<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>
<style>
	.toko_active {
		background-color: #FFF192;
		color: #EF7A8A;
		font-weight: bold;
	}
</style>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->

<div class="content-header">
	<div class="container">

		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Penjualan Produk</h1>
			</div>
			<div class="col-sm-6">

			</div>
		</div>
	</div>
	<div style="margin-top: 40px;"></div>

	<div class="row">
		<div class="col-md-12">
			<?= $this->session->flashdata('message'); ?>
			<div id="cart_session"></div>
		</div>
		<?php
		$cart =	$this->cart->contents();
		$total = 0;
		?>
		<div class="col-4">
			<a href="<?= base_url("match/order?dis=1") ?>">
				<div class="card <?= $dis == '1' ? 'bg-gradient' : '' ?> ">
					<div class="card-body">
						<h5 class="text-center">OFFLINE ORDER</h5>
					</div>
				</div>
			</a>
		</div>
		<div class="col-4">
			<a href="<?= base_url("match/order?dis=2") ?>">
				<div class="card <?= $dis == '2' ? 'bg-gradient' : '' ?>">
					<div class="card-body">
						<h5 class="text-center">ONLINE ORDER</h5>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-12 ">
			<input type="hidden" id="id_distribusi" value="<?= $dis ?>">
		</div>
		<!-- <div class="col-sm-4 col-4">
			<div class="card">
				<div class="card-body">
					<select name="kategori" id="kategori" class="form-control">
						<option value="0">All</option>
						<?php foreach ($kategori as $k) : ?>
							<option value="<?= $k->id_kategori; ?>"><?= $k->nm_kategori; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div> -->
		<input type="hidden" id="kategori">
		<div class="col-8 muncul">
			<div class="data-kategori">

			</div>
		</div>


		<div class="col-sm-2 col-2 mb-2 hilang">
			<button type="button" class="btn btn-warning back_kategori "><i class="fas fa-long-arrow-alt-left"></i> Kembali</button>
		</div>
		<div class="col-10 hilang"></div>

		<div class="col-sm-8 col-8 hilang">

			<div class="card">
				<div class="card-body">
					<input type="text" id="keyword" name="keyword" class="form-control" placeholder="Cari Produk . .">
				</div>
			</div>
			<div class="data-produk">

			</div>

		</div>

		<div class="col-sm-4 col-4 ">
			<a href="<?= base_url() ?>match/list_penjualan">
				<div class="card bg-gradient">
					<div class="card-body">
						<h6 class="text-center"><strong><i class="fa fa-cubes"></i> List Penjualan Produk</strong></h6>
					</div>
				</div>
			</a>
			<div id="cart">

			</div>
		</div>
		<!-- <div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="pills-home-tab"> -->


	</div>
</div>

<!-- voucher -->
<div class="modal fade" id="voucher" tabindex="-1" aria-labelledby="voucher" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Voucher</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form action="" method="post" id='form-check-voucher'>
					<div class="row">
						<div class="col-10">
							<div class="form-group">
								<label>No Voucher</label>
								<input type="text" class="form-control" name="no_voucher" placeholder="Masukan no voucher" required>
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<label>Check</label>
								<button type="submit" class="btn btn-success"> <i class="fas fa-sync-alt"></i></button>
							</div>
						</div>
					</div>
				</form>

				<form method="post" id="form_voucher">

					<input type="hidden" name="id_cart" value="" id="id_cart">
					<input type="hidden" name="price_cart" value="" id="price_cart">


					<div id="data_voucher">
						<!-- <div class="row">
						<div class="col-4">
							<label>No Vocher</label>
							<p>VC006</p>			
						</div>
						<div class="col-4">
							<label>Diskon</label>
							<p>20%</p>			
						</div>
						<div class="col-4">
							<label>Keterangan</label>
							<p>Voucher</p>			
						</div>				
					</div>					 -->
					</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" id="save-voucher" class="btn btn-primary">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="discon_servis" tabindex="-1" aria-labelledby="discon_servis" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Diskon Servis</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" id="diskon_servis">

					<!-- <input type="hidden" name="id_cart" value="" id="id_cart">
				<input type="hidden" name="price_cart" value="" id="price_cart"> -->

					<div class="buying-selling-group" data-toggle="buttons">
						<div class="row">
							<?php foreach ($diskon_servis as $key => $value) : ?>
								<div class="col-lg-2 col-4 mr-5">
									<label class="btn btn-default buying-selling">
										<div class="checkbox-group required">
											<!-- <input type="text"  name="id_diskon" value="<?= $value->id_diskon ?>"> -->
											<input type="radio" name="id_diskon" value="<?= $value->id_diskon ?>" id="option1" autocomplete="off" class="" required>
										</div>
										<span class="radio-dot"></span>
										<?php if ($value->jenis == 1) : ?>
											<span class="buying-selling-word">Rp. <?= $value->jumlah ?></span>
										<?php else : ?>
											<span class="buying-selling-word"><?= $value->jumlah ?>%</span>
										<?php endif; ?>
									</label>
								</div>
							<?php endforeach ?>
						</div>

					</div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>


