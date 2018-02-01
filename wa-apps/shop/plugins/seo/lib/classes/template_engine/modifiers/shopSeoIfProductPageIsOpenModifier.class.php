<?php


class shopSeoIfProductPageIsOpenModifier extends shopSeoModifier
{
	public function modify($source)
	{
		$page_response = new shopSeoProductPageResponse();
		$action_is_reviews = waRequest::param('action') == 'productReviews';

		if ($page_response->isExists() or $action_is_reviews)
		{
			return $source;
		}
		else
		{
			return '';
		}
	}
}