<?php
	
	function prepareDB(){

		global $databaseConnection;

		$connect_query = "host=ec2-54-197-249-167.compute-1.amazonaws.com port=5432 dbname=d1deq0bdiotmdi user=plsrhyziizmras password=I9cXpT9VDHk2AcHKVvyPfPWWti sslmode=require options='--client_encoding=UTF8'";
		$databaseConnection = pg_connect($connect_query);
		//or die('Could not connect: ' . pg_last_error());
		if($databaseConnection)
		{
			echo '<script> console.log("DB connected"); </script>';
		}

		return $databaseConnection;

	}	


	function insertFavorite($user_id,$post_id){

		$parametrer = array($user_id ,$post_id);
		$sqlName = 'InsertbyCode';
		$query_check_exist = "SELECT id FROM favorite WHERE (user_id = $1 && post_id = $2)";
        $statement_check_exist_result = pg_prepare($sqlName, $query_check_exist);
        $statement_check_exist_result = pg_execute($sqlName, $parametrer);
        $result = pg_num_rows($statement_check_exist_result);
        if ($result == 0)
        {
            $query_insert_roles = "INSERT INTO favorite(user_id, post_id) VALUES ($1, $2)";
            $statement_insert_roles = pg_prepare($sqlName, $query_insert_roles);
            $statement_insert_roles = pg_execute($sqlName, $parametrer);
            
        }

	}





?>