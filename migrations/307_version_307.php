<?php defined('BASEPATH') or exit('No direct script access allowed');

// Version 3.0.7 contains dashboard/UI changes only. Perfex still expects a
// migration matching the module version before it completes the update.
if (class_exists('Migration_Version_307', false)) {
    return;
}

class Migration_Version_307 extends App_module_migration
{
    public function up()
    {
        // No database changes are required for this release.
    }
}
