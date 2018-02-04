<?php

class shopSeoPluginSettingsAction extends waViewAction
{
    public function execute()
    {
        waLocale::loadByDomain(array('shop', 'seo'));
        waSystem::pushActivePlugin('seo', 'shop');

        $plugin = wa('shop')->getPlugin('seo');

        $is_submit = waRequest::post('is_submit', '');
        $transfer = waRequest::post('transfer', '');
        $settings = new shopSeoSettings();

        if (waRequest::isXMLHttpRequest() and $transfer)
        {
            $this->doTransfer();
        }

        if (waRequest::isXMLHttpRequest() and $is_submit)
        {
            $this->saveSettings();
        }
	    
        $routing = new shopSeoRouting();
        $storefronts = $routing->getStorefronts();
        $seo_settings = $settings->getAll();
        $storefronts_diff = $settings->getDiff();
        $seo_settings_fields = $settings->getFields();

        $this->view->assign(array(
            'seo_js' => array(
                $plugin->getPluginStaticUrl().'js/form.js',
                $plugin->getPluginStaticUrl().'js/variable.js',
                $plugin->getPluginStaticUrl().'js/settings.js',
            ),
            'seo_css' => array(
                $plugin->getPluginStaticUrl().'css/general.css',
                $plugin->getPluginStaticUrl().'css/form.css',
                $plugin->getPluginStaticUrl().'css/helper.css',
                $plugin->getPluginStaticUrl().'css/variable.css',
                $plugin->getPluginStaticUrl().'css/settings.css',
            ),
            'variables_template_path' => shopSeoPlugin::getVariablesTemplatePath(),
            'storefronts' => $storefronts,
            'seo_settings' => $seo_settings,
            'seo_settings_fields' => $seo_settings_fields,
            'storefronts_diff' => $storefronts_diff,
            'plugin_version' => $plugin->getVersion(),
            //'plugin_version' => time(),
        ));

        waSystem::popActivePlugin();
    }

    private function saveSettings()
    {
        $input_settings = waRequest::post('seo_settings', array());
        $settings = new shopSeoSettings();

        if (is_array($input_settings))
        foreach ($input_settings as $storefront => $_settings)
        {
            $settings->update($_settings, $storefront);
        }

        $fields = waRequest::post('seo_settings_fields', array());
        $settings->updateFields($fields);
        $new_fields = waRequest::post('seo_settings_new_fields', array());
        $settings->addFields($new_fields);

        $this->view->assign('success', true);
    }

    private function doTransfer()
    {
        $transfer = waRequest::post('transfer', '');
        $storefront = waRequest::post('storefront', '');
        $transfer_storefront = waRequest::post('transfer_storefront', '');
        $settings = new shopSeoSettings();

        if ($transfer == 'delete' and $storefront)
        {
            $settings->delete($storefront);
        }
        else if ($transfer == 'transfer' and $storefront and $transfer_storefront)
        {
            $settings->move($storefront, $transfer_storefront);
        }
    }
}