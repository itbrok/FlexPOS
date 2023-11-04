<script>
    // Prevent reloading
    window.onbeforeunload = function () {
        return "";
    }
    var product_data_to_show = { del: 1, number: 0, name: 1, qty: 1, sell_price: 1, tot: 0, unit: 0, iqd: 0, inv: 1 };
</script>
<div class="h-screen flex-grow-1">
    <!-- Header -->
    <header class="bg-surface-primary border-bottom pt-6">
        <div class="container-fluid">
            <div class="mb-npx">
                <div class="row align-items-center">
                    <div class="col-sm-6 col-12 mb-4 mb-sm-0">
                        <!-- Title -->
                        <h1 class="h2 mb-6 ls-tight">شاشة المبيعات</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main -->
    <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
            <div dir="rtl">
                <script><?= file_get_contents("assets/js/search.js") ?></script>
                <!-- <script src="assets/js/search.js"></script> -->
                <div>
                    <div>
                        <div>
                            <div class="row g-6 mb-6">
                                <!-- Date bar -->
                                <div class="col-xl-3 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <span id="clientName"
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">العميل
                                                        : عميل افتراضي</span>
                                                    <span id="dateBar" class="text-xs mb-0">7/6/2001</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-primary text-white text-lg rounded-circle">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Order ID -->
                                <div class="col-xl-3 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">رقم
                                                        الفاتوره</span>
                                                    <span id="thisorderID" class="text-sm mb-0">0</span>
                                                </div>
                                                <!-- Items Count -->
                                                <div class="col">
                                                    <span class="h6 font-semibold text-muted text-sm d-block mb-2">عدد
                                                        المواد</span>
                                                    <span id="itemsCount" class="text-sm mb-0">0</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-tertiary text-white text-lg rounded-circle">
                                                        <i class="fa fa-hashtag"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Discount -->
                                <div class="col-xl-3 col-sm-6 col-12" data-bs-toggle="modal" data-bs-target="#numPad"
                                    onclick="saveParam('updateDiscount(newval)')">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <span
                                                        class="h6 font-semibold text-muted text-sm d-block mb-2">خصم</span>
                                                    <span class="mb-0"><input dir="rtl" id="discount" type="text"
                                                            style="padding: 0 12px 0 0;"
                                                            class="form-control text-sm shadow-none rounded-pill"
                                                            value="0" disabled></span>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-primary text-white text-lg rounded-circle">
                                                        <i class="fa fa-gift"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Total Price -->
                                <div class="col-xl-3 col-sm-6 col-12">
                                    <div class="card shadow border-0">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row">
                                                        <div class="b text-sm col">دولار</div>
                                                        <span id="pricetotal" class="mb-0 text-sm col">0</span>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="b h3 text-danger col">عراقي</div>
                                                        <span id="pricetotalIQD"
                                                            class="mb-0 h5 text-danger col">0</span>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div
                                                        class="icon icon-shape bg-success text-white text-lg rounded-circle">
                                                        <i class="fa fa-dollar-sign"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- box.// -->
                    </div>
                    <div class="row" style="margin-left: 0 !important;">

                        
                        <div class="row">
                            <div class="col card me-2" style="max-width: 22%;min-height: 340px;margin-left: 9px;margin-left: 9;">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item ms-4" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab"
                                            data-bs-target="#q_search" type="button" role="tab"
                                            aria-selected="true">المواد</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#_client" type="button" role="tab"
                                            aria-selected="false">العميل</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="q_search"
                                        role="tabpanel">
                                        <input type="search"
                                            style="padding-right: 2.25rem !important;"
                                            class="form-control form-control-sm form-control-sm mt-1 p-2 text-sm font-semibold shadow-none rounded-pill"
                                            id="quick_search" placeholder="بحث" autocomplete="off">
                                        <div class="row">
                                            <div style="overflow: auto; max-height: 260px;">
                                                <table class="table table-striped table-hover">
                                                    <tbody id="quickSearchItems"
                                                        style="max-width: 19%;">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="_client" role="tabpanel">
                                        <div dir="rtl">
                                            <div>
                                                <input type="search"
                                                    style="padding-right: 2.25rem !important;"
                                                    class="form-control form-control-sm form-control-sm mt-1 p-2 text-sm font-semibold shadow-none rounded-pill sellForConsumerSearchBar"
                                                    placeholder="الاسم" autocomplete="off">
                                                <div class="row">
                                                    <div style="overflow: auto; max-height: 200px;">
                                                        <table
                                                            class="table table-striped table-hover">
                                                            <tbody class="quickConsumerSearch">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <span class="col card" id="cart"
                                style="overflow: auto; min-height: 340px; max-height: 340px;">
                                <table id="orderTable"
                                    class="table text-center table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="7%" scope="col">حذف</th>
                                            <th scope="col">المادة</th>
                                            <th scope="col">الكمية</th>
                                            <th scope="col">سعر البيع</th>
                                            <th scope="col">المخزن</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items">
                                    </tbody>
                                </table>
                            </span>
                        </div>
                                        
                </div>
                <div class="mt-3">
                    <div class="form-check form-switch ms-2" style="display: inline-block;">
                        <input class="form-check-input" onclick="autoPrintSt()" type="checkbox" role="switch"
                            id="autoPrint" checked>
                        <label class="text-sm me-2 ms-2" for="autoPrint">طباعة تلقائيه</label>
                    </div>
                    <div class="pb-3 form-check form-switch me-2" style="display: inline-flex;">
                        <label for="addByBarocde" class="text-sm me-2 ms-2">جهاز الباركود</label>
                        <input id="addByBarocde" class="form-check-input" type="checkbox" role="switch"
                            onchange="barcodeReaderSt()" checked>
                    </div>
                    <div class="mt-3">
                        <button id="sellNowForNoClient"
                            class="me-3 text-xs btn btn-sm paidequaltotal sellNow btn-primary btn-round btn-block"><i
                                class="fa fa-shopping-bag"></i> بيع</button>

                        <!--button id="sellForConBtn"
                            class="me-3 text-xs btn btn-sm btn-outline-success btn-round btn-block"
                            data-bs-toggle="modal" data-bs-target="#sellForConsumer"><i class="fa fa-user"></i> بيع
                            لعميل </button-->
                        <button type="button" id="debt" class="me-3 text-xs btn btn-sm btn-danger btn-round btn-block"
                                data-bs-toggle="modal" data-bs-target="#debtPad" onclick="$('#price_paid').focus();">ذمة</button>
                        <button id="orderhold" class="me-3 text-xs btn btn-sm btn-secondary btn-round btn-block"><i
                                class="far fa-clock"></i> تعليق</button>

                        <button id="unFinishedOrdersBtn"
                            class="me-3 text-xs btn btn-sm btn-outline-dark btn-round btn-block"><i
                                class="fas fa-list"></i> عرض المعلق</button>

                        <!--button data-bs-toggle="modal" data-bs-target="#payDebt"
                            class="me-3 text-xs btn btn-sm btn-danger btn-round btn-block"><i
                                class="fa fa-file-invoice-dollar"></i> تسديد فاتوره </button-->

                        <button id="clear" class="me-3 text-xs btn btn-sm btn-outline-danger btn-round btn-block"><i
                                class="fa fa-times-circle"></i> الغاء</button>

                        <button onclick="printThisOrder()" class="me-3 text-xs btn btn-sm btn-success btn-round"><i
                                class="fa fa-print"></i>
                            طباعة</button>

                        <button id="allOrdersBtn"
                            class="me-3 text-xs btn btn-sm btn-outline-dark btn-round btn-block"><i
                                class="fas fa-list"></i> عرض الفواتير</button>
                        <button onclick="deleteThisOrder()" class="me-3 text-xs btn btn-sm btn-danger btn-round"><i
                                class="fa fa-trash"></i>
                            حذف</button>
                    </div>
                </div>
                <!-- تسديد ديون -->
                <!-- <script src="assets\js\payDebt.js"></script> -->
                <script><?= file_get_contents("assets\js\payDebt.js") ?></script>
                <div class="modal fade" id="payDebt" dir="ltr" tabindex="-1" aria-labelledby="payDebtLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content" style="background-color: #ededed;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="payDebtLabel">تسديد فاتورة</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body col" dir="ltr">
                                <div dir="rtl">
                                    <div class="form-floating mb-3">
                                        <input type="hidden" id="payDebtconsumer_id" name="consumer_id">
                                        <input type="text" class="form-control" name="name" id="payDebtSearchBar"
                                            placeholder="الاسم" autocomplete="off" required>
                                        <label for="payDebtSearchBar">الاسم</label>
                                        <div class="row">
                                            <div style="overflow: auto; max-height: 200px;">
                                                <table class="table table-striped table-hover">
                                                    <tbody id="payDebtSearch">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3 payDebtprice_paid" onclick="saveParam('updateDebtPricePaid(newval)')">
                                        <input type="text" class="form-control form-control-sm bg-white"
                                            name="price_paid" id="payDebtprice_paid"
                                            placeholder="المبلغ المدفوع (دولار)" autocomplete="off">
                                        <label for="payDebtprice_paid">المبلغ المدفوع (دولار)</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="price_left" id="payDebtprice_left"
                                            placeholder="المتبقي (دولار)" autocomplete="off" disabled>
                                        <label for="payDebtprice_left">المتبقي (دولار)</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="payDebtCloseButton btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">اغلاق</button>
                                    <button type="button" id="payDebtSaveButton" class="btn btn-primary"
                                        data-bs-dismiss="modal" disabled>موافق</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- بيع لعميل  -->
                <script><?= file_get_contents("assets/js/sellForConsumer.js") ?></script>
                <!-- <script src="assets/js/sellForConsumer.js"></script> -->
                <div class="modal fade" id="sellForConsumer" dir="ltr" tabindex="-1"
                    aria-labelledby="sellForConsumerLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content" style="background-color: #ededed;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sellForConsumerLabel">بيع لعميل</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="col me-1 ms-2" dir="rtl">
                                <div class="row m-3">
                                    <div class="b col">دولار</div>
                                    <div id="pricetotalSellPad" class="b col">0</div>
                                </div>
                                <div class="text-danger m-3 row">
                                    <div class="b h3 col">عراقي</div>
                                    <div id="pricetotalIQDSellPad" class="b h3 col">0</div>
                                </div>
                            </div>
                            <div class="modal-body col">
                                <div dir="rtl">
                                    <div class="form-floating mb-3" id="sellForConsumerForm">
                                        <input type="hidden" id="consumer_id" name="consumer_id">
                                        <input type="text" class="form-control form-control-sm sellForConsumerSearchBar"
                                            name="name" id="sellForConsumerSearchBar" placeholder="الاسم"
                                            autocomplete="off" required>
                                        <label for="sellForConsumerSearchBar">الاسم</label>
                                        <div class="row">
                                            <div style="overflow: auto; max-height: 200px;">
                                                <table class="table table-striped table-hover">
                                                    <tbody class="quickConsumerSearch">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="sellForConsumerCloseButton btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">اغلاق</button>
                                    <button type="button" id="debt" class="btn btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#debtPad" onclick="$('#price_paid').focus();">ذمة</button>
                                    <button type="button" id="sellForConsumerSaveButton" data-bs-dismiss="modal"
                                        class="btn paidequaltotal sellNow btn-primary">بيع</button>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>

                <!-- اضافة عميل جديد -->
                <!-- <script src="assets/js/NewConsumer.js"></script> -->
                <script><?= file_get_contents("assets/js/NewConsumer.js") ?></script>
                <div class="modal fade" id="NewConsumer" dir="ltr" tabindex="-1" aria-labelledby="NewConsumerLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content" style="background-color: #ededed;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="NewConsumerLabel">اضافة عميل جديد</h5>
                                <button type="button" class="NewConsumerCloseButton btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body col">
                                <form id="NewConsumerForm">
                                    <div dir="rtl">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="name" id="NewConsumerNameBar"
                                                placeholder="الاسم" required autocomplete="off">
                                            <label for="NewConsumerNameBar">الاسم</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="tel" class="form-control" pattern="[0]{1}[7]{1}[0-9]{9}"
                                                name="phone_number" id="floatingphone_number" placeholder="رقم هاتف"
                                                required autocomplete="off">
                                            <label for="floatingphone_number">رقم هاتف</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="address" id="floatingaddress"
                                                placeholder="العنوان" required autocomplete="off">
                                            <label for="floatingaddress">العنوان</label>
                                        </div>
                                        <div class="form-floating">
                                            <textarea name="notes" class="form-control" placeholder="ملاحظات"
                                                id="floatingTextarea" autocomplete="off"></textarea>
                                            <label for="floatingTextarea">ملاحظات</label>
                                        </div>
                                        <input type="hidden" name="newconsumer" value="1">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="NewConsumerCloseButton btn btn-sm btn-secondary"
                                            onclick="showsellpad()" data-bs-dismiss="modal">اغلاق</button>
                                        <button type="submit" id="NewConsumerSaveButton"
                                            class="btn btn-primary">حفظ</button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>


                <!-- لوحة ذمة  -->
                <!-- <script src="assets/js/debtPad.js"></script> -->
                <script><?= file_get_contents("assets/js/debtPad.js") ?></script>
                <div class="modal fade" id="debtPad" dir="ltr" tabindex="-1" aria-labelledby="debtPadLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content" style="background-color: #ededed;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="debtPadLabel">ذمة</h5>
                                <button type="button" class="debtPadCloseButton btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body col">
                                <div dir="rtl">
                                    <div class="form-floating mb-3 price_paid" onclick="saveParam('updatePricePaid(newval)')">
                                        <input type="text" class="form-control form-control-sm bg-white"
                                            name="price_paid" id="price_paid" placeholder="المبلغ المدفوع (دولار)"
                                            autocomplete="off">
                                        <label for="price_paid">المبلغ المدفوع (دولار)</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="price_left" id="price_left"
                                            placeholder="المتبقي (دولار)" autocomplete="off" disabled>
                                        <label for="price_left">المتبقي (دولار)</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="debtPadCloseButton btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">اغلاق</button>
                                    <button type="button" id="debtPadSaveButton"
                                        class="btn paidwithleft sellNow btn-primary"
                                        data-bs-dismiss="modal">موافق</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- فواتير معلقه  -->
                <!-- <script src="assets/js/unFinishedOrders.js"></script> -->
                <script><?= file_get_contents("assets/js/unFinishedOrders.js") ?></script>
                <div class="modal fade" id="unFinishedOrders" dir="ltr" tabindex="-1"
                    aria-labelledby="unFinishedOrdersLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="background-color: #ededed;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="unFinishedOrdersLabel">فواتير معلقه</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body col">
                                <div class="row">
                                    <div style="overflow: auto; max-height: 300px;">
                                        <table class="table table-striped table-hover" dir="rtl">
                                            <thead>
                                                <tr>
                                                    <th>رقم الفاتوره</th>
                                                    <th>عدد المواد</th>
                                                    <th>الخصم</th>
                                                    <th>المجموع الكلي</th>
                                                    <th>العميل</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody id="unFinishedOrdersList">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- عرض جميع الفواتير -->
                <!-- <script src="assets/js/allOrders.js"></script> -->
                <script><?= file_get_contents("assets/js/allOrders.js") ?></script>
                <div class="modal fade" id="allOrders" dir="ltr" tabindex="-1" aria-labelledby="allOrdersLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="background-color: #ededed;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="allOrdersLabel">فواتير</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body col">
                                <div class="row">
                                    <div style="overflow: auto; max-height: 300px;">
                                        <table class="table table-striped table-hover" dir="rtl">
                                            <thead>
                                                <tr>
                                                    <th>رقم الفاتوره</th>
                                                    <th>عدد المواد</th>
                                                    <th>الخصم</th>
                                                    <th>المجموع الكلي</th>
                                                    <th>العميل</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody id="allOrdersList">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer border-0 py-5" dir="rtl">
                                        <a class="btn-sm btn-dark border-0 shadow-none gradient-bottom-right start-teal-500 end-blue-500 start-pink-500-hover end-cyan-500-hover"
                                            id="back"><i class='col fa fa-angle-right'></i></a>
                                        <a class="btn-sm btn-dark border-0 shadow-none gradient-bottom-right start-teal-500 end-blue-500 start-pink-500-hover end-cyan-500-hover"
                                            id="next"><i class='col fa fa-angle-left'></i></a>
                                        <span id="resCount" class="text-muted text-sm"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Details -->
                <!-- <script src="assets\js\productDetailsAdmin.js"></script> -->
                <script><?= file_get_contents("assets/js/productDetailsAdmin.js") ?></script>
                <div class="modal fade" id="productDetails" dir="ltr" tabindex="-1"
                    aria-labelledby="productDetailsLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="background-color: #ededed;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productDetailsLabel">تفاصيل المنتج</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body col" dir="rtl">
                                <div class="row">
                                    <div class="col">
                                        <img id="product_img" src="" class="rounded mx-auto mb-3 d-block" alt="">
                                    </div>
                                    <div class="col prodect-details">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- NumPad -->
                <div class="modal fade" dir="ltr" id="numPad" tabindex="-1" aria-labelledby="numPadLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content" style="background-color: #ededed;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="numPadLabel">لوحة رقمية</h5>
                                <button type="button" class="numpadCloseButton btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body col">
                                <div>
                                    <div class="m-3" dir="rtl">
                                        <span style="display: none;" class="h5 whsprice"></span>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control form-control-sm bg-white"
                                                id="numpadResult" aria-describedby="back-Space" disabled
                                                autocomplete="off">
                                            <span class="input-group-text text-danger" id="numpadResultClear"><i
                                                    class="fa fa-times-circle"></i></span>
                                            <span class="input-group-text" id="back-Space"><i
                                                    class="fa fa-backspace"></i></span>
                                        </div>
                                    </div>
                                    <div class="g-6 numpad">
                                        <div class="row g-6 mb-2">
                                            <button class="form-control form-control-sm col h5 p-4" val="1">1</button>
                                            <button class="form-control form-control-sm col h5 p-4 me-1 ms-1"
                                                val="2">2</button>
                                            <button class="form-control form-control-sm col h5 p-4" val="3">3</button>
                                        </div>
                                        <div class="row g-6 mb-2">
                                            <button class="form-control form-control-sm col h5 p-4" val="4">4</button>
                                            <button class="form-control form-control-sm col h5 p-4 me-1 ms-1"
                                                val="5">5</button>
                                            <button class="form-control form-control-sm col h5 p-4" val="6">6</button>
                                        </div>
                                        <div class="row g-6 mb-2">
                                            <button class="form-control form-control-sm col h5 p-4" val="7">7</button>
                                            <button class="form-control form-control-sm col h5 p-4 me-1 ms-1"
                                                val="8">8</button>
                                            <button class="form-control form-control-sm col h5 p-4" val="9">9</button>
                                        </div>
                                        <div class="row g-6 mb-2">
                                            <button class="form-control form-control-sm col h5 p-3 me-1"
                                                val="0">0</button>
                                            <button class="form-control form-control-sm col h5 p-3 ms-1"
                                                val=".">.</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="numpadCloseButton btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">اغلاق</button>
                                    <button type="button" id="backToDebt" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#debtPad">حفظ</button>
                                    <button type="button" id="numpadSaveButton" class="btn btn-primary"
                                        data-bs-dismiss="modal">حفظ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <script src="assets/js/numpad.js"></script> -->
                <script><?= file_get_contents("assets/js/numpad.js") ?></script>
            </div>
        </div>
    </main>
</div>