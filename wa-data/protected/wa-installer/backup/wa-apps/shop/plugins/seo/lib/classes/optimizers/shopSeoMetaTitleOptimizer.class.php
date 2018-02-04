<?php

abstract class shopSeoMetaTitleOptimizer extends shopSeoOptimizer
{
    public function __construct()
    {
        $this->meta_response = new shopSeoMetaResponse();
    }

    protected function optimize()
    {
        $this->setResponseMetaTitle($this->getText());
    }

    protected function getRequestMetaTitle()
    {
        return $this->meta_response->getRequestMetaTitle();
    }

    protected function setResponseMetaTitle($title)
    {
        $this->meta_response->setResponseMetaTitle($title);
    }

    private $meta_response;
}