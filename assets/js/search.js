var count = 0;
var order = [];
var totalprice;
var barcodeOn = true;
var IQDval = 1;
var discountVal = 0;
var orderID = NaN;
var price_paid = 0;
var totalprice = 0;
var update = false;
var finished = 0;
var autoPrint = true;
var GET;
var UNITES = null;
var searchLimit = 50;
var defaultSearchLimit = 50;

function updateDiscount(newval) {
    $("#discount").val(newval);
    totalPrice();
}

function updateQuantity(e, newval) {
    $("#pq" + e).html(newval);
    order[e][1] = newval;
    $(`#tPQ${e}`).html((parseFloat(newval) * parseFloat(order[e][2])).toLocaleString('en-US'));
    $(`#IQDtp${e}`).html((iqdRound(parseFloat(order[e][2]) * parseFloat(IQDval))).toLocaleString('en-US'));
    totalPrice();
}



function updatePriceValue(e, newval) {
    var formData = { "barcode": order[e][0] };
    $.post("", formData, function (html) {
        if (html != false) {
            try {
                resp = JSON.parse(html);
            } catch (error) {
                alertify.error('حدث خطا من السيرفر');
                return;
            }
            if (resp.ok != true) {
                alertify.error(resp.msg); // Print Error MSG
                return;
            } else {
                var mainval = resp[0].buy_price;
                if (parseFloat(newval) < parseFloat(mainval)) {
                    alertify.error("السعر لازم يكون اكبر او يساوي للسعر الاساسي");
                } else {
                    order[e][2] = newval;
                    $(`#sellPriceForThisItem${e}`).html(parseFloat(newval).toLocaleString('en-US'));
                    $(`#tPQ${e}`).html((parseFloat(newval) * parseFloat(order[e][1])).toLocaleString('en-US'));
                    $(`#IQDtp${e}`).html(iqdRound((parseFloat(order[e][2]) * parseFloat(IQDval))).toLocaleString('en-US'));

                    totalPrice();
                }
            }
        } else {
            alertify.error("ERROR");
            return;
        }
    });
}

function quantityUP(e) {
    var oldval = order[e][1];
    var newval = (parseFloat(oldval) + 1);
    updateQuantity(e, newval);
}

function quantityDN(e) {
    var oldval = order[e][1];
    if (oldval > 1) {
        var newval = (parseFloat(oldval) - 1);
        updateQuantity(e, newval);
    }
}



// barcode - quantity - price - id
function totalPrice() {
    totalprice = 0;
    if (order.length > 0) {
        order.forEach(function (item) {
            totalprice += item[1] * item[2];
        });
    }
}


function setUnites() {
    $.post("", { getUnitesAndClass: 1 }, function (html) {

        if (html != false) {
            try {
                resp = JSON.parse(html);
            } catch (error) {
                alertify.error('حدث خطا من السيرفر');
                return;
            }
            if (resp.ok) {
                UNITES = Array();
                resp[0].unit.forEach(function (unit) {
                    UNITES[unit.unitID] = unit.unitType;
                });
            } else {
                UNITES = null;
            }
        }
    });
}

function fixinProb(itemData, count) {

    //unit
    if (product_data_to_show.unit == 1) {
        if (UNITES != null) {
            $("#itm" + count).append('<td class="border-end border-start">' + UNITES[itemData.unit_id] + '</td>');
        } else {
            $("#itm" + count).append('<td class="border-end border-start">غير معروف</td>');
        }
    }
    //IQD  Trans

    $.post("", { "iqd": 1 }, function (html) {
        if (html != false) {
            try {
                resp = JSON.parse(html);
            } catch (error) {
                alertify.error('حدث خطا من السيرفر');
                return;
            }
            if (resp.ok) {
                IQDval = resp[0].val;
                if (product_data_to_show.iqd == 1)
                    $("#itm" + count).append(`<td id="IQDtp${count}" class="border-end border-start">${(iqdRound(itemData.sell_price * resp[0].val)).toLocaleString('en-US')}</td>`);
                if (product_data_to_show.inv == 1)
                    $("#itm" + count).append('<td class="border-end border-start">' + itemData.box + "-" + itemData.shelf + "-" + itemData.corridor + "-" + itemData.warehouse_name + '</td>');
            } else {
                if (product_data_to_show.iqd == 1)
                    $("#itm" + count).append('<td class="border-end border-start">' + "غير معروف" + '</td>');
                if (product_data_to_show.inv == 1)
                    $("#itm" + count).append('<td class="border-end border-start">' + itemData.box + "-" + itemData.shelf + "-" + itemData.corridor + "-" + itemData.warehouse_name + '</td>');
            }
        }
    });

}


