<?php

class shopSeoLowerModifier extends shopSeoModifier
{
    public function modify($source)
    {
        return mb_strtolower($source);
    }
}