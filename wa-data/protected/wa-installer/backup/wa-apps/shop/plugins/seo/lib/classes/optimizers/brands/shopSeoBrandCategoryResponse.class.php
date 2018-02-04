<?php


class shopSeoBrandCategoryResponse
{
	public function isExists()
	{
		$brand = wa()->getView()->getVars('brand');

		if (!$brand)
		{
			return false;
		}

		return (bool)wa()->getView()->getVars('category');
	}

	public function getName()
	{
		return $this->getField('name');
	}

	private function getField($name)
	{
		$brand = wa()->getView()->getVars('brand');

		if (!$brand)
		{
			return null;
		}

		$category = wa()->getView()->getVars('category');

		return ifset($category[$name]);
	}

	private function setField($name, $value)
	{
		$brand = wa()->getView()->getVars('brand');

		if (!$brand)
		{
			return;
		}

		$category = wa()->getView()->getVars('category');
		$category[$name] = $value;
		wa()->getView()->assign('category', $category);
	}
}