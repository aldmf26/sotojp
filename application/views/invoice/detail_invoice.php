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
				<h1 class="m-0 text-dark">Rincian Invoice</h1>
			</div>


		</div>
	</div>
	<div style="margin-top: 20px;"></div>
	<div class="row justify-content-center">

		<div class="col-md-12">
			<?= $this->session->flashdata('message'); ?>
		</div>

		<div class="col-md-5">
			<div class="card">
				<div class="card-header">
					<p class="float-left"><strong><?= $invoice->no_nota; ?></strong></p>
					<?php if ($invoice->id_customer != 0) : ?>
						<p class="float-right"><?= $invoice->nama; ?> - <?= date('d/M/Y', strtotime($invoice->tgl_jam)) ?></p>
					<?php else : ?>
						<p class="float-right"><?= date('d/M/Y', strtotime($invoice->tgl_jam)) ?></p>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<?php
					$total_app = 0;
					$qty_app = 0;
					?>
					<?php
					$total_produk = 0;
					$qty_produk = 0;
					?>

					<h4 class="text-center mb-2">--Product--</h4>
					<br>
					<div class="row">

						<?php
						$total_toping = 0;
						foreach ($produk as $p) : ?>
							<?php
							$total_produk += ($p->harga * $p->jumlah) - $p->diskon;
							$qty_produk += $p->jumlah;

							$toping = $this->db->query("SELECT a.*, b.nm_produk
							FROM tb_pembelian as a 
							left join tb_produk as b on b.id_produk = a.id_produk
							where a.id_produk_toping = '$p->id_produk' and a.no_nota = '$no_nota'
							")->result();

							?>
							<div class="col-md-8">
								<p><?= $p->jumlah; ?> &nbsp <?= $p->nm_servis; ?></p>
							</div>
							<div class="col-md-4">
								<?php if (empty($p->diskon)) : ?>
									<p class="float-right"><?= number_format($p->harga * $p->jumlah, 0); ?></p>
								<?php else : ?>
									<p class="text-right"><s><?= number_format($p->harga * $p->jumlah, 0); ?></s></p>
									<p class="text-right"><?= number_format(($p->harga * $p->jumlah) - $p->diskon, 0); ?></p>
								<?php endif ?>

							</div>
							<?php

							foreach ($toping as $t) :

							?>
								<div class="col-md-8">
									<p class="ml-3 text-sm"><?= $t->jumlah; ?> &nbsp <?= $t->nm_produk; ?></p>
								</div>
								<div class="col-md-4">
									<p class="float-right text-sm"><?= number_format($t->harga * $t->jumlah, 0); ?></p>
								</div>
							<?php $total_toping += $t->harga * $t->jumlah;
							endforeach ?>

						<?php
						endforeach; ?>
					</div>


				</div>
				<div class="card-footer">
					<hr>
					<div class="row">


						<div class="col-md-6">
							<p class="float-left " style="font-weight: bold;">Total + Toping</p>
						</div>
						<div class="col-md-6">
							<p class="float-right " style="font-weight: bold;"><?= number_format($total_produk + $total_toping); ?></p>
						</div>
					</div>







					<input type="hidden" name="total" id="total" value="<?= $total_produk + $total_toping; ?>">
					<hr>
					<form action="<?= base_url(); ?>/match/edit_pembayaran" method="POST">
						<?php if ($invoice->nominal_voucher > 0) : ?>
							<div class="form-group row">
								<label for="nominal_voucher" class="col-md-4 col-form-label">Voucher</label>
								<div class="col-md-6">
									<input type="number" class="form-control pembayaran" id="nominal_voucher" value="<?= $invoice->nominal_voucher; ?>" name="nominal_voucher" readonly>
								</div>
							</div>
						<?php else : ?>
							<input type="hidden" class="form-control pembayaran" id="nominal_voucher" value="0" name="nominal_voucher" readonly>
						<?php endif; ?>

						<?php if ($invoice->diskon != 0) : ?>
							<div class="form-group row">
								<label for="diskon" class="col-md-4 col-form-label">Diskon</label>
								<div class="col-md-6">
									<input type="number" class="form-control pembayaran" id="diskon" value="<?= $invoice->diskon; ?>" name="diskon" readonly>
								</div>
							</div>
						<?php else : ?>
							<input type="hidden" class="form-control pembayaran" id="diskon" value="0" name="diskon" readonly>
						<?php endif; ?>


						<style>
							.hilang_row {
								display: none;
							}
						</style>
						<div class="form-group row hilang_row">
							<label for="mandiri_kredit" class="col-md-4 col-form-label">Mandiri Kredit</label>
							<div class="col-md-6">
								<input type="number" class="form-control pembayaran" id="mandiri_kredit" value="<?= $invoice->mandiri_kredit; ?>" name="mandiri_kredit">
							</div>
						</div>
						<div class="form-group row hilang_row">
							<label for="mandiri_debit" class="col-md-4 col-form-label">Mandiri Debit</label>
							<div class="col-md-6">
								<input type="number" class="form-control pembayaran" id="mandiri_debit" value="<?= $invoice->mandiri_debit; ?>" name="mandiri_debit">
							</div>
						</div>
						<div class="form-group row hilang_row">
							<label for="bca_kredit" class="col-md-4 col-form-label">BCA Kredit</label>
							<div class="col-md-6">
								<input type="number" class="form-control pembayaran" id="bca_kredit" value="<?= $invoice->bca_kredit; ?>" name="bca_kredit">
							</div>
						</div>
						<!-- <div class="form-group row hilang_row">
								<label for="bca_debit" class="col-md-4 col-form-label">BCA Debit</label>
								<div class="col-md-6">
									<input type="number" class="form-control pembayaran" id="bca_debit" value="<?= $invoice->bca_debit; ?>" name="bca_debit">
								</div>
							</div> -->
						<div class="form-group row ">
							<label for="bca_debit" class="col-md-4 col-form-label">GOPAY</label>
							<div class="col-md-6">
								<input type="number" class="form-control pembayaran" id="shoope" value="<?= $invoice->gopay; ?>" name="gopay">
							</div>
						</div>
						<div class="form-group row ">
							<label for="bca_debit" class="col-md-4 col-form-label">GRABFOOD</label>
							<div class="col-md-6">
								<input type="number" class="form-control pembayaran" id="bca_debit" value="<?= $invoice->bca_debit; ?>" name="bca_debit">
							</div>
						</div>
						<div class="form-group row">
							<input type="hidden" name="id_invoice" value="<?= $invoice->id; ?>">
							<input type="hidden" name="no_nota" value="<?= $invoice->no_nota; ?>">
							<label for="cash" class="col-md-4 col-form-label">CASH </label>
							<div class="col-md-6">
								<input type="number" class="form-control pembayaran" id="cash" value="<?= $invoice->cash; ?>" name="cash">
							</div>
						</div>
						<div class="form-group row">
							<label for="kembalian" class="col-md-4 col-form-label">Kembalian</label>
							<div class="col-md-6">
								<input type="number" class="form-control" id="kembalian" value="<?= $invoice->bayar - $invoice->total ?>" disabled>
							</div>
						</div>

						<input type="hidden" name="total" id="total" value="<?= $invoice->total; ?>">

						<input type="hidden" name="tgl_jam" id="tgl_jam" value="<?= $invoice->tgl_jam; ?>">

						<hr>

						<a target="_blank" href="<?= base_url(); ?>produk/save_nota?invoice=<?= $no_nota; ?>" class="btn btn-success float-right"><i class="fas fa-print"></i> Invoice</a>
						<a target="_blank" href="<?= base_url(); ?>produk/save_checker?invoice=<?= $no_nota; ?>" class="btn mr-2 btn-success float-right"><i class="fas fa-print"></i> Checker</a>
						<button class="btn btn-info float-right mr-2" id="edit_pembayaran" disabled type="submit"><i class="fas fa-edit"></i> Edit</button>
					</form>




				</div>
			</div>
		</div>



	</div>
</div>



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
	$(document).ready(function() {
		$('#cash').on('change blur', function() {
			if ($(this).val().trim().length === 0) {
				$(this).val(0);
			}
		});

		$('#mandiri_kredit').on('change blur', function() {
			if ($(this).val().trim().length === 0) {
				$(this).val(0);
			}
		});
		$('#mandiri_debit').on('change blur', function() {
			if ($(this).val().trim().length === 0) {
				$(this).val(0);
			}
		});
		$('#bca_kredit').on('change blur', function() {
			if ($(this).val().trim().length === 0) {
				$(this).val(0);
			}
		});
		$('#bca_debit').on('change blur', function() {
			if ($(this).val().trim().length === 0) {
				$(this).val(0);
			}
		});
		$('.d_dp_input').hide();
		$('.select_dp').attr('disabled', 'true');
		$('#cb_dp').change(function() {
			if ($(this).is(':checked')) {
				$('.d_dp_input').show();
				$('.select_dp').removeAttr('disabled', 'true');
			} else {
				$('.d_dp_input').hide();
				$('.select_dp').attr('disabled', 'true');
				$("#debit").val(0);
				$("#kd_dp").val('');
			}


		});
		$('.pembayaran').keyup(function() {
			// var dp = parseInt($("#dp").val());

			var cash = parseInt($("#cash").val());
			var bca_debit = parseInt($("#bca_debit").val());
			var shoope = parseInt($("#shoope").val());
			var total = parseInt($("#total").val());
			var bayar = cash + shoope + bca_debit;
			$("#kembalian").val(bayar - total);
			if (total <= bayar) {
				$('#edit_pembayaran').removeAttr('disabled');
			} else {
				$('#edit_pembayaran').attr('disabled', 'true');
			}


		});
		$('#data_dp').change(function() {
			var id_dp = $("#data_dp").val();
			$.ajax({
				url: "<?= base_url(); ?>match/get_dp/",
				method: "POST",
				data: {
					id_dp: id_dp
				},
				dataType: "json",
				success: function(data) {
					$("#dp").val(data.kredit);
					$("#id_customer").val(data.id_customer);
					$("#kd_dp").val(data.kd_dp);
					$("#tgl_dp").val(data.tgl_dp);
					$("#metode").val(data.metode);

					bayar_default();

				}
			});
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