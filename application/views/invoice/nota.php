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
	<p style=" margin-top: -10px;" align="center" class="huruf">Dutamall Banjarmasin</p>
	<p style=" margin-top: -10px;" align="center" class="huruf">Kota Banjarmasin</p>


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
			<?php foreach ($produk as $p) :
				$toping = $this->db->query("SELECT a.*, b.nm_produk
				FROM tb_pembelian as a 
				left join tb_produk as b on b.id_produk = a.id_produk
				where a.id_produk_toping = '$p->id_produk'
				")->result();
			?>
				<?php
				$total_produk += $p->jumlah * $p->harga;
				$qty_produk += $p->jumlah;
				$nm_servis = strtolower($p->nm_servis);
				$hrg_produk = $p->jumlah * $p->harga;
				$hrg_asli = $p->jumlah * $p->harga_asli;
				?>
				<tr class="huruf" style="margin-bottom: 2px;">
					<td width="10%"><?= $p->jumlah; ?></td>
					<td width="50%"><?= ucwords($nm_servis); ?> <br>
						<?php if ($hrg_asli > $hrg_produk) : ?>
							Discount (- <?= number_format($hrg_asli - $hrg_produk, 0) ?>)
						<?php endif; ?>
					</td>

					<td width="40%" style="text-align: right;">
						<?php if ($hrg_asli > $hrg_produk) : ?>

							<strike><?= number_format($hrg_asli, 0); ?></strike><br>
							<?= number_format($hrg_produk, 0); ?>

						<?php else : ?>
							<?= number_format($hrg_produk, 0); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php
				$total_toping = 0;
				foreach ($toping as $t) :
					$total_toping += $t->harga;
				?>
					<tr class="huruf" style="margin-bottom: 2px;">
						<td width="10%">
						</td>
						<td width="50%" style="font-size: smaller;">
							<?= $t->jumlah; ?> &nbsp; <?= ucwords(strtolower($t->nm_produk)); ?></td>
						<td width="40%" style="text-align: right; font-size: smaller;">
							<?= number_format($t->harga, 0); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</table>
	<hr>
	<table width="100%">
		<!-- <?php if ($qty_produk != 0) : ?>
			<tr class="huruf">
				<td>Subtotal <?= $qty_produk; ?> Product</td>
				<td style="text-align: right;"><?= number_format($total_produk, 0) ?></td>
			</tr>
		<?php endif; ?> -->
		<tr class="huruf">
			<td><strong>Total Tagihan</strong></td>
			<td style="text-align: right;"><strong><?= number_format($total_produk + $total_toping, 0); ?></strong></td>
		</tr>
	</table>
	<hr>
	<table width="100%">

		<?php if ($invoice->nominal_voucher > 0) : ?>
			<tr class="huruf">
				<td>Voucher</td>
				<td style="text-align: right;"><?= number_format($invoice->nominal_voucher, 0); ?></td>
			</tr>
		<?php endif; ?>

		<?php if ($invoice->diskon != 0) : ?>
			<tr class="huruf">
				<td>Diskon</td>
				<td style="text-align: right;"><?= number_format($invoice->diskon, 0); ?></td>
			</tr>
		<?php endif; ?>


		<?php if ($invoice->bca_kredit != 0) : ?>
			<tr class="huruf">
				<td>Kredit BCA</td>
				<td style="text-align: right;"><?= number_format($invoice->bca_kredit, 0); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($invoice->bca_debit != 0) : ?>
			<tr class="huruf">
				<td>Debit BCA</td>
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
		<tr class="huruf">
			<td><strong>Total Pembayaran</strong></td>
			<td style="text-align: right;"><strong><?= number_format($invoice->bayar, 0); ?></strong></td>
		</tr>
		<tr class="huruf">
			<td>Kembalian</td>
			<td style="text-align: right;"><?= number_format($invoice->bayar - $invoice->total, 0); ?></td>
		</tr>
	</table>
	<hr>
	<hr>
	<p class="huruf" align="center">Thank You For Next Order !</p>
	<p class="huruf" align="center" style="margin-top: -10px;">Call 0811-518-870</p>
	<p class="huruf" align="center">Instagram : crepesignature.bjm</p>
	<p class="huruf" align="center">Terbayar</p>
	<p class="huruf" align="center" style="margin-top: -10px;"><-------- <?= date('d M Y h:i'); ?> --------></p>


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