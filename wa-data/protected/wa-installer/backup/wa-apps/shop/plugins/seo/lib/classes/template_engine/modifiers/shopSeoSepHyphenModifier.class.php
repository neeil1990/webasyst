<?php


class shopSeoSepHyphenModifier extends shopSeoArrayModifier
{
	public function modify($source)
	{
		return $source;
	}

	public function getSep()
	{
		return ' - ';
	}
}