<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Toping extends CI_Controller
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
            'title'  => "Crepe Signature",
            'bahan' => $this->db->query("SELECT a.id_produk, a.nm_produk, b.stok , c.satuan, a.harga
            FROM tb_produk as a 
            left join (
                SELECT b.id_produk, sum(b.debit - b.kredit) as stok
                FROM tb_stok_produk as b 
                where b.opname = 'T'
                group by b.id_produk
            ) as b on b.id_produk = a.id_produk
            left join tb_satuan as c on c.id_satuan = a.id_satuan
            where a.id_kategori = '26'
            ")->result(),
            'satuan' => $this->db->get('tb_satuan')->result()
        );
        $this->load->view('toping/table', $data);
    }

    public function add_toping()
    {
        $data = array(
            'nm_produk'   => $this->input->post('nm_produk'),
            'id_satuan'  => $this->input->post('id_satuan'),
            'stok'  => $this->input->post('stok'),
            'harga'  => $this->input->post('harga'),
            'id_kategori'  => $this->input->post('id_kategori'),

        );
        $this->db->insert('tb_produk', $data);
        $id_produk = $this->db->insert_id();
        $sku = 'BHN' . $id_produk;

        $data_sku = [
            'sku' => $sku
        ];


        $this->db->where('id_produk', $id_produk);
        $this->db->update('tb_produk', $data_sku);


        $data = array(
            'id_produk'   => $id_produk,
            'debit'  => 0,
            'kredit'  => 0,
        );
        $this->db->insert('tb_stok_produk', $data);

        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
        redirect("Toping");
    }
}
