<?php

class shopSeoBaseReplacesSet extends shopSeoReplacesSet
{
    public function getReplaces()
    {
        $routing = new shopSeoRouting();
        $settings = new shopSeoSettings();
        $current_storefront = $routing->getCurrentStorefront();
        $storefront_settings = $settings->get($current_storefront);
        $config = wa()->getConfig();
        $store_name = '';
        $store_phone = '';

        if ($config instanceof shopConfig)
        {
            $store_name = $config->getGeneralSettings('name');
            $store_phone = $config->getGeneralSettings('phone');
        }

        $storefront_name = ifempty($storefront_settings['storefront_name'], $store_name);

        return array(
            new shopSeoVariable('store_name', $store_name),
            new shopSeoVariable('store_phone', $store_phone),
            new shopSeoVariable('storefront_name', $storefront_name),
            new shopSeoFieldsReplaceSet(),
            new shopSeoRandomSwitch(),
            new shopSeoConst(),
	        new shopSeoRegionsReplacer(),
        );
    }
}