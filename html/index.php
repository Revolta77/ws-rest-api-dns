<!doctype html>
<html dir = "ltr" lang = "sk">
<?php

use ws\Connection;
include_once ( SITEPATH . 'html/header.php');
include_once (CLASSPATH . 'connection.php');
$hash = explode ("/", $_SERVER['REQUEST_URI'])[1];
$api = new Connection();
// kedze mame iba jedneho uzivatela, vyberieme hned prvu hodnotu pola
$user_id = $api->getUsersId()[0];
if ( !empty( $user_id ) ){
	$data = $api->getAllData()[$user_id];
	if ( is_array($data) ) {
		include_once ( SITEPATH . 'html/body.php');
		include_once ( SITEPATH . 'html/footer.php');
	}
}

?>
</html>