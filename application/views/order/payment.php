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
				<h1 class="m-0 text-dark">Rincian Order & Pembayaran</h1>
			</div>
			<div class="col-sm-6">
				<?php if ($this->session->userdata('edit_hapus') == '1') : ?>
					<!-- <button data-toggle="modal" data-target="#modal-detail" class="btn btn-success"><i class="fas fa-download"></i> Detail</button> -->
					<!--<button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>-->
					<!--<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button>-->
					<!-- <button data-toggle="modal" data-target="#modal-delete" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button> -->
				<?php endif ?>
			</div>
			<div class="col-5 mt-2">
				<a href="<?= base_url('match/order'); ?>" class="btn btn-warning">Kembali</a>
			</div>
		</div>
	</div>
	<div style="margin-top: 20px;"></div>
	<div class="row justify-content-center">
		<div class="col-md-12">
			<?= $this->session->flashdata('message'); ?>
		</div>

		<div class="col-sm-7">
			<form action="<?= base_url() ?>produk/checkout" method="post">
				<div class="card">
					<div class="card-body">
						<h3 class="text-center">Rincian Product</h3>
						<hr>
						<div class="row">
							<?php
							$subtotal_produk = 0;
							$jumlah_produk = 0;
							?>
							<div class="col-12">
								<table width="100%">
									<?php
									$ttl_toping = 0;
									foreach ($cart as $key => $value) : ?>
										<tr>
											<?php
											$allCartItems = $this->cart->contents();
											// Filter item yang memiliki properti 'ibu' sesuai dengan 'id_produk' saat ini
											$productItems = array_filter($allCartItems, function ($item) use ($value) {
												return isset($item['ibu']) && $item['ibu'] == $value['id_produk'];
											});
											$toping = $productItems;

											$subtotal_produk += ($value['price'] * $value['qty']) - $value['diskon'];
											$jumlah_produk += $value['qty'];

											?>

											<td width="80">
												<img width="80" class="img-thumbnail" src="<?= base_url() ?>upload/produk/default.png" alt="">
											</td>
											<td>
												<?= $value['name'] ?>
											</td>
											<input type="hidden" name="id_produk[]" value="<?= $value['id'] ?>">
											<input type="hidden" name="qty[]" value="<?= $value['qty'] ?>">
											<td class="text-right">
												<?= $value['qty'] ?> x Rp.<?= number_format($value['price']) ?>
											</td>
											<td class="text-right">
												<?php if (empty($value['diskon'])) : ?>
													<strong>Rp.<?= number_format($value['qty'] * $value['price']) ?></strong>
												<?php else : ?>
													<s><strong>Rp.<?= number_format($value['qty'] * $value['price']) ?></strong></s> <br>
													<strong>Rp.<?= number_format(($value['qty'] * $value['price']) - $value['diskon']) ?></strong>
												<?php endif ?>
											</td>

										</tr>
										<?php foreach ($toping as $t) : ?>
											<tr>
												<td></td>
												<td>
													<p class="ml-4 text-sm"><?= $t['name'] ?></p>
												</td>
												<td class="text-right">
													<p class="ml-4 text-sm"><?= $t['qty'] ?> x Rp.<?= number_format($t['price']) ?></p>
												</td>
												<td>
													<p class="text-right text-sm">Rp.<?= number_format($t['qty'] * $t['price']) ?>
												</td>
											</tr>
										<?php $ttl_toping += $t['qty'] * $t['price'];
										endforeach ?>

									<?php endforeach ?>



								</table>
							</div>

							<!-- <div class="container mt-2">
								<strong>Subtotal <?= $jumlah_produk ?> produk</strong> <strong style="float: right;">Rp. <?= number_format($subtotal_produk) ?></strong>
								<hr>
							</div> -->

							<div class="container">
								<hr>
								<strong style="font-size: 20px;">Total</strong> <strong style="float: right; font-size: 22px;">Rp. <?= number_format($subtotal_produk + $ttl_toping) ?></strong>
							</div>

							<?php $total = $subtotal_produk + $ttl_toping  ?>
							<div class="container">
								<hr>
								<h3 class="text-center mb-4">Pembayaran</h3>
								<hr>
								<form id="form_vcr_inv">
									<div class="row">
										<div class="col-4">
											<div class="form-group">
												<div class="custom-control custom-switch custom-switch-off-warning custom-switch-on-success">
													<input type="checkbox" class="custom-control-input" id="vcr_inv">
													<label class="custom-control-label" for="vcr_inv">Voucher</label>
												</div>
											</div>
										</div>

										<div class="col-5 vcr_inv">
											<div class="form-group">
												<input type="text" id="data_vcr_inv" name="no_voucher" class="form-control select_vcr_inv" placeholder="Masukan kode voucher" required>
											</div>
										</div>

										<div class="col-1 vcr_inv">
											<button type="button" id="cek_voucher" class="btn btn-sm mt-1 btn-primary select_vcr_inv">Cek</button>
										</div>
									</div>
								</form>
								<div class="form-group row vcr_inv">
									<label for="staticEmail" class="col-md-4 col-form-label">Voucher </label>
									<div class="col-md-6">
										<input type="text" readonly id="nominal_voucher" name="nominal_voucher" class="form-control pembayaran select_vcr_inv" value="0">
										<input type="hidden" id="id_voucher" name="id_voucher" class="form-control pembayaran select_vcr_inv">
									</div>
								</div>



								<?php $total = $subtotal_produk + $ttl_toping  ?>


								<div class="form-group row">
									<label for="staticEmail" class="col-md-4 col-form-label">Diskon</label>

									<div class="col-md-6">
										<input type="text" name="diskon" class="form-control pembayaran diskon  " value="0">
										<input type="hidden" name="id_diskon" class="form-control col-3 ml-1" id="id_diskon">
									</div>
								</div>
								<div class="form-group row">
									<label for="staticEmail" class="col-md-4 col-form-label">Total Pembayaran</label>
									<div class="col-md-6">
										<input type="number" class="form-control total_pembayaran" readonly value="<?= $total; ?>">
									</div>
								</div>
								<hr>


								<style>
									.hilang_row {
										display: none;
									}
								</style>
								<div class="form-group row hilang_row">
									<label for="staticEmail" class="col-md-4 col-form-label">Mandiri Kredit</label>
									<div class="col-md-6">
										<input type="number" name="mandiri_kredit" value="0" class="form-control pembayaran" id="mandiri_kredit">
									</div>
								</div>

								<div class="form-group row hilang_row">
									<label for="staticEmail" class="col-md-4 col-form-label">Mandiri Debit</label>
									<div class="col-md-6">
										<input type="number" name="mandiri_debit" value="0" class="form-control pembayaran" id="mandiri_debit">
									</div>
								</div>

								<div class="form-group row hilang_row">
									<label for="staticEmail" class="col-md-4 col-form-label">BCA Kredit</label>
									<div class="col-md-6">
										<input type="number" name="bca_kredit" class="form-control pembayaran" value="0" id="bca_kredit">
									</div>
								</div>

								<!-- <div class="form-group row hilang_row">
									<label for="staticEmail" class="col-md-4 col-form-label">BCA Debit</label>
									<div class="col-md-6">
										<input type="number" name="bca_debit" class="form-control pembayaran" value="0" id="bca_debit">
									</div>
								</div> -->
								<div class="form-group row">
									<label for="staticEmail" class="col-md-4 col-form-label">GOPAY</label>
									<div class="col-md-6">
										<input type="number" name="shoope" class="form-control pembayaran pemgopay" tipe_pem="gopay" value="0" id="shoope">
									</div>
									<div class="col-md-2">
										<button class="btn btn-primary btn-sm salin_nominal" tipe_pem="gopay" type="button"><i class="far fa-copy"></i> nominal</button>
									</div>
								</div>
								<div class="form-group row">
									<label for="staticEmail" class="col-md-4 col-form-label">GRABFOOD</label>
									<div class="col-md-6">
										<input type="number" name="bca_debit" class="form-control pembayaran pemgrab" tipe_pem="grab" value="0" id="bca_debit">
									</div>
									<div class="col-md-2">
										<button class="btn btn-primary btn-sm salin_nominal" tipe_pem="grab" type="button"><i class="far fa-copy"></i> nominal</button>
									</div>
								</div>
								<div class="form-group row">
									<label for="staticEmail" class="col-md-4 col-form-label">CASH</label>
									<div class="col-md-6">
										<input type="number" name="cash" class="form-control pembayaran pemcash" tipe_pem="cash" value="0" id="cash">
									</div>
									<div class="col-md-2">
										<button class="btn btn-primary btn-sm salin_nominal" tipe_pem="cash" type="button"><i class="far fa-copy"></i> nominal</button>
									</div>
								</div>


								<input type="hidden" name="total" id="total" value="<?= $total; ?>">
								<input type="hidden" name="id_distribusi" value="<?= $dis; ?>">

								<button class="btn btn-primary btn-block" style="background-image: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%); border-color: #F7889D; font-weight: bold; color: #fff;" id="btn_bayar" disabled>PROSES BAYAR <i class="fas fa-money-check-alt"></i> <i class="fa fa-chevron-right" style="float: right;"></i></button>


							</div>
						</div>
					</div>
				</div>
			</form>
			<?php $total = $subtotal_produk + $ttl_toping  ?>
		</div>


	</div>
