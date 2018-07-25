<?php

class shopProductmanagerPluginSettingsAction extends waViewAction
{
    public function execute()
    {

        $key = array('shop', 'productmanager');

        $app_settings_model = new waAppSettingsModel();

        $path = wa()->getAppPath('plugins/productmanager/templates/', 'shop');

        if ($template = $app_settings_model->get($key, 'template')) {
            $this->view->assign('template', $template);
        } else {
            $this->view->assign('template', file_get_contents($path.'frontendManager.html'));
        }

    }
}
