<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<?php
// helpers
$subject_id = (int)$subject->id;

// Τίτλος header (όπως στο client)
$headerTitle = '#' . $subject_id . ' ' . $title;

// Display name για το πάνω μέρος του panel
$displayName = $title;

// subject type label
$typeLabel = '';
if (!empty($subject->subject_type)) {
    $typeLabel = ucfirst($subject->subject_type);
}

// full name / subject name
$fullName = '';
if ($subject->subject_type === 'patient') {
    $fn = trim(($subject->first_name ?? '') . ' ' . ($subject->last_name ?? ''));
    $fullName = $fn !== '' ? $fn : ($subject->subject_name ?? '');
} else {
    $fullName = $subject->subject_name ?: '';
}
if ($fullName === '') {
    $fullName = $headerTitle;
}

// country label
$countryName = '';
if (!empty($subject->country)) {
    $country = get_country($subject->country);
    if ($country) {
        $countryName = $country->short_name;
    }
}

// ηλικία από date_of_birth (προαιρετικά)
$ageText = '';
if (!empty($subject->date_of_birth) && $subject->date_of_birth !== '0000-00-00') {
    try {
        $dob  = new DateTime($subject->date_of_birth);
        $now  = new DateTime();
        $diff = $now->diff($dob);
        $ageText = $diff->y . ' ' . _l('years');
    } catch (Exception $e) {
        $ageText = '';
    }
}
?>

<div id="wrapper" class="customer_profile">
  <div class="content">

    <!-- μικρό spacing block όπως στο client.php -->
    <div class="md:tw-w-[calc(100%-theme(width.64)+theme(spacing.16))] [&_div:last-child]:tw-mb-6"></div>

    <!-- header με #ID και dropdown για edit -->
    <div class="md:tw-max-w-64 tw-w-full">
      <h4 class="tw-text-lg tw-font-bold tw-text-neutral-800 tw-mt-0">
        <div class="tw-space-x-3 tw-flex tw-items-center">
          <span class="tw-truncate">
            <?php echo e($headerTitle); ?>
          </span>

          <div class="btn-group">
            <a href="#" class="dropdown-toggle btn-link" data-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false">
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li>
                <a href="<?php echo admin_url('lims/subjects/create/' . $subject_id); ?>">
                  <i class="fa fa-pencil-square-o"></i>
                  <?php echo _l('edit'); ?>
                </a>
              </li>
            </ul>
          </div>

        </div>
      </h4>
    </div>

    <div class="md:tw-flex md:tw-gap-6">

      <!-- ΑΡΙΣΤΕΡΟ ΚΑΘΕΤΟ MENU -->
      <div class="md:tw-max-w-64 tw-w-full">
        <div class="tw-mt-6 tw-flex tw-flex-col tw-space-y-1">

          <?php
		  
          $items = [
			'profile' => [
				'icon'  => 'fa fa-user',
				'label' => _l('lims_profile') ?: 'Profile',
            ],
            'orders' => [
				'icon'  => 'fa fa-list-alt',
				'label' => _l('lims_orders') ?: 'Orders',
            ],
            'samples'=> [
				'icon'  => 'fa fa-flask',
				'label' => _l('lims_samples') ?: 'Samples',
            ],
            'invoices' => [
				'icon'  => 'fa fa-file-invoice',
				'label' => _l('invoices') ?: 'Invoices',
            ],
            'creditnotes' => [
				'icon'  => 'fa fa-file',
				'label' => _l('credit_notes') ?: 'Credit Notes',
            ], 
            'receipts_payments' => [
              'icon'  => 'fa fa-receipt',
              'label' => _l('lims_subject_receipts_or_payments') ?: 'Receipts / Payments',
            ],
            'waybills' => [
              'icon'  => 'fa fa-truck',
              'label' => _l('delivery_notes') ?: 'Waybills',
            ],
            'notes' => [
				'icon'  => 'fa fa-note-sticky',
				'label' => _l('notes'),
            ],
            'files' => [
				'icon'  => 'fa fa-paperclip',
				'label' => _l('customer_attachments') ?: 'Files',
            ],
            'reminders' => [
				'icon'  => 'fa fa-bell',
				'label' => _l('reminders'),
            ],
          ];
          
          foreach ($items as $slug => $cfg):
              $active = ($group === $slug);
              $href   = admin_url('lims/subjects/view/' . $subject_id . '?group=' . $slug);
          ?>
          <a href="<?php echo $href; ?>"
             class="tw-flex tw-items-center tw-justify-between tw-rounded-lg tw-px-3 tw-py-2 tw-text-sm
                    tw-border tw-border-transparent hover:tw-border-neutral-200 hover:tw-bg-neutral-50
                    <?php echo $active
                        ? 'tw-bg-neutral-50 tw-border-neutral-200 tw-font-medium tw-text-neutral-900'
                        : 'tw-text-neutral-600'; ?>">
            <span class="tw-flex tw-items-center tw-space-x-2">
              <i class="<?php echo $cfg['icon']; ?> tw-text-xs"></i>
              <span><?php echo $cfg['label']; ?></span>
            </span>
          </a>
          <?php endforeach; ?>

        </div>
      </div>

      <!-- ΔΕΞΙΑ ΠΕΡΙΕΧΟΜΕΝΟ ΑΝΑΛΟΓΑ ΜΕ ΤΟ group -->
      <div class="tw-mt-12 md:tw-mt-0 tw-w-full tw-max-w-6xl">

        <div class="panel_s">
          <div class="panel-body">
            <?php
              $panel_path = 'lims/admin/subjects/panels/';
              switch ($group) {
                case 'orders':
                  $this->load->view($panel_path . 'orders');
                  break;
                case 'samples':
                  $this->load->view($panel_path . 'samples');
                  break;
                case 'invoices':
                  $this->load->view($panel_path . 'invoices');
                  break;
                case 'creditnotes':
                  $this->load->view($panel_path . 'creditnotes');
                  break;
                case 'receipts_payments':
                  $this->load->view($panel_path . 'receipts_or_payments');
                  break;
				case 'receipts_payments':
                  $this->load->view($panel_path . 'receipts_or_payments');
                  break;
                case 'waybills':
                  $this->load->view($panel_path . 'waybills');
                  break;
                case 'notes':
                  $this->load->view($panel_path . 'notes');
                  break;
                case 'files':
                  $this->load->view($panel_path . 'files');
                  break;
                case 'reminders':
                  $this->load->view($panel_path . 'reminders');
                  break;
                case 'profile':
                default:
                  $this->load->view($panel_path . 'profile');
                  break;
              }
            ?>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

<?php init_tail(); ?>
</body>
</html>
