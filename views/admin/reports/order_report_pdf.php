<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1><?php echo _l('lims_order'); ?> #<?php echo (int)$order->id; ?></h1>
<p><?php echo _l('client'); ?>: <?php echo html_escape($order->client_name ?? ''); ?></p>
<p><?php echo _l('date'); ?>: <?php echo !empty($order->created_at) ? _d($order->created_at) : ''; ?></p>

<hr>
<p>Θα γεμίσουμε εδώ πίνακες με tests, αποτελέσματα, cultures κλπ.</p>
