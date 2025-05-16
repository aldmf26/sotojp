<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<title>Nota</title>
	<style>
		@media print {
			@page {
				size: 80mm auto;
				margin: 0;
			}

			body {
				margin: 0;
				padding: 0;
			}
		}

		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
		}

		.invoice {
			width: 302px;
			/* fix ukuran 80mm @96dpi */
			margin: auto;
			background: #fff;
			padding: 5px;
		}

		.huruf {
			font-size: 14px;
			line-height: 1.2;
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		td {
			vertical-align: top;
		}

		hr {
			border: none;
			border-top: 1px solid #000;
			margin: 4px 0;
		}

		center img {
			margin-top: 5px;
			margin-bottom: 5px;
		}

		#btnRawbt {
			display: block;
			width: 302px;
			margin: 10px auto;
			padding: 10px;
			text-align: center;
			background-color: #000;
			color: #fff;
			text-decoration: none;
			font-weight: bold;
			border-radius: 5px;
		}
	</style>
</head>

<body>

	<!-- Tombol cetak pakai RawBT -->

	<div class="invoice" id="nota">
		<center>
			<img width="100" src="<?= base_url('asset/'); ?>img/logo_fix.png" alt="">
		</center>
		<p align="center" class="huruf">Soto JP Banjarmasin</p>
		<p align="center" class="huruf">0811-518-870</p>
		<p align="center" class="huruf">ig: sotojp_banjarmasin</p>
		<p align="center" class="huruf">Jl. Pangeran Antasari 147 A, Banjarmasin</p>

		<table>
			<tr>
				<td class="huruf">No Nota</td>
				<td class="huruf">: <?= $invoice->no_nota; ?></td>
			</tr>
			<tr>
				<td class="huruf">Waktu</td>
				<td class="huruf">: <?= date('d M Y', strtotime($invoice->tgl_jam)) ?> <?= date('H:i') ?></td>
			</tr>
			<tr>
				<td class="huruf">Kasir</td>
				<td class="huruf">: <?= $this->session->userdata('nm_user') ?></td>
			</tr>
		</table>

		<hr>

		<table>
			<?php
			$total_produk = 0;
			$qty_produk = 0;
			$total_toping = 0;
			if (!empty($produk)) :
				foreach ($produk as $p) :
					$toping = $this->db->query("SELECT a.*, b.nm_produk FROM tb_pembelian as a 
          LEFT JOIN tb_produk as b ON b.id_produk = a.id_produk
          WHERE a.id_produk_toping = '$p->id_produk' AND a.no_nota = '$no_nota'")->result();

					$total_produk += ($p->jumlah * $p->harga) - $p->diskon;
					$qty_produk += $p->jumlah;
					$nm_servis = strtolower($p->nm_servis);
					$hrg_produk = $p->jumlah * $p->harga;
			?>
					<tr class="huruf">
						<td width="10%"><?= $p->jumlah; ?></td>
						<td width="50%">
							<?= ucwords($nm_servis); ?> <br> @<?= number_format($p->harga, 0); ?>
						</td>
						<td width="40%" style="text-align: right;">
							<?php if (!empty($p->diskon)) : ?>
								<strike><?= number_format($hrg_produk, 0); ?></strike><br>
								<?= number_format($hrg_produk - $p->diskon, 0); ?>
							<?php else : ?>
								<?= number_format($hrg_produk, 0); ?>
							<?php endif; ?>
						</td>
					</tr>
					<?php foreach ($toping as $t) :
						$total_toping += $t->harga * $t->jumlah;
					?>
						<tr class="huruf">
							<td></td>
							<td style="font-size: smaller;"><?= $t->jumlah; ?> &nbsp; <?= ucwords(strtolower($t->nm_produk)); ?></td>
							<td style="text-align: right; font-size: smaller;">
								<?= number_format($t->harga * $t->jumlah, 0); ?>
							</td>
						</tr>
					<?php endforeach; ?>
			<?php
				endforeach;
			endif;
			?>
		</table>

		<hr>

		<table>
			<tr class="huruf">
				<td><strong>Grand Total</strong></td>
				<td style="text-align: right;"><strong><?= number_format($invoice->total, 0); ?></strong></td>
			</tr>
			<?php if ($invoice->diskon != 0) : ?>
				<tr class="huruf">
					<td>Diskon</td>
					<td style="text-align: right; color: red;">-<?= number_format($invoice->diskon, 0); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ($invoice->nominal_voucher > 0) : ?>
				<tr class="huruf">
					<td>Voucher</td>
					<td style="text-align: right;"><?= number_format($invoice->nominal_voucher, 0); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ($invoice->kd_dp) :
				$nominal_dp = $this->db->get_where('tb_dp', ['kd_dp' => $invoice->kd_dp])->row()->jumlah_dp;
			?>
				<tr class="huruf">
					<td>Kode DP: <?= $invoice->kd_dp ?></td>
					<td style="text-align: right;"><?= number_format($nominal_dp, 0); ?></td>
				</tr>
			<?php endif; ?>
		</table>

		<hr>

		<table>
			<tr class="huruf">
				<td><strong>Total Bayar</strong></td>
				<td style="text-align: right;"><strong><?= number_format($invoice->bayar, 0); ?></strong></td>
			</tr>
			<?php if ($invoice->bca_debit != 0) : ?>
				<tr class="huruf">
					<td>Transfer</td>
					<td style="text-align: right;"><?= number_format($invoice->bca_debit, 0); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ($invoice->cash != 0) : ?>
				<tr class="huruf">
					<td>Cash</td>
					<td style="text-align: right;"><?= number_format($invoice->cash, 0); ?></td>
				</tr>
			<?php endif; ?>
			<tr class="huruf">
				<td>Kembalian</td>
				<td style="text-align: right;"><?= number_format($invoice->kembali, 0); ?></td>
			</tr>
		</table>

		<hr>

		<p class="huruf" align="center">Thank You For Next Order!</p>
		<h4 class="huruf" align="center">NOMOR ANTRIAN</h4>
		<h4 align="center"><?= $invoice->antrian ?></h4>
	</div>

	<!-- Tombol cetak -->
	<a href="#" onclick="return cetakNotaRawBT();">🖨️ Print Nota</a>

	<script>
		function centerText(text, width = 32) {
			const space = Math.max(0, Math.floor((width - text.length) / 2));
			return ' '.repeat(space) + text;
		}

		function padItem(name, price, width = 32) {
			let namePart = name;
			let pricePart = price.toString();
			let dots = '.'.repeat(Math.max(1, width - namePart.length - pricePart.length));
			return namePart + dots + pricePart;
		}

		function cetakNotaRawBT() {
			const nota = `
${centerText('KASIROK POS')}
${centerText('Jl. Mawar No.123')}
${centerText('Telp: 0812-3456-7890')}
${'-'.repeat(32)}
Tanggal : 2025-05-16 14:30
Kasir   : Admin
Nota    : INV0001
${'-'.repeat(32)}
${padItem('1x Nasi Goreng', '15.000')}
${padItem('2x Teh Manis', '10.000')}
${padItem('1x Air Mineral', '5.000')}
${'-'.repeat(32)}
${padItem('Subtotal', '30.000')}
${padItem('Diskon', '0')}
${padItem('Total', '30.000')}
Bayar   : 50.000
Kembali : 20.000
${'-'.repeat(32)}
${centerText('Terima kasih')}
${centerText('Barang yang sudah dibeli')}
${centerText('tidak dapat dikembalikan')}
${centerText('---')}
`;

			const rawbtLink = "rawbt:" + encodeURIComponent(nota);
			window.location.href = rawbtLink;
			return false;
		}
	</script>
</body>

</html>