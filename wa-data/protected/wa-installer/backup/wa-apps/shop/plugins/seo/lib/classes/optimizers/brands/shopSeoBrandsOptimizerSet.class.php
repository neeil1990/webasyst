<?php


class shopSeoBrandsOptimizerSet extends shopSeoOptimizerSet
{
	protected function getOptimizers()
	{
		return array(
			new shopSeoBrandsMetaTitleOptimizer(),
			new shopSeoBrandsMetaKeywordsOptimizer(),
			new shopSeoBrandsMetaDescriptionOptimizer(),
		);
	}
}