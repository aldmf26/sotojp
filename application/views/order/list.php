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
				<h1 class="m-0 text-dark">List Penjualan Produk</h1>
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

	</div>
	<div style="margin-top: 40px;"></div>
	<div class="row">
		<div class="col-md-12">
			<?= $this->session->flashdata('message'); ?>
		</div>
		<?php
		$cart =	$this->cart->contents();
		$total = 0;
		?>
		<div class="col-sm-12">
			<a href="<?= base_url() ?>match/order" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
			<button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>
			<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button><br><br>
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table id="example1" class="table text-sm">
							<thead>
								<tr>
									<th>NO.</th>
									<th>No Nota</th>
									<th>PENJUAL</th>
									<th>TANGGAL</th>
									<th>PRODUK</th>
									<th>JUMLAH</th>
									<th>SANTUAN</th>
									<th>HARGA</th>
									<th>DISKON</th>
									<th>TOTAL</th>
									<th>ADMIN</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($list as $key => $value) : ?>
									<?php if (!empty($value->nm_servis)) : ?>
										<tr>
											<td><?= $key + 1 ?></td>
											<td><a href="<?= base_url(); ?>match/detail_invoice?invoice=<?= $value->no_nota; ?>"><?= $value->no_nota; ?></a></td>
											<td><?= $value->nm_karyawan ?></td>
											<td><?= date('d/m/Y', strtotime($value->tanggal)) ?></td>
											<td><?= $value->nm_servis ?></td>
											<td><?= $value->jumlah ?></td>
											<td><?= $value->satuan ?></td>
											<td><?= number_format($value->harga) ?></td>
											<td><?= number_format($value->diskon) ?>%</td>
											<td><?= number_format($value->total) ?></td>
											<td><?= $value->admin ?></td>
										</tr>
									<?php endif; ?>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form action="<?= base_url('Match/list_penjualan'); ?>" method="post">
	<div class="modal fade" id="modal-view">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background:#FFA07A;">
					<h4 class="modal-title">View Data</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<table>
							<tr>
								<td><label for="">Tanggal</label></td>
								<td>:</td>
								<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" name="tanggal" id="picker"></td>
							</tr>
						</table>

						<input class="form-control" type="date" value="" id="tanggal1" name="tgl1" hidden>
						<input class="form-control" type="date" value="" id="tanggal2" name="tgl2" hidden>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn" style="background:#FFA07A;">Lanjutkan</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<form action="<?= base_url('Match/summary_penjualan_produk'); ?>" method="post">
	<div class="modal fade" id="modal-summary">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background:#FFA07A;">
					<h4 class="modal-title">Export Summary</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<table>
							<tr>
								<td><label for="">Tanggal</label></td>
								<td>:</td>
								<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" name="tanggal" id="picker2"></td>
							</tr>
						</table>

						<input class="form-control" type="date" value="" id="tanggal3" name="tgl_list_penjualan1" hidden>
						<input class="form-control" type="date" value="" id="tanggal4" name="tgl_list_penjualan2" hidden>
					</div>
					<div class="modal-footer justify-content-between">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn" style="background:#FFA07A;">Lanjutkan</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

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