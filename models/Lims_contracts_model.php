<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lims_contracts_model extends App_Model
{
    protected $table;
    protected $prices;

    public function __construct()
    {
        parent::__construct();
        $this->table  = db_prefix().'lims_contracts';
        $this->prices = db_prefix().'lims_contract_prices';
    }

    public function get($id)
    {
        $c = $this->db->where('id', $id)->get($this->table)->row();
        if ($c) {
            $c->prices = $this->db->where('contract_id', $id)->get($this->prices)->result();
        }
        return $c;
    }

    public function all($client_id = null)
    {
        if ($client_id) {
            $this->db->where('client_id', $client_id);
        }
        return $this->db
            ->order_by('priority', 'DESC')
            ->order_by('id', 'DESC')
            ->get($this->table)->result();
    }

    /**
     * Save contract + prices
     * $data structure (από τη φόρμα):
     *  - client_id (optional int)
     *  - name (required string)
     *  - code (optional string)
     *  - discount_percent (optional float)
     *  - active (checkbox)
     *  - priority (int)
     *  - valid_from (optional date)
     *  - valid_to (optional date)
     *  - item_id[] (array of item ids, may include empty values)
     *  - fixed_price[CUR][] (array parallel to item_id for κάθε currency)
     */
    public function save($data, $id = null)
    {
        // sanitize head
        $contract = [
            'client_id'        => (int)($data['client_id'] ?? 0), // optional
            'name'             => trim($data['name'] ?? ''),
            'code'             => ($data['code'] ?? null) ?: null,
            'discount_percent' => ($data['discount_percent'] === '' ? null : (float)$data['discount_percent']),
            'active'           => isset($data['active']) ? 1 : 0,
            'priority'         => (int)($data['priority'] ?? 0),
            'valid_from'       => !empty($data['valid_from']) ? $data['valid_from'] : null,
            'valid_to'         => !empty($data['valid_to']) ? $data['valid_to'] : null,
            'notes'            => $data['notes'] ?? null,
        ];

        if ($contract['name'] === '') {
            throw new Exception(_l('lims_error_generic') . ' (Missing contract name)');
        }

        // normalize price matrix
        $item_ids = (array)($data['item_id'] ?? []);
        $fixed_price = (array)($data['fixed_price'] ?? []); // [ 'EUR' => [p0, p1,...], 'USD'=>[...] ]

        // remove completely empty rows + build rows
        $rows = [];             // each row: ['item_id'=>int, 'prices'=>['CUR'=>float,...]]
        $currencies = array_keys($fixed_price);
        $unique_items = [];

        for ($i = 0; $i < count($item_ids); $i++) {
            $iid = (int)$item_ids[$i];
            // Skip row with no item selected
            if ($iid <= 0) { continue; }

            // Collect non-empty prices per currency for this row index
            $rowPrices = [];
            foreach ($currencies as $cur) {
                $arr = $fixed_price[$cur] ?? [];
                $val = isset($arr[$i]) ? $arr[$i] : '';
                if ($val !== '' && $val !== null) {
                    $rowPrices[$cur] = (float)$val;
                }
            }

            // If no prices filled at all, still keep the row? -> business decision:
            // We will keep it only if discount is set (global) or later logic might use item presence.
            // For now, keep it ONLY if at least one price is provided:
            if (empty($rowPrices)) { continue; }

            // Avoid duplicate items: keep first occurrence, ignore next ones
            if (isset($unique_items[$iid])) {
                // merge prices into the first row if currency not set there
                $firstIndex = $unique_items[$iid];
                foreach ($rowPrices as $cur => $price) {
                    if (!isset($rows[$firstIndex]['prices'][$cur])) {
                        $rows[$firstIndex]['prices'][$cur] = $price;
                    }
                }
                continue;
            }

            $unique_items[$iid] = count($rows);
            $rows[] = ['item_id' => $iid, 'prices' => $rowPrices];
        }

        // Server-side limit: rows must not exceed total system items
        $total_items = (int)$this->db->count_all_results(db_prefix().'items');
        if ($total_items > 0 && count($rows) > $total_items) {
            throw new Exception('Too many rows compared to available items.');
        }

        // Transactional save
        $this->db->trans_start();

        if ($id) {
            $this->db->where('id', $id)->update($this->table, $contract);
        } else {
            $this->db->insert($this->table, $contract);
            $id = $this->db->insert_id();
        }

        // reset prices
        $this->db->where('contract_id', $id)->delete($this->prices);

        // insert prices (one row per currency)
        foreach ($rows as $r) {
            $iid = (int)$r['item_id'];
            foreach ($r['prices'] as $cur => $price) {
                $this->db->insert($this->prices, [
                    'contract_id' => $id,
                    'item_id'     => $iid,
                    'fixed_price' => (float)$price,
                    'currency'    => (string)$cur,
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            throw new Exception(_l('lims_error_generic'));
        }

        return $id;
    }

    public function delete($id)
    {
        $this->db->where('contract_id', $id)->delete($this->prices);
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows() > 0;
    }
	public function set_active($id, $active)
	{
		// μόνο το active, χωρίς updated_at (για να μην σκάει αν δεν υπάρχει το πεδίο)
		$this->db->where('id', (int)$id)->update($this->table, [
			'active' => $active ? 1 : 0,
		]);
		return $this->db->affected_rows() >= 0;
	}
	public function add_quick($data)
	{
		$insert = [
			'name'        => $data['name'] ?? null,
			'start_date'  => !empty($data['start_date']) ? to_sql_date($data['start_date']) : null,
			'end_date'    => !empty($data['end_date']) ? to_sql_date($data['end_date']) : null,
			'description' => $data['description'] ?? null,
			'created_at'  => date('Y-m-d H:i:s'),
		];

		$this->db->insert(db_prefix().'lims_contracts', $insert);

		return $this->db->insert_id();
	}

}
