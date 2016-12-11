<?php
	$con = mysqli_connect("localhost", "root", "", "smartacc") or die ("Error: connection failed, " . mysqli_connect_error());
	mysqli_query($con, "SET character_set_results=utf8") or die ("Error: Cannot set character set result.");
	mysqli_query($con, "SET character_set_client=utf8") or die ("Error: Cannot set character set client.");
	mysqli_query($con, "SET character_set_connection=utf8") or die ("Error: Cannot set character set connection.");

	$inCat = @$_POST["newIncomeCat"];
	$inSubCat = @$_POST["newIncomeSubCat"];
	$exCat = @$_POST["newExpenseCat"];
	$exSubCat = @$_POST["newExpenseSubCat"];
	$acc = @$_POST["newAccount"];
	$subAcc = @$_POST["newSubaccount"];

	/*
	echo "inCat: {$inCat}<br>";
	echo "inSubCat: {$inSubCat}<br>";
	echo "exCat: {$exCat}<br>";
	echo "exSubCat: {$exSubCat}<br>";
	echo "acc: {$acc}<br>";
	echo "subAcc: {$subAcc}<br>";
	*/

	// Create array of POST data above.
	$data = array
		(
			array($inCat, $inSubCat),
			array($exCat, $exSubCat),
			array($acc, $subAcc)
		);
	// Create array of data table in DB.
	$table = array
		(
			array("in_categories", "in_cats", "in_subcats"),
			array("ex_categories", "ex_cats", "ex_subcats"),
			array("account", "account", "sub_account")
		);

	$successCount = 0;

	for($i = 0; $i < count($data); $i++) {
		$tbname = $table[$i][0];
		$maincol = $table[$i][1];
		$subcol = $table[$i][2];

		if($data[$i][0] == "") {
			continue;
		}

		// check if new input has already exist.
		$str = "SELECT * FROM {$tbname} WHERE {$maincol} = \"{$data[$i][0]}\" AND {$subcol} = \"{$data[$i][1]}\";";
		$query = mysqli_query($con, $str) or die ("Error no.1: could not send query, " . mysqli_error($con));
		$result = mysqli_fetch_assoc($query);

		if(empty($result)) {
			$str = "INSERT INTO {$tbname} ({$maincol}, {$subcol}) VALUES (\"{$data[$i][0]}\", \"{$data[$i][1]}\");";
			$query = mysqli_query($con, $str) or die ("Error no.2: could not send query, " . mysqli_error($con));

			if($query) {
				$successCount++;

				if($successCount === 1) {
					echo "<span class='response' data-complete='true'>Successfully created!</span>";
				}
			}
		} else {
			echo "<span class='response' data-complete='false'>Could not create, your data already exists.</span>";
		}
	}

	mysqli_close($con);
?>