<?php

return array(
    'shop_seo_settings' => array(
        'storefront' => array('varchar', 255, 'null' => 0),
        'name' => array('varchar', 64, 'null' => 0),
        'value' => array('text', 'null' => 1),
        ':keys' => array(
            'PRIMARY' => array('storefront', 'name'),
        )
    ),
    'shop_seo_settings_category' => array(
        'category_id' => array('int', 11, null => 0),
        'storefront' => array('varchar', 255, 'null' => 0),
        'name' => array('varchar', 64, 'null' => 0),
        'value' => array('text', 'null' => 1),
        ':keys' => array(
            'PRIMARY' => array('category_id', 'storefront', 'name'),
        )
    ),
    'shop_seo_settings_product' => array(
        'product_id' => array('int', 11, null => 0),
        'storefront' => array('varchar', 255, 'null' => 0),
        'name' => array('varchar', 64, 'null' => 0),
        'value' => array('text', 'null' => 1),
        ':keys' => array(
            'PRIMARY' => array('product_id', 'storefront', 'name'),
        )
    ),
    'shop_seo_settings_field' => array(
        'id' => array('int', 11, null => 0, 'autoincrement' => 1),
        'name' => array('varchar', 255, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => array('id'),
        )
    )
);