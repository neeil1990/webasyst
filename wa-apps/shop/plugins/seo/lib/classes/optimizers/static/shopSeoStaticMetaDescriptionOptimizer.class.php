<?php

class shopSeoStaticMetaDescriptionOptimizer extends shopSeoMetaDescriptionOptimizer
{
    protected function preCheck()
    {
        $description = $this->getRequestMetaDescription();

        return empty($description);
    }

    protected function getTemplate()
    {
        $routing = new shopSeoRouting();
        $current_storefront = $routing->getCurrentStorefront();
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;

        $settings = new shopSeoSettings();
        $template = new shopSeoTemplate();

        foreach (array($current_storefront, $general_storefront) as $storefront)
        {
            $_settings = $settings->get($storefront);
            $template->setEnable($_settings['static_enable']);
            $template->setContent($_settings['static_meta_description']);

            if (!$template->isEmpty())
            {
                return $template->getContent();
            }
        }

        return '';
    }

    protected function getReplacer()
    {
        return new shopSeoStaticReplacesSet();
    }
}