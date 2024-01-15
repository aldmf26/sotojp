<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bahan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model('M_invoice');
    }

    public function index()
    {
        $data = array(
            'title'  => "Orchard Beauty",
            'bahan' => $this->db->query("SELECT a.id_produk, a.nm_produk, b.stok , c.satuan, a.harga
            FROM tb_produk as a 
            left join (
                SELECT b.id_produk, sum(b.debit - b.kredit) as stok
                FROM tb_stok_produk as b 
                where b.opname = 'T'
                group by b.id_produk
            ) as b on b.id_produk = a.id_produk
            left join tb_satuan as c on c.id_satuan = a.id_satuan
            where a.id_kategori = '20'
            ")->result(),
            'satuan' => $this->db->get('tb_satuan')->result()
        );
        $this->load->view('bahan/table', $data);
    }
}
