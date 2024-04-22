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
                <h1 class="m-0 text-dark">Data Resep</h1>

                <!-- <button type="button" class="hapus">test</button> -->
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
        <div class="d-flex justify-content-between align-items-center">
            <nav class="nav nav-pills">
                <a class="nav-link <?php echo (current_url() == base_url('Match/dt_servis')) ? 'active' : ''; ?>" href="<?= base_url('Match/dt_servis'); ?>">Data Menu</a>
                <a class="nav-link <?php echo (current_url() == base_url('Match/dt_resep')) ? 'active' : ''; ?>" href="<?= base_url('Match/dt_resep'); ?>">Data Resep</a>
            </nav>
            <a href="#" class="btn btn-sm btn-primary import"><i class="fas fa-file-excel"></i> Export</a>
        </div>
        <div class="row">
            <div class="col-lg-12">
            <?php if ($this->session->flashdata('message')) { ?>
                <div class="alert alert-success mt-4" role="alert">
                    <?= $this->session->flashdata('message'); ?>
                </div>
                <br>
                <?php } ?>
                <table id="tb_servis" class="table table-hover" width="100%">
                    <thead>
                        <tr>
                            <th width="6%">No</th>
                            <th>NAMA BAHAN</th>
                            <th>NAMA PRODUK</th>
                            <th width="10%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($kasbon as $k) :
                        ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $k->nm_produk; ?> </td>
                                <td><?= ucwords(strtolower($k->nm_menu)); ?></td>
                                <td><a class="btn btn-sm btn-primary edit" id_produk="<?= $k->id_produk; ?>" href="#"><i class="fas fa-edit"></i></a></td>
                            </tr>
                        <?php $i++;
                        endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




</div>

<!-- ======================================================== conten ======================================================= -->
<form action="<?= base_url('Match/save_edit_resep') ?>" method="post">
    <div id="edit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Resep</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="load_edit"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>

        </div>
    </div>
</form>
<form action="<?= base_url('Match/import_resep') ?>" method="post" enctype="multipart/form-data">
    <div id="import" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Resep</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <table>
                            <tr>

                                <td>
                                    <span style="font-size: 20px;"><b> Download Excel template</b></span><br>
                                    File ini memiliki kolom header dan isi yang sesuai dengan data menu
                                </td>
                                <td>
                                    <a href="<?= base_url('Match/export_template_resep'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> DOWNLOAD
                                        TEMPLATE</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <hr>
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <span style="font-size: 20px;"><b> Upload Excel template</b></span><br>
                                    Setelah mengubah, silahkan upload file.
                                </td>
                                <td>
                                    <input type="file" name="file" class="form-control">
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </div>

        </div>
    </div>
</form>

<!-- ======================================================== conten ======================================================= -->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.import', function() {
            $("#import").modal('show')
        })
        $(document).on('click', '.edit', function(e) {
            e.preventDefault()
            const id_produk = $(this).attr('id_produk')
            $("#edit").modal('show')
            $.ajax({
                type: "GET",
                url: "<?= base_url('Match/load_edit_resep') ?>",
                data: {
                    id_produk: id_produk
                },
                success: function(r) {
                    $('#tb_servis2').DataTable({
                        "paging": false,
                        "pageLength": 100,
                        "scrollY": "350px",
                        "lengthChange": false,
                        "ordering": false,
                        "info": false,
                        "stateSave": true,
                        "autoWidth": true,

                    });
                    $("#load_edit").html(r);

                }
            });
        })
    });
</script>

<?php $this->load->view('tema/Footer'); ?>