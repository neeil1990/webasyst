<?php

class shopSeoCurrencyModifier extends shopSeoModifier
{
    public function modify($source)
    {
        return shop_currency($source);
    }
}