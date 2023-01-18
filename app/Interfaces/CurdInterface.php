<?php
    namespace App\Interfaces;
    interface CrudInterface{
        public function createOrUpdate();
        public function find($id);
        public function list();
    }
?>
