<?php

return array(
    'name' => _wp('Product manager'),
    'description' => _wp('Lock the user for the product'),
    'img'  => 'img/users.png',
    'version' => '1.0',
    'vendor' => 1052580,
    'shop_settings' => true,
    'handlers' => array(
        'backend_product' => 'product_edit',
        'backend_products' => 'backend_products_all',
        'frontend_product' => 'front_product',
        'frontend_head' => 'frontendHead',
    )
);