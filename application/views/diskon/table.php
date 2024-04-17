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
        <h1 class="m-0 text-dark">Data Discount & Voucher</h1>
      </div>
      <div class="col-sm-6">
        <?php if ($this->session->userdata('edit_hapus') == '1') : ?>
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
          <h4 class="float-left">Data Voucher Invoice</h4>
          <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#tambah-voucher-invoice">
            Tambah
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm" id="example3">
              <thead>
                <tr>
                  <th>#</th>
                  <th>No Voucher</th>
                  <th>Jenis</th>
                  <th>Jumlah</th>
                  <th>Tanggal Expired</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($voucher_invoice as $v) : ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $v->no_voucher ?></td>

                    <?php if ($v->jenis == 1) : ?>
                      <td>Rp</td>
                      <td>Rp. <?= $v->jumlah ?></td>
                    <?php else : ?>
                      <td>Discount</td>
                      <td><?= $v->jumlah ?>%</td>
                    <?php endif; ?>

                    <td><?= $v->tgl_akhir ?></td>

                    <?php if ($v->status == 1) : ?>
                      <td class="text-success">Aktif</td>
                    <?php else : ?>
                      <td class="text-danger">Non-Aktif</td>
                    <?php endif; ?>
                    <td><?= $v->ket ?></td>

                    <td>
                      <button type="button" class="btn btn-sm btn-warning " data-toggle="modal" data-target="#edit-voucher-invoice<?= $v->id_voucher ?>"><i class="fa fa-edit"></i></button>
                      <a href="<?= base_url() ?>match/drop_voucher_invoice/<?= $v->id_voucher ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ?')"><i class="fa fa-trash"></i></a>
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
<form action="<?= base_url() ?>match/add_diskon" method="post">
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
            <label>Nama Diskon</label>
            <div>
              <input type="text" name="nm_diskon" class="form-control">
            </div>
          </div>

          <div class="form-group pilih-metode">
            <label for="">Jenis Diskon</label>
            <select class="form-control" id="" required="" name="jenis">
              <label for=""></label>
              <option value="1">Rp</option>
              <option value="2">Persen</option>
            </select>
          </div>


          <div class="form-group ">
            <label>Jumlah Diskon</label>
            <div>
              <input type="number" name="jumlah" class="form-control">
            </div>
            <small class="text-warning">Jika jenis rp (cth: 70000)</small>
            <small class="text-warning">Jika jenis persen (cth: 10)</small>
          </div>

          <div class="form-group ">
            <label>Dari</label>
            <div>
              <input type="date" name="tgl_awal" class="form-control">
            </div>
          </div>
          <div class="form-group ">
            <label>Sampai</label>
            <div>
              <input type="date" name="tgl_akhir" class="form-control">
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

<?php foreach ($diskon as $ds) : ?>

  <form action="<?= base_url() ?>match/edit_diskon" method="post">
    <div class="modal fade" id="edit<?= $ds->id_diskon ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #FFA07A;">
            <h5 class="modal-title" id="exampleModalLabel">Edit Diskon</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <input type="hidden" name="id_diskon" value="<?= $ds->id_diskon ?>">

            <div class="form-group ">
              <label>Nama Diskon</label>
              <div>
                <input type="text" name="nm_diskon" class="form-control" value="<?= $ds->nm_diskon ?>">
              </div>
            </div>

            <div class="form-group pilih-metode">
              <label for="">Jenis Diskon</label>
              <select class="form-control" id="" required="" name="jenis">
                <?php if ($ds->jenis == 1) : ?>
                  <option value="1" selected>Rp</option>
                  <option value="2">Persen</option>
                <?php else : ?>
                  <option value="1">Rp</option>
                  <option value="2" selected>Persen</option>
                <?php endif; ?>
              </select>
            </div>


            <div class="form-group ">
              <label>Jumlah Diskon</label>
              <div>
                <input type="number" name="jumlah" class="form-control" value="<?= $ds->jumlah ?>">
              </div>
              <small class="text-warning">Jika jenis rp (cth: 70000)</small>
              <small class="text-warning">Jika jenis persen (cth: 10)</small>
            </div>

            <div class="form-group ">
              <label>Dari</label>
              <div>
                <input type="date" name="tgl_awal" class="form-control" value="<?= $ds->tgl_awal ?>">
              </div>
            </div>
            <div class="form-group ">
              <label>Sampai</label>
              <div>
                <input type="date" name="tgl_akhir" class="form-control" value="<?= $ds->tgl_akhir ?>">
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Edit</button>
          </div>
        </div>
      </div>
    </div>
  </form>

