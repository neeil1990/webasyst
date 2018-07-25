<?php

class shopProductmanagerPluginBackendEditAction extends waViewAction
{




    public function execute(){


        $product = new shopProductModel();

        $manager_id = waRequest::post('manager_id', "int");
        $product_id = waRequest::post('product_id');

        if(!is_array($product_id)){
            $collection = new shopProductsCollection($product_id);
            $product_coll = $collection->getProducts();
            unset($product_id);
            $product_id = array();
            foreach($product_coll as $prod){
                $product_id[] = $prod['id'];
            }
        }

        foreach($product_id as $id){
            if($id)
            $product->updateById($id,array("manager" => $manager_id));
        }
        print 1;
    }

}