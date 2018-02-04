<?php

class shopFrontendSearchAction extends shopFrontendAction
{
    public function execute()
    {
        $query = waRequest::get('query');

       $shopProductsCollection = new shopProductsCollection('search/query='.str_replace('&', '\&', $query));

       $this->setCollection($shopProductsCollection);



        $query = htmlspecialchars($query);
        $this->view->assign('title', $query);
        $this->getResponse()->setTitle($query.' — '.$this->getStoreName());



        if ($this->layout) {
            $this->layout->assign('query', $query);
        }
        if (!$query) {
            $this->view->assign('sorting', true);
        }

        /**
         * @event frontend_search
         * @return array[string]string $return[%plugin_id%] html output for search
         */

        $this->view->assign('frontend_search', wa()->event('frontend_search'));
        $this->setThemeTemplate('search.html');
    }
}