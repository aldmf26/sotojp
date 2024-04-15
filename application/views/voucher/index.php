<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 justify-content-center">
            <div class="col-sm-8">
                <h3 class="float-left"><?= $title  ?></h3>
                <a href="<?= base_url() ?>Voucher_void/tambah_voucher" class="btn btn-outline-secondary float-right ml-2"><i class="fas fa-plus"></i> Tambah Voucher</a>
            </div>
        </div>
    </div>



    <div class="container-fluid ">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?= $this->session->flashdata('message'); ?><br>

                <table id="example1" class="table table-hover" width="100%">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Voucher</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($voucher as $no => $v) : ?>
                            <tr>
                                <td><?= $no + 1 ?></td>
                                <td><?= $v->voucher ?></td>
                                <td><?= $v->terpakai == 'T' ? 'Belum terpakai' : 'Terpakai' ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- ======================================================== conten ======================================================= -->
<?php $this->load->view('tema/Footer'); ?>