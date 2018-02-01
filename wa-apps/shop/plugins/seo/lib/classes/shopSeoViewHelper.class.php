<?php


class shopSeoViewHelper extends waViewHelper
{
	const TYPE_BASE = 0;
	const TYPE_CATEGORIES = 1;
	const TYPE_PRODUCTS = 2;
	const TYPE_TAGS = 3;
	const TYPE_BRANDS = 4;
	const TYPE_STATIC = 5;
	const TYPE_MAIN = 6;

	public static function getTagDescription()
	{
		if (waRequest::param('action') == 'tag')
		{
			$optimizer = new shopSeoTagsDescriptionOptimizer();
			$optimizer->execute();
			$tag_response = new shopSeoTagResponse();

			return $tag_response->getDescription();
		}

		return '';
	}

	public static function getCategoryAdditionalDescription()
	{
		if (waRequest::param('action') == 'category')
		{
			$general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
			$settings = new shopSeoSettings();
			$_settings = $settings->get($general_storefront);

			if (!ifset($_settings['category_additional_description_enable'], false))
			{
				return '';
			}

			$optimizer = new shopSeoCategoriesAdditionalDescriptionOptimizer();
			$optimizer->execute();
			$category_response = new shopSeoCategoryResponse();

			return $category_response->getAdditionalDescription();
		}

		return '';
	}

	public static function parseTemplate($template, $type = null)
	{
		$type = ifset($type, self::routeType());
		$replacer = null;

		switch ($type)
		{
			case self::TYPE_BASE:
				$replacer = new shopSeoBaseReplacesSet();
				break;
			case self::TYPE_TAGS:
				$replacer = new shopSeoTagsReplacesSet();
				break;
			case self::TYPE_STATIC:
				$replacer = new shopSeoStaticReplacesSet();
				break;
			case self::TYPE_BRANDS:
				$replacer = new shopSeoBrandsReplacesSet();
				break;
			case self::TYPE_PRODUCTS:
				$replacer = new shopSeoProductsReplacesSet();
				break;
			case self::TYPE_CATEGORIES:
				$replacer = new shopSeoCategoriesReplacesSet();
				break;
		}

		if ($replacer instanceof shopSeoIReplacer)
		{
			return $replacer->fetch($template);
		}

		return $template;
	}

	private static function routeType()
	{
		$action = waRequest::param('action');

		if (is_null($action))
		{
			return self::TYPE_BASE;
		}
		elseif ($action == 'tag')
		{
			return self::TYPE_TAGS;
		}
		elseif ($action == 'page')
		{
			return self::TYPE_STATIC;
		}
		elseif ($action == 'brand')
		{
			return self::TYPE_BRANDS;
		}
		elseif ($action == 'product')
		{
			return self::TYPE_PRODUCTS;
		}
		elseif ($action == 'category')
		{
			return self::TYPE_CATEGORIES;
		}

		return null;
	}
}