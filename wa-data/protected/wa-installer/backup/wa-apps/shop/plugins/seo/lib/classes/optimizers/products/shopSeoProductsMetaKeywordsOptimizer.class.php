<?php

class shopSeoProductsMetaKeywordsOptimizer extends shopSeoMetaKeywordsOptimizer
{
    public function __construct()
    {
	    parent::__construct();
        $this->product_response = new shopSeoProductResponse();
    }

    protected function getTemplate()
    {
        $routing = new shopSeoRouting();
        $current_storefront = $routing->getCurrentStorefront();
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;

        $settings = new shopSeoSettings();
        $general_settings = $settings->get($general_storefront);
        $products_enable = ifset($general_settings['category_products_enable'], false);

        $m_category = new shopCategoryModel();
        $m_product = new shopProductModel();
        $product = $m_product->getById($this->product_response->getID());

        $source_keywords = $this->product_response->getMetaKeywords();
        $source_is_empty = empty($source_keywords);

        $template = new shopSeoTemplate();
        $template->setAllow($source_is_empty);

        foreach (array($current_storefront, $general_storefront) as $storefront)
        {
            $_settings = $settings->get($storefront);

            if ($products_enable)
            {
                $_category = $m_category->getById($product['category_id']);

                while (!empty($_category))
                {
                    $_category_settings = $settings->getByCategoryID($_category['id'], $storefront);
                    $template->setOverwrite($_category_settings['products_meta_overwrite']);
                    $template->setEnable($_category_settings['products_enable']);
                    $template->setContent($_category_settings['products_meta_keywords']);

                    if (!$template->isEmpty())
                    {
                        return $template->getContent();
                    }

                    $_category = $m_category->getById($_category['parent_id']);
                }
            }

            $template->setOverwrite($_settings['products_meta_overwrite']);
            $template->setEnable($_settings['products_enable']);
            $template->setContent($_settings['products_meta_keywords']);

            if (!$template->isEmpty())
            {
                return $template->getContent();
            }
        }

        return $source_keywords;
    }

    protected function getReplacer()
    {
        return new shopSeoProductsReplacesSet();
    }

    protected function optimize()
    {
        parent::optimize();
        $this->product_response->setMetaKeywords($this->getText());
    }

    private $product_response;
}