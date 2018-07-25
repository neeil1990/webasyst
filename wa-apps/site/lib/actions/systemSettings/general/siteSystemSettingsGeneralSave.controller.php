<?php

class siteSystemSettingsGeneralSaveController extends waJsonController
{
    public function execute()
    {
        $model = new waAppSettingsModel();

        $settings = array(
            'name'                         => 'Webasyst',
            'url'                          => wa()->getRootUrl(true),
            'auth_form_background'         => 'stock:bokeh_vivid.jpg',
            'auth_form_background_stretch' => 0,
            'locale'                       => 'ru_RU',
            'rememberme'                   => 1,
        );
        foreach ($settings as $setting => $value) {
            $model->set('webasyst', $setting, waRequest::post($setting, $value, waRequest::TYPE_STRING_TRIM));
        }

        // Save locale adapter
        $locale_adapter = waRequest::post('locale_adapter',null, waRequest::TYPE_STRING_TRIM);
        if ($locale_adapter) {
            $file_path = $this->getConfig()->getPath('config', 'factories');
            if ($locale_adapter == 'gettext') {
                if (file_exists($file_path)) {
                    $factories = include($file_path);
                    if (isset($factories['locale'])) {
                        unset($factories['locale']);
                        if ($factories) {
                            waUtils::varExportToFile($factories, $file_path);
                        } else {
                            waFiles::delete($file_path);
                        }
                    }
                }
            } elseif ($locale_adapter == 'php') {
                if (file_exists($file_path)) {
                    $factories = include($file_path);
                } else {
                    $factories = array();
                }
                if (empty($factories['locale']) || $factories['locale'] != 'waLocalePHPAdapter') {
                    $factories['locale'] = 'waLocalePHPAdapter';
                    waUtils::varExportToFile($factories, $file_path);
                }
            }
        }

        ### Save config ###
        $config_types = array(
            'debug' => 'boolean',
        );
        $flush_settings = array('debug');

        // Parse wa-config/config.php
        $config_path = waSystem::getInstance()->getConfigPath().'/config.php';
        $config = file_exists($config_path) ? include($config_path) : array();
        if (!is_array($config)) {
            $config = array();
        }

        $config_values = waRequest::post('config', array(), waRequest::TYPE_ARRAY_TRIM);
        if (!is_array($config_values)) {
            $config_values = array();
        }

        $config_changed = false;
        $flush = false;
        foreach ($config_types as $setting => $type) {
            $value = isset($config_values[$setting]) ? $config_values[$setting] : false;
            switch ($type) {
                case 'boolean':
                    $value = $value ? true : false;
                    break;
            }
            if (!isset($config[$setting]) || ($config[$setting] !== $value)) {
                $config[$setting] = $value;
                $config_changed = true;
                if (in_array($setting, $flush_settings)) {
                    $flush = true;
                }
            }
        }
        if ($config_changed) {
            waUtils::varExportToFile($config, $config_path);
        }
        if ($flush) {
            $path_cache = waConfig::get('wa_path_cache');
            waFiles::delete($path_cache, true);
            waFiles::protect($path_cache);
        }
    }
}