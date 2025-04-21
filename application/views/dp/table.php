<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->

<div class="content-header">
    <div class="container">

        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark">Data DP</h1>
            </div>
            <div class="col-sm-6">
                <?php if ($this->session->userdata('edit_hapus') == '1'): ?>
                    <!-- <button data-toggle="modal" data-target="#modal-detail" class="btn btn-success"><i class="fas fa-download"></i> Detail</button> -->
                    <!--<button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>-->
                    <!--<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button>-->
                    <!-- <button data-toggle="modal" data-target="#modal-delete" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button> -->
                <?php endif ?>
            </div>
            <!-- <div class="col-5 mt-2">
					<a href="<?= base_url('match/order'); ?>" class="btn btn-warning">Kembali</a>
				</div> -->
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <?= $this->session->flashdata('message'); ?>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Input
                    </button>
                </div>
                <?php $i = 1; ?>

                <div class="card-body">
                    <table class="table" id="example1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Tanggal Pakai</th>
                                <th>KD DP</th>
                                <th>Customer</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Admin</th>
                                <th>Status/Print</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dp as $dp) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= date('d-m-y', strtotime($dp->tgl_dp)) ?></td>
                                    <td class="text-center"><?= $dp->tgl_jam ? date('d-m-y', strtotime($dp->tgl_jam)) : '-' ?></td>
                                    <td><?= $dp->kd_dp ?></td>
                                    <td><?= $dp->nama ?></td>
                                    <td><?= number_format($dp->jumlah_dp, 2) ?></td>
                                    <td><?= $dp->metode ?></td>
                                    <td><?= $dp->admin ?></td>
                                    <td class="text-center">
                                        <?php if ($dp->status == 2) : ?>
                                            <span class="badge badge-danger"><i class="fas fa-times-circle"></i></span>
                                        <?php else: ?>
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i></span>
                                        <?php endif; ?>

                                        <a href="<?= base_url('Match/print_dp?kd_dp=') ?><?= $dp->kd_dp ?>" class="btn btn-xs btn-warning"><i class="fas fa-file-invoice"></i></a>

                                        <?php if ($dp->status == 2) : ?>
                                        <?php else: ?>
                                            <?php if ($this->session->userdata('id_role') == '1') : ?>
                                                <a href="<?= base_url('Match/delete_dp?kd_dp=') ?><?= $dp->kd_dp ?>" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></a>
                                            <?php else: ?>
                                            <?php endif ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>


            </div>

        </div>
    </div>


</div>
</div>


<!-- Modal -->
<form action="<?= base_url() ?>match/add_dp" method="post">
    <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #FFA07A;">
                    <h5 class="modal-title" id="exampleModalLabel">Input DP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">



                    <div class="form-group pilih-metode">
                        <label for="">Customer</label>
                        <!-- <select class="form-control" id="" required="">
                            <label for=""></label>
                            <option value="">- Pilih Metode -</option>
                            <option value="manual">Input Manual</option>
                            <option value="customer">Dari Data Customer</option>
                        </select> -->
                        <input type="text" name="customer" class="form-control customer" placeholder="Isi Nama Customer">

                    </div>
                    <!-- <div class="form-group data-customer">

                        <select name="id_customer" id="d_customer" class="form-control data-customer id_customer" disabled>
                            <option value="">- Pilih Customer -</option>
                            <?php foreach ($customer as $key => $value): ?>
                                <option value="<?= $value->id_customer ?>"><?= $value->nama ?></option>
                            <?php endforeach ?>
                        </select>
                    </div> -->
                    <div class="form-group">
                    </div>

                    <div class="form-group ">
                        <label>Jumlah DP</label>
                        <div>
                            <input type="text" name="dp" class="form-control" id="mandiri_kredit">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Metode</label>
                        <select name="metode" id="metode" class="form-control" required>
                            <!-- <?php foreach ($kas as $k): ?>
                        <option value="<?= $k->id_akun ?>">- <?= $k->nm_akun ?> -</option>
                    <?php endforeach; ?>     -->
                            <option value="Cash">Cash</option>
                            <option value="BCA">BCA</option>
                            <option value="Mandiri">Mandiri</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Tanggal</label>
                        <input  type="date" name="tgl_dp" class="form-control" id="tgl_dp" required value="<?= date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <input type="text" name="ket" class="form-control" id="ket">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Input</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $this->load->view('tema/Footer'); ?>

