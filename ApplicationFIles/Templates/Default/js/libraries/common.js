'use strict';



function SetHTMLValue(sID, vValue) {
    var Element = document.getElementById(sID);
    if (Element === undefined) {
        alert('Cant find element with ID - "' + sID + '".');
    }

    Element.innerHTML = vValue;
}

function GetHTMLValue(sID) {
    var Element = document.getElementById(sID);
    if (Element === undefined) {
        alert('Cant find element with ID - "' + sID + '".');
    }

    return Element.innerHTML;
}

