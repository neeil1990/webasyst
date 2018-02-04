<?php

class shopSeoMainMetaTitleOptimizer extends shopSeoMetaTitleOptimizer
{
    protected function preCheck()
    {
        $title = $this->getRequestMetaTitle();

        return empty($title);
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
            $template->setEnable($_settings['main_enable']);
            $template->setContent($_settings['main_meta_title']);

            if (!$template->isEmpty())
            {
                return $template->getContent();
            }
        }

        return '';
    }

    protected function getReplacer()
    {
        return new shopSeoMainReplacesSet();
    }
}