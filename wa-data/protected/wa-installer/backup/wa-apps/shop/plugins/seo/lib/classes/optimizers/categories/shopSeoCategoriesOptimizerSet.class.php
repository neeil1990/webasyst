<?php


class shopSeoCategoriesOptimizerSet extends shopSeoOptimizerSet
{
	protected function getOptimizers()
	{
		return array(
			new shopSeoCategoriesMetaTitleOptimizer(),
			new shopSeoCategoriesMetaKeywordsOptimizer(),
			new shopSeoCategoriesMetaDescriptionOptimizer(),
			new shopSeoCategoriesDescriptionOptimizer(),
			new shopSeoCategoriesAdditionalDescriptionOptimizer(),
			new shopSeoCategoriesH1Optimizer(),
		);
	}
}