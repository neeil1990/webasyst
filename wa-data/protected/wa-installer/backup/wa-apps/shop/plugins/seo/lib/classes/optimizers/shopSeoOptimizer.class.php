<?php

abstract class shopSeoOptimizer
{
    final public function execute()
    {
        if ($this->preCheck())
        {
            $this->applyReplacer();

            if ($this->checkText())
            {
                $this->optimize();
            }
        }
    }

    protected function preCheck()
    {
        return true;
    }

    protected function getTemplate()
    {
        return '';
    }

    protected function getReplacer()
    {
        return null;
    }

    abstract protected function optimize();

    final protected function getText()
    {
        return $this->text;
    }

    private $text;

    private function applyReplacer()
    {
        $replacer = $this->getReplacer();

        if ($replacer instanceof shopSeoIReplacer)
        {
            $this->text = $replacer->fetch($this->getTemplate());
        }
        else
        {
            $this->text = $this->getTemplate();
        }
    }

    private function checkText()
    {
        return !empty($this->text);
    }
}