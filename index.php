<!DOCTYPE html>
<html>
<head>
	<title>Smart Acc</title>
</head>
<style>
.hide {
  display: none;
}
</style>
<body>
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
    <select name="categories" id="categories" class="in-ex-input">
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
    <select name="subcategories" id="subcategoeries" class="in-ex-input">
      <!-- Use php to show subcat depends on selected categories-->
    </select>
  </div>
  <div class="section in-ex" id="accountDiv">
    Account:
    <select name="account" id="account" class="in-ex-input">
      <option class="account-option" value="">-select-</option>
      <option class="account-option" value="cash">cash</option>
      <option class="account-option" value="bank">bank</option>
    </select>
    Subaccount:
    <select name="subaccount" id="subaccount" class="in-ex-input">
      <option class="subaccount-option" value="">-select-</option>
      <option class="subaccount-option" value="wallet">wallet</option>
      <option class="subaccount-option" value="piggy">piggy</option>
      <option class="subaccount-option" value="kasikorn">kasikorn</option>
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

  <script type="text/javascript">
  	var inputType = document.getElementById("inputType"),
		    incomeTypeDiv = document.getElementById("incomeTypeDiv"),
		    necessityDiv = document.getElementById("necessityDiv"),
		    inOnly = document.querySelectorAll(".in-only"),
		    exOnly = document.querySelectorAll(".ex-only"),
		    trOnly = document.querySelectorAll(".tr-only"),
		    inEx = document.querySelectorAll(".in-ex"),
		    inInput = document.querySelectorAll(".income-input-only"),
		    exInput = document.querySelectorAll(".expense-input-only"),
		    trInput = document.querySelectorAll(".transfer-input-only")
		    inExInput = document.querySelectorAll(".in-ex-input");

		// Set default income input value on input type expense.
		hide(inOnly);
		hide(trOnly);

		// Adjust input box in accordance with type of input i.e. expense, income, and transfer.
		inputType.addEventListener("change", function() {
		  // Type of input is income
		  if(inputType.value === "in") {
		    show(inOnly);
		    hide(exOnly);
		    hide(trOnly);
		    exInput.value = "";
		    trInput.value = "";
		  } else if(inputType.value == "ex") { // input is expense
		    show(exOnly);
		    hide(inOnly);
		    hide(trOnly);
		    inInput.value = "";
		    trInput.value = "";
		  } else { // input is transfer
		    show(trOnly);
		    hide(exOnly);
		    hide(inOnly);
		    hide(inEx);
		    inInput.value = "";
		    exInput.value = "";
		  }
		});

		// for showing hidden elements.
		function show(elemList) {
		  var i = 0;
		  while(elemList[i]) {
		    elemList[i].classList.remove("hide");
		    var j = 0,
		        children = elemList[i].children;
		    while(children[j]) {
		      children[j].removeAttribute("disabled");
		      j++;
		    }
		    i++;
		  }
		}
		// for hiding shown elements.
		function hide(elemList) {
		  var i = 0;
		  while(elemList[i]) {
		    elemList[i].classList.add("hide");
		    var j = 0,
		        children = elemList[i].children;
		    while(children[j]) {
		      children[j].setAttribute("disabled", "true");
		      j++;
		    }
		    i++;
		  }
		}

		// --- SET DATE ---
		var dateObj = new Date(),
		    date = document.getElementById("date");

		date.value = dateObj.getFullYear() + "-" + addZero((dateObj.getMonth() + 1)) + "-" + addZero(dateObj.getDate());

		// set hidden date input value
		setHiddenDateValue();

		date.addEventListener("change", function() {
		  setHiddenDateValue();
		});

		function setHiddenDateValue() {
		  var dateVal = date.value,
		      split = dateVal.split("-"),
		      yearVal = split[0],
		      monthVal = split[1],
		      dayVal = split[2];
		  var dateObj = new Date(yearVal, (monthVal-1), dayVal),
		      dateStr = dateObj.toDateString(),
		      weekdayVal = dateStr.split(" ")[0].toLowerCase();
		  var weekVal = Math.ceil(dayVal / 7);
		  var yearInput = document.getElementById("year"),
		      monthInput = document.getElementById("month"),
		      dayInput = document.getElementById("day"),
		      weekdayInput = document.getElementById("weekday"),
		      weekInput = document.getElementById("week");
		  
		  yearInput.value = yearVal;
		  monthInput.value = monthVal;
		  dayInput.value = dayVal;
		  weekdayInput.value = weekdayVal;
		  weekInput.value = weekVal;
		}

		// add '0' to the value which less than 10.
		function addZero(date) {
		  var d = Number(date);
		  if(d < 10) {
		    d = "0" + d;
		    return d;
		  } else {
		    return d;
		  }
		}
  </script>
</body>
</html>