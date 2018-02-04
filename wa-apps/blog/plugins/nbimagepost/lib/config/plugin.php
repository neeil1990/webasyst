<?php

return array(
    'name' => 'Изображение (миниатюра) для поста приложения Блог',
    'description' => 'Добавляет к записи блога загрузку изображения, и отображает изображение на сайте.',
    'img'  => 'img/brands.png',
    'version' => '1.0.0',
    'handlers' => array(
        "backend_post_edit" => "backendPostEdit",
        "backend_stream" => "backendStream",
        "backend_assets" => "blogHeader",
    )
);