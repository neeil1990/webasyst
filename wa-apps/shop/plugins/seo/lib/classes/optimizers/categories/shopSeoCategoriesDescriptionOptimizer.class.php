<?php

class shopSeoCategoriesDescriptionOptimizer extends shopSeoOptimizer
{
    public function __construct()
    {
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
	    $category_settings = $settings->getByCategoryID(
		    $this->category_response->getID(),
		    $current_storefront
	    );

	    $source_description = trim($this->category_response->getDescription());
	    $source_is_empty = empty($source_description);

        $template = new shopSeoTemplate();
	    $template->setAllow($source_is_empty);

	    $overwrite = true;
	    $enable = true;
	    $template->setOverwrite($overwrite);
	    $template->setEnable($enable);
	    $template->setContent($category_settings['category_description']);

	    if (!$template->isEmpty())
	    {
		    return $template->getContent();
	    }

	    $overwrite = false;
	    $template->setOverwrite($overwrite);

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
                    $template->setEnable($_category_settings['subcategories_enable']);
                    $template->setContent($_category_settings['subcategories_description']);

                    if (!$template->isEmpty())
                    {
                        return $template->getContent();
                    }
                }
            }

            $template->setEnable($_settings['categories_enable']);
            $template->setContent($_settings['categories_description']);

            if (!$template->isEmpty())
            {
                return $template->getContent();
            }
        }

        return $source_description;
    }

    protected function getReplacer()
    {
        return new shopSeoCategoriesReplacesSet();
    }

    protected function optimize()
    {
        $this->category_response->setDescription($this->getText());
    }

    private $category_response;
}