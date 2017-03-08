<?php
	$con = mysqli_connect("localhost", "root", "", "smartacc")
		or die ("Error: connection failed, " . mysqli_connect_error());
	mysqli_query($con, "SET character_set_results=utf8")
		or die ("Error: Cannot set character set result.");
	mysqli_query($con, "SET character_set_client=utf8")
		or die ("Error: Cannot set character set client.");
	mysqli_query($con, "SET character_set_connection=utf8")
		or die ("Error: Cannot set character set connection.");

	// Report all errors except E_NOTICE
	// to prevent error in JSON object string.
	//error_reporting(E_ALL & ~E_NOTICE);

	if($_SERVER["REQUEST_METHOD"] != "POST") {
		die ("Incorrect request method.");
	}

	// --- 1. RECEIVED DATA VALIDATION ---
	$err = array();
	// --- 1.1 SIMPLE VALIDATION ---
	// Single simple validation.
	function validate_simple($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	// Validate all data from $_POST to new associative array.
	$data = array();
	for($i = 0; $i < count($_POST); $i++) {
		$data[key($_POST)] = validate_simple(array_values($_POST)[$i]);
		//array_push($arr, "[" . key($_POST) . "] =>" . array_values($_POST)[$i]);
		next($_POST);
	}
	$main = $data["main"];
	$sub = $data["sub"];
	$transact = $data["transactionType"];
	$tablename = $data["tablename"];
	// --- 1.2 STRONG VALIDATION ---
	// --- 1.2.1 VALIDATE TRANSACTION TYPE ---
	// Correctness
	if($transact != "in"
		&& $transact != "ex"
		&& $transact != "tr")
	{array_push($err, "Incorrect transaction type!");}
	// --- 1.2.2 VALIDATE TABLE NAME ---
	if($tablename != "cat" && $tablename != "acc") {
		array_push($err, "Incorrect tablename! (in JS file)");
	}
	// --- 1.2.3 VALIDATE MAIN AND SUB DATA ---
	// Specific strong validation.
	function validate_specific($data, $data_name, $regex) {
		if(!preg_match($regex, $data)) {
			global $err;
			array_push($err, ucfirst($data_name) . " is incorrect!");
			//$err = "Error@$data_name: Incorrect value!";
			//die ($err);
		}
		return $data;
	}
	$main = validate_specific($main, "main input", "/^[\wก-เ ]*$/");
	$sub = validate_specific($sub, "sub input", "/^[\wก-เ ]*$/");

	// --- 2. DEFINE DATA TABLE TO INSERT DATA TO ---
	if($tablename == "cat") {
		if($transact == "in") {
			$table = "in_categories";
			$main_col = "in_cats";
			$sub_col = "in_subcats";
			$data_name = "income category";
		} else {
			$table = "ex_categories";
			$main_col = "ex_cats";
			$sub_col = "ex_subcats";
			$data_name = "expense category";
		}
	} else {
		$table = "account";
		$main_col = "account";
		$sub_col = "sub_account";
		$data_name = "account";
	}

	// --- 3 CHECK IF NEW CAT/ACC IS ALREADY EXISTS ---
	// Database matching check.
	function validate_database($con, $data_name, $column, $table, $where = null) {
		$str = "SELECT DISTINCT $column FROM $table $where;";
		$query = mysqli_query($con, $str);
		$result = mysqli_fetch_all($query, MYSQLI_NUM);
		if($result) {
			global $err;
			//$err = "Unknown $data_name.";
			array_push($err, "This $data_name is already exists.");
		}
	}
	validate_database($con, $data_name, "{$main_col}, {$sub_col}", $table
		, "WHERE {$main_col} = '{$main}' AND {$sub_col} = '{$sub}';");

	// --- 4. ERROR CHECKING ---
	if(count($err) !== 0) {
		$status = 0;
		$status_text = $err[0];
		$responseArr = array("status" => $status, "status_text" => $status_text);
		echo json_encode($responseArr);
		exit();
	}

	// --- 5. SENDING DATA TO DATABASE ---
	$str = "INSERT INTO {$table} ({$main_col}, {$sub_col})
					VALUES ('{$main}', '{$sub}');";
	$query = mysqli_query($con, $str) or die ("Error: could not send query, " . mysqli_error($con));
	// --- 4. SENDING JSON TO JAVASCRIPT ---
	if($query) {
		$status = 1;
		$status_text = "Update complete!";
	}
	$responseArr = array("status" => $status, "status_text" => $status_text);
	echo json_encode($responseArr);

	mysqli_close($con);