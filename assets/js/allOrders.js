
limit = 1;
max = 0;
// Get List of orders
function getAllOrders(){
    var fdata = $("#allOrdersList").html();
    $.post("", {getallOrders:limit}, function(html){
                try {
                    resp = JSON.parse(html);
                } catch (error) {
                    alertify.error('حدث خطا من السيرفر');
                    return;
                }
        if(resp.ok != true){
            $("#allOrdersList").html('');
            alertify.error(resp.msg); // Print Error MSG
        }else{
            var itemData = resp[0];
            $("#allOrdersList").html('');
            itemData.forEach(function(item){
                $("#allOrdersList").append(`<tr data-bs-dismiss="modal" onclick="loadOrder(${item.order_id},false)" id="qsitm${item.order_id}">`);
                $("#qsitm" + item.order_id).append('<td>' + item.order_id + '</td>');
                $("#qsitm" + item.order_id).append('<td>' + item.items_count + '</td>');
                $("#qsitm" + item.order_id).append('<td>' + item.discount + '</td>');
                $("#qsitm" + item.order_id).append('<td>' + item.total_price + '</td>');
                if(item.consumer_id != 0){
                    $.post("", {"getconsumer":item.consumer_id}, (html) =>{
                        consumerData = JSON.parse(html)[0][0];
                        $("#qsitm" + item.order_id).append('<td>' + consumerData.name + '</td>');
                        $("#qsitm" + item.order_id).append('<td>' + item.date + '</td>');
                    });
                }else{
                    $("#qsitm" + item.order_id).append('<td>لايوجد</td>');
                    $("#qsitm" + item.order_id).append('<td>' + item.date + '</td>');
                }
                $("#allOrdersList").append('</tr>');
            });
            $("#resCount").html(`عرض ${itemData.length} من ${itemData[0].resCount} نتيجة`);
            max = itemData[0].resCount;
        }
    }); 
}
$(document).ready(function () {
    $("#allOrdersBtn").click(()=>{
        $('#allOrders').modal('toggle');
        getAllOrders();
    });


    $("#back").click(function(){
        if(limit > 10){
            limit -= 10;
            getAllOrders();
        }
    });
    $("#next").click(function(){
        if((limit + 10) <= max){
            limit += 10;
            getAllOrders();
        }
    });
});






