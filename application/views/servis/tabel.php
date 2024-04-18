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
				<h1 class="m-0 text-dark">Data Produk</h1>

				<!-- <button type="button" class="hapus">test</button> -->
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
		<nav class="nav nav-pills">
			<a class="nav-link <?php echo (current_url() == base_url('Match/dt_servis')) ? 'active' : ''; ?>" href="<?= base_url('Match/dt_servis'); ?>">Data Menu</a>
			<a class="nav-link <?php echo (current_url() == base_url('Match/dt_resep')) ? 'active' : ''; ?>" href="<?= base_url('Match/dt_resep'); ?>">Data Resep</a>
		</nav>
	</div>

	<div class="row">

		<div class="col-12">
			<?= $this->session->flashdata('message'); ?><br>
			<table class="table" width="100%">
				<thead>
					<tr>
						<th>NAMA PRODUK</th>
						<th>KATEGORI</th>
						<th>HARGA OFFLINE</th>
						<th>HARGA ONLINE</th>
						<th>AKSI</th>
					</tr>
				</thead>
				<tbody style="background-color: white;">
					<form action="<?= base_url("Match/add_servis") ?>" method="POST">
						<input type="hidden" name="komisi" value="5">
						<tr>
							<td><input style="border:none; border-bottom: solid;" class="form-control" type="text" placeholder="Isi Nama Servis" name="servis" required></td>
							<td>
								<select name="id_kategori" id="" class="select">
									<option value="">Pilih Kategori</option>
									<?php foreach ($kategori as $k) : ?>
										<option value="<?= $k->id_kategori ?>"><?= $k->nm_kategori ?></option>
									<?php endforeach ?>
								</select>
							</td>
							<td><input style="border:none; border-bottom: solid;" class="form-control" type="number" placeholder="Cth : 100000" name="biaya_offline" required></td>
							<td><input style="border:none; border-bottom: solid;" class="form-control" type="number" placeholder="Cth : 100000" name="biaya_online" required></td>
							<td>
								<button type="submit" class="btn btn-primary btn-sm">Simpan</button>
							</td>
						</tr>
					</form>
				</tbody>
				</tabel>
		</div>
	</div>
	<div class="row">
		<div class="col-12">


			<table id="tb_servis" class="table" width="100%">
				<thead>
					<tr>
						<th width="6%">No</th>
						<th>NAMA PRODUK</th>
						<th width="15%">KATEGORI</th>
						<th width="15%">BIAYA</th>
						<th>RESEP</th>
						<th width="10%">AKSI</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($kasbon as $k) :
						$biaya = $this->db->get_where('tb_harga', ['id_servis' => $k->id_servis])->result()
					?>
						<tr>
							<td><?= $i; ?></td>
							<td><?= $k->nm_servis; ?> </td>
							<td><?= $k->nm_kategori; ?></td>
							<td>
								<?php foreach ($biaya as $b) : ?>
									<?= $b->distirbusi == '1' ? 'Offline:' : 'Online:' ?> <?= number_format($b->harga, 0) ?> <br>
								<?php endforeach ?>

							</td>
							<td class="get_btn_resep" id="td_btn<?= $k->id_servis ?>"></td>
							<td>
								<?php if ($this->session->userdata('id_role') != "3") : ?>
									<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?= $k->id_servis ?>"><i class="fa fa-edit"></i></button>

									<a href="<?= base_url() ?>match/drop_servis/<?= $k->id_servis ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ?')"><i class="fa fa-trash"></i></a>
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

<!-- ======================================================== conten ======================================================= -->
<form action="<?= base_url('Match/kasbon2'); ?>" method="post">
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
</form>


<?php foreach ($kasbon as $key => $value) : ?>
	<div id="myModal<?= $value->id_servis ?>" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Ubah Servis</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered" width="100%">
						<thead>
							<tr>
								<th>NAMA SERVIS</th>
								<th>KATEGORI</th>
								<th>HARGA OFFLINE</th>
								<th>HARGA ONLINE</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<thead style="background-color: white;">
							<form action="<?= base_url("Match/edit_servis") ?>" method="POST">
								<tr>
									<input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->id_servis ?>" type="hidden" name="id_servis" required>
									<td><input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->nm_servis ?>" type="text" placeholder="Isi Nama Servis" name="servis" required></td>

									<td>
										<select name="id_kategori" id="" class="select">
											<option value="">Pilih Kategori</option>
											<?php foreach ($kategori as $k) : ?>
												<option value="<?= $k->id_kategori ?>" <?= $k->id_kategori == $value->id_kategori ? 'selected' : '' ?>><?= $k->nm_kategori ?></option>
											<?php endforeach ?>
										</select>
									</td>

									<td><input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->harga_offline ?>" type="number" name="biaya_offline" required></td>
									<td><input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->hrga_online ?>" type="number" name="biaya_online" required></td>
									<!-- <td><input style="border:none; border-bottom: solid;" class="form-control" value="<?= $value->komisi ?>" type="text" placeholder="Cth : 30" name="komisi" required></td> -->
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

<div id="modal_resep" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="background:#FFA07A;">
				<h4 class="modal-title">Resep</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post" class="input_resep">
					<table class="table">
						<thead>
							<tr>
								<th width="40%">Tambah Bahan</th>
								<th width="20%">Takaran</th>
								<th width="20%">Satuan</th>
								<th width="20%">Aksi</th>
							</tr>
						</thead>
						<tbody id="input_resep">
						</tbody>
					</table>
				</form>
				<form class="ubah_resep" method="POST">
					<table class="table">
						<thead>
							<tr>
								<th>Bahan</th>
								<th>Takaran</th>
								<th>Satuan</th>
								<th>Aksi</th>
							</tr>
						</thead>

						<tbody id="data_resep">

						</tbody>

					</table>
				</form>
				<!-- <div id="data_resep">
		
		</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>


