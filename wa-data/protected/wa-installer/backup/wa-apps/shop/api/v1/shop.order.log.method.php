<?php

class shopOrderLogMethod extends shopApiMethod
{
    public function execute()
    {
        $id = $this->get('id', true);

        $workflow = new shopWorkflow();
        $log_model = new shopOrderLogModel();
        $log = $log_model->getLog($id);
        $root_url = rtrim(wa()->getRootUrl(true), '/');
        foreach ($log as &$l) {
            $l['log_record'] = $l['action_name'] = $l['action_id'];
            if ($l['action_id']) {
                try {
                    $action = $workflow->getActionById($l['action_id']);
                    if ($action) {
                        $l['action_name'] = $action->getName();
                        $l['log_record'] = $action->getOption('log_record');
                    }
                } catch (Exception $e) {
                }
            }
            $use_gravatar = wa('shop')->getConfig()->getGeneralSettings('use_gravatar');
            $gravatar_default = wa('shop')->getConfig()->getGeneralSettings('gravatar_default');

            $l['photo_url_40'] = $root_url.'/wa-content/img/userpic50@2x.jpg';

            if (!empty($l['contact_id'])) {
                $contact = new waContact($l['contact_id']);
                if (!$contact->get('photo') && $use_gravatar) {
                    $l['photo_url_40'] = shopHelper::getGravatar($contact->get('email', 'default'), 40, $gravatar_default, true);
                } elseif (!empty($l['contact_photo'])) {
                    $l['photo_url_40'] = $root_url.waContact::getPhotoUrl($l['contact_id'], $l['contact_photo'], 40, 40, 'person', 1);
                }
            }
        }

        $this->response = $log;
    }
}
