<table id="produkLoad" class="table text-sm" width="100%">

	<thead>
		<tr>
			<th>No</th>
			<th>Kategori</th>
			<th>Satuan</th>
			<th>SKU</th>
			<th>Product</th>
			<th>Harga Modal</th>
			<th>Harga Jual</th>
			<th>Stok</th>
			<th>Komisi</th>
			<th>Diskon</th>
			<th>Monitor</th>
			<?php if ($this->session->userdata('id_role') == '1') : ?>
				<th>Aksi</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody id="myTable">
		<?php $i = 1; ?>
		<?php foreach ($produk as $k) : ?>

			<tr>
				<td><?= $i++; ?></td>
				<td><?= $k->nm_kategori; ?></td>
				<td><?= $k->satuan; ?></td>
				<td><?= $k->sku; ?></td>
				<td><a href="<?= base_url() ?>match/story_in_out/<?= $k->id_produk ?>" class="font-weight-bold" style="color: #787878;"><u><?= $k->nm_produk; ?> </u></a></td>
				<td><?= number_format($k->harga_modal); ?></td>
				<td><?= number_format($k->harga); ?></td>
				<td>
					<?= $k->stok ?>
				</td>
				<?php if (!empty($k->komisi)) : ?>
					<td><?= $k->komisi; ?>% </td>
				<?php else : ?>
					<td>0%</td>
				<?php endif; ?>
				<?php if (!empty($k->diskon)) : ?>
					<td><?= $k->diskon; ?>% </td>
				<?php else : ?>
					<td>0%</td>
				<?php endif; ?>
				<td>
					<!-- <a type="button" class="font-weight-bold" data-toggle="modal" data-target="#myModal<?= $k->id_produk ?>"> -->
					<?php if ($k->monitoring == "y") : ?>
						<span style="color: green;">ON</span>
					<?php else : ?>
						<span style="color: red;">OFF</span>
					<?php endif ?>


					<!-- </a> -->
					<!-- <?php if (empty($k->foto)) : ?>
										<img class="img-thumbnail" width="80" src="<?= base_url() ?>upload/produk/not_found.png" alt="">
										<?php else : ?>
											<img class="img-thumbnail" width="80" src="<?= base_url() ?>upload/produk/<?= $k->foto ?>" alt="">
											<img src="" alt="">
										<?php endif ?>
									-->
				</td>
				<?php if ($this->session->userdata('id_role') == '1') : ?>
					<td width="10%">
						<a href="<?= base_url() ?>match/edit_produk/<?= $k->id_produk ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
						<!-- <a href="<?= base_url() ?>match/drop_produk/<?= $k->id_produk ?>" id_produk="<?= $k->id_produk ?>" class="btnDelete btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ?')"><i class="fa fa-trash"></i></a> -->
						<a href="#" id_produk="<?= $k->id_produk ?>" class="btnDelete btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
					</td>
				<?php endif; ?>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>