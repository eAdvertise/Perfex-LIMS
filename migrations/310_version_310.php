<?php defined('BASEPATH') or exit('No direct script access allowed');

if (class_exists('Migration_Version_310', false)) {
    return;
}

class Migration_Version_310 extends App_module_migration
{
    public function up()
    {
        // Version 3.1.0 contains dashboard additions only.
    }
}
