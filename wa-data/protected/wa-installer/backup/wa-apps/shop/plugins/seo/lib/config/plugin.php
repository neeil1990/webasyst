<?php

return array(
    'name' => 'SEO',
    'description' => 'Fast and flexible optimization of your shop',
    'img' => 'img/seo.png',
    'vendor' => '934303',
    'frontend' => false,
    'shop_settings' => true,
    'version' => '1.7.1.5',
    'handlers' => array(
        'frontend_head' => 'frontendHead',
        'frontend_category' => 'frontendCategory',
        'frontend_product' => 'frontendProduct',
        'backend_category_dialog' => 'backendCategoryDialog',
        'category_save' => 'categorySave',
        'category_delete' => 'categoryDelete',
        'backend_product_edit' => 'backendProductEdit',
        'product_save' => 'productSave',
        'product_delete' => 'productDelete',
    )
);