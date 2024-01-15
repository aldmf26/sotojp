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
				<h1 class="m-0 text-dark">Opname Product</h1>
			</div>
			<div class="col-sm-6">
				<?php if ($this->session->userdata('edit_hapus') == '1') : ?>
					<!-- <button data-toggle="modal" data-target="#modal-detail" class="btn btn-success"><i class="fas fa-download"></i> Detail</button> -->
					<!--<button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>-->
					<!--<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button>-->
					<!-- <button data-toggle="modal" data-target="#modal-delete" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button> -->
				<?php endif ?>
				<a href="<?= base_url() ?>Opname/create_opname" class="btn btn-success float-right ml-2"><i class="fas fa-plus"></i> Stok Opname</a>
				<a href="<?= base_url('Export') ?>" class="btn btn-success float-right ml-2"><i class="fas fa-file-excel"></i> Export all</a>
				<button data-toggle="modal" data-target="#modal-view" class="btn btn-success float-right"><i class="fas fa-eye"></i> View</button>

			</div>
		</div>
	</div>

	<div class="row">
		<div class="container">
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
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($opname as $d) : ?>
						<tr class="clickable-row" id="<?= $d->kode_opname ?>">
							<td><?= $i++ ?></td>
							<td><?= date('d M Y', strtotime($d->tgl)) ?>, <?= date('H:i', strtotime($d->tgl)) ?></td>
							<td><?= $d->kode_opname ?></td>
							<td><?= $d->status ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>


		</div>
	</div>
</div>



<!-- ======================================================== conten ======================================================= -->

<form action="<?= base_url('Opname'); ?>" method="get">
	<div class="modal fade" id="modal-view">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background: #FFA07A;">
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
								<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" id="picker"></td>
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
		$(".clickable-row").click(function() {

			var no_opname = $(this).attr("id");
			window.location.href = '<?= base_url(); ?>opname/detail_opname?kode_opname=' + no_opname;
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