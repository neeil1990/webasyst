<?php


class shopSeoTagsOptimizerSet extends shopSeoOptimizerSet
{
	protected function getOptimizers()
	{
		return array(
			new shopSeoTagsMetaTitleOptimizer(),
			new shopSeoTagsMetaKeywordsOptimizer(),
			new shopSeoTagsMetaDescriptionOptimizer(),
			new shopSeoTagsDescriptionOptimizer(),
		);
	}
}