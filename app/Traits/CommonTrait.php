<?php
    namespace App\Traits;
    trait CommonTrait{
        public static function convDateToDateTime($date,$time_type='00:00:00'){
            return date('Y-m-d '.$time_type,strtotime($date));
        }
        public static function getImageFileBasePath(){
            return "http://localhost:8000/product-images/";
        }
        public static function uploadMultipleFiles($files,$publicPath){
            $data['file'] =[];
            foreach($files as $file){
                    $file_name = time().rand(1,100).'.'.$file->getClientOriginalExtension();
                    $file->move(public_path($publicPath), $file_name); //university/website/pdf
                    $data['file'][] = $file_name;
            }
            return $data;
        }
    }
?>
