<?php

class shopSeoIfPageFirstModifier extends shopSeoModifier
{
    public function modify($source)
    {
        if (waRequest::get('page', '1') == '1')
        {
            return $source;
        }
        else
        {
            return '';
        }
    }
}