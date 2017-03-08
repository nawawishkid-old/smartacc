window.onload = function() {
  var allNavTab = document.querySelectorAll(".nav-tab");

  for(var i = 0; allNavTab[i]; i++) {
    allNavTab[i].addEventListener("click", function() {
      if(this.id == "overviewNavTab") {
        var iframe = document.getElementsByTagName("iframe")[0];

        if(iframe.src != "http://localhost/smartacc/overview.php") {
          iframe.src = "overview.php";
        }
      } else {
        var iframe = document.getElementsByTagName("iframe")[0];

        if(iframe.src != "http://localhost/smartacc/addnewrec.php") {
          iframe.src = "addnewrec.php";
        }
      }
    })
  }
}