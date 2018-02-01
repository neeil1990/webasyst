<?php

class shopSeoCategoriesReplacesSet extends shopSeoReplacesSet
{
    public function getReplaces()
    {
	    $response_category = new shopSeoCategoryResponse();
	    $m_category = new shopCategoryModel();
	    $settings = new shopSeoSettings();

	    $category = $m_category->getById($response_category->getID());
	    $_category = $category;
	    $parent_categories = array();

	    while ($_category['parent_id'])
	    {
		    $_category = $m_category->getById($_category['parent_id']);
		    $parent_categories[] = ifset($_category['name']);
	    }

	    $parent_categories = array_reverse($parent_categories);
	    $root_category_name = reset($parent_categories);
	    $parent_category_name = end($parent_categories);

	    $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
	    $category_settings = $settings->getByCategoryID($response_category->getID(), $general_storefront);

	    $category_name = $category['name'];
	    $category_seo_name = ifempty($category_settings['category_name'], $category_name);
	    $page_number = waRequest::get('page', 1);

		$products_collection = new shopProductsCollection('category/'.$category['id']);
		$products_collection->addWhere('`min_price` <> 0');
		$products_collection->orderBy('min_price');
		$products_count = $products_collection->count();
		$products = $products_collection->getProducts('*', 0, 1);
		$min_price = 0;

		if (count($products) > 0)
		{
			$product = reset($products);
			$min_price = $product['min_price'];
		}

	    return array(
		    new shopSeoVariable('category_name', $category_name),
		    new shopSeoVariable('category_seo_name', $category_seo_name),
		    new shopSeoVariable('parent_category_name', $parent_category_name),
		    new shopSeoVariable('root_category_name', $root_category_name),
		    new shopSeoArrayVariable('parent_categories', $parent_categories),
		    new shopSeoVariable('products_count', $products_count),
		    new shopSeoVariable('page_number', $page_number),
			new shopSeoVariable('min_price', $min_price),
		    new shopSeoBaseReplacesSet(),
	    );
    }
}