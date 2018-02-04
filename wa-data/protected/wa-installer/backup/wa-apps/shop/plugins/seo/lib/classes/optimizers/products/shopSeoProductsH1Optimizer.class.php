<?php

class shopSeoProductsH1Optimizer extends shopSeoOptimizer
{
    public function __construct()
    {
        $this->product_response = new shopSeoProductResponse();
    }

    protected function preCheck()
    {
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
        $settings = new shopSeoSettings();
        $general = $settings->get($general_storefront);

        return ifset($general['products_replace_header_enable'], false);
    }

    protected function getTemplate()
    {
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
        $settings = new shopSeoSettings();
        $product_settings = $settings->getByProductID($this->product_response->getID(), $general_storefront);

        return ifset($product_settings['product_name']);
    }

    protected function optimize()
    {
        $this->product_response->setName($this->getText());
    }

    private $product_response;
}