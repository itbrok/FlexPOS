

$(document).ready(function(){
    $("#unFinishedOrdersBtn").click(()=>{
        $('#unFinishedOrders').modal('toggle');
        // $("#unFinishedOrders").print({title: null});
        var fdata = $("#unFinishedOrdersList").html();
        $.post("", {"getUnFinishedOrders":1}, function(html){
                    try {
                        resp = JSON.parse(html);
                    } catch (error) {
                        alertify.error('حدث خطا من السيرفر');
                        return;
                    }
            if(resp.ok != true){
                $("#unFinishedOrdersList").html('');
                alertify.error(resp.msg); // Print Error MSG
            }else{
                var itemData = resp[0];
                $("#unFinishedOrdersList").html('');
                itemData.forEach(function(item){
                    $("#unFinishedOrdersList").append(`<tr data-bs-dismiss="modal" onclick="loadOrder(${item.order_id})" id="qsitm${item.order_id}">`);
                    $("#qsitm" + item.order_id).append('<td>' + item.order_id + '</td>');
                    $("#qsitm" + item.order_id).append('<td>' + item.items_count + '</td>');
                    $("#qsitm" + item.order_id).append('<td>' + item.discount + '</td>');
                    $("#qsitm" + item.order_id).append('<td>' + item.total_price + '</td>');
                    if(item.consumer_id != 0){
                        $.post("", {getconsumer:item.consumer_id}, (html) =>{
                            consumerData = JSON.parse(html)[0][0];
                            $("#qsitm" + item.order_id).append('<td>' + consumerData.name + '</td>');
                            $("#qsitm" + item.order_id).append('<td>' + item.date + '</td>');
                        });
                    }else{
                        $("#qsitm" + item.order_id).append('<td>لايوجد</td>');
                        $("#qsitm" + item.order_id).append('<td>' + item.date + '</td>');
                    }
                    $("#unFinishedOrdersList").append('</tr>');
                });
            }
        }); 
    });
});