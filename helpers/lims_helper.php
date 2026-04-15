<?php defined('BASEPATH') or exit('No direct script access allowed');
if (!function_exists('lims_next_barcode')) {
    function lims_next_barcode($prefix=null) {
        $CI = &get_instance();
        if(!$prefix){ $prefix = get_option('lims_barcode_prefix') ?: 'SMP'; }
        $table = db_prefix().'lims_barcode_sequences';
        $row = $CI->db->where('prefix',$prefix)->get($table)->row();
        if(!$row){
            $CI->db->insert($table,['prefix'=>$prefix,'next_number'=>1]);
            $row = (object)['next_number'=>1];
        }
        $num = str_pad($row->next_number, 6, '0', STR_PAD_LEFT);
        $CI->db->where('prefix',$prefix)->update($table,['next_number'=>$row->next_number+1]);
        return $prefix.'-'.$num;
    }
}
if (!function_exists('lims_priority_options')) {
    function lims_priority_options()
    {
        return [
            0 => _l('lims_priority_routine') ?: 'Routine',
            1 => _l('lims_priority_urgent')  ?: 'Urgent',
            2 => _l('lims_priority_stat')    ?: 'STAT',
            3 => _l('lims_priority_low')     ?: 'Low',
        ];
    }
}

if (!function_exists('lims_priority_label')) {
    function lims_priority_label($priority)
    {
        $opts = lims_priority_options();
        $priority = (int)$priority;

        return isset($opts[$priority]) ? $opts[$priority] : $priority;
    }
}
if (!function_exists('lims_uuid_v4')) {
    /**
     * RFC 4122 compliant UUID v4.
     */
    function lims_uuid_v4()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

if (!function_exists('lims_uid_short')) {
    /**
     * Returns a short stable token from a UUID (first 8 hex chars, no dashes).
     */
    function lims_uid_short($uuid)
    {
        $u = strtolower((string)$uuid);
        $u = str_replace('-', '', $u);
        return substr($u, 0, 8);
    }
}

if (!function_exists('lims_random_token')) {
    /**
     * Cryptographically-secure random token (URL-safe).
     */
    function lims_random_token($length = 40)
    {
        $length = (int)$length;
        if ($length < 8) $length = 8;
        // base64url without padding
        $raw = base64_encode(random_bytes((int)ceil($length * 0.75) + 2));
        $raw = strtr($raw, '+/', '-_');
        $raw = rtrim($raw, '=');
        return substr($raw, 0, $length);
    }
}
