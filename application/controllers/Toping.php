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
            'bahan' => $this->db->query("SELECT a.id_produk, a.qty_toping, a.nm_produk, b.stok , c.satuan, a.harga, a.id_satuan, d.nm_kategori, a.id_kategori
            FROM tb_produk as a 
            left join (
                SELECT b.id_produk, sum(b.debit - b.kredit) as stok
                FROM tb_stok_produk as b 
                where b.opname = 'T'
                group by b.id_produk
            ) as b on b.id_produk = a.id_produk
            left join tb_satuan as c on c.id_satuan = a.id_satuan
            left join tb_kategori as d on d.id_kategori = a.id_kategori
            where a.id_kategori in ('26','29')
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
            'qty_toping'  => $this->input->post('qty_toping'),
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

        $data_hrga = [
            'id_produk' =>  $id_produk,
            'id_distibusi' => '1',
            'harga' => $this->input->post('harga_offline')
        ];
        $this->db->insert('harga_toping', $data_hrga);
        $data_hrga = [
            'id_produk' =>  $id_produk,
            'id_distibusi' => '2',
            'harga' => $this->input->post('harga_online')
        ];
        $this->db->insert('harga_toping', $data_hrga);

        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
        redirect("Toping");
    }

    public function edit_bahan()
    {
        $id_produk  = $this->input->post('id_produk');
        $data_input = array(
            'nm_produk'   => $this->input->post('nm_produk'),
            'id_satuan'  => $this->input->post('id_satuan'),
            'qty_toping'   => $this->input->post('qty_toping'),
            'harga'   => $this->input->post('harga'),
            'id_kategori'   => $this->input->post('id_kategori'),
        );
        $where = array('id_produk' => $id_produk);
        $res  = $this->M_salon->UpdateData('tb_produk', $data_input, $where);
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
        redirect("Toping");
    }
}
