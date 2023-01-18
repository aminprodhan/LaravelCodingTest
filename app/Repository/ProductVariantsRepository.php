<?php
    namespace App\Repository;
    use App\Interfaces\CrudInterface;
    use App\Models\Product;
use App\Models\Variant;

    class ProductVariantsRepository implements CrudInterface{
        public function createOrUpdate(array $data=[]){
        }
        public function find($id){
        }
        public function list(){
            return Variant::get();
        }
    }
?>
