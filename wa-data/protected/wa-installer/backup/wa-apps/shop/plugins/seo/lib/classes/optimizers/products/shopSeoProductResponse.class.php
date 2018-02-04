<?php


class shopSeoProductResponse
{
	public function getID()
	{
		$product = wa()->getView()->getVars('product');

		if ($product instanceof shopProduct)
		{
			return $product->getId();
		}

		return null;
	}

	public function getOg()
	{
		return $this->getField('og');
	}

	public function updateOg($name, $value)
	{
		$og = $this->getOg();
		$og[$name] = $value;
		$this->setOg($og);
	}

	public function setOg(array $values)
	{
		$this->setField('og', $values);

		foreach ($values as $key => $value)
		{
			wa()->getResponse()->setOGMeta('og:'.$key, $value);
		}
	}

	public function getMetaTitle()
	{
		return $this->getField('meta_title');
	}

	public function setMetaTitle($meta_title)
	{
		$this->setField('meta_title', $meta_title);
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
		return $this->getField('name');
	}

	public function setName($name)
	{
		$this->setField('name', $name);
	}

	public function getDescription()
	{
		return $this->getField('description');
	}

	public function setDescription($description)
	{
		$this->setField('description', $description);
	}

	private function getField($name)
	{
		$product = wa()->getView()->getVars('product');

		return $product->$name;
	}

	private function setField($name, $value)
	{
		$product = wa()->getView()->getVars('product');
		$product->$name = $value;
		wa()->getView()->assign('product', $product);
	}
}