<style>
	.modal-lg-max {
		max-width: 1200px;
	}
</style>

<div class="modal fade modal-cart" id="myModal">
	<div class="modal-dialog modal-lg-max">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h5 class="modal-title">Detail Produk</h5>
				<form method="post" class="input_cart">
					<button type="submit" class="btn btn-primary btn-simpan" disabled> SIMPAN</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="load_detail">

				</div>
				<hr>
				<h5 style="font-size: 1rem;">ADD PERLENGKAPAN</h5>
				<div class="buying-selling-group" id="buying-selling-group" data-toggle="buttons">
					<input type="hidden" id="distribusi" value="<?= $dis ?>">

					<div class="row">
						<?php foreach ($perlengkapan as $key => $t) : ?>
							<div class="col-lg-3">
								<div class="card">
									<div class="card-body">
										<h6 style="font-weight: bold;"><?= $t->nm_produk ?> <span class="float-right">Rp. <?= number_format($t->harga, 0) ?></span></h6>

										<div class="row">
											<div class="col-lg-6">
												<p class="text-sm">+ Quantity</p>
											</div>
											<div class="col-lg-6">
												<div class="row">

													<input type="hidden" name="id_toping[]" class="id_toping" value="<?= $t->id_produk ?>">
													<div class="col-lg-2 mt-2"><a href="" class="minus_toping" id_toping="<?= $t->id_produk ?>"><i class="fas fa-minus"></i></a></div>
													<div class="col-lg-8"><input type="text" class="form-control qty_toping qty_perlengkapan text-center qty_toping<?= $t->id_produk ?> " value="0"></div>
													<div class="col-lg-2 mt-2"><a href="" class="plus_toping" id_toping="<?= $t->id_produk ?>"><i class="fas fa-plus"></i></a></div>
												</div>

											</div>
										</div>
									</div>

								</div>
							</div>
						<?php endforeach ?>
					</div>

				</div>
				<hr>
				<h5 style="font-size: 1rem;">ADD TOPING</h5>
				<div class="buying-selling-group" id="buying-selling-group" data-toggle="buttons">
					<input type="hidden" id="distribusi" value="<?= $dis ?>">
					<div class="row">
						<?php foreach ($toping as $key => $t) : ?>
							<div class="col-lg-3">
								<div class="card">
									<div class="card-body">
										<h6 style="font-weight: bold;"><?= $t->nm_produk ?> <span class="float-right">Rp. <?= number_format($t->harga, 0) ?></span></h6>

										<div class="row">
											<div class="col-lg-6">
												<p class="text-sm">+ Quantity</p>
											</div>
											<div class="col-lg-6">
												<div class="row">

													<input type="hidden" name="id_toping[]" class="id_toping" value="<?= $t->id_produk ?>">
													<div class="col-lg-2 mt-2"><a href="" class="minus_toping" id_toping="<?= $t->id_produk ?>"><i class="fas fa-minus"></i></a></div>
													<div class="col-lg-8"><input type="text" class="form-control qty_toping text-center qty_toping<?= $t->id_produk ?> " value="0"></div>
													<div class="col-lg-2 mt-2"><a href="" class="plus_toping" id_toping="<?= $t->id_produk ?>"><i class="fas fa-plus"></i></a></div>
												</div>

											</div>
										</div>
									</div>

								</div>
							</div>
						<?php endforeach ?>
					</div>

				</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="myModalp">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->


			<!-- Modal body -->
			<div class="modal-body">
				<center><br>
					<img width="120" src="<?= base_url() ?>upload/icon/payment.png" alt="">
				</center><br>
				<h5 class="text-center"> Apakah anda yakin ?</h5>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<a href="<?= base_url("produk/payment?dis=$dis") ?>" class="btn btn-primary"> Lanjutkan</a>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>

		</div>
	</div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="<?= base_url('asset/adminlte/js/'); ?>sweetalert2.min.js"></script>

