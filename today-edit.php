<!DOCTYPE html>
<html>
<head>
	<?php include("frame1-head.html");?>
	<title>Edit Transaction | SMARTACC</title>
	<link rel="stylesheet" type="text/css" href="css/new-and-edit-style.css">
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

		// --- 1. SET HTML INPUT POST VALUE FROM INDEX PAGE ---
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = $_POST["id"];
		}

		$str = "SELECT * FROM record WHERE id = '{$id}';";
		$query = mysqli_query($con, $str) 
			or die ("Error: could not send query, " . mysqli_error($con));
		$result = mysqli_fetch_assoc($query)
			or die ("Error: could not fetch data, " . mysqli_error($con));

		if($result) {
			$date = $result['date'];
			$time = $result['time'];
			$year = $result['year'];
			$month = $result['month'];
			$day = $result['day'];
			$weekday = $result['weekday'];
			$week = $result['week'];
			$transaction_type = $result['transaction_type'];
			$necessity = $result['necessity'];
			$in_type = $result['in_type'];
			$cat = $result['categories'];
			$subcat = $result['subcategories'];
			$acc = $result['acc'];
			$subacc = $result['subacc'];
			$f_acc = $result['from_acc'];
			$f_subacc = $result['from_subacc'];
			$t_acc = $result['to_acc'];
			$t_subacc = $result['to_subacc'];
			$payer = $result['payer'];
			$payee = $result['payee'];
			$amount = $result['amount'];
			$note = $result['note'];
		}
	
		switch($transaction_type) {
			case "in":
				$column = "in_cats";
				$table = "in_categories";
				$sub_col = "in_subcats";
				$class = "cat";
				$sub_class = "subcat";
				break;
			case "ex":
				$column = "ex_cats";
				$table = "ex_categories";
				$sub_col = "ex_subcats";
				$class = "cat";
				$sub_class = "subcat";
		}

		function setSelectOption($con, $column, $table, $class_name, $extension = null) { // Set default value to $extension to avoid warning.
			$str = "SELECT DISTINCT $column FROM $table {$extension};";
			$query = mysqli_query($con, $str)
				or die ("Error: could not send data, " . mysqli_error($con));
			$result = mysqli_fetch_aLL($query, MYSQLI_NUM)
				or die ("Error: could not fetch data, " . mysqli_error($con));
			if($result) {
				for($i = 0; @$result[$i]; $i++) {
					$x = $result[$i][0];
					echo "<option class='{$class_name}-opt' value='{$x}'>{$x}</option>";
				}
			}
		}
		$encrypted_id = pow($id, 5);
		// --- Set current time for 'timePicker' [INPUT]
		date_default_timezone_set("Asia/Bangkok");
		$current_time = date_format(date_create(), "H:i");

		include("frame2-header.html");
	?>
	<main>
		<section class="edit-transact-sec">
			<input type="hidden" name="id" class="input" value="<?php echo $encrypted_id; ?>">
			<section class="in ex tr input-sec sec-transaction-type">
				<section class="list">Transaction type</section>
				<section class="value">
			    <select name="transactionType" id="transactionType" class="input in ex tr" data-transact="<?php echo $transaction_type;?>" required>
						<option class="transaction-type-opt" value="ex">expense</option>
						<option class="transaction-type-opt" value="in">income</option>
						<option class="transaction-type-opt" value="tr">transfer</option>
					</select>
				</section>
			</section>
			<section class="in ex tr input-sec sec-date">
				<section class="list">Date</section>
				<section class="value">
			    <input type="date" name="date" id="date" class="input in ex tr" value="<?php echo $date;?>" required>
			   </section>
			</section>
			<section class="in ex tr input-sec sec-time">
				<section class="list">Time</section>
				<section class="value">
					<div class="time-div">
			    	<input type="text" name="time" id="timePicker" class="input in ex tr" value="<?php echo $current_time;?>" 
			    	pattern="^\d{2}:\d{2}?" maxlength="5" title="Available value is only 'HH:MM'" required>
					  <div class="get-time-now-div">
					    <!--div id="getTimeNow" title="Now"></div-->
					  </div>
					</div>
			   </section>
			</section>
			<section class="in ex tr input-sec sec-amount">
				<section class="list">Amount</section>
				<section class="value">
			    <input type="number" name="amount" id="amount" class="input in ex tr" step="any" value="<?php echo $amount;?>" required>
				</section>
			</section>
			<section class="ex input-sec sec-necessity">
				<section class="list">Necessity</section>
				<section class="value">
			    <select name="necessity" id="necessity" class="input ex" data-transact="<?php echo $necessity;?>" required>
			      <option class="necessity-opt" value="">-- select --</option>
			      <option class="necessity-opt" value="1">necessary</option>
			      <option class="necessity-opt" value="0">unnecessary</option>
			    </select>
				</section>
			</section>
			<section class="in input-sec sec-income-type">
				<section class="list">Income type</section>
				<section class="value">
			    <select name="incomeType" id="incomeType" class="input in" data-transact="<?php echo $in_type;?>" required>
			      <option class="in-type-opt" value="">-- select --</option>
			      <option class="in-type-opt" value="act">active</option>
			      <option class="in-type-opt" value="pas">passive</option>
			    </select>
				</section>
			</section>
			<section class="in ex input-sec sec-cat">
				<section class="list">Category</section>
				<section class="value">
			    <select name="cat" id="cat" class="main-input input in ex for-addnew-modalbox" 
			    data-input-group="category" data-tablename="cat" data-transact="<?php echo $cat;?>" 
			    data-recent-value="<?php echo $cat;?>" required>
			      <option class="cat-opt" value="">-- select --</option>
			      <?php
			      	if($transaction_type != "tr") {
								setSelectOption($con, $column, $table, $class);
							}
						?>
			      <option class="cat-opt" value="new">++ add new ++</option>
			    </select>
			  </section>
			</section>
			<section class="in ex input-sec sec-subcat">
				<section class="list">Subcategory</section>
				<section class="value">
			    <select name="subcat" id="subcat" class="sub-input input in ex for-addnew-modalbox" 
			    data-input-group="category" data-tablename="cat" data-transact="<?php echo $subcat;?>" 
			    data-recent-value="<?php echo $subcat;?>" required>
		      	<option class="subcat-opt" value="">-- select --</option>
			      <?php
			      	if($transaction_type != "tr") {
								setSelectOption($con, $sub_col, $table, $sub_class, "WHERE {$column} = '{$cat}'");
			      	}
						?>
			      <option class="subcat-opt" value="new">++ add new ++</option>
			      <!-- Use php to show subcat depends on selected categories-->
			    </select>
			  </section>
			</section>
			<section class="in ex input-sec sec-acc">
				<section class="list">Account</section>
				<section class="value">
			    <select name="acc" id="acc" class="main-input input in ex for-addnew-modalbox" 
			    data-input-group="account" data-tablename="acc" data-transact="<?php echo $acc;?>" 
			    data-recent-value="<?php echo $acc;?>" required>
			      <option class="acc-opt" value="">-- select --</option>
			      <?php
			      	if($transaction_type != "tr") {
								setSelectOption($con, "account", "account", "acc");
							}
			      ?>
			      <option class="acc-opt" value="new">++ add new ++</option>
			    </select>
			  </section>
			</section>
			<section class="in ex input-sec sec-subacc">
				<section class="list">Subaccount</section>
				<section class="value">
			    <select name="subacc" id="subacc" class="sub-input input in ex for-addnew-modalbox" 
			    data-input-group="account" data-tablename="acc" data-transact="<?php echo $subacc;?>" 
			    data-recent-value="<?php echo $subacc;?>" required>
			      <option class="subacc-opt" value="">-- select --</option>
			      <?php
			      	if($transaction_type != "tr") {
								setSelectOption($con, "sub_account", "account", "subacc", "WHERE account = '{$acc}'");
							}
			      ?>
			      <option class="subacc-opt" value="new">++ add new ++</option>
			      <!-- Use php to show subacc depends on selected account-->
			    </select>
			  </section>
			</section>
			<section class="in input-sec sec-payer">
				<section class="list">Payer</section>
				<section class="value">
					<input type="text" name="payer" id="payer" class="input in" autocomplete="on" value="<?php echo $payer;?>" required>
			  </section>
			</section>
			<section class="ex input-sec sec-payee">
				<section class="list">Payee</section>
				<section class="value">
					<input type="text" name="payee" id="payee" class="input ex" autocomplete="on" value="<?php echo $payee;?>" required>
			  </section>
			</section>
			<section class="tr input-sec sec-f-acc">
				<section class="list">From account</section>
				<section class="value">
			    <select name="fAcc" id="fAcc" class="main-input input tr for-addnew-modalbox" 
			    data-input-group="from-account" data-tablename="acc" data-transact="<?php echo $f_acc;?>" 
			    data-recent-value="<?php echo $f_acc;?>" required>
			      <option class="f-acc-opt" value="">-- select --</option>
			      <?php
			      	if($transaction_type == "tr") {
								setSelectOption($con, "account", "account", "f-acc");
							}
			      ?>
			      <option class="f-acc-opt" value="new">++ add new ++</option>
			    </select>
			  </section>
			</section>
			<section class="tr input-sec sec-f-subacc">
				<section class="list">From subaccount</section>
				<section class="value">
			    <select name="fSubacc" id="fSubacc" class="sub-input input tr for-addnew-modalbox" 
			    data-input-group="from-account" data-tablename="acc" data-transact="<?php echo $f_subacc;?>" 
			    data-recent-value="<?php echo $f_subacc;?>" required>
			      <option class="f-subacc-opt" value="">-- select --</option>
			      <?php
			      	if($transaction_type == "tr") {
								setSelectOption($con, "sub_account", "account", "f-subacc", "WHERE account = '{$f_acc}'");
							}
			      ?>
			      <option class="f-subacc-opt" value="new">++ add new ++</option>
			    </select>
			  </section>
			</section>
			<section class="tr input-sec sec-t-acc">
				<section class="list">To account</section>
				<section class="value">
			    <select name="tAcc" id="tAcc" class="main-input input tr for-addnew-modalbox" 
			    data-input-group="to-account" data-tablename="acc" data-transact="<?php echo $t_acc;?>" 
			    data-recent-value="<?php echo $t_acc;?>" required>
			      <option class="t-acc-opt" value="">-- select --</option>
			      <?php
			      	if($transaction_type == "tr") {
								setSelectOption($con, "account", "account", "t-acc");
							}
			      ?>
			      <option class="t-acc-opt" value="new">++ add new ++</option>
			    </select>
			  </section>
			</section>
			<section class="tr input-sec sec-t-subacc">
				<section class="list">To subaccount</section>
				<section class="value">
			    <select name="tSubacc" id="tSubacc" class="sub-input input tr for-addnew-modalbox" 
			    data-input-group="to-account" data-tablename="acc" data-transact="<?php echo $t_subacc;?>" 
			    data-recent-value="<?php echo $t_subacc;?>" required>
			      <option class="t-subacc-opt" value="">-- select --</option>
			      <?php
			      	if($transaction_type == "tr") {
								setSelectOption($con, "sub_account", "account", "t-subacc", "WHERE account = '{$t_acc}'");
							}
			      ?>
			      <option class="t-subacc-opt" value="new">++ add new ++</option>
			    </select>
			  </section>
			</section>
			<section class="in ex tr input-sec sec-note">
				<section class="list">Note</section>
				<section class="value">
			    <textarea name="note" id="note" class="input in ex tr" cols="30" rows="5" required><?php echo $note;?></textarea>
			  </section>
			</section>
		</section>

		<!--div id="realtimeOutputDivision">
			<h4 style="color:red">Please check this table before you submit</h4>
			<table id='addRecTable'>
				<thead>
					<tr>
						<th>Key</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody id="addRecTBody">
				</tbody>
			</table>
		</div-->

		<div class="addnew-modalbox-background">
		  <div class="addnew-modalbox">
		    <header>
		      <h2></h2>
		    </header>
		    <article>
	      	<div class="addnew-input-div">
	      		<div class="addnew-input-main">
			      	<h3>MAIN</h3>
		          <!-- Create [INPUT] or [SELECT] element via JavaScript,
		          depends on what user have selected -->
	          </div>
	          <div class="addnew-input-sub">
		          <h3>SUB</h3>
		          <input type="text" name="" id="addnewSubInput" class="addnew-input">
	          </div>
	        </div>
	        <div class="addnew-button-div">
	          <button id="addnewSubmitBtn" disabled>Add</button>
	          <button id="addnewCancelBtn">Cancel</button>
          </div>
		    </article>
		  </div>
		</div>
	</main>
	<footer style="width: 100%; height: 10%;">
		<section class="submit-sec">
	  	<button id="originalSubmitBtn" form="formRecord" title="You'll be able to update only when you complete all inputs." disabled>Update</button>
	  </section>
	  <section class="cancel-sec">
	  	<button id="originalCancelBtn">Cancel</button>
	  </section>
	</footer>
	<?php mysqli_close($con); ?>
	<script src="js/new-and-edit-script.js"></script>
</body>
</html>