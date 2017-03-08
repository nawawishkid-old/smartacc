window.addEventListener("load", function() {
  // ============================================================================ //
  // ============================================================================ //
  // ============================================================================ //
  // ============================= OVERVIEW WRAPPER ============================= //
  // ============================================================================ //
  // ============================================================================ //
  // ============================================================================ //



  // =================================================================================== //
  // =================================================================================== //
  // =================================================================================== //
  // ============================= END OF OVERVIEW WRAPPER ============================= //
  // =================================================================================== //
  // =================================================================================== //
  // =================================================================================== //

  // ============================================================================== //
  // ============================================================================== //
  // ============================================================================== //
  // ============================= ADD RECORD WRAPPER ============================= //
  // ============================================================================== //
  // ============================================================================== //
  // ============================================================================== //

  // Set default income input value on input type expense.
  setInitValue();

  // Create realtime output table row
  createTR();

  // ---  ALL INPUT TYPING FUNCTION ---
  var input = document.querySelectorAll("input, select, textarea");
  
  for(var i = 0; input[i]; i++) {

    input[i].addEventListener("input", function() {
      enableOriginalSubmitBtn();
      addTDValue();
    });
  }

  // Adjust input box in accordance with type of input i.e. expense, income, and transfer.
  inputType.addEventListener("change", function() {
    console.log("=== inputType has changed ===");
    setInitValue();
  });

  // Set initial value of many input elements.
  function setInitValue() {
    console.log("=== setInitValue() ===");

    var inputType = document.getElementById("inputType"),
        inOnly = document.querySelectorAll(".in-only"),
        exOnly = document.querySelectorAll(".ex-only"),
        trOnly = document.querySelectorAll(".tr-only"),
        inEx = document.querySelectorAll(".in-ex"),
        inInput = document.querySelectorAll(".income-input-only"),
        exInput = document.querySelectorAll(".expense-input-only"),
        trInput = document.querySelectorAll(".transfer-input-only"),
        inExInput = document.querySelectorAll(".in-ex-input"),
        catInput = document.getElementById("categories"),
        accInput = document.getElementById("account"),
        faccInput = document.getElementById("fromAccount"),
        taccInput = document.getElementById("toAccount"),
        subCatInput = document.getElementById("subcategories"),
        newCatInput = document.getElementById("newCategory");

    // Create option elements of account , fromAccount and toAccount select elements.
    originalOptionElem(accInput, accOptArray, "js-account-option");
    
    // Type of input is income
    if(inputType.value === "in") {
      //console.log("inputType = " + inputType.value);

      SUB_showHide(inOnly, true);
      SUB_showHide(inEx, true);
      SUB_showHide(exOnly, false);
      SUB_showHide(trOnly, false);
      clearValue(exInput);
      clearValue(trInput);
      catInput.name = "inCategories";
      subCatInput.name = "inSubcategories";
      originalOptionElem(catInput, inOptArray, "in-categories-option");
      enableOriginalSubmitBtn();

    } else if(inputType.value == "ex") { // input is expense
      //console.log("inputType = " + inputType.value);

      SUB_showHide(exOnly, true);
      SUB_showHide(inEx, true);
      SUB_showHide(inOnly, false);
      SUB_showHide(trOnly, false);
      clearValue(inInput);
      clearValue(trInput);
      catInput.name = "exCategories";
      subCatInput.name = "exSubcategories";
      originalOptionElem(catInput, exOptArray, "ex-categories-option");
      enableOriginalSubmitBtn();

    } else { // input is transfer
      //console.log("inputType = " + inputType.value);

      SUB_showHide(trOnly, true);
      SUB_showHide(exOnly, false);
      SUB_showHide(inOnly, false);
      SUB_showHide(inEx, false);
      clearValue(inInput);
      clearValue(exInput);
      enableOriginalSubmitBtn();
      originalOptionElem(faccInput, accOptArray, "js-from-account-option");
      originalOptionElem(taccInput, accOptArray, "js-to-account-option");
    }

    // for showing or hiding hidden elements.
    function SUB_showHide(elemList, show) {
      console.log("=== SUB_showHide(elemList) ===");
      var i = 0;
      while(elemList[i]) {
        var elem = elemList[i],
            children = elem.children,
            j = 0;

        // show if show == true, hide if false.
        if(show) {
          elem.classList.remove("hide");
        } else {
          elem.classList.add("hide");
        }

        if(children) {
          //console.log(elem.tagName + "#" + elem.id + " has children, hide/show children as well.");
          while(children[j]) {
            if(show) {
              children[j].removeAttribute("disabled");
            } else {
              children[j].setAttribute("disabled", true);
            }
            j++;
          }
        } else {
          //console.log(elem.tagName + "#" + elem.id + " has no children.");
        }
        i++;
      }
    }
  }

  // === CREATE ORIGINAL OPTION ELEMENTS === //
  function originalOptionElem(selectElem, optArray, optClassName) {
    console.log("=== originalOptionElem(\"" + selectElem.tagName + "#" + selectElem.id + "\") ===");

    if(selectElem.tagName != 'SELECT') {
      return false;
    }

    if(typeof optArray != 'object') {
      return false;
    }

    if(optArray == "" || optArray == undefined) {
      return false;
    }

    if(typeof optClassName != 'string') {
      return false;
    }

    if(optClassName == "") {
      return false;
    }

    // Delete existed option elements.
    var sel = selectElem,
        keepOpt = sel.firstElementChild.className,
        allOpt = sel.querySelectorAll("option");

    for(var i = 0; allOpt[i]; i++) {
      if(allOpt[i].className != keepOpt) {
        sel.removeChild(allOpt[i]);
        //console.log("remove " + allOpt[i].innerHTML);
      }
    }

    // Create and insert new option elements.
    var oldOpt = sel.lastElementChild;

    for(var i = 0; optArray[i]; i++) {
      var x = optArray[i][0],
          newOpt = oldOpt.cloneNode();

      newOpt.value = x;
      newOpt.innerHTML = x;
      newOpt.className = optClassName;

      //console.log("create " + newOpt.innerHTML);

      sel.insertBefore(newOpt, oldOpt);
    }
  }

  // === CLEAR VALUE OF SPECIFIC INPUT LIST === //
  function clearValue(inputList) {
    console.log("=== clearValue(inputList) ===");
    for(var i = 0; inputList[i]; i++) {
      inputList[i].value = "";
    }
  }

  // ================ //
  // === SET DATE === //
  // ================ //
  var dateObj = new Date(),
      date = document.getElementById("date");

  date.value = dateObj.getFullYear() + "-" + addZero((dateObj.getMonth() + 1)) + "-" + addZero(dateObj.getDate());

  // set hidden date input value
  setHiddenDateValue();
      
  // set hidden date input value on user chaged its value.
  date.addEventListener("change", function() {
    setHiddenDateValue();
  });

  function setHiddenDateValue() {
    console.log("=== setHiddenDateValue() ===");
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
    console.log("=== addZero(date) ===");
    var d = Number(date);
    if(d < 10) {
      d = "0" + d;
      return d;
    } else {
      return d;
    }
  }
  // ======================= //
  // === END OF SET DATE === //
  // ======================= //




  //============================//
  // ======== MODAL BOX ========//
  //============================//
  var modBoxBg = document.querySelector(".modal-box-bg"),
      catInput = document.getElementById("categories"),
      subCatInput = document.getElementById("subcategories"),
      accInput = document.getElementById("account"),
      subAccInput = document.getElementById("subaccount"),
      faccInput = document.getElementById("fromAccount"),
      subFaccInput = document.getElementById("fromSubaccount"),
      taccInput = document.getElementById("toAccount"),
      subTaccInput = document.getElementById("toSubaccount"),
      allSelectElem = [
        catInput,
        subCatInput,
        accInput,
        subAccInput,
        faccInput,
        subFaccInput,
        taccInput,
        subTaccInput
      ];

  // when user click select input
  var i = 0;

  while(allSelectElem[i]) {
    allSelectElem[i].addEventListener("change", function(ev) {
      selectClicked(ev);
    });
    i++
  }

  // when user select "+ add new +" option
  function selectClicked(ev) {
    console.log("=== selectClicked() ===");
    var e = ev.target,
        catInput = document.getElementById("categories"),
        accInput = document.getElementById("account"),
        faccInput = document.getElementById("fromAccount"),
        taccInput = document.getElementById("toAccount"),
        mainSelectElem = [catInput, accInput, faccInput, taccInput];

    // Record present user-selected value.
    // Useful when user cancel and close modal box and want to see the previous value they have selected instead of "++ add new ++".
    if(e.value != "" && e.value != "new") {
      e.dataset.prevValue = e.value;
    }

    if(e.value === "new") {
      setModBoxContent(ev);
      enableModBoxSubmitBtn();
      modBoxBg.style.display = "flex";
    }

    // originalSubOptionElem(ev) run only when user select catInput or accInput
    for(var i = 0; mainSelectElem[i]; i++) {
      if(e == mainSelectElem[i]) {
        if(e.value != "") {
          originalSubOptionElem(ev);
        }
      }
    }
  }

  // Create new sub option elements in accordance with user-selected cat/acc value.
  function originalSubOptionElem(ev) {
    console.log("=== originalSubOptionElem(ev) ===");

    var e = ev.target,
        accInput = document.getElementById("account"),
        catInput = document.getElementById("categories"),
        faccInput = document.getElementById("fromAccount"),
        taccInput = document.getElementById("toAccount"),
        subAccInput = document.getElementById("subaccount"),
        subCatInput = document.getElementById("subcategories"),
        subFaccInput = document.getElementById("fromSubaccount"),
        subTaccInput = document.getElementById("toSubaccount"),
        subInput,
        optElemClass,
        optArray;

    console.log(e.name);

    // Filter which select elem user has selected e.g. inCat, exCat or account.
    // Choose data array in accordance with user-selected select input.
    switch(e.name) {
      case catInput.name:
        subInput = subCatInput;

        if(e.name.includes("in")) {
          optClass = "in-subcategories-option";
          optArray = inOptArray;
        } else {
          optClass = "ex-subcategories-option";
          optArray = exOptArray;
        }
        break;

      case accInput.name:
        subInput = subAccInput;
        optClass = "js-subaccount-option";
        optArray = accOptArray;
        break;

      case faccInput.name:
        subInput = subFaccInput;
        optClass = "js-from-subaccount-option";
        optArray = accOptArray;
        break;

      case taccInput.name:
        subInput = subTaccInput;
        optClass = "js-to-subaccount-option";
        optArray = accOptArray;
    }

    // Delete existed option elems.
    var allOpt = subInput.querySelectorAll("option"),
        keepOpt = subInput.firstElementChild.className,
        i = 0;

    for(var i = 0; allOpt[i]; i++) {
      if(allOpt[i].className != keepOpt) {
        subInput.removeChild(allOpt[i]);
      }
    }

    // Create new option elements.    
    var oldOpt = subInput.lastElementChild;

    if(optArray != "") {
      //console.log("optArray != \"\", create new opt!");

      for(var i = 0; optArray[i]; i++) {

        // If user-selected categories/account value equal to any value of stored data in the array.
        if(e.value == optArray[i][0]) {
          var j = 0;

          while(optArray[i][1][j]) { // Create new option elements in accordance with user-selected cat/acc value.
            var x = optArray[i][1][j];

            newOpt = oldOpt.cloneNode();
            newOpt.value = x;
            newOpt.innerHTML = x;
            newOpt.className = optClass;

            subInput.insertBefore(newOpt, oldOpt);
            j++;
          }
        }
      }
    } else {
      //console.log("Error: could not create, optArray is empty.");
    }
  }
  
  // Set modal box content in accordance with what user want to add new item of them e.g. new income category, expense category or account.
  function setModBoxContent(ev) {
    console.log("=== setModBoxContent(ev) ===");
    var e = ev.target,
        accInput = document.getElementById("account"),
        catInput = document.getElementById("categories"),
        subAccInput = document.getElementById("subaccount"),
        subCatInput = document.getElementById("subcategories"),
        inputType = document.getElementById("inputType"),
        newSubInput = document.getElementById("newSubInput"),
        p1str = "Category",
        p2str = "Subcategory";
    
    switch(e) {
      case catInput: // User click add new category input.

        SUB_createModBoxInput();

        if(inputType.value === "in") {
          SUB_setContent("income category", p1str, p2str, "IncomeCat", "IncomeSubCat", inputType.value);
        } else {
          SUB_setContent("expense category", p1str, p2str, "ExpenseCat", "ExpenseSubCat", inputType.value);
        }

        break;

      case subCatInput: // User click add new subcategory input.

        SUB_createModBoxInput(catInput);
        
        if(inputType.value === "in") {
          SUB_setContent("income subcategory", p1str, p2str, "IncomeCat", "IncomeSubCat", inputType.value);
        } else {
          SUB_setContent("expense subcategory", p1str, p2str, "ExpenseCat", "ExpenseSubCat", inputType.value);
        }
        
        newSubInput.autofocus = true;

        break;

      case accInput: // User click add new account input.

        SUB_createModBoxInput();
        SUB_setContent("account", "Account", "Subaccount", "Account", "Subaccount", "acc");
        break;

      case subAccInput: // User click add new subaccount input.

        SUB_createModBoxInput(accInput);
        SUB_setContent("subaccount", "Account", "Subaccount", "Account", "Subaccount", "acc");
        newSubInput.autofocus = true;

    }

    function SUB_setContent(h2in, p1in, p2in, inputName, subInputName, btnName) {
      console.log("=== SUB_setContent() ===");

      var catInput = document.getElementById("categories"),
          accInput = document.getElementById("account"),
          h2 = document.querySelector(".modal-box-head h2"),
          p = document.querySelectorAll(".modal-box-p"),
          p1 = p[0],
          p2 = p[1],
          newInput = document.getElementById("newInput"),
          newSubInput = document.getElementById("newSubInput"),
          submitBtn = document.getElementById("newFormSubmitBtn"),
          h2str = "Add new ",
          h2FirstChar = h2in.substring(0, 1),
          camel = h2in.replace(h2FirstChar, h2FirstChar.toUpperCase());

      h2.innerHTML = h2str + h2in;
      p1.innerHTML = p1in + " name";
      p2.innerHTML = p2in + " name";
      newInput.name = "new" + inputName;
      newSubInput.name = "new" + subInputName;
      submitBtn.name = btnName;

      /*
      console.log("Content detail");
      console.log("h2.innerHTML: " + h2.innerHTML);
      console.log("p1.innerHTML: " + p1.innerHTML);
      console.log("p2.innerHTML: " + p2.innerHTML);
      console.log("newInput.name: " + newInput.name);
      console.log("newSubInput.name: " + newSubInput.name);
      console.log("submitBtn.name: " + submitBtn.name);
      */
    }

    // create modal box input function
    function SUB_createModBoxInput(targetNode) {
      console.log("=== crateInput(targetNode) ===");
      var input,
          form = document.getElementById("newForm"),
          modBoxInput = document.querySelectorAll(".modal-box-input"),
          modBoxP = document.querySelectorAll(".modal-box-p");

      if(targetNode != undefined) { // if it's sub input.
        //console.log("User put param, clone node!!");
        input = targetNode.cloneNode(true);
        input.removeChild(input.lastElementChild);
        if(targetNode.value != "") {
          input.value = targetNode.value;
          input.querySelector("option[value=" + targetNode.value + "]").selected = true;
        }

      } else { // if it's not sub input (main input).
        //console.log("No param, create a new one!!");
        input = document.createElement("input");
        input.type = "text";
      }

      input.id = "newInput";
      input.className = "modal-box-input";

      if(modBoxInput.length > 1) {
        //console.log("Modal box currently has 2 inputs. Replace first one!");
        form.replaceChild(input, modBoxInput[0]);
      } else {
        //console.log("There's only one input on modal box. Insert a new one!");
        form.insertBefore(input, modBoxP[1]);
      }

    }
  }
  
  // =================================
  // === MODAL BOX SUBMIT FUNCTION ===
  // =================================
  function enableModBoxSubmitBtn() {
    console.log("=== enableModBoxSubmitBtn() ===");
    var submitBtn = document.getElementById("newFormSubmitBtn"),
        modBoxInput = document.querySelectorAll(".modal-box-input");

    if(modBoxInput[0].value == "" || modBoxInput[1].value == "") {
      submitBtn.disabled = true;
    }
    
    // enable submit button
    var i = 0;
    while(modBoxInput[i]) {
      /*
      console.log("=== set modBoxInput[i] oninput ===");
      console.log(i);
      console.log(modBoxInput[i]);
      */

      modBoxInput[i].addEventListener("input", function() {
        if(modBoxInput[0].value != "" && modBoxInput[1].value != "") {
          submitBtn.disabled = false;
        } else {
          submitBtn.disabled = true;
        }
      });
      i++;
    }
  }
  
  // =====================================
  // === MODAL BOX SUBMIT BUTTON CLICK ===
  // =====================================
  var submitBtn = document.getElementById("newFormSubmitBtn");

  // user click submit button.
  submitBtn.addEventListener("click", function() {
    console.log("=== submitBtn clicked ===");
    var newInput = document.getElementById("newInput"),
        newSubInput = document.getElementById("newSubInput"),
        subCatInput = document.getElementById("subcategories"),
        subAccInput = document.getElementById("subaccount");

    // Create new FormData
    var fd = new FormData();

    // Append data to FormData
    fd.append(newInput.name, newInput.value);
    fd.append(newSubInput.name, newSubInput.value);

    /*
    for(var key of fd.keys()) {
      console.log("Key in fd = " + key);
    }
    for(var val of fd.values()) {
      console.log("Value in fd = " + val);
    }
    */
    
    // Create AJAX
    var xhr = new xmlHttpRequest();
    xhr.open("POST", "addcatacc.php"); // Type PHP URL here!!
    xhr.send(fd);

    xhr.onload = function() {
      //console.log("newInput.value = " + newInput.value);
      //console.log("newSubInput.value = " + newSubInput.value);

      var status = document.querySelector(".modal-box-status"),
          submitBtn = document.getElementById("newFormSubmitBtn");
      
      status.innerHTML = xhr.responseText;

      var response = status.querySelector(".response");
      // if uploading is incomplete.
      if(response.dataset.complete === "false") {

        setTimeout(function() {
          status.innerHTML = "";
        }, 5000);

      } else {

        // Set original input value to newInput value.
        createNewOptionElem();

        // Close modal
        setTimeout(function() {
          //console.log("setTimeout");

          modBoxBg.style.display = "none";
          status.innerHTML = "";
        }, 1500);
      }

      // Clear modal box input value and form data value
      newInput.value = "";
      newSubInput.value = "";
      enableModBoxSubmitBtn();
      fd = new FormData();

      function createNewOptionElem() {
        console.log("=== createNewOptionElem() ===");

        var n = newInput.name,
            v1 = newInput.value,
            v2 = newSubInput.value;

        if(!n.includes("Acc") && !n.includes("acc")) { // if new created input is not account input.
          //console.log("It's not account input!");
          // Create new option element.
          SUB_createOpt(catInput);

        } else { // if it's account input.
          //console.log("It's account input!");
          // Create new option element.
          SUB_createOpt(accInput);
        }

        // create option element function
        function SUB_createOpt(targetNode) {
          console.log("=== crateOpt(targetNode) ===");
          var opt = targetNode.lastElementChild,
              subTargetNode = document.getElementById("sub" + targetNode.id),
              subOpt = subTargetNode.lastElementChild,
              newOpt = opt.cloneNode(),
              newSubOpt = subOpt.cloneNode();

          // newOpt
          newOpt.innerHTML = v1;
          newOpt.value = v1;
          targetNode.insertBefore(newOpt, opt);
          newOpt.selected = true;
          // newSubOpt
          newSubOpt.innerHTML = v2;
          newSubOpt.className = subTargetNode.id + "-option";
          newSubOpt.value = v2;
          subTargetNode.insertBefore(newSubOpt, subOpt);
          newSubOpt.selected = true;

          targetNode.value = v1;
          subTargetNode.value = v2;
        }
      }
    }

    xhr.onreadystatechange = function() {
      switch(xhr.readyState) {
        case 0:
          console.log("UNSENT");
          break;
        case 1:
          console.log("OPENED");
          break;
        case 3:
          console.log("LOADING");
          break;
        case 4:
          console.log("DONE");
      }
    }
  });
  
  // === MODAL BOX CLOSING ===
  var cancelBtn = document.getElementById("newInputCancel");
  
  // close by clicking cancel button
  cancelBtn.addEventListener("click", closeClearModal);
  // close by clicking modal box background
  document.addEventListener("click", function(ev) {
    if(ev.target == modBoxBg) {
      closeClearModal();
    }
  });
  
  // Close modal function
  function closeClearModal() {
    console.log("=== closeClearModal() ===");
    var modBoxBg = document.querySelector(".modal-box-bg"),
        newInput = document.getElementById("newInput"),
        newSubInput = document.getElementById("newSubInput");

    modBoxBg.style.display = "none";
    newInput.value = "";
    newSubInput.value = "";

    var mainInput = document.querySelectorAll(".main-input"),
        subInput = document.querySelectorAll(".sub-input"),
        status = document.querySelector(".modal-box-status"),
        i = 0;

    status.innerHTML = "";

    // Clear user-selected option element.
    while(mainInput[i]) {
      var m = mainInput[i],
          s = subInput[i],
          mPrevVal = m.dataset.prevValue,
          sPrevVal = s.dataset.prevValue;

      if(!mPrevVal) {
        m.value = "";
        m.querySelector("option[value=''").selected = true;
      } else {
        m.value = mPrevVal;
        m.querySelector("option[value=" + mPrevVal + "]").selected = true;
      }

      if(!sPrevVal) {
        s.value = "";
        s.querySelector("option[value=''").selected = true;
      } else {
        s.value = sPrevVal;
        s.querySelector("option[value=" + sPrevVal + "]").selected = true;
      }

      i++;
    }
  }
  //=======================================//
  // ========== END OF MODAL BOX ==========//
  //=======================================//



  //=================================================//
  // ========== SUBMIT BUTTON AVAILABILITY ==========//
  //=================================================//
  function enableOriginalSubmitBtn() {
    var input = document.querySelectorAll(".input:enabled"),
        submitBtn = document.getElementById("originalSubmitBtn"),
        count = 0;

    for(var j = 0; input[j]; j++) {
      if(input[j].value != "") {
        count++;
      }
    }

    if(count === input.length) {
      submitBtn.disabled = false;
    } else {
      submitBtn.disabled = true;
    }
  }
  

  //====================================================//
  // ========== CREATE REALTIME OUTPUT TABLE ========== //
  //====================================================//
  function createTR() {
    console.log("=== createTR() ===");

    var rtOutput = document.getElementById("realtimeOutputDivision"),
        tbody = document.getElementById("addRecTBody"),
        keyArray = [
          "date", "year", "month", "day", "weekday", "week",
          "input type", "income type", "amount", "necessity", 
          "categories", "subcategories", "account", "subaccount", 
          "from account", "from subaccount", "to account", "to subaccount", 
          "payer", "payee", "note"
        ];

    for(var i = 0; keyArray[i]; i++) {
      var tr = document.createElement("tr"),
          k = keyArray[i];

      tr.innerHTML = "<td class='table-key'>" + k + "</td>"
        + "<td class='table-val'></td>";

      tbody.appendChild(tr);
    }
  }

  function addTDValue() {
    console.log("=== addTDValue() ===");

    var input = document.querySelectorAll("input, select, textarea"),
        tdVal = document.querySelectorAll("td.table-val");

    for(var i = 0; tdVal[i]; i++) {
      tdVal[i].innerHTML = input[i].value;
    }
  }


  // ===================================================================================== //
  // ===================================================================================== //
  // ===================================================================================== //
  // ============================= END OF ADD RECORD WRAPPER ============================= //
  // ===================================================================================== //
  // ===================================================================================== //
  // ===================================================================================== //
});