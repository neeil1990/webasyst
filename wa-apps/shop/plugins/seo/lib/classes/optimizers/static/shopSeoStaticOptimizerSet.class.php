<?php


class shopSeoStaticOptimizerSet extends shopSeoOptimizerSet
{
	protected function getOptimizers()
	{
		return array(
			new shopSeoStaticMetaTitleOptimizer(),
			new shopSeoStaticMetaKeywordsOptimizer(),
			new shopSeoStaticMetaDescriptionOptimizer(),
		);
	}
}