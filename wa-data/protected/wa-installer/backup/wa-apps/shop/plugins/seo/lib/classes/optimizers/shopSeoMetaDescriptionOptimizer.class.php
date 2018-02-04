<?php

abstract class shopSeoMetaDescriptionOptimizer extends shopSeoOptimizer
{
    public function __construct()
    {
        $this->meta_response = new shopSeoMetaResponse();
    }

    protected function optimize()
    {
        $this->setResponseMetaDescription($this->getText());
    }

    protected function getRequestMetaDescription()
    {
        return $this->meta_response->getRequestMetaDescription();
    }

    protected function setResponseMetaDescription($description)
    {
        $this->meta_response->setResponseMetaDescription($description);
    }

    private $meta_response;
}