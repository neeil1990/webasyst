<?php

class shopProductmanagerPluginBackendEditController extends waJsonController
{




    public function execute(){

        $product = new shopProductModel(false);

        $manager_id = strip_tags(waRequest::post('manager_id', "int"));
        $product_id = waRequest::post('product_id');

        if(!is_array($product_id)){
            $collection = new shopProductsCollection(strip_tags($product_id));
            $product_coll = $collection->getProducts(false);
            unset($product_id);
            $product_id = array();
            foreach($product_coll as $prod){
                $product_id[] = $prod['id'];
            }
        }
        foreach($product_id as $id){
            if($id)
            $product->updateById(strip_tags($id),array("manager" => $manager_id),null,false);
        }
        $this->response['data'] = $manager_id;
    }

}