<script>
	$(document).ready(function() {

		hide_default();

		function hide_default() {
			$('#data_voucher').hide();
			$('#save-voucher').hide();
			$('#save-voucher').attr('disabled', 'true');
		}
		$('.back_kategori').click(function(e) {
			e.preventDefault();

			$('.hilang').css('display', 'none');
			$('.muncul').css('display', 'block');

		});
		load_kategori();

		function load_kategori() {
			$.ajax({
				method: "GET",
				url: "<?php echo site_url() ?>Produk/kategori",
				success: function(hasil) {
					$('.data-kategori').html(hasil);
					$('.hilang').css('display', 'none')
				}
			});
		}



		load_cart();

		function load_cart() {
			$.ajax({
				method: "POST",
				url: "<?php echo site_url() ?>Produk/get_cart",
				success: function(hasil) {
					$('#cart').html(hasil);
				}
			});
		}

		$(document).on('click', '.voucher', function(event) {
			var rowid = $(this).attr("id_cart");
			var price_cart = $(this).attr("price_cart");

			$('#id_cart').val(rowid);
			$('#price_cart').val(price_cart);
		});

		$(document).on('submit', '#form-check-voucher', function(event) {
			event.preventDefault();
			$.ajax({
				url: "<?php echo base_url('match/check_voucher'); ?>",
				method: 'POST',
				data: new FormData(this),
				contentType: false,
				processData: false,
				success: function(data) {
					if (data == 'gagal') {
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: ' Voucher tidak tersedia'
						});
						hide_default();
						// $('#id_voucher').attr('disabled','true');
					} else {
						$('#data_voucher').show();

						$('#save-voucher').show();
						$('#save-voucher').removeAttr('disabled', 'true');

						$('#data_voucher').html(data);

					}
				}
			});
		});

		$(document).on('submit', '.input_cart', function(event) {
			event.preventDefault();
			var sku = $("#cart_sku").val();
			var id_produk = $("#cart_id_produk").val();
			var jumlah = $("#cart_jumlah").val();
			var satuan = $("#cart_satuan").val();
			var catatan = $("#cart_catatan").val();
			var id_karyawan = $(".cart_id_karyawan").val();
			var distribusi = $("#distribusi").val();
			var id_toping = $(".id_toping").map(function() {
				return $(this).val();
			}).get();
			var qty_toping = $(".qty_toping").map(function() {
				return $(this).val();
			}).get();

			console.log(id_toping);

			$.ajax({
				url: "<?php echo base_url('Produk/cart'); ?>",
				method: 'POST',
				data: {
					id_produk: id_produk,
					jumlah: jumlah,
					satuan: satuan,
					catatan: catatan,
					id_toping: id_toping,
					distribusi: distribusi,
					qty_toping: qty_toping,
				},
				success: function(data) {
					if (data == 'kosong') {
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: 'Stok tidak cukup'
						});
					}
					if (data == 'null') {
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: 'Data penjual belum diisi'
						});
					}
					$('#cart_session').html(data);
					$('.modal-cart').modal('hide');
					load_cart();
				}
			});
		});


		$(document).on('submit', '#form_voucher', function(event) {
			event.preventDefault();
			$.ajax({
				url: "<?php echo base_url('match/claim_voucher'); ?>",
				method: 'POST',
				data: new FormData(this),
				contentType: false,
				processData: false,
				success: function(data) {
					// alert(data); 
					if (data == 'gagal') {
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'error',
							title: ' Voucher gagal di claim'
						});

					} else {
						Swal.fire({
							toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 3000,
							icon: 'success',
							title: ' Voucher sukses di claim'
						});
						hide_default();
						$('#form-check-voucher').closest('form').find("input[type=text]").val("");
						$('#voucher').modal('hide');
						load_cart();
					}
				}
			});
		});

		$(document).on('click', '.delete_cart', function(event) {
			var rowid = $(this).attr("id");
			$.ajax({
				url: "<?= base_url(); ?>match/delete_cart/",
				method: "POST",
				data: {
					rowid: rowid
				},
				success: function(data) {
					Swal.fire({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 3000,
						icon: 'success',
						title: 'Item dihapus dari keranjang'
					});
					$('#cart_session').html(data);
					load_cart();
				}
			});
		});

		$(document).on('click', '.min_cart', function(event) {
			var rowid = $(this).attr("id");
			var qty = $(this).attr("qty");
			$.ajax({
				url: "<?= base_url(); ?>match/min_cart/",
				method: "POST",
				data: {
					rowid: rowid,
					qty: qty
				},
				success: function(data) {
					// $('#cart_session').html(data); 
					load_cart();
				}
			});
		});
		$(document).on('click', '.plus_cart', function(event) {
			var rowid = $(this).attr("id");
			var qty = $(this).attr("qty");
			var id_produk = $(this).attr("id_produk");
			$.ajax({
				url: "<?= base_url(); ?>match/plus_cart/",
				method: "POST",
				data: {
					rowid: rowid,
					qty: qty,
					id_produk: id_produk
				},
				success: function(data) {
					// $('#cart_session').html(data);

					load_cart();
				}
			});
		});


	});

	$(document).ready(function() {

		$(document).on('click', '.menu_muncul', function(e) {
			e.preventDefault();
			var id_distribusi = $("#id_distribusi").val();
			var id_kategori = $(this).attr('id_kategori');
			var keyword = $("#keyword").val();

			$('#kategori').val(id_kategori);
			$('.hilang').css('display', 'block');
			$('.muncul').css('display', 'none');
			load_data(keyword, id_kategori, id_distribusi);

		});


		function load_data(keyword, kategori, id_distribusi) {
			var id_distribusi = $("#id_distribusi").val();
			$.ajax({
				method: "POST",
				url: "<?php echo site_url() ?>Produk/search_produk",
				data: {
					keyword: keyword,
					kategori: kategori,
					id_distribusi: id_distribusi,
				},
				success: function(hasil) {
					$('.data-produk').html(hasil);
				}
			});
		}
		$(document).on('keyup', '#keyword', function() {
			var keyword = $("#keyword").val();
			var kategori = $("#kategori").val();
			var id_distribusi = $("#id_distribusi").val();
			load_data(keyword, kategori, id_distribusi);
		});
		$('#kategori').change(function() {
			var keyword = $("#keyword").val();
			var kategori = $("#kategori").val();
			var id_distribusi = $("#id_distribusi").val();
			load_data(keyword, kategori, id_distribusi);
		});





	});
