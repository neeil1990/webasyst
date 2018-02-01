<?php

class shopSeoBrandsReplacesSet extends shopSeoReplacesSet
{
    public function getReplaces()
    {
        $brand_response = new shopSeoBrandResponse();
        $category_response = new shopSeoBrandCategoryResponse();
        $brand_name = $brand_response->getName();

        $brand_name = ifempty($brand_name);
        $category_name = $category_response->getName();
        $page_number = waRequest::get('page', 1);

        return array(
            new shopSeoVariable('brand_name', $brand_name),
            new shopSeoVariable('category_name', $category_name),
            new shopSeoBaseReplacesSet(),
            new shopSeoVariable('page_number', $page_number),
        );
    }
}