<?php


class shopSeoFieldsReplaceSet extends shopSeoReplacesSet
{
	public function getReplaces()
	{
		$settings = new shopSeoSettings();
		$fields = $settings->getFields();
		$routing = new shopSeoRouting();
		$current_storefront = $routing->getCurrentStorefront();
		$settings = $settings->get($current_storefront);
		$replaces = array();

		foreach ($fields as $id => $name)
		{
			$value = ifset($settings['storefront_field_'.$id], '');
			$replaces[] = new shopSeoVariable('storefront_field_'.$id, $value);
		}

		return $replaces;
	}
}