</div>





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
<script src="<?= base_url('asset/adminlte/js/'); ?>sweetalert2.min.js"></script>

<script>
	$(document).ready(function() {

		$('.d_dp_input').hide();
		$('.select_dp').attr('disabled', 'true');

		$('.vcr_inv').hide();
		$('.select_vcr_inv').attr('disabled', 'true');

		function bayar_default() {
			var diskon = 0;
			$(".diskon").each(function() {
				diskon += parseInt($(this).val());
			});

			var voucher = parseInt($("#nominal_voucher").val());

			// var diskon = parseInt($("#diskon").val());
			var cash = parseInt($("#cash").val());
			var mandiri_kredit = parseInt($("#mandiri_kredit").val());
			var mandiri_debit = parseInt($("#mandiri_debit").val());
			var bca_kredit = parseInt($("#bca_kredit").val());
			var bca_debit = parseInt($("#bca_debit").val());
			var shoope = parseInt($("#shoope").val());
			// var tokopedia = parseInt($("#tokopedia").val());



			var total = parseInt($("#total").val());
			var bayar = mandiri_kredit + mandiri_debit + cash + bca_kredit + bca_debit + shoope + diskon + voucher;

			if (total <= bayar) {
				$('#btn_bayar').removeAttr('disabled');
			} else {
				$('#btn_bayar').attr('disabled', 'true');
			}
		}

		bayar_default();



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
					$("#debit").val(data.kredit);
					$("#id_customer").val(data.id_customer);
					$("#kd_dp").val(data.kd_dp);
					$("#tgl_dp").val(data.tgl_dp);
					$("#metode").val(data.metode);

					bayar_default();

				}
			});
		});

		$('#vcr_inv').change(function() {
			if ($(this).is(':checked')) {
				$('.vcr_inv').show();
				$('.select_vcr_inv').removeAttr('disabled', 'true');
			} else {
				$('.vcr_inv').hide();
				$('.select_vcr_inv').attr('disabled', 'true');
			}
			$("#nominal_voucher").val('');
			$("#id_voucher").val('');
			bayar_default();
		});

		$(document).on('click', '#cek_voucher', function(event) {
			event.preventDefault();
			var no_voucher = $('#data_vcr_inv').val();

			$.ajax({
				type: "get",
				url: "<?php echo base_url('Match/cek_vcr_inv'); ?>",
				data: {
					no_voucher: no_voucher
				},
				dataType: 'JSON',
				success: function(data) {
					if (data.status == 'ada') {
						if (data.jenis == 1) {
							// Mengatur nilai pada input nominal_voucher dan id_voucher
							$("#nominal_voucher").val(data.jumlah);
							$("#id_voucher").val(data.id_voucher);
							var disc = $(".diskon").val();
							var ttl = $('#total').val();
							var ttl_pembayaran = parseFloat(ttl) - parseFloat(disc) - parseFloat(data.jumlah);

							if (ttl_pembayaran < 0) {
								$('.total_pembayaran').val('0')
							} else {
								$('.total_pembayaran').val(ttl_pembayaran)
							}
						} else {
							var jumlah = parseInt($("#total").val()) > 0 ? parseInt($("#total").val()) * parseInt(data.jumlah) / 100 : 0;
							var ttl = $('#total').val();

							var ttl_pembayaran = parseFloat(ttl) - parseFloat(jumlah);



							if (ttl_pembayaran < 0) {
								$('.total_pembayaran').val('0')
							} else {
								$('.total_pembayaran').val(ttl_pembayaran)
							}
							$("#nominal_voucher").val(jumlah);
							$("#id_voucher").val(data.id_voucher);
						}
						// Menampilkan pesan sukses jika voucher tersedia
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'success',
							title: ' Voucher tersedia'
						});
					} else if (data.status == 'terpakai') {
						// Menampilkan pesan error jika voucher sudah terpakai
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: ' Voucher sudah terpakai'
						});
					} else if (data.status == 'expired') {
						// Menampilkan pesan error jika voucher telah kedaluwarsa
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: ' Voucher expired'
						});
					} else if (data.status == 'kosong') {
						// Menampilkan pesan error jika voucher tidak tersedia
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: ' Voucher tidak tersedia'
						});
					}
					// Memanggil fungsi untuk melakukan pembayaran default
					bayar_default();
				}
			});



		});




		// Mencegah form_vcr_inv melakukan submit secara otomatis
		// $(document).on('click', '#cek_voucher', function(event) {
		// 	event.preventDefault();


		// 	// Menghapus nilai pada input nominal_voucher dan id_voucher
		// 	$("#nominal_voucher").val('');
		// 	$("#id_voucher").val('');

		// 	// Melakukan request AJAX untuk memeriksa voucher
		// 	$.ajax({
		// 		url: "<?php echo base_url('Match/cek_vcr_inv'); ?>",
		// 		method: 'POST',
		// 		data: new FormData(this),
		// 		contentType: false,
		// 		processData: false,
		// 		dataType: 'JSON',
		// 		success: function(data) {
		// 			if (data.status == 'ada') {
		// 				if (data.jenis == 1) {
		// 					// Mengatur nilai pada input nominal_voucher dan id_voucher
		// 					$("#nominal_voucher").val(data.jumlah);
		// 					$("#id_voucher").val(data.id_voucher);
		// 					var disc = $(".diskon").val();
		// 					var ttl = $('#total').val();
		// 					var ttl_pembayaran = parseFloat(ttl) - parseFloat(disc) - parseFloat(data.jumlah);

		// 					if (ttl_pembayaran < 0) {
		// 						$('.total_pembayaran').val('0')
		// 					} else {
		// 						$('.total_pembayaran').val(ttl_pembayaran)
		// 					}
		// 				} else {
		// 					var jumlah = parseInt($("#total").val()) > 0 ? parseInt($("#total").val()) * parseInt(data.jumlah) / 100 : 0;
		// 					$("#nominal_voucher").val(jumlah);
		// 					$("#id_voucher").val(data.id_voucher);
		// 				}
		// 				// Menampilkan pesan sukses jika voucher tersedia
		// 				Swal.fire({
		// 					toast: true,
		// 					position: 'top-end',
		// 					showConfirmButton: false,
		// 					timer: 3000,
		// 					icon: 'success',
		// 					title: ' Voucher tersedia'
		// 				});
		// 			} else if (data.status == 'terpakai') {
		// 				// Menampilkan pesan error jika voucher sudah terpakai
		// 				Swal.fire({
		// 					toast: true,
		// 					position: 'top-end',
		// 					showConfirmButton: false,
		// 					timer: 3000,
		// 					icon: 'error',
		// 					title: ' Voucher sudah terpakai'
		// 				});
		// 			} else if (data.status == 'expired') {
		// 				// Menampilkan pesan error jika voucher telah kedaluwarsa
		// 				Swal.fire({
		// 					toast: true,
		// 					position: 'top-end',
		// 					showConfirmButton: false,
		// 					timer: 3000,
		// 					icon: 'error',
		// 					title: ' Voucher expired'
		// 				});
		// 			} else if (data.status == 'kosong') {
		// 				// Menampilkan pesan error jika voucher tidak tersedia
		// 				Swal.fire({
		// 					toast: true,
		// 					position: 'top-end',
		// 					showConfirmButton: false,
		// 					timer: 3000,
		// 					icon: 'error',
		// 					title: ' Voucher tidak tersedia'
		// 				});
		// 			}
		// 			// Memanggil fungsi untuk melakukan pembayaran default
		// 			bayar_default();
		// 		}
		// 	});
		// });




		$(".diskon").keyup(function() {
			var diskon = $(this).val();
			var ttl = $('#total').val();
			var voucher = $("#nominal_voucher").val();

			var ttl_pembayaran = parseFloat(ttl) - parseFloat(diskon) - parseFloat(voucher);
			if (ttl_pembayaran < 0) {
				$('.total_pembayaran').val('0')
			} else {
				$('.total_pembayaran').val(ttl_pembayaran)
			}


		})


		$('#data_diskon').change(function() {
			var id_diskon = $(this).val();

			$.ajax({
				url: "<?= base_url(); ?>match/get_diskon/",
				method: "POST",
				data: {
					id_diskon: id_diskon
				},
				dataType: "json",
				success: function(data) {
					if (data.jenis == 1) {
						$(".diskon").val(data.jumlah);
						var diskon = data.jumlah;
					} else {
						var jumlah = parseInt($("#total").val()) * parseInt(data.jumlah) / 100;
						$(".diskon").val(jumlah);
						var diskon = jumlah;
					}
					var ttl = $('#total').val();
					var disc = diskon;
					var voucher = $("#nominal_voucher").val();
					if (isNaN(disc)) {
						var ttl_pembayaran = parseFloat(ttl) - parseFloat(voucher);
					} else {
						var ttl_pembayaran = parseFloat(ttl) - parseFloat(disc) - parseFloat(voucher);
					}
					if (ttl_pembayaran < 0) {
						$('.total_pembayaran').val('0')
					} else {
						$('.total_pembayaran').val(ttl_pembayaran)
					}

					$("#id_diskon").val(data.id_diskon);
					bayar_default();
				}
			});




		});



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



		$('.pembayaran').keyup(function() {
			var diskon = 0;
			$(".diskon").each(function() {
				diskon += parseInt($(this).val());
			});


			// var diskon = parseInt($("#diskon").val());
			var cash = parseInt($("#cash").val());
			var mandiri_kredit = parseInt($("#mandiri_kredit").val());
			var mandiri_debit = parseInt($("#mandiri_debit").val());
			var bca_kredit = parseInt($("#bca_kredit").val());
			var bca_debit = parseInt($("#bca_debit").val());
			var shoope = parseInt($("#shoope").val());
			// var tokopedia = parseInt($("#tokopedia").val());
			var total = parseInt($("#total").val());
			var nominal_voucher = parseInt($("#nominal_voucher").val());
			var bayar = mandiri_kredit + mandiri_debit + cash + bca_kredit + bca_debit + shoope + diskon + nominal_voucher;
			if (total <= bayar) {
				$('#btn_bayar').removeAttr('disabled');
			} else {
				$('#btn_bayar').attr('disabled', 'true');
			}


		});

		$('.salin_nominal').click(function(e) {
			e.preventDefault();
			var tipe_pem = $(this).attr('tipe_pem');
			var total_pembayaran = $('.total_pembayaran').val()

			$('.pem' + tipe_pem).val(total_pembayaran);
			$('#btn_bayar').removeAttr('disabled');



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