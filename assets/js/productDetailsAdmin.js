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
            $(".prodect-details").append(`<div class="row">سعر الشراء : ${itemData.buy_price}</div>`);
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
                $(".prodect-details").append(`<div class="row">الخزن : ${itemData.warehouse_name} - ${itemData.corridor} - ${itemData.shelf} - ${itemData.box}</div>`);
                $(".prodect-details").append(`<div class="row">ملاحظات : ${itemData.note}</div>`);
            });
        }
    }
});

}