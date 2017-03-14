<?php
	$con = mysqli_connect("localhost", "root", "", "smartacc")
		or die ("Error: connection failed, " . mysqli_connect_error());
	mysqli_query($con, "SET character_set_results=utf8")
		or die ("Error: Cannot set character set result.");
	mysqli_query($con, "SET character_set_client=utf8")
		or die ("Error: Cannot set character set client.");
	mysqli_query($con, "SET character_set_connection=utf8")
		or die ("Error: Cannot set character set connection.");

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$transact = $_POST["transactionType"];
		$err = array();

		// --- 1. DATA VALIDATION ---
		// --- 1.1 CHECK DATA ARRAY LENGTH ---
		// Check the number of data.
		function checkDataLength($array, $length) {
			$num = count($array);
			if($num !== $length) {
				global $err;
				array_push($err, "Expect {$length} data length, gets {$num}.");
			}
		}
		// Check number of data.
		if($transact !== "tr") {
			checkDataLength($_POST, 12);
		} else if ($transact === "tr") {
			checkDataLength($_POST, 10);
		} else {
			array_push($err, "Incorrect number of data length.");
		}
		// --- 1.2 ALL DATA SIMPLE VALIDATION ---
		// Single simple validation.
		function validate_simple($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		for($i = 0; $i < count($_POST); $i++) {
			$data[key($_POST)] = validate_simple(array_values($_POST)[$i]);
			next($_POST);
		}

		$transact = $data["transactionType"];
		$date = $data["date"];
		$time = $data["time"];
		$amount = $data["amount"];
		$note = $data["note"];
		$cat = $subcat = $acc = $subacc = 
		$necessity = $income_type = $payer = 
		$payee = $facc = $fsubacc = $tacc = $tsubacc = null;

		// --- 1.3 VALIDATE TRANSACTION TYPE ---
		if($transact != "in"
			&& $transact != "ex"
			&& $transact != "tr")
		{array_push($err, "Error@transaction_type: Incorrect value!");}

		// Specific strong validation.
		function validate_specific($data, $data_name, $regex) {
			if(!preg_match($regex, $data)) {
				global $err;
				array_push($err, ucfirst($data_name) . " is incorrect!");
			}
			return $data;
		}
		// --- 1.4 VALIDATE DATE AND TIME ---
		// Only dddd-dd-dd is allowed.
		validate_specific($date, "date", "/^\d{4}-\d{2}-\d{2}$/");
		validate_specific($time, "time", "/^\d{2}:\d{2}$/");
		// --- 1.4 VALIDATE AMOUNT ---
		// Only digits are allowed.
		validate_specific($amount, "amount", "/^[.\d]*$/");
		// --- 1.5 VALIDATE NOTE ---
		// Only word characters, Thai language, space, and dash are allowed.
		validate_specific($note, "note", "/^[\wก-เ -]*$/"); // Last character in Thai language Regex is 'เ', [ก-เ] = [ก-ฮ๐-๙เ]

		// Database matching check.
		function validate_database($con, $data_name, $column, $table, $where = null) {
			$str = "SELECT DISTINCT $column FROM $table $where;";
			$query = mysqli_query($con, $str);
			$result = mysqli_fetch_all($query, MYSQLI_NUM);
			if(!@$result[0]) {
				global $err;
				array_push($err, "Unknown $data_name.");
			}
		}
		// Set variable in accordance with transaction type.
		if($transact !== "tr") {
			// --- 1.5 VALIDATE CATEGORY/SUBCATEGORY ---
			// --- 1.5.1 DATA STRING CHECK ---
			$cat = $data["cat"];
			$subcat = $data["subcat"];
			// Only word characters and Thai language are allowed. Space is not allowed.
			validate_specific($cat, "category", "/^[\wก-เ ]*$/");
			validate_specific($subcat, "subcategory", "/^[\wก-เ ]*$/");
			// --- 1.5.2 DATABASE MATCHING CHECK ---
			validate_database($con, "category/subcategory",
				"{$transact}_cats, {$transact}_subcats",
				"{$transact}_categories",
				"WHERE {$transact}_cats = '$cat' AND {$transact}_subcats = '$subcat'");
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

			if($transact === "in") {
				// --- 1.6 VALIDATE INCOME TYPE ---
				$income_type = $data["incomeType"];
				if($income_type !== "act" && $income_type !== "pas") {
					array_push($err, "Income type is incorrect!");
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
	$str = "INSERT INTO record (
						date, time, transaction_type, necessity, in_type,
						categories, subcategories, from_acc, from_subacc,
						acc, subacc, to_acc, to_subacc, payer, payee,
						amount, note
					)
					VALUES (
						'{$date}', '{$time}', '{$transact}',{$necessity},
						'{$income_type}', '{$cat}', '{$subcat}', '{$facc}',
						'{$fsubacc}', '{$acc}', '{$subacc}', '{$tacc}', '{$tsubacc}',
						'{$payer}', '{$payee}', {$amount}, '{$note}'
					);";

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