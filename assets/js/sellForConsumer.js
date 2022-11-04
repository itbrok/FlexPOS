function saveclient(id, name) {
    $("#consumer_id").val(id);
    $(".quickConsumerSearch").hide();
    $(".sellForConsumerSearchBar").val(name);
    $("#clientName").html(`العميل : ${name}`);
}
def = [];
function addnewclient(name, showSell4ClientPad = false) {
    $("#NewConsumerNameBar").val(name);
}

function showsellpad(){
    if (def['showSell4ClientPad']) {
        $("#sellForConsumer").modal('toggle');
        def['showSell4ClientPad'] = false;
    }
}
$(document).ready(function () {



    $("#sellForConsumerForm").click(function(){
        def['showSell4ClientPad'] = true;
    });
    $(".sellForConsumerSearchBar").on("input", function () {
        var clientName = $(this).val();
        if (clientName.length > 0) {
            $.post("", { "searchconsumer": clientName }, function (html) {
                try {
                    resp = JSON.parse(html);
                } catch (error) {
                    alertify.error('حدث خطا من السيرفر');
                    return;
                }
                if (resp.ok != true) {
                    $(".quickConsumerSearch").html('');
                    $(".quickConsumerSearch").html(`<tr data-bs-toggle="modal" data-bs-target="#NewConsumer" onclick="addnewclient('${clientName}')"><td class="noselect">اضافة عميل جديد</td></tr>`);
                    $(".quickConsumerSearch").show();
                } else {
                    var itemData = resp[0];
                    $(".quickConsumerSearch").html('');
                    try {
                        itemData.forEach(function (item) {
                            $(".quickConsumerSearch").append(`<tr onclick="saveclient(${item.id},'${item.name}')" class="cqs${item.id}">`);
                            $(".cqs" + item.id).append('<td>' + item.name + '</td>');
                            $(".quickConsumerSearch").append('</tr>');
                        });
                    } catch (error) {
                        $(".quickConsumerSearch").append(`<tr onclick="saveclient(${itemData.id},'${itemData.name}')" class="cqs${itemData.id}">`);
                        $(".cqs" + itemData.id).append('<td>' + itemData.name + '</td>');
                        $(".quickConsumerSearch").append('</tr>');
                    }
                    $(".quickConsumerSearch").append(`<tr data-bs-toggle="modal" data-bs-target="#NewConsumer" onclick="addnewclient('${clientName}')"><td class="noselect">اضافة عميل جديد</td></tr>`);
                    $(".quickConsumerSearch").show();
                }
            });
        } else {
            $(".quickConsumerSearch").html('');
        }
    });

});