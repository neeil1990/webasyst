<?php

class shopSeoRandomSwitch implements shopSeoIReplacer
{
    public function fetch($template)
    {
        return preg_replace_callback('/\{(\'.*?\'(\,\'.*?\')*)\}/', array($this, 'randomSwitch'), $template);
    }

    public function randomSwitch(array $matches)
    {
        $matches = $matches[1];
        preg_match_all('/\'((\\\\.|[^\'\\\\])*)\'/', $matches, $matches2);

        return $matches2[1][array_rand($matches2[1])];
    }
}