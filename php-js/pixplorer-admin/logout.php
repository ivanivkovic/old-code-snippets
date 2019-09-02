<?php

include('config.php');

Logs::setLog('Admin ' . ADMIN_NAME . ' ' . ADMIN_ID_TAG . ' LOGGED OUT of admin panel.' , 'admin logins');

session_destroy();

die(header('Location: index.php'));

?>