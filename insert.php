<?php
	/*
	$connect_string = "host=ec2-54-197-249-167.compute-1.amazonaws.com port=5432 dbname=d1deq0bdiotmdi user=plsrhyziizmras password=I9cXpT9VDHk2AcHKVvyPfPWWti sslmode=require options='--client_encoding=UTF8'";
	echo "<script> alert('DB not connected'); </script>";
	$databaseConnection = pg_connect($connect_string) or die('Could not connect: '.pg_last_error());
	echo "<script> alert('DB connected'); </script>";*/

	$databaseConnection = pg_connect("host=ec2-54-197-249-167.compute-1.amazonaws.com port=5432 dbname=d1deq0bdiotmdi user=plsrhyziizmras password=I9cXpT9VDHk2AcHKVvyPfPWWti sslmode=require options='--client_encoding=UTF8'") or die('Could not connect: ' . pg_last_error());

	//$databaseConnection = null;
	if(isset($_POST['action']) && !empty($_POST['action'])) 
	{
		$action = $_POST['action'];
		switch($action) 
		{
	        case 'insertFavorite':
	        	insertFavorite($_POST['user_id'],$_POST['post_id'],$_POST['catalogy'],$_POST['author']);
	        	break;
	        case 'users':
	        	users($_POST['user_id'],$_POST['user_name'],$_POST['user_username'],$_POST['user_gender']);
	        	break;
        	case 'addList':
        		addlist($_POST['cata_name'],$_POST['owner_id']);
        		break;
	        /*
	        case 'getList':
	        	getlist($_POST['owner_id']);
	        	break;
	        	*/

    	}
	
	}

	function insertFavorite($user_id,$post_id,$catalogy,$author){
		global $databaseConnection;

		if($catalogy == 'general'){
			//check post in favorite
			$query_check_exist = "SELECT * FROM favorite WHERE (post_id = '$post_id' AND user_id = '$user_id');";
		    $statement_check_exist_result = pg_query($databaseConnection,$query_check_exist);
		    $result = pg_num_rows($statement_check_exist_result);
		    if($result == 0){//post not in favorite
		    	/*//check post in User_fav
		    	$query_check_User_fav_exist = "SELECT * FROM User_fav WHERE (post_id = '$post_id' AND user_id = '$user_id' );";
		    	$statement_check_exist_result = pg_query($databaseConnection,$query_check_User_fav_exist);
		    	$User_result = pg_num_rows($statement_check_exist_result);
		    	if($User_result == 0){//not in User_fav either*/
		    		//insert post in favorite
	    		$query_insert = "INSERT INTO favorite(user_id, post_id, catalogy, author) VALUES ('$user_id', '$post_id', '$catalogy', '$author');";
        		$statement_insert_post = pg_query($databaseConnection,$query_insert);		    	
	        }
		}else{
			//check post in User_fav
			$query_check_User_fav_exist = "SELECT * FROM User_fav WHERE (post_id = '$post_id' AND user_id = '$user_id' AND catalogy = $catalogy);";
	    	$statement_check_exist_result = pg_query($databaseConnection,$query_check_User_fav_exist);
	    	$result = pg_num_rows($statement_check_exist_result);
	    	if($result == 0){//not in User_fav in this catalogy
		    	//insert post in User_fav
		    	$query_insert = "INSERT INTO User_fav(user_id, post_id, catalogy, author) VALUES ('$user_id', '$post_id', '$catalogy', '$author');";
        		$statement_insert_post = pg_query($databaseConnection,$query_insert);
        		//insert post in favorite
        		$query_insert_User_fav = "INSERT INTO favorite(user_id, post_id, catalogy, author) VALUES ('$user_id', '$post_id', '$catalogy', '$author');";
	        	$statement_insert_post_User_fav = pg_query($databaseConnection,$query_insert_User_fav);
	        }
		}
		/*
		$query_check_exist = "SELECT * FROM favorite WHERE (post_id = '$post_id' AND user_id = '$user_id' AND catalogy = '$catalogy');";
	    $statement_check_exist_result = pg_query($databaseConnection,$query_check_exist);
	    $result = pg_num_rows($statement_check_exist_result);
	    if($result == 0){
	    	/*$query_check_general_exist = "SELECT * FROM favorite WHERE (post_id = '$post_id' AND user_id = '$user_id' AND catalogy = 'general');";
	    	$statement_check_general_exist_result = pg_query($databaseConnection,$query_check_general_exist);
	    	$result_general = $result = pg_num_rows($statement_check_general_exist_result); 	    	
	    	if($result_general == 0) {
	    		$query_insert = "INSERT INTO favorite(user_id, post_id, catalogy, author) VALUES ('$user_id', '$post_id', '$catalogy', '$author');";
	        	$statement_insert_post = pg_query($databaseConnection,$query_insert);
	    	} else {
	    		$query_update = "UPDATE favorite SET catalogy = '$catalogy' WHERE (post_id = '$post_id' AND user_id = '$user_id' AND catalogy = 'general');";
	    		$statement_update_catalogy = pg_query($databaseConnection,$query_insert);
	    	} */ 	
	}

	function addlist($cata_name,$user_id){
		global $databaseConnection;
		$query_addlist = "INSERT INTO catalogy(cata_name,owner_id) VALUES ('$cata_name','$user_id');";
	    $statement_insert_roles = pg_query($databaseConnection,$query_addlist);

	}
/*
	function getlist($user_id){
		header("Content-type: application/json");   
    	global $databaseConnection;
	    $arr = array();
	    $query_fetch_list_name="SELECT row_to_json(t) FROM ( SELECT cata_name FROM catalogy WHERE owner_id = '$user_id')t;";
	    $query_fetch_list_name = "SELECT cata_name FROM catalogy WHERE owner_id = '$user_id';";
	    $statement_fetch_list_name = pg_query($databaseConnection,$query_fetch_list_name);
	    $query_result = pg_fetch_row($statement_fetch_list_name);

	      
	    while($obj = pg_fetch_object($statement_fetch_list_name)) {
	          $arr[] = $obj;
	    }
	    $returnres = $user_id.'and'.$_POST['owner_id'];
	    echo 'returnres';
	    echo $arr;
	    
	}
*/
	function users($user_id,$user_name,$user_username,$user_gender){
		
		//global $databaseConnection;
		//date_default_timezone_set('Asia/Taipei');
		global $databaseConnection;
		$currentdate = date("Y-m-d H:i:s");

		$query_check_exist = "SELECT id FROM users WHERE (user_id = '$user_id');";
	    $statement_check_exist_result = pg_query($databaseConnection,$query_check_exist);
	    $query_result = pg_fetch_row($statement_check_exist_result);
	    //$result = pg_num_rows($statement_check_exist_result);
	    if($query_result[0]==null)
	    {
	    	$query_insert = "INSERT INTO users(user_id,user_name,create_time,user_username,gender) VALUES ('$user_id','$user_username','$currentdate','$user_name','$user_gender');";
	        //$query_insert = "INSERT INTO users(user_id,user_name,create_time,user_username,gender) VALUES ('115556','user_username','currentdate','user_name','user_gender');";
	        //echo $query_insert;
	        $statement_insert_roles = pg_query($databaseConnection,$query_insert);
	    }
	}

?>