//to add item
function addItem(barcode, add = true, qshid = false) {
    client_id = $("#consumer_id").val();
    if (client_id.length > 0) {
        reqData = { "barcode": barcode, "_client_id": client_id };
    } else {
        reqData = { "barcode": barcode };
    }
    var formData = reqData;
    $.post("", formData, function (html) {
        if (html != false) {
            try {
                resp = JSON.parse(html);
            } catch (error) {
                alertify.error('حدث خطا من السيرفر');
                return;
            }
            if (resp.ok != true) {
                alertify.error(resp.msg); // Print Error MSG
                return;
            } else {
                var itemData = resp[0];
                var quantity;
                if (order.length > 0 && add == true) {
                    arrId = 0;
                    preceed = true;
                    order.forEach(function (item) {
                        if (item[0] == itemData.barcode && item[3] == itemData.id) {
                            quantityUP(arrId);
                            $("#quickSearchItems").hide();
                            $("#quickSearchItems").html('');
                            $("#quick_search").val('');
                            preceed = false;
                        }
                        arrId++;
                    });
                    if (!preceed) {
                        return;
                    }
                }
                if (order.length > count) {
                    quantity = order[count][1];
                } else {
                    quantity = 1;
                }
                $("#items").prepend('<tr id="itm' + count + '">');
                //Delete
                $("#itm" + count).append('<td class="text-right"><button class="btn btn-sm btn-outline-danger btn-round" onclick="removeit(' + count + ')" style="padding: 5px;"> <i class="fa fa-trash"></i></button></td>');
                // $("#itm" + count).append('<th scope="row" width="10%">' + (count + 1) + '</th>');    //itemData.id
                // $("#itm" + count).append('<td>' + itemData.barcode + '</td>');

                //Number
                if (product_data_to_show.number == 1)
                    $("#itm" + count).append(`<td class="border-end border-start">${itemData.number}</td>`);
                //Name
                $(`#itm${count}`).append(`<td  onclick="viewProdectDetails('${barcode}')" data-bs-toggle="modal" data-bs-target="#productDetails" class="border-end border-start">${itemData.name}</td>`);
                //OldQuantity
                // $("#itm" + count).append('<td id="qunt' + itemData.id + '"><div class="m-btn-group m-btn-group--pill btn-group mr-2" role="group" aria-label="..."><button type="button" class="m-btn btn btn-default" onclick="quantityDN(' + count + ')"><i class="fa fa-minus text-danger"></i></button><button type="button" class="m-btn btn btn-default" id="pq' + count + '" disabled>' + quantity + '</button><button type="button" class="m-btn btn btn-default" onclick="quantityUP(' + count + ')"><i class="fa fa-plus text-primary"></i></button></div></td>');
                //NewQuantity
                $("#itm" + count).append(`${`<td class="border-end text-primary border-start" data-bs-toggle="modal" data-bs-target="#numPad" onclick="saveParam('updateQuantity(${count}, newval)')" id="pq` + count}">${quantity}</td>`);
                // $("#itm" + count).append('<td>' + itemData.buy_price + '</td>');
                //Price
                $("#itm" + count).append(`<td class="border-end text-primary border-start sellPriceForThisItem" wholesale_price="${itemData.wholesale_price}" id="sellPriceForThisItem${count}" data-bs-toggle="modal" data-bs-target="#numPad" onclick="saveParam('updatePriceValue(${count}, newval)')">${(itemData.sell_price).toLocaleString('en-US')}</td>`);
                //total price for item
                if (product_data_to_show.tot == 1)
                    $("#itm" + count).append(`<td class="border-end" id="tPQ${count}">${(quantity * itemData.sell_price).toLocaleString('en-US')}</td>`);

                fixinProb(itemData, count);
                // $("#itm" + count).append('<td>' + itemData.wholesale_price + '</td>');
                // $("#itm" + count).append('<td>' + itemData.class_id + '</td>');
                // 
                // 
                $("#items").prepend('</tr>');
                count++;
                if (add) {
                    order.push([itemData.barcode, quantity, itemData.sell_price, itemData.id]);
                    totalPrice();
                }
                if (qshid) {
                    $("#quickSearchItems").hide();
                    $("#quickSearchItems").html('');
                    $("#quick_search").val('');

                }
            }
        } else {
            alertify.error(html);
            return;
        }
    });

}



function loadOrder(_orderid, finishedOnly = true) {
    var Orderitems;
    $.post("", { "getorder": _orderid }, function (html) {
        if (html != false) {
            try {
                resp = JSON.parse(html);
            } catch (error) {
                alertify.error('حدث خطا من السيرفر');
                return;
            }
            if (resp.ok != true) {
                alertify.error(resp.msg); // Print Error MSG
            } else {
                data = ((finishedOnly) ? { getOrderDetails: _orderid } : { getOrderDetailsForAdmin: _orderid });
                $.post("", data, function (OrderDetails) {
                    OrderDetails_resp = JSON.parse(OrderDetails);
                    if (OrderDetails_resp.ok != true) {
                        alertify.error(OrderDetails_resp.msg); // Print Error MSG
                    } else {
                        Orderitems = OrderDetails_resp[0];
                        clear(false);
                        var respdata = resp[0];
                        orderID = _orderid;
                        order = [];
                        try {
                            Orderitems.forEach(function (item) {
                                order[count] = {
                                    0: item.prodect_barcode,
                                    1: item.quantity,
                                    2: item.sold_price,
                                    3: item.product_id
                                };
                                count++;
                                totalPrice();
                            });
                            totalprice = respdata.total_price;
                            discountVal = respdata.discount;
                            price_paid = respdata.price_paid;
                            $("#dateBar").html(respdata.date);
                            if (respdata.consumer_id != 0) {
                                $.post("", { "getconsumer": respdata.consumer_id }, (html) => {
                                    consumerData = JSON.parse(html)[0][0];
                                    $("#sellForConsumerSearchBar").val(consumerData.name);
                                    $("#clientName").html(`العميل : ${consumerData.name}`);
                                });

                                $("#consumer_id").val(respdata.consumer_id);
                            }

                            $("#price_left").val(respdata.price_left);
                            $("#discount").val(respdata.discount);
                            update = true;
                            count = 0;
                            order.forEach(function (item) {
                                addItem(item[0], false);
                            });
                        } catch (error) {
                            alertify.error(`حدث خطا غير متوقع ${error}`)
                        }
                    }
                });
            }
        } else {
            alertify.error("حدث خطا"); // Error
        }
    });

}

function main(barcode) {
    if (barcodeOn == true) {
        addItem(barcode);
    } else {
        alertify.error("جهاز الباركود غير مفعل");
    }
}



function updatePgae() {
    discountVal = $("#discount").val();
    // get iqd nearest price

    iqdTotalAfterRound = iqdRound(Math.trunc((IQDval * totalprice) - discountVal));
    $("#pricetotalIQD").html(iqdTotalAfterRound.toLocaleString('en-US'));
    $("#pricetotalIQDSellPad").html(iqdTotalAfterRound.toLocaleString('en-US'));

    $("#pricetotal").html(totalprice.toLocaleString('en-US'));
    $("#pricetotalSellPad").html(totalprice.toLocaleString('en-US'));

    $("#itemsCount").html(count);
    if ($("#consumer_id").val() == "") {
        $("#debt").attr("disabled", true);
    } else {
        $("#debt").removeAttr("disabled");
    }
    left = totalprice - $("#price_paid").val();
    $("#price_left").val(left);
    $("#thisorderID").html(orderID);
    if (count > 0) {
        $(".sellNow").removeAttr("disabled");
        $("#sellForConBtn").removeAttr("disabled");
        $("#orderhold").removeAttr("disabled");
    } else {
        $(".sellNow").attr("disabled", true);
        $("#sellForConBtn").attr("disabled", true);
        $("#orderhold").attr("disabled", true);
    }

    $("#printPad_date").html("التاريخ<br>" + $("#dateBar").html());
    $("#printPad_client_name").html("العميل<br>" + $("#sellForConsumerSearchBar").val());
    $("#printPad_title").html("brok.");
    $("#printPad_count").html("العدد<br>" + count);
    $("#printPad_orderid").html("الفاتورة<br>" + orderID);
    $("#printPad_discount").html("الخصم<br>" + discountVal);
    $("#printPad_footer").html(`Total Price: ${totalprice}   Price Paid: ${price_paid}   Price Left: ${totalprice - price_paid}`);
}

function barcodeReaderSt() {
    barcodeOn = !barcodeOn;
}
function autoPrintSt() {
    autoPrint = !autoPrint;
}

function getDate() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    return today;
}


function getQueryParams(qs) {
    qs = qs.split('+').join(' ');

    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
    }

    return params;
}

function gndSearchItems(q, client_id, l, deleteOld = true){
    if (client_id.length > 0) {
        reqData = { "search": q, "_client_id": client_id, "limit": l };
    } else {
        reqData = { search: q, "limit": l };
    }
    $.post("", reqData, function (html) {
        try {
            resp = JSON.parse(html);
        } catch (error) {
            return;
        }

        if (resp.ok != true) {
            // alertify.error(resp.msg); // Print Error MSG
        } else {
            var itemData = resp[0];
            if(deleteOld){
                $("#quickSearchItems").html('');
            }
            itemData.forEach(function (item) {
                $("#quickSearchItems").append('<tr onclick="addItem(' + "'" + item.barcode + "'" + ',1,1)" id="qsitm' + item.barcode + '">');
                $("#qsitm" + item.barcode).append(`<td class="noselect">${item.name} ${item.number}</td>`);
                $("#quickSearchItems").append('</tr>');
                $("#quickSearchItems").show();
            });
        //     qitms = $("#quickSearchItems").parent().parent()[0];
        //     qitms.querySelector(`#qsitm${resp[0][0].barcode}`).scrollIntoView({
        //         behavior: 'smooth'
        //    });
        }
    });
}

$(document).ready(function () {
    $("#quickSearchItems").parent().parent().on('scroll', function (event) {
        var element = event.target;
        if (element.scrollHeight - element.scrollTop === element.clientHeight) {
            client_id = $("#consumer_id").val();
            q = $("#quick_search").val();
            if(q.length > 0){
                gndSearchItems(q, client_id, searchLimit, false);
                searchLimit += defaultSearchLimit;
            }
        }
    });

    setUnites();


    GET = getQueryParams(document.location.search);

    if (GET.do == "showOrders") {
        $('#allOrders').modal('toggle');
        try {
            getAllOrders();
        } catch {

        }
    }

    if (GET.ev = 1) {
        try {
            eval(GET.eval);
        } catch {

        }
    }
    //Numpad عرض سعر الجملة في 
    $(document).on('click', '.sellPriceForThisItem', function () {
        CurrentWholesalePrice = $(this).attr("wholesale_price");
        showCurrentWholesalePrice = true;
        sellPriceForThisItem = $(this).attr("id");
    });

    // اضافة سعر الجملة بدل السعر الاساسي
    $(document).on('click', '.whsprice', function () {
        updatePriceValue(sellPriceForThisItem.replace("sellPriceForThisItem", ""), CurrentWholesalePrice);
        // $(`#${sellPriceForThisItem}`).html(CurrentWholesalePrice);
        $('#numPad').modal('toggle');
    });

    updatePgae();
    setInterval(updatePgae, 1);

    //ShowDate
    $("#dateBar").html(getDate());
    function saveTheOrder() {
        if (finished == 1 && autoPrint == true) {
            printThisOrder();
        }
        orderDetails = getThisOrderDetails();
        $.post("", { saveOrder: orderDetails }, function (html) {
            try {
                data = JSON.parse(html);
                if (data.ok = false) {
                    alertify.error(data.msg);
                }
                clear();
            } catch (error) {
                alertify.error(error);
            }
        });
    }
    //clear items
    $("#clear").bind("click", function () {
        clear();
    });

    function sellNow() {
        finished = 1;
        saveTheOrder();
    }
    $(document).on('click', '#debtPadSaveButton', function () {
        price_paid = $("#price_paid").val();
        sellNow();
    });
    $(document).on('click', '#sellNowForNoClient', function () {
        price_paid = totalprice;
        sellNow();
    });
    $(document).on('click', '#sellForConsumerSaveButton', function () {
        price_paid = totalprice;
        sellNow();
    });
    $(document).on('click', '#orderhold', function () {
        finished = 0;
        saveTheOrder();
    });
    $("#quick_search").click(function (e) {
        $("#quickSearchItems").show();
    });
    // var fdata = $("#quickSearchItems").html();
    $("#quick_search").on("input", function () {
        var value = $(this).val().toLowerCase();
        if (value.length > 0) {
            client_id = $("#consumer_id").val();
            gndSearchItems(value, client_id, 0); // Add Search items
            searchLimit = defaultSearchLimit;
        }
        // else {
        //     $("#quickSearchItems").html(fdata);
        // }
    });
});

function getThisOrderDetails() {
    orderDetails = {
        "order_id": orderID,
        "consumer_id": $("#consumer_id").val(),
        "items_count": count,
        "discount": discountVal,
        "price_paid": price_paid,
        "price_left": totalprice - price_paid,
        "total_price": totalprice,
        "isitfinished": finished,
        "update": update,
        "items": order
    };
    return orderDetails;
}


function generateOrederCode() {
    possible = '123456789';
    var text = '';
    for (var i = 0; i < 6; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}

function clear(newOrderId = true) {
    $("#items").html('');
    count = 0;

    discountVal = 0;
    IQDval = 1;
    totalprice = 0;
    totalprice = 0;
    price_paid = 0;
    orderID = NaN;
    update = false;
    finished = 0;
    $("input").val("");
    $("#clientName").html(`العميل : عميل افتراضي`);
    $("#discount").val(0);
    $("#dateBar").html(getDate());
    $("#unFinishedOrdersList").html('');
    $(".prodect-details").html("");
    try {
        $("#allOrdersList").html('');
    } catch (e) {

    }
    if (newOrderId) {
        order = [];
        do {
            newOrderCode = generateOrederCode();
            formData = { "canIuseThisOrder": newOrderCode };
            $.post("", formData, function (html) {
                if (html != false) {
                    resp = JSON.parse(html);
                    if (resp.ok != true) {
                        alertify.error(resp.msg); // Print Error MSG
                    } else {
                        orderID = newOrderCode;
                    }
                } else {

                }
            });
        } while (orderID == NaN);
    }



    totalPrice();

}


//Delete item
function removeit(id) {
    count = 0;
    $("#items").html('');
    order.splice(id, 1);
    //$("#itm" + id).remove();
    order.forEach(function (item) {
        addItem(item[0], false);
    });
    totalPrice();
}





function barcodeReader() {
    //for barcode rader
    var barcode = '';
    var interval;
    document.addEventListener("keydown", function (evt) {
        if (interval)
            clearInterval(interval);
        if (evt.code == "Enter") {
            if (barcode)
                main(barcode);
            barcode = "";
            return;
        }
        if (evt.key != "Shift")
            barcode += evt.key;
        interval = setInterval(() => barcode = "", 900)
    });
}


function printThisOrder() {
    orderDetails = getThisOrderDetails();
    $.post('print.php', { "printOrder": orderDetails }, function (html) {
        try {
            resp = JSON.parse(html);
        } catch (error) {
            alertify.error("حدث خطا من السيرفر");
        }

        if (resp.ok) {
            alertify.success("تم طباعة الطلب");
        } else {
            alertify.error(resp.msg);
        }
    });
}

function deleteThisOrder() {
    alertify.confirm('حذف فاتورة', 'هل انت متاكد من انك تريد حذف الفاتورة بشكل نهائي؟',
        function () {
            $.post("", { deleteOrder: orderID },
                function (data) {
                    try {
                        resp = JSON.parse(data);
                    } catch (error) {
                        alertify.error('حدث خطا من السيرفر');
                        return;
                    }
                    if (resp.ok != true) {
                        alertify.error(resp.msg);
                        clear();
                    } else {
                        alertify.success("تم حذف الفاتورة");
                        clear();
                    }
                }
            );
        },
        function () {
            alertify.error('تم الالغاء')
        }
    );
}


// Find nearset number for IQD
function iqdRound(n) {
    iqdLine = [0, 250, 500, 750, 1000];

    if (n.toString().length >= 3) {
        newVal = n.toString().slice(-3);
        if (newVal > iqdLine[3]) {
            return (n - newVal) + iqdLine[4];
        } else if (newVal > iqdLine[2]) {
            return (n - newVal) + iqdLine[3];
        } else if (newVal > iqdLine[1]) {
            return (n - newVal) + iqdLine[2];
        } else if (newVal > iqdLine[0]) {
            return (n - newVal) + iqdLine[1];
        }
    }
    return n;
}


barcodeReader();
clear();
