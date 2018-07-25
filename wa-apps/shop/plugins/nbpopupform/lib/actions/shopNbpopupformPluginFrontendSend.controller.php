<?php

class shopNbpopupformPluginFrontendSendController extends waJsonController
{

    public function execute()
    {
        $data = waRequest::post();

        $product = new shopProduct($data['id']);
        $data['name'] = $product['name'];
        $data['url'] = wa()->getRootUrl(true)."product/".$product['url'];

        $contact = new waContact($product['manager']);
        $data['manager'] = $contact->get("email","default");

        if(empty($data['manager'])){
            $data['manager'] = "info@almamed.su";
        }

        $data['roistat'] = array_key_exists('roistat_visit', $_COOKIE) ? $_COOKIE['roistat_visit'] : "неизвестно";

        $app_config = wa()->getConfig()->getAppConfig('shop');
        $temp_path = $app_config->getAppPath('plugins/');

        $view = wa()->getView();
        $view->assign('data', $data);
        $output = $view->fetch($temp_path.'nbpopupform/templates/mail/template.html');

        $subject = "Запрос КП ".$data['name'];
        $mail_message = new waMailMessage($subject, $output);
        $mail_message->setFrom('noreply@almamed.su', 'АльмаМед');
        $mail_message->setTo($data['manager'],'АльмаМед');
        $mail = $mail_message->send();

        $this->response = array('response' => $mail);
    }
}