<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<title>Cetak Nota ke RawBT</title>
	<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
	<style>
		#nota {
			width: 300px;
			/* Lebar 58mm = 300px kira-kira */
			font-size: 12px;
			padding: 10px;
			border: 1px solid #000;
		}
	</style>
</head>

<body>
	<div id="nota">
		<h3>Nota Penjualan</h3>
		<p>Item 1 - Rp10.000</p>
		<p>Item 2 - Rp15.000</p>
		<p>Total: Rp25.000</p>
	</div>
	<br>
	<button onclick="cetakNota()">Cetak Nota</button>

	<script>
		function sendUrlToPrint(url) {
			var beforeUrl = 'intent:';
			var afterUrl = '#Intent;';
			afterUrl += 'component=ru.a402d.rawbtprinter.activity.PrintDownloadActivity;';
			afterUrl += 'package=ru.a402d.rawbtprinter;end;';
			document.location = beforeUrl + encodeURI(url) + afterUrl;
			return false;
		}

		function cetakNota() {
			const nota = document.getElementById("nota");
			html2canvas(nota, {
				scale: 2,
				useCORS: true
			}).then(canvas => {
				// Ubah ke base64 JPEG
				const imgData = canvas.toDataURL("image/jpeg", 0.8);

				// Kirim ke RawBT menggunakan skema intent
				// Langkah: 1. Buat blob dari data base64
				//         2. Upload ke server/file hosting yang menghasilkan URL langsung (public)
				//         3. Kirim URL-nya ke sendUrlToPrint()

				// ❗ DI SINI kamu harus upload ke server terlebih dahulu
				// Simulasi (tidak akan berhasil tanpa file host):
				// const imageUrl = 'https://yoursite.com/temp/nota.jpg';
				// sendUrlToPrint(imageUrl);

				// Sementara solusi langsung (tidak disarankan, tapi bisa uji coba lokal)
				const rawbtData = imgData.replace("data:image/jpeg;base64,", "");
				window.location.href = "rawbt:base64," + rawbtData;
			});
		}
	</script>
</body>

</html>