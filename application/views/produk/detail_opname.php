<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>

<style>
    .th-top {
        /* background:#F78CA0;
            position: sticky;
            color: white; */
        top: 45px;
        /* box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4); */
        /* border: #F78CA0; */
    }
</style>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Opname Produk</h1>
            </div>
            <div class="col-sm-6">
                <?php if ($this->session->userdata('edit_hapus') == '1') : ?>
                    <!-- <button data-toggle="modal" data-target="#modal-detail" class="btn btn-success"><i class="fas fa-download"></i> Detail</button> -->
                    <!--<button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>-->
                    <!--<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button>-->
                    <!-- <button data-toggle="modal" data-target="#modal-delete" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button> -->
                <?php endif ?>
                <!-- <a href="" class="btn btn-success float-right"><i class="fas fa-plus"></i> Atur Barang</a> -->
                <button type="button" id="atur_barang" class="btn btn-info float-right ml-2" data-toggle="modal" data-target="#exampleModal">
                    <i class="fas fa-plus"></i> Tambah
                </button>
                <!-- <a href="<?= base_url() ?>match/print_opname?kode_opname=<?= $kode_opname ?>" class="btn btn-info float-right ml-2"><i class="fas fa-print"></i> Print</a> -->
                <a href="<?= base_url() ?>match/form_opname?kode_opname=<?= $kode_opname ?>" class="btn btn-info float-right ml-2"><i class="fas fa-print"></i> Formulir</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="container-fluid">
            <div class="col-md-12">
                <?= $this->session->flashdata('message'); ?><br>
            </div>
            <form action="<?= base_url() ?>Opname/edit_stok_aktual" method="POST">
                <input type="hidden" name="kode_opname" value="<?= $kode_opname ?>">
                <div class="card">
                    <div class="card-body">
                        <table id="" class="table" width="100%">

                            <thead>
                                <tr>
                                    <th class="sticky-top th-top">SKU</th>
                                    <th class="sticky-top th-top">Product</th>
                                    <th class="sticky-top th-top">Satuan</th>
                                    <th class="sticky-top th-top">Kategori</th>
                                    <th class="sticky-top th-top">Stok Program</th>
                                    <th class="sticky-top th-top">Stok Aktual</th>
                                    <th class="sticky-top th-top">Selisih</th>
                                    <th class="sticky-top th-top">Harga Jual</th>
                                    <th class="sticky-top th-top">Total Program</th>
                                    <th class="sticky-top th-top">Total Selisih</th>
                                    <th class="sticky-top th-top">Catatan</th>
                                </tr>
                            </thead>

                            <tbody style="text-align: center;">
                                <?php
                                $cek_produk = [];
                                foreach ($opname as $op) : ?>
                                    <?php
                                    $cek_produk[] = $op->id_produk;
                                    $selisih = $op->stok_program - $op->stok_aktual;
                                    if ($selisih < 0) {
                                        $selisih *= -1;
                                    }
                                    $ttl_selisih = $selisih * $op->harga;
                                    ?>
                                    <?php if ($selisih < 0) : ?>
                                        <tr class="text-danger">
                                        <?php else : ?>
                                        <tr>
                                        <?php endif; ?>
                                        <input type="hidden" name="id_opname[]" value="<?= $op->id_opname ?>">
                                        <input type="hidden" name="id_produk[]" value="<?= $op->id_produk ?>">
                                        <td><?= $op->sku ?></td>
                                        <td><?= $op->nm_produk ?></td>
                                        <td><?= $op->satuan ?></td>
                                        <td><?= $op->nm_kategori ?></td>
                                        <td><?= $op->stok_program ?> </td>
                                        <td>
                                            <center><input type="text" name="stok_aktual[]" value="<?= $op->stok_program ?>" style="width: 120px; text-align: center;" class="form-control fill" <?= $op->status == 'Selesai' ? 'Readonly' : '' ?>></center>
                                        </td>
                                        <td><?= $selisih ?></td>
                                        <td><?= number_format($op->harga, 0) ?></td>
                                        <td><?= number_format($op->harga * $op->stok_program, 0) ?></td>
                                        <td><?= number_format($ttl_selisih, 0) ?></td>
                                        <td>
                                            <center><input type="text" name="catatan[]" value="<?= $op->catatan ?>" style="width: 120px; text-align: center;" class="form-control fill" <?= $op->status == 'Selesai' ? 'Readonly' : '' ?>></center>
                                        </td>
                                        </tr>
                                    <?php
                                    $status = $op->status;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <?php if ($status == 'Selesai') : ?>
                        <?php else : ?>
                            <a href="<?= base_url() ?>Match/delete_opname?kode_opname=<?= $kode_opname ?>" class="btn btn-outline-secondary" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i></a>
                            <a href="<?= base_url('Match/opname') ?>" class="btn btn-secondary">Batal</a>

                            <!-- <a href="<?= base_url() ?>Match/selesai_opname?kode_opname=<?= $kode_opname ?>" class="btn btn-info float-right ml-2">Selesai</a>  -->
                            <button type="submit" name="action" value="selesai" class="btn btn-info float-right ml-2">Selesai</button>
                            <button type="submit" name="action" value="edit" class="btn btn-info float-right">Edit</button>
                        <?php endif ?>

                    </div>

                </div>
            </form>



        </div>
    </div>
