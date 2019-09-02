<?php

# Settings that must be applied for the editor to edit the data.

$object = new User($db);

if(isset($_GET['criteria'])){
	$criteria = $_GET['criteria'];
}

$not_found = 'There are no users on the site.';
$not_found_by_criteria = 'There are no users in this group.';

$actions = array('Ban', 'Unban');

# Criterias
$criteria_setting['onoff'] = true;
$criteria_setting['name'] = 'Group By: ';

include('widgets/editor.php');