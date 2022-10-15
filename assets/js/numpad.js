mycall="";
var CurrentWholesalePrice="";
var showCurrentWholesalePrice=false;
$(document).ready(function(){
    $("#back-Space").click(function (e) { 
        $("#numpadResult").val($("#numpadResult").val().slice(0,-1));
    });
    $(".numpad button").click(function(e){
        toAdd = $(this).attr("val");
        res = $("#numpadResult").val();
        if((res.includes(".") && toAdd == ".") || res.length > 16){
            return;
        }else{
            $("#numpadResult").val(res + "" + toAdd);
        }
    });
    $("#numpadResultClear").click(function(e){
        $("#numpadResult").val('');
    });
    $(".numpadCloseButton").click(function(e){
        $("#numpadResult").val('');
    });
    $("#numpadSaveButton").click(function(){
        if($("#numpadResult").val() != ""){
            newval = parseFloat($("#numpadResult").val());
            mycall=mycall.replace("newval", newval);
            eval(mycall);
            $("#numpadResult").val('');
        }
        
    });    
});
function saveParam(a=""){
    $("#backToDebt").hide();
    $("#numpadSaveButton").show();
    mycall=a;
}
//Numpad عرض سعر الجملة في
const myModalEl = document.getElementById('numPad')
myModalEl.addEventListener('shown.bs.modal', event => {
    if(showCurrentWholesalePrice == true){
        $(".whsprice").show();
        $(".whsprice").html(`سعر الجملة: ${CurrentWholesalePrice}`);
        showCurrentWholesalePrice = false;
    }else{
        $(".whsprice").hide();
    }
})
myModalEl.addEventListener('hidden.bs.modal', event => {
    $(".whsprice").hide();
})

