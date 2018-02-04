<?php

class shopSeoTagsDescriptionOptimizer extends shopSeoOptimizer
{
    public function __construct()
    {
        $this->tag_response = new shopSeoTagResponse();
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
            $template->setEnable($_settings['tags_enable']);
            $template->setContent($_settings['tags_description']);

            if (!$template->isEmpty())
            {
                return $template->getContent();
            }
        }

        return '';
    }

    protected function optimize()
    {
        $this->tag_response->setDescription($this->getText());
    }

    protected function getReplacer()
    {
        return new shopSeoTagsReplacesSet();
    }

    private $tag_response;
}