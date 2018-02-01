<?php

class shopSeoTemplate
{
    public function setContent(&$content)
    {
        $this->content = (string)ifset($content, '');
    }

    public function setEnable(&$enable)
    {
        $this->enable = (bool)ifset($enable, false);
    }

    public function setAllow(&$allow)
    {
        $this->allow = (bool)ifset($allow, false);
    }

    public function setOverwrite(&$overwrite)
    {
        $this->overwrite = (bool)ifset($overwrite, false);
    }

    public function isEmpty()
    {
        $template_is_empty = empty($this->content);

        if (($this->allow or $this->overwrite) and $this->enable and !$template_is_empty)
        {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        return $this->content;
    }

    protected $content = '';
    protected $enable = false;
    protected $allow = true;
    protected $overwrite = false;
}