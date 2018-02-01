<?php

class shopSeoCategoriesH1Optimizer extends shopSeoOptimizer
{
    public function __construct()
    {
        $this->category_response = new shopSeoCategoryResponse();
    }

    protected function preCheck()
    {
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
        $settings = new shopSeoSettings();
        $general = $settings->get($general_storefront);

        return ifset($general['categories_replace_header_enable'], false);
    }

    protected function getTemplate()
    {
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
        $settings = new shopSeoSettings();
        $category_settings = $settings->getByCategoryID($this->category_response->getID(), $general_storefront);

        return ifset($category_settings['category_name']);
    }

    protected function optimize()
    {
        $this->category_response->setName($this->getText());
    }

    private $category_response;
}