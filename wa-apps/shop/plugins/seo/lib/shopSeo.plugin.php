<?php

class shopSeoPlugin extends shopPlugin
{
    public function frontendHead()
    {
        if (!$this->pluginEnable())
        {
            return;
        }

        $action = waRequest::param('action');

        if (is_null($action))
        {
            $main_optimizer = new shopSeoMainOptimizerSet();
            $main_optimizer->execute();
        }
        elseif ($action == 'tag')
        {
            $tags_optimizer = new shopSeoTagsOptimizerSet();
            $tags_optimizer->execute();
        }
        elseif ($action == 'page')
        {
            $static_optimizer = new shopSeoStaticOptimizerSet();
            $static_optimizer->execute();
        }
        elseif ($action == 'brand')
        {
            $brands_optimizer = new shopSeoBrandsOptimizerSet();
            $brands_optimizer->execute();
        }
    }

    public function frontendCategory()
    {
        if (!$this->pluginEnable())
        {
            return;
        }

        $categories_optimizer = new shopSeoCategoriesOptimizerSet();
        $categories_optimizer->execute();
    }

    public function frontendProduct()
    {
        if (!$this->pluginEnable())
        {
            return;
        }

        $product_optimizer = new shopSeoProductsOptimizerSet();
        $product_optimizer->execute();
    }

    public function backendCategoryDialog($category)
    {
        $roting = new shopSeoRouting();
        $storefronts = $roting->getStorefronts();
        $settings = new shopSeoSettings();
        $seo_global_settings = $settings->getAll();
        $seo_settings = $settings->getAllByCategoryID(ifset($category['id']));
        $seo_settings_fields = $settings->getFields();

        $view = wa()->getView();
        $view->assign(array(
            'category' => $category,
            'seo_js' => array(
                $this->getPluginStaticUrl().'js/form.js',
                $this->getPluginStaticUrl().'js/variable.js',
                $this->getPluginStaticUrl().'js/category.js',
            ),
            'seo_css' => array(
                $this->getPluginStaticUrl().'css/form.css',
                $this->getPluginStaticUrl().'css/helper.css',
                $this->getPluginStaticUrl().'css/variable.css',
                $this->getPluginStaticUrl().'css/category.css',
            ),
            'seo_storefronts' => $storefronts,
            'seo_global_settings' => $seo_global_settings,
            'seo_settings' => $seo_settings,
            'seo_settings_fields' => $seo_settings_fields,
            'variables_template_path' => self::getVariablesTemplatePath(),
            'plugin_version' => $this->getVersion(),
            //'plugin_version' => time(),
        ));

        return $view->fetch($this->path.'/templates/CategoryDialog.html');
    }

    public function categorySave($category)
    {
        $settings = new shopSeoSettings();
        $seo_settings = waRequest::post('seo_settings', array());
        if (is_array($seo_settings))
            foreach ($seo_settings as $storefront => $_settings)
            {
                $settings->updateByCategoryID($_settings, ifset($category['id']), $storefront);
            }
    }

    public function categoryDelete($category)
    {
        $m_seo = new shopSeoSettingsCategoryModel();
        $m_seo->deleteByField('category_id', $category['id']);
    }

	public function backendProductEdit($product)
	{
        $roting = new shopSeoRouting();
        $storefronts = $roting->getStorefronts();
        $settings = new shopSeoSettings();
        $data = $settings->getAllByProductID($product['data']['id']);
        $seo_settings_fields = $settings->getFields();

		$view = wa()->getView();
        $view->assign(array(
            'product' => $product,
            'seo_js' => array(
                $this->getPluginStaticUrl().'js/form.js',
                $this->getPluginStaticUrl().'js/variable.js',
                $this->getPluginStaticUrl().'js/product.js',
            ),
            'seo_css' => array(
                $this->getPluginStaticUrl().'css/form.css',
                $this->getPluginStaticUrl().'css/helper.css',
                $this->getPluginStaticUrl().'css/variable.css',
                $this->getPluginStaticUrl().'css/product.css',
            ),
            'seo_storefronts' => $storefronts,
            'seo_data' => $data,
            'seo_settings_fields' => $seo_settings_fields,
        ));

		return array(
			'basics' => $view->fetch($this->path.'/templates/Product.html')
		);
	}

    public function productSave($product)
    {
        $settings = new shopSeoSettings();
        $data = waRequest::post('seo_settings', array());

        if (is_array($data))
            foreach ($data as $storefront => $_settings)
            {
                $settings->updateByProductID($_settings, $product['data']['id'], $storefront);
            }
    }

    public function productDelete($product)
    {
        $settings = new shopSeoSettings();

        return $settings->deleteByProductID($product['data']['id']);
    }

    public static function getVariablesTemplatePath()
    {
        $plugin = wa()->getPlugin('seo');

        return $plugin->path.'/templates/Variables.html';
    }

    private function pluginEnable()
    {
        $general_storefront = shopSeoRouting::GENERAL_STOREFRONT_NAME;
        $settings = new shopSeoSettings();
        $seo_settings = $settings->get($general_storefront);

        return ifset($seo_settings['plugin_enable'], false);
    }
}