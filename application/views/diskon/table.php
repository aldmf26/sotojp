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
<?php $this->load->view('tema/Footer'); ?>


<script>
  $(document).ready(function() {
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

    });
    $('.manual').hide();
    $('.manual').attr('disabled', 'true');
    $('.data-customer').hide();
    $('.data-customer').attr('disabled', 'true');
  });
</script>


