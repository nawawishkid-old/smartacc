window.addEventListener("load", function() {
  var inputType = document.getElementById("inputType"),
      incomeTypeDiv = document.getElementById("incomeTypeDiv"),
      necessityDiv = document.getElementById("necessityDiv"),
      inOnly = document.querySelectorAll(".in-only"),
      exOnly = document.querySelectorAll(".ex-only"),
      trOnly = document.querySelectorAll(".tr-only"),
      inEx = document.querySelectorAll(".in-ex"),
      inInput = document.querySelectorAll(".income-input-only"),
      exInput = document.querySelectorAll(".expense-input-only"),
      trInput = document.querySelectorAll(".transfer-input-only"),
      inExInput = document.querySelectorAll(".in-ex-input"),
      catInput = document.getElementById("categories"),
      subCatInput = document.getElementById("subCategories"),
      newCatInput = document.getElementById("newCategory");

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
      catInput.name = "inCategories";
      subCatInput.name = "inSubcategories";
    } else if(inputType.value == "ex") { // input is expense
      show(exOnly);
      hide(inOnly);
      hide(trOnly);
      inInput.value = "";
      trInput.value = "";
      catInput.name = "exCategories";
      subCatInput.name = "exSubcategories";
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
      
  // set hidden date input value on user chaged its value.
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

  //=========================
  // ===== MODAL BOX =====
  //=========================
  var modBoxBg = document.querySelector(".modal-box-bg"),
      catInput = document.getElementById("categories"),
      subCatInput = document.getElementById("subcategories"),
      accInput = document.getElementById("account"),
      subAccInput = document.getElementById("subaccount");

  // show NEW CATEGORY modal box when user select "+ add new +"
  catInput.addEventListener("change", function(ev) {
    selectClicked(ev);
  });

  subCatInput.addEventListener("change", function(ev) {
    selectClicked(ev);
  });

  // show NEW ACCOUNT modal box when user select "+ add new +"
  accInput.addEventListener("change", function(ev) {
    selectClicked(ev);
  });

  subAccInput.addEventListener("change", function(ev) {
    selectClicked(ev);
  });

  function selectClicked(ev) {
    console.log("=== selectClicked() ===");
    var e = ev.target;

    if(e.value === "new") {
      setModBoxContent(ev);
      modBoxBg.style.display = "flex";
    }
  }
  
  // Set modal box content in accordance with what user want to add new item of them e.g. new income category, expense category or account.
  function setModBoxContent(ev) {
    var e = ev.target,
        inputType = document.getElementById("inputType"),
        newSubInput = document.getElementById("newSubInput"),
        p1str = "Category",
        p2str = "Subcategory";
    
    switch(e) {
      case catInput: // User click add new category input.

        createInput();

        if(inputType.value === "in") {
          setContent("income category", p1str, p2str, "IncomeCat", "IncomeSubCat", inputType.value);
        } else {
          setContent("expense category", p1str, p2str, "ExpenseCat", "ExpenseSubCat", inputType.value);
        }

        break;

      case subCatInput: // User click add new subcategory input.

        createInput(catInput);
        
        if(inputType.value === "in") {
          setContent("income subcategory", p1str, p2str, "IncomeCat", "IncomeSubCat", inputType.value);
        } else {
          setContent("expense subcategory", p1str, p2str, "ExpenseCat", "ExpenseSubCat", inputType.value);
        }
        
        newSubInput.autofocus = true;

        break;

      case accInput: // User click add new account input.

        createInput();
        setContent("account", "Account", "Subaccount", "Account", "Subaccount", "acc");
        break;

      case subAccInput: // User click add new subaccount input.

        createInput(accInput);
        setContent("subaccount", "Account", "Subaccount", "Account", "Subaccount", "acc");
        newSubInput.autofocus = true;

    }

    function setContent(h2in, p1in, p2in, inputName, subInputName, btnName) {
      console.log("=== setContent() ===");

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

      console.log("Content detail");
      console.log("h2.innerHTML: " + h2.innerHTML);
      console.log("p1.innerHTML: " + p1.innerHTML);
      console.log("p2.innerHTML: " + p2.innerHTML);
      console.log("newInput.name: " + newInput.name);
      console.log("newSubInput.name: " + newSubInput.name);
      console.log("submitBtn.name: " + submitBtn.name);
    }

    // create modal box input function
    function createInput(targetNode) {
      var input,
          form = document.getElementById("newForm"),
          modBoxInput = document.querySelectorAll(".modal-box-input"),
          modBoxP = document.querySelectorAll(".modal-box-p");

      console.log("targetNode param. = " + targetNode);

      if(targetNode != undefined) {
        console.log("User put param, clone node!!");
        input = targetNode.cloneNode(true);
        input.removeChild(input.lastElementChild);
        if(targetNode.value != "") {
          input.value = targetNode.value;
          input.querySelector("option[value=" + targetNode.value + "]").selected = true;
        }

      } else {
        console.log("No param, create a new one!!");
        input = document.createElement("input");
        input.type = "text";
      }

      input.id = "newInput";
      input.className = "modal-box-input";

      if(modBoxInput.length > 1) {
        console.log("Modal box currently has 2 inputs. Replace first one!");
        form.replaceChild(input, modBoxInput[0]);
      } else {
        console.log("There's only one input on modal box. Insert a new one!");
        form.insertBefore(input, modBoxP[1]);
      }

    }
  }
  
  // === Submit ===
  var submitBtn = document.getElementById("newFormSubmitBtn"),
      modBoxInput = document.querySelectorAll(".modal-box-input");
  
  // enable submit button
  var i = 0;
  while(modBoxInput[i]) {
    modBoxInput[i].addEventListener("input", enableSubmit);
    i++;
  }
  // enable submit button function
  function enableSubmit() {
    console.log("=== ENABLE() ===");
    var submitBtn = document.getElementById("newFormSubmitBtn"),
        modBoxInput = document.querySelectorAll(".modal-box-input");

    if(modBoxInput[0].value !== "" && modBoxInput[1].value !== "") {
      submitBtn.disabled = false;
    } else {
      submitBtn.disabled = true;
    }
  }
  
  // user click submit button.
  submitBtn.addEventListener("click", function() {
    var newInput = document.getElementById("newInput"),
        newSubInput = document.getElementById("newSubInput"),
        subCatInput = document.getElementById("subcategories"),
        subAccInput = document.getElementById("subaccount");

    // Create new FormData
    var fd = new FormData();

    // Append data to FormData
    fd.append(newInput.name, newInput.value);
    fd.append(newSubInput.name, newSubInput.value);
    for(var key of fd.keys()) {
      console.log("Key in fd = " + key);
    }
    for(var val of fd.values()) {
      console.log("Value in fd = " + val);
    }
    
    // Create AJAX
    var xml = new XMLHttpRequest();
    xml.open("POST", "addcatacc.php"); // Type PHP URL here!!
    xml.send(fd);

    xml.onload = function() {
      console.log("newInput.value = " + newInput.value);
      console.log("newSubInput.value = " + newSubInput.value);

      var status = document.querySelector(".modal-box-status"),
          submitBtn = document.getElementById("newFormSubmitBtn");
      
      status.innerHTML = xml.responseText;

      var response = status.querySelector(".response");
      // if uploading is incomplete.
      if(response.dataset.complete === "false") {

        setTimeout(function() {
          status.innerHTML = "";
        }, 3000);

      } else {

        // Set original input value to newInput value.
        createNewOptionElem();

        // Close modal
        setTimeout(function() {
          console.log("setTimeout");

          modBoxBg.style.display = "none";
          status.innerHTML = "";
        }, 1500);
      }

      // Clear modal box input value and form data value
      newInput.value = "";
      newSubInput.value = "";
      enableSubmit();
      fd = new FormData();

      function createNewOptionElem() {
        console.log("=== createNewOptionElem() ===");

        var n = newInput.name,
            v1 = newInput.value,
            v2 = newSubInput.value;

        if(!n.includes("Acc")) { // if new created input is not account input.
          console.log("It's not account input!");
          // Create new option element.
          var opt = catInput.lastElementChild,
              subOpt = subCatInput.lastElementChild,
              newOpt = opt.cloneNode(),
              newSubOpt = subOpt.cloneNode();
          
          console.log(opt);
          // newOpt
          newOpt.innerHTML = v1;
          newOpt.value = v1;
          catInput.insertBefore(newOpt, opt);
          newOpt.selected = true;
          console.log("newOpt created!");
          console.log(newOpt);
          // newSubOpt
          newSubOpt.innerHTML = v2;
          newSubOpt.className = "subcategories-option";
          newSubOpt.value = v2;
          subCatInput.insertBefore(newSubOpt, subOpt);
          newSubOpt.selected = true;
          console.log("newSubOpt created!");
          console.log(newSubOpt);
          
          catInput.value = v1;
          subCatInput.value = v2;
        } else { // if it's account input.
          console.log("It's account input!");
          // Create new option element.
          var opt = accInput.lastElementChild,
              subOpt = subAccInput.lastElementChild,
              newOpt = opt.cloneNode(),
              newSubOpt = subOpt.cloneNode();
          
          // newOpt
          console.log(opt);
          newOpt.innerHTML = v1;
          newOpt.value = v1;
          accInput.insertBefore(newOpt, opt);
          newOpt.selected = true;
          console.log("newOpt created!");
          console.log(newOpt);
          
          // newSubOpt
          newSubOpt.innerHTML = v2;
          newSubOpt.className = "subaccount-option";
          newSubOpt.value = v2;
          subAccInput.insertBefore(newSubOpt, subOpt);
          newSubOpt.selected = true;
          console.log("newSubOpt created!");
          console.log(newSubOpt);
          
          accInput.value = v1; 
          subAccInput.value = v2;
        }
      }
    }

    xml.onreadystatechange = function() {
      switch(xml.readyState) {
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
  
  // close modal box
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

    while(mainInput[i]) {
      var m = mainInput[i],
          s = subInput[i];

      m.value = "";
      m.querySelector("option[value=''").selected = true;
      s.value = "";
      s.querySelector("option[value=''").selected = true;

      i++;
    }
  }
});