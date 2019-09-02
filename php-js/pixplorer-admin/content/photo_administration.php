<?php

# Settings that must be applied for the editor to edit the data.

$object = new Picture($db);

if(isset($_GET['criteria'])){
	$criteria = $_GET['criteria'];
}

$not_found = 'There are no pictures posted.';
$not_found_by_criteria = 'There are no pictures posted by this criteria.';

$actions = array('Hide', 'Unhide', 'Remove');

# Criterias
$criteria_setting['onoff'] = true;
$criteria_setting['name'] = 'Date Posted: ';

include('widgets/editor.php');