<?php


class shopSeoRouting
{
	const GENERAL_STOREFRONT_NAME = 'general';

	public function __construct()
	{
		$this->routing = wa()->getRouting();
	}

	public function getStorefronts()
	{
		$storefronts = array(self::GENERAL_STOREFRONT_NAME);
		$domains = $this->routing->getByApp('shop');

		foreach ($domains as $domain => $domain_routes)
		{
			foreach ($domain_routes as $route)
			{
				$storefronts[] = $domain.'/'.$route['url'];
			}
		}

		return $storefronts;
	}

	public function getCurrentStorefront()
	{
		$domain = $this->routing->getDomain();
		$route = $this->routing->getRoute();
		$storefront = $domain.'/'.$route['url'];

		return $storefront;
	}

	private $routing;
}