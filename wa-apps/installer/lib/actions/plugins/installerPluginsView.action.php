<?php

/*
 * This file is part of Webasyst framework.
 *
 * Licensed under the terms of the GNU Lesser General Public License (LGPL).
 * http://www.webasyst.com/framework/license/
 *
 * @link http://www.webasyst.com/
 * @author Webasyst LLC
 * @copyright 2011 Webasyst LLC
 * @package installer
 */

class installerPluginsViewAction extends installerItemsAction
{
    protected $module = 'plugins';


    protected function getAppOptions()
    {
        $options = parent::getAppOptions();
        if (preg_match('@^wa-plugins/@', waRequest::get('slug'))) {
            $options += array('system' => true);
        }
        return $options;
    }

    public function execute()
    {
        parent::execute();

        $this->view->assign('top', !!preg_match('@^[^/]+$@', waRequest::get('slug')) && !waRequest::get('filter'));
        $this->view->assign('return_url', $this->getReturnUrl());
        $this->view->assign('identity_hash', installerHelper::getHash());
        $this->view->assign('promo_id', installerHelper::getPromoId());
        $this->view->assign('domain', installerHelper::getDomain());
        $this->view->assign('options', (array)waRequest::get('options', array()));
    }
}
