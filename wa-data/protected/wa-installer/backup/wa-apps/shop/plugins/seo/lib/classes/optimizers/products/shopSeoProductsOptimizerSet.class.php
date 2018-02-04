<?php


class shopSeoProductsOptimizerSet extends shopSeoOptimizerSet
{
	protected function getOptimizers()
	{
		return array(
			new shopSeoProductsMetaTitleOptimizer(),
			new shopSeoProductsMetaKeywordsOptimizer(),
			new shopSeoProductsMetaDescriptionOptimizer(),
			new shopSeoProductsDescriptionOptimizer(),
			new shopSeoProductsH1Optimizer(),
		);
	}
}