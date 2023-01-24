function checkPassWord(){
            
    $.post("", {"getwholesale_price":{"password":$("#whpPass").val(), "barcode":$("#checkPass").attr("barcode")}}, function(html) {
        
        if(html != false){
            resp = JSON.parse(html);
            if(resp.ok != true){
                alertify.error(resp.msg); // Print Error MSG
            }else{
                var itemData = resp[0];
                alertify.success(`سعر الشراء: ${itemData}`)
            }
        }
    });
}
function viewProdectDetails(barcode){
var formData = {"barcode":barcode};
$.post("", formData, function(html) {
    if(html != false){
        resp = JSON.parse(html);
        if(resp.ok != true){
            alertify.error(resp.msg); // Print Error MSG
        }else{
            var itemData = resp[0];
            $(".prodect-details").html("");
            $(".prodect-details").append(`<div class="row">اسم المنتج : ${itemData.name}</div>`);
            $(".prodect-details").append(`<div class="row">الرقم : ${itemData.number}</div>`);
            $(".prodect-details").append(`<div class="row">العدد بالمخزن : ${itemData.quantity}</div>`);
            $(".prodect-details").append(`<div class="row">سعر البيع : ${itemData.sell_price}</div>`);
            $(".prodect-details").append(`<div class="row">سعر الجملة : ${itemData.wholesale_price}</div>`);
            if(itemData.img.length > 12){
                $("#product_img").attr("src", itemData.img);
            }else{
                $.post("", {"defult_product_img":1}, function(d) {
                    if(html != false){
                        img = JSON.parse(d);
                        if(img.ok != true){
                            alertify.error(img.msg); // Print Error MSG
                        }else{
                            $("#product_img").attr("src", img[0]);
                        }
                    }
                });
            }
            
            //class
            $.post("", {"getclass":itemData.class_id}, function(html) {
                if(html != false){
                    resp = JSON.parse(html);
                    if(resp.ok){
                        $(".prodect-details").append(`<div class="row">النوع : ${resp[0]}</div>`);
                    }else{
                        $(".prodect-details").append(`<div class="row">النوع : غير معروف</div>`);
                    }
                }
            });
            //unit
            $.post("", {"unit":itemData.unit_id}, function(html) {
                if(html != false){
                    resp = JSON.parse(html);
                    if(resp.ok){
                        $(".prodect-details").append(`<div class="row">الوحدة : ${resp[0]}</div>`);
                    }else{
                        $(".prodect-details").append(`<div class="row">الوحدة : غير معروف</div>`);
                    }
                }
                $(".prodect-details").append(`<div class="row">الخزن : ${itemData.box} - ${itemData.shelf} - ${itemData.corridor} - ${itemData.warehouse_name}</div>`);
                $(".prodect-details").append(`<div class="row">سعر الشراء : <div class="input-group mb-3" style="max-width: 50%;" dir="ltr"><input dir="rtl" id="whpPass" type="password" placeholder="كلمة المرور" class="form-control" aria-describedby="checkPass" autocomplete="off"><span class="btn-primary input-group-text" onClick="checkPassWord()" barcode="${barcode}" id="checkPass"><i class="fas fa-check text-primary col"></i></span></div></div>`);
                $(".prodect-details").append(`<div class="row">ملاحظات : ${itemData.note}</div>`);
            });
        }
    }
});

}