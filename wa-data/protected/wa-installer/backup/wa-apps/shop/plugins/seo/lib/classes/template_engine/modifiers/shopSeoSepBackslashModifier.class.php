<?php


class shopSeoSepBackslashModifier extends shopSeoArrayModifier
{
	public function modify($source)
	{
		return $source;
	}

	public function getSep()
	{
		return ' \ ';
	}
}