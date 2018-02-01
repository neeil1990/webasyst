<?php


class shopSeoProductPageResponse
{
	public function isExists()
	{
		$product = wa()->getView()->getVars('product');

		if (!$product)
		{
			return false;
		}

		return (bool)wa()->getView()->getVars('page');
	}

	public function getName()
	{
		return $this->getField('name');
	}

	private function getField($name)
	{
		$product = wa()->getView()->getVars('product');

		if (!$product)
		{
			return null;
		}

		$page = wa()->getView()->getVars('page');

		return ifset($page[$name]);
	}

	private function setField($name, $value)
	{
		$product = wa()->getView()->getVars('product');

		if (!$product)
		{
			return;
		}

		$page = wa()->getView()->getVars('page');
		$page[$name] = $value;
		wa()->getView()->assign('page', $page);
	}
}