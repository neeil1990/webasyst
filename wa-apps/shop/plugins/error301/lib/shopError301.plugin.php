<?php 
class shopError301Plugin extends shopPlugin
{
	public function index()
	{
		$model = new shopError301Model();
		$model->index();
        $this->saveSettings(array('index' => time()));
	}
	
	public function categorySave($category)
	{
		if(isset($category['parent_id']))
		{
			$model = new shopError301Model();
			$data = array(
				"id" => $category['id'],
				"type" => "c",
				"parent" => $category['parent_id'],
				"url" => $category['url'],
			);
			$model->insert($data, 1);
		}
		else //родитель передается только при создании категории
		{
			$model = new shopError301Model();
			$model->exec("INSERT IGNORE INTO `".$model->table."` SELECT `id`, 'c' as `type`, `url`, `parent_id` as `parent` FROM `shop_category` WHERE `id` = ".(int)$category['id'].";");
		}
	}
	
	public function categoryDelete($item)
	{
		$model = new shopError301Model();
		$model->deleteHistoryByID(array($item['id']), "c");
	}
	
	public function pageSave()
	{
		$get = waRequest::get();
		
		if(isset($get['action']) AND isset($get['module']) AND $get['action'] == 'pageSave' AND $get['module'] == 'product')
		{
			$model = new shopError301Model();
			if(isset($get['id']) AND $get['id'] > 0)
			{
				$post = waRequest::post();
				$data = array(
					"id" => $get['id'],
					"type" => "x",
					"url" => $post['info']['url'],
					"parent" => (int)$get['product_id']
				);
				$model->insert($data, 1);
			}
			else
			{
				$model->exec("INSERT IGNORE INTO `".$model->table."` SELECT `id`, 'x' as `type`, `url`, `product_id` as `parent` FROM `shop_product_pages`;");
			}
		}
	}
	
	public function productSave($params)
	{
		$model = new shopError301Model();
		$data = array(
			"id" => $params['data']['id'],
			"type" => "p",
			"url" => $params['data']['url'],
			"parent" => (int)$params['data']['category_id'],
		);
		$model->insert($data, 1);
	}
	
	public function productDelete($ids)
	{
		$model = new shopError301Model();
		$model->deleteHistoryByID($ids['ids'], "p");
		$model->deleteHistoryByID($ids['ids'], "x");
	}
	
	public function frontendError($params)
	{
		if(waRequest::get('error301', 0, 'int'))
			return false;
		
        $status = $this->getSettings();	
		if(!isset($status['index']) OR (time() - $status['index'] > 86400)) //некоторые изменения невозможно отследить, поэтому не чаще 1 раза в сутки индексируем
		{
			$this->index();
		}
		
		if ($params->getCode() == 404)
		{
            $redirect = $this->getRedirect();		
			if($redirect)
			{
				if(ini_get('allow_url_fopen'))
				{
					$file_headers = @get_headers($redirect.'?error301=1');
				
					foreach($file_headers as &$val)
					{
						$val = str_replace(array(" "), array(""), mb_strtoupper($val));
					}
								
					if(!(
						in_array('HTTP/0.9200OK', $file_headers) || in_array('HTTP/1.1200OK', $file_headers) || in_array('HTTP/1.0200OK', $file_headers) || in_array('HTTP/2200OK', $file_headers) ||
						in_array('HTTP/0.9301MOVEDPERMANENTLY', $file_headers) || in_array('HTTP/1.1301MOVEDPERMANENTLY', $file_headers) || in_array('HTTP/1.0301MOVEDPERMANENTLY', $file_headers) || in_array('HTTP/2301MOVEDPERMANENTLY', $file_headers) ||
						in_array('HTTP/0.9401UNAUTHORIZED', $file_headers) || in_array('HTTP/1.1401UNAUTHORIZED', $file_headers) || in_array('HTTP/1.0401UNAUTHORIZED', $file_headers) || in_array('HTTP/2401UNAUTHORIZED', $file_headers)
					))
					{
						return false;
					}
				}
				wa()->getResponse()->redirect($redirect, 301);
			}			
        }
	}
	
