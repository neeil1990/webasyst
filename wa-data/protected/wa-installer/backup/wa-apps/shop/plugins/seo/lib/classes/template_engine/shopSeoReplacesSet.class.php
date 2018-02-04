<?php

abstract class shopSeoReplacesSet implements shopSeoIReplacer
{
    public function fetch($template)
    {
        $result = $template;

        foreach ($this->getReplaces() as $replacer)
        {
            if ($replacer instanceof shopSeoIReplacer)
            {
                $result = $replacer->fetch($result);
            }
        }

        return $result;
    }

    abstract public function getReplaces();
}