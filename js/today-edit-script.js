window.addEventListener("load", function() {
  // --- 1. DISPLAYING ---
  // --- 1.1 DISPLAY ONLY RELATED ELEMENTS ---
  displayRelatedElem();

  // --- 1.2 DISPLAY RELATED ELEMENTS WHEN TRANSACTION TYPE IS CHANGED ---
  var transactionType = document.getElementById("transactionType");
  transactionType.addEventListener("change", function() {
    console.log("--- transactionType.onchange ---");
    //this.dataset.transact = this.value;
    this.dataset.recentValue = this.value;
    // 1.2.1 Display related elements
    displayRelatedElem();
    // 1.2.2 Fetch cat/acc and subcat/subacc depends on transaction type
    // If transaction type is transfer,
    // fetch fromacc/toacc and fromsubacc/tosubacc.
    if(this.value === "tr") {
      var facc = document.getElementById("fAcc"),
          fSubacc = document.getElementById("fSubacc"),
          tacc = document.getElementById("tAcc"),
          tSubacc = document.getElementById("tSubacc");
      fetchMaininputData(facc);
      removeOption(fSubacc);
      fetchMaininputData(tacc);
      removeOption(tSubacc);
    // Else, fetch cat/subcat.
    // Account in income or expense is always the same,
    // not necessary changing.
    } else {
      var cat = document.getElementById("cat"),
          subcat = document.getElementById("subcat"),
          inex;
      fetchMaininputData(cat);
      removeOption(subcat);
    }
  });

  // --- 1.3 SET FUNCTIONALITY TO 'TIME' INPUT ---
  // Key code of 0-9 digits are start at 48
  // to 57. And backspace is 8

  var tp = document.getElementById("timePicker");
  tp.onkeydown = function(ev) {
    var k = ev.keyCode;
    var l = this.value.length;
    // User not press backspace
    if(k !== 8) {
      // Avoid typing none-digit key code;
      if(k < 48 || k > 57) {
        return false;
      }
      // Limit hours
      if(l === 0) {
        limitHours(k, this);
      }
      // Limit hours second digit
      if(l === 1) {
        if(this.value[0] === "2") {
          if(k > 51) {
            return false;
          }
        }
      }
      // Add colon;
      if(l === 2 && k !== 8) {
        this.value += ":";
      }
      // Limit minutes
      if(l === 2 || l === 3) {
        if(k > 53) {
          var split = this.value.split(":");
          split[1] = 0;
          var j = split.join(":");
          this.value = j;
        }
      }
    }
    // Enable backspace key.
    if(l === 5) {
      if(k > 47 && k < 58) {
        this.value = null;
        limitHours(k, this);
      } else {
        if(k !== 8) {
          return false;
        }
      }
    }
  }

  function limitHours(keycode, targetEl) {
    // If user press number
    // between 3-9 on first digit,
    // add 0 before.
    if(keycode > 50 && keycode < 58) {
      var result = "0" + targetEl.value;
      targetEl.value = result;
    }
  }

  // Get time now button;
  var nowBtn = document.querySelector(".get-time-now-div");
  nowBtn.onclick = function() {
    var d = new Date();
    var m = d.getMinutes();
    var h = d.getHours();
    function addZero(time) {
      var t;
      if(time < 10) {
        t = "0" + time;
      } else {
        t = time;
      }
      return t;
    }
    tp.value = addZero(h) + ":" + addZero(m);
  }
  

  // --- 1.FUNCTION ---
  // Hide unrelated elements and disable them.
  function displayRelatedElem() {
    console.log("--- displayRelatedElem() ---");
    var transactionType = document.getElementById("transactionType").value,
        inputSec = document.querySelectorAll(".input-sec"),
        catInput = document.getElementById("cat"),
        subcatInput = document.getElementById("subcat"),
        count = 0;
    // Displaying
    for(var i = 0; inputSec[i]; i++) {
      var is = inputSec[i];
      var input = is.querySelector(".input");
      if(is.classList.contains(transactionType)) {
        count++;
        // Set background color for even and odd elements.
        if(count % 2 === 0) { // If even
          is.style.backgroundColor = "#d9d9d9";
        } else {
          is.style.backgroundColor = "whitesmoke";
        }
        is.style.display = "block";
        input.disabled = false;
      } else {
        is.style.display = "none";
        input.disabled = true;
      }
    }
  }

  // --- 2. FETCHING DATA AND SELECTING [OPTION] ELEMENT
  function setRecentValueForCategory(targetElem) {
    console.log("--- setRecentValueForCategory(targetElem) ---");
    if(targetElem.id != "cat" && targetElem.id != "subcat") {
      return false;
    }
    var transact = document.getElementById("transactionType").value;
    if(transact == "in") {
      targetElem.dataset.recentInValue = targetElem.value;
    } else {
      targetElem.dataset.recentExValue = targetElem.value;
    }
    targetElem.dataset.recentValue = targetElem.value;
  }
  // --- 2.1 SELECT INITIAL OPTION ON [SELECT] ELEMENT ---
  var select = document.querySelectorAll("select[data-transact]");
  console.log("--- select [select] element option ---");
  for(var i = 0; select[i]; i++) {
    var s = select[i],
        transact = document.getElementById("transactionType").dataset.transact;
    s.querySelector("option[value='" 
          + s.dataset.transact + "']").selected = true;
    if(s.id == "cat" || s.id == "subcat") {
      setRecentValueForCategory(s);
    }
  }

  // --- 2.2 FETCH [OPTION] ELEMENTS OF SUBCATEGORY/SUBACCOUNT WHEN CATEGORY/ACCOUNT VALUE IS CHANGED ---
  function mainSelectChange(onchangeElem) {
    if(onchangeElem.value != "new") {
      var optParent = document.querySelector(".sub-input[data-input-group=" + onchangeElem.dataset.inputGroup + "]");
      fetchSubinputData(onchangeElem, optParent);
    }
    // Set recent value for income/expense category.
    if(onchangeElem.id == "cat") {
      setRecentValueForCategory(onchangeElem);
    }
  }
  var mainSelectElem = document.querySelectorAll(".main-input");
  for(var i = 0; mainSelectElem[i]; i++) {
    mainSelectElem[i].addEventListener("change", function() {
      console.log("--- mainSelectElem[i].onchange ---");
      mainSelectChange(this);
    });
  }

  // --- 2.3 SET RECENT VALUE FOR SUBCATEGORY WHEN ITS VALUE IS CHANGED ---
  var subcat = document.getElementById("subcat");
  subcat.addEventListener("change", function() {
    console.log("--- subcat.onchange ---");
    setRecentValueForCategory(subcat);
  });

  // --- 2.4 PREVENT USER SELECTING THE SAME SUBACCOUNT BY DISABLING THE OTHER ELEMENT (FOR TRANSACTION_TYPE = 'TR' ONLY) ---
  // Initial
  if(transactionType === "tr") {
    preventSameOption();
  }
  // On change
  var fSubacc = document.querySelector(".sub-input[name=fSubacc]");
  fSubacc.addEventListener("change", function() {
    console.log("--- fSubacc.onchange ---");
    preventSameOption();
  });

  // --- 2. FUNCTION ---
  // Set new [OPTION] elements of subcat/sub-transfer=account when targeted element value is changed.
  function fetchSubinputData(onchangeEl, optionParent) {
    console.log("--- fetchSubinputData() ---");
    // Remove recent [OPTION] elements.
    removeOption(optionParent);
    console.log("onchangeEl.value = " + onchangeEl.value);
    if(onchangeEl.value == "") {return false;}
    var transactionType = document.getElementById("transactionType");
    // Create Form Data;
    var fd = new FormData();
    fd.append(transactionType.name, transactionType.value);
    var inputType = (onchangeEl.name === "cat" ? "cat" : "acc");
    fd.append("inputType", inputType);
    fd.append(inputType, onchangeEl.value);
    // Create AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/new-record-php-fetch-catnacc.php");
    xhr.send(fd);
    xhr.onload = function() {
      console.log("--- xhr.onload ---");
      var obj = JSON.parse(this.responseText);
      // Create new [OPTION] from retrieved data
      createOption(optionParent, obj);
      // Select recently-selected [OPTION]
      selectRecentValueOption(optionParent);
      if(onchangeEl.name == "tAcc") {
        preventSameOption();
      }
    }
  }
  // SET NEW [OPTION] OF CATEGORY/SUBCATEGORY WHEN TARGETED ELEMENT VALUE IS CHANGED !
  function fetchMaininputData(optionParent) {
    console.log("--- fetchMaininputData(optionParent) ---");
    // Remove recent [OPTION] elements.
    removeOption(optionParent);
    var transact = document.getElementById("transactionType");
    if(transact.value == "") {return false;}
    var fd = new FormData();
    fd.append(transact.name, transact.value);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/new-record-php-fetch-catnacc.php");
    xhr.send(fd);
    xhr.onload = function() {
      console.log("--- xhr.onload ---");
      var obj = JSON.parse(this.responseText);
      // Create new [OPTION] from retrieved data
      createOption(optionParent, obj);
      // Select recently-selected [OPTION]
      selectRecentValueOption(optionParent);
      // Trigger mainSelectElem.onchange() event
      // because HTML doesn't detect the change that
      // trigger by JavaScript.
      mainSelectChange(optionParent);
    }
  }
  // Remove recent [OPTION] elements. Use in fetchMaininputData() and fetchSubcatAndAcc() function.
  function removeOption(optionParent) {
    console.log("--- removeOption(optionParent) ---");
    var targetOpt = optionParent.querySelectorAll("option");
    for(var i = 0; targetOpt[i]; i++) {
      if(targetOpt[i].value == "" || targetOpt[i].value == "new") {
        continue;
      }
      optionParent.removeChild(targetOpt[i]);
    }
    optionParent.firstElementChild.selected = true;
  }
  // Create new [OPTION] elements. Use in fetchMaininputData() and fetchSubcatAndAcc() function.
  function createOption(optionParent, optionContent) {
    console.log("--- createOption(optionParent, optionContent) ---");
    var classname = optionParent.querySelectorAll("option")[0].className,
        opt = document.createElement("option");
    console.log("typeof content: " + typeof optionContent);
    if(typeof optionContent === "object") { // If 2nd argument value has more than one (array).
      var contentArr = optionContent,
          i = 0;
      for(i; contentArr[i]; i++) {
        var x = contentArr[i];
        opt.value = x;
        opt.innerHTML = x;
        opt.className = classname;
        optionParent.insertBefore(opt, optionParent.lastElementChild);
      }
    } else if(typeof optionContent === "string") { // If only one (string).
      opt.value = optionContent;
      opt.innerHTML = optionContent;
      opt.className = classname;
      optionParent.insertBefore(opt, optionParent.lastElementChild);
    }
    opt.className = classname;
    optionParent.insertBefore(opt, optionParent.lastElementChild);
  }
  // Prevent user from selecting the same subaccount by disabling the [OPTION] which has the same value.
  function preventSameOption() {
    console.log("--- preventSameOption() ---");
    var fSubacc = document.querySelector(".sub-input[name=fSubacc]"), // From subaccount.
        tSubacc = document.querySelector(".sub-input[name=tSubacc]"), // To subaccount.
        tSubaccOpt = tSubacc.querySelectorAll("option");
    for(var i = 0; tSubaccOpt[i]; i++) {
      var opt = tSubaccOpt[i];
      // opt.value !== "" to prevent from disabling '--- select ---' [OPTION];
      if(opt.value !== "" && opt.value === fSubacc.value) {
        opt.disabled = true;
        opt.selected = false;
      } else {
        opt.disabled = false;
      }
    }
  }
  // Select [OPTION] element that its value is the same as its parent recent value.
  function selectRecentValueOption(optionParent) {
    console.log("--- selectRecentValueOption(optionParent) ---");
    var transact = document.getElementById("transactionType").value,
        recVal;
    if(transact == "in") {
      recVal = optionParent.dataset.recentInValue;
    } else if (transact == "ex") {
      recVal = optionParent.dataset.recentExValue;
    }
    var opt = optionParent.querySelector("option[value='" 
        + recVal + "']");
    if(opt != null) {
      opt.selected = true;
    }
    optionParent.setAttribute("data-recent-" 
      + transact + "-value", optionParent.value);
  }

  // --- 3. FORM SUBMISSION ---
  // --- 3.1 ENABLING SUBMIT BUTTON ---
  // --- 3.1.1 INITIAL ---
  enableSubmitBtn("originalSubmitBtn");
  // --- 3.1.2 ONINPUT ---
  var allInput = document.querySelectorAll(".input"),
      i = 0;
  for(i; allInput[i]; i++) {
    allInput[i].addEventListener("input", function() {
      console.log("--- allInput[i].oninput ---");
      enableSubmitBtn("originalSubmitBtn");
    });
  }

  // --- 3.2 SENDING FORM DATA ---
  var submitBtn = document.getElementById("originalSubmitBtn");
  submitBtn.addEventListener("click", function() {
    var enabledInput = document.querySelectorAll(".input:not([disabled])");
    // Create status bar
    var statusBar = document.createElement("div");
    statusBar.className = "status-bar";
    document.body.appendChild(statusBar);
    // Create form data
    var fd = new FormData();
    for(var i = 0; enabledInput[i]; i++) {
      fd.append(enabledInput[i].name, enabledInput[i].value);
    }
    // Create AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/today-edit-php.php");
    xhr.send(fd);
    xhr.onload = function() {
      console.log("--- xhr.onload ---");
      console.log(this.responseText);
      // Receive JSON data
      var obj = JSON.parse(this.responseText);
      displayStatusBar(obj.status, obj.status_text, true);
    }
  });

  // --- 3. FUNCTION ---
  // Enable submit [BUTTON];
  function enableSubmitBtn(buttonId) {
    console.log("--- enableSubmitBtn() ---");
    var btn = document.getElementById(buttonId),
        relatedInput,
        filledNum = 0,
        i = 0;
    if(buttonId == "originalSubmitBtn") {
      var transact = document.getElementById("transactionType");
      relatedInput = document.querySelectorAll(".input." + transact.value);
    } else if(buttonId = "addnewSubmitBtn") {
      relatedInput = document.querySelectorAll(".addnew-input");
    } else { return false;}
    for(i; relatedInput[i]; i++) {
      var input = relatedInput[i];
      if(input.value != "" && input.value != "new") {
        filledNum++;
      }
    }
    if(filledNum === relatedInput.length) {
      btn.disabled = false;
    } else {
      btn.disabled = true;
    }
  }
  // Modify and display status bar
  function displayStatusBar(status, statusText, goBack) {
    console.log("--- displayStatusBar(status, statusText) ---");
    // Set default value of 'goBack'
    goBack = (typeof goBack !== 'boolean' ? false : goBack);
    var statusBars = document.querySelectorAll(".status-bar");
    // Select last index of statusBars
    var id = statusBars.length - 1,
        statusBar = statusBars[id];
    statusBar.innerHTML = statusText;
    if(status === 1) { // If complete
      if(goBack) {
        statusBar.style.backgroundColor = "#000";
        statusBar.style.height = "100%";
        setTimeout(function() {
          window.history.back();
        }, 2000);
      } else {
        statusBar.style.backgroundColor = "#00cc00";
        statusBar.style.height = "5%";
        setTimeout(function() {
          statusBar.style.opacity = "0";
          setTimeout(function() {
            document.body.removeChild(statusBar);
          }, 1000);
        }, 3000);
      }
    } else { // If some value is incorrect
      statusBar.style.backgroundColor = "#ff0000";
      statusBar.style.height = "5%";
      setTimeout(function() {
        statusBar.style.opacity = "0";
        setTimeout(function() {
          document.body.removeChild(statusBar);
        }, 1000);
      }, 3000);
    }
  }

  // --- 4. BUTTON ---
  // --- 4.1 BACK BUTTON ---
  var backBtn = document.querySelector(".header-left img.icon");
  backBtn.addEventListener("click", function() {
    console.log("--- backBtn.onclick ---");
    window.history.back();
  });
  // --- 4.2 CANCEL BUTTON ---
  var cancelBtn = document.getElementById("originalCancelBtn");
  cancelBtn.addEventListener("click", function() {
    console.log("--- cancelBtn.onclick ---");
    window.history.back();
  });

  // --- 5. ADDNEW MODAL BOX ---
  // --- 5.1 MODIFY & OPEN MODAL BOX VIA SELECTING '++ ADD NEW ++' [OPTION] ELEMENT. ---
  var modalboxSelectElements = document.querySelectorAll(".for-addnew-modalbox"),
      background = document.querySelector(".addnew-modalbox-background"),
      i = 0;
  for(i; modalboxSelectElements[i]; i++) {
    modalboxSelectElements[i].addEventListener("change", function() {
      console.log("--- modalboxSelectElements[i].onchange ---");
      var v = this.value;
      console.log("value = " + v);
      // --- 5.1.2 MODIFY MODAL BOX ---
      if(v == "new") {
        // 5.1.2.1 Set header
        var headerH2 = document.querySelector(".addnew-modalbox header h2");
        headerH2.innerHTML = "New " + this.dataset.inputGroup;
        // 5.1.2.2 Remove old main [INPUT] or [SELECT] element in input division, if it has.
        var inputDiv = document.querySelector(".addnew-input-main");
        if(inputDiv.children.length > 1) {
          var targetElem = inputDiv.querySelector(".addnew-input");
          inputDiv.removeChild(targetElem);
        }
        // 5.1.2.3 Create modal box main input element 
        // in accordance with what user is going to add 
        // e.g. cat/acc or subcat/subacc.
        var input;
        if(this.classList.contains("main-input")) { // If user adds new cat/acc.
          console.log("--- Create new [INPUT] ---");
          // Create new [INPUT] type text element.
          input = document.createElement("input");
          input.type = "text";
        } else { // If user adds new subcat/subacc.
          console.log("--- Clone [SELECT] ---");
          // Clone [SELECT] element.
          var originalElem = document.querySelector("select[data-input-group=" 
            + this.dataset.inputGroup + "].main-input");
          input = originalElem.cloneNode(true);
          input.value = originalElem.value;
          // Remove '-- select --' and '-- add new --' [OPTION]
          var selectOpt = input.querySelector("option:first-of-type"),
              addnewOpt = input.querySelector("option:last-of-type");
          input.removeChild(selectOpt);
          input.removeChild(addnewOpt);
        }
        // 5.1.2.4 Set attributes for modalbox main input.
        input.className = "addnew-input";
        input.id = "addnewMainInput";
        input.dataset.tablename = this.dataset.tablename;
        // 5.1.2.5 Store event-triggered element id in dataset
        // of modal box main and sub input, for specifying 
        // which [SELECT] element to put [OPTION] into
        // when user click submit button.
        input.dataset.maininputId = this.id;
        var subInput = document.querySelector(".sub-input[data-input-group=" 
          + this.dataset.inputGroup + "]");
        document.getElementById("addnewSubInput").dataset.subinputId = subInput.id;
        // 5.1.2.6 Add the created element into modal box main-input division.
        inputDiv.appendChild(input);
        // 5.1.2.7 Show modalbox.
        background.style.display = "flex";

        // --- 5.2 FORM SUBMISSION ---
        // --- 5.2.1 'ADD' BUTTON ENABLING ---
        // Always update modalboxInput(s) for
        // enabling 'add' button when user click add new cat/acc.
        var modalboxInput = document.querySelectorAll(".addnew-input");
        for(var i = 0; modalboxInput[i]; i++) {
          modalboxInput[i].addEventListener("input", function() {
            console.log("--- modalboxInput[i].onclick ---");
            enableSubmitBtn("addnewSubmitBtn");
          });
        }
      // --- 5.1.1 PREPARATION ---
      // Store recent value for resetting value 
      // when user cancel adding new data.
      // And prevent from storing "-- select --" and "-- add new --" value.
      } else if(v != "") {
        this.dataset.recentValue = v;
      }
      // Make sure that submit button is available.
      enableSubmitBtn("originalSubmitBtn");
    });
  }
  // --- 5.2.2 SENDING SUBMITTED DATA ---
  var addBtn = document.getElementById("addnewSubmitBtn");
  addBtn.addEventListener("click", function() {
    console.log("--- addBtn.onclick ---");
    // Create status bar
    var statusBar = document.createElement("div");
    statusBar.className = "status-bar";
    document.body.appendChild(statusBar);
    var mainInput = document.getElementById("addnewMainInput"),
        subInput = document.getElementById("addnewSubInput"),
        transactionType = document.getElementById("transactionType");
    // Create form data
    var fd = new FormData();
    fd.append("main", mainInput.value);
    fd.append("sub", subInput.value);
    fd.append("transactionType", transactionType.value);
    fd.append("tablename", mainInput.dataset.tablename);
    // Create AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "php/new-catacc-php.php");
    xhr.send(fd);
    xhr.onload = function() {
      console.log("--- xhr.onload ---");
      console.log(this.responseText);
      // Receive JSON data
      var obj = JSON.parse(this.responseText);
      displayStatusBar(obj.status, obj.status_text)
      // If adding new data is incomplete, keep open modalbox.
      if(obj.status === 0) {
        return false;
      }
      // Create newly-added [OPTION] element in .main-input and .sub-input
      var optParentMain = document.getElementById(mainInput.dataset.maininputId),
          optParentSub = document.getElementById(subInput.dataset.subinputId);
      // If user also add cat/acc, not only subcat/subacc,
      // create newly-added [OPTION] in .main-input and select it.
      if(mainInput.nodeName != "SELECT") {
        createOption(optParentMain, mainInput.value);
        optParentMain.querySelector("option[value=" 
          + mainInput.value + "]").selected = true;
      }
      createOption(optParentSub, subInput.value);
      optParentSub.querySelector("option[value=" 
        + subInput.value + "]").selected = true;
      // Make sure that submit button is available
      enableSubmitBtn("originalSubmitBtn");
      closeModalbox();
    }
  });
  

// TO DO
// - Make a thorough flowchart of this whole app.




  // --- 5.2 CLOSE MODAL BOX ---
  // --- 5.2.1 CLOSE BY CLICKING BACKGROUND ---
  var background = document.querySelector(".addnew-modalbox-background");
  background.addEventListener("click", function(ev) {
    var e = ev.target;
    if(e.className == this.className) {
      console.log("--- background.onclick ---");
      resetRecentValue();
      closeModalbox();
    }
  });
  // --- 5.2.2 CLOSE BY CLICKING CANCEL BUTTON ---
  var modalboxCancelBtn = document.getElementById("addnewCancelBtn");
  modalboxCancelBtn.addEventListener("click", function() {
    console.log("--- modalboxCancelBtn.onclick ---");
    resetRecentValue();
    closeModalbox();
  });
  // --- 5.2.3 CLOSE BY PRESSING 'ESC' BUTTON ON KEYBOARD ---
  document.addEventListener("keyup", function(ev) {
    console.log("--- document.onkeyup ---");
    var e = ev.which;
    // keycode: 27 is 'ESC' button.
    if(e === 27 && (background.style.display != "none")) {
      resetRecentValue();
      closeModalbox();
    }
  });
  // --- 5.2.FUNCTION ---
  // Clear value and close modal box
  function closeModalbox() {
    console.log("--- closeModalbox() ---");
    // Clear existing value.
    var inputs = document.querySelectorAll(".addnew-input"),
        i = 0;
    for(i; inputs[i]; i++) {
      inputs[i].value = "";
    }
    // Hide modalbox.
    var background = document.querySelector(".addnew-modalbox-background");
    background.style.display = "none";
  }
  // Reset [SELECT] recent value when user cancel adding new data.
  function resetRecentValue() {
    var mainInput = document.getElementById("addnewMainInput"),
        subInput = document.getElementById("addnewSubInput"),
        targetElem;
    if(mainInput.nodeName == "SELECT") { // If user was adding only subcat/subacc
      targetElem = document.getElementById(subInput.dataset.subinputId);
    } else { // If user was also adding cat/acc, not only subcat/subacc
      targetElem = document.getElementById(mainInput.dataset.maininputId);
    }
    targetElem.value = targetElem.dataset.recentValue;
    // Make sure that submit button is available.
    enableSubmitBtn("originalSubmitBtn");
  }
});