	public function getRedirect()
	{
		
		$routing = wa()->getRouting();
		$curRouting = $routing->dispatch();
		$url = $routing->getCurrentUrl();
		$search = array(
			'reviews' => false, //принадлежность адреса к отзыву
			'category' => false, //принадлежность адреса к категории
			'product' => false, //принадлежность адреса к товару
			'productpage' => false, //принадлежность адреса подстранице товара
			'url' => '', //url искомого объекта
			'parents' => array() //список родителей искомого объекта
		);
		
		/*
		2.Естественный 
		Страницы товаров: /category-name/subcategory-name/product-name/
		Страница отзывов на товар: /category-name/subcategory-name/product-name/reviews/
		Подстраницы товаров: /category-name/subcategory-name/product-name/page-name/
		Страницы категорий: /category-name/subcategory-name/
		
		0.Смешанный 
		Страницы товаров: /product-name/
		Страница отзывов на товар: /product-name/reviews/
		Подстраницы товаров: /product-name/page-name/
		Страницы категорий: /category/category-name/subcategory-name/subcategory-name/...
		
		1.Плоский (WebAsyst Shop-Script) 
		Страницы товаров: /product/product-name/
		Страница отзывов на товар: /product/product-name/reviews/
		Подстраницы товаров: /product/product-name/page-name/
		Страницы категорий: /category/category-name/
		*/
		
		$expUrl = explode("/", $url);
		unset($expUrl[count($expUrl)-1]); //последний блок адреса тоже не нужен
		$expUrl = array_values($expUrl);
		
		if(isset($expUrl[count($expUrl) - 1]) && $expUrl[count($expUrl) - 1] == 'reviews') // если в конце адреса /reviews/, то это адрес отзыва продукта
		{
			unset($expUrl[count($expUrl) - 1]);
			$search['reviews'] = true;
			$search['product'] = true;
		}	
		
		if(isset($expUrl[0]) && $expUrl[0] == 'product') //товар, плоская адресация
		{
			unset($expUrl[0]);
			$expUrl = array_values($expUrl);
			$search['product'] = true;
			
			if(count($expUrl) == 2) //подстраница товара
			{
				$search['productpage'] = true;
				$search['parents'] = array($expUrl[0]); //url товара заносим в родителя
				$search['url'] = $expUrl[1];
			}
			elseif(count($expUrl) > 0)
			{
				$search['url'] = $expUrl[count($expUrl)-1]; //теоретически только один блок остался, но на случай совсем кривого адреса берем последний
			}			
			unset($expUrl);
		}	
		
		if(isset($expUrl[0]) AND $expUrl[0]=="category") //Категория при смешанной или плоской адресации
		{
			unset($expUrl[0]);
			$expUrl = array_values($expUrl);
			$search['category'] = true;
			$search['url'] = $expUrl[count($expUrl)-1];
			unset($expUrl[count($expUrl)-1]);
			
			if(count($expUrl) != 0)
			{
				$search['parents'] = $expUrl;
			}
			unset($expUrl);
		}
		
		if(isset($expUrl[0]))
		{
			$search['url'] = $expUrl[count($expUrl)-1];
			unset($expUrl[count($expUrl)-1]);
			$search['parents'] = $expUrl;
			unset($expUrl);
		}
		$model = new shopError301Model();
		
		/*
		$item = array(
			'type' => 'x', //тип элемента (x - подстраница товара, p - продукт, с - категория)
			'id' => 1, //id элемента
			'parentID' => 1, //id родителя
			'parent' => '', //родительская часть адреса
			'url0' => '', //адрес для смешанной адресации
			'url1' => '', //адрес для плоской адресации
			'url2' => '', //адрес для естественной адресации
			'rating' => '', //релевантность найденного элемента
		);*/

		$item = $model->getItem($search);
		if($item['type'] == 'x' && $item['rating'] == '' && $item['parentID'] > 0) //подстраница удалена, а товар еще есть
		{
			$productModel = new shopProductModel();
			$product = $productModel->getById($item['parentID']);
			if(!empty($product['category_id']))
			{
				$categoryModel = new shopCategoryModel();
				$category = $categoryModel->getById($product['category_id']);
			}	
			if($product['id'] > 0)
			{
				$item['type'] = 'p';
				$item['parentID'] = $product['category_id'];
				$item['id'] = $product['id'];
				$item['url0'] = $product['url'].'/';
				$item['url1'] = 'product/'.$product['url'].'/';
				$item['url2'] = (empty($category['full_url']) ? '' : $category['full_url']).$product['url'].'/';
			}
		}

		if(empty($curRouting['url_type']))
			$curRouting['url_type'] = 0;
		if($item)
		{
			$base = wa()->getRouteUrl('shop/frontend', array(), true);
			$newUrl = $item['url'.$curRouting['url_type']];
			
			if($search['reviews'])
				return $base.$newUrl.'reviews/';
			
			return $base.$newUrl;
		}
		else
			return false;
	}
}