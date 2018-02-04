<?php


class shopSeoBrandResponse
{
	public function getMetaTitle()
	{
		return $this->getField('title');
	}

	public function setMetaTitle($meta_title)
	{
		$this->setField('title', $meta_title);
	}

	public function getMetaKeywords()
	{
		return $this->getField('meta_keywords');
	}

	public function setMetaKeywords($meta_keywords)
	{
		$this->setField('meta_keywords', $meta_keywords);
	}

	public function getMetaDescription()
	{
		return $this->getField('meta_description');
	}

	public function setMetaDescription($meta_description)
	{
		$this->setField('meta_description', $meta_description);
	}

	public function getName()
	{
		if ($this->isExists())
		{
			return $this->getField('name');
		}
		else
		{
			return wa()->getView()->getVars('title');
		}
	}

	public function setName($name)
	{
		if ($this->isExists())
		{
			$this->setField('name', $name);
		}
		else
		{
			wa()->getView()->assign('title', $name);
		}
	}

	public function isExists()
	{
		$brand = wa()->getView()->getVars('brand');

		return isset($brand);
	}

	private function getField($name)
	{
		$brand = wa()->getView()->getVars('brand');

		return ifset($brand[$name]);
	}

	private function setField($name, $value)
	{
		$brand = wa()->getView()->getVars('brand');
		$brand[$name] = $value;
		wa()->getView()->assign('brand', $brand);
	}
}