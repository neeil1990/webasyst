<?php

class blogAjaxActions extends waJsonActions
{

    function uploadAction()
    {
        if ($_FILES['file']['error']) {
            $files = $_FILES['file']['error'];
        }
        else {
            $format = array('/png','/jpeg');
            if(in_array(strstr($_FILES['file']['type'],'/'), $format)){
                $file_name = md5($_FILES['file']['name']).str_replace('/','.',strstr($_FILES['file']['type'],'/'));
                $files = '/wa-data/public/blog/img/' . $file_name;
                $files_root = $_SERVER['DOCUMENT_ROOT'].$files;
                move_uploaded_file($_FILES['file']['tmp_name'], $files_root);
            }else{
                $files = false;
            }
        }
        $this->response = $files;
    }


    function cropimageAction(){
        $x = waRequest::post('x');
        $y = waRequest::post('y');
        $w = waRequest::post('w');
        $h = waRequest::post('h');
        $id = waRequest::post('id');
        $href = waRequest::post('href');

        if($x and $y and $w and $h and $href){

             $image = waImage::factory(wa()->getConfig()->getRootPath().$href);
             $image->crop($w, $h, $x, $y);
             $image->save(null, 100);
             $model = new blogNbimagepostModel();
             $model->updateById($id, array("image" => $href));
             $this->response = $href;
        }
    }
}