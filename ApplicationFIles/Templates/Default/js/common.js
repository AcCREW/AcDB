'use strict';

var Error = (function (message, type) {
    function Error(message, type) {
        this.message = message;
        this.type = type;
    }
    return Error;
})();
