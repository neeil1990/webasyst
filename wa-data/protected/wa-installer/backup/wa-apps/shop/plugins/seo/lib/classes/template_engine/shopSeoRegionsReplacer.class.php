<?php

class shopSeoRegionsReplacer implements shopSeoIReplacer
{
	public function fetch($template)
	{
		if (class_exists('shopRegionsViewHelper')
			and method_exists('shopRegionsViewHelper', 'parseTemplate'))
		{
			return shopRegionsViewHelper::parseTemplate($template);
		}

		return $template;
	}
}