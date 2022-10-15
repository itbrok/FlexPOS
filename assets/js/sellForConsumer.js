function saveclient(id,name){
    $("#consumer_id").val(id);
    $("#sellForConsumerSearchBar").val(name);
    $("#quickConsumerSearch").hide();
}

function addnewclient(name){
    $("#NewConsumerNameBar").val(name);
}
$(document).ready(function(){
    
    $("#sellForConsumerSearchBar").keyup(function (e) { 
        if($("#sellForConsumerSearchBar").val().length > 0){
            $.post("", {"searchconsumer":$("#sellForConsumerSearchBar").val()}, function(html){
                    try {
                        resp = JSON.parse(html);
                    } catch (error) {
                        alertify.error('حدث خطا من السيرفر');
                        return;
                    }
                if(resp.ok != true){
                    $("#quickConsumerSearch").html('');
                    $("#quickConsumerSearch").html(`<tr data-bs-toggle="modal" data-bs-target="#NewConsumer" onclick="addnewclient('` + $("#sellForConsumerSearchBar").val() + `')"><td class="noselect">اضافة عميل جديد</td></tr>`);
                    $("#quickConsumerSearch").show();
                }else{
                    var itemData = resp[0];
                    $("#quickConsumerSearch").html('');
                    try {
                        itemData.forEach(function(item){
                            $("#quickConsumerSearch").append(`<tr onclick="saveclient(${item.id},'${item.name}')" id="cqs${item.id}">`);
                            $("#cqs" + item.id).append('<td class="noselect">' + item.name + '</td>');
                            $("#quickConsumerSearch").append('</tr>');
                        });
                    } catch (error) {
                        $("#quickConsumerSearch").append(`<tr onclick="saveclient(${itemData.id},'${itemData.name}')" id="cqs${itemData.id}">`);
                        $("#cqs" + itemData.id).append('<td class="noselect">' + itemData.name + '</td>');
                        $("#quickConsumerSearch").append('</tr>');
                    }
                    $("#quickConsumerSearch").append(`<tr data-bs-toggle="modal" data-bs-target="#NewConsumer" onclick="addnewclient('` + $("#sellForConsumerSearchBar").val() + `')"><td class="noselect">اضافة عميل جديد</td></tr>`);
                    $("#quickConsumerSearch").show();
                }
            });
        } else {
            $("#quickConsumerSearch").html('');
        }
    });
    
});