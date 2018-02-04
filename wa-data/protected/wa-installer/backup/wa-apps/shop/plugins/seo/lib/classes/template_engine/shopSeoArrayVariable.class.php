<?php


class shopSeoArrayVariable implements shopSeoIReplacer
{
	public function getModifiers()
	{
		return array(
			'lower' => new shopSeoLowerModifier(),
			'currency' => new shopSeoCurrencyModifier(),
			'if_page_not_first' => new shopSeoIfPageNotFirstModifier(),
			'if_product_page_is_open' => new shopSeoIfProductPageIsOpenModifier(),
			'if_brand_category_is_open' => new shopSeoIfBrandCategoryIsOpenModifier(),
			'sep_backslash' => new shopSeoSepBackslashModifier(),
			'sep_comma' => new shopSeoSepCommaModifier(),
			'sep_hyphen' => new shopSeoSepHyphenModifier(),
			'sep_slash' => new shopSeoSepSlashModifier(),
			'sep_space' => new shopSeoSepSpaceModifier(),
			'reverse' => new shopSeoReverseModifier(),
			'depth' => new shopSeoDepthModifier(),
		);
	}

	public function fetch($template)
	{
		return preg_replace_callback('/\{'.preg_quote($this->name).'((?:\|[A-z0-9\_\-]+(?:\:([A-z0-9\_\-\,]+))?)*)\}/',
			array($this, 'arrayReplace'), $template);
	}

	/**
	 * @param array $matches
	 *
	 * @return array|string
	 */
	public function arrayReplace(array $matches)
	{
		$string_modifiers = ifset($matches[1]);
		preg_match_all('/\|([A-z0-9\_\-]+)(?:\:([A-z0-9\_\-\,]+))?/', $string_modifiers, $matches_modifiers);

		$found_modifiers = ifset($matches_modifiers[1], array());
		$args = ifset($matches_modifiers[2], array());
		$modifiers = $this->getModifiers();
		$value = $this->value;
		$sep = ' ';

		foreach ($found_modifiers as $i => $modifier)
		{
			$modifier = ifset($modifiers[$modifier]);

			if ($modifier instanceof shopSeoArrayModifier)
			{
				$new_sep = $modifier->getSep();
				$sep = ifset($new_sep, $sep);
				$value = $modifier->modify($value, ifset($args[$i]));
			}
			elseif ($modifier instanceof shopSeoModifier)
			{
				$new_value = array();

				foreach ($value as $v)
				{
					$new_v = $modifier->modify($v);

					if (!empty($new_v))
					{
						$new_value[] = $new_v;
					}
				}

				$value = $new_value;
			}
		}

		if (is_array($value))
		{
			return implode($sep, $value);
		}

		return $value;
	}

	public function __construct($name, array $value)
	{
		if (preg_match('/^[A-z0-9\_\-]+$/', $name) === 0)
		{
			throw new Exception('Недопустимое имя переменной');
		}

		$this->name = $name;
		$this->value = $value;
	}

	private $name;
	private $value;
}