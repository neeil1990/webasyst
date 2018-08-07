<?php

class shopProductmanagerPluginDialogManagerAction extends waViewAction
{
    public function execute()
    {
        $users = new shopProductmanagerPlugin(false);

        $count = strip_tags(waRequest::post('count', "string"));
        $this->view->assign('users', $users->get_users());
        $this->view->assign('count', $count);
    }
}