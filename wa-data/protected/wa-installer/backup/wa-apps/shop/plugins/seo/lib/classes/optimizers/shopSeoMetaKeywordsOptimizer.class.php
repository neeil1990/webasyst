<?php

abstract class shopSeoMetaKeywordsOptimizer extends shopSeoOptimizer
{
    public function __construct()
    {
	    $this->meta_response = new shopSeoMetaResponse();
    }

    protected function optimize()
    {
        $this->setResponseMetaKeywords($this->getText());
    }

    protected function getRequestMetaKeywords()
    {
	    return $this->meta_response->getRequestMetaKeywords();
    }

    protected function setResponseMetaKeywords($keywords)
    {
	    $this->meta_response->setResponseMetaKeywords($keywords);
    }

	private $meta_response;
}