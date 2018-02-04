<?php

class shopSeoStaticReplacesSet extends shopSeoReplacesSet
{
    /**
     * @return shopSeoIReplacer[]
     */
    public function getReplaces()
    {
        static $replaces;

        if (!isset($replaces))
        {
            $view = wa()->getView();
            $page = $view->getVars('page');
            $page_name = $page['name'];

            $replaces = array(
                new shopSeoVariable('page_name', $page_name),
                new shopSeoBaseReplacesSet(),
            );
        }

        return $replaces;
    }
}