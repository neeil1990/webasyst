<?php


class shopSeoTagResponse
{
	public function getDescription()
	{
		return wa()->getView()->getVars('tag_description');
	}

	public function setDescription($description)
	{
		wa()->getView()->assign('tag_description', $description);
	}
}