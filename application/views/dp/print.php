<style>
  .invoice
  {
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
   <img width="150" src="<?= base_url('asset/');  ?>orchard_small.png" alt="">
 </center>
 <p align="center" class="huruf">Crepese Signature Studio</p>
 <p align="center" style="margin-top: -10px;" class="huruf">Orchard Nail</p>
 <p style=" margin-top: -10px;" align="center" class="huruf">081151-88778</p>
 <p style=" margin-top: -10px;" align="center" class="huruf">Jalan KS Tubun 28 B-D RT 20 RW 02 Kelurahan Kelayan</p>
 <p style=" margin-top: -10px;" align="center" class="huruf">Kota Banjarmasin</p>
 

 <table width="100%">
  <tr>
  	<td width="40%" class="huruf">Kode DP</td>
	<td style="text-align: left; " class="huruf">: <?= $dt_dp->kd_dp; ?></td>
  </tr>
  <tr>
  	<td width="40%" class="huruf">Waktu</td>
	<td style="text-align: left; " class="huruf">: <?= date('d M Y', strtotime($dt_dp->tgl_dp)) ?> <?= date('H:i') ?></td>
  </tr>
  <!-- <tr>
  	<td width="40%" class="huruf">Order</td>
	<td style="text-align: left; " class="huruf">: Kasir Orchard</td>
  </tr> -->
  <tr>
  	<td width="40%" class="huruf">Kasir</td>
	<td style="text-align: left; " class="huruf">: Kasir Orchard</td>
  </tr>
  <?php if($dt_dp->id_customer != 0): ?>
  <?php $nm_cutomer = strtolower($dt_dp->nama) ?>
  <tr>
  	<td width="40%" class="huruf">Customer</td>
	<td style="text-align: left; " class="huruf">: <?= ucwords($nm_cutomer); ?></td>
  </tr>
  <?php endif; ?>
   <!-- <tr>
     <td>
      #<?= substr($pesan_2->no_order, 5); ?><br><br>  
      <?= $this->session->userdata('nm_resto'); ?>
    </td>
    <td align="center">
     PAX : <?= $pesan_2->page; ?>
   </td>
   <td>
     <td style="text-align: right;">TABLE <?= $pesan_2->no_meja; ?></td>
   </td>
 </tr> -->
</table>

<hr>
  <table width="100%">
	  
	  <tr class="huruf" style="margin-bottom: 2px;">
            <td width="50%"><?= ucwords($dt_dp->ket); ?></td>
            
            <td width="50%" style="text-align: right;"><strong><?= number_format($dt_dp->jumlah_dp,0) ?></strong></td>  
	  </tr>

	 
  </table>
  <!-- <hr>
  <table width="100%">
  <?php if($invoice->diskon !=0): ?>
	  <tr class="huruf">
		  <td>Diskon</td>
		  <td style="text-align: right;"><?= number_format($invoice->diskon,0); ?></td>
	  </tr>
    <?php endif; ?>
    
    <?php if($invoice->dp !=0): ?>
	  <tr class="huruf">
		  <td>DP</td>
		  <td style="text-align: right;"><?= number_format($invoice->dp,0); ?></td>
	  </tr>
	  <?php endif; ?>
      
	  <?php if($invoice->bca_kredit !=0): ?>
	  <tr class="huruf">
		  <td>Kredit BCA</td>
		  <td style="text-align: right;"><?= number_format($invoice->bca_kredit,0); ?></td>
	  </tr>
	  <?php endif; ?>
	  <?php if($invoice->bca_debit !=0): ?>
	  <tr class="huruf">
		  <td>Debit BCA</td>
		  <td style="text-align: right;"><?= number_format($invoice->bca_debit,0); ?></td>
	  </tr>
	  <?php endif; ?>
	  <?php if($invoice->mandiri_kredit !=0): ?>
	  <tr class="huruf">
		  <td>Kredit Mandiri</td>
		  <td style="text-align: right;"><?= number_format($invoice->mandiri_kredit,0); ?></td>
	  </tr>
	  <?php endif; ?>
	  <?php if($invoice->mandiri_debit !=0): ?>
	  <tr class="huruf">
		  <td>Debit Mandiri</td>
		  <td style="text-align: right;"><?= number_format($invoice->mandiri_debit,0); ?></td>
	  </tr>
	  <?php endif; ?>
	  <?php if($invoice->cash !=0) : ?>
	  <tr class="huruf">
		  <td>Cash</td>
		  <td style="text-align: right;"><?= number_format($invoice->cash,0) ; ?></td>
	  </tr>
	  <?php endif; ?>
	  <tr class="huruf">
		  <td><strong>Total Pembayaran</strong></td>
		  <td style="text-align: right;"><strong><?= number_format($invoice->bayar,0); ?></strong></td>
	  </tr>
	  <tr class="huruf">
		  <td>Kembalian</td>
		  <td style="text-align: right;"><?= number_format($invoice->bayar - $invoice->total,0); ?></td>
	  </tr>
  </table> -->
  <hr>
  <hr>
  <p class="huruf" align="center">Thank You For Next Appointment !</p>
  <p class="huruf" align="center" style="margin-top: -10px;">Call 081151-88778</p>
  <p class="huruf" align="center">Instagram : orchard.nail</p>
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
