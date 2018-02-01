<?php

class shopSeoProductsMetaTitleOptimizer extends shopSeoMetaTitleOptimizer
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

	    $source_title = $this->product_response->getMetaTitle();
	    $source_is_empty = empty($source_title);

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
				    $template->setContent($_category_settings['products_meta_title']);

				    if (!$template->isEmpty())
				    {
					    return $template->getContent();
				    }

				    $_category = $m_category->getById($_category['parent_id']);
			    }
		    }

		    $template->setOverwrite($_settings['products_meta_overwrite']);
		    $template->setEnable($_settings['products_enable']);
		    $template->setContent($_settings['products_meta_title']);

		    if (!$template->isEmpty())
		    {
			    return $template->getContent();
		    }
	    }

        return $source_title;
    }

    protected function getReplacer()
    {
        return new shopSeoProductsReplacesSet();
    }

	protected function optimize()
	{
		parent::optimize();
		$this->product_response->setMetaTitle($this->getText());

		$product_og_model = new shopProductOgModel();
		$og = $product_og_model->getData(new shopProduct($this->product_response->getID()));

		if (empty($og['title']))
		{
			$this->product_response->updateOg('title', $this->getText());
		}
	}

    private $product_response;
}