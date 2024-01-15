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
				<h3 class="float-left">Daftar Stok Produk</h3>
				<a href="<?= base_url() ?>Stok/create_stok_masuk" class="btn btn-outline-secondary float-right ml-2"><i class="fas fa-truck-loading"></i> Stok Masuk</a>
				<a href="#modal-view" data-toggle="modal" class="btn btn-outline-secondary float-right"><i class="fas fa-eye"></i> View</a>
			</div>
		</div>
	</div>



	<div class="row">
		<div class="container-fluid">
			<div class="col-md-12">
				<?= $this->session->flashdata('message'); ?><br>
			</div>


			<table id="example1" class="table table-hover" width="100%">

				<thead>
					<tr>
						<th>#</th>
						<th>TANGGAL</th>
						<th>KODE</th>
						<th>STATUS</th>
						<th>JUMLAH BARANG</th>
						<th>ADMIN</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($stok_produk as $d) : ?>
						<tr class="clickable-row" id="<?= $d->kode_stok_produk ?>">
							<td><?= $i++ ?></td>
							<td><?= date('d M Y, H:i', strtotime($d->tgl_input)) ?></td>
							<td><?= $d->kode_stok_produk ?></td>
							<td><?= $d->status ?></td>
							<?php if ($d->jenis = 'MASUK') : ?>
								<td><?= $d->debit ?></td>
							<?php else : ?>
								<td><?= $d->kredit ?></td>
							<?php endif; ?>
							<td><?= $d->admin ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<form action="" method="GET">
	<div class="modal fade" id="modal-view">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background:#fadadd;">
					<h4 class="modal-title">View Data</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">

						<div class="col-6">
							<div class="form-group">
								<label for="">Dari</label>
								<input type="date" name="tgl1" class="form-control" required>
							</div>
						</div>

						<div class="col-6">
							<div class="form-group">
								<label for="">Sampai</label>
								<input type="date" name="tgl2" class="form-control" required>
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">View</button>
				</div>
			</div>
		</div>
	</div>
</form>



<!-- ======================================================== conten ======================================================= -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>



<script>
	$(document).ready(function() {
		$(".clickable-row").click(function() {
			var kode_stok_produk = $(this).attr("id");


			window.location.href = '<?= base_url(); ?>Stok/detail_produk_masuk?kode=' + kode_stok_produk;


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