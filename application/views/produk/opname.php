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


<?php $this->load->view('tema/Footer'); ?>
<script>
	$(document).ready(function() {
		$(".clickable-row").click(function() {

			var no_opname = $(this).attr("id");
			window.location.href = '<?= base_url(); ?>opname/detail_opname?kode_opname=' + no_opname;
		});
	});
</script>