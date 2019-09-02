<?php

class User{

	public $id = false;
	public $data;
	public $social_id;
	public $social_data = array();
	public $social_type; # 0 = Facebook user. 1 = Twitter user.
	public $facebook_connect; # 0 = Facebook user. 1 = Twitter user.
	public $logged_in = false;
	public $fb_object;
	public $social_url;

	private $fb_graph = 'https://graph.facebook.com/';
	private $db;
	
	# Load objects.
	public function __construct($config)
	{
		$this->fb_object = new Facebook($config);
	}
	
	# Checks if the visitor is user is logged in via any social network to the site.
	public function loggedInViaAnyNetwork()
	{
		$result = $this->fb_object->getUser() !== 0 /* more social conditionals will be added here */ ? true : false;
		return $result;
	}
	
	# Clear.
	public function loggedInViaFacebook(){
		$return = $this->fb_object->getUser() !== 0 ? true : false;
		return $return;
	}
	
	# Returns a session or a "false" value.
	public function getSession()
	{
		if(isset($_SESSION['user']))
		{
			$this->id = $_SESSION['user'];
			return true;
		}
		else
		{
			return false;
		}
	}
	
	# If the social user (i.e. facebook user) ever was on this site, has records in DB.
	public function findInDB($id, $social_type)
	{
		$query = 'SELECT * FROM sc_users WHERE social_id = "' . $id . '" AND social_type="' . $social_type . '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			return $fetch['user_id']; # Returns user id.
		}
		else
		{
			return false; # Not found in DB.
		}
	}
	
	# Record to DB.
	public function registerToDB($id, $social_type) # Registers a user by his social network type and social network ID.
	{ 
		$query = 'INSERT INTO sc_users SET social_id = "' . $id . '", social_type="' . $social_type . '"';
		$result = DB::sql($query);
	}
	
	# Get social data (type, id) from a specific website user.
	public function getDBSocialData()
	{
		$query = 'SELECT social_type, social_id FROM sc_users WHERE user_id = "' . $this->id . '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			$this->social_id = $fetch['social_id'];
			$this->social_type = $fetch['social_type'];
		}
		else{
			return false;
		}
	}
	
	# Get user data from social networks directly, in general.
	public function getSocialNetworkData()
	{
		if(is_numeric($this->social_type) && $this->social_type == 0) # Facebook
		{
			$fql = 'SELECT username, name, first_name, last_name, pic_big, pic_square FROM user where uid = "' . $this->social_id . '"';
			$result = $this->fb_object->api(array(
									  'method' => 'fql.query',
									  'query' => $fql,
									));
			$this->social_data = $result[0];
		}
	}
	
	
	# Gets social network data from site database.
	public function getDBUserData()
	{
	
		if(is_numeric($this->social_type) && $this->social_type == 0) # Facebook
		{
			$query = 'SELECT * FROM sc_users WHERE user_id = "' . $this->id . '"';
			$result = DB::sql($query);
			
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				$this->data['first_name'] = $fetch['first_name'];
				$this->data['last_name'] = $fetch['last_name'];
				$this->data['username'] = $fetch['username'];
				$this->data['fullname'] = $fetch['fullname'];
				$this->data['nav_user_pic'] = $fetch['nav_user_pic'];
				$this->data['profile_pic'] = $fetch['profile_pic'];
			}
		}
	}
	
	# Converts social data into object data. ############### OBSULETE
	public function convertSocialData()
	{
		if(is_numeric($this->social_type) && $this->social_type == 0) # Facebook
		{
			$this->data['first_name'] = $this->social_data['first_name'];
			$this->data['last_name'] = $this->social_data['last_name'];
			$this->data['username'] = $this->social_data['username'];
			$this->data['fullname'] = $this->social_data['name'];
			$this->data['nav_user_pic'] = $this->social_data['pic_square'];
			$this->data['profile_pic'] = $this->social_data['pic_big'];
		}
	}
	
	# Gets data from social network to database. Used for updating data on every login.
	public function updateDBUserData()
	{
		$query = 'UPDATE sc_users SET 
							first_name="' . $this->data['first_name'] . '",
							last_name="' . $this->data['last_name'] . '",
							fullname="' . $this->data['fullname'] . '",
							nav_user_pic="' . $this->data['nav_user_pic'] . '",
							profile_pic="' . $this->data['profile_pic'] . '",
							username="' . $this->data['username'] . '"
							WHERE user_id = "' . $this->id . '"
							';
		$result = DB::sql($query);
	}
	
	# Updates periodically all site users. In construction?
	public function updateAllUsersDBData(){
		
	}
	
}