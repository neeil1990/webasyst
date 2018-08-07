<?php

return array(
    'shop_nbpopupform_forminput' => array(
        'id' => array('int', 11),
        'id_form' => array('int', 11, 'null' => 0),
        'names' => array('varchar', 255, 'null' => 0),
        'type' => array('varchar', 255, 'null' => 0),
        'required' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id'
        )
    ),
    'shop_nbpopupform_forms' => array(
        'id' => array('int', 11),
        'name' => array('varchar', 255, 'null' => 0),
        'description' => array('text'),
        ':keys' => array(
            'PRIMARY' => 'id'
        )
    )
);