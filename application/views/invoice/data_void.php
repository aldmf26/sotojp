<?php $this->load->view('tema/Header', $title); ?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script> 
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>
	
	<div class="content-header">
		<div class="container">

			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Daftar Void</h1>
				</div>
				<div class="col-sm-6">
					<?php if ($this->session->userdata('edit_hapus')=='1'): ?>
						<!-- <button data-toggle="modal" data-target="#modal-detail" class="btn btn-success"><i class="fas fa-download"></i> Detail</button> -->
						<!--<button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>-->
						<!--<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button>-->
						<!-- <button data-toggle="modal" data-target="#modal-delete" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button> -->
					<?php endif ?>
					<button data-toggle="modal" data-target="#modal-view" class="btn btn-success"><i class="fas fa-eye"></i> View</button>
					<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button>
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
							<table width="100%" id="example1" class="table">
								<thead>
									<tr>
										<th rowspan="2">#</th>
										<th rowspan="2">NO NOTA</th>
										<th rowspan="2">NAMA CUSTOMER</th>
										<th colspan="2" class="text-center">MANDIRI</th>
                                        <th colspan="2" class="text-center">BCA</th>
                                        <th rowspan="2">TOTAL</th>
                                        <th rowspan="2">BAYAR</th>
                                        <th rowspan="2">TANGGAL</th>
                                        <th rowspan="2">VOID</th>
										<th rowspan="2">KET VOID</th>
									</tr>
                                    <tr>
                                        <th>KREDIT</th>
                                        <th>DEBIT</th>
                                        <th>KREDIT</th>
                                        <th>DEBIT</th>
                                    </tr>
								</thead>
								<tbody>
								<?php $i=1; ?>
									<?php foreach ($invoice as $key => $value): ?>
										<tr class="clickable-row" id="<?= $value->no_nota ?>" >
											<td><?= $i++ ?></td>
                                            <td><a href="<?= base_url(); ?>match/detail_invoice?invoice=<?= $value->no_nota; ?>"><?= $value->no_nota ?></a></td>
                                            <td><?= $value->nama ?></td>
                                            <td><?= $value->mandiri_kredit ?></td>
                                            <td><?= $value->mandiri_debit ?></td>
                                            <td><?= $value->bca_kredit ?></td>
                                            <td><?= $value->bca_debit ?></td>
                                            <td><?= number_format($value->total) ?></td>
                                            <td><?= number_format($value->bayar) ?></td>
                                            <td><?= date('d/m/Y', strtotime($value->tgl_jam)) ?></td>
                                            <td><?= $value->nm_void ?></td>
											<td><?= $value->ket_void ?></td>
											
											<!-- <?php if ($this->session->userdata('id_role')=='1'): ?>
                                            <td><form action="<?= base_url('Match/void') ?>" method="post">
												<input type="hidden" name="no_nota" value="<?= $value->no_nota ?>">
												<button class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')" type="submit"><i class="fas fa-exclamation"></i> Void</button>
											</form></td>
											<?php endif; ?> -->
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

	<form action="<?= base_url('Match/invoice'); ?>" method="post">
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
												<td ><label for="">Tanggal</label></td>
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

				<form action="<?= base_url('Match/summary_appointment'); ?>" method="post">
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
												<td ><label for="">Tanggal</label></td>
												<td>:</td>
												<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" name="tanggal" id="picker2"></td>
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
	

	<?php $this->load->view('tema/Footer'); ?>

