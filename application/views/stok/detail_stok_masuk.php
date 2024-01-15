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
                <h3 class="float-left">Stok Masuk</h3>
            </div>
            <div class="col-sm-6">

                <?php if ($cek_status->status != 'Selesai') : ?>
                    <button type="button" id="atur_barang" class="btn btn-outline-secondary float-right ml-2" data-toggle="modal" data-target="#exampleModal">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                <?php endif ?>
                <a href="<?= base_url() ?>Stok/print_stok_masuk?kode_stok_produk=<?= $kode_stok_produk ?>" class="btn btn-outline-secondary float-right"><i class="fas fa-print"></i> Print</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="container-fluid">
            <div class="col-md-12">
                <?= $this->session->flashdata('message'); ?><br>
            </div>
            <form action="<?= base_url() ?>Stok/edit_stok_masuk" method="POST">
                <div class="card">
                    <div class="card-body">
                        <table id="" class="table" width="100%">

                            <thead>
                                <tr>
                                    <!-- <th class="sticky-top th-top">SKU</th> -->
                                    <th class="sticky-top th-top">Product</th>
                                    <th class="sticky-top th-top">Satuan</th>
                                    <th class="sticky-top th-top">Kategori</th>
                                    <th class="sticky-top th-top">Harga Jual</th>
                                    <th class="sticky-top th-top" width="10%">Stok Program</th>
                                    <th class="sticky-top th-top">Stok Masuk</th>
                                    <th class="sticky-top th-top" width="10%">Total Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cek_produk = [];
                                foreach ($stok as $op) :
                                    $cek_produk[] = $op->id_produk;
                                ?>
                                    <tr>
                                        <input type="hidden" name="id_stok_produk[]" value="<?= $op->id_stok_produk ?>">
                                        <input type="hidden" name="id_produk[]" value="<?= $op->id_produk ?>">
                                        <td><?= $op->nm_produk ?></td>
                                        <td><?= $op->satuan ?></td>
                                        <td><?= $op->nm_kategori ?></td>
                                        <td><?= number_format($op->harga, 0) ?></td>
                                        <td><?= $op->stok_program ?></td>
                                        <td>
                                            <input type="text" name="debit[]" value="<?= $op->debit ?>" style="width: 150px; text-align: center;" class="form-control fill">
                                        </td>
                                        <td><?= $op->ttl_stok ?></td>


                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">

                        <a href="<?= base_url('Stok') ?>" class="btn btn-secondary">Kembali</a>

                        <!-- <a href="<?= base_url() ?>Match/selesai_opname?kode_stok_produk=<?= $kode_stok_produk ?>" class="btn btn-info float-right ml-2">Selesai</a>  -->
                        <?php if ($cek_status->status != 'Selesai') : ?>
                            <a href="<?= base_url() ?>Stok/delete_stok?kode_stok_produk=<?= $kode_stok_produk ?>" class="btn btn-outline-secondary" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i></a>
                            <button type="submit" name="action" value="selesai" class="btn btn-outline-secondary float-right ml-2">Selesai</button>
                            <!-- <button type="submit" name="action" value="edit" class="btn btn-costume float-right">Edit</button> -->
                        <?php endif; ?>
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
<form action="<?= base_url() ?>stok/tambah_stok_masuk" method="POST">
    <input type="hidden" name="kode_stok_produk" value='<?= $kode_stok_produk ?>'>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#fadadd;">
                    <h5 class="modal-title majoo" id="exampleModalLabel">Pilih Produk</h5>
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
                                            <center><input class="form-check-input" type="checkbox" name="id_produk_stok[]" value="<?= $p->id_produk ?>" checked></center>
                                        <?php else : ?>
                                            <center><input class="form-check-input" type="checkbox" name="id_produk_stok[]" value="<?= $p->id_produk ?>"></center>
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
            $('input:checkbox').not(this).prop('checked', this.checked);
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