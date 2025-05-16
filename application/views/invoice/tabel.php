<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>

<style type="text/css">
	.modal .modal-dialog-aside {
		width: 500px;
		max-width: 80%;
		height: 100%;
		margin: 0;
		transform: translate(0);
		transition: transform .2s;
	}


	.modal .modal-dialog-aside .modal-content {
		height: inherit;
		border: 0;
		border-radius: 0;
	}

	.modal .modal-dialog-aside .modal-content .modal-body {
		overflow-y: auto
	}

	.modal.fixed-left .modal-dialog-aside {
		margin-left: auto;
		transform: translateX(100%);
	}

	.modal.fixed-right .modal-dialog-aside {
		margin-right: auto;
		transform: translateX(-100%);
	}

	.modal.show .modal-dialog-aside {
		transform: translateX(0);
	}
</style>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->

<div class="content-header">
	<div class="container-fluid">

		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Daftar Invoice</h1>
			</div>
			<div class="col-sm-6">
				<button data-toggle="modal" data-target="#modal-view" class="btn btn-success float-right ml-2"><i class="fas fa-eye"></i> View</button>
				<!-- <a target="_blank" href="<?= base_url("Produk/summary?tgl1=$tgl1&tgl2=$tgl2") ?>" class="btn btn-success float-right"><i class="fas fa-print"></i> Summary</a> -->
				<a target="_blank" href="<?= base_url("Produk/shift_out?tgl1=$tgl1&tgl2=$tgl2") ?>" class="btn btn-success mr-2 float-right"><i class="fas fa-print"></i> Shift Out</a>
				<!-- <button data-toggle="modal" data-target="#laporan-pemasukan" class="btn btn-success"><i class="fas fa-print"></i> Laporan item penjualan</button> -->
			</div>
		</div>
	</div>
	<div style="margin-top: 40px;"></div>
	<div class="row">
		<div class="col-md-12">
			<?= $this->session->flashdata('message'); ?>
		</div>
		<!-- <?php
				$cart =	$this->cart->contents();
				$total = 0;
				?> -->
		<div class="col-sm-12">
			<!-- <a href="<?= base_url() ?>match/order" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a> -->

			<!-- <button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button><br><br> -->
			<div class="card">
				<div class="card-body">
					<div class="table-responsive table-hover">
						<table width="100%" id="example1" class="table table-sm">
							<thead>
								<tr>
									<th>#</th>
									<th>NO NOTA</th>
									<th>JENIS</th>
									<th class="text-right">Grand Total</th>
									<th class="text-right">Voucher</th>
									<th class="text-right">Dp</th>
									<th class="text-right">Total bayar</th>
									<th class="text-right">CASH</th>
									<th class="text-right">TRANSFER</th>
									<th class="text-right">KEMBALIAN</th>
									<th>TANGGAL</th>
									<th>AKSES 1</th>
								</tr>
							</thead>
							<thead>
								<tr>
									<th colspan="3" class="text-right">Total</th>
									<th class="text-right"><?= number_format(array_sum(array_column($invoice, 'total')), 0) ?></th>
									<th class="text-right"><?= number_format(array_sum(array_column($invoice, 'nominal_voucher')), 0) ?></th>
									<th class="text-right"><?= number_format(array_sum(array_map(function ($inv) {
																return empty($inv->kd_dp) ? 0 : $this->db->get_where('tb_dp', ['kd_dp' => $inv->kd_dp])->row()->jumlah_dp;
															}, $invoice)), 0) ?></th>
									<th class="text-right"><?= number_format(array_sum(array_column($invoice, 'bayar')), 0) ?></th>
									<th class="text-right"><?= number_format(array_sum(array_column($invoice, 'cash')), 0) ?></th>
									<th class="text-right"><?= number_format(array_sum(array_column($invoice, 'bca_debit')), 0) ?></th>
									<th class="text-right"><?= number_format(array_sum(array_column($invoice, 'kembali')), 0) ?></th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach ($invoice as $key => $value) : ?>
									<tr class="clickable-row" id="<?= $value->no_nota ?>">
										<td><?= $i++ ?></td>
										<?php
										$nominal_dp = (empty($value->kd_dp)) ? 0 : $this->db->get_where('tb_dp', ['kd_dp' => $value->kd_dp])->row()->jumlah_dp;
										?>
										<td>
											<a href="<?= base_url(); ?>produk/nota?invoice=<?= $value->no_nota; ?>"><?= $value->no_nota ?></a>
										</td>
										<td class="text-right"><?= $value->id_distribusi == 1 ? 'Offline' : 'Online' ?></td>
										<td class="text-right"><?= number_format($value->total, 0) ?></td>
										<td class="text-right"><?= number_format($value->nominal_voucher, 0) ?></td>
										<td class="text-right"><?= number_format($nominal_dp, 0) ?></td>
										<td class="text-right"><?= number_format($value->bayar, 0) ?></td>
										<td class="text-right"><?= number_format($value->cash, 0) ?></td>
										<td class="text-right"><?= number_format($value->bca_debit, 0) ?></td>
										<td class="text-right"><?= number_format($value->kembali, 0) ?></td>
										<td><?= date('d/m/Y', strtotime($value->tgl_jam)) ?></td>
										<?php if ($this->session->userdata('id_role') == '1'): ?>
											<td><button type="button" class="btn btn-danger btn-sm void" data-toggle="modal" data-target="#modalvoid<?= $value->id ?>">
													<i class="fas fa-exclamation"></i> Void
												</button></td>
										<?php endif; ?>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form action="<?= base_url('Match/summary_laporan_pemasukan'); ?>" method="GET">
	<div class="modal fade" id="laporan-pemasukan">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="background:#FFA07A;">
					<h4 class="modal-title">Laporan Pemasukan</h4>
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
								<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" id="picker2"></td>
							</tr>
						</table>

						<input class="form-control" type="date" value="" id="tanggal3" name="tgl1" hidden>
						<input class="form-control" type="date" value="" id="tanggal4" name="tgl2" hidden>
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


<div id="modal_aside_left" class="modal fixed-left fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-aside" role="document">
		<div class="modal-content">
			<div class="modal-header" style="background: #FFA07A;">
				<h5 class="modal-title">Detail Invoice</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="card" id="get_invoice">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<a id="print" class="btn btn-info">Print</a>
			</div>
		</div>
	</div> <!-- modal-bialog .// -->
</div> <!-- modal.// -->

<form action="<?= base_url('Match/invoice'); ?>" method="get">
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

<form action="<?= base_url('Produk/laporan_invoice'); ?>" method="Get">
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
								<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" id="global"></td>
							</tr>
						</table>

						<input class="form-control" type="date" value="" id="global1" name="tgl1" hidden>
						<input class="form-control" type="date" value="" id="global2" name="tgl2" hidden>
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

<!-- Modal Void-->
<?php foreach ($invoice as $key => $value) : ?>
	<form action="<?= base_url('Match/void') ?>" method="post">
		<div class="modal fade" id="modalvoid<?= $value->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Void</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<!-- <div class="col-lg-6">
								<label for="">Voucher</label>
								<input class="form-control" type="text" name="voucher" required>
							</div> -->
							<div class="col-lg-6">
								<label for="">Keterangan</label>
								<input class="form-control" type="text" name="ket_void" required>
							</div>
						</div>
						<input type="hidden" name="no_nota" value="<?= $value->no_nota ?>">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Void</button>
					</div>
				</div>
			</div>
		</div>
	</form>
<?php endforeach; ?>


<?php $this->load->view('tema/Footer'); ?>