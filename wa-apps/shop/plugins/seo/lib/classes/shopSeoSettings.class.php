<?php


class shopSeoSettings
{
	public function __construct()
	{
		$this->m_settings = new shopSeoSettingsModel();
		$this->m_c_settings = new shopSeoSettingsCategoryModel();
		$this->m_p_settings = new shopSeoSettingsProductModel();
		$this->m_f_settings = new shopSeoSettingsFieldModel();
	}

	public function getAll()
	{
		$routing = new shopSeoRouting();
		$storefronts = $routing->getStorefronts();
		$result = array();

		foreach ($storefronts as $storefront)
		{
			$settings = $this->get($storefront);
			$result[$storefront] = $settings;
		}

		return $result;
	}

	public function getExists()
	{
		$storefronts = array();
		$result = $this->m_settings->query('SELECT DISTINCT `storefront` FROM `'.$this->m_settings->getTableName().'`');

		while ($storefront = $result->fetchField('storefront'))
		{
			$storefronts[$storefront] = true;
		}

		$result = $this->m_c_settings->query('SELECT DISTINCT `storefront` FROM `'.$this->m_c_settings->getTableName().'`');

		while ($storefront = $result->fetchField('storefront'))
		{
			$storefronts[$storefront] = true;
		}

		$result = $this->m_p_settings->query('SELECT DISTINCT `storefront` FROM `'.$this->m_p_settings->getTableName().'`');

		while ($storefront = $result->fetchField('storefront'))
		{
			$storefronts[$storefront] = true;
		}

		return array_keys($storefronts);
	}

	public function getDiff()
	{
		$routing = new shopSeoRouting();
		$storefronts = $routing->getStorefronts();

		return array_diff($this->getExists(), $storefronts);
	}

	public function get($storefront)
	{
		$rows = $this->m_settings->getByField(array(
			'storefront' => $storefront,
		), true);

		$settings = array();

		foreach ($rows as $row)
		{
			$settings[$row['name']] = $row['value'];
		}

		return $settings;
	}

	public function update(array $settings, $storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME)
	{
		$rows = array();

		foreach ($settings as $name => $value)
		{
			$rows[] = array(
				'storefront' => $storefront,
				'name' => $name,
				'value' => $value,
			);
		}

		return $this->m_settings->deleteByField(array(
			'storefront' => $storefront,
		))
			and $this->m_settings->multipleInsert($rows);
	}

	public function delete($storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME)
	{
		return $this->m_settings->deleteByField(array(
			'storefront' => $storefront,
		)) and $this->m_c_settings->deleteByField(array(
			'storefront' => $storefront,
		)) and $this->m_p_settings->deleteByField(array(
			'storefront' => $storefront
		));
	}

	public function move($from_storefront, $to_storefront)
	{
		return $this->m_settings->updateByField(array(
			'storefront' => $from_storefront,
		), array(
			'storefront' => $to_storefront,
		)) and $this->m_c_settings->updateByField(array(
			'storefront' => $from_storefront,
		), array(
			'storefront' => $to_storefront,
		)) and $this->m_p_settings->updateByField(array(
			'storefront' => $from_storefront,
		), array(
			'storefront' => $to_storefront,
		));
	}

	public function getAllByCategoryID($category_id)
	{
		$routing = new shopSeoRouting();
		$storefronts = $routing->getStorefronts();
		$result = array();

		foreach ($storefronts as $storefront)
		{
			$settings = $this->getByCategoryID($category_id, $storefront);
			$result[$storefront] = $settings;
		}

		return $result;
	}

	public function getByCategoryID($category_id, $storefront)
	{
		$rows = $this->m_c_settings->getByField(array(
			'storefront' => $storefront,
			'category_id' => $category_id,
		), true);

		$settings = array();

		foreach ($rows as $row)
		{
			$settings[$row['name']] = $row['value'];
		}

		return $settings;
	}

	public function updateByCategoryID(array $settings, $category_id, $storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME)
	{
		$rows = array();

		foreach ($settings as $name => $value)
		{
			$rows[] = array(
				'storefront' => $storefront,
				'category_id' => $category_id,
				'name' => $name,
				'value' => $value,
			);
		}

		return $this->m_c_settings->deleteByField(array(
			'storefront' => $storefront,
			'category_id' => $category_id,
		))
			and $this->m_c_settings->multipleInsert($rows);
	}

	public function deleteByCategoryID($category_id)
	{
		return $this->m_c_settings->deleteByField('category_id', $category_id);
	}

	public function getAllByProductID($product_id)
	{
		$routing = new shopSeoRouting();
		$storefronts = $routing->getStorefronts();
		$result = array();

		foreach ($storefronts as $storefront)
		{
			$settings = $this->getByProductID($product_id, $storefront);
			$result[$storefront] = $settings;
		}

		return $result;
	}

	public function getByProductID($product_id, $storefront)
	{
		$rows = $this->m_p_settings->getByField(array(
			'storefront' => $storefront,
			'product_id' => $product_id,
		), true);

		$settings = array();

		foreach ($rows as $row)
		{
			$settings[$row['name']] = $row['value'];
		}

		return $settings;
	}

	public function updateByProductID(array $settings, $product_id, $storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME)
	{
		$rows = array();

		foreach ($settings as $name => $value)
		{
			$rows[] = array(
				'storefront' => $storefront,
				'product_id' => $product_id,
				'name' => $name,
				'value' => $value,
			);
		}

		return $this->m_p_settings->deleteByField(array(
			'storefront' => $storefront,
			'product_id' => $product_id,
		))
			and $this->m_p_settings->multipleInsert($rows);
	}

	public function deleteByProductID($product_id)
	{
		return $this->m_p_settings->deleteByField('product_id', $product_id);
	}

	public function getFields()
	{
		$rows = $this->m_f_settings->getAll();
		$fields = array();

		foreach ($rows as $row)
		{
			$fields[$row['id']] = $row['name'];
		}

		return $fields;
	}

	public function updateFields($fields)
	{
		$rows = array();

		foreach ($fields as $id => $name)
		{
			$rows[] = array(
				'id' => $id,
				'name' => $name,
			);
		}

		return $this->m_f_settings->query('DELETE FROM `'.$this->m_f_settings->getTableName().'`')
			and $this->m_f_settings->multipleInsert($rows);
	}

	public function addFields($fields)
	{
		$rows = array();

		foreach ($fields as $name)
		{
			$rows[] = array(
				'id' => null,
				'name' => $name,
			);
		}

		return $this->m_f_settings->multipleInsert($rows);
	}

	private $m_settings;
	private $m_c_settings;
	private $m_p_settings;
	private $m_f_settings;
}