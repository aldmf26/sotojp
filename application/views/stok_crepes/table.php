<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Stok Bahan</h1>
            </div>
            <div class="col-sm-6">
                <?php if ($this->session->userdata('edit_hapus') == '1') : ?>

                <?php endif ?>
            </div>
        </div>
        <nav class="nav nav-pills">
			<a class="nav-link <?php echo (current_url() == base_url('Match/stok')) ? 'active' : ''; ?>" href="<?= base_url('Match/stok'); ?>">Stok Sekarang</a>
			<a class="nav-link <?php echo (current_url() == base_url('Match/list_pemotongan_resep')) ? 'active' : ''; ?>" href="<?= base_url('Match/list_pemotongan_resep'); ?>">List Pemotongan Resep</a>
		</nav>
    </div>

    <div class="row">
        <div class="container-fluid">
            <div class="col-lg-8">
                <?= $this->session->flashdata('message'); ?><br>
                <table class="table" id="tb_servis">
                    <thead>
                        <tr>
                            <th>no</th>
                            <th>nama bahan</th>
                            <th>qty</th>
                            <th>satuan</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php
                        $n = 1;
                        foreach ($produk as $p) : ?>
                            <tr>
                                <td><?= $n++ ?></td>
                                <td><?= $p->nm_produk ?></td>
                                <td><?= number_format($p->stok_program, 1) ?></td>
                                <td><?= $p->satuan ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('tema/Footer'); ?>