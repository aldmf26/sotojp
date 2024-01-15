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
                <h1 class="m-0 text-dark">Data Toping</h1>
            </div>
            <div class="col-sm-6">
                <?php if ($this->session->userdata('edit_hapus') == '1') : ?>

                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="container">
            <div class="col-12">
                <?= $this->session->flashdata('message'); ?><br>
                <table id="example1" class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NAMA Toping</th>
                            <th>SATUAN</th>
                            <th class="text-right">STOK</th>
                            <th class="text-right">HARGA</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <thead style="background-color: white;">
                        <form action="<?= base_url("Toping/add_toping") ?>" method="POST">
                            <input type="hidden" name="id_kategori" value="26">
                            <tr>
                                <td></td>
                                <td><input style="border:none; border-bottom: solid;" class="form-control" type="text" placeholder="Isi Nama Bahan" name="nm_produk" required></td>
                                <td><select style="border:none; border-bottom: solid;" class="form-control" name="id_satuan" required>
                                        <option value="">- Pilih Satuan -</option>
                                        <?php foreach ($satuan as $s) : ?>
                                            <option value="<?= $s->id_satuan ?>"><?= $s->satuan ?></option>
                                        <?php endforeach; ?>
                                    </select></td>
                                <td><input style="border:none; border-bottom: solid;" class="form-control" type="number" name="stok" required></td>
                                <td><input style="border:none; border-bottom: solid;" class="form-control" type="number" name="harga" required></td>
                                <td>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </td>
                            </tr>
                        </form>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($bahan as $k) : ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $k->nm_produk; ?></td>
                                <td><?= $k->satuan; ?></td>
                                <td class="text-right"><?= $k->stok; ?></td>
                                <td class="text-right"><?= $k->harga; ?></td>
                                <td>
                                    <?php if ($this->session->userdata('id_role') == '1') : ?>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?= $k->id_produk ?>"><i class="fa fa-edit"></i></button>
                                        <a href="<?= base_url() ?>match/drop_bahan/<?= $k->id_produk ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ?')"><i class="fa fa-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php $i++;
                        endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== conten ======================================================= -->
<!-- <form action="<?= base_url('Match/kasbon2'); ?>" method="post">
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
											<td ><label for="">Tanggal</label></td>
											<td>:</td>
											<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" name="tanggal" id="picker3"></td>
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
			</form> -->


<?php foreach ($bahan as $key => $value) : ?>
    <div id="myModal<?= $value->id_produk ?>" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Bahan</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table id="example2" class="table table-striped table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>NAMA BAHAN</th>
                                <th>SATUAN</th>
                                <th width="20%">STOK</th>
                                <th width="20%">HARGA</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <thead style="background-color: white;">
                            <form action="<?= base_url("Match/edit_bahan") ?>" method="POST">
                                <tr>
                                    <input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->id_produk ?>" type="hidden" name="id_produk" required>
                                    <td><input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->nm_produk ?>" type="text" placeholder="Isi Nama Bahan" name="nm_produk" required></td>
                                    <td><select style="border:none; border-bottom: solid;" class="form-control" name="id_satuan" required>

                                            <?php foreach ($satuan as $s) : ?>
                                                <?php if ($s->id_satuan == $value->id_satuan) : ?>
                                                    <option value="<?= $s->id_satuan ?>" selected><?= $s->satuan ?></option>
                                                <?php else : ?>
                                                    <option value="<?= $s->id_satuan ?>"><?= $s->satuan ?></option>
                                                <?php endif ?>
                                            <?php endforeach; ?>
                                        </select></td>
                                    <td width="20%"><input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->stok ?>" type="number" name="stok" required></td>
                                    <td width="20%"><input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->harga ?>" type="number" name="harga"></td>
                                    <td>
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    </td>
                                </tr>
                            </form>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<?php endforeach ?>

<!-- <form action="<?= base_url('Match/summary_kasbon'); ?>" method="post">
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
										<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" name="tanggal" id="pickerkas"></td>
									</tr>
								</table>

								<input class="form-control" type="date" value="" id="tanggalkas1" name="tgl3" hidden>  
								<input class="form-control" type="date" value="" id="tanggalkas2" name="tgl4" hidden> 
							</div>
							<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn" style="background:#FFA07A;">Lanjutkan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form> -->

<!-- <form action="<?= base_url('Match/drop_kasbon_skala'); ?>" method="post">
			<div class="modal fade" id="modal-delete">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header bg-red">
							<h4 class="modal-title">Delete Skala Besar</h4>
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
										<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" name="tanggal" id="picker_drop"></td>
									</tr>
								</table>

								<input class="form-control" type="date" value="" id="tanggal5" name="tgl5" hidden>  
								<input class="form-control" type="date" value="" id="tanggal6" name="tgl6" hidden> 
							</div>
							<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" onclick="return confirm('Yakin Hapus?')" class="btn btn-danger">Lanjutkan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form> -->
<!-- ======================================================== conten ======================================================= -->

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