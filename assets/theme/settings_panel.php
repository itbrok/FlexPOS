
<script>
    <?php
        $sizes = array_keys(json_decode(getSittings("papers"),true));
        foreach($sizes as $i => $s){
            $arr[$i] = "<div class=\"form-check\"><input onClick=\"selectSize('$s')\" class=\"form-check-input\" type='radio' id='$i$s' name='size'><label class='form-check-label' for='$i$s'>$s</label></div>";
        }

        $papers = getSittings("papers");
    ?>
    var sizes = `<form><?php echo implode('',$arr); ?></form>`
    papers = <?= $papers ?>;
</script>
<script src="assets\richtexteditor\jquery.richtext.min.js"></script>
<link rel="stylesheet" href="assets\richtexteditor\richtext.min.css">

<div class="h-screen flex-grow-1">
    <!-- Header -->
    <header class="bg-surface-primary border-bottom pt-6">
        <div class="container-fluid">
            <div class="mb-npx">
                <div class="row align-items-center">
                    <div class="col-sm-6 col-12 mb-4 mb-sm-0">
                        <!-- Title -->
                        <h1 class="h2 mb-6 ls-tight">الاعدادات</h1>
                    </div>
                    <!-- Actions -->
                    <div class="col-sm-6 col-12 text-sm-end">
                        <div class="mx-n1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main -->
    <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
            <div class="card shadow border-0 mb-7">
                <div class="card-header" style="border-bottom: 0 !important;">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item ms-4" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="true">عام</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#printers" type="button" role="tab" aria-selected="false">الطابعات</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#papers" type="button" role="tab" aria-selected="false">الاوراق</button>
                        </li>
                    </ul>
                </div>
                <div class="table-responsive">

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="row justify-content-center">
                                <table class="col-sm-6 col-md-6">
                                    <thead>
                                        <tr>
                                            <th width="50%">
                                            </th>
                                            <th>

                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="mb-3">
                                            <td><span>الاصدار</span></td>
                                            <td><span class="mb-3"><?php echo $flex["version"]; ?></span></td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><span>كلمة المرور لهذا الحساب</span></td>
                                            <td><input class="form-control form-control-sm mb-3" id="change_my_password" type="password" value="just some text here"></td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><span>رمز سعر الجملة</span></td>
                                            <td><input class="form-control form-control-sm mb-3" id="change_wholesale_password" type="password" value="just some text here"></td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><span>سعر صرف الدولار</span></td>
                                            <td><input class="form-control form-control-sm mb-3" id="change_iqd_value" type="text" value="<?= getIQD()['val'] ?>"></td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><span>الصورة الافتراضية للمنتجات</span></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row">
                                                            <img class="img-fluid rounded d-block" id="product_img">
                                                        </div>
                                                        <div class="row">
                                                            <input class="form-control form-control-sm" type="file" id="imgInput" accept="image/*">
                                                            <canvas height="300" width="400" style="display: none;" id="product_canvas">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><span>Logo Small</span></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row">
                                                            <img src="<?= getSittings("logo_small") ?? getSittings("def_logo") ?>" class="img-fluid rounded d-block" id="logo_small">
                                                        </div>
                                                        <div class="row">
                                                            <input class="form-control form-control-sm logoUpdate" targetImg="logo_small" size="small" type="file" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><span>Logo Large</span></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row">
                                                            <img src="<?= getSittings("logo_large") ?? getSittings("def_logo") ?>" class="img-fluid rounded d-block" id="logo_large">
                                                        </div>
                                                        <div class="row">
                                                            <input class="form-control form-control-sm logoUpdate" targetImg="logo_large" size="large" type="file" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><span>التحديثات</span></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col">
                                                        <button id="checkupdate" class="btn btn-sm btn-secondary form-control">فحص</button>
                                                    </div>
                                                    <div class="col">
                                                        <button id="updatenow" class="btn btn-sm btn-primary form-control" style="display: none;">التحديث الان</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><a href="?p=user&do=view&id=<?php echo $_SESSION["user_id"]; ?>" class="btn btn-sm btn-outline-primary mb-3">حسابي</a></td>
                                        </tr>
                                        <tr class="mb-3">
                                            <td><a href="?logout=1" class="btn btn-sm btn-outline-danger">تسجيل الخروج</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" name="defult_product_img" id="defult_product_img">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="printers" role="tabpanel">
                            <div class="row justify-content-center">
                                <div class="col-sm-6 col-md-6">
                                    <table class="table table-hover table-nowrap">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الطابعة</th>
                                                <th>الحجم</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="printerslist">
                                        </tbody>
                                    </table>

                                    <div class="card-footer border-0 py-5">
                                        <button id="updateprinters" class="btn col btn-sm btn-outline-danger">تحديث القائمة <i class="fa-solid fa-rotate"></i></button>
                                        <span id="resCount" class="text-muted text-sm"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="papers" role="tabpanel">
                            <div class="row justify-content-center">
                                <div class="col-sm-6 col-md-6">
                                    <table class="col-sm-6 col-md-6">
                                        <thead>
                                            <tr>
                                                <th width="50%">
                                                </th>
                                                <th>

                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="mb-3">
                                                <td><h2>حجم 77</h2></td>
                                            </tr>
                                            <tr class="mb-3">
                                                <td><span>عدد المواد الاقصى لكل ورقة</span></td>
                                                <td><input class="form-control form-control-sm mb-3" id="77_max_items_count" type="number" value="30"></td>
                                            </tr>
                                            <tr class="mb-3">
                                                <td><span>الاسعار</span></td>
                                                <td>
                                                    <div class="form-check"><input onclick="changePaper('77','iqd',true)" class="form-check-input" name="77iqd" type="radio" id="77iqdtrue"><label class='form-check-label' for='77iqdtrue'>عراقي</label></div>
                                                    <div class="form-check"><input onclick="changePaper('77','iqd',false)" class="form-check-input" name="77iqd" type="radio" id="77iqdfalse"><label class='form-check-label' for='77iqdfalse'>دولار</label></div>
                                                </td>
                                            </tr>
                                            <tr class="mb-3">
                                                <td><h2>حجم A4</h2></td>
                                            </tr>
                                            <tr class="mb-3">
                                                <td><span>عدد المواد الاقصى لكل ورقة</span></td>
                                                <td><input class="form-control form-control-sm mb-3" id="A4_max_items_count" type="number" value="32"></td>
                                            </tr>
                                            <tr class="mb-3">
                                                <td><span>الاسعار</span></td>
                                                <td>
                                                    <div class="form-check"><input onclick="changePaper('A4','iqd',true)" class="form-check-input" name="A4iqd" type="radio" id="A4iqdtrue"><label class='form-check-label' for='A4iqdtrue'>عراقي</label></div>
                                                    <div class="form-check"><input onclick="changePaper('A4','iqd',false)" class="form-check-input" name="A4iqd" type="radio" id="A4iqdfalse"><label class='form-check-label' for='A4iqdfalse'>دولار</label></div>
                                                </td>
                                            </tr>
                                            <tr class="mb-3">
                                                <td><span>الفوتر</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <textarea id="footer_text"></textarea>
                                    <div class="card-footer border-0 py-5">
                                    <button onclick="savePapers()" class="btn col btn-sm btn-outline-primary">حفظ <i class="fa-solid fa-rotate"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- Tabs end -->
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="updateiFrame" dir="ltr" tabindex="-1" aria-labelledby="updateiFrameLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background-color: #ededed;">
            <div class="modal-header">
                <h5 class="modal-title" id="updateiFrameLabel">تحديث التطبيق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body col" dir="rtl">
                <iframe id="updateFrame" src="" width="100%"></iframe>
            </div>
        </div>
    </div>
