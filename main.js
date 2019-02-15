YUI().use("json", "yahoo", "event", "io-base", "node-base", function(Y) {
/*function disablecatsel() {
   alert('disable');
   //var inp = document.getElementByTagName("select"); alert(inp);
   var ip = document.getElementById('#id_coursecategory'); console.log(ip);
   if (document.getElementByName("coursecategory").disabled = true) {
       document.getElementByName("coursecategory").disabled = false;
   } else {
       document.getElementByName("coursecategory").disabled = true;
   }
}*/
disablecatsel = function(e) {
    var val = document.getElementById("mform1").elements.length;
    
    for (var i=0;i<=document.getElementById("mform1").elements.length;i++) {
        if (document.getElementById("mform1").elements[i].name === "coursecategory") {
           if (document.getElementById("mform1").elements[i].disabled == true) {
               document.getElementById("mform1").elements[i].disabled = false;
           } else {
               document.getElementById("mform1").elements[i].disabled = true;
           }
        }
        if (document.getElementById("mform1").elements[i].name === "courselist") {
            if (document.getElementById("mform1").elements[i].disabled == false) {
                document.getElementById("mform1").elements[i].disabled = true;
            }
        }
        if (document.getElementById("mform1").elements[i].name === "userlist") {
            if (document.getElementById("mform1").elements[i].disabled == false) {
                document.getElementById("mform1").elements[i].disabled = true;
            }
        }
    }
}

disablecrssel = function(e) {
    var val = document.getElementById("mform1").elements.length;
 
    for (var i=0;i<=document.getElementById("mform1").elements.length;i++) {
        if (document.getElementById("mform1").elements[i].name === "courselist") {
            if (document.getElementById("mform1").elements[i].disabled == true) {
                document.getElementById("mform1").elements[i].disabled = false;
            } else {
                document.getElementById("mform1").elements[i].disabled = true;
            }
        }
        if (document.getElementById("mform1").elements[i].name === "coursecategory") {
            if (document.getElementById("mform1").elements[i].disabled == false) {
                document.getElementById("mform1").elements[i].disabled = true;
            }
        }
        if (document.getElementById("mform1").elements[i].name === "userlist") {
            if (document.getElementById("mform1").elements[i].disabled == false) {
                document.getElementById("mform1").elements[i].disabled = true;
            }
        }
    }
}

disableusersel = function(e) {
    var val = document.getElementById("mform1").elements.length;

    for (var i=0;i<=document.getElementById("mform1").elements.length;i++) {
        if (document.getElementById("mform1").elements[i].name === "userlist") {
            if (document.getElementById("mform1").elements[i].disabled == true) {
                document.getElementById("mform1").elements[i].disabled = false;
            } else {
                document.getElementById("mform1").elements[i].disabled = true;
            }
        }
        if (document.getElementById("mform1").elements[i].name === "courselist") {
            if (document.getElementById("mform1").elements[i].disabled == false) {
                document.getElementById("mform1").elements[i].disabled = true;
            }
        }
        if (document.getElementById("mform1").elements[i].name === "coursecategory") {
            if (document.getElementById("mform1").elements[i].disabled == false) {
                document.getElementById("mform1").elements[i].disabled = true;
            }
        }
    }
}

Y.on('change', disablecatsel, "#id_radiobut_0");
Y.on('change', disablecrssel, "#id_radiobut_1");
Y.on('change', disableusersel, "#id_radiobut_2");
/*.mform .fitem div.fitemtitle{
    width: 400px;
}*/
});
