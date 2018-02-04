<?php


class shopSeoMetaResponse
{
	public function getRequestMetaTitle()
	{
		return waRequest::param('meta_title');
	}

	public function setResponseMetaTitle($meta_title)
	{
		$meta_title = preg_replace('/\s+/', ' ', $meta_title);
		wa()->getResponse()->setTitle($meta_title);
	}

	public function getRequestMetaKeywords()
	{
		return waRequest::param('meta_keywords');
	}

	public function setResponseMetaKeywords($meta_keywords)
	{
		$meta_keywords = preg_replace('/\s+/', ' ', $meta_keywords);
		wa()->getResponse()->setMeta('keywords', $meta_keywords);
	}

	public function getRequestMetaDescription()
	{
		return waRequest::param('meta_description');
	}

	public function setResponseMetaDescription($meta_description)
	{
		$meta_description = preg_replace('/\s+/', ' ', $meta_description);
		wa()->getResponse()->setMeta('description', $meta_description);
	}
}