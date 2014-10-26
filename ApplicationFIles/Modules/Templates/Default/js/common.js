'use strict';

var Error = (function () {
    function Error(message, type) {
        this.message = message;
        this.type = type;
    }
    return Error;
})();
