<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Subjects_model extends App_Model
{
    protected $table;

    protected $allowed_fields = [
        'client_id',
        'primary_contact_id',
        'subject_type',
        'language', // NEW
        'subject_name',
        'first_name',
        'last_name',
        'internal_code',
        'id_number',
        'nationality',
        'gender',
        'social_insurance_no',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'notes',
        'active',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix() . 'lims_subjects';
    }

    public function get($id)
    {
        $id = (int) $id;

        $subject = $this->db
            ->get_where($this->table, ['id' => $id])
            ->row();

        if ($subject) {
            // Αν υπάρχει internal_code και δεν υπάρχει code, φτιάξε alias
            if (isset($subject->internal_code)) {
                $subject->code = $subject->internal_code;
            }
        }

        return $subject;
    }

    public function get_client($subject)
    {
        if (!$subject || empty($subject->client_id)) {
            return null;
        }

        return $this->db
            ->get_where(db_prefix() . 'clients', ['userid' => (int)$subject->client_id])
            ->row();
    }

    public function get_orders($subject_id)
    {
        $p = db_prefix();

        return $this->db
            ->select('o.*, c.company AS client_company')
            ->from($p . 'lims_orders o')
            ->join($p . 'clients c', 'c.userid = o.client_id', 'left')
            ->where('o.subject_id', (int)$subject_id)
            ->order_by('o.id', 'DESC')
            ->get()
            ->result();
    }

    public function get_samples($subject_id)
    {
        $p = db_prefix();

        return $this->db
            ->select('s.*, st.name AS sample_type_name')
            ->from($p . 'lims_samples s')
            ->join($p . 'lims_sample_types st', 'st.id = s.sample_type_id', 'left')
            ->where('s.subject_id', (int)$subject_id)
            ->order_by('s.id', 'DESC')
            ->get()
            ->result();
    }

   /**
     * Δημιουργία Subject
     */
    public function create($data)
	{
		$data = $this->prepare_data($data);

		// Canonical UID for Partner sync
		if (function_exists('lims_uuid_v4')) {
			$data['subject_uid'] = lims_uuid_v4();
		}


		if (!$data) {
			return false;
		}

		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = $data['created_at'];

		$this->db->insert($this->table, $data);

		// Πιο αξιόπιστο για INSERT από το affected_rows()
		$insert_id = $this->db->insert_id();

		// Αν όντως έγινε insert και πήραμε ID
		if ($insert_id) {
			$this->bump_internal_code_counter();
			return (int) $insert_id;
		}

		// Αν κάτι δεν πήγε καλά, γράψε το error στο log για να το δούμε
		$error = $this->db->error();
		log_message(
			'error',
			'LIMS Subjects_model::create DB error: ' .
			($error['code'] ?? '') . ' - ' . ($error['message'] ?? '')
		);

		return false;
	}


    /**
     * Ενημέρωση Subject
     */
    public function update($id, $data)
	{
		$id   = (int) $id;
		$data = $this->prepare_data($data);

		if ($id <= 0 || !$data) {
			return false;
		}

		$data['updated_at'] = date('Y-m-d H:i:s');

		$this->db->where('id', $id)->update($this->table, $data);

		return $this->db->affected_rows() > 0;
	}


    /**
     * Common sanitization
     */
    protected function prepare_data($data)
    {
        if (!is_array($data)) {
            return [];
        }

        $clean = array_intersect_key($data, array_flip($this->allowed_fields));

        // client
        $clean['client_id'] = !empty($clean['client_id']) ? (int)$clean['client_id'] : null;

        // primary contact (αν το χρησιμοποιήσουμε αργότερα)
        if (isset($clean['primary_contact_id'])) {
            $clean['primary_contact_id'] = $clean['primary_contact_id'] !== ''
                ? (int)$clean['primary_contact_id']
                : null;
        }

        // active checkbox
        $clean['active'] = isset($data['active']) && (int)$data['active'] === 1 ? 1 : 0;

        // date_of_birth
        if (!empty($clean['date_of_birth'])) {
            $clean['date_of_birth'] = to_sql_date($clean['date_of_birth']);
        } else {
            $clean['date_of_birth'] = null;
        }

        // country (id από countries)
        if (isset($clean['country']) && $clean['country'] !== '') {
            $clean['country'] = (int)$clean['country'];
        } else {
            $clean['country'] = null;
        }

        // gender normalisation
        if (!empty($clean['gender'])) {
            $g = strtolower(trim($clean['gender']));
            if (!in_array($g, ['male','female','other'], true)) {
                $g = 'other';
            }
            $clean['gender'] = $g;
        }

        // NEW: language validation (Perfex language folder name)
        if (array_key_exists('language', $clean)) {
            $lang = trim((string)$clean['language']);
            if ($lang === '') {
                $clean['language'] = null;
            } else {
                $available = function_exists('get_available_languages') ? get_available_languages() : [];
                if (!empty($available) && !in_array($lang, $available, true)) {
                    $clean['language'] = null;
                } else {
                    $clean['language'] = $lang;
                }
            }
        }

        return $clean;
    }

    /**
     * Γεννήτρια internal code: prefix + next_number (όπως στα invoices)
     * Χρησιμοποιεί τις options:
     *  - lims_subject_prefix
     *  - lims_subject_next_number
     */
    public function generate_internal_code()
    {
        $prefix = get_option('lims_subject_prefix');
        if ($prefix === '') {
            $prefix = 'SUB-';
        }

        $next = (int) get_option('lims_subject_next_number');
        if ($next <= 0) {
            $next = 1;
        }

        // format π.χ. SUB-00001
        $code = $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);

        return $code;
    }

    /**
     * Απλά αυξάνει το lims_subject_next_number κατά 1
     * (δεν χρειάζεται να επιστρέψει code)
     */
    protected function bump_internal_code_counter()
    {
        $next = (int) get_option('lims_subject_next_number');
        if ($next <= 0) {
            $next = 1;
        }

        update_option('lims_subject_next_number', $next + 1);
    }

    /**
     * Quick create από το modal (ελάχιστα πεδία)
     */
    public function add_quick(array $data)
    {
        $p = db_prefix();

        // ---- Internal code (από settings αν δεν σταλεί έτοιμο) ----
        $internal_code = '';

        if (!empty($data['internal_code'])) {
            $internal_code = trim($data['internal_code']);
        } else {
            $internal_code = $this->generate_internal_code();
        }

        // ---- Subject type (από το dropdown του modal) ----
        $allowedTypes = ['patient', 'doctor', 'lab', 'farm', 'restaurant', 'other'];
        $subject_type = !empty($data['subject_type']) ? trim($data['subject_type']) : 'patient';
        if (!in_array($subject_type, $allowedTypes, true)) {
            $subject_type = 'other';
        }

        // NEW: language (default to current staff/system)
        $language = null;
        if (isset($data['language'])) {
            $language = trim((string)$data['language']);
            if ($language === '') {
                $language = null;
            } else {
                $available = function_exists('get_available_languages') ? get_available_languages() : [];
                if (!empty($available) && !in_array($language, $available, true)) {
                    $language = null;
                }
            }
        }
        if ($language === null) {
            $language = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        }

        // ---- Βασικά στοιχεία από το modal ----
        $subject_name = isset($data['subject_name']) ? trim($data['subject_name']) : '';
        $first_name   = isset($data['first_name'])   ? trim($data['first_name'])   : '';
        $last_name    = isset($data['last_name'])    ? trim($data['last_name'])    : '';
        $id_number    = isset($data['id_number'])    ? trim($data['id_number'])    : '';
        $phone        = isset($data['phone'])        ? trim($data['phone'])        : '';
        $email        = isset($data['email'])        ? trim($data['email'])        : '';

        $address      = isset($data['address']) ? trim($data['address']) : '';
        $city         = isset($data['city'])    ? trim($data['city'])    : '';
        $state        = isset($data['state'])   ? trim($data['state'])   : '';
        $zip          = isset($data['zip'])     ? trim($data['zip'])     : '';

        // Χώρα: από το modal αν υπάρχει, αλλιώς default εταιρείας
        $country_id = null;
        if (isset($data['country']) && $data['country'] !== '') {
            $country_id = (int)$data['country'];
        } else {
            $defaultCountry = (int)get_option('invoice_company_country'); // numeric id από settings
            if ($defaultCountry > 0) {
                $country_id = $defaultCountry;
            }
        }

        // DOB από datepicker (προαιρετικό)
        $date_of_birth = null;
        if (!empty($data['date_of_birth'])) {
            $date_of_birth = to_sql_date($data['date_of_birth']);
        }

        // --- Υπάρχων πελάτης (αν επιλέχθηκε από το dropdown) ---
        $client_id           = !empty($data['client_id']) ? (int)$data['client_id'] : null;
        $primary_contact_id  = null;

        // Αν ΔΕΝ υπάρχει client_id, φτιάχνουμε νέο customer + primary contact
        if (!$client_id) {
            $this->load->model('clients_model');

            // Όνομα εταιρείας για τον νέο πελάτη
            $companyName = $subject_name;
            if ($companyName === '') {
                $fullName = trim($first_name . ' ' . $last_name);
                $companyName = $fullName !== '' ? $fullName : $internal_code;
            }

            // Ελάχιστα safe πεδία για νέο client
            $clientData = [
                'company'     => $companyName,
                'phonenumber' => $phone,
                'address'     => $address,
                'city'        => $city,
                'state'       => $state,
                'zip'         => $zip,
                'country'     => $country_id,
                'active'      => 1,
                'addedfrom'   => is_staff_logged_in() ? get_staff_user_id() : 0,
                'datecreated' => date('Y-m-d H:i:s'),
            ];

            // 2ο arg = custom fields (κενό)
            $client_id = $this->clients_model->add($clientData, []);

            if ($client_id) {
                // Δημιουργία ΜΙΑΣ Primary contact (πολύ basic, χωρίς portal access)
                $contactFirstname = $first_name !== '' ? $first_name : $companyName;
                $contactLastname  = $last_name;

                $contactInsert = [
                    'userid'      => (int)$client_id,
                    'is_primary'  => 1,
                    'firstname'   => $contactFirstname,
                    'lastname'    => $contactLastname,
                    'email'       => $email,
                    'phonenumber' => $phone,
                    'title'       => '',
                    'datecreated' => date('Y-m-d H:i:s'),
                    'direction'   => 0,
                    'active'      => 0, // 0 = χωρίς πρόσβαση στο portal, μόνο για επικοινωνία
                ];

                $this->db->insert($p . 'contacts', $contactInsert);
                if ($this->db->affected_rows() > 0) {
                    $primary_contact_id = (int)$this->db->insert_id();
                }
            }
        }

        // ---- Τελικό insert για τον Subject ----
        $now = date('Y-m-d H:i:s');

        $insert = [
            'client_id'          => $client_id ?: null,
            'primary_contact_id' => $primary_contact_id ?: null,
            'subject_type'       => $subject_type,
            'language'           => $language, // NEW
            'subject_name'       => $subject_name !== '' ? $subject_name : null,
            'first_name'         => $first_name !== '' ? $first_name : null,
            'last_name'          => $last_name !== '' ? $last_name : null,
            'internal_code'      => $internal_code,
            'id_number'          => $id_number !== '' ? $id_number : null,
            'date_of_birth'      => $date_of_birth,
            'phone'              => $phone !== '' ? $phone : null,
            'email'              => $email !== '' ? $email : null,
            'address'            => $address !== '' ? $address : null,
            'city'               => $city !== '' ? $city : null,
            'state'              => $state !== '' ? $state : null,
            'zip'                => $zip !== '' ? $zip : null,
            'country'            => $country_id ?: null,
            'active'             => 1,
            'created_at'         => $now,
            'updated_at'         => $now,
        ];

        $this->db->insert($this->table, $insert);
        $subject_id = (int)$this->db->insert_id();

        if ($subject_id) {
            // Αν δημιουργήσαμε εμείς το internal_code, bump το counter στα options
            $this->bump_internal_code_counter();

            return $subject_id;
        }

        // Σε περίπτωση λάθους, γράψε στο log για debug
        $error = $this->db->error();
        log_message(
            'error',
            'LIMS Subjects_model::add_quick DB error: '
            . ($error['code'] ?? '') . ' - ' . ($error['message'] ?? '')
        );

        return false;
    }
	  

    /**
     * Return active languages for dropdown, based on Perfex Localization settings.
     * - If an "enabled languages" list exists in tbloptions, it's used.
     * - Else, if a "disabled languages" list exists, we use (all - disabled).
     * - Else, fall back to all language folders under application/language.
     *
     * Output format: [['id' => 'english', 'name' => 'English'], ...]
     */
    public function get_active_languages_dropdown()
    {
        $active = $this->get_active_language_folders();

        $out = [];
        foreach ($active as $langFolder) {
            $out[] = [
                'id'   => $langFolder,
                'name' => $this->humanize_language_folder($langFolder),
            ];
        }

        return $out;
    }

    /**
     * Active language folders (e.g. ['english','greek']) as configured in Setup->Settings->Localization.
     */
    public function get_active_language_folders()
    {
        $available = $this->list_language_folders();

        if (empty($available)) {
            return [];
        }

        // Detect enabled/disabled lists in tbloptions
        $lists = $this->detect_language_lists_from_options($available);

        $enabled  = $lists['enabled'];
        $disabled = $lists['disabled'];

        if (!empty($enabled)) {
            $active = $enabled;
        } elseif (!empty($disabled)) {
            $active = array_values(array_diff($available, $disabled));
        } else {
            $active = $available;
        }

        // Ensure default language is always present (defensive)
        $default = (string) get_option('active_language');
        if ($default === '') {
            $default = (string) get_option('default_language');
        }
        if ($default !== '' && in_array($default, $available, true) && !in_array($default, $active, true)) {
            array_unshift($active, $default);
            $active = array_values(array_unique($active));
        }

        return $active;
    }

    /**
     * List available language folders under application/language.
     */
    protected function list_language_folders()
    {
        $base = rtrim(APPPATH, '/\\') . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR;
        $dirs = glob($base . '*', GLOB_ONLYDIR);

        if (!is_array($dirs)) {
            return [];
        }

        $langs = [];
        foreach ($dirs as $dir) {
            $name = basename($dir);
            if ($name === '.' || $name === '..') {
                continue;
            }
            $langs[] = $name;
        }

        sort($langs, SORT_NATURAL | SORT_FLAG_CASE);

        return $langs;
    }

    /**
     * Detect "enabled" and "disabled" language lists from tbloptions, in a robust way.
     */
    protected function detect_language_lists_from_options(array $available)
    {
        $enabledCandidates  = [];
        $disabledCandidates = [];

        $rows = $this->db
            ->select('name, value')
            ->like('name', 'language')
            ->get(db_prefix() . 'options')
            ->result_array();

        foreach ($rows as $row) {
            $name = strtolower((string) ($row['name'] ?? ''));
            $val  = (string) ($row['value'] ?? '');

            $list = $this->parse_languages_value($val);
            if (empty($list)) {
                continue;
            }

            // Keep only real language folders
            $list = array_values(array_intersect($list, $available));
            if (empty($list)) {
                continue;
            }

            if (strpos($name, 'enabled') !== false
                || strpos($name, 'active_languages') !== false
                || (strpos($name, 'languages') !== false && strpos($name, 'disabled') === false && strpos($name, 'disable') === false)
            ) {
                $enabledCandidates[$name] = $list;
            }

            if (strpos($name, 'disabled') !== false || strpos($name, 'disable') !== false) {
                $disabledCandidates[$name] = $list;
            }
        }

        // Pick the "best" candidate (the one with the most entries)
        $enabled  = [];
        $disabled = [];

        if (!empty($enabledCandidates)) {
            uasort($enabledCandidates, function ($a, $b) {
                return count($b) <=> count($a);
            });
            $enabled = array_values(reset($enabledCandidates));
        }

        if (!empty($disabledCandidates)) {
            uasort($disabledCandidates, function ($a, $b) {
                return count($b) <=> count($a);
            });
            $disabled = array_values(reset($disabledCandidates));
        }

        return ['enabled' => $enabled, 'disabled' => $disabled];
    }

    /**
     * Parse languages stored in options.
     * Supports JSON array, serialized array, CSV, newline-separated.
     */
    protected function parse_languages_value($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return [];
        }

        // JSON array
        if (strlen($value) > 0 && $value[0] === '[') {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return array_values(array_filter(array_map('strval', $decoded)));
            }
        }

        // PHP serialized array
        if (preg_match('/^a:\d+:{/i', $value)) {
            $decoded = @unserialize($value);
            if (is_array($decoded)) {
                return array_values(array_filter(array_map('strval', $decoded)));
            }
        }

        // CSV / newline / pipe
        $sep = null;
        foreach ([",", "\n", "\r\n", "|", ";"] as $candidate) {
            if (strpos($value, $candidate) !== false) {
                $sep = $candidate;
                break;
            }
        }

        if ($sep !== null) {
            $parts = array_map('trim', explode($sep, $value));
            $parts = array_values(array_filter($parts, function ($v) {
                return $v !== '';
            }));
            return array_map('strval', $parts);
        }

        // Single value
        return [$value];
    }

    protected function humanize_language_folder($folder)
    {
        $folder = (string) $folder;
        $folder = str_replace(['_', '-'], ' ', $folder);
        $folder = preg_replace('/\s+/', ' ', $folder);
        $folder = trim($folder);

        return mb_convert_case($folder, MB_CASE_TITLE, 'UTF-8');
    }

	
}
