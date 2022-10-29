$(document).ready(function(){
    $("#NewConsumerSaveButton").click(function (e) { 
        e.preventDefault();
        $.post("", $("#NewConsumerForm").serialize(), function (data) {
            resp = JSON.parse(data);
            if (resp.ok == false){
                alertify.error(resp.msg);
            }else{
                $("#sellForConsumerSearchBar").val(resp.name);
                $("#consumer_id").val(resp.id);
                $("#quickConsumerSearch").hide();
                $(".quickConsumerSearch").hide();
                alertify.success("تم اضافة عميل بنجاح");
                $("#NewConsumerForm .form-control").val("");
            }
        });
    });
});