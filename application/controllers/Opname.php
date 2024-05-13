<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Opname extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model('M_invoice');
    }

    public function index()
    {
        if (empty($this->input->get('tgl1'))) {
            $tgl1 = date('Y-m-01');
            $tgl2 = date('Y-m-t');
        } else {
            $tgl1 = $this->input->get('tgl1');
            $tgl2 = $this->input->get('tgl2');
        }


        $opname = $this->db->query("SELECT a.tgl, a.kode_stok_produk as kode_opname, a.status 
        FROM tb_stok_produk as a
        where a.tgl between '$tgl1' and '$tgl2' and a.jenis = 'Opname' 
        group by a.kode_stok_produk
        ORDER BY a.id_stok_produk DESC
        ")->result();

        $data = [
            'title'  => "Crepe | Opname Product",
            'opname' => $opname
        ];
        $this->load->view('produk/opname', $data);
    }

    public function create_opname()
    {
        $produk = $this->db->query("SELECT a.*, if(b.stok is null,0,b.stok) as stok_program, c.nm_kategori, d.satuan
        FROM tb_produk as a
        left join (
            SELECT b.id_produk , sum(b.debit - b.kredit) stok
            FROM tb_stok_produk as b 
            where b.opname = 'T'
            group by b.id_produk
        ) as b on b.id_produk = a.id_produk
        left join tb_kategori as c on c.id_kategori = a.id_kategori
        left join tb_satuan as d on d.id_satuan = a.id_satuan
        ")->result();
        $data = array(
            'title'  => "Crepe | Create Opname Produk",
            'produk'   => $produk,
            'kategori'    => $this->db->get('tb_kategori')->result(),
        );
        $this->load->view('produk/create_opname', $data);
    }

    public function input_opname()
    {
        $id_produk = $this->input->post('id_produk_opname');
        $kode_opname = date('ymd') . strtoupper(random_string('alpha', 3));

        foreach ($id_produk as $id) {
            $get_produk = $this->db->query("SELECT a.id_produk , if(b.stok is null,0,b.stok) as stok, a.harga
            FROM tb_produk as a 
            left join (
                SELECT b.id_produk , sum(b.debit - b.kredit) as stok
                FROM tb_stok_produk as b
                where b.opname = 'T'
                group by b.id_produk
            ) as b on b.id_produk = a.id_produk
            where a.id_produk = '$id'
            ")->row();
            $data = [
                'kode_stok_produk' => $kode_opname,
                'id_produk' => $get_produk->id_produk,
                'stok_program' => $get_produk->stok,
                'status' => 'Draft',
                'tgl' => date('Y-m-d H:i:s'),
                'jenis' => 'Opname',
                'admin' => $this->session->userdata('nm_user')
            ];
            $this->db->insert('tb_stok_produk', $data);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Produk berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
        redirect("Opname/detail_opname?kode_opname=$kode_opname");
    }

    public function detail_opname()
    {
        $kode_opname = $this->input->get('kode_opname');
        $data = array(
            'title'  => "Orchard | Create Opname Produk",
            'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->get('tb_produk')->result(),
            'kategori'    => $this->db->get('tb_kategori')->result(),
            'opname' => $this->db->query("SELECT a.id_stok_produk as id_opname, a.id_produk, a.stok_program, a.debit as stok_aktual, b.harga, b.sku, b.nm_produk, c.satuan, d.nm_kategori, a.ket as catatan, a.status
            FROM tb_stok_produk as a 
            left join tb_produk as b on b.id_produk = a.id_produk
            left join tb_satuan as c on c.id_satuan = b.id_satuan
            left join tb_kategori as d on d.id_kategori = b.id_kategori
            where a.kode_stok_produk = '$kode_opname'
            group by a.id_stok_produk;
            ")->result(),
            'kode_opname' => $kode_opname
        );
        $this->load->view('produk/detail_opname', $data);
    }

    public function edit_stok_aktual()
    {
        if ($this->input->post('action') == 'selesai') {

            $id_opname = $this->input->post('id_opname');
            $id_produk = $this->input->post('id_produk');
            $stok_aktual = $this->input->post('stok_aktual');
            $catatan = $this->input->post('catatan');
            $id_produk = $this->input->post('id_produk');
            $kode_opname = $this->input->post('kode_opname');




            for ($x = 0; $x < sizeof($id_opname); $x++) {
                $data = [
                    'opname' => 'Y'
                ];
                $this->db->where('id_produk', $id_produk[$x]);
                $this->db->where('kode_stok_produk !=', $kode_opname);
                $this->db->update('tb_stok_produk', $data);
                $data = [
                    'debit' => $stok_aktual[$x],
                    'kredit' => '0',
                    'ket' => $catatan[$x],
                    'status' => 'Selesai',
                    'tgl' => date('Y-m-d H:i:s')
                ];
                $this->db->where('id_stok_produk', $id_opname[$x]);
                $this->db->update('tb_stok_produk', $data);
            }

            $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Opname Product Selesai  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
            redirect("Opname");
        }

        if ($this->input->post('action') == 'edit') {
            $id_opname = $this->input->post('id_opname');
            $stok_aktual = $this->input->post('stok_aktual');
            $catatan = $this->input->post('catatan');
            $id_produk = $this->input->post('id_produk');
            $kode_opname = $this->input->post('kode_opname');
            $data = [
                'opname' => 'Y'
            ];
            $this->db->where('kode_stok_produk !=', $kode_opname);
            $this->db->update('tb_stok_produk', $data);

            for ($x = 0; $x < sizeof($id_opname); $x++) {
                $data = [
                    'debit' => $stok_aktual[$x],
                    'kredit' => '0',
                    'ket' => $catatan[$x]
                ];
                $this->db->where('id_stok_produk', $id_opname[$x]);
                $this->db->update('tb_stok_produk', $data);
            }
            $this->session->set_flashdata('message', '<div class="alert bg-success" role="alert">Data Berhasil Di Opname</div>');
            redirect(isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '');
        }
    }
}
