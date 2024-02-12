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
                <h1 class="m-0 text-dark">Data Discount</h1>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <?= $this->session->flashdata('message'); ?>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="float-left">Data Diskon</h4>
                    <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
                        Input
                    </button>
                </div>
                <?php $i = 1; ?>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-sm" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Produk</th>
                                    <th>Diskon</th>
                                    <th>Distribusi</th>
                                    <th>Dari</th>
                                    <th>Sampai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_diskon as $no => $d) : ?>
                                    <tr>
                                        <td><?= $no + 1 ?></td>
                                        <td><?= $d->nm_servis ?></td>
                                        <td>Rp. <?= number_format($d->diskon, 0) ?></td>
                                        <td><?= $d->id_distribusi == '1' ? 'Offline' : 'Online' ?></td>
                                        <td><?= date('d-m-Y', strtotime($d->start_date)) ?></td>
                                        <td><?= date('d-m-Y', strtotime($d->finish_date)) ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
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
</div>


<!-- Modal -->
<form action="<?= base_url() ?>diskon/add_diskon" method="post">
    <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #FFA07A;">
                    <h5 class="modal-title" id="exampleModalLabel">Input Diskon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group ">
                        <label>Menu</label>
                        <select name="id_servis[]" class="select" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">
                            <?php foreach ($menu as $m) : ?>
                                <option value="<?= $m->id_servis ?>"><?= $m->nm_servis ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label>Jumlah Diskon</label>
                        <div>
                            <input type="number" name="jumlah" class="form-control">
                        </div>
                        <small class="text-warning">(cth: 2,000)</small>
                    </div>
                    <div class="form-group ">
                        <label>Distribusi</label>
                        <div>
                            <select name="distribusi" id="" class="select">
                                <option value="1">Online</option>
                                <option value="2">Offline</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label>Dari</label>
                        <div>
                            <input type="date" name="dari" class="form-control">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label>Sampai</label>
                        <div>
                            <input type="date" name="sampai" class="form-control">
                        </div>
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



<form action="<?= base_url() ?>match/add_voucher" method="post">
    <div class="modal fade" id="tambah-voucher" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #FFA07A;">
                    <h5 class="modal-title" id="exampleModalLabel">Input Voucher Peritem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group pilih-metode">
                        <label for="">Jenis Diskon</label>
                        <select class="form-control" id="" required name="jenis">
                            <label for=""></label>
                            <option value="1">Rp</option>
                            <option value="2">Persen</option>
                        </select>
                    </div>


                    <div class="form-group ">
                        <label>Jumlah Diskon</label>
                        <div>
                            <input type="number" name="jumlah" class="form-control" required>
                        </div>
                        <small class="text-warning">Jika jenis rp (cth: 70000)</small>
                        <small class="text-warning">Jika jenis persen (cth: 10)</small>
                    </div>

                    <div class="form-group ">
                        <label>Tanggal Expired</label>
                        <div>
                            <input type="date" name="tgl_akhir" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label>Keterangan</label>
                        <div>
                            <input type="text" name="ket" class="form-control">
                        </div>
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




<form action="<?= base_url() ?>match/add_voucher_invoice" method="post">
    <div class="modal fade" id="tambah-voucher-invoice" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #FFA07A;">
                    <h5 class="modal-title" id="exampleModalLabel">Input Voucher Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group pilih-metode">
                        <label for="">Jenis Diskon</label>
                        <select class="form-control" id="" required name="jenis">
                            <label for=""></label>
                            <option value="1">Rp</option>
                            <option value="2">Persen</option>
                        </select>
                    </div>


                    <div class="form-group ">
                        <label>Jumlah Diskon</label>
                        <div>
                            <input type="number" name="jumlah" class="form-control" required>
                        </div>
                        <small class="text-warning">Jika jenis rp (cth: 70000)</small>
                        <small class="text-warning">Jika jenis persen (cth: 10)</small>
                    </div>

                    <div class="form-group ">
                        <label>Tanggal Expired</label>
                        <div>
                            <input type="date" name="tgl_akhir" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label>Keterangan</label>
                        <div>
                            <input type="text" name="ket" class="form-control">
                        </div>
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





