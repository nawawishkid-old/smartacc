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
	error_reporting(E_ALL & ~E_NOTICE);

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$transaction_type = $_POST["transactionType"];
		$err = array();

		// --- 1. DATA VALIDATION ---
		// --- 1.FUNCTION ---
		// Single simple validation.
		function validate_simple($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		// Check the number of data.
		function checkDataLength($array, $length) {
			$num = count($array);
			if($num !== $length) {
				global $err;
				array_push($err, "Expect {$length} data length, gets {$num}.");
			}
		}
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
		// Database matching check.
		function validate_database($con, $data_name, $column, $table, $where = null) {
			$str = "SELECT DISTINCT $column FROM $table $where;";
			$query = mysqli_query($con, $str);
			$result = mysqli_fetch_all($query, MYSQLI_NUM);
			if(!@$result[0]) {
				global $err;
				//$err = "Unknown $data_name.";
				array_push($err, "Unknown $data_name.");
			}
		}

		// Check number of data.
		if($transaction_type !== "tr") {
			checkDataLength($_POST, 12);
		} else if ($transaction_type === "tr") {
			checkDataLength($_POST, 10);
		} else {
			//$err = "Incorrect number of data length.";
			array_push($err, "Incorrect number of data length.");
		}

		// Validate all data from $_POST to new associative array.
		$data = array();
		for($i = 0; $i < count($_POST); $i++) {
			$data[key($_POST)] = validate_simple(array_values($_POST)[$i]);
			//array_push($arr, "[" . key($_POST) . "] =>" . array_values($_POST)[$i]);
			next($_POST);
		}

		$transaction_type = $data["transactionType"];
		$date = $data["date"];
		$time = $data["time"];
		$amount = $data["amount"];
		$note = $data["note"];
		$cat = $subcat = $acc = $subacc = $necessity = $income_type = $payer = $payee = $facc = $fsubacc = $tacc = $tsubacc = null;

		// --- STRONG VALIDATION ---
		// --- 1.1 VALIDATE TRANSACTION TYPE ---
		// Correctness
		if($transaction_type != "in"
			&& $transaction_type != "ex"
			&& $transaction_type != "tr")
		{
			//$err = "Error@transaction_type: Incorrect value!";
			array_push($err, "Error@transaction_type: Incorrect value!");
			//die ($err);
		}
		// --- 1.2 VALIDATE DATE AND TIME ---
		// Only dddd-dd-dd is allowed.
		validate_specific($date, "date", "/^\d{4}-\d{2}-\d{2}$/");
		validate_specific($time, "time", "/^\d{2}:\d{2}$/");
		// --- 1.3 VALIDATE AMOUNT ---
		// Only digits are allowed.
		validate_specific($amount, "amount", "/^[.\d]*$/");
		// --- 1.4 VALIDATE NOTE ---
		// Only word characters, Thai language, space, and dash are allowed.
		validate_specific($note, "note", "/^[\wก-เ -]*$/"); // Last character in Thai language Regex is 'เ', [ก-เ] = [ก-ฮ๐-๙เ]

		// Set variable in accordance with transaction type.
		if($transaction_type !== "tr") {
			// --- 1.4 VALIDATE CATEGORY/SUBCATEGORY ---
			// --- 1.4.1 DATA STRING CHECK ---
			$cat = $data["cat"];
			$subcat = $data["subcat"];
			// Only word characters and Thai language are allowed. Space is not allowed.
			validate_specific($cat, "category", "/^[\wก-เ ]*$/");
			validate_specific($subcat, "subcategory", "/^[\wก-เ ]*$/");
			// --- 1.4.2 DATABASE MATCHING CHECK ---
			validate_database($con, "category/subcategory",
				"{$transaction_type}_cats, {$transaction_type}_subcats",
				"{$transaction_type}_categories",
				"WHERE {$transaction_type}_cats = '$cat' AND {$transaction_type}_subcats = '$subcat'");
			// --- 1.5 VALIDATE ACCOUNT/SUBACCOUNT ---
			// --- 1.5.1 DATA STRING CHECK ---
			$acc = $data["acc"];
			$subacc = $data["subacc"];
			// Only word characters and Thai language are allowed. Space is not allowed.
			validate_specific($acc, "account", "/^[\wก-เ ]*$/");
			validate_specific($subacc, "subaccount", "/^[\wก-เ ]*$/");
			// --- 1.5.2 DATABASE MATCHING CHECK ---
			validate_database($con, "account/subaccount",
				"account, sub_account", "account",
				"WHERE account = '$acc' AND sub_account = '$subacc'");

			if($transaction_type === "in") {
				// --- 1.6 VALIDATE INCOME TYPE ---
				$income_type = $data["incomeType"];
				if($income_type !== "act" && $income_type !== "pas") {
					//$err = "Error@income_type: Incorrect value!";
					array_push($err, "Income type is incorrect!");
					//die ($err);
				}
				// --- 1.7 VALIDATE PAYER ---
				$payer = $data["payer"];
				validate_specific($payer, "payer", "/^[\wก-เ -]*$/");
			} else {
				// --- 1.6 VALIDATE NECESSITY ---
				$necessity = $data["necessity"];
				if($necessity != 0 && $necessity != 1) {
					//$err = "Necessity is incorrect!";
					array_push($err, "Necessity is incorrect!");
				}
				// --- 1.7 VALIDATE PAYEE ---
				$payee = $data["payee"];
				validate_specific($payee, "payee", "/^[\wก-เ -]*$/");
			}
		} else {
			// --- 1.4 VALIDATE FROM/TO ACCOUNT/SUBACCOUNT ---
			// --- 1.4.1 STRING CHECK ---
			$facc = $data["fAcc"];
			validate_specific($facc, "from_account", "/^[\wก-เ ]*$/");
			$fsubacc = $data["fSubacc"];
			validate_specific($fsubacc, "from_subaccount", "/^[\wก-เ ]*$/");
			$tacc = $data["tAcc"];
			validate_specific($tacc, "to_account", "/^[\wก-เ ]*$/");
			$tsubacc = $data["tSubacc"];
			validate_specific($tsubacc, "to_subaccount", "/^[\wก-เ ]*$/");
			// --- 1.4.2 DATABASE MATCHING CHECK ---
			validate_database($con, "from_account/from_subaccount",
				"account, sub_account", "account",
				"WHERE account = '$facc' AND sub_account = '$fsubacc'");
			validate_database($con, "to_account/to_subaccount",
				"account, sub_account", "account",
				"WHERE account = '$tacc' AND sub_account = '$tsubacc'");
		}

		// --- 1.ID VALIDATE ID ---
		$encrypted_id = $data["id"];
		validate_specific($encrypted_id, "id", "/^[\d.E+]*$/");

		// --- DECRYPT ID USING BINARY SEARCH ---
		function id_decryption($con, $encrypted_id) {
			$str = "SELECT COUNT(id) AS rowsNum FROM record;";
			$query = mysqli_query($con, $str);
			$result = mysqli_fetch_assoc($query)
				or die ("Error: could not fetch 'COUNT()', " . mysqli_error());
			
			$enc_id = $encrypted_id;
			$ceil = $result["rowsNum"];
			$floor = 0;
			$num = floor($ceil / 2);
			while(true) {
				$x = pow($num, 5);
				if($x == $enc_id) {
					$id = $num;
					break;
				} else {
					if ($x > $enc_id) {
						$ceil = $num;
						$num = floor($ceil - (($ceil - $floor) / 2));
					}
					if ($x < $enc_id) {
						$floor = $num;
						$num = ceil($floor + (($ceil - $floor) / 2));
					}
				}
			}
			return $id;
		}
		$id = id_decryption($con, $encrypted_id);
		// --- TIME ---
		date_default_timezone_set("Asia/Bangkok");
		$date_time = date_format(date_create(), "Y-m-d H:i:s");
	} else {
		die ("Error@REQUEST_METHOD");
	}

	// --- 2. ERROR CHECKING ---
	if(count($err) !== 0) {
		$status = 0;
		$status_text = $err[0];
		$responseArr = array("status" => $status, "status_text" => $status_text);
		echo json_encode($responseArr);
		exit();
	}

	// --- 3. UPDATE DATABASE ---
	$str = "UPDATE record SET date = '{$date}', time = '{$time}', transaction_type = '{$transaction_type}',
					necessity = {$necessity}, in_type = '{$income_type}', categories = '{$cat}',
					subcategories = '{$subcat}', from_acc = '{$facc}', from_subacc = '{$fsubacc}',
					acc = '{$acc}', subacc = '{$subacc}', to_acc = '{$tacc}', to_subacc = '{$tsubacc}',
					payer = '{$payer}', payee = '{$payee}', amount = {$amount}, note = '{$note}',
					timestamp = '{$date_time}'
					WHERE id = {$id};";
	$query = mysqli_query($con, $str) or die ("Error: could not send query, " . mysqli_error($con));

	// --- 4. SENDING JSON TO JAVASCRIPT ---
	if($query) {
		$status = 1;
		$status_text = "Update complete!";
	}
	$responseArr = array("status" => $status, "status_text" => $status_text);
	echo json_encode($responseArr);
	//echo "status = {$status}\nstatus_text = {$status_text}\n";

	mysqli_close($con);
?>