<a href="#backup-confirmation-popup" class="backup-confirmation-popup-button open-popup" style="visibility: hidden;"></a>
<div id="backup-confirmation-popup" class="white-popup mfp-hide" style="max-width: 400px; text-align: center;">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12 popup-header popup-element-header" style="font-size: 16pt;">
            Pesanan Cadangan
        </div>
    </div>
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            Apakah anda ingin menambah pesanan cadangan, jika pesanan ini tidak ada?
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="button-orange-black" id="add-backup">Tambah cadangan</button>&nbsp;
            <button class="button-black-white" id="finish-backup">Tidak, terima kasih</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    var backupStatus = {{ $backupStatus }};

    /**
     * Showing backup confirmation popup.
     */
    function showBackupPopup() {

        // if we are still on backup request, show a popup for backup confirmation.
        if (backupStatus) {
            $(".backup-confirmation-popup-button").click();
        }
    }

    $(document).ready(function () {

        var orderForm = $("form#new-order");

        // register triggers for magnific popup backup confirmation
        $(".backup-confirmation-popup-button").magnificPopup({
            closeOnBgClick: false,
            mainClass: 'mfp-fade',
            callbacks: {
            }
        });

        $("#add-backup").click(function () {
            orderForm.find("input[name=backup]").val(1);
            $.magnificPopup.close();
        });

        $("#finish-backup").click(function () {
            orderForm.find("input[name=backup]").val(0);
            $.magnificPopup.close();
        });

    });
</script>
