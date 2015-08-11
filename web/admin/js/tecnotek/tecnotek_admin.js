if (typeof console == "undefined" || typeof console.log == "undefined" || typeof console.debug == "undefined")
    var console = { log:function () {
}, debug:function () {
} };
if (typeof jQuery !== 'undefined') {
    console.debug("JQuery found!!!");
} else {
    console.debug("JQuery not found!!!");
}

String.prototype.endsWith = function(suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== -1;
};

$(document).ready(function () {
    Tecnotek.init();
});
var Tecnotek = {
    module:"",
    imagesURL:"",
    assetsURL:"",
    isIe:false,
    session:{},
    logout:function (url) {
        location.href = url;
    },
    roundTo:function (original) {
        //return Math.round(original*100)/100;
        return original.toFixed(2);
    },
    init:function () {
        var module = Tecnotek.module;
        console.debug("Module: " + module);
        if (module) {
            switch (module) {
                case "sports-list": Tecnotek.Sports.List.init(); break;
                case "pentions-list": Tecnotek.Pentions.List.init(); break;
                case "patients-list": Tecnotek.Patients.List.init(); break;
                case "patients-edit": Tecnotek.Patients.Edit.init(); break;
                case "patients-pentions-list": Tecnotek.PatientsPentions.List.init(); break;
                default: break;
            }
        }
        Tecnotek.setNavigation();
        Tecnotek.UI.init();
    },
    setNavigation: function() {
        var path = window.location.pathname;
        path = path.replace(/\/$/, "");
        path = decodeURIComponent(path);

        //Set Dashboard
        if(path.endsWith('admin') || path.endsWith("admin/")){
            $("#menu_dashboard_item").addClass("active");
        } else
        // Set Patientes Item Active
        if(path.indexOf('patients') > -1){
            $("#menu_patients_item").addClass("active");
        } else
        if (path.indexOf("sports")  > -1) {
            $("#menu_sports_item").addClass("active");
            $("#menu_forms_item").addClass("active");
        } else if(path.indexOf("pentions")  > -1){
            $("#menu_pentions_item").addClass("active");
            $("#menu_forms_item").addClass("active");
        } else
            $("#menu_forms_item").addClass("active");
    },
    ajaxCall:function (url, params, succedFunction, errorFunction) {
        var request = $.ajax({
            url:url,
            type:"POST",
            data:params,
            dataType:"json"
        });

        request.done(succedFunction);

        request.fail(errorFunction);
    },
    ajaxSyncCall:function (url, params, succedFunction, errorFunction) {
        var request = $.ajax({
            url:        url,
            type:       "POST",
            data:       params,
            dataType:   "json",
            async:      false
        });

        request.done(succedFunction);

        request.fail(errorFunction);
    },
    showInfoMessage:function (message) {
        Tecnotek.showInformationMessage(message);
    },
    showInformationMessage:function (message) {
        alert(message);
    },
    showErrorMessage:function (message) {
        alert(message);
    },
    showConfirmationQuestion:function (message) {
        return confirm(message);
    },
    UI:{
        translates:{},
        urls:{},
        vars:{},
        intervals:{},
        init:function () {

        },
        validateForm:function (formSelector) {
            //alert("validating form");
            var result = $(formSelector).validationEngine('validate');
            //alert("result "+result);
            return result;
        }
    }
};


