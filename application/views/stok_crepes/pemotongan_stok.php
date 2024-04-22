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
                <h1 class="m-0 text-dark">List Pemotongan Stok Bahan</h1>
                <p>Tgl : <?= date("d F Y", strtotime($tgl1)); ?> ~ <?= date("d F Y", strtotime($tgl2)); ?></p>
            </div>
            <div class="col-sm-6">
                <?php if ($this->session->userdata('edit_hapus') == '1') : ?>
                    <!-- <button data-toggle="modal" data-target="#modal-detail" class="btn btn-success"><i class="fas fa-download"></i> Detail</button> -->
                    <!--<button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>-->
                    <!--<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button>-->
                    <!-- <button data-toggle="modal" data-target="#modal-delete" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button> -->
                <?php endif ?>
            </div>
        </div>
        <nav class="nav nav-pills">
            <a class="nav-link <?php echo (current_url() == base_url('Match/stok')) ? 'active' : ''; ?>" href="<?= base_url('Match/stok'); ?>">Stok Sekarang</a>
            <a class="nav-link <?php echo (current_url() == base_url('Match/list_pemotongan_resep')) ? 'active' : ''; ?>" href="<?= base_url('Match/list_pemotongan_resep'); ?>">List Pemotongan Resep</a>
        </nav>
    </div>
    <div style="margin-top: 40px;"></div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->session->flashdata('message'); ?>
        </div>
        <?php
        $cart =    $this->cart->contents();
        $total = 0;
        ?>
        <div class="col-sm-7">
            <a href="<?= base_url() ?>match/order" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
            <button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tb_servis">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bahan</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                <?php
                                $n = 1;
                                foreach ($produk as $p) : ?>
                                    <tr>
                                        <td><?= $n++ ?></td>
                                        <td><?= $p->nm_produk ?></td>
                                        <td><?= number_format($p->ttl, 2) ?></td>
                                        <td><?= $p->satuan ?></td>
                                        <td><a href="#" class="btn btn-sm btn-primary detail" id_produk="<?= $p->id_produk; ?>"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="">
    <div class="modal fade" id="modal-view">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:#FFA07A;">
                    <h4 class="modal-title">View Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="date" name="tgl1" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <input type="date" name="tgl2" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background:#FFA07A;">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="detailnya">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header" style="background:#FFA07A;">
                <h4 class="modal-title">Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="load_detail"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('tema/Footer'); ?>
<script>
    $(document).ready(function() {
        $(document).on('click', '.detail', function(e) {
            e.preventDefault()
            const id = $(this).attr('id_produk')
            const tgl1 = "<?= $tgl1 ?>"
            const tgl2 = "<?= $tgl2 ?>"

            $("#detailnya").modal('show')
            $.ajax({
                type: "GET",
                url: "<?= base_url('Match/load_detail_stok') ?>",
                data: {
                    id_produk: id,
                    tgl1: tgl1,
                    tgl2: tgl2
                },
                success: function(r) {
                    $('#tb_detail_stok').DataTable({
                        "paging": false,
                        "pageLength": 100,
                        "scrollY": "350px",
                        "lengthChange": false,
                        "ordering": false,
                        "info": false,
                        "stateSave": true,
                        "autoWidth": true,
                        
                    });
                    $("#load_detail").html(r);
                }
            });
        })
    });
</script>