</div>



<script>

    function changePaper(size, sub, val){
        papers[size][sub] = val;
    }

    function savePapers(){

        changePaper("A4",'@footer_text',$('#footer_text').val());
        changePaper("77",'@footer_text',$('#footer_text').val());
        
        changePaper("A4",'@max_items_count',$('#A4_max_items_count').val());
        changePaper("77",'@max_items_count',$('#77_max_items_count').val());
        
        $.post("", {
            update_papers: papers
        }, function(html) {
            try {
                resp = JSON.parse(html);
            } catch (error) {
                alertify.error('حدث خطا من السيرفر');
                return;
            }
            if (resp.ok == false) {
                alertify.error(resp.msg);
            } else {
                alertify.success("تم حفظ اعدادات الاوراق");
            }
        });
    }

    limit = 1;
    max = 0;
    // Get List of printers
    function getprinters() {
        $.post("", {
            getprintersList: limit
        }, function(html) {
            try {
                resp = JSON.parse(html);
            } catch (error) {
                alertify.error('حدث خطا من السيرفر');
                return;
            }
            if (resp.ok != true) {
                alertify.error(resp.msg);
            } else {
                $("#printerslist").html('');
                var itemData = resp[0];
                try {
                    itemData.forEach(function(item) {
                        $("#printerslist").append(`<tr id="printer${item.id}">`);
                        $("#printer" + item.id).append(`<td>${item.id}</td>`);
                        $("#printer" + item.id).append(`<td>${item.PrinterName}</td>`);
                        $("#printer" + item.id).append(`<td>${item.size}</td>`);
                        $("#printer" + item.id).append(`<td class="text-end"><button class="btn btn-sm btn-square btn-neutral ms-1 text-danger" onclick="deleteit('printer',${item.id},'#printer${item.id}')"> <i class="fa fa-trash"></i></button><a href="javascript:changeSize(${item.id},'${item.PrinterName}')" class="btn btn-sm btn-square btn-neutral text-danger-hover"><i class="fa fa-pen"></i></a></td>`);
                        $("#printerslist").append('</tr>');
                    });
                    $("#resCount").html(`عرض ${itemData.length} نتيجة`);
                    max = itemData[0].resCount;
                } catch (error) {
                    alertify.error(error);
                }
            }
        });
    }

    function drawImageScaled(img, ctx) {
        var canvas = ctx.canvas;
        var hRatio = canvas.width / img.width;
        var vRatio = canvas.height / img.height;
        var ratio = Math.min(hRatio, vRatio);
        var centerShift_x = (canvas.width - img.width * ratio) / 2;
        var centerShift_y = (canvas.height - img.height * ratio) / 2;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, img.width, img.height, centerShift_x, centerShift_y, img.width * ratio, img.height * ratio);
        return canvas.toDataURL();
    }

    function updateProductImg(url, todb = true) {
        canvas = $('#product_canvas');
        ctx = canvas[0].getContext('2d');
        img = new Image;
        img.onload = function() {
            data = drawImageScaled(img, ctx);
            $('#product_img')[0].src = data;
            if (todb) {
                $.post("", {
                    update_defult_product_img: data
                }, function(html) {
                    try {
                        resp = JSON.parse(html);
                    } catch (error) {
                        alertify.error('حدث خطا من السيرفر');
                        return;
                    }
                    if (resp.ok == false) {
                        alertify.error(resp.msg);
                    } else {
                        alertify.success("تم تحديث الصورة الافتراضية للمنتجات");
                    }
                });
            }
        }
        img.src = url;
    }
    selectedSize="A4";
    function selectSize(a){
        selectedSize=a;
    }

    function changeSize(a,b){
        alertify.confirm(b,"",
                function () {
                    $.post("", { updateprintersize: a, size: selectedSize},
                        function (data, textStatus, jqXHR) {
                            try {
                                resp = JSON.parse(data);
                            } catch (error) {
                                alertify.error('حدث خطا من السيرفر');
                                return;
                            }
                            if (resp.ok != true) {
                                alertify.error(resp.msg);
                            } else {
                                limit=1;
                                max=0;
                                getprinters();
                                alertify.success("تم تحديث القائمة");
                            }
                        }
                    );
                },
                function () {
                    alertify.error('تم الالغاء')
                }
       ).setContent(sizes).showModal();
    }

    $(document).ready(function() {
        //Set papers
        $("#77_max_items_count").val(papers["77"]["@max_items_count"]);
        $("#A4_max_items_count").val(papers["A4"]["@max_items_count"]);
        if(papers["77"]["iqd"] == true){
            $("#77iqdtrue").attr("checked",true);
        }else{
            $("#77iqdfalse").attr("checked",true);
        }
        
        if(papers["A4"]["iqd"] == true){
            $("#A4iqdtrue").attr("checked",true);
        }else{
            $("#A4iqdfalse").attr("checked",true);
        }

        $('#footer_text').val(papers["A4"]["@footer_text"]);

        //Set richText Editor
        $('#footer_text').richText({

        // text formatting
        bold: true,
        italic: true,
        underline: true,

        // text alignment
        leftAlign: true,
        centerAlign: true,
        rightAlign: true,
        justify: true,

        // lists
        ol: true,
        ul: true,

        // title
        heading: true,

        // fonts
        fonts: true,
        fontList: ["Arial",
        "Arial Black",
        "Comic Sans MS",
        "Courier New",
        "Geneva",
        "Georgia",
        "Helvetica",
        "Impact",
        "Lucida Console",
        "Tahoma",
        "Times New Roman",
        "Verdana"
        ],
        fontColor: true,
        fontSize: true,

        // uploads
        imageUpload: true,
        fileUpload: false,

        // media
        Embed: false,

        // link
        urls: false,

        // tables
        table: true,

        // code
        removeStyles: true,
        code: true,

        // colors
        colors: [],

        // dropdowns
        fileHTML: '',
        imageHTML: '',

        // translations
        translations: {
        'title': 'عنوان',
        'white': 'White',
        'black': 'Black',
        'brown': 'Brown',
        'beige': 'Beige',
        'darkBlue': 'Dark Blue',
        'blue': 'Blue',
        'lightBlue': 'Light Blue',
        'darkRed': 'Dark Red',
        'red': 'Red',
        'darkGreen': 'Dark Green',
        'green': 'Green',
        'purple': 'Purple',
        'darkTurquois': 'Dark Turquois',
        'turquois': 'Turquois',
        'darkOrange': 'Dark Orange',
        'orange': 'Orange',
        'yellow': 'Yellow',
        'imageURL': 'Image URL',
        'fileURL': 'File URL',
        'linkText': 'Link text',
        'url': 'URL',
        'size': 'Size',
        'responsive': '<a href="https://www.jqueryscript.net/tags.php?/Responsive/">Responsive</a>',
        'text': 'Text',
        'openIn': 'Open in',
        'sameTab': 'Same tab',
        'newTab': 'New tab',
        'align': 'Align',
        'left': 'Left',
        'justify': 'Justify',
        'center': 'Center',
        'right': 'Right',
        'rows': 'صفوف',
        'columns': 'عواميد',
        'add': 'Add',
        'pleaseEnterURL': 'Please enter an URL',
        'videoURLnotSupported': 'Video URL not supported',
        'pleaseSelectImage': 'Please select an image',
        'pleaseSelectFile': 'Please select a file',
        'bold': 'Bold',
        'italic': 'Italic',
        'underline': 'Underline',
        'alignLeft': 'Align left',
        'alignCenter': 'Align centered',
        'alignRight': 'Align right',
        'addOrderedList': 'Add ordered list',
        'addUnorderedList': 'Add unordered list',
        'addHeading': 'Add Heading/title',
        'addFont': 'Add font',
        'addFontColor': 'Add font color',
        'addFontSize': 'Add font size',
        'addImage': 'Add image',
        'addVideo': 'Add video',
        'addFile': 'Add file',
        'addURL': 'Add URL',
        'addTable': 'Add table',
        'removeStyles': 'Remove styles',
        'code': 'Show HTML code',
        'undo': 'Undo',
        'redo': 'Redo',
        'close': 'Close'
        },

        // privacy
        youtubeCookies: false,

        // preview
        preview: false,

        // placeholder
        placeholder: '',

        // dev settings
        useSingleQuotes: false,
        height: 0,
        heightPercentage: 0,
        id: "",
        class: "",
        useParagraph: false,
        maxlength: 0,
        useTabForNext: false,

        // callback function after init
        callback: undefined,

        });


        getprinters();
        $("#updateprinters").click(()=>{
            alertify.confirm('تحديث قائمة', 'هل انت متاكد من انك تريد تحديث قائمة الطابعات؟',
                function () {
                    $.post("", { updateprinters: 1 },
                        function (data, textStatus, jqXHR) {
                            try {
                                resp = JSON.parse(data);
                            } catch (error) {
                                alertify.error('حدث خطا من السيرفر');
                                return;
                            }
                            if (resp.ok != true) {
                                alertify.error(resp.msg);
                            } else {
                                getprinters();
                                alertify.success("تم تحديث القائمة");
                            }
                        }
                    );
                },
                function () {
                    alertify.error('تم الالغاء')
                }
            );
        });
        $('#imgInput').change(function() {
            updateProductImg(URL.createObjectURL($(this)[0].files[0]));
        });


        $("#change_wholesale_password").click(() => {
            alertify.prompt('تغيير رمز سعر الجملة', '<div dir="rtl">الرمز الجديد</div>', '', function(evt, value) {
                $.post("", 'change_wholesale_password=' + value, function(html) {
                    try {
                        resp = JSON.parse(html);
                    } catch (error) {
                        alertify.error('حدث خطا من السيرفر');
                        return;
                    }
                    if (resp.ok == false) {
                        alertify.error(resp.msg);
                    } else {
                        alertify.success("تم تحديث رمز سعر الجملة");
                    }
                });
            }, function() {
                alertify.error('تم الالغاء')
            });
        });

        $("#change_iqd_value").click(() => {
            alertify.prompt('تغيير سعر صرف الدولار', '<div dir="rtl">السعر الجديد الجديد</div>', '', function(evt, value) {
                $.post("", {change_iqd_value: value}, function(html) {
                    try {
                        resp = JSON.parse(html);
                    } catch (error) {
                        alertify.error('حدث خطا من السيرفر');
                        return;
                    }
                    if (resp.ok == false) {
                        alertify.error(resp.msg);
                    } else {
                        $("#change_iqd_value").val(value);
                        alertify.success("تم تحديث سعر صرف الدولار");
                    }
                });
            }, function() {
                alertify.error('تم الالغاء')
            });
        });

        $("#change_my_password").click(() => {
            alertify.prompt('تغيير رمز الحساب', '<div dir="rtl">الرمز الجديد</div>', '', function(evt, value) {
                $.post("", 'change_my_password=' + value, function(html) {
                    try {
                        resp = JSON.parse(html);
                    } catch (error) {
                        alertify.error('حدث خطا من السيرفر');
                        return;
                    }
                    if (resp.ok == false) {
                        alertify.error(resp.msg);
                    } else {
                        alertify.success("تم تحديث رمز الحساب");
                    }
                });
            }, function() {
                alertify.error('تم الالغاء')
            });
        });

        $("#checkupdate").click(() => {
            $.post("update.php", 'check=1', function(html) {
                try {
                    resp = JSON.parse(html);
                } catch (error) {
                    alertify.error('حدث خطا من السيرفر');
                    return;
                }
                if (resp.ok == false) {
                    if (resp[0] == 4) {
                        alertify.error("لا يوجد تحديث جديد");
                    }
                } else {
                    if (resp[0] == 3) {
                        alertify.success("يوجد تحديث جديد ");
                        $("#updatenow").show();
                        $("#checkupdate").hide();
                    }
                }
            });
        });

        function wait(ms) {
            var start = new Date().getTime();
            var end = start;
            while (end < start + ms) {
                end = new Date().getTime();
            }
        }
        $("#updatenow").click(() => {
            $('#updateiFrame').modal('toggle');
            $("#updateFrame").attr("src", "update.php?update=1");
        });

        $.post("", 'defult_product_img=1', function(html) {
            try {
                resp = JSON.parse(html);
            } catch (error) {
                alertify.error('حدث خطا من السيرفر');
                return;
            }
            if (resp.ok == false) {
                alertify.error('حدث خطا من السيرفر');
            } else {
                updateProductImg(resp[0], false);
            }
        });

        $(".logoUpdate").on("input", function(evt){
            var tgt = evt.target || window.event.srcElement,
            files = tgt.files;
            mySize = $(this).attr("size");
            target = $(this).attr("targetImg");
            // FileReader support
            if (FileReader && files && files.length) {
                var fr = new FileReader();
                fr.onload = function () {
                    
                    console.log(target);
                    document.getElementById(target).src = fr.result;
                    
                    $.post("", {
                        update_logo: {size: mySize, data: fr.result}
                    }, function(html) {
                        try {
                            resp = JSON.parse(html);
                        } catch (error) {
                            alertify.error('حدث خطا من السيرفر');
                            return;
                        }
                        if (resp.ok == false) {
                            alertify.error(resp.msg);
                        } else {
                            alertify.success("تم تحديث الـ Logo");
                        }
                    });
                }
                fr.readAsDataURL(files[0]);
            }
            
            // Not supported
            else {
                // fallback -- perhaps submit the input to an iframe and temporarily store
                // them on the server until the user's session ends.
            }
        });
    });
</script>