<?php endforeach; ?>

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

<?php foreach ($voucher as $vc) : ?>

  <form action="<?= base_url() ?>match/edit_voucher" method="post">
    <div class="modal fade" id="edit-voucher<?= $vc->id_voucher ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #FFA07A;">
            <h5 class="modal-title" id="exampleModalLabel">Edit Voucher Peritem</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <input type="hidden" name="id_voucher" value="<?= $vc->id_voucher ?>">

            <div class="form-group pilih-metode">
              <label for="">Jenis Diskon</label>
              <select class="form-control" id="" required name="jenis">
                <?php if ($vc->jenis == 1) : ?>
                  <option value="1" selected>Rp</option>
                  <option value="2">Persen</option>
                <?php else : ?>
                  <option value="1">Rp</option>
                  <option value="2" selected>Persen</option>
                <?php endif; ?>
              </select>
            </div>


            <div class="form-group ">
              <label>Jumlah Diskon</label>
              <div>
                <input type="number" name="jumlah" value="<?= $vc->jumlah ?>" class="form-control" required>
              </div>
              <small class="text-warning">Jika jenis rp (cth: 70000)</small>
              <small class="text-warning">Jika jenis persen (cth: 10)</small>
            </div>

            <div class="form-group ">
              <label>Tanggal Expired</label>
              <div>
                <input type="date" name="tgl_akhir" value="<?= $vc->tgl_akhir ?>" class="form-control" required>
              </div>
            </div>

            <div class="form-group ">
              <label>Keterangan</label>
              <div>
                <input type="text" name="ket" value="<?= $vc->ket ?>" class="form-control">
              </div>
            </div>

            <div class="form-group pilih-metode">
              <label for="">Status</label>
              <select class="form-control" id="" required name="status">
                <?php if ($vc->status == 1) : ?>
                  <option value="1" selected>Aktif</option>
                  <option value="0">Non-Aktif</option>
                <?php else : ?>
                  <option value="1">Aktif</option>
                  <option value="0" selected>Non-Aktif</option>
                <?php endif; ?>
              </select>
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

<?php endforeach; ?>


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

<?php foreach ($voucher_invoice as $vc) : ?>

  <form action="<?= base_url() ?>match/edit_voucher_invoice" method="post">
    <div class="modal fade" id="edit-voucher-invoice<?= $vc->id_voucher ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #FFA07A;">
            <h5 class="modal-title" id="exampleModalLabel">Edit Voucher Peritem</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <input type="hidden" name="id_voucher" value="<?= $vc->id_voucher ?>">

            <div class="form-group pilih-metode">
              <label for="">Jenis Diskon</label>
              <select class="form-control" id="" required name="jenis">
                <?php if ($vc->jenis == 1) : ?>
                  <option value="1" selected>Rp</option>
                  <option value="2">Persen</option>
                <?php else : ?>
                  <option value="1">Rp</option>
                  <option value="2" selected>Persen</option>
                <?php endif; ?>
              </select>
            </div>


            <div class="form-group ">
              <label>Jumlah Diskon</label>
              <div>
                <input type="number" name="jumlah" value="<?= $vc->jumlah ?>" class="form-control" required>
              </div>
              <small class="text-warning">Jika jenis rp (cth: 70000)</small>
              <small class="text-warning">Jika jenis persen (cth: 10)</small>
            </div>

            <div class="form-group ">
              <label>Tanggal Expired</label>
              <div>
                <input type="date" name="tgl_akhir" value="<?= $vc->tgl_akhir ?>" class="form-control" required>
              </div>
            </div>

            <div class="form-group ">
              <label>Keterangan</label>
              <div>
                <input type="text" name="ket" value="<?= $vc->ket ?>" class="form-control">
              </div>
            </div>

            <div class="form-group pilih-metode">
              <label for="">Status</label>
              <select class="form-control" id="" required name="status">
                <?php if ($vc->status == 1) : ?>
                  <option value="1" selected>Aktif</option>
                  <option value="0">Non-Aktif</option>
                <?php else : ?>
                  <option value="1">Aktif</option>
                  <option value="0" selected>Non-Aktif</option>
                <?php endif; ?>
              </select>
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

<?php endforeach; ?>



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