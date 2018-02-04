<?php

class shopSeoCategoriesAdditionalDescriptionOptimizer extends shopSeoOptimizer
{
    public function __construct()
    {
        $this->category_response = new shopSeoCategoryResponse();
    }

    protected function getTemplate()
    {
        $routing = new shopSeoRouting();
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
        $current_storefront = $routing->getCurrentStorefront();

        $settings = new shopSeoSettings();


        $template = new shopSeoTemplate();

        $allow = true;
        $enable = true;
        $template->setAllow($allow);
        $template->setEnable($enable);

        foreach (array($current_storefront, $general_storefront) as $storefront)
        {
            $category_settings = $settings->getByCategoryID(
                $this->category_response->getID(),
                $storefront
            );

            $template->setContent($category_settings['category_additional_description']);

            if (!$template->isEmpty())
            {
                return $template->getContent();
            }
        }

        return '';
    }

    protected function getReplacer()
    {
        return new shopSeoCategoriesReplacesSet();
    }

    protected function optimize()
    {
        $this->category_response->setAdditionalDescription($this->getText());
    }

    private $category_response;
}