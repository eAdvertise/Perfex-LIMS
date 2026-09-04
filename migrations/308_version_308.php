<?php defined('BASEPATH') or exit('No direct script access allowed');

// Version 3.0.8 adds a dashboard widget and requires no schema changes.
if (class_exists('Migration_Version_308', false)) {
    return;
}

class Migration_Version_308 extends App_module_migration
{
    public function up()
    {
        // Intentionally empty.
    }
}
