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
		$today_date = $_POST["todayDate"];
	}

	$str = "SELECT * FROM record WHERE date = '{$today_date}';";
	$query = mysqli_query($con, $str) 
		or die ("Error: could not send query, " . mysqli_error($con));
	$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
		//or die ("Error: could not fetch data, " . mysqli_error($con));

	//print_r($result);

	if($result) {
		$month_list = ["", "January", "February", "March", "April", "May", "June",
										"July", "August", "September", "October", "November", "December"];
		for($i = $total_income = $total_expense = 0; @$result[$i]; $i++) {
			if($result[$i]['transaction_type'] === "in") {
				$total_income += $result[$i]['amount'];
			} elseif ($result[$i]['transaction_type'] === "ex") {
				$total_expense += $result[$i]['amount'];
			}
		}
		$i = 0;
		while (@$result[$i]) {
			$note = "<span>Note:</span> {$result[$i]['note']}<br>";
			$number1 = number_format($result[$i]['amount']);
			$number2 = number_format($result[$i]['amount'], 2);
			$amount = "<span>Amount:</span> {$number2} THB<br>";
			$time = $result[$i]['time'] == "00:00:00" ? "--:--" : $result[$i]['time'];
			// --- GET DATE ---
			$date_data = $result[$i]['date'];
			// Year
			$year = substr($date_data, 0, 4);
			// Month
			$month_i = substr($date_data, 5, 2);
			$month_i = substr($month_i, 0, 1) == "0" ? substr($month_i, 1) : $month_i;
			$month = substr($month_list[$month_i], 0, 3);
			// Day
			$day = substr($date_data, 8, 2);
			$day = substr($day, 0, 1) == "0" ? substr($day, 1) : $day;
			// Weekday
			$weekday_list = ["", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
			$weekday = ucfirst($weekday_list[date("N", strtotime($date_data))]);
			// Week
			$week = ceil($day / 7);
			$date = "<span>Date:</span> {$month} {$day}, {$year} | {$weekday} | {$time} | week {$week}<br>";
			$acc = "<span>Account:</span> " . ucfirst($result[$i]['acc']) . " > " . ucfirst($result[$i]['subacc']) . "<br>";
			$cat = "<span>Category:</span> " . ucfirst($result[$i]['categories']) . " > " . ucfirst($result[$i]['subcategories']) . "<br>";
			$header = $note . $amount . $date;
			switch ($result[$i]["transaction_type"]) {
				case 'in':
					$type = "<span>Transaction type:</span> Income<br>";
					$in_type = ($result[$i]['in_type'] === "act" ? "Active" : "Passive");
					$in_type = "<span>Income type:</span> {$in_type}<br>";
					$payer = "<span>Payer:</span> " . ucfirst($result[$i]['payer']) . "<br>";
					$income_percent = "<span>Income percentage of the day:</span> " . number_format(($result[$i]['amount'] / $total_income) * 100, 2) . "%<br>";
					$detail = $header . $acc . $type . $in_type . $cat . $payer . $income_percent;
					$detail_header = $result[$i]['categories'] . ", " . $result[$i]['subcategories'];
					break;
				case 'ex':
					$type = "<span>Transaction type:</span> Expense<br>";
					$necessity = "<span>Necessity:</span> " . ($result[$i]['necessity'] == 1 ? "Necessary" : "Unnecessary") . "<br>";
					$payee = "<span>Payee:</span> " . ucfirst($result[$i]['payee']) . "<br>";
					$expense_percent = "<span>Expense percentage of the day:</span> " . number_format(($result[$i]['amount'] / $total_expense) * 100, 2) . "%<br>";
					$detail = $header . $acc . $type . $necessity . $cat . $payee . $expense_percent;
					$detail_header = $result[$i]['categories'] . ", " . $result[$i]['subcategories'];
					break;
				case 'tr':
					$type = "<span>Transaction type:</span> Transfer<br>";
					$from = "<span>From:</span> " . ucfirst($result[$i]['from_acc']) . " > " . ucfirst($result[$i]['from_subacc']) . "<br>";
					$to = "<span>To:</span> " . ucfirst($result[$i]['to_acc']) . " > " . ucfirst($result[$i]['to_subacc']) . "<br>";
					$detail = $header . $type . $from . $to;
					$detail_header = $result[$i]['from_subacc'] . ", " . $result[$i]['to_subacc'];
			}
			echo "<section class='today-flow-item' id='todayFlowItem{$i}'>
							<section class='today-flow-item-sec-left'>
								<header>
									<span><strong data-transact='{$result[$i]["transaction_type"]}'>{$result[$i]['transaction_type']}</strong> | {$result[$i]['date']} | {$detail_header}</span>
								</header>
								<article>
									<section class='today-flow-item-sec-left-article-sec-left'>
										<img class='category-icon' src=''/>
										<section>
											<span>{$result[$i]['note']}</span>
										</section>
									</section>
									<section class='today-flow-item-sec-left-article-sec-right' data-transact='{$result[$i]["transaction_type"]}'>{$number1}</section>
								</article>
								<label class='today-flow-item-label' for='todayFlowItem{$i}'>
								</label>
							</section>
							<section class='today-flow-item-sec-right'>
							</section>
							<section class='today-flow-item-hidden-menu'>
								<section class='hidden-menu-sec-1' data-transact-id='{$result[$i]["id"]}'>
								</section>
								<section class='hidden-menu-sec-2' data-transact-id='{$result[$i]["id"]}'>
								</section>
							</section>
					</section>
						<section class='today-flow-item-hidden-detail'>
							<p>{$detail}</p>
						</section>";
			$i++;
		}
	} else {
		echo "<section class='today-no-transaction'>
						No transaction on {$today_date}
					</section>";
	}

	mysqli_close($con);
?>