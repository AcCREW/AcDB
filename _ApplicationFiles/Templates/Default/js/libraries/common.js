'use strict';

function GetObjectMemoryUsage(object) {

	var objectList = [];

	var recurse = function (value) {
		var bytes = 0;

		if (typeof value === 'boolean') {
			bytes = 4;
		}
		else if (typeof value === 'string') {
			bytes = value.length * 2;
		}
		else if (typeof value === 'number') {
			bytes = 8;
		}
		else if
        (
            typeof value === 'object'
            && objectList.indexOf(value) === -1
        ) {
			objectList[objectList.length] = value;

			for (var i in value) {
				bytes += 8; // an assumed existence overhead
				bytes += recurse(value[i])
			}
		}

		return bytes;
	}

	return ConvertBytes(recurse(object));
}

function ConvertBytes(nSizeBytes, nPrecision) {
	if (nPrecision === undefined) {
		nPrecision = 2;
	}
	var dBase = (Math.log(nSizeBytes) / Math.log(1024));
	var arSuffixes = Array('B', 'kB', 'MB', 'GB', 'TB');

	return parseFloat(Math.pow(1024, dBase - Math.floor(dBase))).toFixed(nPrecision) + arSuffixes[Math.floor(dBase)];
}

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

