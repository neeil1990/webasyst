<?php

class shopSeoMainReplacesSet extends shopSeoReplacesSet
{
    public function getReplaces()
    {
        return array(
            new shopSeoBaseReplacesSet(),
        );
    }
}