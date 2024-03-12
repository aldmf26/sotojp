<style>
	.invoice {
		margin: auto;
		width: 80mm;
		background: #FFF;
	}

	.huruf {
		font-size: 14px;
	}
</style>
<script>
	window.print();
</script>







<div class="invoice">
	<br>
	<center>
		<img width="100" src="<?= base_url('asset/');  ?>img/crepe_logo.png" alt="">
	</center>
	<p align="center" class="huruf">Crepe Signature Banjarmasin</p>
	<p style=" margin-top: -10px;" align="center" class="huruf">0811-518-870</p>
	<p style=" margin-top: -10px;" align="center" class="huruf">ig:crepesignature.bjm</p>
	<p style=" margin-top: -10px;" align="center" class="huruf">Dutamall</p>
	<!-- <p style=" margin-top: -10px;" align="center" class="huruf">Kota Banjarmasin</p> -->


	<table width="100%">
		<tr>
			<td width="40%" class="huruf">No Nota</td>
			<td style="text-align: left; " class="huruf">: <?= $invoice->no_nota; ?></td>
		</tr>
		<tr>
			<td width="40%" class="huruf">Waktu</td>
			<td style="text-align: left; " class="huruf">: <?= date('d M Y', strtotime($invoice->tgl_jam)) ?> <?= date('H:i') ?></td>
		</tr>
		<tr>
			<td width="40%" class="huruf">Order</td>
			<td style="text-align: left; " class="huruf">: <?= $invoice->admin ?></td>
		</tr>
		<tr>
			<td width="40%" class="huruf">Kasir</td>
			<td style="text-align: left; " class="huruf">: <?= $this->session->userdata('nm_user') ?></td>
		</tr>
	</table>

	<hr>
	<table width="100%">
		<?php
		$total_produk = 0;
		$qty_produk = 0;
		?>
		<?php if (!empty($produk)) : ?>
			<?php $total_toping = 0;
			foreach ($produk as $p) :
				$toping = $this->db->query("SELECT a.*, b.nm_produk
				FROM tb_pembelian as a 
				left join tb_produk as b on b.id_produk = a.id_produk
				where a.id_produk_toping = '$p->id_produk' and a.no_nota = '$no_nota'
				")->result();
			?>
				<?php
				$total_produk += ($p->jumlah * $p->harga) - $p->diskon;
				$qty_produk += $p->jumlah;
				$nm_servis = strtolower($p->nm_servis);
				$hrg_produk = $p->jumlah * $p->harga;
				$hrg_asli = $p->jumlah * $p->harga_asli;

				?>
				<tr class="huruf" style="margin-bottom: 2px;">
					<td width="10%"><?= $p->jumlah; ?></td>
					<td width="50%"><?= ucwords($nm_servis); ?></td>

					<td width="40%" style="text-align: right;">
						<?php if (!empty($p->diskon)) : ?>

							<strike><?= number_format($hrg_produk, 0); ?></strike><br>
							<?= number_format($hrg_produk - $p->diskon, 0); ?>

						<?php else : ?>
							<?= number_format($hrg_produk, 0); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php

				foreach ($toping as $t) :

				?>
					<tr class="huruf" style="margin-bottom: 2px;">
						<td width="10%">
						</td>
						<td width="50%" style="font-size: smaller;">
							<?= $t->jumlah; ?> &nbsp; <?= ucwords(strtolower($t->nm_produk)); ?></td>
						<td width="40%" style="text-align: right; font-size: smaller;">
							<?= number_format($t->harga * $t->jumlah, 0); ?>
						</td>
					</tr>
				<?php $total_toping += $t->harga * $t->jumlah;
				endforeach; ?>
			<?php
			endforeach; ?>
		<?php endif; ?>
	</table>
	<hr>
	<table width="100%">
		<?php if ($qty_produk != 0) : ?>
			<tr class="huruf">
				<td>Subtotal <?= $qty_produk; ?> Product</td>
				<td style="text-align: right;"><?= number_format($total_produk + $total_toping, 0) ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($invoice->diskon != 0) : ?>
			<tr class="huruf">
				<td>Diskon</td>
				<td style="text-align: right;color: red; ">-<?= number_format($invoice->diskon, 0); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($invoice->nominal_voucher > 0) : ?>
			<tr class="huruf">
				<td>Voucher</td>
				<td style="text-align: right;"><?= number_format($invoice->nominal_voucher, 0); ?></td>
			</tr>
		<?php endif; ?>
		<tr class="huruf">
			<td><strong>Grand Total</strong></td>
			<td style="text-align: right;"><strong><?= number_format($total_produk + $total_toping - $invoice->diskon - $invoice->nominal_voucher, 0); ?></strong></td>
		</tr>
	</table>
	<hr>
	<table width="100%">


		<?php if ($invoice->bca_kredit != 0) : ?>
			<tr class="huruf">
				<td>Kredit BCA</td>
				<td style="text-align: right;"><?= number_format($invoice->bca_kredit, 0); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($invoice->bca_debit != 0) : ?>
			<tr class="huruf">
				<td>GRABFOOD</td>
				<td style="text-align: right;"><?= number_format($invoice->bca_debit, 0); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($invoice->mandiri_kredit != 0) : ?>
			<tr class="huruf">
				<td>Kredit Mandiri</td>
				<td style="text-align: right;"><?= number_format($invoice->mandiri_kredit, 0); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($invoice->mandiri_debit != 0) : ?>
			<tr class="huruf">
				<td>Debit Mandiri</td>
				<td style="text-align: right;"><?= number_format($invoice->mandiri_debit, 0); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($invoice->cash != 0) : ?>
			<tr class="huruf">
				<td>Cash</td>
				<td style="text-align: right;"><?= number_format($invoice->cash, 0); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($invoice->gopay != 0) : ?>
			<tr class="huruf">
				<td>GOPAY</td>
				<td style="text-align: right;"><?= number_format($invoice->gopay, 0); ?></td>
			</tr>
		<?php endif; ?>
		<tr class="huruf">
			<td><strong>Total Pembayaran</strong></td>
			<td style="text-align: right;"><strong><?= number_format($invoice->total, 0); ?></strong></td>
		</tr>
		<tr class="huruf">
			<td>Kembalian</td>
			<td style="text-align: right;"><?= number_format($invoice->bayar - $invoice->total, 0); ?></td>
		</tr>
	</table>
	<hr>
	<hr>
	<p class="huruf" align="center">Thank You For Next Order !</p>
	<!-- <p class="huruf" align="center" style="margin-top: -10px;">Call 0811-518-870</p> -->
	<!-- <p class="huruf" align="center">Instagram : crepesignature.bjm</p> -->
	<!-- <p class="huruf" align="center">Terbayar</p>
	<p class="huruf" align="center" style="margin-top: -10px;"><-------- <?= date('d M Y h:i'); ?> -------->
	<h4 class="huruf" align="center">NOMOR ANTRIAN</h4>
	<h4 align="center"><?= $invoice->antrian ?></h4>
	<p class="huruf" align="center">Tunggu nomor kamu dipanggil</p>
	<br>
	<br>
	<br>
	<br>


	<!-- <script>
  var url = document.getElementById('url').value;
    var count = 5; // dalam detik
    function countDown() {
      if (count > 0) {
        count--;
        var waktu = count + 1;
        $('#pesan').html('Anda akan di redirect ke ' + url + ' dalam ' + waktu + ' detik.');
        setTimeout("countDown()", 1000);
      } else {
        window.location.href = url;
      }
    }
    countDown();
  </script>  -->