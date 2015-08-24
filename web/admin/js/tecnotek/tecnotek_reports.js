var Tecnotek = Tecnotek || {};

Tecnotek.Reports = {
    List: {
        init : function() {
            $('#btnPrint').click(function(event){
                $("#report").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});
            });
        }/*,
        initButtons : function() {
            alert("llego");
            $('#btnPrint').click(function(event){

                $("#report").printElement({printMode:'popup', pageTitle:$(this).attr('rel')});

            })
        }*/
    }
};