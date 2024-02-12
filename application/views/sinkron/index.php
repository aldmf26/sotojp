<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>

<style type="text/css">
    .modal .modal-dialog-aside {
        width: 500px;
        max-width: 80%;
        height: 100%;
        margin: 0;
        transform: translate(0);
        transition: transform .2s;
    }


    .modal .modal-dialog-aside .modal-content {
        height: inherit;
        border: 0;
        border-radius: 0;
    }

    .modal .modal-dialog-aside .modal-content .modal-body {
        overflow-y: auto
    }

    .modal.fixed-left .modal-dialog-aside {
        margin-left: auto;
        transform: translateX(100%);
    }

    .modal.fixed-right .modal-dialog-aside {
        margin-right: auto;
        transform: translateX(-100%);
    }

    .modal.show .modal-dialog-aside {
        transform: translateX(0);
    }
</style>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->

<div class="content-header">
    <div class="container">
        <div class="row mb-4 mt-4">
            <div class="col-lg-12 text-center">
                <h3>Import data ke cloud</h3>
            </div>
        </div>

        <div class="col-lg-12 text-center loading_muncul" style="display: none;">
            <img src="<?= base_url('asset/img/upload2.gif') ?>" alt="" width="30%" height="350px">
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-3">
                <a href="<?= base_url("Sinkron/import_invoice") ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <?php if (empty($invoice->no_nota)) : ?>
                                <img src="<?= base_url('asset/img/approve.png') ?>" alt="" width="120px">
                            <?php else : ?>
                                <img src="<?= base_url('asset/img/cloud-computing.png') ?>" alt="" width="120px">
                            <?php endif ?>

                            <h5 class="mt-2">Upload Invoice</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?= base_url("Sinkron/import_pembelian") ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <?php if (empty($pembelian->no_nota)) : ?>
                                <img src="<?= base_url('asset/img/approve.png') ?>" alt="" width="120px">
                            <?php else : ?>
                                <img src="<?= base_url('asset/img/cloud-computing.png') ?>" alt="" width="120px">
                            <?php endif ?>

                            <h5 class="mt-2">Upload Detail Pembelian</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?= base_url('Sinkron/import_stok') ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <?php if (empty($stok->kode_stok_produk)) : ?>
                                <img src="<?= base_url('asset/img/approve.png') ?>" alt="" width="120px">
                            <?php else : ?>
                                <img src="<?= base_url('asset/img/cloud-computing.png') ?>" alt="" width="120px">
                            <?php endif ?>
                            <h5 class="mt-2">Upload Stok</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>










<?php $this->load->view('tema/Footer'); ?>
<script>
    $('.loading').click(function() {
        $('.loading').hide();
        $('.loading_muncul').show();
    });
</script>