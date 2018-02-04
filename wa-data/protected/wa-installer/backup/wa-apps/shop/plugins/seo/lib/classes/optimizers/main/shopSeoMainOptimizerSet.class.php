<?php


class shopSeoMainOptimizerSet extends shopSeoOptimizerSet
{
	protected function getOptimizers()
	{
		return array(
			new shopSeoMainMetaTitleOptimizer(),
			new shopSeoMainMetaKeywordsOptimizer(),
			new shopSeoMainMetaDescriptionOptimizer(),
		);
	}
}