<style>
    .buying-selling.active {
        background-image: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);
    }

    #option1 {
        display: none;
    }

    .buying-selling {
        width: 100%;
        padding: 10px;
        position: relative;
    }

    .buying-selling-word {
        font-size: 15px;
        font-weight: 600;
        margin-left: 35px;
    }

    .radio-dot:before,
    .radio-dot:after {
        content: "";
        display: block;
        position: absolute;
        background: #fff;
        border-radius: 100%;
    }

    .radio-dot:before {
        width: 20px;
        height: 20px;
        border: 1px solid #ccc;
        top: 10px;
        left: 16px;
    }

    .radio-dot:after {
        width: 12px;
        height: 12px;
        border-radius: 100%;
        top: 14px;
        left: 20px;
    }

    .buying-selling.active .buying-selling-word {
        color: #fff;
    }

    .buying-selling.active .radio-dot:after {
        background-image: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);
    }

    .buying-selling.active .radio-dot:before {
        background: #fff;
        border-color: #699D17;
    }

    .buying-selling:hover .radio-dot:before {
        border-color: #adadad;
    }

    .buying-selling.active:hover .radio-dot:before {
        border-color: #699D17;
    }


    .buying-selling.active .radio-dot:after {
        background-image: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);
    }

    .buying-selling:hover .radio-dot:after {
        background-image: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);
    }

    .buying-selling.active:hover .radio-dot:after {
        background-image: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);

    }

    @media (max-width: 400px) {

        .mobile-br {
            display: none;
        }

        .buying-selling {
            width: 49%;
            padding: 10px;
            position: relative;
        }

    }
</style>

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
    $(function() {
        $('.select').select2()

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });

    $(document).ready(function() {
        // $('#cash').on('change blur',function(){
        // 	if($(this).val().trim().length === 0){
        // 		$(this).val(0);
        // 	}
        // 	});
        // $('#mandiri_kredit').on('change blur',function(){
        // 	if($(this).val().trim().length === 0){
        // 		$(this).val(0);
        // 	}
        // 	});
        // $('#mandiri_debit').on('change blur',function(){
        // 	if($(this).val().trim().length === 0){
        // 		$(this).val(0);
        // 	}
        // 	});
        // $('#bca_kredit').on('change blur',function(){
        // 	if($(this).val().trim().length === 0){
        // 		$(this).val(0);
        // 	}
        // 	});
        // $('#bca_debit').on('change blur',function(){
        // 	if($(this).val().trim().length === 0){
        // 		$(this).val(0);
        // 	}
        // 	});


        // $('.pembayaran').keyup(function(){
        //     var cash = parseInt($("#cash").val());
        //     var mandiri_kredit = parseInt($("#mandiri_kredit").val());
        // 	var mandiri_debit = parseInt($("#mandiri_debit").val());
        // 	var bca_kredit = parseInt($("#bca_kredit").val());
        // 	var bca_debit = parseInt($("#bca_debit").val());
        // 	var total = parseInt($("#total").val());
        //     var bayar = mandiri_kredit + mandiri_debit + cash + bca_kredit + bca_debit;
        // 	if(total <= bayar){
        // 		$('#btn_bayar').removeAttr('disabled');
        // 	}else{
        // 		$('#btn_bayar').attr('disabled','true');
        // 	}


        //   });


        $('#d_customer').select2({
            width: '100%',
            language: {
                noResults: function() {
                    return '<button class="btn btn-sm btn-primary" id="no-results-btn" onclick="noResultsButtonClicked()">No Result Found</a>';
                },
            },
            escapeMarkup: function(markup) {
                return markup;
            },
        });

    });

    function noResultsButtonClicked() {
        $('.manual').removeAttr('disabled');
        $('.manual').show();
        $('.data-customer').attr('disabled', 'true');
        $('.data-customer').hide();
        // $(".pilih-metode").val("manual");
        $('.pilih-metode option[value=manual]').attr('selected', 'selected');
    }

    $(document).ready(function() {
        // $(".pilih_customer").change(function () { 
        //   $(this).find("option:selected") 
        //   .each(function () { 
        //     var optionValue = $(this).attr("value"); 
        //     if (optionValue) { 
        //       $(".box").not("." + optionValue).hide(); 
        //       $("." + optionValue).show(); 
        //     } else { 
        //       $(".box").hide(); 
        //     } 
        //   }); 
        // }).change();

        $(".pilih-metode").change(function() {
            $(this).find("option:selected")
                .each(function() {
                    var metode = $(this).attr("value");
                    if (metode == "manual") {
                        $('.manual').removeAttr('disabled');
                        $('.manual').show();
                        $('.data-customer').attr('disabled', 'true');
                        $('.data-customer').hide();
                    } else {
                        $('.data-customer').removeAttr('disabled');
                        $('.data-customer').show();
                        $('.manual').attr('disabled', 'true');
                        $('.manual').hide();
                    }

                });


            //  var metode = $(this).attr("value")
            //  if( metode == "manual"){
            //    $('.manual').removeAttr('disabled');
            //    $('.data-customer').attr('disabled','true');
            //  }else{
            //   $('.data-customer').removeAttr('disabled');
            //    $('.manual').attr('disabled','true');
            //  }

        });
        $('.manual').hide();
        $('.manual').attr('disabled', 'true');
        $('.data-customer').hide();
        $('.data-customer').attr('disabled', 'true');
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