<?php

class shopSeoProductsDescriptionOptimizer extends shopSeoOptimizer
{
    public function __construct()
    {
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
        $product_settings = $settings->getByProductID(
            $this->product_response->getID(),
            $current_storefront
        );

        $source_description = trim($this->product_response->getDescription());
        $source_is_empty = empty($source_description);

        $template = new shopSeoTemplate();
        $template->setAllow($source_is_empty);

        $overwrite = true;
        $enable = true;
        $template->setOverwrite($overwrite);
        $template->setEnable($enable);
        $template->setContent($product_settings['product_description']);

        if (!$template->isEmpty())
        {
            return $template->getContent();
        }

        $overwrite = false;
        $template->setOverwrite($overwrite);

        foreach (array($current_storefront, $general_storefront) as $storefront)
        {
            $_settings = $settings->get($storefront);

            if ($products_enable)
            {
                $_category = $m_category->getById($product['category_id']);

                while (!empty($_category))
                {
                    $_category_settings = $settings->getByCategoryID($_category['id'], $storefront);
                    $template->setEnable($_category_settings['products_enable']);
                    $template->setContent($_category_settings['products_description']);

                    if (!$template->isEmpty())
                    {
                        return $template->getContent();
                    }

                    $_category = $m_category->getById(ifset($_category['parent_id']));
                }
            }

            $template->setEnable($_settings['products_enable']);
            $template->setContent($_settings['products_description']);

            if (!$template->isEmpty())
            {
                return $template->getContent();
            }
        }

        return $source_description;
    }

    protected function getReplacer()
    {
        return new shopSeoProductsReplacesSet();
    }

    protected function optimize()
    {
        $this->product_response->setDescription($this->getText());
    }

    private $product_response;
}