<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Sinkron extends CI_Controller
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
            'title' => 'Sinkron',
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
        $this->load->view('sinkron/index', $data);
    }



    public function import_invoice()
    {
        // Ambil data dari tabel 'tb_invoice'
        $tb_order = $this->db->get_where('tb_invoice', ['import' => 'T'])->result();

        // Siapkan data yang akan dikirim ke API
        $data1 = [];
        foreach ($tb_order as $t) {
            $data = [
                'import' => 'Y'
            ];
            $this->db->where('id', $t->id);
            $this->db->update('tb_invoice', $data);
            $data1[] = [
                'no_nota' => $t->no_nota,
                'total' => $t->total,
                'bayar' => $t->bayar,
                'kembali' => $t->kembali,
                'cash' => $t->cash,
                'mandiri_debit' => $t->mandiri_debit,
                'mandiri_kredit' => $t->mandiri_kredit,
                'bca_kredit' => $t->bca_kredit,
                'bca_debit' => $t->bca_debit,
                'gopay' => $t->gopay,
                'diskon' => $t->diskon,
                'id_voucher' => empty($t->id_voucher) ? '0' : $t->id_voucher,
                'nominal_voucher' => empty($t->nominal_voucher) ? '0' : $t->nominal_voucher,
                'tgl_jam' => $t->tgl_jam,
                'id_customer' => empty($t->id_customer) ? '0' : $t->id_customer,
                'admin' => $t->admin,
                'status' => $t->status,
                'nm_void' => empty($t->nm_void) ? '0' : $t->nm_void,
                'ket_void' => empty($t->ket_void) ? '0' : $t->ket_void,
                'id_distribusi' => $t->id_distribusi,
                'antrian' => $t->antrian,
                // 'import' => $t->import,
            ];
        }

        // Konfigurasi opsi untuk permintaan HTTP dengan Guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://crepeapi.ptagafood.com/api/api_invoice_crepe', [
            'form_params' => $data1
        ]);

        // Mendapatkan respons dari server
        $responseBody = $response->getBody()->getContents();

        // Tampilkan respons dari server


        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Pembayaran berhasil diubah!<div class="ml-5 btn btn-sm"></div></div>');

        redirect('sinkron');
    }
    public function import_pembelian()
    {
        // Ambil data dari tabel 'tb_invoice'
        $tb_pembelian = $this->db->get_where('tb_pembelian', ['import' => 'T'])->result();

        // Siapkan data yang akan dikirim ke API
        $data1 = [];
        foreach ($tb_pembelian as $t) {
            $data = [
                'import' => 'Y'
            ];
            $this->db->where('id_pembelian', $t->id_pembelian);
            $this->db->update('tb_pembelian', $data);
            $data1[] = [
                'no_nota' => $t->no_nota,
                'id_karyawan' => $t->id_karyawan,
                'id_produk' => $t->id_produk,
                'nm_karyawan' => $t->nm_karyawan,
                'tanggal' => $t->tanggal,
                'jumlah' => $t->jumlah,
                'harga' => $t->harga,
                'diskon' => empty($t->diskon) ? '0'  : $t->diskon,
                'total' => $t->total,
                'admin' => $t->admin,
                'kategori' => $t->kategori,
                'id_produk_toping' => $t->id_produk_toping,
                'id_distribusi' => $t->id_distribusi,
                'void' => $t->void,
            ];
        }

        // Konfigurasi opsi untuk permintaan HTTP dengan Guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://crepeapi.ptagafood.com/api/api_pembelian_crepe', [
            'form_params' => $data1
        ]);

        // Mendapatkan respons dari server
        $responseBody = $response->getBody()->getContents();

        // Tampilkan respons dari server


        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Pembayaran berhasil diubah!<div class="ml-5 btn btn-sm"></div></div>');

        redirect('sinkron');
    }
    public function import_stok()
    {
        // Ambil data dari tabel 'tb_invoice'
        $tb_pembelian = $this->db->get_where('tb_stok_produk', ['import' => 'T'])->result();

        // Siapkan data yang akan dikirim ke API
        $data1 = [];
        foreach ($tb_pembelian as $t) {
            $data1[] = [
                'kode_stok_produk' => $t->kode_stok_produk,
                'id_produk' => $t->id_produk,
                'stok_program' => $t->stok_program,
                'debit' => $t->debit,
                'kredit' => $t->kredit,
                'tgl' => $t->tgl,
                'tgl_input' => $t->tgl_input,
                'admin' => $t->admin,
                'jenis' => $t->jenis,
                'ttl_stok' => $t->ttl_stok,
                'harga' => $t->harga,
                'status' => empty($t->status) ? 'import' : $t->status,
                'ket' => empty($t->ket) ? ' ' : $t->ket,
                'opname' => $t->opname,
            ];
        }

        // Konfigurasi opsi untuk permintaan HTTP dengan Guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://crepeapi.ptagafood.com/api/api_stok_crepe', [
            'form_params' => $data1
        ]);

        // Mendapatkan respons dari server
        $responseBody = $response->getBody()->getContents();

        // Tampilkan respons dari server
        $data = [
            'import' => 'Y'
        ];
        $this->db->where('import', 'T');
        $this->db->update('tb_stok_produk', $data);

        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Pembayaran berhasil diubah!<div class="ml-5 btn btn-sm"></div></div>');

        redirect('sinkron');
    }
}
