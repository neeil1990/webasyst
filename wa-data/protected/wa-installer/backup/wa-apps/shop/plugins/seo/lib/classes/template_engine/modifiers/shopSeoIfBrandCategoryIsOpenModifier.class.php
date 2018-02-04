<?php


class shopSeoIfBrandCategoryIsOpenModifier extends shopSeoModifier
{
	public function modify($source)
	{
		$category_response = new shopSeoBrandCategoryResponse();

		if ($category_response->isExists())
		{
			return $source;
		}
		else
		{
			return '';
		}
	}
}