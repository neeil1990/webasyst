<?php
/**
* Класс для работы с таблицей 'shop_wmimageincat_images'
*/
class shopWmimageincatModel extends waModel
{
    protected $table = 'shop_wmimageincat_images';
 
    function getAllofCat($cat_id)
    {
        $result = $this->getByField('category_id', $cat_id, true);
        return $result;
    }
}