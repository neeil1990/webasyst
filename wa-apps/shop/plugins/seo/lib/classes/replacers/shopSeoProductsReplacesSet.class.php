<?php

class shopSeoProductsReplacesSet extends shopSeoReplacesSet
{
    public function fetch($template)
    {
        $template = parent::fetch($template);

        $m_product = new shopProductModel();
        $product_response = new shopSeoProductResponse();
        $product = $m_product->getById($product_response->getID());
        // very bad patch
        $search = array('{$name}', '{$price}', '{$summary}');
        $replace = array();
        foreach ($search as $i => $s)
        {
            $r = substr($s, 2, -1);
            if (isset($product[$r]))
            {
                if ($r == 'price')
                {
                    $replace[] = shop_currency_html($product[$r], null, null, true);
                } else
                {
                    $replace[] = $product[$r];
                }
            }
            else
            {
                unset($search[$i]);
            }
        }

        $template = str_replace($search, $replace, $template);

        return $template;
    }

    public function getReplaces()
    {
	    $product_response = new shopSeoProductResponse();
	    $page_response = new shopSeoProductPageResponse();
        $action = waRequest::param('action');
	    $m_product = new shopProductModel();
	    $m_category = new shopCategoryModel();
	    $settings = new shopSeoSettings();
        $product = $m_product->getById($product_response->getID());
	    $category = $m_category->getById($product['category_id']);
	    $_category = $category;
	    $categories = array();

        while ($_category)
        {
	        $categories[] = $_category;
            $_category = $m_category->getById($_category['parent_id']);
        }

	    $categories = array_reverse($categories);
	    $categories_name = array();

	    foreach ($categories as $_category)
	    {
		    $categories_name[] = ifset($_category['name']);
	    }

        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
	    $category_name = ifset($category['name']);
	    $category_settings = $settings->getByCategoryID(ifset($category['id']), $general_storefront);
	    $category_seo_name = ifempty($category_settings['category_name'], $category_name);
	    $root_category = reset($categories);
	    $root_category_name = ifset($root_category['name']);
	    $root_category_settings = $settings->getByCategoryID(ifset($root_category['id']), $general_storefront);
	    $root_category_seo_name = ifempty($root_category_settings['category_name'], $root_category_name);

        $product_settings = $settings->getByProductID($product_response->getID(), $general_storefront);

        $product_name = $product['name'];
        $product_seo_name = ifempty($product_settings['product_name'], $product_name);
        $product_price = number_format($product['price'], 2, '.', '');
        $wa_product = new shopProduct($product_response->getID());
        $product_price = shopRounding::roundCurrency($product_price, $product['currency']);
        $skus = $wa_product['skus'];
        $product_sku = ifset($skus[$product['sku_id']]['sku'], '');

        if ($action == 'productReviews')
        {
            $page_name = _w('Reviews');
        }
        else
        {
            $page_name = $page_response->getName();
        }


        return array(
            new shopSeoVariable('product_name', $product_name),
            new shopSeoVariable('product_seo_name', $product_seo_name),
            new shopSeoVariable('product_price', $product_price),
            new shopSeoVariable('product_sku', $product_sku),
            new shopSeoVariable('category_name', $category_name),
            new shopSeoVariable('category_seo_name', $category_seo_name),
            new shopSeoVariable('root_category_name', $root_category_name),
            new shopSeoVariable('root_category_seo_name', $root_category_seo_name),
            new shopSeoArrayVariable('categories', $categories_name),
            new shopSeoVariable('page_name', $page_name),
            new shopSeoBaseReplacesSet(),
        );
    }
}