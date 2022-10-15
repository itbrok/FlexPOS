$(document).ready(function(){
    $("#backToDebt").hide();
    $(".price_paid").click(function (e) { 
        $("#backToDebt").show();
        $("#numpadSaveButton").hide();
    });
    $("#backToDebt").click(function (e) { 
        updatePricePaid($("#numpadResult").val());
        $("#numpadResult").val('');
    });
});

function updatePricePaid(newval){
    $("#price_paid").val(newval);
    $("#backToDebt").hide();
    $("#numpadSaveButton").show();
}
