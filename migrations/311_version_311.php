<?php defined('BASEPATH') or exit('No direct script access allowed');

if (class_exists('Migration_Version_311', false)) {
    return;
}

class Migration_Version_311 extends App_module_migration
{
    public function up()
    {
        // Version 3.1.1 contains dashboard additions only.
    }
}