<!-- ======================================================== conten ======================================================= -->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

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

		get_btn_reseps();

		function get_btn_reseps() {
			$.ajax({
				url: "<?php echo base_url('match/get_btn_reseps'); ?>",
				method: "POST",
				dataType: "JSON",
				success: function(data) {
					// console.log(data);
					$.each(data, function(i, item) {
						// console.log(item.id_servis);
						if (item.jml > 0) {
							var html_code = '<button id="' + item.id_servis + '" type="button" class="btn btn-danger btn-sm btn_resep btn_resep' + item.id_servis + '" data-toggle="modal" data-target="#modal_resep">Resep(' + item.jml + ')</button>';
						} else {
							var html_code = '<button id="' + item.id_servis + '" type="button" class="btn btn-success btn-sm btn_resep btn_resep' + item.id_servis + '" data-toggle="modal" data-target="#modal_resep">Resep</button>';
						}

						$('#td_btn' + item.id_servis).html(html_code);
					});
					//   $('#input_resep').html(data);
					//   $('.select').select2();
				}

			});
		}





		function get_input_resep(id_servis) {
			$.ajax({
				url: "<?php echo base_url('match/get_input_resep'); ?>",
				method: "POST",
				data: {
					id_servis: id_servis
				},
				success: function(data) {
					$('#input_resep').html(data);
					$('.select').select2();
				}

			});
		}

		function get_resep(id_servis) {
			$.ajax({
				url: "<?php echo base_url('match/get_resep'); ?>",
				method: "POST",
				data: {
					id_servis: id_servis
				},
				success: function(data) {
					$('#data_resep').html(data);
					$('.select').select2();
				}

			});
		}

		function get_btn_resep(id_servis) {
			// console.log(id_servis);
			$.ajax({
				url: "<?php echo base_url('match/get_btn_resep'); ?>",
				method: "POST",
				data: {
					id_servis: id_servis
				},
				dataType: "JSON",
				success: function(data) {
					// console.log('yes');

					// if(item.jml > 0){
					// 	var html_code = '<button id="'+data.id_servis+'" type="button" class="btn btn-danger btn-sm btn_resep btn_resep'+data.id_servis+'" data-toggle="modal" data-target="#modal_resep">Resep('+data.jml+')</button>';
					// }else{
					// 	var html_code = '<button id="'+data.id_servis+'" type="button" class="btn btn-success btn-sm btn_resep btn_resep'+data.id_servis+'" data-toggle="modal" data-target="#modal_resep">Resep</button>';
					// }

					// $('#td_btn'+data.id_servis).html(html_code);

				}

			});
		}



		// $('.btn_resep').click(function() {
		$("table").on("click", ".btn_resep", function() {
			var id_servis = $(this).attr('id');

			get_input_resep(id_servis);

			//   $('#id_servis_bahan').val(id_servis);

			get_resep(id_servis);


			//   Swal.fire({
			//                 toast: true,
			//                 position: 'top-end',
			//                 showConfirmButton: false,
			//                 timer: 3000,
			//                 icon: 'success',
			//                 title: 'Test' + id_resep
			//               });




			// $.ajax({
			//     url:"<?= base_url(); ?>"+post_akun+"/get_detail/",
			//     method:"POST",
			//     data:{id_nota:id_nota},
			//     success:function(data){
			//       $('#modal_detail_kas').modal('show');
			//       $('#modal-title').text("Detail "+modal_title);
			//       $('#detail_kas').html(data);

			//     }

			//   });
		});



		$(document).on('submit', '.input_resep', function(event) {
			event.preventDefault();
			var id_servis = $('#id_servis_bahan').val();

			$.ajax({
				url: "<?php echo base_url('match/add_resep'); ?>",
				method: 'POST',
				data: new FormData(this),
				contentType: false,
				processData: false,
				success: function(data) {
					Swal.fire({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 3000,
						icon: 'success',
						title: 'Resep Berhasil ditambah'
					});
					//   $('#takaran').val("");
					//   $('#id_produk').val("");
					// $('#input_resep')[0].reset();
					get_input_resep(id_servis);
					get_resep(id_servis);

					//   get_btn_resep(id_servis);
					get_btn_reseps();

				}
			});
		});

		$(document).on('submit', '.ubah_resep', function(event) {
			event.preventDefault();
			id_servis = $('#id_servis_bahan').val();

			$.ajax({
				url: "<?php echo base_url('match/edit_resep'); ?>",
				method: 'POST',
				data: new FormData(this),
				contentType: false,
				processData: false,
				success: function(data) {
					Swal.fire({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 3000,
						icon: 'success',
						title: 'Resep Berhasil diubah'
					});
					get_resep(id_servis);
				}
			});
		});

		$("table").on("change", "#id_produk", function() {
			var id_produk = $('#id_produk').val();
			//   alert(id_produk);
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url('Match/get_satuan'); ?>',
				data: {
					'id_produk': id_produk
				},
				success: function(data) {
					$("#satuan").val(data);
				}

			})
		});



		$("table").on("click", ".hapus_resep", function() {
			var id_servis = $('#id_servis_bahan').val();
			var id_resep = $(this).attr("id");
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url('Match/drop_resep'); ?>',
				data: {
					id_resep: id_resep
				},
				success: function(data) {
					Swal.fire({
						toast: true,
						position: 'top-end',
						showConfirmButton: false,
						timer: 3000,
						icon: 'success',
						title: 'Resep Berhasil dihapus'
					});
					get_resep(id_servis);
					get_btn_reseps();
				}

			})
		});


	});
</script>

<?php $this->load->view('tema/Footer'); ?>