<?php

class blogNbimagepostPluginBackendEditAction extends waViewAction
{

    public function execute(){

        waFiles::create(wa()->getConfig()->getRootPath().'/wa-data/public/blog/img/30x30/');
        $image = waImage::factory(wa()->getConfig()->getRootPath().'/wa-data/public/blog/img/fb332e2a90fe896c564076b84a4ed4d8.jpeg');
        $image->crop(200, 200, 0, 0);
        $image->save(wa()->getConfig()->getRootPath().'/wa-data/public/blog/img/30x30/fb332e2a90fe896c564076b84a4ed4d8.jpeg', 100);
        var_dump($image);


    }

}