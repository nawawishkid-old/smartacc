<!DOCTYPE html>
<html>
<head>
	<title>Insert New Record</title>
	<style>
	table {
		border: 1px solid black;
	}
	th:first-of-type {
		border-right: 1px solid black;
	}
	tr {
		border-top: 1px solid black;
	}
	td {
		border-top: 1px solid black;
	}
	td.key {
		border-right: 1px solid black;
	}
	</style>
</head>
<body>
<?php
	$con = mysqli_connect("localhost", "root", "", "smartacc")
		or die ("Error: connection failed, " . mysqli_connect_error());
	mysqli_query($con, "SET character_set_results=utf8")
		or die ("Error: Cannot set character set result.");
	mysqli_query($con, "SET character_set_client=utf8")
		or die ("Error: Cannot set character set client.");
	mysqli_query($con, "SET character_set_connection=utf8")
		or die ("Error: Cannot set character set connection.");

	/*
	function validate($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
	}
	*/

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$date = @$_POST["date"];
		$year = @$_POST["year"];
		$month = @$_POST["month"];
		$day = @$_POST["day"];
		$weekday = @$_POST["weekday"];
		$week = @$_POST["week"];
		$input_type = @$_POST["inputType"];
		$income_type = $cat = $subcat = $payer = $acc = $sub_acc = NULL;
		$necessity = $ex_cat = $ex_subcat = $payee = NULL;
		$from_acc = $from_subacc = $to_acc = $to_subacc = NULL;
		$amount = @$_POST["amount"];
		$note = @$_POST["note"];

		/*
		echo "Date: {$date}<br>"
			. "Year: {$year}<br>"
			. "Month: {$month}<br>"
			. "Day: {$day}<br>"
			. "Weekday: {$weekday}<br>"
			. "Week: {$week}<br>";

		echo "Input type: {$input_type}<br>";
		*/

		switch($input_type) {
			case "in":
				$income_type = $_POST["incomeType"];
				$cat = $_POST["inCategories"];
				$subcat = $_POST["inSubcategories"];
				$payer = $_POST["payer"];
				$acc = $_POST["account"];
				$sub_acc = $_POST["subaccount"];

				/*
				echo "Income type: {$income_type}<br>"
					. "Income categories: {$in_cat}<br>"
					. "Income subcategories: {$in_subcat}<br>"
					. "Payer: {$payer}<br>"
					. "Account: {$acc}<br>"
					. "Subaccount: {$sub_acc}<br>";
				*/

				/*
				$new_array = array
					(
						"income type" => $income_type,
						"income cat" => $in_cat,
						"income subcat" => $in_subcat,
						"payer" => $payer,
						"account" => $acc,
						"subaccount" => $sub_acc
					);

				$data_array = array_merge($data_array, $new_array);
				*/
				break;

			case "ex":
				$necessity = $_POST["necessity"];
				$cat = $_POST["exCategories"];
				$subcat = $_POST["exSubcategories"];
				$payee = $_POST["payee"];
				$acc = $_POST["account"];
				$sub_acc = $_POST["subaccount"];

				/*
				echo "Necessity: {$necessity}<br>"
					. "Expense categories: {$ex_cat}<br>"
					. "Expense subcategories: {$ex_subcat}<br>"
					. "Payee: {$payee}<br>"
					. "Account: {$acc}<br>"
					. "Subaccount: {$sub_acc}<br>";
				*/

				/*
				$new_array = array
					(
						"necessity" => $necessity,
						"expense cat" => $ex_cat,
						"expense subcat" => $ex_subcat,
						"payee" => $payee,
						"account" => $acc,
						"subaccount" => $sub_acc
					);

				$data_array = array_merge($data_array, $new_array);
				*/
				break;

			case "tr":
				$from_acc = $_POST["fromAccount"];
				$from_subacc = $_POST["fromSubaccount"];
				$to_acc = $_POST["toAccount"];
				$to_subacc = $_POST["toSubaccount"];
				
				/*
				echo "From account: {$from_acc}<br>"
					. "From subaccount: {$from_subacc}<br>"
					. "To account: {$to_acc}<br>"
					. "To subaccount: {$to_subacc}<br>";
				*/

				/*
				$new_array = array
					(
						"from account" => $from_acc,
						"from subaccount" => $from_subacc,
						"to account" => $to_acc,
						"to subaccount" => $to_subacc
					);

				$data_array = array_merge($data_array, $new_array);
				*/
		}

		$data_array = array
			(
				"date" => $date,
				"year" => $year,
				"month" => $month,
				"day" => $day,
				"weekday" => $weekday,
				"week" => $week,
				"input type" => $input_type,
				"categories" => $cat,
				"subcategories" => $subcat,
				"from account" => $from_acc,
				"from subaccount" => $from_subacc,
				"account" => $acc,
				"subaccount" => $sub_acc,
				"to account" => $to_acc,
				"to subaccount" => $to_subacc,
				"payer" => $payer,
				"payee" => $payee,
				"amount" => $amount,
				"note" => $note
			);

		$str = "INSERT INTO record 
			(
				date, 
				year, 
				month, 
				day, 
				weekday, 
				week, 
				necessity, 
				transaction_type, 
				categories, 
				subcategories, 
				from_acc, 
				from_subacc, 
				acc, 
				subacc, 
				to_acc, 
				to_subacc, 
				payer, 
				payee, 
				amount, 
				note
			)
			VALUES 
			(
				'{$date}', 
				'{$year}', 
				'{$month}',
				'{$day}', 
				'{$weekday}', 
				'{$week}',
				'{$necessity}', 
				'{$input_type}',
				'{$cat}', 
				'{$subcat}',
				'{$from_acc}', 
				'{$from_subacc}',
				'{$acc}', 
				'{$sub_acc}', 
				'{$to_acc}',
				'{$to_subacc}', 
				'{$payer}', 
				'{$payee}',
				'{$amount}', 
				'{$note}'
			);";

			$query = mysqli_query($con, $str)
				or die ("Error: could not insert new record " . mysqli_error($con) . "<br>");

			if($query) { $complete = "Insert complete!";}

			mysqli_close($con);
	}
?>
<h1><?php echo @$complete; ?></h1>
<h1 id="status">I take you back in 3</h1>
<p>or click <a href="index.php">here</a></p>
<div id="output">
	<table>
		<tr>
			<th>KEY</th>
			<th>VALUE</th>
		</tr>
		<?php
			foreach ($data_array as $key => $v) {
				if($v == "") {
					continue;
				} else {
					echo "<tr><td class='key'>{$key}</td><td class='value'>{$v}</td></tr>";
				}
			}
		?>
	</table>
</div>
<script>
	window.onload = function() {
		var status = document.getElementById("status"),
				n = 3,
				interval = setInterval(function() {x();}, 1000);

		function x() {
			console.log("=== x() ===");
			console.log(n);
			console.log(status.innerHTML);
			n--;
			status.innerHTML = "I take you back in " + n;

			if(n === 0) {
				clearInterval(interval);
			}
		}

		setTimeout(function() {
			window.location = 'index.php';
		}, 3000);
	}
</script>
</body>
</html>