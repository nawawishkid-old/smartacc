<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Smart Acc</title>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
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

		fetch_data($con, "in");
		fetch_data($con, "ex");
		fetch_data($con, "acc");

		// Fetch categories or account option from database
		function fetch_data($con, $input_type) {

			echo "<script>\nvar {$input_type}OptArray = [];\n";

  		switch($input_type) {
  			case "in":
  				$str = "SELECT in_cats, in_subcats FROM in_categories;";
  				//$str2 = ""
  				break;
  			case "ex":
  				$str = "SELECT ex_cats, ex_subcats FROM ex_categories;";
  				break;
  			case "acc":
  				$str = "SELECT account, sub_account FROM account;";
  		}

  		$query = mysqli_query($con, $str);
  		$result = mysqli_fetch_all($query, MYSQLI_NUM);

  		//print_r($result);

  		$i = 0;

			while(@$result[$i]) {
				$x = $result[$i][0];

				// Push this data immediately without creating new array, if this subcat name is the same as previous subcat name
				if($i > 0 && $x == $result[$i - 1][0]) {
					echo "sub{$x}.push('{$result[$i][1]}');\n";
				} else {
					// Create subcat array with data in it and push this subcat array into created main array.
					// sub{$x} array always have 2 index.
					// First is used to store the name of subcat in index 0,
					// second always contain its data in array object in index 1.
					echo "sub{$x} = [];\nsub{$x}.push('{$result[$i][1]}');\nvar {$x} = ['{$x}', sub{$x}];\n{$input_type}OptArray.push({$x});\n";
				}
				
				$i++;
			}

			echo "console.log({$input_type}OptArray);\n</script>";
		}

		mysqli_close($con);
	?>

	<nav>
	  <span id="overviewNavTab" class="nav-tab">Overview</span>
	  <span id="addRecordNavTab" class="nav-tab">Add new record</span>
	</nav>
	<div id="overviewWrapper" class="wrapper">
	</div>
	<div id="addRecordWrapper" class="wrapper hide">
		<div id='formDivision'>
			<form id="formRecord" name="formRecord" method="post" action="add-record.php">
			  <div class="section" id="dateDiv">
					Date:
			    <input type="date" name="date" id="date" class="input" required>
			    <input type="hidden" name="year" id="year">
			    <input type="hidden" name="month" id="month">
			    <input type="hidden" name="day" id="day">
			    <input type="hidden" name="weekday" id="weekday">
			    <input type="hidden" name="week" id="week">
			  </div>
			  <div class="section" id="inputTypeDiv">
					Type of input:
			    <select name="inputType" id="inputType" class="input" required>
						<option class="type-option" value="ex">expense</option>
						<option class="type-option" value="in">income</option>
						<option class="type-option" value="tr">transfer</option>
					</select>
			  </div>
			  <div class="section in-only hide" id="incomeTypeDiv">
			    Type of income:
			    <select name="incomeType" id="incomeType" class="income-input-only input" required>
			      <option class="income-option" value="">-- select --</option>
			      <option class="income-option" value="act">active</option>
			      <option class="income-option" value="pas">passive</option>
			      <option class="income-option" value="rep">repay</option>
			    </select>
			  </div>
			  <div class="section" id="amountDiv">
					Amount of money:
			    <input type="number" name="amount" id="amount" class="input" required>
			  </div>
			  <div class="section ex-only" id="necessityDiv">
			    Necessity:
			    <select name="necessity" id="necessity" class="expense-input-only input" required>
			      <option class="necessity-option" value="">-- select --</option>
			      <option class="necessity-option" value="1">necessary</option>
			      <option class="necessity-option" value="0">unnecessary</option>
			    </select>
			  </div>
			  <div class="section in-ex" id="categoriesDiv">
			    Categories:
			    <select name="exCategories" id="categories" class="in-ex-input main-input input" required>
			      <option class="categories-option" value="">-- select --</option>
			      <option class="categories-option" value="new">++ add new ++</option>
			    </select>
			    Subcategories:
			    <select name="exSubcategories" id="subcategories" class="in-ex-input sub-input input" required>
		      	<option class="subcategories-option" value="">-- select --</option>
			      <option class="subcategories-option" value="new">++ add new ++</option>
			      <!-- Use php to show subcat depends on selected categories-->
			    </select>
			  </div>
			  <div class="section in-ex" id="accountDiv">
			    Account:
			    <select name="account" id="account" class="in-ex-input main-input input" required>
			      <option class="account-option" value="">-- select --</option>
			      <option class="account-option" value="new">++ add new ++</option>
			    </select>
			    Subaccount:
			    <select name="subaccount" id="subaccount" class="in-ex-input sub-input input" required>
			      <option class="subaccount-option" value="">-- select --</option>
			      <option class="subaccount-option" value="new">++ add new ++</option>
			      <!-- Use php to show subacc depends on selected account-->
			    </select>
			  </div>
			  <!-- 
			##########
			INPUT TYPE TRANSFER ONLY
			##########
			-->
			  <div class="section tr-only hide" id="fromAccountDiv">
			    From account:
			    <select name="fromAccount" id="fromAccount" class="transfer-input-only input" required>
			      <option class="from-account-option" value="">-- select --</option>
			      <option class="from-account-option" value="new">++ add new ++</option>
			    </select>
			    Subaccount:
			    <select name="fromSubaccount" id="fromSubaccount" class="transfer-input-only input" required>
			      <option class="from-subaccount-option" value="">-- select --</option>
			      <option class="from-subaccount-option" value="new">++ add new ++</option>
			      <!-- changes depend on account -->
			    </select>
			  </div>
			  <div class="section tr-only hide" id="toAccountDiv">
			    To account:
			    <select name="toAccount" id="toAccount" class="transfer-input-only input" required>
			      <option class="to-account-option" value="">-- select --</option>
			      <option class="to-account-option" value="new">++ add new ++</option>
			      <!-- depends on fromAccount -->
			    </select>
			    SubAccount:
			    <select name="toSubaccount" id="toSubaccount" class="transfer-input-only input" required>
			      <option class="to-subaccount-option" value="">-- select --</option>
			      <option class="to-subaccount-option" value="new">++ add new ++</option>
			      <!-- changes depend on account-->
			    </select>
			  </div>
			  <div class="section in-only hide" id="payerDiv">
			    Payer:
			    <input type="text" name="payer" id="payer" class="income-input-only input" autocomplete="on" required>
			  </div>
			  <div class="section ex-only" id="payeeDiv">
			    Payee:
			    <input type="text" name="payee" id="payee" class="expense-input-only input" autocomplete="on" required>
			  </div>
			  <div class="section" id="noteDiv">
			    Note:<br>
			    <textarea name="note" id="note" class="input" cols="30" rows="5" required></textarea>
			  </div>
			</form>
		</div>

		<div id="realtimeOutputDivision">
			<h4 style="color:red">Please check this table before you submit</h4>
			<table id='table'>
				<thead>
					<tr>
						<th>Key</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
		<div id="submitBtnDivision">
			<p style="color:red">You'll be able to submit only when you complete all inputs.</p>
	  	<button id="originalSubmitBtn" form="formRecord" disabled>submit</button>
	  </div>

		<div class="modal-box-bg">
		  <div class="modal-box">
		    <div class="modal-box-head">
		      <h2></h2>
		    </div>
		    <div class="modal-box-body">
		      <div class="modal-box-content">
		        <form action="" method="post" id="newForm">
		          <p class="modal-box-p"></p>
		          <p class="modal-box-p"></p>
		          <input type="text" name="" id="newSubInput" class="modal-box-input"><br>
		          <button type="button" id="newFormSubmitBtn" name="" disabled>Submit</button>
		          <button type="button" class="cancel-button" id="newInputCancel">Cancel</button>
		        </form>
		        <div class="modal-box-status">
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
	</div>

  <script src="script.js"></script>
</body>
</html>