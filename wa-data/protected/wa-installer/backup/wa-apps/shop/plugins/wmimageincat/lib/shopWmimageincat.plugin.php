<?php
    class shopWmimageincatPlugin extends shopPlugin
    {   
        /**
        *Преобразование массива данных в строку
        *@param data-массив со значениями ширины и высоты изображения
        *@return строка вида "width X height"
        */
        protected function convert_to_string($data)
        {
            if(!is_array($data)){
                return $data;
            }else if(is_array($data)){
                $result = implode(' X ', $data);
                return $result;
            }
        }

    /**
    * Метод для хука "backend_category_dialog". Выводит список для манипуляции с изображениями в окне настроек категории.
    *@param  $categoty - данные о категории из базы данных
    *@return   CategoryFields.html  
    */
    public function backendCategoryDialog($category)
    {
        $view = wa()->getView();

        $plugin = wa()->getPlugin('wmimageincat');
        $model = new shopWmimageincatModel();
   
        $banner = $this->convert_to_string($plugin->getSettings('banner'));
        $image = $this->convert_to_string($plugin->getSettings('image'));
        $icon = $this->convert_to_string($plugin->getSettings('icon'));
        $tmp_sizes = array($banner, $image, $icon);   
        $sizes = compact('banner', 'image', 'icon', $tmp_sizes);

        $images = $model->getAllofCat($category['id']);
        $images_flag = array('banner' => false, 'image'=>false, 'icon'=>false);
        if(!empty($images)){
            $keys = array_keys($images);
            $values = array_values($images);
            foreach($images as $key => $val)
            {
                $images_flag[$val['type_image']] = true;
                $keys[$key] = $val['type_image'];	  
            }

            $images = array_combine($keys, $values);	
            $view->assign('images', $images);	
        }
     
        $view->assign('images_flag', $images_flag);
        $view->assign('sizes', $sizes);
        $view->assign('cat_id', $category['id']);
        $path = wa()->getAppPath('plugins/wmimageincat/templates/','shop');
        $content = $view->fetch($path.'CategoryFields.html');
        return  $content;
    }

    /**
    *Сохранение изображения
    *@param $file - файл выбранного изображения;
    * $categoty - данные о категории из базы данных;
    * $type - тип изображения (banner || image || icon);
    */
    protected function SaveImageData($file, $category, $type)
    {
        $model = new shopWmimageincatModel();
        $plugin = wa()->getPlugin('wmimageincat');
	
        $data=array(
            'category_id' => $category['id'],
            'upload_datetime' => date('Y-m-d H:i:s'),
            'file_name' => basename($file->name),
            'size' => $file->size,
            'ext' => $file->extension
        );
			

        try{
            $image = $file->waImage();
            $path = wa()->getDataPath("wmimageincatPlugin/categories/{$category['id']}/", true, 'shop');
            $original_path = wa()->getDataPath("wmimageincatPlugin/categories/{$category['id']}/", false, 'shop');
            $data['width'] = $image->width;
            $data['height'] = $image->height;
            $data['type_image'] = $type;

            $res = $model->insert($data);
            if($res){
                $resize = $plugin->getSettings($type);
                $image->save($original_path.$type.'_'.$res.'.'.$data['ext']);
                $image = shopCreatethumbnails::generateThumb($original_path.$type.'_'.$res.'.'.$data['ext'], $resize);
                $image->save($path.$type.'_'.$res.'.'.$data['ext']);
            }
        } catch (waException $e) {
            echo "%wmimageincat_plugin%Файл {$data['file_name']} не является изображением, либо произошла другая ошибка -  ".$e->getMessage()."%wmimageincat_plugin%";
        }        
    }

    /**
    *Метод для хука "category_save". 
    *@param $category-данные о категории из базы данных
    */
    public function SaveCategorySettings($category)
    {
	    
			$image_type=array();
			$image_type['banner'] = waRequest::file('wmimageincat_banner_file');
			$image_type['image'] = waRequest::file('wmimageincat_image_file');
			$image_type['icon'] = waRequest::file('wmimageincat_icon_file');			
			
			foreach($image_type as $key => $val)
			{
        		if(($val->uploaded())){
					$this->SaveImageData($val, $category, $key);
				}
			}
	}

    /**
    *Метод выводит ссылку на изображение определённого типа для категории
    *@param int $id - идентификатор категории
    * string $type - banner || image|| icon
    */
    public static function getCategoryImage($id, $type)
    {
        $model = new shopWmimageincatModel();
        $path = wa()->getDataUrl("wmimageincatPlugin/categories/{$id}/", true, 'shop');
        $image = $model->getByField(array('category_id'=>$id, 'type_image'=>$type));
        if($image){
            return $path.$type.'_'.$image['id'].'.'.$image['ext'];
        }else{
            return false;
        }
    }

    /**
    *Метод для вывода массива изображений для всех категрий
    *@return array(
    *     [(int) categoty_id]=>array(
    *            ['banner'] => (string) link,
    *            ['image'] => (string) link,
    *            ['icon'] => (string) link,
    *     ),.........
    *);
    */
    public static function getCategoryImageObj()
    {
        $model = new shopWmimageincatModel();
        $query = $model->query('SELECT id, type_image, category_id, ext FROM shop_wmimageincat_images')->fetchAll('id');
        
        $result = array();
        foreach($query as $key=>$val)
        {
            $path = wa()->getDataUrl("wmimageincatPlugin/categories/{$val['category_id']}/", true, 'shop');
            if(!isset($result[$val['category_id']])){
                $result[$val['category_id']] = array();
                $result[$val['category_id']][$val['type_image']] = $path.$val['type_image'].'_'.$key.'.'.$val['ext'];
            }else{
                if(empty($result[$val['category_id']][$val['type_image']])){
                    $result[$val['category_id']][$val['type_image']] = $path.$val['type_image'].'_'.$key.'.'.$val['ext'];
                }
            }
        }
        return $result;		
    }

}