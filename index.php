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


	?>
	<nav>
	  <span class="nav-tab">Overview</span>
	  <span class="nav-tab">Add new record</span>
	</nav>
	<form method="post" action="">
	  <div class="section" id="dateDiv">
			Date:
	    <input type="date" name="date" id="date">
	    <input type="hidden" name="year" id="year">
	    <input type="hidden" name="month" id="month">
	    <input type="hidden" name="day" id="day">
	    <input type="hidden" name="weekday" id="weekday">
	    <input type="hidden" name="week" id="week">
	  </div>
	  <div class="section" id="inputTypeDiv">
			Type of input:
	    <select name="inputType" id="inputType">
				<option class="type-option" value="ex">expense</option>
				<option class="type-option" value="in">income</option>
				<option class="type-option" value="tr">transfer</option>
			</select>
	  </div>
	  <div class="section in-only hide" id="incomeTypeDiv">
	    Type of income:
	    <select name="incomeType" id="incomeType" class="income-input-only">
	      <option class="income-option" value="">-select-</option>
	      <option class="income-option" value="act">active</option>
	      <option class="income-option" value="pas">passive</option>
	      <option class="income-option" value="rep">repay</option>
	    </select>
	  </div>
	  <div class="section" id="amountDiv">
			Amount of money:
	    <input type="number" name="amount" id="amount" placeholder="e.g. 1000, 1250, etc.">
	  </div>
	  <div class="section ex-only" id="necessityDiv">
	    Necessity:
	    <select name="necessity" id="necessity" class="expense-input-only">
	      <option class="necessity-option" value="">-select-</option>
	      <option class="necessity-option" value="1">necessary</option>
	      <option class="necessity-option" value="0">unnecessary</option>
	    </select>
	  </div>
	  <div class="section in-ex" id="categoriesDiv">
	    Categories:
	    <select name="exCategories" id="categories" class="in-ex-input main-input">
	      <option class="categories-option" value="">-select-</option>
	      <option class="categories-option" value="debt">debt</option>
	      <option class="categories-option" value="facilitation">facilitation</option>
	      <option class="categories-option" value="family">family</option>
	      <option class="categories-option" value="food">food</option>
	      <option class="categories-option" value="garment">garment</option>
	      <option class="categories-option" value="medical">medical</option>
	      <option class="categories-option" value="pet">pet</option>
	      <option class="categories-option" value="residence">residence</option>
	      <option class="categories-option" value="social">social</option>
	      <option class="categories-option" value="transport">transport</option>
	      <option class="categories-option" value="new">+ add new +</option>
	    </select>
	    Subcategories:
	    <select name="exSubcategories" id="subcategories" class="in-ex-input sub-input">
      	<option class="subcategories-option" value="">-select-</option>
	      <option class="subcategories-option" value="new">+ add new +</option>
	      <!-- Use php to show subcat depends on selected categories-->
	    </select>
	  </div>
	  <div class="section in-ex" id="accountDiv">
	    Account:
	    <select name="account" id="account" class="in-ex-input main-input">
	      <option class="account-option" value="">-select-</option>
	      <option class="account-option" value="cash">cash</option>
	      <option class="account-option" value="bank">bank</option>
	      <option class="account-option" value="new">+ add new +</option>
	    </select>
	    Subaccount:
	    <select name="subaccount" id="subaccount" class="in-ex-input sub-input">
	      <option class="subaccount-option" value="">-select-</option>
	      <option class="subaccount-option" value="wallet">wallet</option>
	      <option class="subaccount-option" value="piggy">piggy</option>
	      <option class="subaccount-option" value="kasikorn">kasikorn</option>
	      <option class="subaccount-option" value="new">+ add new +</option>
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
	    <select name="fromAccount" id="fromAccount" class="transfer-input-only">
	      <option class="from-account-option" value="">-select-</option>
	      <option class="from-account-option" value="cash">cash</option>
	      <option class="from-account-option" value="bank">bank</option>
	    </select>
	    Subaccount:
	    <select name="fromSubaccount" id="fromSubaccount" class="transfer-input-only">
	      <option class="from-subaccount-option" value="">-select-</option>
	      <option class="from-subaccount-option" value="wallet">wallet</option>
	      <option class="from-subaccount-option" value="piggy">piggy</option>
	      <option class="from-subaccount-option" value="kasikorn">kasikorn</option>
	      <option class="from-subaccount-option" value="new">+ add new +</option>
	      <!-- changes depend on account -->
	    </select>
	  </div>
	  <div class="section tr-only hide" id="toAccountDiv">
	    To account:
	    <select name="toAccount" id="toAccount" class="transfer-input-only">
	      <option class="to-account-option" value="">-select-</option>
	      <option class="to-account-option" value="cash">cash</option>
	      <option class="to-account-option" value="bank">bank</option>
	      <!-- depends on fromAccount -->
	    </select>
	    SubAccount:
	    <select name="toSubaccount" id="toSubaccount" class="transfer-input-only">
	      <option class="to-subaccount-option" value="">-select-</option>
	      <option class="to-subaccount-option" value="wallet">wallet</option>
	      <option class="to-subaccount-option" value="piggy">piggy</option>
	      <option class="to-subaccount-option" value="kasikorn">kasikorn</option>
	      <option class="from-subaccount-option" value="new">+ add new +</option>
	      <!-- changes depend on account-->
	    </select>
	  </div>
	  <div class="section in-only hide" id="payerDiv">
	    Payer:
	    <input type="text" name="payer" id="payer" class="income-input-only" placeholder="e.g. 'mom'." autocomplete="on">
	  </div>
	  <div class="section ex-only" id="payeeDiv">
	    Payee:
	    <input type="text" name="payee" id="payee" class="expense-input-only" placeholder="e.g. '7-Eleven'." autocomplete="on">
	  </div>
	  <div class="section" id="noteDiv">
	    Note:<br>
	    <textarea name="note" id="note" cols="30" rows="5"></textarea>
	  </div>
	  <button>submit</button>
		</form>
	  <hr>
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

  <script src="script.js"></script>
</body>
</html>