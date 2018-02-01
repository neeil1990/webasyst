<?php


class shopSeoOptimizerSet
{
	final public function execute()
	{
		foreach ($this->getOptimizers() as $optimizer)
		{
			if ($optimizer instanceof shopSeoOptimizer)
			{
				$optimizer->execute();
			}
		}
	}

	protected function getOptimizers()
	{
		return array();
	}
}