</div>




<!-- ======================================================== conten ======================================================= -->

<style>
    .modal-lg {
        max-width: 1100px;
        margin: 2rem auto;
    }

    .modal-body {
        max-height: 500px;
        overflow-y: auto;
    }

    .cari {
        /* background:#F78CA0;
            position: sticky;
            color: white; */
        top: 0px;
        /* box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
            border: #F78CA0; */
    }

    .th {
        /* background:#F78CA0;
            position: sticky;
            color: white; */
        top: 40px;
        /* box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4); */
        /* border: #F78CA0; */
    }
</style>


<!-- Modal Create Opname -->
<form action="<?= base_url() ?>match/tambah_detail_opname" method="POST">
    <input type="hidden" name="kode_opname" value='<?= $kode_opname ?>'>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #FFA07A;">
                    <h5 class="modal-title" id="exampleModalLabel">Pilih Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <table class="table table-hover" width="100%">
                        <div class="sticky-top cari">
                            <div class="row ">
                                <div class="col-6">
                                    <select id="countriesDropdown" class="form-control" oninput="filterTable()">
                                        <option>All</option>
                                        <?php foreach ($kategori as $k) : ?>
                                            <option><?= $k->nm_kategori ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="text" id="myInput" name="keyword" class="form-control" placeholder="Cari Produk . .">
                                </div>
                            </div>
                            <br>
                            <thead class="bg-costume">
                                <tr>
                                    <th class="sticky-top th majoo">#</th>
                                    <th class="sticky-top th majoo">PRODUK</th>
                                    <th class="sticky-top th majoo">KATEGORI</th>
                                    <th class="sticky-top th majoo">SKU</th>
                                    <th class="sticky-top th majoo">SATUAN</th>
                                    <th class="sticky-top th majoo">STOK</th>
                                    <th class="sticky-top th majoo">HARGA</th>
                                    <th class="sticky-top th majoo">
                                        <i class="fas fa-check-square"></i>
                                        <!-- <input type="checkbox" id="checkAll" value=""> -->
                                    </th>
                                </tr>
                            </thead>
                        </div>
                        <!-- <form action="<?= base_url() ?>match/input_opname" method="POST"> -->
                        <tbody id="myTable">
                            <?php
                            $n = 1;
                            foreach ($produk as $p) : ?>
                                <tr>
                                    <td><?= $n++ ?></td>
                                    <td><?= $p->nm_produk ?></td>
                                    <td><?= $p->nm_kategori ?></td>
                                    <td><?= $p->sku ?></td>
                                    <td><?= $p->satuan ?></td>
                                    <td><?= $p->stok ?></td>
                                    <td><?= number_format($p->harga, 0) ?></td>
                                    <td>
                                        <?php if (in_array($p->id_produk, $cek_produk)) : ?>
                                            <center><input class="form-check-input" type="checkbox" name="id_produk_opname[]" value="<?= $p->id_produk ?>" checked></center>
                                        <?php else : ?>
                                            <center><input class="form-check-input" type="checkbox" name="id_produk_opname[]" value="<?= $p->id_produk ?>"></center>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>

                    <!-- </form> -->
                </div>
            </div>
        </div>

    </div>
</form>




<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/solid.css" integrity="sha384-wnAC7ln+XN0UKdcPvJvtqIH3jOjs9pnKnq9qX68ImXvOGz2JuFoEiCjT8jyZQX2z" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css" integrity="sha384-HbmWTHay9psM8qyzEKPc8odH4DsOuzdejtnr+OFtDmOcIVnhgReQ4GZBH7uwcjf6" crossorigin="anonymous">
<script src="<?= base_url() ?>asset/time/jquery.skedTape.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('asset/'); ?>/plugins/daterangepicker/daterangepicker.js"></script>

<script>
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function filterTable() {
        // Variables
        let dropdown, table, rows, cells, country, filter;
        dropdown = document.getElementById("countriesDropdown");
        table = document.getElementById("myTable");
        rows = table.getElementsByTagName("tr");
        filter = dropdown.value;

        // Loops through rows and hides those with countries that don't match the filter
        for (let row of rows) { // `for...of` loops through the NodeList
            cells = row.getElementsByTagName("td");
            country = cells[2] || null; // gets the 2nd `td` or nothing
            // if the filter is set to 'All', or this is the header row, or 2nd `td` text matches filter
            // alert(country.textContent);
            if (filter === "All" || !country || (filter === country.textContent)) {
                row.style.display = ""; // shows this row
            } else {
                row.style.display = "none"; // hides this row
            }
        }
    }
</script>

<script>
    $(document).ready(function() {

        $("#checkAll").click(function() {
            $('input:checkbox:visible').not(this).prop('checked', this.checked);
        });


    });
</script>

<script>
    function autofill_anak() {
        var nm_kry = document.getElementById('nm_kry').value;
        $.ajax({
            url: "<?php echo base_url(); ?>Match/cari_anak",
            data: '&nm_kry=' + nm_kry,
            success: function(data) {
                var hasil = JSON.parse(data);

                $.each(hasil, function(key, val) {
                    document.getElementById('id_kry').value = val.id_kry;
                    document.getElementById('nm_kry').value = val.nm_kry;
                });
            }
        });
    }
</script>

<?php $this->load->view('tema/Footer'); ?>