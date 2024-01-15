<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<?php
$ttl_modal = 0;
$ttl_jual = 0;
foreach ($produk as $op) {
	$ttl_modal += $op->harga_modal * $op->stok;
	$ttl_jual += $op->harga * $op->stok;
} ?>
<div class="content-header">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="m-0 text-dark">Record Produk</h1>
				<hr>
				<?php if ($this->session->userdata('id_role') == '1') : ?>
					<a href="<?= base_url("Match/produk_terlaris") ?>" class="btn btn-success mb-2 ">Produk Terlaris</a>

					<a href="<?= base_url("Match/tambah_produk2") ?>" class="btn btn-info mb-2 ">Tambah Produk</a>
					<a href="<?= base_url("Match/export_data_produk") ?>" class="btn btn-info mb-2 ">Export</a>

					<div class="form-group float-right">
						<label>Total Modal</label>
						<p><strong><?= number_format($ttl_modal, 0) ?></strong></p>
					</div>
					<div class="form-group float-right mr-2">
						<label>Total Jual</label>
						<p><strong><?= number_format($ttl_jual, 0) ?></strong></p>
					</div>
					<!-- <a href="<?= base_url("Excel/export") ?>" class="btn btn-info mb-2 ml-2 float-right"><i class="fas fa-file-download"></i> Export</a>		
					<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#import">
						<i class="fas fa-file-upload"></i> Import
					</button> -->
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="container-fluid">

			<hr>

			<div id="loadTabel"></div>

		</div>
	</div>
</div>

<form action="<?= base_url('Excel/import_data'); ?>" method="post" enctype="multipart/form-data">
	<div class="modal fade" id="import" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background: #FFA07A;">
					<h5 class="modal-title" id="exampleModalLabel">Import</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="upload">Import Produk</label>
						<input type="file" name="produk" id="upload" value="" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Import</button>
				</div>
			</div>
		</div>
	</div>
</form>

<?php foreach ($produk as $key => $value) : ?>
	<div id="myModal<?= $value->id_produk ?>" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="background: #FADADD;">
					<h4 class="modal-title">History Monitoring Produk</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<?php
					$status = $this->db->join('tb_produk', 'tb_produk.id_produk = tb_monitoring2.id_produk', 'left')->order_by('tb_monitoring2.id_monitoring2', "desc")->limit(1)->get_where('tb_monitoring2', array('tb_monitoring2.id_produk' => $value->id_produk))->row();
					?>
					<?php if ($status) : ?>
						<?php
						$stok = $this->db->get_where('tb_monitoring', array('id_produk' => $value->id_produk, 'tanggal' => $status->tanggal))->result();

						$status2 = $this->db->get_where('tb_monitoring2', array('id_produk' => $value->id_produk))->result();
						?>
						<?php if ($status->status == "y") : ?>
							<h4 class="text-center"><u><?= $status->nm_produk ?></u></h4>
							<hr>
							<div class="row">
								<div class="col-md-6">
									<h5 class="text-center">Stok Masuk</h5>
									<hr>
									<table class="table table-bordered">
										<tr>
											<th>Tanggal</th>
											<th>Stok Masuk</th>
										</tr>
										<?php foreach ($stok as $stok2) : ?>
											<tr>
												<td><?= $stok2->tanggal ?></td>
												<td><?= $stok2->stok ?></td>
											</tr>
										<?php endforeach ?>
									</table>
								</div>
								<div class="col-md-6">
									<h5 class="text-center">Status</h5>
									<hr>
									<table class="table table-bordered">
										<tr>
											<th>Tanggal</th>
											<th>Status</th>
										</tr>
										<?php foreach ($status2 as $st2) : ?>
											<tr>
												<td><?= date('d F Y', strtotime($st2->tanggal)); ?></td>
												<td>
													<?php if ($st2->status == "y") : ?>
														ON
													<?php else : ?>
														OFF
													<?php endif ?>
												</td>
											</tr>
										<?php endforeach ?>
									</table>
								</div>
							</div>
						<?php else : ?>
							<h5 class="text-center">Opps, Produk tidak di monitoring!</h5>
						<?php endif ?>
					<?php else : ?>
						<h5 class="text-center">Opps, Produk tidak di monitoring!</h5>
					<?php endif ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>
<?php endforeach ?>


<!-- ======================================================== conten ======================================================= -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
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
<script>
	$(document).ready(function() {
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			$.fn.dataTable.tables({
					visible: true,
					api: true
				})
				.responsive.recalc()
				.columns.adjust();
		});
		$.ajax({
			type: "get",
			url: "<?= base_url() ?>/Produk/loadTabel",
			success: function(r) {
				$("#loadTabel").html(r);
				$('#produk').DataTable({
					"paging": false,
					"pageLength": 100,
					// 		"scrollY": "350px",
					"lengthChange": false,
					"ordering": false,
					"info": false,
					"stateSave": true,
					"autoWidth": true,
					// 		"responsive": true,
					"stateSave": true,
					// "order": [ 5, 'DESC' ],
					// "searching": false,
				});
				// 	$('.select').select2()
			}
		});

		$(document).on('click', '.btnDelete', function() {
			var id_produk = $(this).attr('id_produk')
			var filter = $("#countriesDropdown").val()

			if (confirm('Yakin ingin dihapus ? ')) {
				$.ajax({
					type: "GET",
					url: `<?= base_url() ?>match/drop_produk/${id_produk}`,
					success: function(r) {
						loadFilteranFix(filter)
					}
				});
			}
		})


	});

	function loadFilteranFix(filter) {
		$.ajax({
			type: "GET",
			url: `<?= base_url() ?>match/getProdukDrowdown/${filter}`,
			success: function(r) {
				$('#tblDropdown').html(r);
				$('#tabelBefore').css("display", "none");
				$("#produkLoad").DataTable({
					"paging": false,
					"pageLength": 100,
					// 		"scrollY": "350px",
					"lengthChange": false,
					"ordering": false,
					"info": false,
					"stateSave": true,
					"autoWidth": true,
					"stateSave": true,
				})
				// 	$('.select').select2()
			}
		});
	}

	$(document).on('change', '#countriesDropdown', function() {
		var filter = $(this).val()
		loadFilteranFix(filter)
		// filterTable(filter)
	})



	$("#checkAll").click(function() {
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
</script>

<?php $this->load->view('tema/Footer'); ?>