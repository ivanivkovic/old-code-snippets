<?php

# Search controller

class Controller extends BaseController{
	
	public function index($keyword = ''){
	
		if($keyword !== '')
		{
			$this->keyword($keyword);
		}
		else
		{
			header('Location: ' . WEB_PATH);
		}
	}
	
	public function keyword($keyword = ''){
		
		$criteria = $keyword !== '' ? $keyword : $this->criteria;
		$this->registry->template->footer = true;
		
		if($criteria !== '')
		{	
			$this->registry->template->nav_headline = ucfirst($this->registry->router->criteria);
			$result = Search::searchByKeywords($criteria, PAGE_LOAD_ITEM_LIMIT);
		
		}else{
			$this->registry->template->errormsg = $this->registry->template->loadString('err_post_criteria');
		}
		
		if(isset($result) && $result !== false){
		
			$this->registry->template->keyword = $criteria;
			$this->registry->template->result = $result;			
			$this->registry->template->footer = true;
			$this->registry->template->loadTemplate(__FUNCTION__);
			
		}else{
		
			$this->registry->template->errormsg = $this->registry->template->loadString('err_criteria_no_match');
			$this->registry->template->loadTemplate(__FUNCTION__);
			
		}
	}
	
	public function categories()
	{
		# Input types in order. Changing this will change the order of input recognition. (but then all $_GET links must be changed)
		$inputs = array('city', 'cat', 'subcat', 'country', 'region');
		
		# Getting get var values.
		if(isset($this->criteria) && $this->criteria != '')
		{
			foreach($inputs as $key => $value)
			{
				if($key == 0)
				{
					$key = '';
				}
				else
				{
					$key = $key + 1; # Because the criteria attribute names are criteria, criteria2 etc we take first occurence (0) without number, and others are increased by 1.
				}
				
				$attr = 'criteria' . $key;
				
				if(isset($this->registry->router->$attr))
				{
					$input[$value] = $this->registry->router->$attr;
				}
			}
		}
		
		# Input validation.
		if(isset($input))
		{
			foreach($inputs as $i)
			{
				if(isset($input[$i]))
				{
					if(Security::checkNumNotNull($input[$i]) != false)
					{
						switch($i){
							case 'cat':
							case 'subcat':
								if(Categories::fetchCatName($input[$i]) != false)
								{
									$$i = $input[$i];
								}
							break;
							case 'country':
							case 'region':
							case 'city':
								if(WorldDatabase::exists($i, $input[$i]) != false)
								{
									$$i = $input[$i];
								}
							break;
						}
					}
				}
			}
		}else{
			$this->registry->template->errormsg = $this->registry->template->loadString('err_post_criteria');
		}
		
		# Somehow, I can't pass a multidim array to the template, so I have to include separate widgets handling the data correctly. ($this->registry->template->file hold the files names.) Ivan Ivkoviæ
		
		# Search processing for category/subcategory and city. (Complete form filled out.)
		
		if(!isset($cat) && !isset($region) && !isset($country) && isset($city)){
		
			$this->registry->template->city_id = $city;
			$data = Picture::fetchAlbumsByCity($city);
			
			if($data !== false){
			
				$this->registry->template->data = $data;
				$this->registry->template->parent_data = WorldDatabase::getParent($city);
				$this->registry->template->city_name = WorldDatabase::fetchName($city, 'city');
				$this->registry->template->file = 'explore_results_by_city.php';
			}else{
				$this->registry->template->errormsg = $this->registry->template->loadString('err_criteria_no_match');
			}
		}
		
		if(isset($cat) && isset($city)){
		
			$this->registry->template->city_id = $city;
			
			$cat_id = isset($subcat) ? $subcat : $cat;
			$data = Picture::fetchAlbumsByLocationAndCategory($cat_id, $city);
			
			if($data !== false)
			{
				$this->registry->template->cat_name = Categories::fetchCatName($cat_id);
				$this->registry->template->city_name = clearAsterisk($world->fetchName($city, 'city'));
				$this->registry->template->parent_data = WorldDatabase::getParent($city);
				$this->registry->template->data = $data;
				$this->registry->template->cat_id = $cat_id;
				$this->registry->template->file = !isset($subcat) ? 'explore_results_bycat.php' : 'explore_results_bysubcat.php';
			}
			else
			{
				$this->registry->template->errormsg = $this->registry->template->loadString('err_criteria_no_match');
			}
		}

		# Search processing for category/subcategory only.
		if(isset($cat) && !isset($city) && !isset($region) && !isset($country))
		{
			$cat_id = isset($subcat) ? $subcat : $cat;
			$data = Picture::fetchAlbumsByCategory($cat_id);
			
			if($data !== false)
			{
				$this->registry->template->cat_name = Categories::fetchCatName($cat_id);
				$this->registry->template->data = $data;
				$this->registry->template->cat_id = $cat_id;
				$this->registry->template->file = !isset($subcat) ? 'explore_results_bycat.php' : 'explore_results_bysubcat.php';
			}
			else
			{
				$this->registry->template->errormsg = $this->registry->template->loadString('err_criteria_no_match');
			}
		}
		
		# For country/region/category/subcategory only. No cities.
		if(isset($cat) && !isset($city) && isset($country))
		{
			$cat_id = isset($subcat) ? $subcat : $cat;
			
			if(isset($region))
			{
				$data = Picture::fetchAlbumsByParentAndCategory($cat_id, $region, 'region');
				if($data !== false)
				{
					$this->registry->template->data = $data;
				}
				else
				{
					$this->registry->template->errormsg = $this->registry->template->loadString('err_criteria_no_match');
				}
			}else{
				$result = WorldDatabase::getChild($country, 'country');
				if($result[0] != 'region')
				{
					$data = Picture::fetchAlbumsByParentAndCategory($cat_id, $country, 'country');
					if($data !== false)
					{
						$this->registry->template->data = $data;
					}
					else
					{
						$this->registry->template->errormsg = 'There are no results that match your search criteria.';
					}
				}else{
					$this->registry->template->errormsg = $this->registry->template->loadString('err_specify_region');
				}
			}
			
			if(isset($data) && $data != false) # Activate the if statement later.
			{
				$this->registry->template->cat_name = $categories->fetchCatName($cat_id);
				$this->registry->template->cat_id = $cat_id;
				$this->registry->template->parent_name = isset($region) ? WorldDatabase::fetchName($region, 'region') : WorldDatabase::fetchName($country, 'country');
				$this->registry->template->file = !isset($subcat) ? 'explore_results_parent_bycat.php' : 'explore_results_parent_bysubcat.php';
			}
		}
		
		$this->registry->template->meta_description = $this->registry->template->loadString('search_by_categories');
		$this->registry->template->meta_keywords = '';
		
		$this->registry->template->title = $this->registry->template->loadString('search_results');
		$this->registry->template->user = $this->registry->user;
		$this->registry->template->footer = true;
		$this->registry->template->loadTemplate(__FUNCTION__);
	}
}