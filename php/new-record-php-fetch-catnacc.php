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
		$transaction_type = $_POST["transactionType"];
		$input_type = isset($_POST["inputType"]) ? $_POST["inputType"] : null;
	}

	if($input_type) {
		if($input_type === "acc") {
			$value = $_POST["acc"];
			fetchData($con, "sub_account", "account", "WHERE account = '{$value}'");
		} else {
			$subcolumn = "{$transaction_type}_subcats";
			$column = "{$transaction_type}_cats";
			$table = "{$transaction_type}_categories";
			$value = $_POST["cat"];
			fetchData($con, $subcolumn, $table, "WHERE {$column} = '{$value}'");
		}
	} else {
		if($transaction_type != "tr") {
			$cat = "{$transaction_type}_cats";
			$table = "{$transaction_type}_categories";
			fetchData($con, $cat, $table);
		} else {
			fetchData($con, "account", "account");
		}
	}
	
	function fetchData($con, $column, $table, $extension = null) {
		$str = "SELECT DISTINCT {$column} FROM {$table} {$extension};";
		$query = mysqli_query($con, $str)
			or die ("Error: could not send data, " . mysqli_error($con));
		$result = mysqli_fetch_aLL($query, MYSQLI_NUM)
			or die ("Error: could not fetch data, " . mysqli_error($con));
		if($result) {
			$x = array();
			//$x = "";
			for($i = 0; @$result[$i]; $i++) {
				if($i + 1 === count($result)) {
					array_push($x, $result[$i][0]);
					//$x .= $result[$i][0];
				} else {
					array_push($x, $result[$i][0]);
					//$x .= $result[$i][0] . ",";
				}
			}
			echo json_encode($x);
		}
	}

	mysqli_close($con);
?>