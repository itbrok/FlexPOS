$(document).ready(function(){
    $("#login").click(function (e) { 
        $.post("", $("form").serialize(), function (data, textStatus, jqXHR) {
            resp = JSON.parse(data);
            if (resp.ok == false){
                alertify.error(resp.msg)
            }else{
                window.location = "?reload";
            }
        });
    });
});