</script>

<script>
	$(document).ready(function() {

		$(document).on('click', '.klikdetail', function(e) {
			e.preventDefault();
			$('.qty_toping').val(0);
			var id_kategori = $(this).attr('id_kategori');
			if (id_kategori == 28 || id_kategori == 26) {
				$('.btn-simpan').prop('disabled', false);
			} else {
				$('.btn-simpan').prop('disabled', true);
			}
			var id_produk = $(this).attr('id_produk');
			var id_distribusi = $("#id_distribusi").val();


			$.ajax({
				type: "get",
				url: "<?= base_url('Produk/detail_order') ?>",
				data: {
					id_produk: id_produk,
					id_distribusi: id_distribusi
				},
				success: function(response) {
					$('.load_detail').html(response);

				}
			});


		});

		$('.minus_toping').click(function(e) {
			e.preventDefault();

			var id_toping = $(this).attr('id_toping');
			var qty = $('.qty_toping' + id_toping).val();
			var total = parseFloat(qty) - 1;
			$('.qty_toping' + id_toping).val(total);

			var id_kategori = $("#kategori").val();

			var totalSum = 0;
			$('.qty_perlengkapan').each(function() {
				totalSum += parseFloat($(this).val()) || 0;
			});

			if (id_kategori == 28 || id_kategori == 26) {

			} else {
				if (parseFloat(totalSum) > 0) {
					$('.btn-simpan').prop('disabled', false);
				} else {
					$('.btn-simpan').prop('disabled', true);
				}
			}





		});
		$('.plus_toping').click(function(e) {
			e.preventDefault();

			var id_toping = $(this).attr('id_toping');
			var qty = $('.qty_toping' + id_toping).val();
			var total = parseFloat(qty) + 1;
			$('.qty_toping' + id_toping).val(total);
			var id_kategori = $("#kategori").val();

			var totalSum = 0;
			$('.qty_perlengkapan').each(function() {
				totalSum += parseFloat($(this).val()) || 0;
			});
			if (id_kategori == 28 || id_kategori == 26) {

			} else {
				if (parseFloat(totalSum) > 0) {
					$('.btn-simpan').prop('disabled', false);
				} else {
					$('.btn-simpan').prop('disabled', true);
				}
			}


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