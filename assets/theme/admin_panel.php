
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <!--                               Font Awesome                               -->
    <link rel="stylesheet" href="assets/fontawesome-free-6.2.0-web/css/all.min.css">
    <!--                   Webpixels CSS                -->
    <link rel="stylesheet" href="assets/css/webpixels.css">
    <!--                  main Style                -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!--                    jquery                    -->
    <script src="assets/js/jquery-3.4.1.min.js"></script>
    <!--                    alertify                    -->
    <script src="assets/alertify/alertify.js"></script>
    <link rel="stylesheet" href="assets\alertify\css\alertify.min.css" />
    <link rel="stylesheet" href="assets\alertify\css\themes\default.min.css" />

    <title>Flex - لوحة التحكم</title>


<script>
    //SORT TABLES
    function sortTables() {
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

        const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
            v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
            )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

        // do the work...
        document.querySelectorAll('th').forEach(th => th.addEventListener('click', (() => {
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        Array.from(tbody.querySelectorAll('tr'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
            .forEach(tr => tbody.appendChild(tr) );
        })));
    }
</script>



<!-- Banner -->
    <!-- <div class="btn w-full btn-primary text-truncate rounded-0 py-2 border-0 position-relative" style="z-index: 1000;">
        <strong>Crafted with Webpixels CSS:</strong> The design system for Bootstrap 5. Browse all components →
</div> -->
    <nav class="bg-gray-50 navbar navbar-expand-lg navbar-light px-0 py-3">
        <div class="container-xl">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                flex ⚡
            </a>
            <!-- Navbar toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Nav -->
                <div class="navbar-nav mx-lg-auto">
                    <!-- Navigation -->
                    <a class="nav-link" href="?p=">
                        <i class="fa fa-house"></i> الرئيسية
                    </a>

                    <a class="nav-link" href="?p=salse_panel">
                        <i class="fa-solid fa-cart-shopping"></i> شاشة المبيعات
                    </a>

                    <a class="nav-link" href="?p=clients_panel">
                        <i class="fa fa-users"></i> العملاء
                    </a>

                    <a class="nav-link" href="?p=users_panel">
                        <i class="fa fa-user-tie"></i> الموظفين
                    </a>

                    <a class="nav-link" href="?p=products_panel">
                        <i class="fa-solid fa-boxes-stacked"></i> المنتجات
                    </a>

                    <a class="nav-link" href="?p=unit_panel">
                        <i class="fa-solid fa-keyboard"></i> الوحدات
                    </a>

                    <a class="nav-link" href="?p=class_panel">
                        <i class="fa-solid fa-check"></i> الانواع
                    </a>
                    <a class="nav-link" href="?p=settings_panel">
                        <i class="fa-solid fa-gear"></i> الاعدادات
                    </a>
                    <a id="notifs" class="btn nav-link position-relative">
                        <i class="fa-solid fa-bell"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Dashboard -->
    <div dir="rtl" class="bg-surface-secondary">
        <!-- Vertical Navbar -->
        <!-- <nav class="navbar show navbar-vertical h-lg-screen navbar-expand-lg px-0 py-3 navbar-light bg-white border-bottom border-bottom-lg-0 border-end-lg" style="width: 40%;" id="navbarVertical">
            <div class="container-fluid">
                <!-- Toggler 
                <button class="navbar-toggler ms-n2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Brand -
                <!-- <a class="navbar-brand py-lg-2 mb-lg-5 px-lg-6 me-0" href="#">
                    <img src="https://preview.webpixels.io/web/img/logos/clever-primary.svg" alt="...">
                </a> -->
        <!-- User menu (mobile) -
                <!-- <div class="navbar-user d-lg-none">
                     Dropdown
                    <div class="dropdown">
                         Toggle
                        <a href="#" id="sidebarAvatar" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="avatar-parent-child">
                                <img alt="Image Placeholder"
                                    src="https://images.unsplash.com/photo-1548142813-c348350df52b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80"
                                    class="avatar avatar- rounded-circle">
                                <span class="avatar-child avatar-badge bg-success"></span>
                            </div>
                        </a>
                         Menu 
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sidebarAvatar">
                            <a href="#" class="dropdown-item">Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="#" class="dropdown-item">Billing</a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div> -
                <!-- Collapse -
                <div class="collapse navbar-collapse" id="sidebarCollapse">
                    
                    <!-- Divider --
                    <hr class="navbar-divider my-5 opacity-20">
                    <!-- Navigation --
                    <!-- <ul class="navbar-nav mb-md-4" dir="ltr">
                        <li>
                            <div class="nav-link text-xs font-semibold text-uppercase text-muted ls-wide" href="#">
                                Contacts
                                <span
                                    class="badge bg-soft-primary text-primary rounded-pill d-inline-flex align-items-center ms-4">13</span>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="nav-link d-flex align-items-center">
                                <div class="me-4">
                                    <div class="position-relative d-inline-block text-white">
                                        <img alt="Image Placeholder"
                                            src="https://images.unsplash.com/photo-1548142813-c348350df52b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80"
                                            class="avatar rounded-circle">
                                        <span
                                            class="position-absolute bottom-2 end-2 transform translate-x-1/2 translate-y-1/2 border-2 border-solid border-current w-3 h-3 bg-success rounded-circle"></span>
                                    </div>
                                </div>
                                <div>
                                    <span class="d-block text-sm font-semibold">
                                        Marie Claire
                                    </span>
                                    <span class="d-block text-xs text-muted font-regular">
                                        Paris, FR
                                    </span>
                                </div>
                                <div class="ms-auto">
                                    <i class="bi bi-chat"></i>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link d-flex align-items-center">
                                <div class="me-4">
                                    <div class="position-relative d-inline-block text-white">
                                        <span class="avatar bg-soft-warning text-warning rounded-circle">JW</span>
                                        <span
                                            class="position-absolute bottom-2 end-2 transform translate-x-1/2 translate-y-1/2 border-2 border-solid border-current w-3 h-3 bg-success rounded-circle"></span>
                                    </div>
                                </div>
                                <div>
                                    <span class="d-block text-sm font-semibold">
                                        Michael Jordan
                                    </span>
                                    <span class="d-block text-xs text-muted font-regular">
                                        Bucharest, RO
                                    </span>
                                </div>
                                <div class="ms-auto">
                                    <i class="bi bi-chat"></i>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link d-flex align-items-center">
                                <div class="me-4">
                                    <div class="position-relative d-inline-block text-white">
                                        <img alt="..."
                                            src="https://images.unsplash.com/photo-1610899922902-c471ae684eff?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80"
                                            class="avatar rounded-circle">
                                        <span
                                            class="position-absolute bottom-2 end-2 transform translate-x-1/2 translate-y-1/2 border-2 border-solid border-current w-3 h-3 bg-danger rounded-circle"></span>
                                    </div>
                                </div>
                                <div>
                                    <span class="d-block text-sm font-semibold">
                                        Heather Wright
                                    </span>
                                    <span class="d-block text-xs text-muted font-regular">
                                        London, UK
                                    </span>
                                </div>
                                <div class="ms-auto">
                                    <i class="bi bi-chat"></i>
                                </div>
                            </a>
                        </li>
                    </ul> --
                    <!-- Push content down --
                    <div class="mt-auto"></div>
                    <!-- User (md) --
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-person-square"></i> Account
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?logout=1">
                                <i class="bi bi-box-arrow-left"></i> تسجيل خروج
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> -->
        <!-- Main content -->
        <?php include($contant_body); ?>

    </div>

    <!-- Notifications Modal -->
    <div class="modal fade" id="notifModal" dir="ltr" tabindex="-1" aria-labelledby="notifModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="background-color: #ededed;">
                <div class="modal-header">
                    <h5 class="modal-title" id="notifModalLabel">الاشعارات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div style="overflow: auto; max-height: 400px;" dir="rtl">
                    <table class="table table-striped table-hover">
                        <tbody id="notifsList" style="max-width: 19%;">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/popperjs/popper.min.js"></script>
    <script src="assets/bootstrap-5.2.1-dist/js/bootstrap.min.js"></script>
    <script>
        function pageReload(){
            window.location.reload();
        }
        $(document).ready(function () {
            sortTables();
            $("#notifs").click(function () {
                $.post("", { getnotifications: 1 }, function (html) {
                    try {
                        resp = JSON.parse(html);
                    } catch (error) {
                        return;
                    }
                    if (resp.ok != true) {
                        alertify.error(resp.msg);
                    } else {
                        $("#notifsList").html('');
                        try {
                            cnt = 0;
                            resp[0].forEach(function (item) {
                                $("#notifsList").prepend(`<tr id="notf${cnt}">`);
                                msg = `تم بيع ${item.items_count} مواد للعميل ${'<b>' + item.clientName + '</b>'} بسعر ${item.total_price} بواسطة ${'<b>' + item.userName + '</b>'}`;
                                $(`#notf${cnt}`).append(`<td><a href="?p=salse_panel&ev=1&eval=loadOrder(${item.order_id},false)"><span>${msg}</span></a><td>`);
                                $("#notifsList").append('</tr>');
                                cnt += 1;
                            });
                        } catch (error) {
                            console.log(error);
                            $('#notifsList').html("لايوجد");
                        }
                        $('#notifModal').modal('toggle');
                    }
                });


            });
        });
        function deleteit(type, id, itemToDelete) {
            alertify.confirm('حذف عنصر', 'هل انت متاكد من انك تريد حذف هذا العنصر بشكل نهائي؟',
                function () {
                    $.post("", { deleteItem: type, id: id },
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
                                alertify.success(resp[0]);
                                $(itemToDelete).remove();
                            }
                        }
                    );
                },
                function () {
                    alertify.error('تم الالغاء')
                }
            );
        }
    </script>
