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
    <div class="container-fluid">
        <div class="row mb-4 mt-4">
            <div class="col-lg-12 text-center">
                <h3>Download data dari cloud</h3>
            </div>
        </div>

        <div class="col-lg-12 text-center loading_muncul" style="display: none;">
            <img src="<?= base_url('asset/img/upload2.gif') ?>" alt="" width="30%" height="350px">
        </div>

        <div class="row ">
            <div class="col-lg-3">
                <a href="<?= base_url('download/download_menu') ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= base_url('asset/img/download-computing.png') ?>" alt="" width="120px">
                            <h6 class="mt-2">Menu</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?= base_url('download/download_bahan') ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= base_url('asset/img/download-computing.png') ?>" alt="" width="120px">
                            <h6 class="mt-2">Bahan & Toping</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?= base_url('download/download_resep') ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= base_url('asset/img/download-computing.png') ?>" alt="" width="120px">
                            <h6 class="mt-2">Resep</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?= base_url('download/download_stok_masuk') ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= base_url('asset/img/download-computing.png') ?>" alt="" width="120px">
                            <h6 class="mt-2">Stok Masuk</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?= base_url('download/download_stok_opname') ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= base_url('asset/img/download-computing.png') ?>" alt="" width="120px">
                            <h6 class="mt-2">Stok Opname</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?= base_url('download/download_voucher_void') ?>" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= base_url('asset/img/download-computing.png') ?>" alt="" width="120px">
                            <h6 class="mt-2">Voucher Void</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="#" class="loading">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="<?= base_url('asset/img/download-computing.png') ?>" alt="" width="120px">
                            <h6 class="mt-2">User</h6>
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