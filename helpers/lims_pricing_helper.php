<?php defined('BASEPATH') or exit('No direct script access allowed');
if (!function_exists('lims_resolve_price')) {
    function lims_resolve_price($client_id, $item_id){
        $CI = &get_instance();
        $contracts = db_prefix().'lims_contracts';
        $prices = db_prefix().'lims_contract_prices';
        $sql = "SELECT cp.fixed_price, c.discount_percent FROM {$contracts} c
                 LEFT JOIN {$prices} cp ON cp.contract_id=c.id AND cp.item_id=?
                 WHERE c.client_id=? AND c.active=1
                   AND (c.valid_from IS NULL OR c.valid_from<=CURDATE())
                   AND (c.valid_to IS NULL OR c.valid_to>=CURDATE())
               ORDER BY c.priority DESC, c.id DESC LIMIT 1";
        $row = $CI->db->query($sql, [$item_id, $client_id])->row();
        if($row && $row->fixed_price !== null){
            return ['price'=>(float)$row->fixed_price, 'source'=>'contract_fixed'];
        }
        if($row && $row->discount_percent){
            $item = $CI->db->where('id',$item_id)->get(db_prefix().'items')->row();
            if($item){
                $price = (float)$item->rate * (1 - (float)$row->discount_percent/100);
                return ['price'=>$price, 'source'=>'contract_percent'];
            }
        }
        $item = $CI->db->where('id',$item_id)->get(db_prefix().'items')->row();
        return ['price'=>(float)($item->rate ?? 0), 'source'=>'item'];
    }
}
/**
 * Επιστρέφει τελική τιμή για ANALYSIS, με προτεραιότητα:
 * 1) LIMS Contracts per client+item+currency
 * 2) Core Items rate (ίδια λογική/νόμισμα)
 *
 * @return array ['price'=>float, 'source'=>'contract'|'item'|'none', 'currency'=>string|null]
 */
function lims_resolve_analysis_price($client_id, $analysis_id, $currency_name = null)
{
    $CI = &get_instance();
    $client_id = (int)$client_id;
    $analysis_id = (int)$analysis_id;

    // Βρες analysis -> item_id
    $analysis = $CI->db->where('id',$analysis_id)->get(db_prefix().'lims_analyses')->row();
    if(!$analysis || !(int)$analysis->item_id){
        return ['price'=>0.0,'source'=>'none','currency'=>$currency_name];
    }
    $item_id = (int)$analysis->item_id;

    // 1) Contracts (αν έχεις ήδη helper lims_resolve_price για items, επαναχρησιμοποίησέ τον)
    if (!function_exists('lims_resolve_price')) {
        // basic inline fallback: ψάξε tbllims_contracts + tbllims_contract_prices
        $CI->db->select('cp.fixed_price, cp.currency')
               ->from(db_prefix().'lims_contracts c')
               ->join(db_prefix().'lims_contract_prices cp','cp.contract_id=c.id','inner')
               ->where('c.active',1)
               ->where('cp.item_id',$item_id);
        if ($client_id>0){ $CI->db->where('c.client_id',$client_id); }
        if ($currency_name){ $CI->db->where('cp.currency',$currency_name); }
        $CI->db->order_by('c.priority','DESC');
        $row = $CI->db->get()->row();
        if ($row){
            return ['price'=>(float)$row->fixed_price, 'source'=>'contract', 'currency'=>$row->currency];
        }
    } else {
        $res = lims_resolve_price($client_id, $item_id, $currency_name); // δικό σου helper για items
        if ($res && isset($res['price']) && $res['price']>0){
            return ['price'=>(float)$res['price'], 'source'=>'contract', 'currency'=>$res['currency'] ?? $currency_name];
        }
    }

    // 2) Fallback σε core item rate
    $item = $CI->db->select('rate')->where('id',$item_id)->get(db_prefix().'items')->row();
    if ($item) {
        return ['price'=>(float)$item->rate, 'source'=>'item', 'currency'=>$currency_name];
    }

    return ['price'=>0.0,'source'=>'none','currency'=>$currency_name];
}