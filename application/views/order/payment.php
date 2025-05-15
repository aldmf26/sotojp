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
			<form x-data="paymentHandler" action="<?= base_url() ?>produk/checkout" method="post">
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
												<?= $value['id_produk'] ?>
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
													<input @click="voucherHide = !voucherHide" type="checkbox" class="custom-control-input" id="vcr_inv">
													<label class="custom-control-label" for="vcr_inv">Voucher</label>
												</div>
											</div>
										</div>

										<div x-show="!voucherHide" x-transition class="col-5 vcr_inv">
											<div class="form-group">
												<input x-model="voucher" type="text" @input="resetVoucher" id="data_vcr_inv" name="no_voucher" class="form-control select_vcr_inv" placeholder="Masukan kode voucher">
												<span x-show="errorVoucher !== ''" x-text="errorVoucher" x-transition :class="{'badge bg-success': errorVoucher === 'success', 'badge bg-danger': errorVoucher !== 'success'}">voucher tidak ada</span>
											</div>
										</div>

										<div x-show="!voucherHide" x-transition class="col-1 vcr_inv">
											<button @click="cekVoucher" type="button" id="cek_voucher" class="btn btn-sm mt-1 btn-primary select_vcr_inv">Cek</button>
										</div>
									</div>
								</form>
								<div x-show="!voucherHide" class="form-group row vcr_inv">
									<label for="staticEmail" class="col-md-4 col-form-label">Voucher </label>
									<div class="col-md-6">
										<input x-model="nominal_voucher" type="text" readonly id="nominal_voucher" name="nominal_voucher" class="form-control pembayaran select_vcr_inv" value="0">
										<input type="hidden" id="id_voucher" name="id_voucher" class="form-control pembayaran select_vcr_inv">
									</div>
								</div>



								<?php $total = $subtotal_produk + $ttl_toping  ?>


								<div class="row">

									<div class="col-4">
										<div class="form-group">
											<label for="staticEmail" class="col-md-4 col-form-label">Dp</label>
										</div>
									</div>

									<div class="col-6 d_dp_input">
										<div class="form-group">
											<select x-model="dp" name="kd_dp" class="form-control">
												<option value="">- Pilih DP -</option>
												<?php foreach ($dp as $dp) : ?>
													<option data-nominal="<?= $dp->jumlah_dp ?>" value="<?= $dp->id_dp ?>"><?= $dp->kd_dp ?> | <?= $dp->nama ?> | <?= $dp->ket ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>


								</div>

								<div x-show="dp > 0" x-transition class="form-group row vcr_inv">
									<label for="staticEmail" class="col-md-4 col-form-label">Nominal Dp </label>
									<div class="col-md-6">
										<input x-model="nominal_dp" type="text" readonly id="nominal_dp" name="nominal_dp" class="form-control pembayaran select_vcr_inv" value="0">
										<input type="hidden" id="id_dp" name="id_dp" class="form-control pembayaran select_vcr_inv">
									</div>
								</div>
								<div class="form-group row">
									<label for="staticEmail" class="col-md-4 col-form-label">Total Pembayaran</label>
									<div class="col-md-6">
										<input type="number" class="form-control total_pembayaran" readonly :value="totalPembayaran">
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


								<div class="form-group row">
									<label for="staticEmail" class="col-md-4 col-form-label">CASH</label>
									<div class="col-md-6">
										<input type="number" name="cash" class="form-control pembayaran pemcash" tipe_pem="cash" value="0" id="cash">
									</div>
									<div class="col-md-2">
										<button class="btn btn-primary btn-sm salin_nominal" tipe_pem="cash" type="button"><i class="far fa-copy"></i> nominal</button>
									</div>
								</div>
								<div class="form-group row">
									<label for="staticEmail" class="col-md-4 col-form-label">Transfer</label>
									<div class="col-md-6">
										<input type="number" name="bca" class="form-control pembayaran pembca" tipe_pem="bca" value="0" id="bca">
									</div>
									<div class="col-md-2">
										<button class="btn btn-primary btn-sm salin_nominal" tipe_pem="bca" type="button"><i class="far fa-copy"></i> nominal</button>
									</div>
								</div>


								<input type="hidden" name="total" id="total" value="<?= $total; ?>">
								<input type="hidden" name="id_distribusi" value="<?= $dis; ?>">

								<button class="btn btn-primary btn-block" type="submit" id="proses_bayar" disabled>PROSES BAYAR <i class="fas fa-money-check-alt"></i> <i class="fa fa-chevron-right" style="float: right;"></i></button>

								<script>
									document.addEventListener('DOMContentLoaded', function() {
										const cashInput = document.getElementById('cash');
										const bcaInput = document.getElementById('bca');
										const prosesBayarButton = document.getElementById('proses_bayar');
										const salinNominalButtons = document.querySelectorAll('.salin_nominal');

										function validatePayment() {
											const cashValue = parseFloat(cashInput.value) || 0;
											const bcaValue = parseFloat(bcaInput.value) || 0;

											if (cashValue > 0 || bcaValue > 0) {
												prosesBayarButton.disabled = false;
											} else {
												prosesBayarButton.disabled = true;
											}
										}

										cashInput.addEventListener('input', validatePayment);
										bcaInput.addEventListener('input', validatePayment);

										salinNominalButtons.forEach(button => {
											button.addEventListener('click', function() {
												prosesBayarButton.disabled = false;
											});
										});

										validatePayment(); // Initial check
									});
								</script>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>


	</div>
