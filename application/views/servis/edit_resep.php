<h5>Bahan : <span style="font-weight: bold; text-decoration: underline;"><?= $produkOne->nm_produk ?></span> <?= $produkOne->satuan ?></h5>
<div class="row mt-4">
    <div class="col-lg-12">
    <table id="tb_servis2" class="table table-hover" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>NAMA MENU</th>
						<th width="10">TAKARAN</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($produk as $k) :
					?>
						<tr>
							<td><?= $i; ?></td>
							<td><?= $k->nm_servis; ?> </td>
                            <td>
                                <input name="takaran[]" onfocus="this.select()" type="text" value="<?= $k->takaran; ?>" class="form-control text-right">
                                <input name="id_servis[]" type="hidden" value="<?= $k->id_servis; ?>">
                                <input name="id_produk[]" type="hidden" value="<?= $k->id_produk; ?>">
                            </td>
						</tr>
					<?php $i++;
					endforeach ?>
				</tbody>
			</table>
    </div>
</div>