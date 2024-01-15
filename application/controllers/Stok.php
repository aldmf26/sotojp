<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        if (!$this->session->userdata('nm_user')) {
            redirect('Login');
        }
        $this->load->model('M_opname');
        date_default_timezone_set('Asia/Makassar');
    }

    //stok produk/////////    

    public function index()
    {

        if (empty($this->input->get('tgl1'))) {
            $tgl1   = date('Y-m-') . '01';
            $tgl2   = date('Y-m-t');
        } else {
            $tgl1   = $this->input->get('tgl1');
            $tgl2   = $this->input->get('tgl2');
        }

        $data = array(
            'title'  => "Stok Produk",
            'stok_produk' => $this->db->select('*')->select_sum('debit')->select_sum('kredit')->where('tgl >=', $tgl1)->where('tgl <=', $tgl2)->where('jenis', 'masuk')->group_by('kode_stok_produk')->order_by('id_stok_produk', 'DESC')->get('tb_stok_produk')->result()
        );


        $this->load->view('stok/stok_produk', $data);
    }

    //stok masuk
    public function create_stok_masuk()
    {
        $produk = $this->db->query("SELECT a.*, b.stok as stok_program, c.nm_kategori, d.satuan
        FROM tb_produk as a
        left join (
            SELECT b.id_produk , sum(b.debit - b.kredit) stok
            FROM tb_stok_produk as b 
            where b.opname ='T'
            group by b.id_produk
        ) as b on b.id_produk = a.id_produk
        left join tb_kategori as c on c.id_kategori = a.id_kategori
        left join tb_satuan as d on d.id_satuan = a.id_satuan
        ")->result();
        $data = array(
            'title'  => "Create Stok Masuk",
            'produk' => $produk,
            'kategori' => $this->db->get('tb_kategori')->result()
            // 'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->get('tb_produk')->result(),
            // 'kategori'    => $this->db->get('tb_produk')->result(),
            // 'produk' => $this->db->get('tb_produk')->result(),
        );

        $this->load->view('stok/create_produk_masuk', $data);
    }

    public function input_produk_masuk()
    {
        $id_produk = $this->input->post('id_stok_produk');
        $kode_stok_produk = 'INV' . date('ymd') . strtoupper(random_string('alpha', 3));
        $admin = $this->session->userdata('nm_user');

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
                'kode_stok_produk' => $kode_stok_produk,
                'id_produk' => $get_produk->id_produk,
                'stok_program' => $get_produk->stok,
                'ttl_stok' => $get_produk->stok,
                'debit' => 0,
                'kredit' => 0,
                'harga' => $get_produk->harga,
                'admin' => $admin,
                'jenis' => 'Masuk',
                'status' => 'Draft',
                'tgl_input' => date('Y-m-d H:i:s'),
                'tgl' => date('Y-m-d'),
                'ket' => 'stok masuk'
            ];
            $this->db->insert('tb_stok_produk', $data);
        }
        redirect("Stok/detail_produk_masuk?kode=$kode_stok_produk");
    }

    public function detail_produk_masuk()
    {
        $kode_stok_produk = $this->input->get('kode');

        $produk = $this->db->query("SELECT a.*, b.stok as stok_program, c.nm_kategori, d.satuan
        FROM tb_produk as a
        left join (
            SELECT b.id_produk , sum(b.debit - b.kredit) stok
            FROM tb_stok_produk as b 
            group by b.id_produk
        ) as b on b.id_produk = a.id_produk
        left join tb_kategori as c on c.id_kategori = a.id_kategori
        left join tb_satuan as d on d.id_satuan = a.id_satuan
        ")->result();
        $data = [
            'title'  => "Detail Produk Masuk",

            'cek_status' => $this->db->group_by('kode_stok_produk')->get_where('tb_stok_produk', ['kode_stok_produk' => $kode_stok_produk])->row(),

            'stok' => $this->db->query("SELECT a.id_stok_produk, b.id_produk, b.nm_produk, e.satuan, d.nm_kategori, b.harga, a.stok_program, a.debit, a.ttl_stok, c.stok as ttl_stok
            FROM tb_stok_produk as a
            left join tb_produk as b on b.id_produk = a.id_produk
            left join (
                SELECT b.id_produk , sum(b.debit - b.kredit) stok
                FROM tb_stok_produk as b 
                where b.opname = 'T'
                group by b.id_produk
            ) as c on c.id_produk = b.id_produk
            left join tb_kategori as d on d.id_kategori = b.id_kategori
            left join tb_satuan as e on e.id_satuan = b.id_satuan
            where a.kode_stok_produk = '$kode_stok_produk'
            group by a.id_produk
            
            ")->result(),

            'kode_stok_produk' => $kode_stok_produk,

            'produk' => $this->M_opname->get_opname(),
            'kategori' => $this->db->get('tb_kategori')->result()
        ];

        $this->load->view('stok/detail_stok_masuk', $data);
    }

    public function tambah_stok_masuk()
    {
        $id_produk = $this->input->post('id_produk_stok');
        $kode_stok_produk = $this->input->post('kode_stok_produk');
        $admin = $this->session->userdata('nm_user');

        foreach ($id_produk as $id) {
            $get_produk = $this->db->get_where('tb_produk', array(
                'id_produk' => $id
            ))->row();
            $cek = $this->db->get_where('tb_stok_produk', [
                'kode_stok_produk' => $kode_stok_produk,
                'id_produk' => $id
            ])->num_rows();
            if ($cek > 0) {
                continue;
            } else {
                $data = [
                    'kode_stok_produk' => $kode_stok_produk,
                    'id_produk' => $get_produk->id_produk,
                    'stok_program' => $get_produk->stok,
                    'ttl_stok' => $get_produk->stok,
                    'debit' => 0,
                    'kredit' => 0,
                    'harga' => $get_produk->harga,
                    'admin' => $admin,
                    'jenis' => 'Masuk',
                    'status' => 'Draft',
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'tgl' => date('Y-m-d'),
                    'ket' => 'stok masuk'
                ];
                $this->db->insert('tb_stok_produk', $data);
            }
        }
        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Produk berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
        redirect("Stok/detail_produk_masuk?kode=$kode_stok_produk");
    }

    public function delete_stok()
    {
        $kode_stok_produk = $this->input->get('kode_stok_produk');
        $this->db->where('kode_stok_produk', $kode_stok_produk);
        $this->db->delete('tb_stok_produk');

        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil dihapus!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
        redirect("Stok");
    }

    public function edit_stok_masuk()
    {
        if ($this->input->post('action') == 'selesai') {

            $id_stok_produk = $this->input->post('id_stok_produk');
            $id_produk = $this->input->post('id_produk');
            $debit = $this->input->post('debit');


            for ($x = 0; $x < sizeof($id_stok_produk); $x++) {

                $dt_produk = $this->db->select('stok_program')->get_where('tb_stok_produk', ['id_stok_produk' => $id_stok_produk[$x]])->row();

                $ttl_stok = $debit[$x] + $dt_produk->stok_program;

                $data = [
                    'debit' => $debit[$x],
                    'status' => 'Selesai',
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'tgl' => date('Y-m-d'),
                    'ttl_stok' => $ttl_stok
                ];
                $this->db->where('id_stok_produk', $id_stok_produk[$x]);
                $this->db->update('tb_stok_produk', $data);
            }
            $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Opname Produk Selesai  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
            redirect("Stok");
        }

        if ($this->input->post('action') == 'edit') {
            $id_stok_produk = $this->input->post('id_stok_produk');
            $id_produk = $this->input->post('id_produk');
            $debit = $this->input->post('debit');
            $tgl_expired = $this->input->post('tgl_expired');


            for ($x = 0; $x < sizeof($id_stok_produk); $x++) {
                $dt_produk = $this->db->select('stok_program')->get_where('tb_stok_produk', ['id_stok_produk' => $id_stok_produk[$x]])->row();

                $ttl_stok = $debit[$x] + $dt_produk->stok_program;
                $data = [
                    'debit' => $debit[$x],
                    'tgl_expired' => $tgl_expired[$x],
                    // 'status' => 'Selesai',
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'tgl' => date('Y-m-d'),
                    'ttl_stok' => $ttl_stok
                ];
                $this->db->where('id_stok_produk', $id_stok_produk[$x]);
                $this->db->update('tb_stok_produk', $data);
            }
            $this->session->set_flashdata('message', '<div class="alert bg-success" role="alert">Data Berhasil Di Edit</div>');
            redirect(isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '');
        }
    }

    public function print_stok_masuk()
    {
        $kode_stok_produk = $this->input->get('kode_stok_produk');
        $data = [
            'stok' => $this->db->group_by('kode_stok_produk')->get_where('tb_stok_produk', ['kode_stok_produk' => $kode_stok_produk])->row(),
            'detail_stok' => $this->db->join('tb_produk', 'tb_stok_produk.id_produk = tb_produk.id_produk', 'left')
                ->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')
                ->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')
                ->get_where('tb_stok_produk', ['kode_stok_produk' => $kode_stok_produk])->result(),
            'kode_stok_produk' => $kode_stok_produk
        ];
        $this->load->view('stok/print_stok_masuk', $data);
    }

    //stok keluar
    public function create_stok_keluar()
    {
        $data = array(
            'title'  => "Create Stok Keluar",
            'produk' => $this->M_opname->get_opname(),
            'kategori' => $this->db->get('tb_kategori')->result()
            // 'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->get('tb_produk')->result(),
            // 'kategori'    => $this->db->get('tb_produk')->result(),
            // 'produk' => $this->db->get('tb_produk')->result(),
        );
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('produk/create_produk_keluar', $data);
        $this->load->view('templates/footer');
    }

    public function input_produk_keluar()
    {
        $id_produk = $this->input->post('id_stok_produk');
        $kode_stok_produk = 'INV' . date('ymd') . strtoupper(random_string('alpha', 3));
        $admin = $this->session->userdata('id_user');

        foreach ($id_produk as $id) {
            $get_produk = $this->db->get_where('tb_produk', array(
                'id_produk' => $id
            ))->row();
            $data = [
                'kode_stok_produk' => $kode_stok_produk,
                'id_produk' => $get_produk->id_produk,
                'stok_program' => $get_produk->stok,
                'ttl_stok' => $get_produk->stok,
                'debit' => 0,
                'kredit' => 0,
                'harga' => $get_produk->harga,
                'admin' => $admin,
                'jenis' => 'Keluar',
                'status' => 'Draft',
                'tgl_input' => date('Y-m-d H:i:s'),
                'tgl' => date('Y-m-d')
            ];
            $this->db->insert('tb_stok_produk', $data);
        }
        redirect("Produk/detail_produk_keluar?kode=$kode_stok_produk");
    }

    public function detail_produk_keluar()
    {
        $kode_stok_produk = $this->input->get('kode');
        $data = array(
            'title'  => "Detail Produk Keluar",

            'cek_status' => $this->db->group_by('kode_stok_produk')->get_where('tb_stok_produk', ['kode_stok_produk' => $kode_stok_produk])->row(),

            'stok' => $this->db->join('tb_produk', 'tb_stok_produk.id_produk = tb_produk.id_produk', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_produk.id_satuan', 'left')->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori')->group_by('tb_stok_produk.id_produk')->get_where('tb_stok_produk', [
                'kode_stok_produk' => $kode_stok_produk,
                'jenis' => 'Keluar'
            ])->result(),

            'kode_stok_produk' => $kode_stok_produk,

            'produk' => $this->M_opname->get_opname(),
            'kategori' => $this->db->get('tb_kategori')->result()
        );
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('produk/detail_stok_keluar', $data);
        $this->load->view('templates/footer');
    }

    public function tambah_stok_keluar()
    {
        $id_produk = $this->input->post('id_produk_stok');
        $kode_stok_produk = $this->input->post('kode_stok_produk');
        $admin = $this->session->userdata('id_user');

        foreach ($id_produk as $id) {
            $get_produk = $this->db->get_where('tb_produk', array(
                'id_produk' => $id
            ))->row();
            $cek = $this->db->get_where('tb_stok_produk', [
                'kode_stok_produk' => $kode_stok_produk,
                'id_produk' => $id
            ])->num_rows();
            if ($cek > 0) {
                continue;
            } else {
                $data = [
                    'kode_stok_produk' => $kode_stok_produk,
                    'id_produk' => $get_produk->id_produk,
                    'stok_program' => $get_produk->stok,
                    'ttl_stok' => $get_produk->stok,
                    'debit' => 0,
                    'kredit' => 0,
                    'harga' => $get_produk->harga,
                    'admin' => $admin,
                    'jenis' => 'Keluar',
                    'status' => 'Draft',
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'tgl' => date('Y-m-d')
                ];
                $this->db->insert('tb_stok_produk', $data);
            }
        }
        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Produk berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
        redirect("Produk/detail_produk_keluar?kode=$kode_stok_produk");
    }
}
