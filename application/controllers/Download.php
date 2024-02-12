<?php

use GuzzleHttp\Client;

defined('BASEPATH') or exit('No direct script access allowed');
class Download extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model('M_invoice');
    }


    public function index()
    {
        $data = [
            'title' => 'Download',
            'invoice' => $this->db->query("SELECT a.no_nota
            FROM tb_invoice as a 
            where a.import = 'T' 
            ")->row(),
            'stok' => $this->db->query("SELECT a.kode_stok_produk
            FROM tb_stok_produk as a 
            where a.import = 'T' 
            ")->row(),
            'pembelian' => $this->db->query("SELECT a.no_nota
            FROM tb_pembelian as a 
            where a.import = 'T' 
            ")->row(),

        ];
        $this->load->view('download/index', $data);
    }

    public function download_menu()
    {
        // Inisialisasi objek client GuzzleHttp
        $client = new Client();

        // URL API
        $url = 'https://crepeapi.ptagafood.com/api/tb_menu_crepe';

        try {
            // Mengirimkan permintaan GET ke API
            $response = $client->request('GET', $url);

            // Mendapatkan kode status respons
            $statusCode = $response->getStatusCode();

            // Jika respons sukses (kode status 200)
            if ($statusCode == 200) {
                $this->db->truncate('tb_servis');
                // Mendapatkan konten respons dalam bentuk string
                $body = $response->getBody()->getContents();

                // Mengubah konten respons menjadi array
                $data = json_decode($body, true);
                if (isset($data['menu']) && is_array($data['menu'])) {
                    // Loop setiap elemen dalam array 'menu'
                    foreach ($data['menu'] as $item) {
                        $data2 = [
                            'id_servis' => $item['id_servis'],
                            'nm_servis' => $item['nm_servis'],
                            'id_kategori' => $item['id_kategori'],
                            'durasi' => $item['durasi'],
                            'menit' => $item['menit'],
                            'biaya' => $item['biaya'],
                            'komisi' => $item['komisi'],
                            'foto' => $item['foto'],
                        ];
                        $this->db->insert('tb_servis', $data2);
                    }
                    redirect('download');
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            echo "Error: " . $e->getMessage();
        }
    }
    public function download_bahan()
    {
        // Inisialisasi objek client GuzzleHttp
        $client = new Client();

        // URL API
        $url = 'https://crepeapi.ptagafood.com/api/tb_bahan_crepe';

        try {
            // Mengirimkan permintaan GET ke API
            $response = $client->request('GET', $url);

            // Mendapatkan kode status respons
            $statusCode = $response->getStatusCode();

            // Jika respons sukses (kode status 200)
            if ($statusCode == 200) {
                $this->db->truncate('tb_produk');
                // Mendapatkan konten respons dalam bentuk string
                $body = $response->getBody()->getContents();

                // Mengubah konten respons menjadi array
                $data = json_decode($body, true);
                if (isset($data['bahan']) && is_array($data['bahan'])) {
                    // Loop setiap elemen dalam array 'bahan'
                    foreach ($data['bahan'] as $item) {
                        $data2 = [
                            'id_produk' => $item['id_produk'],
                            'id_kategori' => $item['id_kategori'],
                            'id_satuan' => $item['id_satuan'],
                            'sku' => $item['sku'],
                            'nm_produk' => $item['nm_produk'],
                            'harga_modal' => $item['harga_modal'],
                            'harga' => $item['harga'],
                            'stok' => $item['stok'],
                            'terjual' => $item['terjual'],
                            'foto' => $item['foto'],
                            'diskon' => $item['diskon'],
                            'komisi' => $item['komisi'],
                            'monitoring' => $item['monitoring'],
                            'qty_toping' => $item['qty_toping'],
                        ];
                        $this->db->insert('tb_produk', $data2);
                    }
                    redirect('download');
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            echo "Error: " . $e->getMessage();
        }
    }
}