</div>
<script>
	function paymentHandler() {
		return {
			originalTotalPembayaran: <?= $totalPembayaran ?>, // Simpan total awal
			totalPembayaran: <?= $totalPembayaran ?>,
			voucherHide: true,
			voucher: '',
			nominal_voucher: 0,
			errorVoucher: '',
			voucherApplied: false,

			dp: '',
			nominal_dp: 0,
			id_dp: '',

			resetVoucher() {
				// Jika pengguna mulai mengetik ulang kode voucher, reset semua nilai
				this.totalPembayaran = this.originalTotalPembayaran;
				this.nominal_voucher = 0;
				this.voucherApplied = false;
				this.errorVoucher = '';
			},

			cekVoucher() {
				let url = '<?= base_url('produk/getVoucher') ?>';

				if (this.voucher) {
					url += `?voucher=${this.voucher}&subTotal=${this.originalTotalPembayaran}`;
				}

				fetch(url)
					.then(response => response.json())
					.then(data => {
						if (data.status === 'success') {
							// Setel ulang total ke nilai awal sebelum mengurangi diskon baru
							this.totalPembayaran = this.originalTotalPembayaran;

							this.nominal_voucher = data.nominal;
							this.totalPembayaran -= this.nominal_voucher;

							this.voucherApplied = true;
							this.errorVoucher = 'success';
						} else {
							this.errorVoucher = data.message;
						}
					})
					.catch(error => console.error('Error fetching data:', error));
			},
			cekDp() {
				if (!this.dp) {
					this.nominal_dp = 0;
					this.id_dp = '';
					this.totalPembayaran = this.originalTotalPembayaran - this.nominal_voucher;
					return;
				}

				let url = '<?= base_url('produk/getDp') ?>';

				url += `?dp=${this.dp}`;

				fetch(url)
					.then(res => res.json())
					.then(data => {
						console.log(data);
						if (data.status === 'success') {
							this.nominal_dp = parseInt(data.nominal);
							this.id_dp = this.dp;
							this.totalPembayaran = this.originalTotalPembayaran - this.nominal_dp - this.nominal_voucher;
						} else {
							console.error(data.message);
							this.nominal_dp = 0;
							this.id_dp = '';
							this.totalPembayaran = this.originalTotalPembayaran - this.nominal_voucher;
						}
					})
					.catch(err => console.error('Error fetching DP:', err));
			},

			init() {
				this.$watch('dp', () => {
					this.cekDp();
				});
			}
		}

	}
</script>

<?php $this->load->view('tema/Footer'); ?>
<script>
	$(document).ready(function() {
		$('.salin_nominal').click(function(e) {
			e.preventDefault();
			var total_pembayaran = $('.total_pembayaran').val();
			var tipe_pem = $(this).attr('tipe_pem');
			$('.pem' + tipe_pem).val(total_pembayaran);
		});
	});
</script>