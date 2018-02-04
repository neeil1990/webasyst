<?php

class shopSeoCategoriesMetaKeywordsOptimizer extends shopSeoMetaKeywordsOptimizer
{
    public function __construct()
    {
        parent::__construct();
        $this->category_response = new shopSeoCategoryResponse();
    }

    protected function getTemplate()
    {
        $routing = new shopSeoRouting();
        $current_storefront = $routing->getCurrentStorefront();
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;

        $settings = new shopSeoSettings();
        $general_settings = $settings->get($general_storefront);
        $subcategories_enable = ifset($general_settings['category_subcategories_enable'], false);

        $m_category = new shopCategoryModel();
        $category = $m_category->getById($this->category_response->getID());

        $source_keywords = $this->category_response->getMetaKeywords();
        $source_is_empty = empty($source_keywords);

        $template = new shopSeoTemplate();
        $template->setAllow($source_is_empty);

        foreach (array($current_storefront, $general_storefront) as $storefront)
        {
            $_settings = $settings->get($storefront);

            if ($subcategories_enable)
            {
                $_category = $category;

                while ($_category['parent_id'])
                {
                    $_category = $m_category->getById($_category['parent_id']);
                    $_category_settings = $settings->getByCategoryID($_category['id'], $storefront);
                    $template->setOverwrite($_category_settings['subcategories_meta_overwrite']);
                    $template->setEnable($_category_settings['subcategories_enable']);
                    $template->setContent($_category_settings['subcategories_meta_keywords']);

                    if (!$template->isEmpty())
                    {
                        return $template->getContent();
                    }
                }
            }

            $template->setOverwrite($_settings['categories_meta_overwrite']);
            $template->setEnable($_settings['categories_enable']);
            $template->setContent($_settings['categories_meta_keywords']);

            if (!$template->isEmpty())
            {
                return $template->getContent();
            }
        }

        return $source_keywords;
    }

    protected function getReplacer()
    {
        return new shopSeoCategoriesReplacesSet();
    }

    protected function optimize()
    {
        parent::optimize();
        $this->category_response->setMetaKeywords($this->getText());
    }

    private $category_response;
}