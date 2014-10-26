$(function () {
    Loader = {
        options: {
            message: null,
            defaultProgressColor: null
        },
        element: null,
        progressBarElement: null,
        messageElement: null,
        init: false,
        stages: 1,
        curStage: 0,
        Init: function (message) {
            this.options.message = typeof message === 'undefined' ? 'Loading...' : message;
            document.body.innerHTML += '<div id="AcLoading"><div id="AcLoaderBar"><div id="AcLoaderBarFill"></div></div><div id="AcLoader"><div id="AcLoaderSpin"><i class="fa fa-cog fa-spin"></i><br /></div><div id="AcLoaderMessage">' + this.message + '</i><br /></div></div></div>';
            this.element = document.getElementById("AcLoading");
            this.messageElement = document.getElementById("AcLoaderMessage");
            this.progressBarElement = $("#AcLoaderBarFill");
            this.options.defaultProgressColor = this.progressBarElement.css('background-color');
            this.init = true;
        },
        Show: function (message, stages, movenext) {
            if (!this.init) { return; }
            this.stages = typeof stages === 'undefined' || parseFloat(stages) < 0 ? 0 : parseFloat(stages);
            this.curStage = 0;
            this._Show(message);
            this.SetProgress(0, false);
            if (!(typeof movenext === 'undefined') && movenext) {
                this.MoveNext();
            }
        },
        _Show: function (message) {
            if (!this.init) { return; }
            if (message) {
                this.SetMessage(message);
            }
            this.element.style.display = "block";
        },
        Hide: function () {
            if (!this.init) { return; }
            this.element.style.display = "none";
        },
        Remove: function () {
            if (!this.init) { return; }
            this.element.remove();
            this.init = false;
        },
        SetMessage: function (message) {
            if (!this.init) {
                return;
            }
            this.options.message = message;
            this.messageElement.innerHTML = message;
        },
        MoveNext: function (message) {
            if (!this.init) { return; }
            if (typeof message !== 'undefined') {
                this.SetMessage(message);
            }
            this.curStage = this.curStage + 1;
            this.SetProgress(this.curStage);
        },
        SetError: function (message) {
            if (!this.init) { return; }
            if (typeof message !== 'undefined') {
                this.SetMessage(message);
                this.SetProgressBarColor('red');
            }
            setTimeout(function () {
                Loader.Hide()
                Loader.SetProgressBarColor();
            }, 1500);
        },
        SetProgressBarColor: function (color) {
            if (!this.init) { return; }
            this.progressBarElement.css('background-color', typeof color === 'undefined' ? this.options.defaultProgressColor : color);
        },
        SetProgress: function (stage, animate) {
            if (!this.init) { return; }
            if (typeof animate === 'undefined') {
                animate = true;
            }
            var dWidth = ((parseFloat(stage) / this.stages) * 100);
            if (animate) {
                this.progressBarElement.animate({
                    width: dWidth + "%",
                }, 400, function () {
                    if (stage >= Loader.stages) {
                        Loader.Hide();
                    }
                });
            } else {
                this.progressBarElement.css("width", dWidth + "%");
            }
        }
    };
    Loader.Init();

    //Loader.Show('Loading...', 1, false);
});