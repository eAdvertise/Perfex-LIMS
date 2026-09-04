<?php defined('BASEPATH') or exit('No direct script access allowed');

if (class_exists('Migration_Version_309', false)) {
    return;
}

class Migration_Version_309 extends App_module_migration
{
    public function up()
    {
        // Version 3.0.9 contains dashboard additions only.
    }
}
