<?php

class shopProductbrandsPluginBackendSaveController extends waJsonController
{
    public function execute()
    {
        $id = waRequest::get('id');
        $data = waRequest::post();



        $brands_model = new shopProductbrandsModel();
        $brand = $brands_model->getById($id);

        $feature_model = new shopFeatureValuesVarcharModel();
        $f = $feature_model->getById($id);

        // change name of the brand, save new value for feature
        if ($data['name'] != $f['value']) {
            $feature_model->updateById($id, array('value' => $data['name']));
        }

        if (empty($data['image']) && $brand && $brand['image']) {
            waFiles::delete(wa()->getDataPath('brands/'.$id, true, 'shop', false));
        }

        if (empty($data['img_flag_country']) && $brand && $brand['img_flag_country']) {
            waFiles::delete(wa()->getDataPath('brands/'.$id.'/flag', true, 'shop', false));
        }

        if (empty($data['img_home_brand']) && $brand && $brand['img_home_brand']) {
            waFiles::delete(wa()->getDataPath('brands/'.$id.'/home', true, 'shop', false));
        }

        $data['hidden'] = empty($data['hidden']) ? 0 : 1;
        if (waRequest::post('allow_filter')) {
            $data['filter'] = implode(',', ifset($data['filter'], array()));
        } else {
            $data['filter'] = null;
        }

        $data['enable_sorting'] = waRequest::post('enable_sorting') ? 1 : 0;

        $img_home_brand_file = waRequest::file('img_home_brand_file');
        if ($img_home_brand_file->uploaded() && in_array(strtolower($img_home_brand_file->extension), array('jpg', 'jpeg', 'png', 'gif'))) {
            $this->response['img_home_brand'] = $data['img_home_brand'] = '.'.$img_home_brand_file->extension;
            $path = wa()->getDataPath('brands/'.$id.'/home/', true, 'shop');
            $img_home_brand_file->moveTo($path, $id.$data['img_home_brand']);
            $this->response['image_url'] = wa()->getDataUrl('brands/'.$id.'/home/'.$id.$data['img_home_brand'], true, 'shop').'?v'.time();

            $sizes = trim(wa()->getSetting('sizes', '', array('shop', 'productbrands')));
            if ($sizes) {
                $sizes = explode(';', $sizes);
                foreach ($sizes as $size) {
                    if (!$size) {
                        continue;
                    }
                    if ($thumb_img = shopImage::generateThumb($path.$id.$data['img_home_brand'], $size)) {
                        $thumb_img->save($path.$id.'.'.$size.$data['img_home_brand']);
                    }
                }
            }
        }


        $image = waRequest::file('image_file');
        if ($image->uploaded() && in_array(strtolower($image->extension), array('jpg', 'jpeg', 'png', 'gif'))) {
            $this->response['image'] = $data['image'] = '.'.$image->extension;
            $path = wa()->getDataPath('brands/'.$id.'/', true, 'shop');
            $image->moveTo($path, $id.$data['image']);
            $this->response['image_url'] = wa()->getDataUrl('brands/'.$id.'/'.$id.$data['image'], true, 'shop').'?v'.time();

            $sizes = trim(wa()->getSetting('sizes', '', array('shop', 'productbrands')));
            if ($sizes) {
                $sizes = explode(';', $sizes);
                foreach ($sizes as $size) {
                    if (!$size) {
                        continue;
                    }
                    if ($thumb_img = shopImage::generateThumb($path.$id.$data['image'], $size)) {
                        $thumb_img->save($path.$id.'.'.$size.$data['image']);
                    }
                }
            }
        }

        $img_flag_country_file = waRequest::file('img_flag_country_file');
        if ($img_flag_country_file->uploaded() && in_array(strtolower($img_flag_country_file->extension), array('jpg', 'jpeg', 'png', 'gif'))) {
            $this->response['img_flag_country'] = $data['img_flag_country'] = '.'.$img_flag_country_file->extension;
            $path = wa()->getDataPath('brands/'.$id.'/flag/', true, 'shop');
            $img_flag_country_file->moveTo($path, $id.$data['img_flag_country']);
            $this->response['image_url'] = wa()->getDataUrl('brands/'.$id.'/flag/'.$id.$data['img_flag_country'], true, 'shop').'?v'.time();

            $sizes = trim(wa()->getSetting('sizes', '', array('shop', 'productbrands')));
            if ($sizes) {
                $sizes = explode(';', $sizes);
                foreach ($sizes as $size) {
                    if (!$size) {
                        continue;
                    }
                    if ($thumb_img = shopImage::generateThumb($path.$id.$data['img_flag_country'], $size)) {
                        $thumb_img->save($path.$id.'.'.$size.$data['img_flag_country']);
                    }
                }
            }
        }

        if ($brand) {
            $data['url'] = $data['url'];
            $brands_model->updateById($id, $data);
        } else {
            $data['url'] = $this->rusToLat($data['name']);
            $data['id'] = $id;
            $brands_model->insert($data);
        }
    }

    public function display()
    {
        $this->getResponse()->sendHeaders();
        if (!$this->errors) {
            $data = array('status' => 'ok', 'data' => $this->response);
            echo json_encode($data);
        } else {
            echo json_encode(array('status' => 'fail', 'errors' => $this->errors));
        }
    }

    public function rusToLat($title){
        $str = mb_strtolower($title);
        $iso = array(
            "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
            "Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
            "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
            "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
            "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
            "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
            "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
            "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
            "е"=>"e","ё"=>"yo","ж"=>"zh",
            "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
            "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
            "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
            "—"=>"-","«"=>"","»"=>"","…"=>""
        );
        $text = strtr($str, $iso);
        return str_replace(' ', '-', $text);
    }

}