<?php
require 'database.php';

if(isset($_POST['get_message'])) {
	open_my_db();

	$message_hash = "";

	//TODO Have in mind that JSON message can be broken.
	$json = json_decode($_POST['get_message'], true);
	foreach ($json as $key => $value) {
		//TODO Move keys as constants in separate PHP file.
		if($key == 'message_hash') {
			$message_hash = mysql_real_escape_string( $value );
		}
	}

	//TODO Replace SQL with stored procedure call.
	$result = query_my_db( "SELECT registered, parent_hash, message_hash, instance_hash, message, rating FROM correspondence WHERE message_hash = '".$message_hash."';" );
	close_my_db();

	$response = array();
	if($result != false){
		$response["found"] = "true";
		$response["registered"] = "".trim($result[0][0],"\r\n");
		$response["parent_hash"] = "".trim($result[0][1],"\r\n");
		$response["message_hash"] = "".trim($result[0][2],"\r\n");
		$response["instance_hash"] = "".trim($result[0][3],"\r\n");
		$response["message"] = "".trim($result[0][4],"\r\n");
		$response["rating"] = "".trim($result[0][5],"\r\n");
	} else {
		$response["found"] = "false";
	}

	echo( json_encode($response) );
}

if(isset($_POST['replay'])) {
	open_my_db();

	$parent_hash = "";
	$message_hash = "";
	$instance_hash = "";
	$message = "";
	$rating = "";

	//TODO Have in mind that JSON message can be broken.
	$json = json_decode($_POST['replay'], true);
	foreach ($json as $key => $value) {
		//TODO Move keys as constants in separate PHP file.
		if($key == 'parent_hash') {
			$parent_hash = mysql_real_escape_string( $value );
		}
		if($key == 'message_hash') {
			$message_hash = mysql_real_escape_string( $value );
		}
		if($key == 'instance_hash') {
			$instance_hash = mysql_real_escape_string( $value );
		}
		if($key == 'message') {
			$message = mysql_real_escape_string( $value );
		}
	}

	//TODO Replace SQL with stored procedure call.
	query_my_db( "INSERT INTO correspondence (parent_hash, message_hash, instance_hash, rating, message) VALUES ('".$parent_hash."', '".$message_hash."', '".$instance_hash."', '".$rating."', '".$message."');" );
	close_my_db();

	//TODO Inform all moderators.
}

?>
