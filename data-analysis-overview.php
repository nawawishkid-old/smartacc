<?php
	$con = mysqli_connect("localhost", "root", "", "smartacc")
		or die ("Error: Could not connect to database, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_results=utf8")
		or die ("Error: Could not set character set results, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_client=utf8")
		or die ("Error: Could not set character set client, " . mysqli_error($con));
	mysqli_query($con, "SET character_set_connection=utf8")
		or die ("Error: Could not set character set connection, " . mysqli_error($con));

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if($_POST['ovCashflowSelect']) {
			$postdata = $_POST['ovCashflowSelect'];
			$month_list = ["January", "February", "March", 
											"April", "May", "June", "July",
											"August", "September", "October",
											"November", "December"];
		}
	}
	// ================ //
	// === CASHFLOW === //
	// ================ //

	switch($postdata) {
		case "year":
			// --- OF THE YEAR --- //
			$str1 = "SELECT DISTINCT {$postdata} FROM record;";

			$query = mysqli_query($con, $str1)
				or die ("Error: Could not send query, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: Could not fetch data, " . mysqli_error($con));

			//print_r($result);

			for($i = 0; @$result[$i]; $i++) {
				$str2 = "SELECT DISTINCT
									{$postdata},
									(
						        SELECT SUM(amount)
						        FROM record
						        WHERE transaction_type = 'in' AND {$postdata} = {$result[$i][$postdata]}
							    ) AS TotalIncome,
							    (
						        SELECT SUM(amount)
						        FROM record
						        WHERE transaction_type = 'ex' AND {$postdata} = {$result[$i][$postdata]}
							    ) AS TotalExpense
								FROM record
								GROUP BY {$postdata};";

				$query = mysqli_query($con, $str2)
					or die ("Error: Could not send query, " . mysqli_error($con));
				$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
					or die ("Error: Could not fetch data, " . mysqli_error($con));

				$year = $result[$i][$postdata];
				$in = $result[$i]['TotalIncome'];
				$ex = $result[$i]['TotalExpense'];
				$in = ($in == "" ? 0 : $in);
				$ex = ($ex == "" ? 0 : $ex);

				echo "<tr>
								<td class='ov-cashflow-table-key table-key col-3'>{$year}</td>
								<td class='ov-cashflow-table-val table-val col-3 plus'>{$in}</td>
								<td class='ov-cashflow-table-val table-val col-3 minus'>{$ex}</td>
							</tr>";
			}

			mysqli_close($con);

			break;

		case "month":
			// --- OF THE MONTH --- //
			$str1 = "SELECT DISTINCT year, {$postdata} FROM record;";
			
			$query = mysqli_query($con, $str1)
				or die ("Error: Could not send query, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: Could not fetch data, " . mysqli_error($con));

			//print_r($result);

			for($i = 0; @$result[$i]; $i++) {
				$str2 = "SELECT DISTINCT
									year,
									{$postdata},
									(
						        SELECT SUM(amount)
						        FROM record
						        WHERE transaction_type = 'in'
						        AND year = {$result[$i]['year']}
						        AND {$postdata} = {$result[$i][$postdata]}
							    ) AS TotalIncome,
							    (
						        SELECT SUM(amount)
						        FROM record
						        WHERE transaction_type = 'ex'
						        AND year = {$result[$i]['year']}
						        AND {$postdata} = {$result[$i][$postdata]}
							    ) AS TotalExpense
								FROM record
								GROUP BY {$postdata};";

				$query = mysqli_query($con, $str2)
					or die ("Error: Could not send query, " . mysqli_error($con));
				$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
					or die ("Error: Could not fetch data, " . mysqli_error($con));

				//print_r($result);

				$year = $result[$i]['year'];
				$month = $month_list[$result[$i][$postdata] - 1];
				$in = $result[$i]['TotalIncome'];
				$ex = $result[$i]['TotalExpense'];
				$in = ($in == "" ? 0 : $in);
				$ex = ($ex == "" ? 0 : $ex);

				echo "<tr>
								<td class='ov-cashflow-table-key table-key col-4'>{$year}</td>
								<td class='ov-cashflow-table-key table-key col-4'>{$month}</td>
								<td class='ov-cashflow-table-val table-val col-4 plus'>{$in}</td>
								<td class='ov-cashflow-table-val table-val col-4 minus'>{$ex}</td>
							</tr>";
			}

			mysqli_close($con);
			
			break;

		case "week":
			// --- OF THE MONTH --- //
			$str1 = "SELECT DISTINCT year, month, {$postdata} FROM record;";
			
			$query = mysqli_query($con, $str1)
				or die ("Error: Could not send query, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: Could not fetch data, " . mysqli_error($con));

			//print_r($result);

			for($i = 0; @$result[$i]; $i++) {
				$str2 = "SELECT DISTINCT
									year,
									month,
									{$postdata},
									(
						        SELECT SUM(amount)
						        FROM record
						        WHERE transaction_type = 'in'
						        AND year = {$result[$i]['year']}
						        AND month = {$result[$i]['month']}
						        AND {$postdata} = {$result[$i][$postdata]}
							    ) AS TotalIncome,
							    (
						        SELECT SUM(amount)
						        FROM record
						        WHERE transaction_type = 'ex'
						        AND year = {$result[$i]['year']}
						        AND month = {$result[$i]['month']}
						        AND {$postdata} = {$result[$i][$postdata]}
							    ) AS TotalExpense
								FROM record;";

				$query = mysqli_query($con, $str2)
					or die ("Error: Could not send query, " . mysqli_error($con));
				$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
					or die ("Error: Could not fetch data, " . mysqli_error($con));

				//echo $str2 . "<br><br>";
				//print_r($result);

				$year = $result[$i]['year'];
				$month = $month_list[$result[$i]['month'] - 1];
				$week = $result[$i][$postdata];
				$in = $result[$i]['TotalIncome'];
				$ex = $result[$i]['TotalExpense'];
				$in = ($in == "" ? 0 : $in);
				$ex = ($ex == "" ? 0 : $ex);

				echo "<tr>
								<td class='ov-cashflow-table-key table-key col-5'>{$year}</td>
								<td class='ov-cashflow-table-key table-key col-5'>{$month}</td>
								<td class='ov-cashflow-table-key table-key col-5'>{$week}</td>
								<td class='ov-cashflow-table-val table-val col-5 plus'>{$in}</td>
								<td class='ov-cashflow-table-val table-val col-5 minus'>{$ex}</td>
							</tr>";
			}

			mysqli_close($con);
			
			break;

		case "day":
			// --- OF THE MONTH --- //
			$str1 = "SELECT DISTINCT year, month, {$postdata} FROM record;";
			
			$query = mysqli_query($con, $str1)
				or die ("Error: Could not send query, " . mysqli_error($con));
			$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
				or die ("Error: Could not fetch data, " . mysqli_error($con));

			//print_r($result);

			for($i = 0; @$result[$i]; $i++) {
				$str2 = "SELECT DISTINCT
									year,
									month,
									{$postdata},
									(
						        SELECT SUM(amount)
						        FROM record
						        WHERE transaction_type = 'in'
						        AND year = {$result[$i]['year']}
						        AND month = {$result[$i]['month']}
						        AND {$postdata} = {$result[$i][$postdata]}
							    ) AS TotalIncome,
							    (
						        SELECT SUM(amount)
						        FROM record
						        WHERE transaction_type = 'ex'
						        AND year = {$result[$i]['year']}
						        AND month = {$result[$i]['month']}
						        AND {$postdata} = {$result[$i][$postdata]}
							    ) AS TotalExpense
								FROM record;";

				$query = mysqli_query($con, $str2)
					or die ("Error: Could not send query, " . mysqli_error($con));
				$result = mysqli_fetch_all($query, MYSQLI_ASSOC)
					or die ("Error: Could not fetch data, " . mysqli_error($con));

				//echo $str2 . "<br><br>";
				//print_r($result);

				$year = $result[$i]['year'];
				$month = $month_list[$result[$i]['month'] - 1];
				$day = $result[$i][$postdata];
				$in = $result[$i]['TotalIncome'];
				$ex = $result[$i]['TotalExpense'];
				$in = ($in == "" ? 0 : $in);
				$ex = ($ex == "" ? 0 : $ex);

				echo "<tr>
								<td class='ov-cashflow-table-key table-key col-5'>{$year}</td>
								<td class='ov-cashflow-table-key table-key col-5'>{$month}</td>
								<td class='ov-cashflow-table-key table-key col-5'>{$day}</td>
								<td class='ov-cashflow-table-val table-val col-5 plus'>{$in}</td>
								<td class='ov-cashflow-table-val table-val col-5 minus'>{$ex}</td>
							</tr>";
			}

			mysqli_close($con);
			
	}

?>