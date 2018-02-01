<?php

$general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
$settings = new shopSeoSettings();
$general = $settings->get($general_storefront);

if (!ifset($general['main_enable'], false) and empty($general['main_meta_title']))
{
	$general['main_meta_title'] = '{store_name}  — интернет-магазин';
}

if (!ifset($general['categories_enable'], false) and empty($general['categories_meta_title']))
{
	$general['categories_meta_title'] = '{category_seo_name} купить в интернет-магазине {store_name}';
}

if (!ifset($general['products_enable'], false) and empty($general['products_meta_title']))
{
	$general['products_meta_title'] = '{product_seo_name} купить в интернет-магазине {store_name}';
}

if (!ifset($general['static_enable'], false) and empty($general['static_meta_title']))
{
	$general['static_meta_title'] = '{page_name} | интернет-магазин {store_name}';
}

if (!ifset($general['tags_enable'], false) and empty($general['tags_meta_title']))
{
	$general['tags_meta_title'] = '{tag_name} купить в интернет-магазине {store_name}';
}

if (!ifset($general['brands_enable'], false) and empty($general['brands_meta_title']))
{
	$general['brands_meta_title'] = '{brand_name} купить в интернет-магазине {store_name}';
}

$settings->update($general);