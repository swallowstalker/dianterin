<div id="food-popup" class="white-popup mfp-hide" style="max-width: 600px;">
    <div class="row">
        <div class="col-md-12 popup-header popup-element-header" style="font-size: 16pt;">

        </div>
    </div>

    <div class="row" id="error-notification" style="margin-top: 10px;">
        <div class="col-md-12" style="color: red; font-size: 10pt;">
            Kamu belum memilih makanan.
        </div>
    </div>

    {!! Form::open(["url" => "order/add", "id" => "new-order"]) !!}
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">
            <div class="table-responsive" style="max-height: 350px;">
                <table class="table table-hover table-condensed" id="dataTables-list">
                    <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
    </div>
    <div class="row" style="margin-bottom: 5px; border-top: 1px solid #E8E8E8; padding-top: 10px;">
        <div class="col-md-12" style="font-weight: bold;">
            Pengantar
        </div>
    </div>
    <div class="row">

        <!-- we will show available courier here. -->
        <div class="col-md-12">
            <div class="row" id="available-courier">

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-9">

            {!! Form::textarea("preference", "", [
                "style" => 'width: 100%; resize: none;',
                "rows" => 2,
                "placeholder" => "Preferensi"
                ]) !!}

            {!! Form::hidden("menu") !!}
            {!! Form::hidden("backup", $backupStatus) !!}
        </div>
        <div class="col-xs-3">
            {!! Form::submit("Pesan", [
                "name" => "save",
                "class" => "button-yellow-white",
                "style" => 'width: 100%; height: 45px;'
                ]) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

<script type="text/javascript">

    var oTable = null;
    var selectedMenu = 0;
    var menuListSource = baseURL + "/menu/list";


    var orderForm = $("form#new-order");

    /**
     * List all active courier for given restaurant.
     */
    function listActiveCourier(currentRestaurantID) {

        var defaultCourierTemplate = '<div class="col-md-12" style="margin-bottom: 10px;">';

        // get available courier for ordering menu in this restaurant
        $.ajax({
            url: baseURL + '/courier/list',
            type: "GET",
            data: {
                restaurant_id: currentRestaurantID
            },
            success: function (data) {

                if (data.length > 0) {

                    var availableCourierHTML = "";
                    for (var i = 0; i < data.length; i++) {

                        var checked = "";
                        if (i == 0) {
                            checked = 'checked="checked"';
                        }

                        availableCourierHTML +=
                                '<div class="col-md-3" style="margin-bottom: 10px;">' +
                                '<div class="row">' +
                                '<div class="col-md-8 col-xs-4" style="padding-right: 0;">' +
                                '<input type="radio" name="travel" value="' + data[i].travel_id + '" style="margin-right: 5px;" ' + checked + '>' +
                                '<img src="{!! asset("img") !!}/img_default_profile.png" style="height: 60px;" />&nbsp;'+
                                '</div>' +
                                '<div class="col-md-4 col-xs-8" style="padding-left: 0; padding-top: 5px;">' +
                                '<div style="font-size: 14pt;">' + data[i].courier_name + '</div>' +
                                '<div style="font-size: 10pt;">' + data[i].cost + '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                    }

                    $("#available-courier").append(availableCourierHTML);
                    $("form#new-order").find("input[type=submit]").prop("disabled", false);
                    $("form#new-order").find("textarea[name=preference]").prop("disabled", false);
                    $("form#new-order").find("input[type=submit]").addClass("button-yellow-white");
                    $("form#new-order").find("input[type=submit]").removeClass("button-grey-black");

                } else {

                    defaultCourierTemplate += "Belum ada pengantar yang tersedia.";
                    defaultCourierTemplate += "</div>";

                    $("#available-courier").html(defaultCourierTemplate);
                    $("form#new-order").find("input[type=submit]").prop("disabled", true);
                    $("form#new-order").find("textarea[name=preference]").prop("disabled", true);
                    $("form#new-order").find("input[type=submit]").addClass("button-grey-black");
                    $("form#new-order").find("input[type=submit]").removeClass("button-yellow-white");
                }
            },
            error: function (data) {

                defaultCourierTemplate += "Kami tidak dapat mengambil data pengantar yang tersedia.";
                defaultCourierTemplate += "</div>";

                $("#available-courier").html(defaultCourierTemplate);
                $("form#new-order").find("input[type=submit]").prop("disabled", true);
                $("form#new-order").find("textarea[name=preference]").prop("disabled", true);
                $("form#new-order").find("input[type=submit]").addClass("button-grey-black");
                $("form#new-order").find("input[type=submit]").removeClass("button-yellow-white");
            }
        });
    }

    $(document).ready(function () {

        var currentRestaurantID = 0;
        var currentRestaurantName = "";
        var currentMenuListSource = menuListSource;

        $(".food-popup-button").click(function () {
            currentRestaurantID = $(this).next().val();
            currentRestaurantName = $(this).next().next().val();
            currentMenuListSource = menuListSource + "?restaurantID=" + currentRestaurantID;
        });

        // register triggers for magnific popup
        $(".food-popup-button").magnificPopup({
            closeOnBgClick: false,
            mainClass: 'mfp-fade',
            callbacks: {
                beforeOpen: function () {

                    $("#available-courier").html("");

                    $("#food-popup .popup-header").html(currentRestaurantName);
                    $("#food-popup #error-notification").hide();

                    if (oTable != null) {
                        oTable.destroy();
                        oTable = null;
                    }

                    var settings = {
                        processing: true,
                        autoWidth: false,
                        ajax: {
                            url: currentMenuListSource
                        },
                        serverSide: false,
                        lengthChange: false,
                        searching: true,
                        paging: false,
                        dom: 'T<"H"f>t<"F"p>',
                        tableTools: {
                            sRowSelect: "single",
                            aButtons: []
                        },
                        order: [
                            [0, 'asc']
                        ],
                        columns: [
                            {
                                visible: true,
                                searchable: true,
                                orderable: true,
                                className: "left",
                                width: "71%",
                                data: "name"
                            },
                            {
                                visible: true,
                                searchable: true,
                                orderable: true,
                                className: "right",
                                width: "20%",
                                data: "price"
                            },
                            {
                                visible: true,
                                searchable: true,
                                orderable: true,
                                className: "left reference",
                                width: "3%",
                                data: "reference"
                            }
                        ],
                        responsive: true
                    };

                    oTable = $('#dataTables-list').DataTable(settings);

                    listActiveCourier(currentRestaurantID);
                },
                close: function () {

                    // clear all food popup input.
                    orderForm.find("input[name=menu]").val("");
                    orderForm.find("textarea[name=preference]").val("");

                    $("#available-courier").html("");
                }
            }
        });

        /**
         * if menu is chosen, update menu hidden value
         */
        $("#dataTables-list tbody").on("click", "tr", function () {

            var reference = $(this).find(".reference").find("input[type=hidden]").val();
            orderForm.find("input[name=menu]").val(reference);
            orderForm.find("textarea[name=preference]").html("");

            $.ajax({
                url: baseURL + '/menu/previous/preference',
                type: "GET",
                data: {
                    menu: reference
                },
                success: function (data) {
                    orderForm.find("textarea[name=preference]").val(data.preference);
                }
            });

        });

        /**
         * trying to validate if input menu is empty
         */
        {{-- @fixme validation should be handled on the server, not here. --}}
        orderForm.submit(function () {

            if (orderForm.find("input[name=menu]").val() == "") {

                orderForm.find("input[type=submit]").prop("disabled", false);
                $("#food-popup #error-notification").show();

                event.preventDefault();
                return false;
            }
        });
    });
</script>