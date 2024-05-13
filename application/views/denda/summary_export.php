<table class="table" border="1">
	<thead>
		<th colspan="3"><?= $sort ?></th>
	</thead>
	<thead>
		<tr>
			<th>NAMA</th>
			<th>NOMINAL</th>
			<th>ALASAN</th>
		</tr>
	</thead>

	<tbody style="text-align: center;">
		<?php
		$ttl = 0;
		foreach ($denda as $k) :
			$ttl += $k->total;
		?>
			<tr>
				<td><?= $k->nm_denda; ?></td>
				<td><?= number_format($k->total, 0) ?></td>
				<td style="width: 150px;"><?= $k->alasan ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<th>TOTAL</th>
			<th><?= number_format($ttl, 0); ?></th>
			<th></th>
		</tr>
	</tfoot>
</table>


<style>
	@media print {

		.no_print,
		.no-print * {
			display: none !important;
		}
	}
</style>
<hr>
<a href="<?= base_url('Match/excel_denda_sum/') . $tgl1 . '/' . $tgl2; ?>" class="no_print" target="_blank"><i>Excel_Summary Klik Here ...</i></a>