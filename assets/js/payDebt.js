function saveDebtclient(id,name){
    $("#payDebtconsumer_id").val(id);
    $("#payDebtSearchBar").val(name);
    updateDebtPricePaid(0, 0);
    $("#payDebtSearch").hide();
}
function updateDebtPricePaid(newval, showNumPad = true){
    $.post("", {"getdebtvalue":$("#payDebtconsumer_id").val()}, function(html) {
        if(html != false){
            resp = JSON.parse(html);
            if(resp.ok != true){
                alertify.error(resp.msg); // Print Error MSG
            }else{
                if(showNumPad) $('#payDebt').modal('toggle');
                $("#payDebtprice_paid").val(newval.toLocaleString("en-US"));
                $("#payDebtprice_left").val((Math.abs(resp[0]) - newval).toLocaleString("en-US"));
                $("#payDebtSaveButton").removeAttr("disabled");
            }
        }else{
            alertify.error("هذا العميل لاتوجد عليه ديون سابقه"); // Print Error MSG
        }
    });
    
}
$(document).ready(function(){
    $("#payDebtSaveButton").click(function (e) { 
        $.post("", {"paydebt":{"consumer_id":$("#payDebtconsumer_id").val(), "value":$("#payDebtprice_paid").val()}}, function(html){
            resp = JSON.parse(html);
            if(resp.ok != true){
                alertify.error(resp.msg);
            }else{
                $("#payDebt input").val("");
            }
        });
        
    });



    $("#payDebtSearchBar").keyup(function (e) { 
        if($("#payDebtSearchBar").val().length > 0){
            $.post("", {"searchconsumer":$("#payDebtSearchBar").val()}, function(html){
                resp = JSON.parse(html);
                if(resp.ok != true){
                    alertify.error(resp.msg);
                }else{
                    var itemData = resp[0];
                    $("#payDebtSearch").html('');
                    try {
                        itemData.forEach(function(item){
                            $("#payDebtSearch").append(`<tr onclick="saveDebtclient(${item.id},'${item.name}')" id="payDebtItm${item.id}">`);
                            $("#payDebtItm" + item.id).append('<td class="noselect">' + item.name + '</td>');
                            $("#payDebtSearch").append('</tr>');
                        });
                    } catch (error) {
                        $("#payDebtSearch").append(`<tr onclick="saveDebtclient(${itemData.id},'${itemData.name}')" id="payDebtItm${itemData.id}">`);
                        $("#payDebtItm" + itemData.id).append('<td class="noselect">' + itemData.name + '</td>');
                        $("#payDebtSearch").append('</tr>');
                    }
                    $("#payDebtSearch").show();
                }
            });
        } else {
            $("#payDebtSearch").html('');
        }
    });
    
});