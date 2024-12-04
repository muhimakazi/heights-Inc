<?php

require_once(appSource."/entities/FormFieldCategory.php");

class FormFieldCategoryService {
    
    public static function insert($data){
        return $data->insert($data);
    }
    
    public static function categoryList($selection, $search_query){
        $categoryObj = new FormFieldCategory();
        return $categoryObj->categoryList($selection, $search_query);
    }
    
    public static function updateStatus($status, $id){
        $categoryObj = new FormFieldCategory();
        return $categoryObj->updateStatus($status, $id);
    }
    
    public static function delete($id){
        $status="DELETED";
        return self::updateStatus($status, $id);
    }
    
    public static function rename($name, $id){
        $categoryObj = new FormFieldCategory();
        return $categoryObj->rename($name, $id);
    }
    
    public static function categoryExists($data){
        return $data->categoryExists($data);
    }
    
    public static function conflicts($name, $id){
        $categoryObj = new FormFieldCategory();
        return $categoryObj->conflicts($name, $id);
    }
    
    public static function getCategoryCodeById($id){
        $categoryObj = new FormFieldCategory();
        return $categoryObj->getCategoryCodeById($id);
    }
    
    public static function categoryCodePrefix(){
        // Torus Form Field Category
        return "TFFC";
    }
}

?>