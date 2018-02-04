<?php

$path = wa()->getAppPath('plugins/seo', 'shop');

$files = array(
	'css/seo.CategoryDialog.css',
	'css/seo.General.css',
	'css/seo.Product.css',
	'css/seo.Settings.css',
	'js/seo.CategoryDialog.js',
	'js/seo.Product.js',
	'js/seo.Settings.js',
	'lib/classes/shopSeoBaseReplacesSet.class.php',
	'lib/classes/shopSeoBrandsMetaDescriptionOptimizer.class.php',
	'lib/classes/shopSeoBrandsMetaKeywordsOptimizer.class.php',
	'lib/classes/shopSeoBrandsMetaTitleOptimizer.class.php',
	'lib/classes/shopSeoBrandsReplacesSet.class.php',
	'lib/classes/shopSeoCategoriesDescriptionOptimizer.class.php',
	'lib/classes/shopSeoCategoriesH1Optimizer.class.php',
	'lib/classes/shopSeoCategoriesMetaDescriptionOptimizer.class.php',
	'lib/classes/shopSeoCategoriesMetaKeywordsOptimizer.class.php',
	'lib/classes/shopSeoCategoriesMetaTitleOptimizer.class.php',
	'lib/classes/shopSeoCategoriesReplacesSet.class.php',
	'lib/classes/shopSeoCategoriesStore.class.php',
	'lib/classes/shopSeoConst.class.php',
	'lib/classes/shopSeoCurrencyModifier.class.php',
	'lib/classes/shopSeoIReplacer.class.php',
	'lib/classes/shopSeoIfPageNotFirstModifier.class.php',
	'lib/classes/shopSeoLowerModifier.class.php',
	'lib/classes/shopSeoMainDescription.class.php',
	'lib/classes/shopSeoMainMetaDescriptionOptimizer.class.php',
    'lib/classes/shopSeoMainMetaKeywordsOptimizer.class.php',
    'lib/classes/shopSeoMainMetaTitleOptimizer.class.php',
	'lib/classes/shopSeoMainReplacesSet.class.php',
	'lib/classes/shopSeoMetaDescriptionOptimizer.class.php',
	'lib/classes/shopSeoMetaKeywordsOptimizer.class.php',
	'lib/classes/shopSeoMetaTitleOptimizer.class.php',
	'lib/classes/shopSeoModifier.class.php',
	'lib/classes/shopSeoOptimizer.class.php',
	'lib/classes/shopSeoOverwriteTemplateChecker.class.php',
	'lib/classes/shopSeoProcessor.class.php',
	'lib/classes/shopSeoProcessorBrandPage.class.php',
	'lib/classes/shopSeoProcessorCategoryPage.class.php',
	'lib/classes/shopSeoProcessorMainPage.class.php',
	'lib/classes/shopSeoProcessorProductPage.class.php',
	'lib/classes/shopSeoProcessorStaticPage.class.php',
	'lib/classes/shopSeoProcessorTagPage.class.php',
	'lib/classes/shopSeoProductsDescriptionOptimizer.class.php',
	'lib/classes/shopSeoProductsH1Optimizer.class.php',
	'lib/classes/shopSeoProductsMetaDescriptionOptimizer.class.php',
	'lib/classes/shopSeoProductsMetaKeywordsOptimizer.class.php',
	'lib/classes/shopSeoProductsMetaTitleOptimizer.class.php',
	'lib/classes/shopSeoProductsReplacesSet.class.php',
	'lib/classes/shopSeoProductsStore.class.php',
	'lib/classes/shopSeoRandomSwitch.class.php',
	'lib/classes/shopSeoReplacesSet.class.php',
	'lib/classes/shopSeoSettingsStore.class.php',
	'lib/classes/shopSeoStaticMetaDescriptionOptimizer.class.php',
	'lib/classes/shopSeoStaticMetaKeywordsOptimizer.class.php',
	'lib/classes/shopSeoStaticMetaTitleOptimizer.class.php',
	'lib/classes/shopSeoStaticReplacesSet.class.php',
	'lib/classes/shopSeoTagsMetaDescriptionOptimizer.class.php',
	'lib/classes/shopSeoTagsMetaKeywordsOptimizer.class.php',
	'lib/classes/shopSeoTagsMetaTitleOptimizer.class.php',
	'lib/classes/shopSeoTagsReplacesSet.class.php',
	'lib/classes/shopSeoTemplate.class.php',
	'lib/classes/shopSeoTemplateChecker.class.php',
	'lib/classes/shopSeoVariable.class.php',
);

foreach ($files as $file)
{
	$_path = $path . '/' . $file;

	if (file_exists($_path))
	{
		waFiles::delete($_path);
	}
}
