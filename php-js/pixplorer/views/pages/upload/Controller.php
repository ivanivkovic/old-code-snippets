<?php

class Controller extends BaseController{
	
	public function index(){
		die(header('Location: ' . SITE_URL));
	}
	
	# Page with upload results and add description form for each uploaded photo.
	public function result(){
	
		$user = $this->registry->user;
		
		if($user->logged_in)
		{
			$result = Picture::processUpload();
			
			if($result['success'] == false)
			{
				$this->registry->template->title = $this->registry->template->loadString('err_upload_fail');
				$this->registry->template->errormsg = $this->registry->template->loadString($result['message']);
			}
			else
			{
				# Upload.
				$uploads = Picture::upload($user->id);
				
				# Store result rows in an array for further use in multiple cases.
				foreach($uploads as $result)
				{
					while($fetch = $result->fetch(PDO::FETCH_ASSOC))
					{
						$upload_info[] = $fetch;
					}
					
				}
				
				$this->registry->template->uploads = $upload_info;
				
				# Facebook upload wall feed publish.
				if($uploads !== false)
				{
					if($user->facebook_connect === true && $user->loggedInViaFacebook())
					{
						Picture::facebookPublishUploads($upload_info[0], $user, count($upload_info), $this->registry->template);
					}
				}
				$this->registry->template->title = $this->registry->template->loadString('upload_success');
			}
			
			$this->registry->template->user = $this->registry->user;
			$this->registry->template->loadTemplate(__FUNCTION__);
			
		}else{
			header('Location: /');
		}
	}
	
	# Adds photos info from upload/result form.
	public function addinfo()
	{
		if($this->registry->user->logged_in)
		{
			if(isset($_POST['ids_list']))
			{
				$data = explode(',' , $_POST['ids_list']);
				
				foreach($data as $value)
				{
					$desc = Security::secureTextArea($_POST['desc_' . $value]);
					
					if($value != ' ' && $value != '')
					{
						if(Picture::exists($value))
						{
							if(Picture::ownedByUser($value, $this->registry->user->id))
							{
								Picture::uploadInfo($value, $desc);
								header('Location: ' . Conf::$page['profile_home'] );
								
							}
						}
					}
				}
			}
			else
			{
				die(header('Location: ' . SITE_URL));
			}
		}
		else
		{
			die(header('Location: ' . SITE_URL));
		}
	}
	
}