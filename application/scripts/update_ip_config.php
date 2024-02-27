<?php
// Fungsi untuk mendapatkan IP dinamis dari layanan eksternal
function getDynamicIP()
{
    // Gantikan URL berikut dengan layanan yang menyediakan informasi IP dinamis Anda
    $ip_service_url = 'https://api.ipify.org?format=json';

    // Ambil data JSON dari layanan dan konversi menjadi array
    $ip_data = json_decode(file_get_contents($ip_service_url), true);

    // Ambil alamat IP dari data JSON
    $dynamic_ip = $ip_data['ip'];

    return $dynamic_ip;
}

// Fungsi untuk memperbarui konfigurasi base URL di CodeIgniter
function updateBaseURL($ip)
{
    // Path ke file konfigurasi CodeIgniter
    $config_file = 'application/config/config.php';

    // Baca isi file konfigurasi sebagai string
    $config_content = file_get_contents($config_file);

    // Tentukan base URL baru
    $new_base_url = 'http://' . $ip . '/';

    // Ganti base URL lama dengan yang baru dalam string konfigurasi
    $new_config_content = preg_replace("/(\$config\['base_url'\]\s*=\s*')(.+?)(';\s*)/", "$1$new_base_url$3", $config_content);

    // Tulis kembali isi file konfigurasi dengan base URL yang diperbarui
    file_put_contents($config_file, $new_config_content);
}

// Ambil IP dinamis
$dynamic_ip = getDynamicIP();

// Perbarui base URL di CodeIgniter dengan IP dinamis yang baru
updateBaseURL($dynamic_ip);

echo 'Konfigurasi base URL telah diperbarui dengan IP dinamis: ' . $dynamic_ip;
