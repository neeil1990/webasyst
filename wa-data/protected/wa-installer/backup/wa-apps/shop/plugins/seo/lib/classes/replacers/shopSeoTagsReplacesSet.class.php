<?php

class shopSeoTagsReplacesSet extends shopSeoReplacesSet
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
            $tag_name = $view->getVars('title');
            $page_number = waRequest::get('page', 1);

            $replaces = array(
                new shopSeoVariable('tag_name', $tag_name),
                new shopSeoVariable('page_number', $page_number),
                new shopSeoBaseReplacesSet(),
            );
        }

        return $replaces;
    }
}