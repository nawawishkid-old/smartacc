<?php
	$con = mysqli_connect("localhost", "root", "", "smartacc")
		or die ("Error: connection failed, " . mysqli_connect_error());
	mysqli_query($con, "SET character_set_results=utf8")
		or die ("Error: Cannot set character set result.");
	mysqli_query($con, "SET character_set_client=utf8")
		or die ("Error: Cannot set character set client.");
	mysqli_query($con, "SET character_set_connection=utf8")
		or die ("Error: Cannot set character set connection.");

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$id = $_POST["id"];
	}

	//$str = "DELETE FROM record WHERE id = '{$id}';";
	$query = mysqli_query($con, $str) 
		or die ("Error: could not send query, " . mysqli_error($con));

	if($query) {
		echo "ITEM DELETED!";
	}

	mysqli_close($con);
?>