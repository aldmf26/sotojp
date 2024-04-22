<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
class Match extends CI_Controller {

    public function __construct()
    {
      parent::__construct();
      if(!$this->session->userdata('nm_user')){
		redirect('Login');
      }
      date_default_timezone_set('Asia/Makassar');
      $this->load->model('M_invoice');
  }

  public function index(){
    $data = array(
        'title' => "Beranda", 
    );
    $this->load->view('beranda', $data);
}

// ================================== CLEAR DATA ==========================================
public function trash(){

    $data = array(
        'title'  => "Orchard Appointment", 
    );
    $this->load->view('bersih/form', $data);

}

public function trash2(){

    $tgl1 = $this->input->post('tgl1');
    $tgl2 = $this->input->post('tgl2');
    $db = $this->input->post('db');

    // $app    = "Delete from tb_appoiment WHERE tgl BETWEEN '$tgl1' AND '$tgl2'";
    // $res1   = $this->db->query($app);

    // $cancel    = "Delete from tb_cancel WHERE tgl BETWEEN '$tgl1' AND '$tgl2'";
    // $res1   = $this->db->query($cancel);

    // $order    = "Delete from tb_order WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'";
    // $res1   = $this->db->query($order);

    // $terapis    = "Delete from tb_terapis WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'";
    // $res1   = $this->db->query($terapis);

    // $denda  = "Delete from ctt_denda WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'";
    // $res2   = $this->db->query($denda);

    // $kasbon = "Delete from ctt_kasbon WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'";
    // $res3   = $this->db->query($kasbon);

    // $tips   = "Delete from ctt_tips WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'";
    // $res4   = $this->db->query($tips);

    foreach($db as $d){
        $data = explode('|', $d);
        $delete    = "Delete from $data[0] WHERE $data[1] BETWEEN '$tgl1' AND '$tgl2'";
        $res   = $this->db->query($delete);
    }
    

    

    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">*Data  Berhasil Di Hapus !! <div class="ml-5 btn btn-sm"><i class="fas fa-trash fa-2x"></i></div></div>');
    redirect("Match/trash");

}
// ================================== END CLEAR DATA ==========================================

// ================================== PRODUK ==========================================

public function kategori()
{
    $data = array(
        'title'  => "Orchard Kategori", 
        'kategori'    => $this->db->get('tb_kategori')->result(),
    );
    $this->load->view('kategori/tabel', $data);
}

public function story_in_out($id_produk)
{

    $data = array(
        'title' => 'Story In Out',
        'detail'    => $this->db->select('*, SUM(tb_jurnal.qty) AS qty')->join('tb_jurnal', 'tb_jurnal.id_produk = tb_produk.id_produk', 'left')->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->group_by('tb_jurnal.tgl')->order_by('tb_jurnal.tgl','DESC')->get_where('tb_produk', array('tb_jurnal.id_produk' => $id_produk))->result(),
        'produk'    => $this->db->get_where('tb_produk', array('id_produk' => $id_produk))->row()
    );
    $this->load->view('produk/story_in_out', $data);

}







public function produk()
{
    $cek = ['13'];
    $data = array(
        'title'  => "Crepe Produk", 
        'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->where_not_in('tb_produk.id_kategori',$cek)->get('tb_produk')->result(),
        'kategori'    => $this->db->get('tb_kategori')->result(),
        'satuan'    => $this->db->get('tb_satuan')->result(),
    );
    $this->load->view('produk/tabel', $data);
}
public function export_produk()
{
    $cek = ['13'];
    $data = array(
        'title'  => "Crepe Produk", 
        'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->where_not_in('tb_produk.id_kategori',$cek)->get('tb_produk')->result(),
        'kategori'    => $this->db->get('tb_kategori')->result(),
        'satuan'    => $this->db->get('tb_satuan')->result(),
    );
    $this->load->view('produk/export_data_produk', $data);
}

public function produk_perlengkapan()
{
    $cek = ['13','15'];
    $data = array(
        'title'  => "Orchard Produk", 
        'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->where_in('tb_produk.id_kategori',$cek)->get('tb_produk')->result(),
        'kategori'    => $this->db->get('tb_kategori')->result(),
        'satuan'    => $this->db->get('tb_satuan')->result(),
    );
    $this->load->view('produk/produk_perlengkapan', $data);
}

public function satuan(){
    $data = [
        'title' => 'Orchard | Satuan',
        'satuan' => $this->db->get('tb_satuan')->result()
    ];

    $this->load->view('produk/satuan',$data);
}

public function add_produk()
{
    $valid = $this->form_validation;

    $valid->set_rules(
        'nama',
        '<i class="feather icon-info"></i> Nama produk',
        'required',
        array(
            'requiured' => 'harus di isi!'
        )
    );

    if ($valid->run()) {

        $config['upload_path']       = './upload/produk/';
        $config['allowed_types']     = 'gif|jpg|png|jpeg';
        $config['max_size']          = '2400';
        $config['max_width']         = '2024';
        $config['max_height']        = '2024';

        $this->load->library('upload', $config);
        $this->load->helper('string');

        if (!$this->upload->do_upload('foto')) {
            $i = $this->input;
            $data = array(
                'id_kategori'          => $i->post('id_kategori'),
                'id_satuan'          => $i->post('id_satuan'),
                'nm_produk'      => $i->post('nama'),
                'harga'      => $i->post('harga'),
                'harga_modal'      => $i->post('harga_modal'),
                'stok'      => $i->post('stok'),
                'diskon'      => $i->post('diskon'),
                'komisi' => 5,
            );
            $this->db->insert('tb_produk', $data);
            $id_produk = $this->db->insert_id();
            $sku = 'OBP'.$id_produk;

            $data_sku = [
                'sku' => $sku
            ];

            $this->db->where('id_produk', $id_produk);
            $this->db->update('tb_produk', $data_sku);
            $this->session->set_flashdata('sukses', '<i class="fa fa-info-circle"></i> Produk berhasil ditambahkan!');
            redirect(base_url('Match/produk'), 'refresh');
        } 
        else 
        {
            $upload_gambar = array('upload_data' => $this->upload->data());
            $config['image_library'] = 'gd2';
            $config['source_image'] = './upload/produk/' . $upload_gambar['upload_data']['file_name'];
            $config['new_image']    = './upload/produk/';
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['width']         = 2024;
            $config['height']       = 2024;

            $this->load->library('image_lib', $config);

            $this->image_lib->resize();

            $i = $this->input;
            $data = array(
              'id_kategori'          => $i->post('id_kategori'),
              'id_satuan'          => $i->post('id_satuan'),
              'nm_produk'      => $i->post('nama'),
              'harga'      => $i->post('harga'),
              'harga_modal'      => $i->post('harga_modal'),
              'stok'      => $i->post('stok'),
              'foto'         => $upload_gambar['upload_data']['file_name'],
              'diskon'      => $i->post('diskon'),
              'komisi' => 5,
          );
            $this->db->insert('tb_produk', $data);

            $id_produk = $this->db->insert_id();
            $sku = 'OBP'.$id_produk;

            $data_sku = [
                'sku' => $sku
            ];

            $this->db->where('id_produk', $id_produk);
            $this->db->update('tb_produk', $data_sku);


            $this->session->set_flashdata('sukses', '<i class="fa fa-info-circle"></i> Produk berhasil ditambahkan!');
            redirect(base_url('Match/produk'), 'refresh');
        }
    }
}

public function add_produk2()
{
    $valid = $this->form_validation;

    $valid->set_rules(
        'nama',
        '<i class="feather icon-info"></i> Nama produk',
        'required',
        array(
            'requiured' => 'harus di isi!'
        )
    );

    if ($valid->run()) {

        $config['upload_path']       = './upload/produk/';
        $config['allowed_types']     = 'gif|jpg|png|jpeg';
        $config['max_size']          = '2400';
        $config['max_width']         = '2024';
        $config['max_height']        = '2024';

        $this->load->library('upload', $config);
        $this->load->helper('string');

        if (!$this->upload->do_upload('foto')) {
            $i = $this->input;
            $data = array(
                'id_kategori'          => $i->post('id_kategori'),
                'id_satuan'          => $i->post('id_satuan'),
                'nm_produk'      => $i->post('nama'),
                'harga'      => $i->post('harga'),
                'harga_modal'      => $i->post('harga_modal'),
                'stok'      => $i->post('stok'),
            );
            $this->db->insert('tb_produk', $data);
            $id_produk = $this->db->insert_id();
            $sku = 'OBP'.$id_produk;

            $data_sku = [
                'sku' => $sku
            ];

            $this->db->where('id_produk', $id_produk);
            $this->db->update('tb_produk', $data_sku);
            $this->session->set_flashdata('sukses', '<i class="fa fa-info-circle"></i> Produk berhasil ditambahkan!');
            redirect(base_url('Match/tambah_produk_masuk'), 'refresh');
        } 
        else 
        {
            $upload_gambar = array('upload_data' => $this->upload->data());
            $config['image_library'] = 'gd2';
            $config['source_image'] = './upload/produk/' . $upload_gambar['upload_data']['file_name'];
            $config['new_image']    = './upload/produk/';
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['width']         = 2024;
            $config['height']       = 2024;

            $this->load->library('image_lib', $config);

            $this->image_lib->resize();

            $i = $this->input;
            $data = array(
              'id_kategori'          => $i->post('id_kategori'),
              'id_satuan'          => $i->post('id_satuan'),
              'nm_produk'      => $i->post('nama'),
              'harga'      => $i->post('harga'),
              'harga_modal'      => $i->post('harga_modal'),
              'stok'      => $i->post('stok'),
              'foto'         => $upload_gambar['upload_data']['file_name']
          );
            $this->db->insert('tb_produk', $data);

            $id_produk = $this->db->insert_id();
            $sku = 'OBP'.$id_produk;

            $data_sku = [
                'sku' => $sku
            ];

            $this->db->where('id_produk', $id_produk);
            $this->db->update('tb_produk', $data_sku);


            $this->session->set_flashdata('sukses', '<i class="fa fa-info-circle"></i> Produk berhasil ditambahkan!');
            redirect(base_url('Match/tambah_produk_masuk'), 'refresh');
        }
    }
}

public function add_kategori()
{
    $nama = $this->input->post('nama');

    $data = array(
        'nm_kategori'   => $nama
    );
    $this->db->insert('tb_kategori', $data);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Kategori berhasil ditambahkan!<div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/kategori");
}

public function add_satuan()
{
    $nama = $this->input->post('nama');

    $data = array(
        'satuan'   => $nama
    );
    $this->db->insert('tb_satuan', $data);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Satuan berhasil ditambahkan!<div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/satuan");
}

public function drop_kategori($id_kategori)
{
    $where = array('id_kategori' => $id_kategori);
    $res = $this->M_salon->DropData('tb_kategori', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Kategori berhasil dihapus!<div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/kategori");
}

public function drop_satuan($id_satuan)
{
    $where = array('id_satuan' => $id_satuan);
    $res = $this->M_salon->DropData('tb_satuan', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Satuan berhasil dihapus!<div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/satuan");
}

public function edit_kategori($id_kategori){
    $data = array(
        'title'  => "Orchard | Edit Kategori", 
        'kategori'    => $this->db->get_where('tb_kategori', array('id_kategori' => $id_kategori))->result()[0],
    );
       $this->load->view('kategori/edit', $data);
}
public function edit_satuan($id_satuan){
    $data = array(
        'title'  => "Orchard | Edit Satuan", 
        'satuan'    => $this->db->get_where('tb_satuan', array('id_satuan' => $id_satuan))->result()[0],
    );
       $this->load->view('produk/edit_satuan', $data);
}

public function update_kategori(){
    $id_kategori = [
        'id_kategori' => $this->input->post('id_kategori')
    ];
    $data = [
        'nm_kategori' => $this->input->post('nm_kategori')
    ];
    $this->db->update('tb_kategori', $data, $id_kategori);
    // $res = $this->M_salon->UpdateData('tb_kategori', $data, $id_kategori);     
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Kategori Berhasil Di Update </div>');
    redirect('Match/kategori');
}

public function update_satuan(){
    $id_satuan = [
        'id_satuan' => $this->input->post('id_satuan')
    ];
    $data = [
        'satuan' => $this->input->post('satuan')
    ];
    $this->db->update('tb_satuan', $data, $id_satuan);
    // $res = $this->M_salon->UpdateData('tb_kategori', $data, $id_kategori);     
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Kategori Berhasil Di Update </div>');
    redirect('Match/satuan');
}



public function getProdukDrowdown($filter){
    if($filter == 'all') {
        $cek = ['13', '20'];
        $produk = $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->where_not_in('tb_produk.id_kategori', $cek)->get('tb_produk')->result();
    } else {
        $produk = $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->where('tb_produk.id_kategori',$filter)->get('tb_produk')->result();
    }
    $data = [
        'produk'   => $produk,
    ];
    $this->load->view('produk/loadProduk2', $data);
}

public function drop_produk($id_produk)
{
    $where = array('id_produk' => $id_produk);
    $res = $this->M_salon->DropData('tb_produk', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dihapus!<div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    // redirect("Match/produk");
}

public function edit_produk($id_produk)
{
   $data = array(
    'title'  => "Orchard Edit Produk", 
    'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->get_where('tb_produk', array('id_produk' => $id_produk))->row(),
    'kategori'    => $this->db->get('tb_kategori')->result(),
    'satuan'    => $this->db->get('tb_satuan')->result(),
);
   $this->load->view('produk/edit', $data);
}

public function update_produk()
{
    // $id_produk = $this->input->post('id_produk');
    $id_kategori = $this->input->post('id_kategori');
    $id_satuan = $this->input->post('id_satuan');
    $nama = $this->input->post('nama');
    $harga_modal = $this->input->post('harga_modal');
    $harga = $this->input->post('harga');
    $stok = $this->input->post('stok');
    $diskon = $this->input->post('diskon');
    $komisi = $this->input->post('komisi');

    $data_update = array(
        // 'id_produk'   => $id_produk,
        'id_kategori'   => $id_kategori,
        'id_satuan'   => $id_satuan,
        'nm_produk'     => $nama,
        'harga_modal'         => $harga_modal,
        'harga'         => $harga,
        'stok'          => $stok,
        'diskon'    => $diskon,
        'komisi'    => $komisi
    );

    $id_produk = [
        'id_produk' =>$this->input->post('id_produk')
    ];

    $this->db->update('tb_produk', $data_update, $id_produk);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil di ubah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/produk");
}

public function kelolaProduk(){
    $data = array(
        'title'  => "Orchard Kelola Produk", 
        // 'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->get('tb_produk')->result(),
        // 'kategori'    => $this->db->get('tb_produk')->result(),
        'produk' => $this->db->get('tb_produk')->result(),
    );
    $this->load->view('produk/kelola_produk', $data);
}

public function opname(){
    $data = array(
        'title'  => "Orchard | Opname Product", 
        // 'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->get('tb_produk')->result(),
        // 'kategori'    => $this->db->get('tb_produk')->result(),
        // 'produk' => $this->db->get('tb_produk')->result(),
        'opname' => $this->db->group_by('kode_opname')->get('tb_opname')->result()
    );

    if (empty($this->input->post('tgl1'))) {
        $bulan = date('m');
        $year = date('Y');        
        $data = array(
            'title'  => "Crepese Signature | Daftar Opname",
            'opname' => $this->M_salon->daftar_opname(" where MONTH(tgl) = '$bulan' AND YEAR(tgl) = $year ") 
            
        );
    }else{
        $dt_a   = $this->input->post('tgl1');
        // $dt_b   = $this->input->post('tgl2');
        $dt_b = date('Y-m-d', strtotime('+1 days', strtotime($this->input->post('tgl2'))));
        $data = array(
            'title'  => "Crepese Signature | Daftar Opname", 
            'opname' => $this->M_salon->daftar_opname(" where tgl BETWEEN '$dt_a' AND '$dt_b' ")
        );
    }


    $this->load->view('produk/opname', $data);
}

public function create_opname(){
    $data = array(
        'title'  => "Orchard | Create Opname Produk", 
        'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan','left')->get('tb_produk')->result(),
        'kategori'    => $this->db->get('tb_kategori')->result(),
    );
    $this->load->view('produk/create_opname', $data);
}

public function input_opname(){
   $id_produk = $this->input->post('id_produk_opname');
   $kode_opname = date('ymd') . strtoupper(random_string('alpha',3));
   
   foreach($id_produk as $id){
       $get_produk = $this->db->get_where('tb_produk', array(
        'id_produk' => $id))->result()[0];
       $data = [
           'kode_opname' => $kode_opname,
           'id_produk' => $get_produk->id_produk,
           'stok_program' => $get_produk->stok,
           'stok_aktual' => $get_produk->stok,
           'harga' => $get_produk->harga,
           'catatan' => '',
           'status' => 'Draft',
           'tgl' => date('Y-m-d H:i:s')
       ];
       $this->M_salon->InputData('tb_opname',$data);
   }
   $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Produk berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
   redirect("Match/detail_opname?kode_opname=$kode_opname");
   
}

public function tambah_detail_opname(){
    $id_produk = $this->input->post('id_produk_opname');
    $kode_opname = $this->input->post('kode_opname');
    
    foreach($id_produk as $id){
        $get_produk = $this->db->get_where('tb_produk', array(
         'id_produk' => $id))->result()[0];
         $cek = $this->db->get_where('tb_opname', [
             'kode_opname' => $kode_opname,
             'id_produk' => $id
         ])->num_rows();
         if($cek > 0){
            continue;
         }else {
            $data = [
                'kode_opname' => $kode_opname,
                'id_produk' => $get_produk->id_produk,
                'stok_program' => $get_produk->stok,
                'stok_aktual' => $get_produk->stok,
                'harga' => $get_produk->harga,
                'catatan' => '',
                'status' => 'Draft',
                'tgl' => date('Y-m-d H:i:s')
            ];
            $this->M_salon->InputData('tb_opname',$data);
         }
        
    }
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Produk berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/detail_opname?kode_opname=$kode_opname");
    
 }

 public function delete_opname(){
    $kode_opname= $this->input->get('kode_opname');
    $this->db->where('kode_opname', $kode_opname);
    $this->db->delete('tb_opname');

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Produk berhasil dihapus!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/opname");

 }

 public function selesai_opname(){
 $kode_opname= $this->input->get('kode_opname');
 $data = [
     'status' => 'Selesai',
     'tgl' => date('Y-m-d H:i:s')
 ];
    $where = array( 'kode_opname' => $kode_opname);
    $res = $this->M_salon->UpdateData('tb_opname', $data, $where);

    $dt_opname = $this->db->get_where('tb_opname',['kode_opname' => $kode_opname])->result();

 foreach($dt_opname as $do){
    $data_stok_barang = [
        'stok' => $do->stok_aktual
    ];
    $this->db->where('id_produk', $do->id_produk);
    $this->db->update('tb_produk', $data_stok_barang);
 }
    

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Opname Product Selesai  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/opname");
 }

 public function edit_stok_aktual()
    {
        if($this->input->post('action') == 'selesai'){

            $id_opname = $this->input->post('id_opname');
            $id_produk = $this->input->post('id_produk');
            $stok_aktual = $this->input->post('stok_aktual');
            $catatan = $this->input->post('catatan');
            $id_produk = $this->input->post('id_produk');
            

            for ($x = 0; $x < sizeof($id_opname); $x++) {
                $data = [
                    'stok_aktual' => $stok_aktual[$x],
                    'catatan' => $catatan[$x],
                    'status' => 'Selesai',
                    'tgl' => date('Y-m-d H:i:s')
                ];
                $this->db->where('id_opname', $id_opname[$x]);
                $this->db->update('tb_opname', $data);
            }

            for ($x = 0; $x < sizeof($id_produk); $x++) {
                $data_produk = [
                    'stok' => $stok_aktual[$x]
                ];
                $this->db->where('id_produk', $id_produk[$x]);
                $this->db->update('tb_produk', $data_produk);
            }
            $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Opname Product Selesai  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
            redirect("Match/opname");
        }

        if($this->input->post('action') == 'edit'){
        $id_opname = $this->input->post('id_opname');
        $stok_aktual = $this->input->post('stok_aktual');
        $catatan = $this->input->post('catatan');
        $id_produk = $this->input->post('id_produk');
        

        for ($x = 0; $x < sizeof($id_opname); $x++) {
            $data = [
                'stok_aktual' => $stok_aktual[$x],
                'catatan' => $catatan[$x]
            ];
            $this->db->where('id_opname', $id_opname[$x]);
            $this->db->update('tb_opname', $data);
        }
        $this->session->set_flashdata('message', '<div class="alert bg-success" role="alert">Data Berhasil Di Opname</div>');
        redirect(isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '');
        }
        
    }

public function detail_opname(){
    $kode_opname = $this->input->get('kode_opname');
   $data = array(
        'title'  => "Orchard | Create Opname Produk", 
        'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan','left')->get('tb_produk')->result(),
        'kategori'    => $this->db->get('tb_kategori')->result(),
        'opname' => $this->db->join('tb_produk','tb_opname.id_produk = tb_produk.id_produk','left')
        ->join('tb_kategori','tb_produk.id_kategori = tb_kategori.id_kategori','left')
        ->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan','left')
        ->get_where('tb_opname',['kode_opname'=>$kode_opname])->result(),
        'kode_opname' => $kode_opname
    );
    $this->load->view('produk/detail_opname', $data);
}

public function form_opname(){
    $kode_opname = $this->input->get('kode_opname');
    $data = array(
        'title'  => "Orchard | Create Opname Produk", 
        'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan','left')->get('tb_produk')->result(),
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
    $this->load->view('produk/form_opname', $data);
}

public function print_opname(){
    $kode_opname = $this->input->get('kode_opname');
    $data = array(
        'opname' => $this->db->group_by('kode_opname')->get_where('tb_opname',['kode_opname' => $kode_opname])->result()[0],
        'detail_opname' => $this->db->join('tb_produk','tb_opname.id_produk = tb_produk.id_produk','left')
        ->join('tb_kategori','tb_produk.id_kategori = tb_kategori.id_kategori','left')
        ->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan','left')
        ->get_where('tb_opname',['kode_opname'=>$kode_opname])->result(),
        'kode_opname' => $kode_opname
    );
    $this->load->view('produk/print_opname', $data);
}

public function get_opname()
{
    $kategori = 1;
    
    $keyword = '';
    if (isset($_POST['keyword'])) 
    {
      $keyword = $this->input->post('keyword');
    //   $kategori = $this->input->post('kategori');
    }
    if (isset($_POST['kategori'])) 
    {
      $kategori = $this->input->post('kategori');
    }

$data = $this->M_salon->get_opname($keyword,$kategori);
$i=1;
    echo '<form action="'.base_url().'match/input_opname" method="POST">';
    foreach($data as $d){
        echo '
        <tr>
            <td>'.$i++.'</td>
            <td>'.$d->nm_produk.'</td>
            <td>'.$d->nm_kategori.'</td>
            <td>'.$d->satuan.'</td>
            <td>'.$d->stok.'</td>
            <td>'.$d->harga.'</td>
            <td>
            <div class="form-check">
            <input class="form-check-input" type="checkbox" name="id_produk_opname[]" value="'.$d->id_produk.'">
            
          </div>
            </td>
        </tr>';
    }
    echo '</form>';
}

public function edit_stok_nyata(){

    $data_update = array(
        'id_produk'   => $this->input->post('id_produk'),
        'stok_nyata' => $this->input->post('stok_nyata'),
        'stok' => $this->input->post('stok')        
    );

    $this->M_salon->update_produk($data_update);
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Stok nyata berhasil di ubah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/kelolaProduk");
}

public function get_produk(){
    $id = $_POST['id'];
    $produk = $this->db->where('id_kategori',$id)->get('tb_produk')->result_array();

    foreach ($produk as $d) {
      
      echo "<option value='".$d['id_produk']."'>".$d['nm_produk']."</option>";
    }
}



public function get_kategori(){
    // $id = $_POST['id'];
    $kategori = $this->db->get('tb_kategori')->result_array();

    foreach ($kategori as $d) {
      
      echo "<option value='".$d['id_kategori']."'>".$d['nm_kategori']."</option>";
    }
}

public function produk_masuk(){
    // $produk =$this->db->get('tb_produk')->result();
    if (empty($this->input->post('tgl1'))) {
        $bulan = date('m');
        $year = date('Y');        
        $data = array(
            'title'  => "Crepese Signature | list penjualan",
            'produk_masuk' => $this->M_salon->get_list_produk_masuk(" where MONTH( tbl_produk_masuk.tgl) = '$bulan' AND YEAR(tbl_produk_masuk.tgl) = '$year'"), 
            'kategori' => $this->db->get('tb_kategori')->result(),
        );
    }else{
        $dt_a   = $this->input->post('tgl1');
        $dt_b   = $this->input->post('tgl2');
        $data = array(
            'title'  => "Crepese Signature | list penjualan", 
            'produk_masuk' => $this->M_salon->get_list_produk_masuk(" where tbl_produk_masuk.tgl BETWEEN '$dt_a' AND '$dt_b' "),
            'kategori' => $this->db->get('tb_kategori')->result(),
        );
        
    }

    // $data = array(
    //     'title'  => "Crepese Signature | Produk Masuk", 
    //     'produk_masuk' => $this->db->join('tb_produk', 'tbl_produk_masuk.id_produk = tb_produk.id_produk')->get('tbl_produk_masuk')->result(),
    //     'produk' => $this->db->get('tb_produk')->result(),
    // );
    $this->load->view('produk/produk_masuk', $data);
}

function summary_produk_masuk(){
    $tgl1 = $this->input->post('tgl_masuk1');
    $tgl2 = $this->input->post('tgl_masuk2');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'masuk'     => $this->M_salon->summary_produk_masuk($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('produk/summary_export_produk_masuk', $data);
}

function excel_produk_masuk(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'masuk'     => $this->M_salon->summary_produk_masuk($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('produk/excel_produk_masuk', $data);
}

public function tambah_produk_masuk(){

    $data = [
        'title' => 'Crepese Signature | Produk Masuk',
        'kategori' => $this->db->get('tb_kategori')->result(),
    ];
    $this->load->view('produk/tambah_produk_masuk',$data);
}


public function tambah_produk(){

    $data = [
        'title' => 'Crepese Signature | Tambah Produk',
        'kategori' => $this->db->where('id_kategori !=','13')->get('tb_kategori')->result(),
        'satuan'    => $this->db->get('tb_satuan')->result(),
    ];
    $this->load->view('produk/tambah_produk',$data);
}

public function tambah_produk2(){

    $data = [
        'title' => 'Crepese Signature | Tambah Produk',
        'kategori' => $this->db->where('id_kategori !=','13')->get('tb_kategori')->result(),
        'satuan'    => $this->db->get('tb_satuan')->result(),
    ];
    $this->load->view('produk/tambah_produk2',$data);
}

public function add_produk_masuk(){

    $data = array(
        'id_produk'   => $this->input->post('id_produk'),
        'jumlah' => $this->input->post('jumlah'),
        'hrg_beli' => $this->input->post('hrg_beli'),
        // 'hrg_jual' => $this->input->post('hrg_jual'),
        'tgl' => $this->input->post('tgl'),
        'tgl_expired' => $this->input->post('tgl_expired')        
    );

    $produk = $this->db->where('id_produk', $this->input->post('id_produk'))->get('tb_produk')->result_array()[0];
    // if($this->input->post('hrg_jual') > $produk['harga']){
        $stok = $produk['stok'] + $this->input->post('jumlah');
    //     $stok_nyata = $produk['stok_nyata'] + $this->input->post('jumlah');
        $data_update_produk = array(
            'id_produk'   => $this->input->post('id_produk'),
           
            'stok' => $stok       
        );    
        $this->M_salon->update_produk($data_update_produk);
    // }else{
    //     $stok = $produk['stok'] + $this->input->post('jumlah');
    //     $stok_nyata = $produk['stok_nyata'] + $this->input->post('jumlah');
    //     $data_update_produk = array(
    //         'id_produk'   => $this->input->post('id_produk'),
    //         'stok' => $stok,
    //         'stok_nyata' => $stok_nyata        
    //     );    
    //     $this->M_salon->update_produk($data_update_produk);
    // }

    $this->M_salon->InputData('tbl_produk_masuk',$data);
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Produk berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/produk_masuk");
}

public function edit_produk_masuk($id_produk_masuk)
{
   $data = array(
    'title'  => "Orchard Edit Produk Masuk", 
    'produk' => $this->db->get('tb_produk')->result(),
    'produk_masuk' => $this->db->where('id', $id_produk_masuk)->get('tbl_produk_masuk')->result_array()[0] 
);
   $this->load->view('produk/edit_produk_masuk', $data);
}

public function update_produk_masuk()
{
    $id_produk = $this->input->post('id_produk');
    $id_produk_masuk = $this->input->post('id_produk_masuk');
    $jumlah = $this->input->post('jumlah');
    $hrg_beli = $this->input->post('hrg_beli');
    $hrg_jual = $this->input->post('hrg_jual');

    $data_update = array(
        'id'   => $id_produk_masuk,
        'id_produk'   => $id_produk,
        'jumlah'   => $jumlah,
        'hrg_beli'   => $hrg_beli,
        'hrg_jual'   => $hrg_jual,
    );

    $this->M_salon->update_produk_masuk($data_update);
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Produk berhasil di ubah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/produk_masuk");
}

public function drop_produk_masuk($id_produk_masuk)
{
    $where = array('id' => $id_produk_masuk);
    $res = $this->M_salon->DropData('tbl_produk_masuk', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dihapus!<div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/produk_masuk");
}

public function produk_terlaris(){
    $data = array(
        'title'  => "Orchard Produk Terlaris", 
        // 'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->get('tb_produk')->result(),
        // 'kategori'    => $this->db->get('tb_produk')->result(),
        'produk' => $this->db->select('*')->from('tb_pembelian')->join('tb_produk', 'tb_pembelian.id_produk = tb_produk.id_produk', 'left')->select_sum('jumlah')->group_by('tb_pembelian.id_produk')->order_by('jumlah','DESC')->get()->result(),
    );
    $this->load->view('produk/produk_terlaris', $data);
}

public function export_data_produk(){
    
    $data = array(
        'produk' => $this->db->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan')->join('tb_kategori','tb_produk.id_kategori = tb_kategori.id_kategori')->get('tb_produk')->result()
    );

    $this->load->view('produk/export_data_produk', $data);
}

// ================================== END PRODUK ==========================================

// ================================== APPOITMENT ==========================================

public function appoiment(){
    if (!empty($this->session->userdata('tgl1'))) {
        $dt_a   = $this->session->userdata('tgl1');
        $dt_b   = $this->session->userdata('tgl2');
        $data = array(
            'title'  => "Orchard Appointment", 
            'anak'   => $this->M_salon->ambil_anak(),
            'app'    => $this->M_salon->dt_app(" where tgl BETWEEN '$dt_a' AND '$dt_b' "),
        );
    }else{
        $dt   = date('Y-m-d');
        $data = array(
            'title'  => "Orchard Appointment", 
            'anak'   => $this->M_salon->ambil_anak(),
            'app'    => $this->M_salon->dt_app(" where tgl_input = '$dt' "),
        );
    }
    $this->load->view('appoiment/tabel', $data);
}

public function appoiment2(){
    $dt_a = $this->input->post('tgl1');
    $dt_b = $this->input->post('tgl2');
    $sesi = array(
        'tgl1' => $this->input->post('tgl1'), 
        'tgl2' => $this->input->post('tgl2'), 
    );
    $this->session->set_userdata($sesi);
    $data = array(
        'title'  => "Orchard Appointment", 
        'anak'   => $this->M_salon->ambil_anak(),
        'app'    => $this->M_salon->dt_app(" where tgl BETWEEN '$dt_a' AND '$dt_b' "),
    );
    $this->load->view('appoiment/tabel', $data);
}

function input_app(){
    $tgl    = $this->input->post('tanggal'); 
    $kd_app = $this->input->post('kd_app'); 
    $cek = $this->db->get_where(" tb_appoiment where kd_app = '$kd_app' and tgl = '$tgl' ")->row();
    if ($cek) {
        echo "Nomor <b style='color: red;'>".$kd_app."</b> Sudah digunakan ... Kembali" ; exit;
    }else{

        $data_input = array(
            'tgl'       => $this->input->post('tanggal'),
                // 'kd_app'    => $this->input->post('kd_app'),
                // 'jam'       => $this->input->post('jam'),
                // 'ket'       => strtoupper($this->input->post('ket')),
            'id_kry_2'  => $this->input->post('id_kry_2'),
            'nm_app'    => strtoupper($this->input->post('nm_app')),
            'org'       => $this->input->post('org'),
            'nominal'   => ($this->input->post('org') * 5000),
            'admin'     => $this->session->userdata('nm_user'),
            'tgl_input' => date('Y-m-d')
        );
        $res  = $this->M_salon->InputData('tb_appoiment', $data_input);
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
        redirect("Match/appoiment");
    }
}

public function edit_app($id_app){
    $a = $this->M_salon->dt_app(" where id_app ='$id_app' ");
    $data = array(
        "id_app"     => $a[0]->id_app,
        "kd_app"     => $a[0]->kd_app,
        "jam"        => $a[0]->jam,
        "id_kry_2"   => $a[0]->id_kry_2,
        "nm_app"     => $a[0]->nm_app,
        "tgl"        => $a[0]->tgl,
        "ket"        => $a[0]->ket,
        "org"        => $a[0]->org,
        "nominal"    => $a[0]->nominal,
        'title'      => "Edit Appointment", 
        'anak'       => $this->M_salon->ambil_anak()
    );
    $this->load->view('appoiment/form_edit', $data);
}

function update_app(){
    $id_app  = $this->input->post('id_app');
    $data_input = array(
            // 'kd_app'    => $this->input->post('kd_app'),
            // 'jam'       => $this->input->post('jam'),
        'tgl'       => $this->input->post('tgl'),
        'id_kry_2'  => $this->input->post('id_kry_2'),
        'nm_app'    => strtoupper($this->input->post('nm_app')),
        'org'       => $this->input->post('org'),
        'nominal'   => ($this->input->post('org') * 5000),
    );
    $where = array('id_app' => $id_app);
    $res  = $this->M_salon->UpdateData('tb_appoiment', $data_input, $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/appoiment");
}

function update_app_order(){
    $id_order = $this->input->post('id_order');
    $id_terapis = $this->input->post('id_terapis');
    $id_servis= $this->input->post('id_servis');
    $start = $this->input->post('start');
    $start2 = date('H:i:s', strtotime($start));
    $end = $this->input->post('end');
    $end2 = date('H:i:s', strtotime($end));
    $tgl = $this->input->post('tgl');

    // echo $tgl;
    
    // $data = [
    //     'id_order' => $id_order,
    //     'id_terapis' => $id_terapis,
    //     'id_servis' => $id_servis,
    //     'start' => $start,
    //     'end' => $end
    // ];

    $cek = $this->db->get_where("tb_order where location='$id_terapis' AND tanggal='$tgl' AND '$end' > start AND '$start2' < end AND  id_order != '$id_order'")->num_rows();

    if ($cek > 0) 
    {
     echo "gagal";
    }else{
        $detail_s = $this->db->get_where('tb_servis', array('id_servis' => $id_servis))->row();
    $detail_t = $this->db->get_where('tb_terapis', array('id_terapis' => $id_terapis))->row();
    
    
    $tgl2 = date('D M d Y ' , strtotime($tgl));
    
    $start_t = $tgl2.$start2.' GMT+0800 (Central Indonesia Time)';
    $end_t = $tgl2.$end2.' GMT+0800 (Central Indonesia Time)';

    
        $ttl_jam = $detail_t->ttl_jam + $detail_s->durasi;
     $data_t = array(
        'ttl_jam'  => $ttl_jam
    );

     $where = array( 'id_terapis' => $id_terapis);
     $res = $this->M_salon->UpdateData('tb_terapis', $data_t, $where);

        $data_update = array(
            'id_terapis'   => $id_terapis,
            'id_servis'   => $id_servis,
            'location'   => $id_terapis,
            'start'   => $start2,
            'start_t'   => $start_t,
            'end'   => $end2,
            'end_t'   => $end_t,
    
        );
        $where = array('id_order' => $id_order );
        $res = $this->M_salon->UpdateData('tb_order', $data_update, $where);
       echo 'berhasil';
    }

    
    
}

function drop_app($id_app){
    $where = array('id_app' => $id_app);
    $res = $this->M_salon->DropData('tb_appoiment', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Hapus !!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/appoiment");
}

public function rekap_app(){
    $dt   = date('Y-m-d');
    $dt_a = date('Y-m-25');
    $dt_b = date('Y-m-26');
    $dt_c = date('Y-m-d', strtotime($dt_a.'-1 month'));
    $data = array(
        'title'  => "Orchard Appointment", 
        'salon'  => $this->M_salon->dt_kry(),
    );
    $this->load->view('appoiment/rekap', $data);
}

function summary_app(){
    $tgl1 = $this->input->post('tgl1');
    $tgl2 = $this->input->post('tgl2');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'app'       => $this->M_salon->summary_app($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    ); 

    $this->load->view('appoiment/summary_export', $data);
}

function excel_app_sum(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'app'       => $this->M_salon->summary_app($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2)),
    ); 

    $this->load->view('appoiment/excel_sum', $data);
}

function excel_app_det(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'app'       => $this->M_salon->dt_app(" where tgl BETWEEN '$tgl1' AND '$tgl2' "),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2)),
    ); 

    $this->load->view('appoiment/excel_det', $data);
}

// ================================== END APPOIMENT ==========================================

// ================================== APP ==========================================

public function order()
{
    $names = ['T1', 'T2', 'T3','T4','T5','T6','T7','T8','T9','T10'];
    $cek = ['13', '20','26','29'];
    $this->cart->destroy();
    $kry = $this->db->where_not_in('nm_kry', $names)->get('tb_karyawan')->result();

        if (empty($this->input->get('dis'))) {
            $dis = '1';
        } else {
            $dis = $this->input->get('dis');
        }

    $data = array(
        'title'  => "Crepe Order Produk", 
        'produk'   => $this->db->join('tb_kategori', 'tb_servis.id_kategori = tb_kategori.id_kategori', 'left')->get('tb_servis')->result(),
        'toping'  => $this->db->query("SELECT a.id_produk, a.nm_produk, a.qty_toping, b.harga
        FROM tb_produk as a
        left JOIN(
        SELECT b.id_produk, b.harga
            FROM harga_toping as b 
            where b.id_distibusi ='$dis'
        ) as b on b.id_produk = a.id_produk
        where a.id_kategori ='26'
        order by a.nm_produk ASC
        ;")->result(),

        'perlengkapan'  => $this->db->query("SELECT a.id_produk, a.nm_produk, a.qty_toping, b.harga
        FROM tb_produk as a
        left JOIN(
        SELECT b.id_produk, b.harga
            FROM harga_toping as b 
            where b.id_distibusi ='$dis'
        ) as b on b.id_produk = a.id_produk
        where a.id_kategori ='29'
        order by a.nm_produk ASC
        ;")->result(),
        'kategori'    => $this->db->where_not_in('id_kategori',$cek)->get('tb_kategori')->result(),
        'diskon_servis' => $this->db->get('tb_diskon_servis')->result(),
        'dis' => $dis
    );
    $this->load->view('order/tabel', $data);
}


public function cart()
{
$sku = $this->input->post('sku');
$id_produk = $this->input->post('id_produk');
$jumlah = $this->input->post('jumlah');
$satuan = $this->input->post('satuan');
$catatan = $this->input->post('catatan');
$id_karyawan= $this->input->post('id_karyawan');
// $karyawan = $this->db->get_where('tb_karyawan', array('id_kry' => $id_karyawan))->row();
$karyawan = [];

$qty = 0;
foreach($this->cart->contents() as $cart){
    if($cart['type'] == 'barang'){
        if($id_produk == $cart['id_produk']){
            $qty = $cart['qty'] + $jumlah;
        }
    }   
    }

    $detail = $this->db->get_where('tb_servis', array('id_servis' => $id_produk))->row();
    if(empty($id_karyawan)){
        echo "null";
    }else{
        foreach($id_karyawan as $id_kr){
            $kry = $this->db->get_where('tb_karyawan', array('id_kry' => $id_kr))->row();
            $karyawan [] = $kry->nm_kry; 
        }
        if ($jumlah > $detail->stok) 
    {
        // $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk gagal dimasukan kedalam keranjang, stok kurang dari jumlah permintaan!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        // redirect("Match/order");
        echo 'kosong';
    }elseif($qty > $detail->stok){
        echo 'kosong';
    }
    else
    {
        if(!empty($detail->diskon)){
            $dsc = $detail->harga * $detail->diskon / 100;
            $harga = $detail->harga - $dsc;
        }else{
            $harga = $detail->harga;
        }
    $data = array(
        'id'      => $sku,
        'id_produk' => $id_produk,
        'qty'     => $this->input->post('jumlah'),
        'type'    => 'barang',  
        'price'   => $harga,
        'name'    => preg_replace("/[^a-zA-Z0-9]/", " ", $detail->nm_produk) ,
        'picture' => $detail->foto,
        'disc'    => $detail->diskon,
        'satuan'  => $satuan,
        'catatan' => $catatan,
        'id_karyawan'   => $id_karyawan,
        'nm_karyawan'   => $karyawan
    );

    $this->cart->insert($data);
    //    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dimasukan kedalam keranjang!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    //    redirect("Match/order");
    }
}

}

public function get_cart(){
    // var_dump($this->cart->contents());
    if(empty($this->cart->contents())){
        echo '<div class="card">
        <div class="card-body">
            <h3 class="text-center">Keranjang Belanja</h3>
            <hr>
            <center><br><br>
                <img width="100" src="'.base_url().'upload/icon/cart.png" alt=""><br><br>
                <h5>keranjang belanja kosong!</h5>
            </center><br>

        </div>
    </div>';
    }else {
        // var_dump($this->cart->contents());
        // $this->cart->destroy();
        // echo "ada";

        echo ' <div class="card">';
        
        echo '<div class="card-body">
            <h3 class="text-center">Product</h3>
            <hr>';
            $subtotal_produk = 0;
            $jumlah = 0;
            foreach ($this->cart->contents() as $key => $value){
                if($value['type'] == 'barang'){
            echo '<div class="row">';
            
                
                    $subtotal_produk += $value['price']*$value['qty'];
                $jumlah += $value['qty'];
                echo '<div class="col-sm-12 col-md-12">';
                foreach($value['nm_karyawan'] as $key => $nm_karyawan){
              echo  '<span class="badge badge-secondary">'.$nm_karyawan.'</span> ';
            }
               echo '<p>'.$value['name'].'</p>               
            </div>
            <div class="col-sm-6 col-md-6">
            <p>'.$value['qty'] .' x <strong>Rp.'.number_format($value['price']) .'</strong> </p>
            </div>
            <div class="col-sm-6 col-md-6 text-center text-lg">
            <div class="row">
                <div class="col-sm-4 col-md-4">
                <a class="min_cart mr-2" id="'.$value['rowid'].'" qty="'.$value['qty'].'" href="javascript:void(0)" style="margin-top: 50px;"><i class="fa fa-minus"></i></a>
                </div>
                <div class="col-sm-4 col-md-4">
                <a class="delete_cart mr-2" id="'.$value['rowid'].'" href="javascript:void(0)" style="margin-top: 50px;"><i class="fa fa-times"></i></a>
                </div>
                <div class="col-sm-4 col-md-4">
                <a class="plus_cart mr-2" id="'.$value['rowid'].'" qty="'.$value['qty'].'" id_produk="'.$value['id'].'" href="javascript:void(0)" style="margin-top: 50px;"><i class="fa fa-plus"></i></a>
                </div>
            </div>            
            </div>
            <div class="col-md-6 mb-4">
            <strong>Rp. '.number_format($value['qty'] * $value['price'],0).'</strong>
            </div>
            
            <div class="col-sm-4 col-md-4">
            <button class="voucher btn btn-sm btn-success mr-2" data-toggle="modal" data-target="#voucher" price_cart="'.$value['price'].'" id_cart="'.$value['rowid'].'"><i class="fa fa-tags"></i></button>
            </div>
            ';
            }   
            }
            echo '<div class="container">
        <strong>Subtotal '. $jumlah .' produk</strong> <strong style="float: right;">Rp. '. number_format($subtotal_produk) .'</strong>
        </div';
            echo '<div class="card-body">
            <hr>
            <h3 class="text-center">Service dsadsa</h3>
            <hr>
            </div>';
            echo '<div class="row">';
            $subtotal_app = 0;
            // $jumlah = 0;
            foreach ($this->cart->contents() as $key => $value){
                if($value['type'] == 'order'){
                
                    $subtotal_app += $value['price'] * $value['qty'];
                // $jumlah += $value['qty'];
                echo '<div class="col-sm-12 col-md-12">';
                foreach($value['terapis'] as $key => $terapis){
                    echo  '<span class="badge badge-secondary">'.$terapis.'</span> ';
                  }
               echo '<p>'.$value['name'].'</p>               
            </div>
            <div class="col-sm-6 col-md-6">
            <p>'.$value['qty'].' x <strong>Rp.'.number_format($value['price']) .'</strong> </p>
            </div>
            <div class="col-sm-6 col-md-6 text-center text-lg">
            <div class="row">
                
            <div class="col-sm-4 col-md-4">
            <a class="min_cart mr-2" id="'.$value['rowid'].'" qty="'.$value['qty'].'" href="javascript:void(0)" style="margin-top: 50px;"><i class="fa fa-minus"></i></a>
            </div>
            <div class="col-sm-4 col-md-4">
            <a class="delete_cart mr-2" id="'.$value['rowid'].'" href="javascript:void(0)" style="margin-top: 50px;"><i class="fa fa-times"></i></a>
            </div>
            <div class="col-sm-4 col-md-4">
            <a class="plus_cart_app mr-2" id="'.$value['rowid'].'" qty="'.$value['qty'].'" href="javascript:void(0)" style="margin-top: 50px;"><i class="fa fa-plus"></i></a>
            </div>

           
                
            </div>            
            </div>
            <div class="col-md-6 mb-4">
            <strong>Rp. '.number_format($value['qty'] * $value['price'],0).'</strong>
            </div>

            <div class="col-sm-4 col-md-4">
            <button class="voucher btn btn-sm btn-success mr-2" data-toggle="modal" data-target="#voucher" price_cart="'.$value['price'].'" id_cart="'.$value['rowid'].'"><i class="fa fa-tags"></i></button>
            </div>';
        }
            }
            
            echo '<div class="container">
        <strong>Subtotal Service</strong> <strong style="float: right;">Rp. '. number_format($subtotal_app) .'</strong>
        </div';
        echo '<div class="container">
                    <hr>
                    <strong style="font-size: 20px;">Total</strong> <strong style="float: right; font-size: 22px;">Rp. '. number_format($subtotal_app + $subtotal_produk) .'</strong>
                    <hr>
                </div>
                
            </div>
            <a type="button" data-toggle="modal" data-target="#myModalp" class="btn btn-primary btn-block" style="background-image: linear-gradient(to right, #FFF192 0%, #FFF192 19%, #FFEA61 60%, #FFDD3C 100%); border-color: #F7889D; font-weight: bold; color: #EF0097;">LANJUTKAN KE PEMBAYARAN</a>
            </div>
            </div>';    
        
    }
}

public function delete_cart()
{
    $rowid = $_POST['rowid'];
    $this->cart->remove($rowid);
    // $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dihapus dari keranjang!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    // redirect(base_url('Match/order'),'refresh');
    // echo '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dihapus dari keranjang!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>';
}

public function min_cart()
{
    $data = array(
        'rowid' => $_POST['rowid'],
        'qty'   => $_POST['qty'] - 1
);
$this->cart->update($data);
    // $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dihapus dari keranjang!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    // redirect(base_url('Match/order'),'refresh');
    // echo '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dihapus dari keranjang!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>';
}

    public function plus_cart()
    {
        
        
            $data = array(
                'rowid' => $_POST['rowid'],
                'qty'   => $_POST['qty'] + 1
        );
        $this->cart->update($data);
        
        
    }

    public function plus_cart_app()
    {
            $data = array(
                'rowid' => $_POST['rowid'],
                'qty'   => $_POST['qty'] +1
        );
        $this->cart->update($data);
        
    }

    public function min_kry()
{
    $data = array(
        'rowid' => $_POST['rowid'],
        'qty'   => $_POST['qty'] - 1
);
$this->cart->update($data);
}

public function tambah_diskon_cart()
{
    $row_id = $this->input->post('id_cart');
    $price_cart = $this->input->post('price_cart');
    $id_diskon = $this->input->post('id_diskon');

    $diskon = $this->db->get_where('tb_diskon_servis',['id_diskon' => $id_diskon])->result()[0];

    if($diskon->jenis == 1){
        $harga = $price_cart - $diskon->jumlah;
    }else{
        $jml_diskon = $price_cart * $diskon->jumlah / 100;
        $harga = $price_cart - $jml_diskon;
    }

    $data = array(
        'rowid' => $row_id,
        'price'   => $harga
);
$this->cart->update($data);

echo  'success' ;
    // $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dihapus dari keranjang!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    // redirect(base_url('Match/order'),'refresh');
    // echo '<div style="background-color: #FFA07A;" class="alert" role="alert">Produk berhasil dihapus dari keranjang!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>';
}

public function payment()
{
    $now = date('Y-m-d');
    $dsk = $this->db->where('tgl_awal <=',$now)->where('tgl_akhir >=',$now)->get('tb_diskon')->result();
    if(!empty($dsk)){
        $diskon = $dsk;
    }else{
        $diskon = null;
    }
   $data = array(
    'title'  => "Orchard Payment Order Produk", 
    'dp' => $this->db->join('tb_customer','tb_dp.id_customer = tb_customer.id_customer')->get_where('tb_dp',['status' => '1'])->result(),
    'diskon' => $diskon,
    'customer' => $this->db->get('tb_customer')->result()
);
   $this->load->view('order/payment', $data);
}

public function stok()
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
        'title'  => "Crepese Signature", 
        'produk' => $produk,
    );
        $this->load->view('stok_crepes/table', $data);
}

public function list_pemotongan_resep()
{
    $tgl1   = $this->input->get('tgl1') ?? date('Y-m-1');
    $tgl2   = $this->input->get('tgl2') ?? date('Y-m-t');

    $produk = $this->db->query("SELECT a.kode_stok_produk as invoice,c.satuan,b.nm_produk,a.id_produk, sum(a.kredit) as ttl FROM `tb_stok_produk` as a
    JOIN tb_produk as b on a.id_produk = b.id_produk
    JOIN tb_satuan as c on b.id_satuan = c.id_satuan
    WHERE a.jenis = 'Penjualan' AND a.tgl BETWEEN '$tgl1' AND '$tgl2'
    GROUP by a.id_produk;")->result();

    $data = array(
        'title'  => "Crepese Signature | list penjualan", 
        'produk' => $produk,
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
    );
    $this->load->view('stok_crepes/pemotongan_stok', $data);
}
public function load_detail_stok()
{
    $tgl1   = $this->input->get('tgl1');
    $tgl2   = $this->input->get('tgl2');
    $id_produk   = $this->input->get('id_produk');

    $produk = $this->db->query("SELECT e.satuan,c.nm_produk,a.kode_stok_produk as invoice,b.nm_servis, COUNT(*) as ttl,(COUNT(*) * d.takaran) as resep_dipakai FROM `tb_stok_produk` as a 
    LEFT JOIN (
        SELECT a.no_nota,b.nm_servis,b.id_servis FROM `tb_pembelian` as a
        JOIN tb_servis as b on a.id_produk = b.id_servis
        WHERE a.tanggal BETWEEN '$tgl1' AND '$tgl2'
        GROUP BY a.no_nota
    ) as b on b.no_nota = a.kode_stok_produk
    LEFT JOIN tb_produk as c on a.id_produk = c.id_produk
    LEFT JOIN tb_resep as d on a.id_produk = d.id_produk AND b.id_servis = d.id_servis
    LEFT JOIN tb_satuan as e on e.id_satuan = c.id_satuan
    WHERE a.id_produk = $id_produk AND a.jenis = 'Penjualan' AND a.tgl BETWEEN '$tgl1' AND '$tgl2' GROUP BY a.kode_stok_produk;;")->result();

    $data = array(
        'title'  => "Crepese Signature | list penjualan", 
        'produk' => $produk,
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
    );
    $this->load->view('stok_crepes/load_detail', $data);
}

public function checkout()
{
    $no_invoice=$this->M_invoice->get_no_invoice();
    $i_invoice = $this->M_invoice->simpan_invoice($no_invoice);
    
    $no_nota = 'OR'.$no_invoice;
    
    // $i          = $this->input;
    // $metode = $i->post('metode');
    $cash = $this->input->post('cash');
    $bca_kredit = $this->input->post('bca_kredit');
    $bca_debit = $this->input->post('bca_debit');
    $mandiri_kredit = $this->input->post('mandiri_kredit');
    $mandiri_debit = $this->input->post('mandiri_debit');
    $shoope = $this->input->post('shoope');
    $tokopedia = $this->input->post('tokopedia');
    $kd_dp = $this->input->post('kd_dp');
    $id_customer = $this->input->post('id_customer');
    $id_diskon = $this->input->post('id_diskon');
    if($this->input->post('debit') != 0){
        $debit = $this->input->post('debit');
    }else{
        $debit = 0;
    }

    $total = $this->input->post('total');


    if($this->input->post('nominal_voucher') > 0){
        $nominal_voucher = $this->input->post('nominal_voucher');
        $id_voucher = $this->input->post('id_voucher');
        $data_off_voucher = [
            'tgl_pakai' => date('Y-m-d'),
            'status' => 0
        ];
        $this->db->where('id_voucher',$id_voucher);
        $this->db->update('tb_voucher_invoice',$data_off_voucher);

        $persen_voucher = $nominal_voucher * 100 / $total;
    }else{
        $nominal_voucher = NULL;
        $id_voucher = NULL;
        $persen_voucher = NULL;
    }
    

    if($this->input->post('diskon') != 0){
        $dsk = $this->input->post('diskon');
        $diskon = 0;
        for($count = 0; $count<count($dsk); $count++){
            $diskon += $dsk[$count];
        }

        $persen_diskon = $diskon * 100 / $total;

    }else{
        $diskon = 0;
        $persen_diskon = 0;
    }
    
    // $bayar = $cash + $bca_kredit + $bca_debit + $mandiri_kredit + $mandiri_debit + $debit + $diskon;
    $bayar = $cash + $bca_kredit + $bca_debit + $shoope + $tokopedia + $mandiri_kredit + $mandiri_debit + $debit + $diskon + $nominal_voucher;
    
    $customer = $id_customer;
    $tgl_jam = date('Y-m-d');
    $admin = $this->session->userdata('nm_user');

    $keranjang  = $this->cart->contents();

    $tanggal = date('Y-m-d');

    $month = date('m' , strtotime($tanggal));
        $year = date('Y' , strtotime($tanggal));

    
    if($keranjang){

        if($total <= $bayar){

        

    foreach($keranjang as $keranjang)
    {
        if($keranjang['type'] == 'barang'){
        $id_produk=$keranjang['id_produk'];
        $produk = $this->db->get_where('tb_produk', array('id_produk' => $id_produk))->row_array();

        $terjual = $produk['terjual']+$keranjang['qty'];
        $stokbaru = $produk['stok']-$keranjang['qty'];
        if($keranjang['price'] > 0){
            $subharga = $keranjang['qty']*$keranjang['price'];
        }else{
            $subharga = 0;
        }

        $nm_karyawan = '';
        $length = count($keranjang['nm_karyawan']);
        $number = 1;
        foreach($keranjang['nm_karyawan'] as $karyawan){
            $nm_karyawan .= $karyawan;
            if($number !== $length){
                $nm_karyawan .= ', ';
            }            
            $number ++; 
        }


        $data = array(  
            'id_karyawan'  => $keranjang['id_karyawan'][0],
            'no_nota' => $no_nota,
            'nm_karyawan' => $nm_karyawan,
            'id_produk'   => $keranjang['id_produk'],
            'tanggal'   => $tanggal,
            'jumlah'        => $keranjang['qty'],
            // 'satuan'        => $keranjang['satuan'],
            'harga'  => $keranjang['price'],
            'diskon'          => $keranjang['disc'],
            'total'      => $subharga,
            'catatan'  => $keranjang['catatan'],
            'admin'  => $admin
        );

        $produk_e = [
            'id_produk' => $id_produk,
            'terjual'   => $terjual,
            'stok'      => $stokbaru,
        ];        
        $this->db->insert('tb_pembelian', $data);
        $id_pembelian = $this->db->insert_id();
        $this->M_salon->update_stok($produk_e);

        if($subharga > 0 && $produk['komisi'] > 0){

            if($persen_voucher){
                $kurang_voucher = $subharga * $persen_voucher / 100;
            }else{
                $kurang_voucher = 0;
            }

            if($persen_diskon){
                $kurang_diskon = $subharga * $persen_diskon / 100;
            }else{
                $kurang_diskon = 0;
            }

            // $komisi1 = $subharga * 5 /100;

            if(($subharga - $kurang_voucher - $kurang_voucher) > 0){
                $komisi1 = ($subharga - $kurang_voucher - $kurang_diskon) * $produk['komisi'] /100;
                $komisi = $komisi1 / count($keranjang['id_karyawan']);
                foreach($keranjang['id_karyawan'] as $id_karyawan){
                    $data_komisi = [
                        'id_pembelian' => $id_pembelian,
                        'id_kry'  => $id_karyawan,
                        'komisi' => $komisi,
                        'tgl' => date('Y-m-d')
                    ];
                    $this->db->insert('komisi', $data_komisi); 
                }
            }
            
        }

        
        }else{
            $id_servis=$keranjang['id'];
            $servis = $this->db->get_where('tb_servis', array('id_servis' => $id_servis))->row_array();
                $resep = $this->db->get_where('tb_resep',['id_servis' => $keranjang['id']])->result();
                if(!empty($resep)){
                    foreach($resep as $b){
                        $jml_takaran = $b->takaran * $keranjang['qty'];
                        $bahan = $this->db->get_where('tb_produk',['id_produk' => $b->id_produk])->result()[0];
                        $data_bahan = [
                            'stok' => $bahan->stok - $jml_takaran
                        ];
                        
                        $this->db->where('id_produk',$b->id_produk);
                        $this->db->update('tb_produk',$data_bahan);
                    }
                }
                
                if($keranjang['price'] > 0){
                    $total_harga_servis = $keranjang['price'] * $keranjang['qty'];
                }else{
                    $total_harga_servis = 0;
                }
                
                $kry = $this->db->get('tb_karyawan')->result_array();
                $id_kry = [];
                $customer = $keranjang['id_customer'];
                foreach($kry as $k){
                    foreach($keranjang['terapis'] as $t){
                        if($k['nm_kry'] == $t){
                            $id_kry [] = $k['id_kry'];
                        }
                    }
                }
                $terapis = '';
                $length = count($keranjang['terapis']);
                $number = 1;
                foreach($keranjang['terapis'] as $karyawan){
                    $terapis .= $karyawan;
                    if($number !== $length){
                        $terapis .= ', ';
                    }            
                    $number ++; 
                }

                //order pembayaran
                foreach ($keranjang['id_order'] as $id_o) {
                    $data_o = [
                        'no_nota' => $no_nota,
                        'bayar' => 'Y'
                    ];

                    $this->db->where('id_order',$id_o);
                    $this->db->update('tb_order',$data_o);
                }

                //end pembayaran
        
                $data = array(  
                    'nm_karyawan' => $terapis,
                    'id_servis'   => $keranjang['id'],
                    'no_nota' => $no_nota,
                    'id_customer'   => $keranjang['id_customer'],
                    'qty' => $keranjang['qty'],
                    'tgl'   => $tanggal,
                    'start'   => $keranjang['start'],
                    'end'   => $keranjang['end'],
                    'biaya'  => $keranjang['price'],
                    'total' => $total_harga_servis,
        
                    'admin'  => $admin
                );
               
                $this->db->insert('tb_app', $data);
                $id_app = $this->db->insert_id();

                if($total_harga_servis > 0){
                    if($persen_voucher){
                        $kurang_voucher = $total_harga_servis * $persen_voucher / 100;
                    }else{
                        $kurang_voucher = 0;
                    }
        
                    if($persen_diskon){
                        $kurang_diskon = $total_harga_servis * $persen_diskon / 100;
                    }else{
                        $kurang_diskon = 0;
                    }
                    // $komisi1 = $total_harga_servis*$servis['komisi']/100;
                    if(($total_harga_servis - $kurang_diskon - $kurang_voucher) > 0){
                        $komisi1 = ($total_harga_servis - $kurang_diskon - $kurang_voucher)*5/100;
                    $komisi = $komisi1 / count($keranjang['terapis']);
                    foreach($id_kry as $id){
                        $data_komisi = [
                            'id_app' => $id_app,
                            'id_kry'  => $id,
                            'komisi' => $komisi,
                            'tgl' => date('Y-m-d')
                        ];
                        $this->db->insert('tb_komisi_app', $data_komisi);
                        }
                    }

                    
                }
                
        
        }
        
    }
    $data_invoice = [
        'no_nota' => $no_nota,
        'total' => $total,
        'cash' => $cash,
        'mandiri_kredit' => $mandiri_kredit,
        'mandiri_debit' => $mandiri_debit,
        'bca_kredit' => $bca_kredit,
        'bca_debit' => $bca_debit,
        'shoope' => $shoope,
        'tokped' => $tokopedia,
        'bayar' => $bayar - $diskon - $debit,
        'kembali' => $bayar -  $total,
        'tgl_jam'=> $tgl_jam,
        'dp'=> $debit,
        'diskon'=> $diskon,
        'kd_dp'=> $kd_dp,
        'id_customer' => $customer,
        'admin' => $admin,
        'nominal_voucher' => $nominal_voucher,
        'id_voucher' => $id_voucher,
    ];
    $this->db->insert('tb_invoice', $data_invoice);
    
    //ganti status dp
    if($debit != 0){
        // $data_dp = [
        //     'kd_dp' => $kd_dp,
        //     'id_customer' => $this->input->post('id_customer'),
        //     'tgl_dp' => $this->input->post('tgl_dp'),
        //     'metode' => $this->input->post('metode'),
        //     'debit' => $debit,
        //     'admin' => $this->session->userdata('nm_user'),
        //     'status' => 2,
        //     'tgl_input' => date('Y-m-d H:i:s')
            
        // ];
        // $this->db->insert('tb_dp', $data_dp);
        $data_status = [
            'status' => 2
        ];
    
        $this->db->where('kd_dp',$kd_dp);
        $this->db->update('tb_dp',$data_status);

        //jurnal dp
        $dat_dp = $this->db->get_where('tb_dp',['kd_dp' => $kd_dp])->row();
        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 5])->result()[0];
        $kode_akun_dp_debit = $this->db->where('id_akun', 5)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun_dp_debit == 0){
            $kode_akun_dp_debit = 1;
        }else{
            $kode_akun_dp_debit += 1;
        }
    $data_dp_debit = [
        'id_buku' => 1,
        'id_akun' => 5,
        'debit' => $dat_dp->jumlah_dp,
        'kredit' => 0,
        'tgl' => date('Y-m-d'),
        'tgl_input' => date('Y-m-d H:i:s'),
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tanggal)).'-'.$kode_akun_dp_debit,
        'kd_gabungan' => $no_nota.$kd_dp,
        'admin' => $admin,
        'ket' =>  'DP '.$kd_dp
    ];

    $this->db->insert('tb_jurnal',$data_dp_debit);


        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 4])->result()[0];
        $kode_akun_dp_kredit = $this->db->where('id_akun', 4)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun_dp_kredit == 0){
            $kode_akun_dp_kredit = 1;
        }else{
            $kode_akun_dp_kredit += 1;
        }
    $data_dp_debit = [
        'id_buku' => 1,
        'id_akun' => 4,
        'debit' => 0,
        'kredit' => $dat_dp->jumlah_dp,
        'tgl' => date('Y-m-d'),
        'tgl_input' => date('Y-m-d H:i:s'),
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tanggal)).'-'.$kode_akun_dp_kredit,
        'kd_gabungan' => $no_nota.$kd_dp,
        'admin' => $admin,
        'ket' =>  'DP '.$kd_dp
    ];

    $this->db->insert('tb_jurnal',$data_dp_debit);
        //end jurnal dp
    }

    // $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun])->result()[0];
    // $kode_akun = $this->db->where('id_akun', $id_akun)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
    // if($kode_akun == 0){
    //     $kode_akun = 1;
    // }else{
    //     $kode_akun += 1;
    // }


    if($cash != 0){
        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 1])->result()[0];
        $kode_akun = $this->db->where('id_akun', 1)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

        $kembali = $bayar - $total;
            $data_cash = [
                'id_buku' => 1,
                'kd_gabungan' => $no_nota,
                'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tanggal)).'-'.$kode_akun,
                'id_akun' => 1,
                'debit' => $cash - $kembali,
                'kredit' => 0,
                'tgl' => date('Y-m-d'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
                'ket' => 'Penjualan'
            ];

            $this->db->insert('tb_jurnal',$data_cash);
    }

    if($bca_kredit != 0 || $bca_debit != 0){
        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 2])->result()[0];
        $kode_akun = $this->db->where('id_akun', 2)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

        $data_piutang_bca = [
            'id_buku' => 1,
            'id_akun' => 2,
            'kd_gabungan' => $no_nota,
            'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tanggal)).'-'.$kode_akun,
            'debit' => $bca_kredit + $bca_debit,
            'kredit' => 0,
            'tgl' => date('Y-m-d'),
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
            'ket' => 'Penjualan'
        ];
                
        $this->db->insert('tb_jurnal',$data_piutang_bca);
    }

    if($mandiri_debit != 0 || $mandiri_kredit != 0){
        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 3])->result()[0];
        $kode_akun = $this->db->where('id_akun', 3)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

        $data_piutang_mandiri = [
                                'id_buku' => 1,
                                'id_akun' => 3,
                                'kd_gabungan' => $no_nota,
                                'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tanggal)).'-'.$kode_akun,
                                'debit' => $mandiri_kredit + $mandiri_debit,
                                'kredit' => 0,
                                'tgl' => date('Y-m-d'),
                                'tgl_input' => date('Y-m-d H:i:s'),
                                'admin' => $admin,
                                'ket' => 'Penjualan'
                            ];
                
            $this->db->insert('tb_jurnal',$data_piutang_mandiri);
    }

    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 4])->result()[0];
        $kode_akun = $this->db->where('id_akun', 4)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

    $kembali = $bayar - $total;
    $total_semua = $mandiri_debit + $mandiri_kredit + $bca_debit + $shoope + $tokopedia + $bca_kredit + $cash - $kembali;
        
        if($total_semua > 0){
            $data_penjualan = [
                'id_buku' => 1,
                'id_akun' => 4,
                'kd_gabungan' => $no_nota,
                'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tanggal)).'-'.$kode_akun,
                'kredit' => $total_semua,
                'debit' => 0,
                'tgl' => date('Y-m-d'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
            ];
                    
            $this->db->insert('tb_jurnal',$data_penjualan); 
        }
        
   
    $this->cart->destroy();
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Proses pembelian berhasil!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/detail_invoice?invoice=$no_nota"),'refresh');
    }else{
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mohon maaf, jumlah bayar kurang dari jumlah tagihan / data pembayaran gagal dikirim. Harap lakukan pembayaran lagi<div class="ml-5 btn btn-sm"></div></div>');
        redirect('Match/payment');
    }


    }else{
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Mohon maaf, data invoice double. Harap print nota sesuai nama customer / No invoice terakhir<div class="ml-5 btn btn-sm"></div></div>');
        redirect('Match/invoice');
    }     
}
public function detail_invoice(){
   $no_nota = $this->input->get('invoice');
    $invoice = $this->db->join('tb_customer','tb_invoice.id_customer = tb_customer.id_customer','left')->get_where('tb_invoice', [
        'no_nota' => $no_nota
    ])->result()[0];
    $produk = $this->db->select('tb_servis.nm_servis as nm_servis, tb_pembelian.diskon as diskon, tb_pembelian.id_produk as id_produk, tb_pembelian.harga as harga, tb_pembelian.jumlah as jumlah, tb_produk.nm_produk')
                ->join('tb_invoice','tb_invoice.no_nota = tb_pembelian.no_nota','left')
                ->join('tb_produk','tb_pembelian.id_produk = tb_produk.id_produk','left')
                ->join('tb_servis','tb_pembelian.id_produk = tb_servis.id_servis','left')
                ->get_where('tb_pembelian',[
        'tb_pembelian.no_nota' => $no_nota
    ])->result();
    
    $servis = $this->db->select('tb_app.biaya as biaya, tb_app.qty as qty, tb_servis.nm_servis as nm_servis')->join('tb_invoice','tb_invoice.no_nota = tb_app.no_nota','left')->join('tb_servis','tb_app.id_servis = tb_servis.id_servis','left')->get_where('tb_app',[
        'tb_app.no_nota' => $no_nota
    ])->result(); 
        $data = [
            'title'  => "Crepese Signature | Detail Invoice", 
            'invoice' => $invoice,
            'servis' => $servis,
            'produk' => $produk,
            'no_nota' => $no_nota,
            'dp' => $this->db->join('tb_customer','tb_dp.id_customer = tb_customer.id_customer')->get_where('tb_dp',['status' => '1'])->result(),
        ];
    
    $this->load->view('invoice/detail_invoice', $data);
}

public function edit_pembayaran(){
    $id_invoice = $this->input->post('id_invoice');
   $no_nota = $this->input->post('no_nota');
    $data = [
        'bayar' => $this->input->post('cash')  + $this->input->post('bca_debit') + $this->input->post('gopay'),
        'cash' => $this->input->post('cash'),
        'mandiri_kredit' => $this->input->post('mandiri_kredit'),
        'mandiri_debit' => $this->input->post('mandiri_debit'),
        'bca_kredit' => $this->input->post('bca_kredit'),
        'bca_debit' => $this->input->post('bca_debit'),
        'gopay' => $this->input->post('gopay'),
        
    ];

    $this->db->where('id', $id_invoice);
    $this->db->update('tb_invoice', $data);


   
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Pembayaran berhasil diubah!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("produk/detail_invoice?invoice=$no_nota"),'refresh');
}

public function nota(){
    $no_nota = $this->input->get('invoice');
    $invoice = $this->db->join('tb_customer','tb_invoice.id_customer = tb_customer.id_customer','left')->get_where('tb_invoice', [
        'no_nota' => $no_nota
    ])->result()[0];
    $produk = $this->db->select('tb_pembelian.harga as harga, tb_pembelian.jumlah as jumlah, tb_produk.nm_produk, tb_produk.harga as harga_asli, tb_pembelian.diskon as diskon, tb_produk.id_kategori as id_kategori')->join('tb_invoice','tb_invoice.no_nota = tb_pembelian.no_nota','left')->join('tb_produk','tb_pembelian.id_produk = tb_produk.id_produk','left')->get_where('tb_pembelian',[
        'tb_pembelian.no_nota' => $no_nota
    ])->result();
    
    $servis = $this->db->select('tb_app.biaya as biaya, tb_app.qty as qty, tb_servis.nm_servis as nm_servis, tb_servis.biaya as hrg_servis')->join('tb_invoice','tb_invoice.no_nota = tb_app.no_nota','left')->join('tb_servis','tb_app.id_servis = tb_servis.id_servis','left')->get_where('tb_app',[
        'tb_app.no_nota' => $no_nota
    ])->result(); 
        $data = [
            'title'  => "Crepese Signature | Detail Invoice", 
            'invoice' => $invoice,
            'servis' => $servis,
            'produk' => $produk
        ];

    $this->load->view('invoice/nota',$data);
}

public function daftar_komisi(){
    if (empty($this->input->post('tgl1'))) {
        $bulan = date('m');
        $year = date('Y');         
        $data = array(
            'title'  => "Crepese Signature | Daftar Komisi",
            'komisi' => $this->M_salon->daftar_komisi(" where MONTH(komisi.tgl) = '$bulan' AND YEAR(komisi.tgl) = '$year'") 
            
        );
    }else{
        $dt_a   = $this->input->post('tgl1');
        $dt_b   = $this->input->post('tgl2');
        $data = array(
            'title'  => "Crepese Signature | Daftar Komisi", 
            'komisi' => $this->M_salon->daftar_komisi(" where komisi.tgl BETWEEN '$dt_a' AND '$dt_b' ")
        );
    }
    $this->load->view('order/daftar_komisi', $data);
}

public function daftar_komisi_app(){
    if (empty($this->input->post('tgl1'))) {
        $bulan = date('m');
        $year = date('Y');        
        $data = array(
            'title'  => "Crepese Signature | Daftar Komisi Appointment",
            'komisi' => $this->M_salon->daftar_komisi_app(" where MONTH(tb_komisi_app.tgl) = '$bulan' AND YEAR(tb_komisi_app.tgl) = $year ") 
            
        );
    }else{
        $dt_a   = $this->input->post('tgl1');
        $dt_b   = $this->input->post('tgl2');
        $data = array(
            'title'  => "Crepese Signature | Daftar Komisi Appointment", 
            'komisi' => $this->M_salon->daftar_komisi_app(" where tb_komisi_app.tgl BETWEEN '$dt_a' AND '$dt_b' ")
        );
    }
    $this->load->view('app/daftar_komisi_app', $data);
}


public function list_penjualan()
{
    if (empty($this->input->post('tgl1'))) {
        $bulan = date('m');
        $year = date('Y');        
        $data = array(
            'title'  => "Crepese Signature | list penjualan",
            'list' => $this->M_salon->get_list_penjualan(" where MONTH(tb_pembelian.tanggal) = '$bulan' AND YEAR(tb_pembelian.tanggal) = '$year'") 
            
        );
    }else{
        $dt_a   = $this->input->post('tgl1');
        $dt_b   = $this->input->post('tgl2');
        $data = array(
            'title'  => "Crepese Signature | list penjualan", 
            'list' => $this->M_salon->get_list_penjualan(" where tb_pembelian.tanggal BETWEEN '$dt_a' AND '$dt_b' ")
        );
    }
    $this->load->view('order/list', $data);
}

function summary_penjualan_produk(){
    $tgl1 = $this->input->post('tgl_list_penjualan1');
    $tgl2 = $this->input->post('tgl_list_penjualan2');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'penjualan'     => $this->M_salon->summary_penjualan_produk($tgl1, $tgl2),
        'detail' => $this->M_salon->get_list_penjualan(" where tb_pembelian.tanggal BETWEEN '$tgl1' AND '$tgl2' "),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('produk/summary_penjualan_produk', $data);
}

function excel_penjualan_produk(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'penjualan'     => $this->M_salon->summary_penjualan_produk($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('produk/excel_penjualan_produk', $data);
}

function excel_detail_penjualan_produk(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'detail' => $this->M_salon->get_list_penjualan(" where tb_pembelian.tanggal BETWEEN '$tgl1' AND '$tgl2' "),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('produk/excel_detail_penjualan', $data);
}


// ================================== END APPOIMENT ==========================================
//===========================================JURNAL===============================
public function jurnal(){

    // $tgl = date('Y-m-d');
    // $tgl2 = date('Y-m-d', strtotime('-30 days', strtotime($tgl)));

    if(empty($this->input->get('month'))){
        $month = date('m');
        $year = date('Y');
    }else{
        $month = $this->input->get('month');
        $year = $this->input->get('year');
    }

    $buku = ['1','2'];

    
    // $jurnal = $this->db->select_sum('debit')->select_sum('kredit')->select('tb_buku.nm_buku, tb_akun.nm_akun, tb_akun.no_akun, tb_jurnal.tgl, tb_jurnal.kd_gabungan')->from('tb_jurnal')->join('tb_buku','tb_jurnal.id_buku = tb_buku.id_buku')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->where_in('tb_jurnal.id_buku',$buku)->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',$year)->group_by('tb_jurnal.id_akun')->group_by('tb_jurnal.tgl')->group_by('tb_jurnal.id_buku')->order_by('tb_jurnal.tgl','ASC')->order_by('tb_jurnal.id_buku','ASC')->order_by('tb_jurnal.id_akun','ASC')->get()->result();
    $jurnal = $this->db->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->where_in('tb_jurnal.id_buku',$buku)->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',$year)->order_by('id_jurnal','DESC')->get('tb_jurnal')->result();

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();
    
    $data = [
        'title' => 'Laporan Jurnal Pendapatan',

        'jurnal' => $jurnal,
        'akun' => $this->db->get('tb_akun')->result(),
        'tahun' => $tahun,
        'month' => $month,
        'year' => $year
    ];

    $this->load->view('pembukuan/jurnal',$data);
}

public function penerimaan_bank(){
    $bank = $this->input->post('bank');
    $jumlah = $this->input->post('jumlah');
    $tgl = $this->input->post('tgl');
    $kd_gabungan = 'ORC'.date('dmy'). strtoupper(random_string('alpha',3));
    $user = $this->session->userdata('nm_user');

    $month = date('m' , strtotime($tgl));
    $year = date('Y' , strtotime($tgl));

    $admin = $this->input->post('admin');

    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $bank])->result()[0];
        $kode_akun = $this->db->where('id_akun', $bank)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }
    $data_penerimaan = [
        'id_buku' => 2,
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'kd_gabungan' => $kd_gabungan,
        'id_akun' => $bank,
        'debit' => $jumlah,
        'tgl' => $tgl,
        'tgl_input' => date('Y-m-d H:i:s'),        
        'admin' => $user,
        'ket' => 'Penerimaan Bank'
    ];

    $this->db->insert('tb_jurnal',$data_penerimaan);

    // if($bank == 6){
    //     $pa_a = 8;
    // }elseif($bank == 7){
    //     $pa_a = 9;
    // }

    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 54])->result()[0];
        $kode_akun = $this->db->where('id_akun', 54)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

    $data_admin = [
        'id_buku' => 2,
        'id_akun' => 54,
        'kd_gabungan' => $kd_gabungan,
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'debit' => $admin,
        'tgl' => $tgl,
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $user,
        
    ];

    $this->db->insert('tb_jurnal',$data_admin);

    if($bank == 6){
        $pa = 2;
    }elseif($bank == 7){
        $pa = 3;
    }

    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $pa])->result()[0];
        $kode_akun = $this->db->where('id_akun', $pa)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

    
    $data_bank = [
        'id_buku' => 2,
        'kd_gabungan' => $kd_gabungan,
        'id_akun' => $pa,
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'kredit' => $admin + $jumlah,
        'tgl' => $tgl,
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $user
    ];

    $this->db->insert('tb_jurnal',$data_bank);

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan! <i class="fas fa-check-circle"></i></div></div>');
    redirect(base_url('Match/jurnal'), 'refresh');
}

public function add_pemasukan(){
    $tgl = $this->input->post('tgl');

    $id_akun_debit = $this->input->post('id_akun_debit');
    $ket_debit = $this->input->post('ket_debit');
    $debit = $this->input->post('debit');

    $id_akun_kredit = $this->input->post('id_akun_kredit');
    $ket_kredit = $this->input->post('ket_kredit');
    $kredit = $this->input->post('kredit');

    $kd_gabungan = 'ORC'.date('dmy', strtotime($tgl)). strtoupper(random_string('alpha',3));
    $admin = $this->session->userdata('nm_user');

    $month = date('m' , strtotime($tgl));
    $year = date('Y' , strtotime($tgl));

    for($count = 0; $count<count($id_akun_kredit); $count++){

        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun_kredit[$count]])->result()[0];
        $kode_akun = $this->db->where('id_akun', $id_akun_kredit[$count])->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

    $data_kredit = [
        'id_buku' => 1,
        'id_akun' => $id_akun_kredit[$count],
        'kd_gabungan' => $kd_gabungan,
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'kredit' => $kredit[$count],
        'debit' => 0,
        'ket' => $ket_kredit[$count],
        'tgl' => $tgl,
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $admin,
        
    ];

    $this->db->insert('tb_jurnal',$data_kredit);
      }


        for($count = 0; $count<count($id_akun_debit); $count++){

        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun_debit[$count]])->result()[0];
        $kode_akun = $this->db->where('id_akun', $id_akun_debit[$count])->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

    $data_debit = [
        'id_buku' => 1,
        'id_akun' => $id_akun_debit[$count],
        'kd_gabungan' => $kd_gabungan,
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'debit' => $debit[$count],
        'kredit' => 0,
        'ket' => $ket_debit[$count],
        'tgl' => $tgl,
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $admin,
        
    ];

    $this->db->insert('tb_jurnal',$data_debit);
      }

      

      $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan! <i class="fas fa-check-circle"></i></div></div>');
      redirect(base_url('Match/jurnal'), 'refresh');

}

public function neraca_saldo(){
    // if(empty($this->input->get('month'))){
    //     $month = date('m');
    //     $year = date('Y');
    // }else{
    //     $month = $this->input->get('month');
    //     $year = $this->input->get('year');
    // }

    

    // $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();
    $data = [
        'title' => 'Data Neraca Saldo',
        'neraca_saldo' => $this->db->join('tb_akun','tb_neraca_saldo.id_akun = tb_akun.id_akun')->get('tb_neraca_saldo')->result(),
        // 'month' => $month,
        // 'tahun' => $tahun,
        'akun' => $this->db->get_where('tb_akun',[
            'neraca_saldo' => 'Y'
        ])->result()
    ];

    $this->load->view('pembukuan/neraca_saldo',$data);
}

public function add_neraca_saldo(){
    $tgl = $this->input->post('tgl');
    $bulan = date('m', strtotime($tgl));
    $tahun = date('Y', strtotime($tgl));

    $data = [
        'tgl' => $this->input->post('tgl'),
        'id_akun' => $this->input->post('id_akun'),
        'debit_saldo' => $this->input->post('debit'),
        'kredit_saldo' => $this->input->post('kredit')
    ];

    $this->db->insert('tb_neraca_saldo',$data);

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambah! </div>');
    redirect("Match/neraca_saldo?month=$bulan&year=$tahun"); 
    
}

public function edit_saldo(){
    $id_akun = $this->input->post('id_akun');
    $data = [
        'debit_akun' => $this->input->post('debit'),
        'kredit_akun' => $this->input->post('kredit')
    ];

    $this->db->where('id_akun',$id_akun);
    $this->db->update('tb_akun',$data);
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah! </div>');
    redirect('Match/neraca_saldo'); 
}

public function akun(){
    $akun_relation = $this->db->query("SELECT tb_akun.id_akun as id_akun, nm_akun, relasi.id_relation_debit, relasi.id_relation_kredit 
    FROM tb_akun
    LEFT JOIN (SELECT id_akun, id_relation_debit, id_relation_kredit FROM tb_relasi_akun) relasi ON tb_akun.id_akun = relasi.id_akun
    ")->result();
    $data = [
        'title' => 'Data Akun',
        'akun' => $this->db->join('tb_kategori_akun','tb_akun.id_kategori = tb_kategori_akun.id_kategori')->get('tb_akun')->result(),
        'kategori' => $this->db->get('tb_kategori_akun')->result(),
        'akun_relation' => $akun_relation
    ];

    $this->load->view('pembukuan/akun',$data);
}

public function add_akun(){

    if($this->input->post('pl') == 'Y'){
        $pl = 'Y';
    }else{
        $pl = 'T';
    }

    if($this->input->post('neraca') == 'Y'){
        $neraca = 'Y';
    }else{
        $neraca = 'T';
    }

    if($this->input->post('penyesuaian') == 'Y'){
        $penyesuaian = 'Y';
    }else{
        $penyesuaian = 'T';
    }

    if($this->input->post('neraca_saldo') == 'Y'){
        $neraca_saldo = 'Y';
    }else{
        $neraca_saldo = 'T';
    }

    if($this->input->post('penutup') == 'Y'){
        $penutup = 'Y';
    }else{
        $penutup = 'T';
    }

    $data = [
        'no_akun' => $this->input->post('no_akun'),
        'nm_akun' => $this->input->post('nm_akun'),
        'kd_akun' => $this->input->post('kd_akun'),
        'id_kategori' => $this->input->post('id_kategori'),
        'pl' => $pl,
        'neraca' => $neraca,
        'penyesuaian' => $penyesuaian,
        'neraca_saldo' => $neraca_saldo,
        'penutup' => $penutup
    ];

    $this->db->insert('tb_akun',$data);

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan! <i class="fas fa-check-circle"></i></div></div>');
    redirect(base_url('Match/akun'), 'refresh');
}

public function edit_akun(){
    $id_akun = $this->input->post('id_akun');
    // if($this->input->post('pl') == 'Y'){
    //     $pl = 'Y';
    // }else{
    //     $pl = 'T';
    // }

    // if($this->input->post('neraca') == 'Y'){
    //     $neraca = 'Y';
    // }else{
    //     $neraca = 'T';
    // }
  $data = [
      'no_akun' => $this->input->post('no_akun'),
      'kd_akun' => $this->input->post('kd_akun'),
      'nm_akun' => $this->input->post('nm_akun'),
      'id_kategori' => $this->input->post('id_kategori'),
    //   'pl' => $pl,
    //   'neraca' => $neraca
  ];

  $this->db->where('id_akun',$id_akun);
  $this->db->update('tb_akun',$data);
  $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah! </div>');
  redirect('Match/Akun'); 
}

public function edit_type_akun(){
    $id_akun = $this->input->post('id_akun');
    if($this->input->post('pl') == 'Y'){
        $pl = 'Y';
    }else{
        $pl = 'T';
    }

    if($this->input->post('neraca') == 'Y'){
        $neraca = 'Y';
    }else{
        $neraca = 'T';
    }

    if($this->input->post('penyesuaian') == 'Y'){
        $penyesuaian = 'Y';
    }else{
        $penyesuaian = 'T';
    }

    if($this->input->post('neraca_saldo') == 'Y'){
        $neraca_saldo = 'Y';
    }else{
        $neraca_saldo = 'T';
    }

    if($this->input->post('penutup') == 'Y'){
        $penutup = 'Y';
    }else{
        $penutup = 'T';
    }

    if($this->input->post('al') == 'Y'){
        $al = 'Y';
    }else{
        $al = 'T';
    }

    if($this->input->post('at') == 'Y'){
        $at = 'Y';
    }else{
        $at = 'T';
    }

    if($this->input->post('ekuitas') == 'Y'){
        $ekuitas = 'Y';
    }else{
        $ekuitas = 'T';
    }

    if($this->input->post('pendapatan') == 'Y'){
        $pendapatan = 'Y';
    }else{
        $pendapatan = 'T';
    }

    if($this->input->post('pengeluaran') == 'Y'){
        $pengeluaran = 'Y';
    }else{
        $pengeluaran = 'T';
    }

    if($this->input->post('biaya_fix') == 'Y'){
        $biaya_fix = 'Y';
    }else{
        $biaya_fix = 'T';
    }

    if($this->input->post('pph_hutang') == 'Y'){
        $pph_hutang = 'Y';
    }else{
        $pph_hutang = 'T';
    }

    if($this->input->post('pendapatan_bank') == 'Y'){
        $pendapatan_bank = 'Y';
    }else{
        $pendapatan_bank = 'T';
    }

    if($this->input->post('akun_gantung') == 'Y'){
        $akun_gantung = 'Y';
    }else{
        $akun_gantung = 'T';
    }
  $data = [

    'pl' => $pl,
    'neraca' => $neraca,
    'penyesuaian' => $penyesuaian,
    'neraca_saldo' => $neraca_saldo,
    'penutup' => $penutup,
    'aktiva_l' => $al,
    'aktiva_t' => $at,
    'ekuitas' => $ekuitas,
    'pendapatan' => $pendapatan,
    'pengeluaran' => $pengeluaran,
    'biaya_fix' => $biaya_fix,
    'pph_hutang' => $pph_hutang,
    'pendapatan_bank' => $pendapatan_bank,
    'akun_gantung' => $akun_gantung,
  ];

  $this->db->where('id_akun',$id_akun);
  $this->db->update('tb_akun',$data);
  $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Type akun berhasil diubah! </div>');
  redirect('Match/Akun'); 
}

public function kode()
    {
        $tgl = $this->input->post('tgl');
        $month = date('m' , strtotime($tgl));
        $year = date('Y' , strtotime($tgl));
        $id_akun = $this->input->post('id_akun');
        $kode = $this->db->where('id_akun', $id_akun)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal_pengeluaran')->num_rows();
        if($kode == 0){
            echo '1';
        }else{
            echo $kode + 1;
        }
    }

public function bayar_bkin(){
    $bkin = $this->db->select_sum('kredit','jml_kredit')->select_sum('debit','jml_debit')->select('tgl,no_bkin,kd_gabungan')->group_by('no_bkin')->get_where('tb_jurnal',['id_akun' => 63])->result();

    $data = [
        'title' => 'Pembayarab BKIN',
        'bkin' => $bkin
    ];

    $this->load->view('bkin/index',$data);
}

public function edit_bkin(){
    $id_metode = $this->input->post('id_metode');
    $id_jurnal = $this->input->post('id_bkin');
    $rp_beli = $this->input->post('rp_beli');
    $rp_pajak = $this->input->post('rp_pajak');
    $qty = $this->input->post('qty');
    $ttl_rp = $this->input->post('ttl_rp');

    $admin = $this->session->userdata('nm_user');

    $ppn = $this->input->post('ppn');
    $pajak1 = 0;
    if(!empty($ppn[0])){
    for($count = 0; $count<count($ppn); $count++){
        $pajak1 += $ppn[$count];
    }
    }

    $kd_gabungan = $this->input->post('kd_gabungan');
    $tgl = $this->input->post('tgl');

    // $this->db->where('kd_gabungan',$kd_gabungan);
    // $this->db->where('id_akun',61);
    // $this->db->delete('tb_jurnal');


    $month = date('m' , strtotime($tgl));
    $year = date('Y' , strtotime($tgl));
    $get_kd_pajak = $this->db->get_where('tb_akun',['id_akun' => '61'])->result()[0];
    $kode_pajak = $this->db->where('id_akun', 61)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
    if($kode_pajak == 0){
    $kode_pajak = 1;
    }else{
        $kode_pajak += 1;
    }

    // $data_ppn = $this->db->get_where('tb_jurnal' , )

    $ttl_metode = 0;    
    for($count = 0; $count<count($id_jurnal); $count++){
        $pajak = $rp_beli[$count] * $qty[$count] * 10 / 100;
        $ttl_pajak = $rp_beli[$count] * $qty[$count] + $pajak;
        $ttl_metode += $rp_beli[$count] * $qty[$count];

        $data_jurnal = [
            'rp_beli' => $rp_beli[$count],
            'ttl_rp' => $rp_beli[$count] * $qty[$count],
            'rp_pajak' => $ttl_rp[$count],
            'debit' => $rp_beli[$count] * $qty[$count],
        ];

        $this->db->where('id_jurnal',$id_jurnal[$count]);
        $this->db->update('tb_jurnal',$data_jurnal);

    }

    $data_metode = [
        'kredit' => $ttl_metode + $pajak1,
    ];

    $this->db->where('id_jurnal',$id_metode);
    $this->db->update('tb_jurnal',$data_metode);


    $this->db->where('kd_gabungan',$kd_gabungan);
    $this->db->where('id_akun', 61);
    $this->db->delete('tb_jurnal');

    if($pajak1 != 0){
        $data_pajak = [
            'id_buku' => 3,
            'id_akun' => 61,
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_pajak->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_pajak,
            'debit' => $pajak1,
            'tgl' => $tgl,
          //   'ket'=>'BKIN',
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
        ];
        $this->db->insert('tb_jurnal',$data_pajak);
    }

    


    echo "success";
}

public function get_pembayaran_bkin(){
    $kd_gabungan = $this->input->post('kd_gabungan');
    $no_bkin = $this->input->post('no_bkin');

    // $detail_jurnal = $this->db->select('tgl')->get_where('tb_jurnal',['kd_gabungan' => $kd_gabungan])->row();


    $hutang = $this->db->select_sum('kredit','ttl_hutang')->select_sum('debit','ttl_terbayar')->select('no_bkin,id_jurnal,tgl')->group_by('no_bkin')->get_where('tb_jurnal',[
        'id_akun' => 63, 
        'no_bkin' => $no_bkin])->row();
    $produk = $this->db->where('kd_gabungan',$kd_gabungan)->where('debit !=', null)->where('debit !=', 0)->where('id_akun',27)->get('tb_jurnal')->result();
    $metode = $this->db->get_where('tb_akun',['id_kategori' => 1])->result();
    $kasdll = $this->db->get_where('tb_akun',['id_akun' => 13])->row();

    $get_ppn = $this->db->get_where('tb_jurnal',[
        'id_akun' => 61,
        'kd_gabungan' => $kd_gabungan
    ])->row();

    $sisa = $hutang->ttl_hutang - $hutang->ttl_terbayar;

    echo  '
    <form method="POST" id="edit_bkin"> 
        <div class="row">

          <div class="col-3">
            <div class="form-group">
              <label for="list_kategori">Hutang</label>
              <input class="form-control" type="text" name="hutang" id="hutang" value="Hutang Bk-in" readonly>
              <input type="hidden" name="id_metode" value="'.$hutang->id_jurnal.'">
              <input type="hidden" name="kd_gabungan" value="'.$kd_gabungan.'">
              <input type="hidden" name="tgl" value="'.$hutang->tgl.'">
              
               
            </div>                  
          </div>

          <div class="col-3">
            <div class="form-group">
              <label for="list_kategori">Kode Bkin</label>
              <input class="form-control" type="text" value="'.$hutang->no_bkin.'" readonly>                
            </div>                  
          </div>

          <div class="col-3">
            <div class="form-group">
              <label for="list_kategori">Jumlah</label>
              <input class="form-control" type="text" name="jml_hutang" id="jml_hutang" value="'.$hutang->ttl_hutang.'" readonly>                
            </div>                  
          </div>

          <div class="col-3">
            <div class="form-group">
              <label for="list_kategori">Terbayar</label>
              <input class="form-control" type="text" name="terbayar" id="terbayar" value="'.$hutang->ttl_terbayar.'" readonly>                
            </div>                  
          </div>
          <div class="col-12">
            <hr>                  
          </div>          

        </div>

                             
        <div class="row">
        
            <div class="col-3">
              <label for="list_kategori">Product</label>                
            </div>
            <div class="col-1">
              <label for="list_kategori">Qty</label>                
            </div>
            <div class="col-3">
              <label for="list_kategori">Harga</label>                
            </div>
            <div class="col-2">
              <label for="list_kategori">PPN</label>                
            </div>
            <div class="col-3">
              <label for="list_kategori">Total Harga</label>                
            </div>
            ';
            foreach ($produk as $p) {

            if($get_ppn){
                $ppn = $p->rp_beli * $p->qty * 10 / 100; 
            }  else {
                $ppn = 0;
            }

            $ttl_rp = $p->ttl_rp + $ppn;
              
            
            $dt_produk = $this->db->get_where('tb_produk',['id_produk' => $p->id_produk])->row();    
            
            echo'    
            <div class="col-3">
              <div class="form-group">
              <input type="hidden" name="id_bkin[]" value="'.$p->id_jurnal.'">
                <input class="form-control" type="text"  value="'.$dt_produk->nm_produk.'" readonly>                
              </div>                
            </div>
            <div class="col-1">
              <div class="form-group">
                <input class="form-control qty'.$p->id_jurnal.'" name="qty[]" type="number"  value="'.$p->qty.'" readonly>                
              </div>                
            </div>
            <div class="col-3">
              <div class="form-group">
                <input class="form-control rp_beli rp_beli'.$p->id_jurnal.'" type="text" name="rp_beli[]" rp_beli="'.$p->id_jurnal.'"  value="'.$p->rp_beli.'">                
              </div>                 
            </div>
            <div class="col-2">
              <div class="form-group">
                <input class="form-control ppn'.$p->id_jurnal.' ppn" ppn="'.$p->id_jurnal.'" name="ppn[]" type="text"  value="'.$ppn.'">                
              </div>                
            </div>
            <div class="col-3">
              <div class="form-group">
                <input class="form-control ttl_rp'.$p->id_jurnal.' ttl_rp" name="ttl_rp[]" type="text"  value="'.$ttl_rp.'" readonly>                
              </div>                
            </div>
            ';
        }
        echo'

        </div>
        <button type="submit" class="btn btn-info float-right">Edit</button>
        </form>
        <br><br>
        
    ';

    echo'
    <form action="'. base_url("Match/pembayaran_bkin").'" method="POST">                      
    <div class="row">
      
    <div class="col-12">
    <hr>
    </div>
     
    <div class="col-3">
          <div class="form-group">
            <label for="list_kategori">Tanggal</label>
            <input class="form-control" type="date" name="tgl" required>                
          </div>                  
        </div>

      <div class="col-3">
          <div class="form-group">
          <label for="list_kategori">Metode</label>
              <select name="id_metode" class="form-control select" required>
                ';
                echo'
                    <option value="'.$kasdll->id_akun.'">'.$kasdll->nm_akun.'</option>
                    ';
                foreach($metode as $m){
                    echo'
                    <option value="'.$m->id_akun.'">'.$m->nm_akun.'</option>
                    ';
                }
                    
                
                  

                echo'
              </select>                
          </div>                  
        </div>

        <div class="col-3">
          <div class="form-group">
            <label for="list_kategori">Bayar</label>
            <input class="form-control" type="hidden" name="no_bkin" value="'.$hutang->no_bkin.'">
            <input class="form-control" type="number" name="jml_bayar" id="jml_bayar" value="'.$sisa.'" max="'.$sisa.'">                
          </div>                  
        </div>

        <div class="col-3">
        <br>
        <button type="submit" class="btn btn-info mt-2">Save</button>                
        </div>

    </div>
    </form>
    ';

}

public function pembayaran_bkin(){
    $metode = $this->input->post('id_metode');
    $no_bkin = $this->input->post('no_bkin');
    $jml_bayar = $this->input->post('jml_bayar');
    $tgl = $this->input->post('tgl');

    $kd_gabungan = 'ORC'.date('dmy'). strtoupper(random_string('alpha',3));
    $admin = $this->session->userdata('nm_user');

    $month = date('m' , strtotime($tgl));
    $year = date('Y' , strtotime($tgl));

   $get_kd_hbkin = $this->db->get_where('tb_akun',['id_akun' => 63])->result()[0];
    // $kd_hbkin = $this->db->where('id_akun', 63)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
    // if($kd_hbkin == 0){
    //     $kd_hbkin = 1;
    // }else{
    //     $kd_hbkin += 1;
    // }

    $get_urutan_akun = $this->db->order_by('no_nota','DESC')->get_where('tb_jurnal',[
        'id_akun' => 63,
        'MONTH(tgl)' => $month,
        'YEAR(tgl)' => $year
      ])->row();
   
      // var_dump ($get_urutan_akun);
   
      if($get_urutan_akun){
        $pecah = explode("-" , $get_urutan_akun->no_nota);
        $kd_hbkin = $pecah[1] + 1;
      }else{
        $kd_hbkin = 1;
      }

    $get_kd_metode = $this->db->get_where('tb_akun',['id_akun' => $metode])->result()[0];
    $kode_metode = $this->db->where('id_akun', $metode)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
    if($kode_metode == 0){
      $kode_metode = 1;
  }else{
    $kode_metode += 1;
}


$data_metode = [
    'id_buku' => 3,
    'id_akun' => $metode,
    'kd_gabungan' => $kd_gabungan,
    'no_nota' => $get_kd_metode->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_metode,
    'kredit' => $jml_bayar,
    'tgl' => $tgl,
    'tgl_input' => date('Y-m-d H:i:s'),
    'admin' => $admin,
  ];
  $this->db->insert('tb_jurnal',$data_metode);

  $data_bkin = [
    'id_buku' => 3,
    'id_akun' => 63,
    'kd_gabungan' => $kd_gabungan,
    'no_nota' => $get_kd_hbkin->kd_akun.date('my' , strtotime($tgl)).'-'.$kd_hbkin,
    'debit' => $jml_bayar,
    'tgl' => $tgl,
    'no_bkin' => $no_bkin,
    'tgl_input' => date('Y-m-d H:i:s'),
    'admin' => $admin,
  ];
  $this->db->insert('tb_jurnal',$data_bkin);

  $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan! </div>');
redirect('Match/bayar_bkin'); 


}

public function pengeluaran(){
    // $tgl = date('Y-m-d');
    // $tgl2 = date('Y-m-d', strtotime('-3 days', strtotime($tgl)));

    // $jurnal = $this->db->select_sum('debit')->select_sum('kredit')->select('tb_buku.nm_buku, tb_akun.nm_akun, tb_akun.no_akun, tb_jurnal_pengeluaran.tgl, tb_jurnal_pengeluaran.ket')->from('tb_jurnal_pengeluaran')->join('tb_buku','tb_jurnal_pengeluaran.id_buku = tb_buku.id_buku')->join('tb_akun','tb_jurnal_pengeluaran.id_akun = tb_akun.id_akun')->where('tb_jurnal_pengeluaran.id_buku','3')->where('tb_jurnal_pengeluaran.tgl >=',$tgl2)->where('tb_jurnal_pengeluaran.tgl <=',$tgl)->group_by('tb_jurnal_pengeluaran.id_akun')->group_by('tb_jurnal_pengeluaran.tgl')->order_by('tb_jurnal_pengeluaran.tgl','ASC')->order_by('tb_jurnal_pengeluaran.id_akun','ASC')->get()->result();
    // $id_akun = ['1', '2', '3','4','5','6','7','8','9'];
    if(empty($this->input->get('tgl1'))){
        $month = date('m');
        $year = date('Y');

        $last_date=cal_days_in_month(CAL_GREGORIAN,$month,$year);

        $tgl1 = $year.'-'.$month.'-01';
        $tgl2 = $year.'-'.$month.'-'.$last_date;

        // $jurnal = $this->db->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->where('tb_jurnal.id_buku','3')->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',$year)->order_by('id_jurnal','DESC')->get('tb_jurnal')->result();
        // $sort = '';


       

    }else{
        $tgl1 = $this->input->get('tgl1');
        $tgl2 = $this->input->get('tgl2');
    }

    $jurnal = $this->db->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->where('tb_jurnal.id_buku','3')->where('tb_jurnal.tgl >=',$tgl1)->where('tb_jurnal.tgl <=',$tgl2)->order_by('id_jurnal','DESC')->get('tb_jurnal')->result();
    $sort = date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2));
    
    $data = [
        'title' => 'Laporan Jurnal Pengeluaran',

        'jurnal' => $jurnal,
        'akun' => $this->db->where('id_kategori !=','2')->where('id_kategori !=','3')->get('tb_akun')->result(),
        'akun_all' => $this->db->get('tb_akun')->result(),
        'kas' => $this->db->where_in('id_kategori',['1','5'])->get('tb_akun')->result(),
        'satuan' => $this->db->get('tb_satuan')->result(),
        'produk' => $this->db->get('tb_produk')->result(),
        'sort' => $sort,
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
        'aktiva' => $this->db->get('tb_kelompok_aktiva')->result(),
        'laba' => $this->db->get_where('tb_akun',['id_kategori' => 10])->result()
    ];

    $this->load->view('pembukuan/jurnal_pengeluaran',$data);
}

public function rekapitulasi_pengeluaran(){
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $pengeluaran = $this->db->select_sum('tb_jurnal.debit','debit')->select_sum('tb_jurnal.kredit','kredit')->select('tb_akun.nm_akun, tb_jurnal.tgl, tb_akun.no_akun, tb_akun.id_akun')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->group_by('tb_jurnal.id_akun')->order_by('tb_akun.no_akun','ASC')->get_where('tb_jurnal',[
        'tb_jurnal.tgl >=' => $tgl1,
        'tb_jurnal.tgl <=' => $tgl2,
        'tb_jurnal.id_buku' => 3 
    ])->result();

    $data = [
        'title' => 'Rekapitulasi Jurnal Pengeluaran',
        'pengeluaran' => $pengeluaran,
        'tgl1' => $tgl1,
        'tgl2' => $tgl2
    ];
    

    $this->load->view('pembukuan/rekapitulasi_pengeluaran',$data);
}

public function detail_pengeluaran(){
    $id_akun = $this->input->get('id');
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $buku = $this->db->order_by('tgl','ASC')->get_where('tb_jurnal',[
        'id_akun' => $id_akun,
        'tgl >=' => $tgl1,
        'tgl <=' => $tgl2,
        'id_buku' => 3
        ])->result();

    $data = [
        'title' => 'Buku Besar',
        'buku' => $buku,
        'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row(),
        'tgl1' => $tgl1,
        'tgl2' => $tgl2
    ];

    $this->load->view('pembukuan/detail_pengeluaran',$data);
}

public function print_detail_pengeluaran(){
    $id_akun = $this->input->get('id');
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $buku = $this->db->order_by('tgl','ASC')->get_where('tb_jurnal',[
        'id_akun' => $id_akun,
        'tgl >=' => $tgl1,
        'tgl <=' => $tgl2,
        'id_buku' => 3
        ])->result();

    $data = [
        'title' => 'Buku Besar',
        'buku' => $buku,
        'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row(),
        'tgl1' => $tgl1,
        'tgl2' => $tgl2
    ];

    $this->load->view('pembukuan/print_detail_pengeluaran',$data);
}

public function rekapitulasi_pemasukan(){
    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $pemasukan = $this->db->select_sum('tb_jurnal.debit','debit')->select_sum('tb_jurnal.kredit','kredit')->select('tb_akun.nm_akun, tb_jurnal.tgl, tb_akun.no_akun, tb_akun.id_akun')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->group_by('tb_jurnal.id_akun')->order_by('tb_akun.no_akun','ASC')->get_where('tb_jurnal',[
        'MONTH(tb_jurnal.tgl)' => $month,
        'YEAR(tb_jurnal.tgl)' => $year,
        'tb_jurnal.id_buku' => 1, 
    ])->result();

    $data = [
        'title' => 'Rekapitulasi Jurnal Pemasukan',
        'pemasukan' => $pemasukan,
        'month' => $month,
        'year' => $year
    ];
    

    $this->load->view('pembukuan/rekapitulasi_pemasukan',$data);
}

public function detail_pemasukan(){
    $id_akun = $this->input->get('id');
    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $buku = $this->db->order_by('tgl','ASC')->get_where('tb_jurnal',[
        'id_akun' => $id_akun,
        'MONTH(tgl)' => $month,
        'YEAR(tgl)' => $year,
        'id_buku' => 1
        ])->result();

    $data = [
        'title' => 'Buku Besar',
        'buku' => $buku,
        'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row(),
        'month' => $month,
        'year' => $year
    ];

    $this->load->view('pembukuan/detail_pemasukan',$data);
}

public function excel_pengeluaran(){
        $tgl1 = $this->input->get('tgl1');
        $tgl2 = $this->input->get('tgl2');
        $jurnal = $this->db->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->where('tb_jurnal.id_buku','3')->where('tb_jurnal.tgl >=',$tgl1)->where('tb_jurnal.tgl <=',$tgl2)->order_by('id_jurnal','ASC')->get('tb_jurnal')->result();
        $sort = date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2));
    
    $data = [
        'title' => 'Laporan Jurnal Pengeluaran',

        'jurnal' => $jurnal,
        'sort' => $sort
    ];

    $this->load->view('pembukuan/excel_pengeluaran',$data);
}

public function excel_pemasukan(){
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');
    $jurnal = $this->db->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->where('tb_jurnal.id_buku','1')->where('tb_jurnal.tgl >=',$tgl1)->where('tb_jurnal.tgl <=',$tgl2)->order_by('id_jurnal','ASC')->get('tb_jurnal')->result();
    $sort = date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2));

$data = [
    'title' => 'Laporan Jurnal Pemasukan',

    'jurnal' => $jurnal,
    'sort' => $sort
];

$this->load->view('pembukuan/excel_pemasukan',$data);
}

public function get_bpenyusutan(){
    $nota = $this->input->post('nota');
    $aktiva = $this->db->get_where('aktiva',[
    'nota' => $nota,
    'debit_aktiva !=' => 0])->result();
     $output = [];
     foreach($aktiva as $k){
        $output['b_penyusutan']= $k->b_penyusutan;
     }   
     echo json_encode($output);
}

public function get_data_produk(){
    $id_produk = $this->input->post('id_produk');
    $produk = $this->db->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan')->get_where('tb_produk',['tb_produk.id_produk' => $id_produk])->result();
    $output = [];
    foreach($produk as $d){
        $output['sku']= $d->sku;
        $output['satuan']= $d->satuan;
        $output['monitoring']= $d->monitoring;

    }   


    echo json_encode($output);
}

public function add_bkin(){
    $kd_gabungan = 'ORC'.date('dmy'). strtoupper(random_string('alpha',3));
    $tgl = $this->input->post('tgl');
    $id_akun = $this->input->post('id_akun');
    $metode = $this->input->post('metode');
    // $ket = $this->input->post('ket');
    $rp_pajak = $this->input->post('rp_pajak');
    // $total = $this->input->post('total');
    $admin = $this->session->userdata('nm_user');

    $id_produk = $this->input->post('id_produk');
    $qty = $this->input->post('qty');
    $rp_beli = $this->input->post('rp_beli');
    $mon = $this->input->post('mon');
    $ppn = $this->input->post('ppn');

    $no_bkin = $this->input->post('no_bkin');


    $month = date('m' , strtotime($tgl));
    $year = date('Y' , strtotime($tgl));

    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun])->result()[0];
     // $kode_akun = $this->db->where('id_akun', $id_akun)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        
        // if($kode_akun == 0){
        //     $kode_akun = 1;
        // }else{
        //     $kode_akun += 1;
        // }
        
        $get_urutan_akun = $this->db->order_by('no_nota','DESC')->get_where('tb_jurnal',[
            'id_akun' => $id_akun,
            'MONTH(tgl)' => $month,
            'YEAR(tgl)' => $year
          ])->row();
  
          // var_dump ($get_urutan_akun);
  
          if($get_urutan_akun){
            $pecah = explode("-" , $get_urutan_akun->no_nota);
            $kode_akun = $pecah[1] + 1;
          }else{
            $kode_akun = 1;
          }

    $get_kd_metode = $this->db->get_where('tb_akun',['id_akun' => $metode])->result()[0];
    $kode_metode = $this->db->where('id_akun', $metode)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
    if($kode_metode == 0){
      $kode_metode = 1;
  }else{
    $kode_metode += 1;
}

$get_kd_pajak = $this->db->get_where('tb_akun',['id_akun' => '61'])->result()[0];
$kode_pajak = $this->db->where('id_akun', 61)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
if($kode_pajak == 0){
  $kode_pajak = 1;
}else{
    $kode_pajak += 1;
}



$total = 0;
for($count = 0; $count<count($rp_pajak); $count++){
  $total += $rp_pajak[$count];
}

$data_metode = [
  'id_buku' => 3,
  'id_akun' => $metode,
  'kd_gabungan' => $kd_gabungan,
  'no_nota' => $get_kd_metode->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_metode,
  'kredit' => $total,
  'no_bkin' => $no_bkin,
  'tgl' => $tgl,
      'ket'=>'BKIN',
  'tgl_input' => date('Y-m-d H:i:s'),
  'admin' => $admin,
];
$this->db->insert('tb_jurnal',$data_metode);



for($count = 0; $count<count($rp_pajak); $count++){
      // $total += $rp_pajak[$count];
  $get_produk = $this->db->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan')->get_where('tb_produk',['id_produk' => $id_produk[$count]])->result()[0];
  $row_produk = $this->db->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan')->get_where('tb_produk',['id_produk' => $id_produk[$count]])->row();

  if ($row_produk->monitoring!=$mon[$count]) 
  {
    $this->db->where('id_produk', $id_produk[$count]);
    $data_promon = array(
        'monitoring'    => $mon[$count]
    );
    $this->db->update('tb_produk', $data_promon);
    $data_mon = array(
        'id_produk'  =>  $id_produk[$count],
        'tanggal'    => date('Y-m-d'),
        'stok'       => $qty[$count]
    );
    $this->db->insert('tb_monitoring', $data_mon);
    $data_mon2 = array(
      'id_produk'  =>  $id_produk[$count],
      'tanggal'    => date('Y-m-d'),
      'status'       => $mon[$count]
  );
    $this->db->insert('tb_monitoring2', $data_mon2);
}
else
{
    $data_mon3 = array(
        'id_produk'  =>  $id_produk[$count],
        'tanggal'    => date('Y-m-d'),
        'stok'       => $qty[$count]
    );
    $this->db->insert('tb_monitoring', $data_mon3);
}

if (!empty($ppn[$count]) || $ppn[$count]!=0) 
{
   $data_pajak = [
      'id_buku' => 3,
      'id_akun' => 61,
      'kd_gabungan' => $kd_gabungan,
      'no_nota' => $get_kd_pajak->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_pajak,
      'debit' => $ppn[$count],
      'tgl' => $tgl,
    //   'ket'=>'BKIN',
      'tgl_input' => date('Y-m-d H:i:s'),
      'admin' => $admin,
  ];
  $this->db->insert('tb_jurnal',$data_pajak);
  $kode_pajak ++;
}


$data_jurnal = [
  'id_buku' => 3,
  'id_akun' => $id_akun,
  'kd_gabungan' => $kd_gabungan,
  'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
  'debit' => $rp_beli[$count] * $qty[$count] ,
  'ket' => $get_produk->nm_produk,
  'tgl' => $tgl,
  'tgl_input' => date('Y-m-d H:i:s'),
  'admin' => $admin,
  'id_produk' => $id_produk[$count],
  'qty' => $qty[$count],
  'rp_beli' => $rp_beli[$count],
  'ttl_rp' => $rp_beli[$count] * $qty[$count],
  'rp_pajak' => $rp_pajak[$count]
];

$this->db->insert('tb_jurnal',$data_jurnal);

if($mon[$count] == 'y'){
    $stok_dulu = $get_produk->stok;
    $data = [
        'stok' => $stok_dulu + $qty[$count]
    ]; 
    $this->db->where('id_produk',$id_produk[$count]);
    $this->db->update('tb_produk',$data);
}



// $kode_akun++;
}



$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan! </div>');
redirect('Match/pengeluaran');    

}

public function add_pengeluaran(){
    $kd_gabungan = 'ORC'.date('dmy'). strtoupper(random_string('alpha',3));
    $tgl = $this->input->post('tgl');
    $id_akun = $this->input->post('id_akun');
    $metode = $this->input->post('metode');
    $ket = $this->input->post('ket');
    $ttl_rp = $this->input->post('ttl_rp');
    // $total = $this->input->post('total');
    $admin = $this->session->userdata('nm_user');

    $id_satuan = $this->input->post('id_satuan');
    $qty = $this->input->post('qty');
    $rp_beli = $this->input->post('rp_beli');
    
    $id_post_center = $this->input->post('id_post_center');

    $no_urutan = $this->input->post('no_urutan');

    $urutan = (int) filter_var($no_urutan, FILTER_SANITIZE_NUMBER_INT);
    


        $month = date('m' , strtotime($tgl));
        $year = date('Y' , strtotime($tgl));

        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun])->result()[0];
         // $kode_akun = $this->db->where('id_akun', $id_akun)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        
        // if($kode_akun == 0){
        //     $kode_akun = 1;
        // }else{
        //     $kode_akun += 1;
        // }
        
        $get_urutan_akun = $this->db->order_by('id_jurnal','DESC')->get_where('tb_jurnal',[
            'id_akun' => $id_akun,
            'MONTH(tgl)' => $month,
            'YEAR(tgl)' => $year
          ])->row();
  
          // var_dump ($get_urutan_akun);
  
          if($get_urutan_akun){
            $pecah = explode("-" , $get_urutan_akun->no_nota);
            $kode_akun = $pecah[1] + 1;
          }else{
            $kode_akun = 1;
          }

        $get_kd_metode = $this->db->get_where('tb_akun',['id_akun' => $metode])->result()[0];
        $kode_metode = $this->db->where('id_akun', $metode)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_metode == 0){
          $kode_metode = 1;
        }else{
            $kode_metode += 1;
        }

    $total = 0;
    for($count = 0; $count<count($ttl_rp); $count++){
      $total += $ttl_rp[$count];
    }

    $dt_ak = $this->db->get_where('tb_akun',['id_akun'=>$id_akun])->row();
    
    $data_metode = [
      'id_buku' => 3,
      'id_akun' => $metode,
      'kd_gabungan' => $kd_gabungan,
      'no_nota' => $get_kd_metode->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_metode,
      'kredit' => $total,
      'tgl' => $tgl,
      'tgl_input' => date('Y-m-d H:i:s'),
      'admin' => $admin,
      'ket'=>$dt_ak->nm_akun,
      'no_urutan' => $no_urutan,
      'urutan' => $urutan,
  ];

  $this->db->insert('tb_jurnal',$data_metode);

  if(!empty($id_satuan)){
    for($count = 0; $count<count($ttl_rp); $count++){
        // $total += $ttl_rp[$count];

        //jurnal
        $data_jurnal = [
            'id_buku' => 3,
            'id_akun' => $id_akun,
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
            'debit' => $ttl_rp[$count],
            'ket' => $ket[$count],
            'tgl' => $tgl,
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
            
            'id_post_center' => $id_post_center ? $id_post_center[$count] : 0,
             'no_urutan' => $no_urutan,
            'urutan' => $urutan,
  
            'qty' => $qty[$count],
            'id_satuan' => $id_satuan[$count],
            'rp_beli' => $rp_beli[$count],
            'ttl_rp' => $ttl_rp[$count],
        ];
  
        $this->db->insert('tb_jurnal',$data_jurnal);

        //peralatan
        $data_peralatan = [
            'kd_gabungan' => $kd_gabungan,
            'ket' => $ket[$count],
            'rp_beli' => $rp_beli[$count],
            'qty' => $qty[$count],
            'id_satuan' => $id_satuan[$count],
            'ttl_rp' => $ttl_rp[$count],            
            'tgl' => $tgl,
            'admin' => $admin    
        ];

        $this->db->insert('tb_peralatan',$data_peralatan);

        // $kode_akun++;
      }
  }else{
    for($count = 0; $count<count($ket); $count++){
        // $total += $ttl_rp[$count];
        $data_jurnal = [
            'id_buku' => 3,
            'id_akun' => $id_akun,
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
            'debit' => $ttl_rp[$count],
            'ket' => $ket[$count],
            'tgl' => $tgl,
            'ttl_rp' => $ttl_rp[$count],
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
            
            'id_post_center' => $id_post_center ? $id_post_center[$count] : 0,
             'no_urutan' => $no_urutan,
            'urutan' => $urutan,
        ];
  
        $this->db->insert('tb_jurnal',$data_jurnal);
        // $kode_akun++;
      }
  }

    

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diinput!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/pengeluaran"),'refresh');
}

public function add_prive(){
    $kd_gabungan = 'ORC'.date('dmy'). strtoupper(random_string('alpha',3));
    $tgl = $this->input->post('tgl');
    $id_akun = $this->input->post('id_akun');
    $metode = $this->input->post('metode');
    $ket = $this->input->post('ket');
    $ttl_rp = $this->input->post('ttl_rp');
    // $total = $this->input->post('total');
    $admin = $this->session->userdata('nm_user');

    $laba = $this->input->post('laba');


        $month = date('m' , strtotime($tgl));
        $year = date('Y' , strtotime($tgl));

        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun])->result()[0];
        $kode_akun = $this->db->where('id_akun', $id_akun)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

        $get_kd_metode = $this->db->get_where('tb_akun',['id_akun' => $metode])->result()[0];
        $kode_metode = $this->db->where('id_akun', $metode)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_metode == 0){
          $kode_metode = 1;
        }else{
            $kode_metode += 1;
        }

        $get_kd_laba = $this->db->get_where('tb_akun',['id_akun' => $laba])->result()[0];
        $kode_laba = $this->db->where('id_akun', $laba)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_laba == 0){
          $kode_laba = 1;
        }else{
            $kode_laba += 1;
        }

    //laba
    $data_laba = [
        'id_buku' => 3,
        'id_akun' => $laba,
        'kd_gabungan' => $kd_gabungan,
        'no_nota' => $get_kd_laba->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_laba,
        'debit' => $ttl_rp,
        'kredit' => 0,
        'tgl' => $tgl,
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $admin,
        'ket' => $ket
    ];
  
    $this->db->insert('tb_jurnal',$data_laba);
    
    //metode
  $data_metode = [
    'id_buku' => 3,
    'id_akun' => $metode,
    'kd_gabungan' => $kd_gabungan,
    'no_nota' => $get_kd_metode->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_metode,
    'debit' => 0,
    'kredit' => $ttl_rp,
    'tgl' => $tgl,
    'tgl_input' => date('Y-m-d H:i:s'),
    'admin' => $admin,
    'ket'=>$ket
];

$this->db->insert('tb_jurnal',$data_metode);

    //akun
    $data_akun = [
      'id_buku' => 3,
      'id_akun' => $id_akun,
      'kd_gabungan' => $kd_gabungan,
      'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
      'debit' => $ttl_rp,
      'kredit' => 0 ,
      'tgl' => $tgl,
      'tgl_input' => date('Y-m-d H:i:s'),
      'admin' => $admin,
      'ket'=>$ket
  ];

  $this->db->insert('tb_jurnal',$data_akun);    

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diinput!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/pengeluaran"),'refresh');
}

public function get_jurnal(){
    $kd_gabungan = $this->input->post('kd_gabungan');

    $kredit = $this->db->where('kd_gabungan',$kd_gabungan)->where('kredit !=', null)->where('kredit !=', 0)->get('tb_jurnal')->row();
    $debit = $this->db->where('kd_gabungan',$kd_gabungan)->where('debit !=', null)->where('debit !=', 0)->get('tb_jurnal')->result();

    $debit1 = $this->db->where('kd_gabungan',$kd_gabungan)->where('debit !=', null)->where('debit !=', 0)->get('tb_jurnal')->row();

    $akun = $this->db->get('tb_akun')->result();

    $kas = $this->db->get('tb_akun')->result();

    // $kas = $this->db->where_in('id_kategori',['1','5'])->get('tb_akun')->result();

    $satuan = $this->db->get('tb_satuan')->result();

    $produk = $this->db->get('tb_produk')->result();

    echo '
    <div class="row">

    <div class="col-sm-3 col-md-3">
        <div class="form-group">
            <label for="list_kategori">Tanggal</label>
            <input class="form-control" type="date" name="tgl" id="tgl_edit" value="'. $kredit->tgl .'" required>
                            
        </div>                            
    </div>
    <input type="hidden" name="id_jurnal_kredit" value="'.$kredit->id_jurnal.'">
    
     <input type="hidden" name="id_akun_dulu" value="'.$debit1->id_akun.'">
        <input type="hidden" name="id_metode_dulu" value="'.$kredit->id_akun.'">

        <input type="hidden" name="tgl_dulu" value="'.$kredit->tgl.'">
    

        
        <div class="col-sm-4 col-md-4">
            <div class="form-group">
                <label for="list_kategori">Akun</label>
                <select name="metode" id="id_akun_edit" class="form-control select" required="">
    ';
    foreach($kas as $a){
      if($kredit->id_akun == $a->id_akun){
        echo '<option value="'. $a->id_akun .'" selected>'. $a->nm_akun .'</option>';
      }else {
        echo '<option value="'. $a->id_akun .'">'. $a->nm_akun .'</option>';
      }
      
    }

    echo '
    </select>
          </div>
      </div>

      <div class="col-sm-2 col-md-2">
      <div class="form-group">
          <label for="list_kategori">Kredit</label>
          <input type="number" class="form-control total" readonly value="'.$kredit->kredit.'">                                         
      </div>                            
      </div>
      
      <div class="col-sm-3 col-md-3">
        <div class="form-group">
            <label for="list_kategori">No Urutan</label>
            <input class="form-control" type="text" name="no_urutan" value="'. $kredit->no_urutan .'" required>
                            
        </div>                            
    </div>
      
    </div>';

    echo '
        <div id="non_monitoring" class="detail">
        <hr>
        ';

        foreach($debit as $d){
            
            echo '
            <div class="row">
            <input type="hidden" name="id_jurnal[]" value="'.$d->id_jurnal.'">

            <div class="col-md-3">
                        <div class="form-group">
                            <label for="list_kategori">Akun</label>
                            <select name="id_akun[]"  class="form-control select" required>
                            ';
                                                                    
                            foreach($akun as $a){
                                if($d->id_akun == $a->id_akun){
                                  echo '<option value="'. $a->id_akun .'" selected>'. $a->nm_akun .'</option>';
                                }else {
                                  echo '<option value="'. $a->id_akun .'">'. $a->nm_akun .'</option>';
                                }
                                
                              }
                          
                              echo '
                              </select>
                                    </div>
                                </div>
                                
            <div class="col-md-3">
                        <div class="form-group">
                            <label for="list_kategori">Post Center</label>
                            <select name="id_post_center[]"  class="form-control select">
                            ';
                            $dt_post = $this->db->get_where('tb_post_center',['id_akun' => $d->id_akun])->result();
                            if($dt_post){
                                echo '<option value="">-Pilih Post Center-</option>';
                            }
                                                                    
                            foreach($dt_post as $post){
                                if($d->id_post_center == $post->id_post){
                                  echo '<option value="'. $post->id_post .'" selected>'. $post->nm_post .'</option>';
                                }else {
                                  echo '<option value="'. $post->id_post .'">'. $post->nm_post .'</option>';
                                }
                                
                              }
                          
                              echo '
                              </select>
                                    </div>
                                </div>
                                
                                

            <div class="col-md-3">
                        <div class="form-group">
                            <label for="list_kategori">Keterangan</label>
                            <input type="text" class="form-control input_detail input_non_monitoring" name="ket[]" value="'.$d->ket.'">                                        
                        </div>                            
            </div>                       
    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="list_kategori">Debit</label>
                            <input type="text" class="form-control  input_detail input_non_monitoring total_rp" name="ttl_rp[]" value="'.$d->debit.'" required>                                        
                        </div>                            
                    </div>
    
                </div>
            ';
        }

        echo '</div>';

    // if(!empty($debit1->id_produk)){
    //     echo'
    //         <div id="bkin" class="detail">
    //         <hr>
    //         ';
    //     foreach($debit as $key => $d){
    //         $detail = $key + 1;
    //         echo '
    //         <div class="row">
    //         <input type="hidden" name="id_jurnal[]" value="'.$d->id_jurnal.'">
    //         <div class="col-md-4">
    //                   <div class="form-group">
    //                       <label for="list_kategori">Produk</label>
    //                       <select name="id_produk[]" class="form-control select id_produk input_detail input_bkin" detail="1" required>
    //                               <option>-- Pilih Produk --</option>';
    //                              foreach($produk as $p){
    //                                 if($d->id_produk == $p->id_produk){
    //                                     echo '<option value="'. $p->id_produk .'" selected>'. $p->nm_produk .'</option>';
    //                                   }else {
    //                                     echo '<option value="'. $p->id_produk .'">'. $p->nm_produk .'</option>';
    //                                   }
    //                              }
                                                  
    //                       echo '</select> 
                                          
    //                   </div>                            
    //               </div>
  
    //               <div class="col-md-2">
    //                   <div class="form-group">
    //                       <label for="list_kategori">Qty</label>
    //                       <input type="text" class="form-control qty'.$detail.' input_detail input_bkin" qty="'.$detail.'" name="qty[]" value="'.$d->qty.'" required>                                        
    //                   </div>                            
    //               </div>
  
    //               <div class="col-md-2">
    //                   <div class="form-group">
    //                       <label for="list_kategori">Rp/Satuan</label>
    //                       <input type="text" class="form-control rp_beli input_detail input_bkin" rp_beli="'.$detail.'" name="rp_beli[]" value="'.$d->rp_beli.'" required>                                        
    //                   </div>                            
    //               </div>
  
    //               <div class="col-md-2">
    //                   <div class="form-group">
    //                       <label for="list_kategori">Rp/Pajak</label>
    //                       <input type="text" class="form-control rp_pajak rp_pajak'.$detail.' input_detail input_bkin" rp_pajak="'.$detail.'" name="rp_pajak[]" value="'.$d->rp_pajak.'" required>                                        
    //                   </div>                            
    //               </div>
  
    //           </div>
    //         ';
    //     }
    //     echo'</div>';
    // }else if(!empty($debit1->id_satuan)){
    //     echo '<div id="monitoring" class="detail">
    //     <hr>';

    //     foreach($debit as $key => $d){
    //         $detail = $key + 1;
    //         echo '
    //         <div class="row">

    //         <input type="hidden" name="id_jurnal[]" value="'.$d->id_jurnal.'">

    //         <div class="col-md-3">
    //                     <div class="form-group">
    //                         <label for="list_kategori">Keterangan</label>
    //                         <input type="text" class="form-control input_detail input_monitoring" name="ket[]" value="'.$d->ket.'" required>                                        
    //                     </div>                            
    //         </div>                        
    
    //           <div class="col-md-2">
    //                     <div class="form-group">
    //                         <label for="list_kategori">Satuan</label>
    //                         <select name="id_satuan[]" class="form-control select satuan input_detail input_monitoring" required>';
    //                                foreach($satuan as $p){
    //                                 if($d->id_satuan == $p->id_satuan){
    //                                     echo '<option value="'. $p->id_satuan .'" selected>'. $p->satuan .'</option>';
    //                                   }else {
    //                                     echo '<option value="'. $p->id_produk .'">'. $p->satuan .'</option>';
    //                                   }
    //                                }
                                                  
    //                         echo '</select> 
                                            
    //                     </div>                            
    //                 </div>
    
    //                 <div class="col-md-2">
    //                     <div class="form-group">
    //                         <label for="list_kategori">Qty</label>
    //                         <input type="text" class="form-control input_detail input_monitoring qty_monitoring'.$detail.'" qty="'.$detail.'" name="qty[]" value="'.$d->qty.'"  required>                                        
    //                     </div>                            
    //                 </div>
    
    //                 <div class="col-md-2">
    //                     <div class="form-group">
    //                         <label for="list_kategori">Rp/Satuan</label>
    //                         <input type="text" class="form-control input_detail input_monitoring rp_satuan'.$detail.' rp_satuan" name="rp_beli[]" rp_satuan="'.$detail.'" value="'.$d->rp_beli.'" required>                                        
    //                     </div>                            
    //                 </div>
    
    //                 <div class="col-md-2">
    //                     <div class="form-group">
    //                         <label for="list_kategori">Total Rp</label>
    //                         <input type="text" class="form-control  input_detail input_monitoring total_rp total_rp'.$detail.'" name="ttl_rp[]" total_rp="'.$detail.'" value = "'.$d->ttl_rp.'" required>                                        
    //                     </div>                            
    //                 </div>
    
    //             </div>
    
    //         ';
    //     }
        
    //     echo '</div>';
    // }else{
    //     echo '
    //     <div id="non_monitoring" class="detail">
    //     <hr>
    //     ';

    //     foreach($debit as $d){
            
    //         echo '
    //         <div class="row">
    //         <input type="hidden" name="id_jurnal[]" value="'.$d->id_jurnal.'">

    //         <div class="col-md-4">
    //                     <div class="form-group">
    //                         <label for="list_kategori">Keterangan</label>
    //                         <input type="text" class="form-control input_detail input_non_monitoring" name="ket[]" value="'.$d->ket.'" required>                                        
    //                     </div>                            
    //         </div>                        
    
    //                 <div class="col-md-3">
    //                     <div class="form-group">
    //                         <label for="list_kategori">Total Rp</label>
    //                         <input type="text" class="form-control  input_detail input_non_monitoring total_rp" name="ttl_rp[]" value="'.$d->ttl_rp.'" required>                                        
    //                     </div>                            
    //                 </div>
    
    //             </div>
    //         ';
    //     }

    //     echo '</div>';
    // }

  }

  public function edit_jurnal_pengeluaran(){
    $tgl = $this->input->post('tgl');
    $metode = $this->input->post('metode');
    $ket = $this->input->post('ket');
    $ttl_rp = $this->input->post('ttl_rp');
    $admin = $this->session->userdata('nm_user');
    $id_akun = $this->input->post('id_akun');
    $id_post_center = $this->input->post('id_post_center');
    $id_jurnal = $this->input->post('id_jurnal');
    
    $no_urutan = $this->input->post('no_urutan');

    $urutan = (int) filter_var($no_urutan, FILTER_SANITIZE_NUMBER_INT);
    // $id_satuan = $this->input->post('id_satuan');
    // $qty = $this->input->post('qty');
    // $rp_beli = $this->input->post('rp_beli');

    // $rp_pajak = $this->input->post('rp_pajak');
    // $id_produk = $this->input->post('id_produk');

    $tgl1 = $this->input->post('tgl1');
    $tgl2 = $this->input->post('tgl2');

    $id_jurnal_kredit = $this->input->post('id_jurnal_kredit');

    //metode

    $total = 0;
    
        for($count = 0; $count<count($ttl_rp); $count++){
            $total += $ttl_rp[$count];

            $data_akun = [
                'id_akun' => $id_akun[$count],
                'id_post_center' => $id_post_center ? $id_post_center[$count] : 0,
                'kredit' => 0,
                'debit' => $ttl_rp[$count],
                'tgl' => $tgl,
                'ket'=>$ket[$count],
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
                
                'no_urutan' => $no_urutan,
                'urutan' => $urutan,
            ];
        
        
            $this->db->where('id_jurnal',$id_jurnal[$count]);  
            $this->db->update('tb_jurnal',$data_akun);
          }

    

    $data_metode = [
        'id_akun' => $metode,
        'kredit' => $total,
        'debit' => 0,
        'tgl' => $tgl,
      //   'ket'=>'BKIN',
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $admin,
        
        'no_urutan' => $no_urutan,
                'urutan' => $urutan,
    ];


    $this->db->where('id_jurnal',$id_jurnal_kredit);  
    $this->db->update('tb_jurnal',$data_metode);

    redirect("match/pengeluaran?tgl1=$tgl1&tgl2=$tgl2",'refresh');



  }

  public function edit_jurnal_penyesuaian(){
    $tgl = $this->input->post('tgl');
    $metode = $this->input->post('metode');
    $ket = $this->input->post('ket');
    $ttl_rp = $this->input->post('ttl_rp');
    $admin = $this->session->userdata('nm_user');
    $id_akun = $this->input->post('id_akun');
    $id_jurnal = $this->input->post('id_jurnal');
    // $id_satuan = $this->input->post('id_satuan');
    // $qty = $this->input->post('qty');
    // $rp_beli = $this->input->post('rp_beli');

    // $rp_pajak = $this->input->post('rp_pajak');
    // $id_produk = $this->input->post('id_produk');

    // $tgl1 = $this->input->post('tgl1');
    // $tgl2 = $this->input->post('tgl2');

    $id_jurnal_kredit = $this->input->post('id_jurnal_kredit');

    //metode

    $total = 0;
    
        for($count = 0; $count<count($ttl_rp); $count++){
            $total += $ttl_rp[$count];

            $data_akun = [
                'id_akun' => $id_akun[$count],
                'kredit' => 0,
                'debit' => $ttl_rp[$count],
                'tgl' => $tgl,
                'ket'=>$ket[$count],
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
            ];
        
        
            $this->db->where('id_jurnal',$id_jurnal[$count]);  
            $this->db->update('tb_jurnal',$data_akun);
          }

    

    $data_metode = [
        'id_akun' => $metode,
        'kredit' => $total,
        'debit' => 0,
        'tgl' => $tgl,
      //   'ket'=>'BKIN',
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $admin,
    ];


    $this->db->where('id_jurnal',$id_jurnal_kredit);  
    $this->db->update('tb_jurnal',$data_metode);

    redirect("match/jurnal_penyesuaian",'refresh');



  }

  public function edit_jurnal_pemasukan(){
    $tgl = $this->input->post('tgl');
    $metode = $this->input->post('metode');
    $ket = $this->input->post('ket');
    $ttl_rp = $this->input->post('ttl_rp');
    $admin = $this->session->userdata('nm_user');
    $id_akun = $this->input->post('id_akun');
    $id_jurnal = $this->input->post('id_jurnal');

    $month = $this->input->post('month');
    $year = $this->input->post('year');

    $id_jurnal_kredit = $this->input->post('id_jurnal_kredit');

    //metode

    $total = 0;
    
        for($count = 0; $count<count($ttl_rp); $count++){
            $total += $ttl_rp[$count];

            $data_akun = [
                'id_akun' => $id_akun[$count],
                'kredit' => 0,
                'debit' => $ttl_rp[$count],
                'tgl' => $tgl,
                'ket'=>$ket[$count],
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
            ];
        
        
            $this->db->where('id_jurnal',$id_jurnal[$count]);  
            $this->db->update('tb_jurnal',$data_akun);
          }

    

    $data_metode = [
        'id_akun' => $metode,
        'kredit' => $total,
        'debit' => 0,
        'tgl' => $tgl,
      //   'ket'=>'BKIN',
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $admin,
    ];


    $this->db->where('id_jurnal',$id_jurnal_kredit);  
    $this->db->update('tb_jurnal',$data_metode);

    redirect("match/jurnal?month=$month&year=$year",'refresh');



  }

  public function drop_penyesuaian($kd_gabungan,$month,$year){
    //jurnal  
    $this->db->where('kd_gabungan',$kd_gabungan);
    $this->db->delete('tb_jurnal');

    //peralatan
    $this->db->where('kd_gabungan',$kd_gabungan);
    $this->db->delete('tb_peralatan');

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/jurnal_penyesuaian?month=$month&year=$year"),'refresh');

  }

  public function drop_pemasukan($kd_gabungan,$month,$year){
    //jurnal  
    $this->db->where('kd_gabungan',$kd_gabungan);
    $this->db->delete('tb_jurnal');

    //peralatan
    $this->db->where('kd_gabungan',$kd_gabungan);
    $this->db->delete('tb_peralatan');

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/jurnal?month=$month&year=$year"),'refresh');

  }

  public function drop_pengeluaran($kd_gabungan){
    //jurnal  
    $this->db->where('kd_gabungan',$kd_gabungan);
    $this->db->delete('tb_jurnal');

    //peralatan
    $this->db->where('kd_gabungan',$kd_gabungan);
    $this->db->delete('tb_peralatan');

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/pengeluaran"),'refresh');

  }

public function edit_pengeluaran(){
    $data = [
        'tgl' => $this->input->post('tgl'),
        'id_akun' => $this->input->post('id_akun'),
        'debit' => $this->input->post('debit'),
        'kredit' => $this->input->post('kredit'),
        'ket' => $this->input->post('ket'),
    ];
    $id_jurnal = $this->input->post('id_jurnal');

    $this->db->where('id_jurnal',$id_jurnal);
    $this->db->update('tb_jurnal',$data);

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diinput!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/pengeluaran"),'refresh');
}

public function buku_besar(){

    if(empty($this->input->get('month'))){
        $month = date('m');
        $year = date('Y');
    }else{
        $month = $this->input->get('month');
        $year = $this->input->get('year');
    }
    
    // $buku = $this->db->select_sum('tb_jurnal.debit')->select_sum('tb_jurnal.kredit')->select('no_akun, nm_akun, tb_akun.id_akun, tb_neraca_saldo.debit_saldo, tb_neraca_saldo.kredit_saldo')->from('tb_jurnal')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->join("tb_neraca_saldo","tb_jurnal.id_akun = tb_neraca_saldo.id_akun")->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',date('Y'))->where('MONTH(tb_neraca_saldo.tgl)',$month)->where('YEAR(tb_neraca_saldo.tgl)',date('Y'))->group_by('tb_jurnal.id_akun')->get()->result();

    $buku =  $this->db->query("SELECT tb_akun.id_akun, tb_akun.no_akun, tb_akun.nm_akun, jurnal.debit, jurnal.kredit, neraca_saldo.debit_saldo, neraca_saldo.kredit_saldo,
    lanjut.debit_lanjut, lanjut.kredit_lanjut
    FROM tb_akun
         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit, SUM(tb_jurnal.kredit) as kredit FROM tb_jurnal JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun AND MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year AND tb_jurnal.id_buku != 5 GROUP BY tb_jurnal.id_akun) jurnal ON tb_akun.id_akun = jurnal.id_akun
         
         LEFT JOIN (SELECT tb_neraca_saldo.id_akun,sum(tb_neraca_saldo.debit_saldo) as debit_saldo, sum(tb_neraca_saldo.kredit_saldo) as kredit_saldo FROM tb_neraca_saldo  GROUP BY tb_neraca_saldo.id_akun) neraca_saldo ON tb_akun.id_akun = neraca_saldo.id_akun

         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit_lanjut, SUM(tb_jurnal.kredit) as kredit_lanjut FROM tb_jurnal LEFT JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun WHERE tb_akun.neraca_saldo = 'Y' AND MONTH(tb_jurnal.tgl) < $month AND YEAR(tb_jurnal.tgl) <= $year GROUP BY tb_jurnal.id_akun) lanjut ON tb_akun.id_akun = lanjut.id_akun

         WHERE tb_akun.id_akun != 55
         ORDER BY tb_akun.no_akun ASC")->result();

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();     

    // $buku =  $this->db->query("SELECT SUM(tb_jurnal.debit) as debit, SUM(tb_jurnal.kredit) as kredit, no_akun, nm_akun, tb_akun.id_akun, neraca_saldo.debit_saldo, neraca_saldo.kredit_saldo FROM tb_jurnal 
    // LEFT JOIN tb_akun on tb_jurnal.id_akun = tb_akun.id_akun 
    // LEFT JOIN (SELECT tb_neraca_saldo.id_akun,sum(tb_neraca_saldo.debit_saldo) as debit_saldo, sum(tb_neraca_saldo.kredit_saldo) as kredit_saldo FROM tb_neraca_saldo WHERE MONTH(tb_neraca_saldo.tgl) = $month AND YEAR(tb_neraca_saldo.tgl) = $year GROUP BY tb_neraca_saldo.id_akun) neraca_saldo ON tb_jurnal.id_akun = neraca_saldo.id_akun WHERE MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year AND tb_jurnal.status = 'Y' GROUP BY tb_jurnal.id_akun")->result();

    

    $data = [
        'title' => 'Rekapitulasi Jurnal',
        'buku' => $buku,
        'month' => $month,
        'year' => $year,
        'tahun' => $tahun
    ];

    $this->load->view('pembukuan/buku_besar',$data);
}

public function print_buku_besar1(){

    if(empty($this->input->get('month'))){
        $month = date('m');
        $year = date('Y');
    }else{
        $month = $this->input->get('month');
        $year = $this->input->get('year');
    }
    
    // $buku = $this->db->select_sum('tb_jurnal.debit')->select_sum('tb_jurnal.kredit')->select('no_akun, nm_akun, tb_akun.id_akun, tb_neraca_saldo.debit_saldo, tb_neraca_saldo.kredit_saldo')->from('tb_jurnal')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->join("tb_neraca_saldo","tb_jurnal.id_akun = tb_neraca_saldo.id_akun")->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',date('Y'))->where('MONTH(tb_neraca_saldo.tgl)',$month)->where('YEAR(tb_neraca_saldo.tgl)',date('Y'))->group_by('tb_jurnal.id_akun')->get()->result();

    $buku =  $this->db->query("SELECT tb_akun.id_akun, tb_akun.no_akun, tb_akun.nm_akun, jurnal.debit, jurnal.kredit, neraca_saldo.debit_saldo, neraca_saldo.kredit_saldo,
    lanjut.debit_lanjut, lanjut.kredit_lanjut
    FROM tb_akun
         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit, SUM(tb_jurnal.kredit) as kredit FROM tb_jurnal JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun AND MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year AND tb_jurnal.id_buku != 5 GROUP BY tb_jurnal.id_akun) jurnal ON tb_akun.id_akun = jurnal.id_akun
         
         LEFT JOIN (SELECT tb_neraca_saldo.id_akun,sum(tb_neraca_saldo.debit_saldo) as debit_saldo, sum(tb_neraca_saldo.kredit_saldo) as kredit_saldo FROM tb_neraca_saldo GROUP BY tb_neraca_saldo.id_akun) neraca_saldo ON tb_akun.id_akun = neraca_saldo.id_akun

         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit_lanjut, SUM(tb_jurnal.kredit) as kredit_lanjut FROM tb_jurnal LEFT JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun WHERE tb_akun.neraca_saldo = 'Y' AND MONTH(tb_jurnal.tgl) < $month AND YEAR(tb_jurnal.tgl) <= $year GROUP BY tb_jurnal.id_akun) lanjut ON tb_akun.id_akun = lanjut.id_akun

         WHERE tb_akun.id_akun != 55
         ORDER BY tb_akun.no_akun ASC")->result();

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();     

    // $buku =  $this->db->query("SELECT SUM(tb_jurnal.debit) as debit, SUM(tb_jurnal.kredit) as kredit, no_akun, nm_akun, tb_akun.id_akun, neraca_saldo.debit_saldo, neraca_saldo.kredit_saldo FROM tb_jurnal 
    // LEFT JOIN tb_akun on tb_jurnal.id_akun = tb_akun.id_akun 
    // LEFT JOIN (SELECT tb_neraca_saldo.id_akun,sum(tb_neraca_saldo.debit_saldo) as debit_saldo, sum(tb_neraca_saldo.kredit_saldo) as kredit_saldo FROM tb_neraca_saldo WHERE MONTH(tb_neraca_saldo.tgl) = $month AND YEAR(tb_neraca_saldo.tgl) = $year GROUP BY tb_neraca_saldo.id_akun) neraca_saldo ON tb_jurnal.id_akun = neraca_saldo.id_akun WHERE MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year AND tb_jurnal.status = 'Y' GROUP BY tb_jurnal.id_akun")->result();

    

    $data = [
        'title' => 'Rekapitulasi Jurnal',
        'buku' => $buku,
        'month' => $month,
        'year' => $year,
        'tahun' => $tahun
    ];

    $this->load->view('pembukuan/print_buku_besar1',$data);
}
public function export_buku_besar1(){

    if(empty($this->input->get('month'))){
        $month = date('m');
        $year = date('Y');
    }else{
        $month = $this->input->get('month');
        $year = $this->input->get('year');
    }
    
    // $buku = $this->db->select_sum('tb_jurnal.debit')->select_sum('tb_jurnal.kredit')->select('no_akun, nm_akun, tb_akun.id_akun, tb_neraca_saldo.debit_saldo, tb_neraca_saldo.kredit_saldo')->from('tb_jurnal')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->join("tb_neraca_saldo","tb_jurnal.id_akun = tb_neraca_saldo.id_akun")->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',date('Y'))->where('MONTH(tb_neraca_saldo.tgl)',$month)->where('YEAR(tb_neraca_saldo.tgl)',date('Y'))->group_by('tb_jurnal.id_akun')->get()->result();

    $buku =  $this->db->query("SELECT tb_akun.id_akun, tb_akun.no_akun, tb_akun.nm_akun, jurnal.debit, jurnal.kredit, neraca_saldo.debit_saldo, neraca_saldo.kredit_saldo,
    lanjut.debit_lanjut, lanjut.kredit_lanjut
    FROM tb_akun
         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit, SUM(tb_jurnal.kredit) as kredit FROM tb_jurnal JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun AND MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year AND tb_jurnal.id_buku != 5 GROUP BY tb_jurnal.id_akun) jurnal ON tb_akun.id_akun = jurnal.id_akun
         
         LEFT JOIN (SELECT tb_neraca_saldo.id_akun,sum(tb_neraca_saldo.debit_saldo) as debit_saldo, sum(tb_neraca_saldo.kredit_saldo) as kredit_saldo FROM tb_neraca_saldo GROUP BY tb_neraca_saldo.id_akun) neraca_saldo ON tb_akun.id_akun = neraca_saldo.id_akun

         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit_lanjut, SUM(tb_jurnal.kredit) as kredit_lanjut FROM tb_jurnal LEFT JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun WHERE tb_akun.neraca_saldo = 'Y' AND MONTH(tb_jurnal.tgl) < $month AND YEAR(tb_jurnal.tgl) <= $year GROUP BY tb_jurnal.id_akun) lanjut ON tb_akun.id_akun = lanjut.id_akun

         WHERE tb_akun.id_akun != 55
         ORDER BY tb_akun.no_akun ASC")->result();

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();     

    // $buku =  $this->db->query("SELECT SUM(tb_jurnal.debit) as debit, SUM(tb_jurnal.kredit) as kredit, no_akun, nm_akun, tb_akun.id_akun, neraca_saldo.debit_saldo, neraca_saldo.kredit_saldo FROM tb_jurnal 
    // LEFT JOIN tb_akun on tb_jurnal.id_akun = tb_akun.id_akun 
    // LEFT JOIN (SELECT tb_neraca_saldo.id_akun,sum(tb_neraca_saldo.debit_saldo) as debit_saldo, sum(tb_neraca_saldo.kredit_saldo) as kredit_saldo FROM tb_neraca_saldo WHERE MONTH(tb_neraca_saldo.tgl) = $month AND YEAR(tb_neraca_saldo.tgl) = $year GROUP BY tb_neraca_saldo.id_akun) neraca_saldo ON tb_jurnal.id_akun = neraca_saldo.id_akun WHERE MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year AND tb_jurnal.status = 'Y' GROUP BY tb_jurnal.id_akun")->result();

    

    $data = [
        'title' => 'Rekapitulasi Jurnal',
        'buku' => $buku,
        'month' => $month,
        'year' => $year,
        'tahun' => $tahun
    ];

    $this->load->view('pembukuan/export_buku_besar1',$data);
}

public function detail_buku_besar(){
    $id_akun = $this->input->get('id');
    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $buku = $this->db->query("SELECT * FROM tb_jurnal as a 
            LEFT JOIN(SELECT tb_jurnal.id_akun, GROUP_CONCAT(DISTINCT tb_jurnal.ket SEPARATOR ', ') as ket2, 
            GROUP_CONCAT(DISTINCT b.nm_akun SEPARATOR ', ') as ket3, kd_gabungan 
            FROM tb_jurnal 
            LEFT JOIN tb_akun AS b ON b.id_akun = tb_jurnal.id_akun
            WHERE debit > 0 GROUP BY kd_gabungan) jurnal2 ON a.kd_gabungan = jurnal2.kd_gabungan and jurnal2.id_akun != a.id_akun
            where a.id_akun = '$id_akun' and month(a.tgl) = '$month' and YEAR(a.tgl) = '$year'
            order by a.tgl ASC")->result();
    
    $neraca_saldo = $this->db->get_where('tb_neraca_saldo',[
        'id_akun' => $id_akun
    ])->result();

    $lanjut = $this->db->select_sum('tb_jurnal.debit','debit')->select_sum('tb_jurnal.kredit','kredit')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun','left')->group_by('tb_jurnal.id_akun')->get_where('tb_jurnal',[
        'tb_jurnal.id_akun' => $id_akun,
        'MONTH(tb_jurnal.tgl) <' => $month,
        'YEAR(tb_jurnal.tgl) <=' => $year,
        'tb_akun.neraca_saldo' => 'Y'
    ])->result();

    $data = [
        'title' => 'Buku Besar',
        'buku' => $buku,
        'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row(),
        'month' => $month,
        'year' => $year,
        'neraca_saldo' => $neraca_saldo,
        'lanjut' => $lanjut
    ];

    $this->load->view('pembukuan/detail_buku_besar',$data);
}

public function periode_buku_besar(){
    $id_akun = $this->input->get('id_akun');
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $buku = $this->db->order_by('tgl','ASC')->get_where('tb_jurnal',[
        'id_akun' => $id_akun,
        'tgl >=' => $tgl1,
        'tgl <=' => $tgl2,
        'id_buku !=' => 5
        ])->result();
    
    // $neraca_saldo = $this->db->get_where('tb_neraca_saldo',[
    //     'id_akun' => $id_akun
    // ])->result();

    // $lanjut = $this->db->select_sum('debit')->select_sum('kredit')->group_by('id_akun')->get_where('tb_jurnal',[
    //     'id_akun' => $id_akun,
    //     'MONTH(tgl) <' => $month,
    //     'YEAR(tgl) <=' => $year,
    // ])->result();

    $data = [
        'title' => 'Periode Buku Besar',
        'buku' => $buku,
        'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row(),
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
        // 'neraca_saldo' => $neraca_saldo,
        // 'lanjut' => $lanjut
    ];

    $this->load->view('pembukuan/periode_buku_besar',$data);
}

public function print_buku_besar(){
    $id_akun = $this->input->get('id');
    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $buku = $this->db->order_by('tgl','ASC')->get_where('tb_jurnal',[
        'id_akun' => $id_akun,
        'MONTH(tgl)' => $month,
        'YEAR(tgl)' => $year,
        'id_buku !=' => 5
        ])->result();
    
        $neraca_saldo = $this->db->get_where('tb_neraca_saldo',[
            'id_akun' => $id_akun
        ])->result();
    
        $lanjut = $this->db->select_sum('tb_jurnal.debit','debit')->select_sum('tb_jurnal.kredit','kredit')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun','left')->group_by('tb_jurnal.id_akun')->get_where('tb_jurnal',[
            'tb_jurnal.id_akun' => $id_akun,
            'MONTH(tb_jurnal.tgl) <' => $month,
            'YEAR(tb_jurnal.tgl) <=' => $year,
            'tb_akun.neraca_saldo' => 'Y'
        ])->result();

    $data = [
        'title' => 'Buku Besar',
        'buku' => $buku,
        'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row(),
        'month' => $month,
        'year' => $year,
        'neraca_saldo' => $neraca_saldo,
        'lanjut' => $lanjut
    ];

    $this->load->view('pembukuan/print_buku_besar',$data);
  }

  public function excel_buku_besar(){
    $id_akun = $this->input->get('id');
    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $buku = $this->db->query("SELECT * FROM tb_jurnal as a 
            LEFT JOIN(SELECT tb_jurnal.id_akun, GROUP_CONCAT(DISTINCT tb_jurnal.ket SEPARATOR ', ') as ket2, 
            GROUP_CONCAT(DISTINCT b.nm_akun SEPARATOR ', ') as ket3, kd_gabungan, c.nm_post
            FROM tb_jurnal 
            LEFT JOIN tb_akun AS b ON b.id_akun = tb_jurnal.id_akun
            LEFT JOIN tb_post_center as c on c.id_post = tb_jurnal.id_post_center
            WHERE debit > 0 GROUP BY kd_gabungan) jurnal2 ON a.kd_gabungan = jurnal2.kd_gabungan and jurnal2.id_akun != a.id_akun
            where a.id_akun = '$id_akun' and month(a.tgl) = '$month' and YEAR(a.tgl) = '$year'
            order by a.tgl ASC")->result();
    
        $neraca_saldo = $this->db->get_where('tb_neraca_saldo',[
            'id_akun' => $id_akun
        ])->result();
    
        $lanjut = $this->db->select_sum('tb_jurnal.debit','debit')->select_sum('tb_jurnal.kredit','kredit')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun','left')->group_by('tb_jurnal.id_akun')->get_where('tb_jurnal',[
            'tb_jurnal.id_akun' => $id_akun,
            'MONTH(tb_jurnal.tgl) <' => $month,
            'YEAR(tb_jurnal.tgl) <=' => $year,
            'tb_akun.neraca_saldo' => 'Y'
        ])->result();

    $data = [
        'title' => 'Buku Besar',
        'buku' => $buku,
        'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row(),
        'month' => $month,
        'year' => $year,
        'neraca_saldo' => $neraca_saldo,
        'lanjut' => $lanjut
    ];

    $this->load->view('pembukuan/excel_buku_besar',$data);
  }

  public function print_buku_besar_periode(){
    $id_akun = $this->input->get('id_akun');
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $buku = $this->db->order_by('tgl','ASC')->get_where('tb_jurnal',[
        'id_akun' => $id_akun,
        'tgl >=' => $tgl1,
        'tgl <=' => $tgl2,
        'id_buku !=' => 5
        ])->result();
    
    // $neraca_saldo = $this->db->get_where('tb_neraca_saldo',[
    //     'id_akun' => $id_akun
    // ])->result();

    // $lanjut = $this->db->select_sum('debit')->select_sum('kredit')->group_by('id_akun')->get_where('tb_jurnal',[
    //     'id_akun' => $id_akun,
    //     'MONTH(tgl) <' => $month,
    //     'YEAR(tgl) <=' => $year,
    // ])->result();

    $data = [
        'title' => 'Periode Buku Besar',
        'buku' => $buku,
        'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row(),
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
        // 'neraca_saldo' => $neraca_saldo,
        // 'lanjut' => $lanjut
    ];

    $this->load->view('pembukuan/print_buku_besar_periode',$data);
}


  public function export_excel_buku_besar(){
    $id_akun = $this->input->get('id');
    $data = [
      'buku' => $this->db->get_where('tb_jurnal',[
          'id_akun' => $id_akun,])->result(),
      'akun' => $this->db->get_where('tb_akun',['id_akun' => $id_akun])->row()

  ];

    $this->load->view('pembukuan/export_excel',$data);
  }

  //penyesuaian

  public function jurnal_penyesuaian(){
    if(empty($this->input->get('month'))){
        $month = date('m');
        $year = date('Y');
    }else{
        $month = $this->input->get('month');
        $year = $this->input->get('year');
    }

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();
    $data = [
        'title' => 'Jurnal Penyesuaian',
        'akun' => $this->db->where('penyesuaian','Y')->get('tb_akun')->result(),
        'jurnal' => $this->db->select('tb_akun.no_akun, tb_akun.nm_akun, tb_jurnal.no_nota, tb_jurnal.ket, tb_jurnal.debit, tb_jurnal.kredit, tb_jurnal.tgl, tb_jurnal.kd_gabungan')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->get_where('tb_jurnal',[
            'id_buku' => 4,
            'MONTH(tgl)' => $month,
            'YEAR(tgl)' => $year
            ])->result(),
        'barang' => $this->db->get_where('aktiva',[
            'debit_aktiva !=' => 0
        ])->result(),

        'month' => $month,
        'year' => $year,
        'tahun' => $tahun,
        'aktiva' => $this->db->query("SELECT a.nota , a.barang, a.b_penyusutan, 
        sum(a.debit_aktiva - iva.nilai) as nilai2
        From aktiva as a
        left join (SELECT va.nota, sum(va.kredit_aktiva) as nilai from aktiva as va group by va.nota) as iva on iva.nota = a.nota
        where a.kredit_aktiva = '0'
        group by a.nota
        ")->result(),

    ];

    $this->load->view('pembukuan/jurnal_penyesuaian',$data);
}

public function print_penyesuaian(){

        $month = $this->input->get('month');
        $year = $this->input->get('year');

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();
    $data = [
        'title' => 'Jurnal Penyesuaian',
        'akun' => $this->db->where('penyesuaian','Y')->get('tb_akun')->result(),
        'jurnal' => $this->db->select('tb_akun.no_akun, tb_akun.nm_akun, tb_jurnal.no_nota, tb_jurnal.ket, tb_jurnal.debit, tb_jurnal.kredit, tb_jurnal.tgl, tb_jurnal.kd_gabungan')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->get_where('tb_jurnal',[
            'id_buku' => 4,
            'MONTH(tgl)' => $month,
            'YEAR(tgl)' => $year
            ])->result(),
        'barang' => $this->db->get_where('aktiva',[
            'debit_aktiva !=' => 0
        ])->result(),

        'month' => $month,
        'year' => $year,
        'tahun' => $tahun

    ];

    $this->load->view('pembukuan/print_penyesuaian',$data);
}

public function add_penyesuaian(){
    $kd_gabungan = 'ORC'.date('dmy'). strtoupper(random_string('alpha',3));
    $tgl = $this->input->post('tgl');
    $id_akun = $this->input->post('id_akun');
    $metode = $this->input->post('metode');
    $ket = $this->input->post('ket');
    $total = $this->input->post('total');
    // $total = $this->input->post('total');
    $admin = $this->session->userdata('nm_user');

        $month = date('m' , strtotime($tgl));
        $year = date('Y' , strtotime($tgl));

        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun])->result()[0];
        $kode_akun = $this->db->where('id_akun', $id_akun)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

        $get_kd_metode = $this->db->get_where('tb_akun',['id_akun' => $metode])->result()[0];
        $kode_metode = $this->db->where('id_akun', $metode)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_metode == 0){
          $kode_metode = 1;
        }else{
            $kode_metode += 1;
        }

    // $total = 0;
    // for($count = 0; $count<count($ttl_rp); $count++){
    //   $total += $ttl_rp[$count];
    // }

    $get_akun = $this->db->get_where('tb_akun',['id_akun'=>$id_akun])->row();
    
    $data_metode = [
      'id_buku' => 4,
      'id_akun' => $metode,
      'kd_gabungan' => $kd_gabungan,
      'no_nota' => $get_kd_metode->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_metode,
      'kredit' => $total,
      'ket' => $ket,
      'tgl' => $tgl,
      'tgl_input' => date('Y-m-d H:i:s'),
      'admin' => $admin,
      'ket'=>'Penyesuaian '.$get_akun->nm_akun
  ];

  $this->db->insert('tb_jurnal',$data_metode);

  $data_jurnal = [
    'id_buku' => 4,
    'id_akun' => $id_akun,
    'kd_gabungan' => $kd_gabungan,
    'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
    'debit' => $total,
    'ket' => $ket,
    'tgl' => $tgl,
    'tgl_input' => date('Y-m-d H:i:s'),
    'admin' => $admin,
];

$this->db->insert('tb_jurnal',$data_jurnal);

$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diinput!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/jurnal_penyesuaian?month=$month&year=$year"),'refresh');

}

public function add_penyesuaian_aktiva(){
    $kd_gabungan = 'ORC'.date('dmy'). strtoupper(random_string('alpha',3));
    $tgl = $this->input->post('tgl');
    $id_akun = $this->input->post('id_akun');
    $metode = $this->input->post('metode');
    $ket = $this->input->post('ket');
    $ttl_rp = $this->input->post('total_penyesuaian');
    // $total = $this->input->post('total');
    $admin = $this->session->userdata('nm_user');

    $barang = $this->input->post('barang');

        $month = date('m' , strtotime($tgl));
        $year = date('Y' , strtotime($tgl));

        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun])->result()[0];
        $kode_akun = $this->db->where('id_akun', $id_akun)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }

        $get_kd_metode = $this->db->get_where('tb_akun',['id_akun' => $metode])->result()[0];
        $kode_metode = $this->db->where('id_akun', $metode)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_metode == 0){
          $kode_metode = 1;
        }else{
            $kode_metode += 1;
        }

    $total = 0;
    for($count = 0; $count<count($ttl_rp); $count++){
      $total += $ttl_rp[$count];
    }

    // $get_akun = $this->db->get_where('tb_akun',['id_akun'=>$id_akun])->result()[0];
    
    $data_metode = [
      'id_buku' => 4,
      'id_akun' => $metode,
      'kd_gabungan' => $kd_gabungan,
      'no_nota' => $get_kd_metode->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_metode,
      'kredit' => $total,
      'ket' => $ket,
      'tgl' => $tgl,
      'tgl_input' => date('Y-m-d H:i:s'),
      'admin' => $admin,
      'ket'=>'Penyesuaian Aktiva'
  ];

  $this->db->insert('tb_jurnal',$data_metode);

    $data_jurnal = [
        'id_buku' => 4,
        'id_akun' => $id_akun,
        'kd_gabungan' => $kd_gabungan,
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'debit' => $total,
        'kredit' => 0,
        'tgl' => $tgl,
        'tgl_input' => date('Y-m-d H:i:s'),
        'admin' => $admin,
    ];

    $this->db->insert('tb_jurnal',$data_jurnal);
  

//aktiva
for($count = 0; $count<count($ttl_rp); $count++){
    
    $kelompok = $this->db->get_where('aktiva',[
        'nota' => $barang[$count]
    ])->row();

    $data_aktiva = [
        'tgl' => $tgl,
        'id_kelompok' => $kelompok->id_kelompok,
        'debit_aktiva' => 0,
        'kredit_aktiva' => $ttl_rp[$count],
        'nota' => $barang[$count]
    ];
    
    $this->db->insert('aktiva',$data_aktiva);
  }





$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diinput!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/jurnal_penyesuaian"),'refresh');

}

//AKtiva

public function add_aktiva(){
    $kd_gabungan = 'ORC'.date('dmy'). strtoupper(random_string('alpha',3));
    $tgl = $this->input->post('tgl');
    $id_akun = $this->input->post('id_akun');
    $metode = $this->input->post('metode');
    $ket = $this->input->post('ket');
    $ttl_rp = $this->input->post('ttl_rp');
    $rp_satuan = $this->input->post('rp_satuan');
    // $total = $this->input->post('total');
    $admin = $this->session->userdata('nm_user');
    $ppn = $this->input->post('ppn');

    $id_satuan = $this->input->post('id_satuan');
    $qty = $this->input->post('qty');
    $rp_beli = $this->input->post('rp_beli');
        $month = date('m' , strtotime($tgl));
        $year = date('Y' , strtotime($tgl));

        $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_akun])->result()[0];
        $kode_akun = $this->db->where('id_akun', $id_akun)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }
        $get_kd_ppn = $this->db->get_where('tb_akun',['id_akun' => 60])->result()[0];
        $kode_ppn = $this->db->where('id_akun', 60)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_ppn == 0){
            $kode_ppn = 1;
        }else{
            $kode_ppn += 1;
        }

        $get_kd_metode = $this->db->get_where('tb_akun',['id_akun' => $metode])->result()[0];
        $kode_metode = $this->db->where('id_akun', $metode)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_metode == 0){
          $kode_metode = 1;
        }else{
            $kode_metode += 1;
        }

    $total = 0;
    for($count = 0; $count<count($ttl_rp); $count++){
      $total += $ttl_rp[$count];
    }

    // $get_akun = $this->db->get_where('tb_akun',['id_akun'=>$id_akun])->row();
    
    $data_metode = [
      'id_buku' => 3,
      'id_akun' => $metode,
      'kd_gabungan' => $kd_gabungan,
      'no_nota' => $get_kd_metode->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_metode,
      'kredit' => $total,
      'tgl' => $tgl,
      'tgl_input' => date('Y-m-d H:i:s'),
      'admin' => $admin,
      'ket'=>'Pembelian Aktiva'
  ];

  $this->db->insert('tb_jurnal',$data_metode);

  
  
  for($count = 0; $count<count($ket); $count++){
    // $total += $ttl_rp[$count];
    if (empty($ppn[$count])) {
    }else{
        $data_jurnal = [
            'id_buku' => 3,
            'id_akun' => 61,
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_ppn->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_ppn,
            'debit' => $ppn[$count],
            'tgl' => $tgl,
            'ttl_rp' => $ppn[$count],
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
        ];
        $this->db->insert('tb_jurnal',$data_jurnal);
        // $kode_akun++;
    }
    
  }


  if(!empty($id_satuan)){
    for($count = 0; $count<count($ttl_rp); $count++){
        // $total += $ttl_rp[$count];
        $data_jurnal = [
            'id_buku' => 3,
            'id_akun' => $id_akun,
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
            'debit' => $rp_satuan[$count] * $qty[$count],
            'ket' => $ket[$count],
            'tgl' => $tgl,
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
  
            'qty' => $qty[$count],
            'id_satuan' => $id_satuan[$count],
            'rp_beli' => $rp_beli[$count],
            'ttl_rp' => $ttl_rp[$count],
        ];
        $this->db->insert('tb_jurnal',$data_jurnal);
        // $kode_akun++;
      }
  }else{
    for($count = 0; $count<count($ket); $count++){
        // $total += $ttl_rp[$count];
        $data_jurnal = [
            'id_buku' => 3,
            'id_akun' => $id_akun,
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
            'debit' => $rp_satuan[$count] * $qty[$count],
            'ket' => $ket[$count],
            'tgl' => $tgl,
            'ttl_rp' => $ttl_rp[$count],
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
        ];
        $this->db->insert('tb_jurnal',$data_jurnal);
        // $kode_akun++;
      }
  }

  

  $id_kelompok = $this->input->post('id_kelompok');
  
  
  for($count = 0; $count<count($ket); $count++){
    $id = $id_kelompok[$count];
    $kelompok = $this->db->get_where('tb_kelompok_aktiva',['id_kelompok' => $id])->row();
    $susut = $kelompok->tarif; 
    
    $aktiva = [
        'id_kelompok' => $id_kelompok[$count],
        'barang' => $ket[$count],
        'debit_aktiva' => $rp_satuan[$count] * $qty[$count],
        'tgl' => $tgl,
        'nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'b_penyusutan' => (($rp_satuan[$count] * $qty[$count]) * $susut) / 12
    ];
    $this->db->insert('aktiva',$aktiva);
}
    

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diinput!<div class="ml-5 btn btn-sm"></div></div>');
    redirect(base_url("match/pengeluaran"),'refresh');
}

//penutup
public function jurnal_penutup(){

    if(empty($this->input->get('month'))){
        $month = date('m');
        $year = date('Y');
    }else{
        $month = $this->input->get('month');
        $year = $this->input->get('year');
    }

    $pendapatan = [4,56];    

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();    
    $laba =  $this->db->query("SELECT tb_akun.no_akun, tb_akun.nm_akun, laba.debit_laba, laba.kredit_laba,
    neraca_saldo.debit_neraca_saldo, neraca_saldo.kredit_neraca_saldo
    FROM tb_akun
         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit_laba, SUM(tb_jurnal.kredit) as kredit_laba FROM tb_jurnal JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun AND MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year GROUP BY tb_jurnal.id_akun) laba ON tb_akun.id_akun = laba.id_akun
         LEFT JOIN (SELECT tb_neraca_saldo.id_akun, SUM(tb_neraca_saldo.debit_saldo) as debit_neraca_saldo, SUM(tb_neraca_saldo.kredit_saldo) as kredit_neraca_saldo FROM tb_neraca_saldo WHERE MONTH(tb_neraca_saldo.tgl) = $month AND YEAR(tb_neraca_saldo.tgl) = $year GROUP BY tb_neraca_saldo.id_akun) neraca_saldo ON tb_akun.id_akun = neraca_saldo.id_akun
         WHERE tb_akun.id_akun != 55 AND tb_akun.pl = 'Y'
         ")->result();
    
    $data = [
        'title' => 'Jurnal Penutup',
        'penjualan' => $this->db->select_sum('debit')->select_sum('kredit')->select('no_akun, nm_akun, tb_akun.id_akun, tb_jurnal.status')->from('tb_jurnal')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',$year)->where_in('tb_jurnal.id_akun',$pendapatan)->group_by('tb_jurnal.id_akun')->get()->result(),

        'penutup' => $this->db->select_sum('debit')->select_sum('kredit')->select('no_akun, nm_akun, tb_akun.id_akun')->from('tb_jurnal')->join('tb_akun','tb_jurnal.id_akun = tb_akun.id_akun')->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',$year)->where_not_in('tb_jurnal.id_akun',$pendapatan)->where('tb_akun.penutup','Y')->group_by('tb_jurnal.id_akun')->get()->result(),

        'month' => $month,
        'year' => $year,
        'tahun' => $tahun,

        'laba' => $laba
    ];

    $this->load->view('pembukuan/jurnal_penutup',$data);
}

public function tutup_buku(){
    $ikhtisar_debit = $this->input->post('ikhtisar_debit');
    $ikhtisar_kredit = $this->input->post('ikhtisar_kredit');
    $total_laba = $this->input->post('total_laba');
    $penjualan = $this->input->post('penjualan');
    $id_biaya = $this->input->post('id_biaya');
    $biaya = $this->input->post('biaya');
    $month = $this->input->post('month');
    $year = date('Y');

    $tgl=$year.'-'.$month.'-30';

    $kd_gabungan = 'ORC30'.$month.date('y'). strtoupper(random_string('alpha',3));
    $admin = $this->session->userdata('nm_user');


    //penjualan
    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 4])->result()[0];
        $kode_akun = $this->db->where('id_akun', 4)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }
    $data_penjualan = [
        'id_buku' => 5,
        'id_akun' => 4,
        'kd_gabungan' => $kd_gabungan,
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'debit' => $penjualan,
        'kredit' => 0,
        'tgl' => $tgl,
        'tgl_input' => $tgl.date(' H:i:s'),
        'admin' => $admin,        
    ];

    $this->db->insert('tb_jurnal',$data_penjualan);

     //ikhtisar
     $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 55])->result()[0];
     $kode_akun = $this->db->where('id_akun', 55)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
     if($kode_akun == 0){
         $kode_akun = 1;
     }else{
         $kode_akun += 1;
     }
 $data_ikhtisar = [
     'id_buku' => 5,
     'id_akun' => 55,
     'kd_gabungan' => $kd_gabungan,
     'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
     'debit' => 0,
     'kredit' => $ikhtisar_kredit,
     'tgl' => $tgl,
     'tgl_input' => $tgl.date(' H:i:s'),
     'admin' => $admin,        
 ];

 $this->db->insert('tb_jurnal',$data_ikhtisar);


     $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 55])->result()[0];
     $kode_akun = $this->db->where('id_akun', 55)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
     if($kode_akun == 0){
         $kode_akun = 1;
     }else{
         $kode_akun += 1;
     }
 $data_ikhtisar = [
     'id_buku' => 5,
     'id_akun' => 55,
     'kd_gabungan' => $kd_gabungan,
     'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
     'debit' => $ikhtisar_debit,
     'kredit' => 0,
     'tgl' => $tgl,
     'tgl_input' => $tgl.date(' H:i:s'),
     'admin' => $admin,        
 ];

 $this->db->insert('tb_jurnal',$data_ikhtisar);


 //biaya
 for($count = 0; $count<count($id_biaya); $count++){
    // $total += $rp_pajak[$count];

    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => $id_biaya[$count]])->result()[0];
     $kode_akun = $this->db->where('id_akun', $id_biaya[$count])->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
     if($kode_akun == 0){
         $kode_akun = 1;
     }else{
         $kode_akun += 1;
     }
 $data_biaya = [
     'id_buku' => 5,
     'id_akun' => $id_biaya[$count],
     'kd_gabungan' => $kd_gabungan,
     'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
     'debit' => 0,
     'kredit' => $biaya[$count],
     'tgl' => $tgl,
     'tgl_input' => $tgl.date(' H:i:s'),
     'admin' => $admin,        
 ];

 $this->db->insert('tb_jurnal',$data_biaya);
  }

  //modal_pemilik

  $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 55])->result()[0];
     $kode_akun = $this->db->where('id_akun', 55)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
     if($kode_akun == 0){
         $kode_akun = 1;
     }else{
         $kode_akun += 1;
     }
 $data_ikhtisar = [
     'id_buku' => 5,
     'id_akun' => 55,
     'kd_gabungan' => $kd_gabungan,
     'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
     'debit' => $total_laba,
     'kredit' => 0,
     'tgl' => $tgl,
     'tgl_input' => $tgl.date(' H:i:s'),
     'admin' => $admin,        
 ];

 $this->db->insert('tb_jurnal',$data_ikhtisar);

    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 58])->result()[0];
        $kode_akun = $this->db->where('id_akun', 58)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }
    $data_ikhtisar = [
        'id_buku' => 5,
        'id_akun' => 58,
        'kd_gabungan' => $kd_gabungan,
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl)).'-'.$kode_akun,
        'debit' => 0,
        'kredit' => $total_laba,
        'tgl' => $tgl,
        'tgl_input' => $tgl.date(' H:i:s'),
        'admin' => $admin,        
    ];

    $this->db->insert('tb_jurnal',$data_ikhtisar);

  //neraca saldo
  $neraca =  $this->db->query("SELECT tb_akun.id_akun,neraca.debit_neraca, neraca.kredit_neraca,
       neraca_saldo.debit_neraca_saldo, neraca_saldo.kredit_neraca_saldo
       FROM tb_akun
            LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit_neraca, SUM(tb_jurnal.kredit) as kredit_neraca FROM tb_jurnal JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun WHERE tb_akun.neraca = 'Y' AND MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year GROUP BY tb_jurnal.id_akun) neraca ON tb_akun.id_akun = neraca.id_akun
            LEFT JOIN (SELECT tb_neraca_saldo.id_akun, SUM(tb_neraca_saldo.debit_saldo) as debit_neraca_saldo, SUM(tb_neraca_saldo.kredit_saldo) as kredit_neraca_saldo FROM tb_neraca_saldo WHERE MONTH(tb_neraca_saldo.tgl) = $month AND YEAR(tb_neraca_saldo.tgl) = $year GROUP BY tb_neraca_saldo.id_akun) neraca_saldo ON tb_akun.id_akun = neraca_saldo.id_akun
            WHERE tb_akun.neraca_saldo = 'Y'
            ")->result();
    
    foreach($neraca as $n){
        $neraca = $n->debit_neraca_saldo + $n->debit_neraca - $n->kredit_neraca - $n->kredit_neraca_saldo;
        
        if($month != '12'){
                $bulan = $month +1;
            if($neraca > 0){
                $debit = $neraca;
                $kredit = 0;
            }else{
                $debit = 0;
                $kredit = $neraca * -1;
            }
            $data_saldo = [
                'id_akun' => $n->id_akun,
                'tgl' => $year.'-'.$bulan.'-01',
                'debit_saldo' => $debit,
                'kredit_saldo' => $kredit
            ];
            $this->db->insert('tb_neraca_saldo',$data_saldo);
        }else{
            $tahun = $year +1;
            if($neraca > 0){
                $debit = $neraca;
                $kredit = 0;
            }else{
                $debit = 0;
                $kredit = $neraca * -1;
            }
            $data_saldo = [
                'id_akun' => $n->id_akun,
                'tgl' => $tahun.'-01-01',
                'debit_saldo' => $debit,
                'kredit_saldo' => $kredit
            ];
            $this->db->insert('tb_neraca_saldo',$data_saldo);
        }

        //tutup buku
        $data_tutup = [
            'status' => 'T'
        ];
        $this->db->where('MONTH(tgl)',$month);
        $this->db->where('YEAR(tgl)', $year);
        $this->db->update('tb_jurnal',$data_tutup);
        

    }

  $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan! </div>');
      redirect('Match/jurnal_penutup');
}

public function laporan_laba_rugi(){

    if(empty($this->input->get('month'))){
        $month = date('m');
        $year = date('Y');
    }else{
        $month = $this->input->get('month');
        $year = $this->input->get('year');
    }

    

    $laba =  $this->db->query("SELECT tb_akun.id_akun, tb_akun.no_akun, tb_akun.nm_akun, laba.debit_laba, laba.kredit_laba
    FROM tb_akun
         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit_laba, SUM(tb_jurnal.kredit) as kredit_laba FROM tb_jurnal JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun AND MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year GROUP BY tb_jurnal.id_akun) laba ON tb_akun.id_akun = laba.id_akun
         WHERE tb_akun.id_akun != 55 AND tb_akun.pl = 'Y'
         ORDER BY tb_akun.no_akun ASC")->result();
    
    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();
    
    $data = [
        'title' => 'Laporan Laba/Rugi',
        'laba' => $laba,
        'month' => $month,
        'year' => $year,
        'tahun' => $tahun
    ];

    $this->load->view('pembukuan/laporan_laba_rugi',$data);
}

public function print_laba_rugi(){

    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $laba =  $this->db->query("SELECT tb_akun.id_akun, tb_akun.no_akun, tb_akun.nm_akun, laba.debit_laba, laba.kredit_laba
    FROM tb_akun
         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit_laba, SUM(tb_jurnal.kredit) as kredit_laba FROM tb_jurnal JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun AND MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year GROUP BY tb_jurnal.id_akun) laba ON tb_akun.id_akun = laba.id_akun
         WHERE tb_akun.id_akun != 55 AND tb_akun.pl = 'Y'
         ORDER BY tb_akun.no_akun ASC")->result();
    
    $data = [
        'title' => 'Laporan Laba/Rugi',
        'laba' => $laba,
        'month' => $month,
        'year' => $year
    ];

    $this->load->view('pembukuan/print_laba_rugi',$data);
}

public function excel_laba_rugi(){

    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $laba =  $this->db->query("SELECT tb_akun.id_akun, tb_akun.no_akun, tb_akun.nm_akun, laba.debit_laba, laba.kredit_laba
    FROM tb_akun
         LEFT JOIN (SELECT tb_jurnal.id_akun, SUM(tb_jurnal.debit) as debit_laba, SUM(tb_jurnal.kredit) as kredit_laba FROM tb_jurnal JOIN tb_akun ON tb_jurnal.id_akun = tb_akun.id_akun AND MONTH(tb_jurnal.tgl) = $month AND YEAR(tb_jurnal.tgl) = $year GROUP BY tb_jurnal.id_akun) laba ON tb_akun.id_akun = laba.id_akun
         WHERE tb_akun.id_akun != 55 AND tb_akun.pl = 'Y'
         ORDER BY tb_akun.no_akun ASC")->result();
    
    $data = [
        'title' => 'Laporan Laba/Rugi',
        'laba' => $laba,
        'month' => $month,
        'year' => $year
    ];

    $this->load->view('pembukuan/excel_laba_rugi',$data);
}

public function laporan_bulanan(){
    $periode = $this->db->select('tgl')->from('tb_jurnal')->group_by('MONTH(tgl)')->group_by('YEAR(tgl)')->order_by('tgl','ASC')->get()->result();

    $akun_pendapatan = $this->db->get_where('tb_akun',[
        'pendapatan' => 'Y'
    ])->result();

    // $kategori = [4,7];

    $akun_pengeluaran = $this->db->get_where('tb_akun',[
        'pengeluaran' => 'Y',
        'biaya_fix' => 'T',
        'pph_hutang' => 'T',
        'id_kategori !=' => 7,
    ])->result();

    $akun_biaya_fix = $this->db->get_where('tb_akun',[
        'pengeluaran' => 'Y',
        'biaya_fix' => 'Y',
        'pph_hutang' => 'T',
        'id_kategori !=' => 7
    ])->result();

    $akun_aktiva = $this->db->get_where('tb_akun',[
        'pengeluaran' => 'Y',
        'id_kategori =' => 7
    ])->result();

    $akun_gantung = $this->db->join('tb_akun','tb_post_center.id_akun = tb_akun.id_akun','left')->get_where('tb_post_center',[
        'tb_akun.akun_gantung' => 'Y'
    ])->result();
    

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();

    $data = [
        'title' => 'Laporan Bulanan',
       'tahun' => $tahun,
       'periode' => $periode,
       'akun_pendapatan' => $akun_pendapatan,
       'akun_pengeluaran' => $akun_pengeluaran,
       'akun_aktiva' => $akun_aktiva,
       'akun_biaya_fix' => $akun_biaya_fix,
       'akun_gantung' => $akun_gantung,
       'akun_all' => $this->db->get('tb_akun')->result()   
    ];
    

    $this->load->view('pembukuan/laporan_bulanan',$data);
}

public function excel_laporan_bulanan(){
    $periode = $this->db->select('tgl')->from('tb_jurnal')->group_by('MONTH(tgl)')->group_by('YEAR(tgl)')->order_by('tgl','ASC')->get()->result();

    $akun_pendapatan = $this->db->get_where('tb_akun',[
        'pendapatan' => 'Y'
    ])->result();

    // $kategori = [4,7];

    $akun_pengeluaran = $this->db->get_where('tb_akun',[
        'pengeluaran' => 'Y',
        'biaya_fix' => 'T',
        'pph_hutang' => 'T',
        'id_kategori !=' => 7,
    ])->result();

    $akun_biaya_fix = $this->db->get_where('tb_akun',[
        'pengeluaran' => 'Y',
        'biaya_fix' => 'Y',
        'pph_hutang' => 'T',
        'id_kategori !=' => 7
    ])->result();

    $akun_aktiva = $this->db->get_where('tb_akun',[
        'pengeluaran' => 'Y',
        'id_kategori =' => 7
    ])->result();
    
    $akun_gantung = $this->db->get_where('tb_post_center',[
        'id_akun' => 70
    ])->result();

    $tahun = $this->db->select('tgl')->from('tb_jurnal')->group_by('YEAR(tgl)')->get()->result();

    $data = [
       'tahun' => $tahun,
       'periode' => $periode,
       'akun_pendapatan' => $akun_pendapatan,
       'akun_pengeluaran' => $akun_pengeluaran,
       'akun_aktiva' => $akun_aktiva,
       'akun_biaya_fix' => $akun_biaya_fix,
       'akun_gantung' => $akun_gantung
    ];
    

    $this->load->view('pembukuan/excel_laporan_bulanan',$data);
}

public function add_laporan_bulanan(){
    $year = $this->input->post('year');
    $month = $this->input->post('month');

    $last_date=cal_days_in_month(CAL_GREGORIAN,$month,$year);

    $kategori = [3,4,7];
    $buku = [1,3];

    

    $data_laporan = $this->db->select_sum('tb_jurnal.debit','debit')->select_sum('tb_jurnal.kredit','kredit')->select('tb_akun.id_akun')->join('tb_jurnal','tb_akun.id_akun = tb_jurnal.id_akun')->where_in('tb_akun.id_kategori',$kategori)->where_in('tb_jurnal.id_buku',$buku)->where('MONTH(tb_jurnal.tgl)',$month)->where('YEAR(tb_jurnal.tgl)',$year)->group_by('tb_akun.id_akun')->get('tb_akun')->result();

    foreach($data_laporan as $dl){
        if($dl->debit != null){
            $debit = $dl->debit;
        }else{
            $debit = 0;
        }

        if($dl->kredit != null){
            $kredit = $dl->kredit;
        }else{
            $kredit = 0;
        }
        $laporan = [
            'id_akun' => $dl->id_akun,
            'debit' => $debit,
            'kredit' => $kredit,
            'tgl' => $year.'-'.$month.'-'.$last_date
        ];
        $this->db->insert('tb_laporan',$laporan);
    }

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan! </div>');
    redirect('Match/laporan_bulanan');
}

//=============================================END JURNAL==============================
// ================================== APP ==========================================

public function app()
{
    // $d_order = $this->db->join('tb_order','tb_order.id_terapis = tb_terapis.id_terapis')->where('tb_order.tanggal', date('Y-m-d'))->get('')->result_array();
    // $d_order = $this->db->get_where('tb_terapis', ['tanggal' => date('Y-m-d')])->result_array();
    // $d_order = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', ['tb_order.tanggal' => date('Y-m-d')])->result_array();

    $d_order = $this->db->select('*')->from('tb_terapis')->join('tb_order', 'tb_terapis.id_terapis = tb_order.id_terapis','left')->where('tb_terapis.tanggal' , date('Y-m-d'))->group_by('tb_terapis.id_terapis')->select_sum('tb_order.total')->get()->result_array();
    $d_order_d = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', ['tb_order.tanggal' => date('Y-m-d')])->result_array();

    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => date('Y-m-d')))->result();

    $customer = $this->db->get('tb_customer')->result();

    $now = date('Y-m-d');
    $data = array(
        'title'  => "Appointment | Crepese Signature", 
        'anak'   => $this->db->get('tb_karyawan')->result(),
        'terapis'   => $this->db->get_where('tb_terapis', ['tanggal' => $now])->result(),
        'servis'   => $this->db->get('tb_servis')->result(),
        'd_order' => $d_order,
        'd_order_d' => $d_order_d,
        'd_order_all' => $d_order_all,
        'customer'  => $customer,
        'tgl' => $now
    );
    $this->load->view('app/tabel', $data);
}

public function daftar_app(){
    if (empty($this->input->post('tgl1'))) {
        $bulan = date('m');
        $year = date('Y');        
        $data = array(
            'title'  => "Crepese Signature | Daftar Appointment",
            'appointment' => $this->M_salon->daftar_app(" where MONTH(tb_app.tgl) = '$bulan' AND YEAR(tb_app.tgl) = '$year'") 
            
        );
    }else{
        $dt_a   = $this->input->post('tgl1');
        $dt_b   = $this->input->post('tgl2');
        $data = array(
            'title'  => "Crepese Signature | Daftar Appointment", 
            'appointment' => $this->M_salon->daftar_app(" where tb_app.tgl BETWEEN '$dt_a' AND '$dt_b' ")
        );
    }
    $this->load->view('app/daftar_app', $data);
}

public function check_app(){
    $id_app = $this->input->get('app');
    $app = $this->db->join('tb_servis','tb_app.id_servis = tb_servis.id_servis')->join('tb_customer','tb_app.id_customer = tb_customer.id_customer')->get_where('tb_app',['id_app' => $id_app])->result()[0];
    $terapis = $this->db->join('tb_karyawan','tb_komisi_app.id_kry = tb_karyawan.id_kry')->get_where('tb_komisi_app', ['id_app' => $id_app])->result();        
    $data = array(
        'title'  => "Crepese Signature | Cek Appointment",
        'app' => $app,
        'terapis' => $terapis        
    );
    $this->load->view('app/cek_app', $data);
}

public function check_penjualan(){
    $id_penjualan = $this->input->get('id');
    $penjualan = $this->db->select('tb_pembelian.tanggal as tanggal, tb_produk.nm_produk as nm_produk, tb_pembelian.jumlah as jumlah, tb_pembelian.harga as harga, tb_pembelian.total as total')->join('tb_produk','tb_pembelian.id_produk = tb_produk.id_produk')->get_where('tb_pembelian',['id_pembelian' => $id_penjualan])->result()[0];
    $karyawan = $this->db->join('tb_karyawan','komisi.id_kry = tb_karyawan.id_kry')->get_where('komisi', ['id_pembelian' => $id_penjualan])->result();        
    $data = array(
        'title'  => "Crepese Signature | Cek Appointment",
        'penjualan' => $penjualan,
        'karyawan' => $karyawan        
    );
    $this->load->view('order/cek_penjualan', $data);
}


function app_add_terapis()
{
    $id_kry = $this->input->post('terapis');
    $now = $this->input->post('tgl');
    $tzoffset =  $this->input->post('tzoffset');
    foreach($id_kry as $k){

        $cek = $this->db->get_where('tb_terapis', ['nama_t' => $k, 'tanggal' => $now])->num_rows();
    if ($cek==1) 
    {
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis gagal ditambahkan karena sudah terdaftar pada sistem!!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        redirect("Match/app");
    }
    else
    {
        $data = array(
            'nama_t'    => $k,
            'tanggal'   => $now,
            'tzoffset'  => $tzoffset
        );
        $this->db->insert('tb_terapis', $data);
    }

    }
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis berhasil ditambahkan!!  <div class="ml-5 btn btn-sm"><i class="fas fa-check-circle fa-2x"></i></div></div>');
        redirect("Match/app");
    
}

function app_add_terapis2()
{
    $id_kry = $this->input->post('terapis');
    $now = $this->input->post('tgl');
    $tzoffset =  $this->input->post('tzoffset');
    foreach($id_kry as $k){

        $cek = $this->db->get_where('tb_terapis', ['nama_t' => $k, 'tanggal' => $now])->num_rows();
    if ($cek==1) 
    {
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis gagal ditambahkan karena sudah terdaftar pada sistem!!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        redirect("Match/app_priode?tgl=$now");
    }
    else
    {
        $data = array(
            'nama_t'    => $k,
            'tanggal'   => $now,
            'tzoffset'  => $tzoffset
        );
        $this->db->insert('tb_terapis', $data);
    }

    }
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis berhasil ditambahkan!!  <div class="ml-5 btn btn-sm"><i class="fas fa-check-circle fa-2x"></i></div></div>');
        redirect("Match/app_priode?tgl=$now");
}

function hapus_terapis(){
    $id_terapis = $this->input->post('id_terapis');

    foreach($id_terapis as $id){
        $cek = $this->db->get_where('tb_order' , ['id_terapis' => $id])->num_rows();

        if($cek > 0)
    {
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Ada terapis yang masih melakukan service<div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        redirect("Match/app");
    }else{
        $this->db->where('id_terapis', $id);
        $this->db->delete('tb_terapis');
        
    }
    }

    
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Terapis berhasil di hapus<div class="ml-5 btn btn-sm"></div></div>');
        redirect("Match/app");
}

function hapus_terapis2(){
    $id_terapis = $this->input->post('id_terapis');
    $tgl = $this->input->post('tgl');

    foreach($id_terapis as $id){
        $cek = $this->db->get_where('tb_order' , ['id_terapis' => $id])->num_rows();

        if($cek > 0)
    {
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Ada terapis yang masih melakukan service<div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        redirect("Match/app_priode?tgl=$tgl");
    }else{
        $this->db->where('id_terapis', $id);
        $this->db->delete('tb_terapis');        
    }
    }

    
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Terapis berhasil di hapus<div class="ml-5 btn btn-sm"></div></div>');
        redirect("Match/app_priode?tgl=$tgl");
}


function add_terapis_kelola()
{
    $id_kry = $this->input->post('terapis');
    $now = $this->input->post('tgl');
    $tzoffset =  $this->input->post('tzoffset');
    foreach($id_kry as $k){

        $cek = $this->db->get_where('tb_terapis', ['nama_t' => $k, 'tanggal' => $now])->num_rows();
    if ($cek==1) 
    {
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis gagal ditambahkan karena sudah terdaftar pada sistem!!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        redirect("Match/kelola_app?tgl=$now");
    }
    else
    {
        $data = array(
            'nama_t'    => $k,
            'tanggal'   => $now,
            'tzoffset'  => $tzoffset
        );
        $this->db->insert('tb_terapis', $data);
    }

    }
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis berhasil ditambahkan!!  <div class="ml-5 btn btn-sm"><i class="fas fa-check-circle fa-2x"></i></div></div>');
        redirect("Match/kelola_app?tgl=$now");
}

function add_terapis_kelola2()
{
    $id_kry = $this->input->post('terapis');
    $now = $this->input->post('tgl');
    $tzoffset =  $this->input->post('tzoffset');
    foreach($id_kry as $k){

        $cek = $this->db->get_where('tb_terapis', ['nama_t' => $k, 'tanggal' => $now])->num_rows();
    if ($cek==1) 
    {
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis gagal ditambahkan karena sudah terdaftar pada sistem!!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        redirect("Match/kelola_app2?tgl=$now");
    }
    else
    {
        $data = array(
            'nama_t'    => $k,
            'tanggal'   => $now,
            'tzoffset'  => $tzoffset
        );
        $this->db->insert('tb_terapis', $data);
    }

    }
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis berhasil ditambahkan!!  <div class="ml-5 btn btn-sm"><i class="fas fa-check-circle fa-2x"></i></div></div>');
        redirect("Match/kelola_app2?tgl=$now");
}

function generate_terapis2()
{
    $now = $this->input->post('tgl');
    for($i=1; $i<=10; $i++){        
        if($i == 10){
            $nm_karyawan = "Z$i";
        }else{
            $nm_karyawan = "Z0$i";
        } 
    $cek = $this->db->get_where('tb_terapis', ['nama_t' => $nm_karyawan, 'tanggal' => $now])->num_rows();
    if ($cek==1) 
    {
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis gagal ditambahkan karena sudah terdaftar pada sistem!!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        redirect("Match/app");
    }
    else
    {
        $data = array(
            'nama_t'    => $nm_karyawan,
            'tanggal'   => $now,
            'tzoffset'  => $this->input->post('tzoffset')
        );
        $this->db->insert('tb_terapis', $data);
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis berhasil ditambahkan!!  <div class="ml-5 btn btn-sm"><i class="fas fa-check-circle fa-2x"></i></div></div>');
    }
    }
    redirect("Match/app");
    
}

function generate_terapis()
{
    $now = $this->input->post('tgl');
    for($i=1; $i<=10; $i++){
        if($i == 10){
            $nm_karyawan = "Z$i";
        }else{
            $nm_karyawan = "Z0$i";
        }        
    $cek = $this->db->get_where('tb_terapis', ['nama_t' => $nm_karyawan, 'tanggal' => $now])->num_rows();
    if ($cek==1) 
    {
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis gagal ditambahkan karena sudah terdaftar pada sistem!!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
        redirect("Match/app_priode?tgl=$now");
    }
    else
    {
        $data = array(
            'nama_t'    => $nm_karyawan,
            'tanggal'   => $now,
            'tzoffset'  => $this->input->post('tzoffset')
        );
        $this->db->insert('tb_terapis', $data);
        $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Terapis berhasil ditambahkan!!  <div class="ml-5 btn btn-sm"><i class="fas fa-check-circle fa-2x"></i></div></div>');
    }
    }
    redirect("Match/app_priode?tgl=$now");
    
}






function app_add_order()
{
    if (!empty($this->input->post('id_customer'))) 
    {
        $id_customer = $this->input->post('id_customer');
    }
    else
    {
        $customer = $this->input->post('customer');
        $data = array(
            'nama'  => $customer
        );
        $this->db->insert('tb_customer', $data);
        $id_customer = $this->db->insert_id();
    }
    $id_terapis= $this->input->post('id_terapis');
    $id_servis= $this->input->post('id_servis');
    $detail_s = $this->db->get_where('tb_servis', array('id_servis' => $id_servis))->row();
    $detail_t = $this->db->get_where('tb_terapis', array('id_terapis' => $id_terapis))->row();
    $start= $this->input->post('jam_mulai');
    $start2 = date('H:i:s', strtotime($start));
    $end = date('H:i:s',strtotime('+'.$detail_s->durasi.' Hours +'.$detail_s->menit.' minutes',strtotime($start2)));
    $now = date('Y-m-d');
    $cek = $this->db->get_where("tb_order where location='$id_terapis' AND tanggal='$now' AND '$end' > start AND '$start2' < end")->num_rows();
    if ($cek > 0) 
    {
     $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Appointment gagal disimpan, waktu yang Anda inputkan bertabrakan! </div>');
     redirect("match/app");
 }
 else
 {
     $ttl_jam = $detail_t->ttl_jam + $detail_s->durasi;

     $start_t = date('D M d Y ').$start2.' GMT+0800 (Central Indonesia Time)';
     $end_t = date('D M d Y ').$end.' GMT+0800 (Central Indonesia Time)';

        // Sat Dec 26 2020 10:00:00 GMT+0800 (Central Indonesia Time)

     $data_t = array(
        'ttl_jam'  => $ttl_jam
    );

     $where = array( 'id_terapis' => $id_terapis);
     $res = $this->M_salon->UpdateData('tb_terapis', $data_t, $where);

     $data = array(
        'id_terapis'    => $id_terapis,
        'id_servis'     => $id_servis,
        'id_customer'     => $id_customer,
        'location'      => $id_terapis,
        'tanggal'       => date('Y-m-d'),
        'start'         => $start,
        'start_t'         => $start_t,
        'end'           => $end,
        'end_t'           => $end_t
    );

     $this->db->insert('tb_order', $data);

     $this->session->set_flashdata('message', '<div class="alert alert-primary" role="alert">Data Appointment berhasil disimpan! </div>');
     redirect("match/app");
 }    
}

function app_add_order_2()
{
    if (!empty($this->input->post('id_customer'))) 
    {
        $id_customer = $this->input->post('id_customer');
    }
    else
    {
        $customer = $this->input->post('customer');
        $data = array(
            'nama'  => $customer
        );
        $this->db->insert('tb_customer', $data);
        $id_customer = $this->db->insert_id();
    }
    $id_terapis= $this->input->post('id_terapis');
    $id_servis= $this->input->post('id_servis');
    $detail_s = $this->db->get_where('tb_servis', array('id_servis' => $id_servis))->row();
    $detail_t = $this->db->get_where('tb_terapis', array('id_terapis' => $id_terapis))->row();
    $start= $this->input->post('jam_mulai');
    $start2 = date('H:i:s', strtotime($start));
    $end = date('H:i:s',strtotime('+'.$detail_s->durasi.' Hours +'.$detail_s->menit.' minutes',strtotime($start2)));
    $now = $this->input->post('tgl');
    $cek = $this->db->get_where("tb_order where location='$id_terapis' AND tanggal='$now' AND '$end' > start AND '$start2' < end")->num_rows();
    if ($cek > 0) 
    {
     $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Appointment gagal disimpan, waktu yang Anda inputkan bertabrakan! </div>');
     redirect("Match/app_priode?tgl=$now");
 }
 else
 {
     $ttl_jam = $detail_t->ttl_jam + $detail_s->durasi;

     $tgl = date('D M d Y ', strtotime($now));

     $start_t = $tgl.$start2.' GMT+0800 (Central Indonesia Time)';
     $end_t = $tgl.$end.' GMT+0800 (Central Indonesia Time)';

        // Sat Dec 26 2020 10:00:00 GMT+0800 (Central Indonesia Time)

     $data_t = array(
        'ttl_jam'  => $ttl_jam
    );

     $where = array( 'id_terapis' => $id_terapis);
     $res = $this->M_salon->UpdateData('tb_terapis', $data_t, $where);

     $data = array(
        'id_terapis'    => $id_terapis,
        'id_servis'     => $id_servis,
        'id_customer'     => $id_customer,
        'location'      => $id_terapis,
        'tanggal'       => $now,
        'start'         => $start,
        'start_t'         => $start_t,
        'end'           => $end,
        'end_t'           => $end_t
    );

     $this->db->insert('tb_order', $data);

     $this->session->set_flashdata('message', '<div class="alert alert-primary" role="alert">Data Appointment berhasil disimpan! </div>');
     //app_priode?tgl=2021-02-20
     redirect("Match/app_priode?tgl=$now");
 }    
}

function app_add_order_multiple(){
    if (!empty($this->input->post('id_customer'))) 
    {
        $id_customer = $this->input->post('id_customer');
    }
    else
    {
        $customer = $this->input->post('customer');
        $data = array(
            'nama'  => $customer
        );
        $this->db->insert('tb_customer', $data);
        $id_customer = $this->db->insert_id();
    }

    
    $id_terapis= $this->input->post('id_terapis');
    $id_servis= $this->input->post('id_servis');    
    $start= $this->input->post('jam_mulai');
    $now = $this->input->post('tgl');
    
    foreach($id_terapis as $id_terapis){
        
        $detail_s = $this->db->get_where('tb_servis', array('id_servis' => $id_servis))->row();
    $detail_t = $this->db->get_where('tb_terapis', array('id_terapis' => $id_terapis))->row();
    $start2 = date('H:i:s', strtotime($start));
    $end = date('H:i:s',strtotime('+'.$detail_s->durasi.' Hours +'.$detail_s->menit.' minutes',strtotime($start2)));

    $cek = $this->db->get_where("tb_order where location='$id_terapis' AND tanggal='$now' AND '$end' > start AND '$start2' < end")->num_rows();
    if($cek > 0){
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Appointment gagal disimpan, waktu yang Anda inputkan bertabrakan! </div>');
     redirect("Match/app");
    }else{
        $ttl_jam = $detail_t->ttl_jam + $detail_s->durasi;
        $tgl = date('D M d Y ', strtotime($now));

     $start_t = $tgl.$start2.' GMT+0800 (Central Indonesia Time)';
     $end_t = $tgl.$end.' GMT+0800 (Central Indonesia Time)';

     $data_t = array(
        'ttl_jam'  => $ttl_jam
    );

     $where = array( 'id_terapis' => $id_terapis);
     $res = $this->M_salon->UpdateData('tb_terapis', $data_t, $where);

            $data = array(
                'id_terapis'    => $id_terapis,
                'id_servis'     => $id_servis,
                'id_customer'     => $id_customer,
                'location'      => $id_terapis,
                'tanggal'       => $now,
                'start'         => $start,
                'start_t'         => $start_t,
                'end'           => $end,
                'end_t'           => $end_t
            );
        
            $this->db->insert('tb_order', $data);
    }
    }
    $this->session->set_flashdata('message', '<div class="alert alert-primary" role="alert">Data Appointment berhasil disimpan! </div>');
         //app_priode?tgl=2021-02-20
         redirect("Match/app");
}

function app_add_order_multiple2(){

    $id_terapis = $this->input->post('terapis');
    $id_servis = $this->input->post('servis');
    $start = $this->input->post('jam_mulai');
    $id_customer = $this->input->post('id_customer');
    $customer = $this->input->post('customer');
    $now = $this->input->post('tgl');
    // $data = [
    //     'id_customer' => $id_customer,
    //     'customer' => $customer,
    //     'tgl' => $tgl,
    //     'terapis' => $terapis,
    //     'servis' => $servis,
    //     'jam_mulai' => $jam_mulai        
    // ];

    // return var_dump($data);
    if (!empty($this->input->post('id_customer'))) 
    {
        $id_customer = $this->input->post('id_customer');
    }
    else
    {
        $customer = $this->input->post('customer');
        $data = array(
            'nama'  => $customer
        );
        $this->db->insert('tb_customer', $data);
        $id_customer = $this->db->insert_id();
    }

    
    // $id_terapis= $this->input->post('id_terapis');
    // $id_servis= $this->input->post('id_servis');    
    // $start= $this->input->post('jam_mulai');
    // $now = $this->input->post('tgl');
    $gagal = 0;
    for($count = 0; $count<count($id_servis); $count++){
        
        $detail_s = $this->db->get_where('tb_servis', array('id_servis' => $id_servis[$count]))->row();
    $detail_t = $this->db->get_where('tb_terapis', array('id_terapis' => $id_terapis[$count]))->row();
    $start2 = date('H:i:s', strtotime($start[$count]));
    $end = date('H:i:s',strtotime('+'.$detail_s->durasi.' Hours +'.$detail_s->menit.' minutes',strtotime($start2)));

    $cek = $this->db->get_where("tb_order where location='$id_terapis[$count]' AND tanggal='$now' AND '$end' > start AND '$start2' < end")->num_rows();
    if($cek > 0 ){
        $gagal++;
    }
    }

    
    if($gagal > 0){
    //     $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Appointment gagal disimpan, waktu yang Anda inputkan bertabrakan! </div>');
    //  redirect("Match/app_priode?tgl=$now");
    echo "gagal";
    }else{
        for($count = 0; $count<count($id_servis); $count++){
            $detail_s = $this->db->get_where('tb_servis', array('id_servis' => $id_servis[$count]))->row();
            $detail_t = $this->db->get_where('tb_terapis', array('id_terapis' => $id_terapis[$count]))->row();
            $start2 = date('H:i:s', strtotime($start[$count]));
            $end = date('H:i:s',strtotime('+'.$detail_s->durasi.' Hours +'.$detail_s->menit.' minutes',strtotime($start2)));     

        $ttl_jam = $detail_t->ttl_jam + $detail_s->durasi;
        $tgl = date('D M d Y ', strtotime($now));

     $start_t = $tgl.$start2.' GMT+0800 (Central Indonesia Time)';
     $end_t = $tgl.$end.' GMT+0800 (Central Indonesia Time)';

     $data_t = array(
        'ttl_jam'  => $ttl_jam
    );

     $where = array( 'id_terapis' => $id_terapis[$count]);
     $res = $this->M_salon->UpdateData('tb_terapis', $data_t, $where);

            $data = array(
                'id_terapis'    => $id_terapis[$count],
                'id_servis'     => $id_servis[$count],
                'id_customer'     => $id_customer,
                'location'      => $id_terapis[$count],
                'tanggal'       => $now,
                'start'         => $start[$count],
                'start_t'         => $start_t,
                'end'           => $end,
                'end_t'           => $end_t
            );
        
            $this->db->insert('tb_order', $data);
            echo "berhasil";
    }
    }
    // $this->session->set_flashdata('message', '<div class="alert alert-primary" role="alert">Data Appointment berhasil disimpan! </div>');
    //      //app_priode?tgl=2021-02-20
    //      redirect("Match/app_priode?tgl=$now");
    
}

function app_order(){
    $tgl = $this->input->get('tgl');
    $id_customer = $this->input->get('customer');
    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl,
    'tb_order.id_customer' => $id_customer))->result();

    $data = array(
        'title'  => "Appointment | Crepese Signature", 
        'tgl' => $tgl,
        'id_customer' => $id_customer,
        'd_order_all' => $d_order_all,
        'terapis'   => $this->db->get_where('tb_terapis', ['tanggal' => $tgl])->result(),
        'customer' => $this->db->get_where('tb_customer', ['id_customer' => $id_customer])->result_array()[0],
        'servis'   => $this->db->get('tb_servis')->result()
    );
    $this->load->view('app/kelola_data', $data);


}

function detail_order(){
    $tgl = $this->input->get('tgl');
    $id_customer = $this->input->get('customer');
    $service = $this->db->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl,
    'tb_order.id_customer' => $id_customer, 'tb_order.bayar' => 'T'))->result();
    $total_s = $this->db->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->group_by('tb_order.id_servis')->get_where('tb_order', array('tb_order.tanggal' => $tgl,
    'tb_order.id_customer' => $id_customer, 'tb_order.bayar' => 'T'))->result();
    $cek_servis = $this->db->get_where('tb_order',[
        'tanggal' => $tgl,
        'id_customer' => $id_customer,
        'status' => 'Berjalan',
        'bayar' => 'T'
    ])->num_rows();

    $terapis = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl,
    'tb_order.id_customer' => $id_customer))->result();

    $total_servis = 0;
    foreach($total_s as $t){
        $total_servis += $t->biaya;
    }

    $data = array(
        'title'  => "Appointment | Crepese Signature", 
        'tgl' => $tgl,
        'id_customer' => $id_customer,
        'service' => $service,
        'terapis'   => $terapis,
        'customer' => $this->db->get_where('tb_customer', ['id_customer' => $id_customer])->result_array()[0],
        'servis'   => $this->db->get('tb_servis')->result(),
        'cek_servis' => $cek_servis,
        'total_servis' => $total_servis
    );
    $this->load->view('app/detail_order', $data);
}

function cart_order (){
    $tgl = $this->input->post('tgl');
    $id_customer = $this->input->post('id_customer');
    $service = $this->db->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->group_by('tb_order.id_servis')->get_where('tb_order', array('tb_order.tanggal' => $tgl,
    'tb_order.id_customer' => $id_customer, 'tb_order.bayar' => 'T'))->result();

    $terapis = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl,
    'tb_order.id_customer' => $id_customer, 'tb_order.bayar' => 'T'))->result();
    $data = [];
    
    foreach($service as $s){
        $ter = [];
        $id_order = [];
        foreach($terapis as $t){
            if($s->id_servis == $t->id_servis){
                $ter [] = $t->nama_t;
                $id_order [] = $t->id_order;
            }

        }
        $data [] = [
            'id' => $s->id_servis,
            'qty' => 1,
            'price'   => $s->biaya,
            'name'    => preg_replace("/[^a-zA-Z0-9]/", " ", $s->nm_servis),
            'start'    => $s->start,
            'end'    => $s->end,
            'type' => 'order',
            'terapis' => $ter,
            'id_order' => $id_order,
            'tgl' => $tgl,
            'id_customer' => $id_customer
            ];
    }

    $this->cart->insert($data);
//    return var_dump($this->cart->contents() );
//    return var_dump($service);
    redirect('match/order');
    // var_dump($data);
}

function update_app1()
{
    $id_order = $this->input->post('id_order');
    $id_t = $this->input->post('id_t');
    $start = $this->input->post('start');
    $end = $this->input->post('end');
    $start2 = date('H:i:s', strtotime($start));
    $end2 = date('H:i:s', strtotime($end));
    $now = date('Y-m-d');
    
    $start_t = date('D M d Y ').$start2.' GMT+0800 (Central Indonesia Time)';
    $end_t = date('D M d Y ').$end2.' GMT+0800 (Central Indonesia Time)';

    $cek = $this->db->get_where("tb_order where location='$id_t' AND tanggal='$now' AND '$end2' > start AND '$start2' < end")->num_rows();
    if ($cek > 0) 
    {
     $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Appointment gagal disimpan, waktu yang Anda inputkan bertabrakan! </div>');
     redirect("match/app");
    }else{
        $data_update = array(
            'id_terapis'   => $id_t,
            'location'   => $id_t,
            'start'   => $start2,
            'start_t'   => $start_t,
            'end'   => $end2,
            'end_t'   => $end_t,
    
        );
        $where = array('id_order' => $id_order );
        $res = $this->M_salon->UpdateData('tb_order', $data_update, $where);
        $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Data Berhasil Di Update </div>');
        redirect("match/app");
    }
    
    
}

function update_app2()
{
    $id_order = $this->input->post('id_order');
    $id_t = $this->input->post('id_t');
    $start = $this->input->post('start');
    $end = $this->input->post('end');
    $tgl = $this->input->post('tgle');
    $start2 = date('H:i:s', strtotime($start));
    $end2 = date('H:i:s', strtotime($end));
    
    $tgl2 = date('D M d Y ', strtotime($this->input->post('tgle')));


    $start_t = $tgl2.$start2.' GMT+0800 (Central Indonesia Time)';
    $end_t = $tgl2.$end2.' GMT+0800 (Central Indonesia Time)';

    $cek = $this->db->get_where("tb_order where location='$id_t' AND tanggal='$tgl' AND '$end2' > start AND '$start2' < end")->num_rows();
    if($cek > 0){
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Appointment gagal disimpan, waktu yang Anda inputkan bertabrakan! </div>');
        redirect("Match/app_priode?tgl=$tgl");
    }else{
        $data_update = array(
            'id_terapis' => $id_t,
            'location'   => $id_t,
            'start'   => $start2,
            'start_t'   => $start_t,
            'end'   => $end2,
            'end_t'   => $end_t,
    
        );
        $where = array('id_order' => $id_order );
        $res = $this->M_salon->UpdateData('tb_order', $data_update, $where);
        $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Data Berhasil Di Update </div>');
        redirect("Match/app_priode?tgl=$tgl");
    }
    
   
}

function selesai_app()
{
    $id_order = $this->input->post('id_order');
    $total = $this->input->post('total');
    
    $data_update = array(
        'status'   => 'Selesai',
        'total'   => $total,

    );
    $where = array('id_order' => $id_order );
    $res = $this->M_salon->UpdateData('tb_order', $data_update, $where);
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Data Berhasil Di Update </div>');
    redirect("match/app");
}

function selesai_app1()
{
    $id_order = $this->input->post('id_order');
    $total = $this->input->post('total');
    $tgl = $this->input->post('tgl');
    
    $data_update = array(
        'status'   => 'Selesai',
        'total'   => $total,

    );
    $where = array('id_order' => $id_order );
    $res = $this->M_salon->UpdateData('tb_order', $data_update, $where);
    // $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Data Berhasil Di Update </div>');
    // redirect("match/app_priode?tgl=$tgl");
    echo 'berhasil';
}

function batal_selesai()
{
    $id_order = $this->input->post('id_order');
    
    $data_update = array(
        'status'   => null,
    );
    $where = array('id_order' => $id_order );
    $res = $this->M_salon->UpdateData('tb_order', $data_update, $where);
    // $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Data Berhasil Di Update </div>');
    // redirect("match/app_priode?tgl=$tgl");
    echo 'berhasil';
}

function drop_app1()
{
    $id_order = $this->input->post('id_order');
    $costumer = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->get_where('tb_order', array('id_order' => $id_order))->row();
    $ket = $this->input->post('ket');
    $now = date('Y-m-d');
    $data_insert = array(
        'tgl'   => $now,
        'nama'   => $costumer->nama,
        'telepon'   => $costumer->telepon,
        'ket'   => $ket,

    );
    $this->db->insert('tb_cancel', $data_insert);
    $where = array('id_order' => $id_order);
    $res = $this->M_salon->DropData('tb_order', $where);
    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Berhasil Di Cancel </div>');
    redirect("match/app");
}

function drop_app2()
{
    $id_order = $this->input->post('id_order');
    $costumer = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->get_where('tb_order', array('id_order' => $id_order))->row();
    $ket = $this->input->post('ket');
    $now = $this->input->post('tgl');
    $data_insert = array(
        'tgl'   => $now,
        'nama'   => $costumer->nama,
        'telepon'   => $costumer->telepon,
        'ket'   => $ket,

    );
    $this->db->insert('tb_cancel', $data_insert);
    $where = array('id_order' => $id_order);
    $res = $this->M_salon->DropData('tb_order', $where);
    // $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Berhasil Di Cancel </div>');
    // redirect("Match/app_priode?tgl=$now");
    echo "berhasil";
}

function summary_app1(){
    $tgl1 = $this->input->post('tgl1');
    $tgl2 = $this->input->post('tgl2');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'app'       => $this->M_salon->summary_app1($tgl1, $tgl2),
        'penjualan'       => $this->M_salon->summary_app2($tgl1, $tgl2),
        'komisi'       => $this->M_salon->summary_app3($tgl1, $tgl2),
        'komisi_app'       => $this->M_salon->summary_app4($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    ); 

    $this->load->view('app/summary_export', $data);
}

function summary_appointment(){
    $tgl1 = $this->input->post('tgl1');
    $tgl2 = $this->input->post('tgl2');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'appointment'       => $this->M_salon->summary_appointment($tgl1, $tgl2),
        'servis' => $this->M_salon->summary_servis($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    ); 

    $this->load->view('app/summary_appointment', $data);
}

function app_priode()
{
    // $now = date('Y-m-d');
    $tgl = $this->input->get('tgl');

    // $d_order = $this->db->get_where('tb_terapis', ['tanggal' => date('Y-m-d')])->result_array();

    // $d_order_d = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', ['tb_order.tanggal' => date('Y-m-d')])->result_array();

    // $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => date('Y-m-d')))->result();

    // $d_order = $this->db->get_where('tb_terapis', array('tb_terapis.tanggal' => $tgl))->result_array();
    $d_order = $this->db->select('*')->from('tb_terapis')->join('tb_order', 'tb_terapis.id_terapis = tb_order.id_terapis','left')->where('tb_terapis.tanggal' , $tgl)->group_by('tb_terapis.id_terapis')->select_sum('tb_order.total')->get()->result_array();

    $d_order_d = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result_array();

    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result();

    $customer = $this->db->get('tb_customer')->result();

    
    $data = array(
        'title'  => "Appointment | Crepese Signature", 
        'anak'   => $this->db->get('tb_karyawan')->result(),
        'terapis'   => $this->db->get_where('tb_terapis', array('tanggal' => $tgl))->result(),
        'servis'   => $this->db->get('tb_servis')->result(),
        'd_order' => $d_order,
        'd_order_d' => $d_order_d,
        'd_order_all' => $d_order_all,
        'tgl'       => $tgl,
        'customer'  => $customer
    );
    if($tgl > date('Y-m-d')){
        $this->load->view('app/app_priode_besok', $data);
    }else{
        $this->load->view('app/app_priode', $data);
    }  
   
}

function get_diagram(){
    $tgl = $this->input->post('tgl');
    $d_order = $this->db->select('*')->from('tb_terapis')->join('tb_order', 'tb_terapis.id_terapis = tb_order.id_terapis','left')->where('tb_terapis.tanggal' , $tgl)->group_by('tb_terapis.id_terapis')->select_sum('tb_order.total')->get()->result_array();

    $d_order_d = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result_array();

//     $d_o = array();

// if ($this->session->userdata('id_role')=='1'){
//   foreach ($d_order as $key => $value) 
//     {
//       $nama_t =  $value['nama_t'];
//       $total = number_format($value['total']) ;
//       $d = array(
//         'id'  => $value['id_terapis'],
//         'name'  => "$nama_t ($total)",
//         'tzOffset'  => $value['tzoffset'],
//       );
//       $d_o[] = $d;
//     }
// }else{
//   foreach ($d_order as $key => $value) 
//     {

//       $d = array(
//         'id'  => $value['id_terapis'],
//         'name'  => $value['nama_t'],
//         'tzOffset'  => $value['tzoffset'],
//       );
//       $d_o[] = $d;
//     }
// }


foreach ($d_order_d as $key => $value) 
{
    $d_l['name'] = $value['nama'].' - '.$value['nm_servis'];
    $d_l['location'] = $value['location'];
    $d_l['start'] = $value['start'];
    $d_l['end'] = $value['end'];
    $d_l['url'] = $value['status'];
  
}

// foreach ($d_order as  $d) 
// {
//     $d_o['id'] = $d['id_terapis'];
//     $d_o['name'] = $d['name_t'];
//     $d_o['tzOffset'] = $d['tzoffset'];
// }

    // $a = [
        
    //     'd_o' => $d_o,
    //     'data' => $data,
    // ];
    // $output['nama'] = 'rahman';
    // $output['kelas'] = '7';

    echo json_encode($d_l);
    // var_dump($d_l);
}

function get_kelola(){
    // $tgl = '2021-03-04';
    $tgl = $this->input->post('tgl');
    $customer = $this->db->join('tb_customer','tb_order.id_customer = tb_customer.id_customer','left')->group_by('tb_order.id_customer')->order_by('tb_order.start')->get_where('tb_order', ['tb_order.tanggal' => $tgl])->result();
    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result();
    $terapis = $this->db->get_where('tb_terapis', array('tanggal' => $tgl))->result();
    $servis = $this->db->get('tb_servis')->result();

    // $data = [
    //     'customer' => $customer,
    //     'd_order_all' => $d_order_all,
    //     'terapis' => $terapis,
    //     'title' => 'get_kelola',
    //     'tgl' => $tgl
    // ];

    // $this->load->view('test/kelola_appointment',$data);

    echo '<table class="table" width="100%">
    <tr class="text-center">
     <th>Therapist</th>
     <th >CUSTOMER - SERVIS</th>
     <th >JAM MULAI</th>
     <th>JAM SELESAI</th>
     <th >AKSI</th>
   </tr>';
     foreach($customer as $c){
         $cek_bayar = $this->db->get_where('tb_order',['tanggal' => $tgl, 'id_customer' => $c->id_customer, 'bayar' => 'T'])->num_rows();
         echo '<tr>';
         if($cek_bayar > 0){
             echo '<td><a href="'.base_url().'match/detail_order?tgl='.$tgl.'&customer='.$c->id_customer.'"><strong>'.strtoupper($c->nama).'</strong></a></td>';
         }else{
             echo '<td><strong>'.strtoupper($c->nama).'</strong></td>';
         }
         
         echo'<td></td>
         <td></td>
         <td></td>
         <td></td>';
       
         foreach ($d_order_all as $key => $value){

           
            if($c->id_customer == $value->id_customer){
                
                echo'
                <tbody style="text-align: center;">
                <form method="post" class="update_kelola">
                <input type="hidden" id="tgl_'.$value->id_order.'" name="tgl" class="tgl" value="'. $tgl.'">
                      
                      <td width="20%">';
                      if ($value->status=="Selesai"){
                        echo '<select class="form-control select" required="" name="id_terapis" class="id_terapis_e" id="id_terapis_'.$value->id_order.'" disabled>
                          <label for=""></label>';
                          foreach($terapis as $tr){
                            if($value->id_terapis == $tr->id_terapis){
                                echo '<option value="'.$tr->id_terapis.'" selected>'.$tr->nama_t.'</option>';
                            }else{
                                echo '<option value="'. $tr->id_terapis.'" >'. $tr->nama_t.'</option>';
                            }
                          }
                        }else{
                            echo '<select class="form-control select" required="" name="id_terapis" class="id_terapis_e" id="id_terapis_'.$value->id_order.'">
                          <label for=""></label>';
                          foreach($terapis as $tr){
                            if($value->id_terapis == $tr->id_terapis){
                                echo '<option value="'.$tr->id_terapis.'" selected>'.$tr->nama_t.'</option>';
                            }else{
                                echo '<option value="'. $tr->id_terapis.'" >'. $tr->nama_t.'</option>';
                            }
                          }
                          
                        }
                        echo '</select>';
                          echo '</td>
                          <input type="hidden" class="id_order_e" name="id_order" value="'. $value->id_order.'">
                          <td >';

                          if ($value->status=="Selesai"){
                            echo '<select class="form-control select" required="" name="id_servis"  id="id_servis_'.$value->id_order.'" disabled>
                              <label for=""></label>';
                              foreach($servis as $tr){
                                if($value->id_servis == $tr->id_servis){
                                    echo '<option value="'.$tr->id_servis.'" selected>'.$tr->nm_servis.'</option>';
                                }else{
                                    echo '<option value="'. $tr->id_servis.'" >'. $tr->nm_servis.'</option>';
                                }
                                
                              }
                              echo '</select>';
                            }else{
                                echo '<select class="form-control select" required="" name="id_servis"  id="id_servis_'.$value->id_order.'">
                              <label for=""></label>';
                              foreach($servis as $tr){
                                if($value->id_servis == $tr->id_servis){
                                    echo '<option value="'.$tr->id_servis.'" selected>'.$tr->nm_servis.'</option>';
                                }else{
                                    echo '<option value="'. $tr->id_servis.'" >'. $tr->nm_servis.'</option>';
                                }
                              }
                              echo '</select>';
                            }
                            
                          echo '</td>
                          
                          <td >';
                          if ($value->status=="Selesai"){
                              echo '<input type="time" name="start" class="form-control start" id="start_'.$value->id_order.'" value="'.$value->start.'" readonly>';
                          }else{
                              echo '<input type="time" name="start" class="form-control start" id="start_'.$value->id_order.'"  value="'.$value->start.'">';
                          }
                          echo '</td>
                          <td >';
                          if ($value->status=="Selesai"){
                              echo '<input type="time" name="end" class="form-control end" id="end_'.$value->id_order.'" value="'.$value->end.'" readonly>';
                          }else{
                              echo '<input type="time" name="end" class="form-control end" id="end_'.$value->id_order.'" value="'.$value->end.'">';
                          }
                          echo '</td>
                          <td >';
                          if($value->bayar == 'Y'){
                            echo'<span class="text-warning">Paid</span>';
                            if ($this->session->userdata('id_role')=='1'){
                                echo '<button id_order="'.$value->id_order.'" class="btn ml-2 btn-xs btn-success unpaid">Unpaid</button>';
                            }
                          }else{
                            if ($value->status=="Selesai"){
                                echo '<span class="badge badge-success"><i class="fa fa-check"></i></span> &nbsp &nbsp  
                                <button id="'.$value->id_order.'" class="btn btn-xs btn-danger batal"><i class="fas fa-times"></i></button>';
                            }else{
                                echo '<button type="submit" id="'.$value->id_order.'" class="btn btn-primary btn-xs update_app">Simpan</button>
                                <button type="button" class="btn btn-danger btn-xs cancel" id_order = "'. $value->id_order.'" >Cancel</button>
                                <button type="button" class="btn btn-success btn-xs selesai" id="'.$value->id_order.'" tgl = "'.$tgl.'" >Selesai</button>';
                            }
                          }
                          
                          echo '</td> </form>
                          </tbody>';
                        
         echo '</tr>';
            }
            
         }
         
         
     }
     echo '</table>';

}

function get_selesai(){
    $id_order = $this->input->post('id_order');
    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array(
        'tb_order.id_order' => $id_order))->result();
     $output = [];
     foreach($d_order_all as $d){
        $output['nama_t']= $d->nama_t;
        $output['nama']= $d->nama;
     $output['nm_servis']= $d->nm_servis;
     $output['start']= $d->start;
     $output['end']= $d->end;   
     $output['id_order']= $d->id_order;
     $output['biaya']= $d->biaya;

    $start = strtotime($d->start);
    $end = strtotime($d->end);
    
    $diff    =$end - $start;

    $jam    = floor($diff / (60 * 60));
    $menit    =$diff - $jam * (60 * 60);
    
    $output['total_waktu'] = $jam.' jam '.floor($menit/60).' menit';

     }   
     

     echo json_encode($output);
}

function kelola_app(){
    $tgl = $this->input->get('tgl');

    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result();

    $data = [
        'title' => 'Kelola Appointment',
        'tgl' => $tgl,
        'anak'   => $this->db->get('tb_karyawan')->result(),
        
        'd_order_all' => $d_order_all,
        'terapis'   => $this->db->get_where('tb_terapis', ['tanggal' => $tgl])->result(),
        'customer' => $this->db->join('tb_order','tb_customer.id_customer = tb_order.id_customer', 'left')->group_by('tb_order.id_customer')->get_where('tb_customer', [
            'tb_order.tanggal' => $tgl
        ])->result(),
        'servis'   => $this->db->get('tb_servis')->result(),
    ];
    $this->load->view('app/kelola_app',$data);

}

function kelola_app2(){
    $tgl = $this->input->get('tgl');

    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result();

    $data = [
        'title' => 'Kelola Appointment',
        'tgl' => $tgl,
        'd_order_all' => $d_order_all,
        'anak'   => $this->db->get('tb_karyawan')->result(),
        'terapis'   => $this->db->get_where('tb_terapis', ['tanggal' => $tgl])->result(),
        'customer' => $this->db->join('tb_order','tb_customer.id_customer = tb_order.id_customer', 'left')->group_by('tb_order.id_customer')->get_where('tb_customer', [
            'tb_order.tanggal' => $tgl
        ])->result(),
        'servis'   => $this->db->get('tb_servis')->result(),
    ];
    $this->load->view('app/kelola_app2',$data);

}

function list_appointment(){
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal >=' => $tgl1, 'tb_order.tanggal <=' => $tgl2))->result();
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'd_order_all' => $d_order_all
    ); 

    $this->load->view('app/list_appointment', $data);
}

function laporan_diagram()
{
    // $now = date('Y-m-d');
    $tgl = '2021-02-13';

    // $d_order = $this->db->get_where('tb_terapis', ['tanggal' => date('Y-m-d')])->result_array();

    // $d_order_d = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', ['tb_order.tanggal' => date('Y-m-d')])->result_array();

    // $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => date('Y-m-d')))->result();

    // $d_order = $this->db->get_where('tb_terapis', array('tb_terapis.tanggal' => $tgl))->result_array();
    $d_order = $this->db->select('*')->from('tb_terapis')->join('tb_order', 'tb_terapis.id_terapis = tb_order.id_terapis')->where('tb_terapis.tanggal' , $tgl)->group_by('tb_terapis.id_terapis')->select_sum('tb_order.total')->get()->result_array();

    $d_order_d = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result_array();

    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result();

    $customer = $this->db->get('tb_customer')->result();

    
    $data = array(
        'title'  => "Appointment | Crepese Signature", 
        'anak'   => $this->db->get('tb_karyawan')->result(),
        'terapis'   => $this->db->get_where('tb_terapis', array('tanggal' => $tgl))->result(),
        'servis'   => $this->db->get('tb_servis')->result(),
        'd_order' => $d_order,
        'd_order_d' => $d_order_d,
        'd_order_all' => $d_order_all,
        'tgl'       => $tgl,
        'customer'  => $customer,
        'title' => 'test aj'
    );
    // if($tgl > date('Y-m-d')){
    //     $this->load->view('app/app_priode_besok', $data);
    // }else{
    //     $this->load->view('app/app_priode', $data);
    // }  
    // $this->load->view('app/laporan_diagram',$data,false);
   

    // $pdfFilePath = FCPATH . '/assets/file/mpdf.pdf';
    ini_set('memory_limit', '32M');
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
    $view =  $this->load->view('app/laporan_diagram',[],TRUE);
    $mpdf->WriteHTML($view);
    $mpdf->Output();


    // $this->load->library('pdf');

    // $this->pdf->setPaper('A4', 'potrait');
    // $this->pdf->filename = "laporan-petanikode.pdf";
    // $this->pdf->load_view('app/laporan_diagram', $data);
    $filename = 'test';
        // $option = new \Dompdf\Options();
        // $option->set('isJavascriptEnabled', TRUE);
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->set_option('enable-javascript', true);
        $dompdf->set_option('javascript-delay', 13500);
        $dompdf->set_option('enable-smart-shrinking', true);
        $dompdf->set_option('no-stop-slow-scripts', true);
        $dompdf->load_html( $this->load->view('app/laporan_diagram', $data, true));
        $dompdf->render();
        $dompdf->stream($filename.'.pdf',array("Attachment"=>0));
   
}

public function laporan_komisi(){

    $hari  = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
    if(empty($this->input->get('tgl1'))){
        $tgl1   = date('Y-m-').'01';
        $tgl2   = date('Y-m-').$hari;
    }else{
        $tgl1   = $this->input->get('tgl1');
        $tgl2   = $this->input->get('tgl2');
    } 
    

        $dt_sum_app = $this->db->select('SUM(qty * biaya) as total')->get_where('tb_app',[
            'tgl >=' => $tgl1,
            'tgl <=' => $tgl2
            ])->row();
         
        $dt_sum_pembelian = $this->db->select('SUM(jumlah * harga) as total')->get_where('tb_pembelian',[
            'tanggal >=' => $tgl1,
            'tanggal <=' => $tgl2
        ])->row();
        $data = [
            'title' => 'laporan komisi',
            // 'komisi' => $this->db->query("SELECT tb_karyawan.id_kry, nm_kry, tb_komisi_app.total_app, komisi.total_produk FROM tb_karyawan
            // LEFT JOIN (SELECT komisi.id_kry, SUM(komisi.komisi) as total_produk FROM komisi WHERE komisi.tgl BETWEEN '$tgl1' AND '$tgl2' GROUP BY komisi.id_kry) komisi ON tb_karyawan.id_kry = komisi.id_kry
            // LEFT JOIN (SELECT tb_komisi_app.id_kry, SUM(tb_komisi_app.komisi) as total_app FROM tb_komisi_app WHERE tb_komisi_app.tgl BETWEEN '$tgl1' AND '$tgl2' GROUP BY tb_komisi_app.id_kry) tb_komisi_app ON tb_karyawan.id_kry = tb_komisi_app.id_kry")->result(),
            'komisi' => $this->db->query("SELECT tb_karyawan.id_kry, komisi2.total_produk2, nm_kry, tb_komisi_app.total_app, komisi.total_produk FROM tb_karyawan
                                        LEFT JOIN (SELECT komisi.id_kry, SUM(komisi.komisi) as total_produk 
                                        FROM komisi 
                                        left join tb_pembelian on tb_pembelian.id_pembelian = komisi.id_pembelian
                                        WHERE komisi.tgl BETWEEN '$tgl1' AND '$tgl2' and tb_pembelian.id_produk NOT IN('139','134','135','136','137','138') GROUP BY komisi.id_kry) komisi ON tb_karyawan.id_kry = komisi.id_kry
                                        
                                        left join (
                                        SELECT komisi.id_kry, komisi.id_pembelian, SUM(komisi.komisi) as total_produk2 , tb_pembelian.id_produk
                                        FROM komisi 
                                        left join tb_pembelian on tb_pembelian.id_pembelian = komisi.id_pembelian
                                        WHERE komisi.tgl BETWEEN '$tgl1' AND '$tgl2' and tb_pembelian.id_produk IN('139','134','135','136','137','138')
                                        GROUP BY komisi.id_kry
                                        ) komisi2 on tb_karyawan.id_kry = komisi2.id_kry
                                        LEFT JOIN (SELECT tb_komisi_app.id_kry, SUM(tb_komisi_app.komisi) as total_app FROM tb_komisi_app WHERE tb_komisi_app.tgl BETWEEN '$tgl1' AND '$tgl2' GROUP BY tb_komisi_app.id_kry) tb_komisi_app ON tb_karyawan.id_kry = tb_komisi_app.id_kry")->result(),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'dt_sum_app' => $dt_sum_app,
            'dt_sum_pembelian' => $dt_sum_pembelian,
            'invoice' => $this->db->select_sum('diskon')->get_where('tb_invoice',['tgl_jam >=' => $tgl1, 'tgl_jam <=' => $tgl2])->row(),
            'dt_masuk' => $this->db->query("SELECT COUNT(tgl) as jml_masuk FROM `view_masuk` WHERE tgl >= '$tgl1' AND tgl <= '$tgl2'")->row(),
            'dt_rules' => $this->db->get('tb_rules')->result(),
            'rules_active' => $this->db->get_where('tb_rules',['status' => 1])->row(),   
        ];

    $this->load->view('invoice/laporan_komisi',$data);
}

public function edit_rules_komisi()
{
    $tgl1 = $this->input->post('tgl1');
    $tgl2 = $this->input->post('tgl2');

    $id_rules = $this->input->post('id_rules');
    $jenis = $this->input->post('jenis');
    $jumlah = $this->input->post('jumlah');
    $persen = $this->input->post('persen');

    $jenis_tambah = $this->input->post('jenis_tambah');
    $jumlah_tambah = $this->input->post('jumlah_tambah');
    $persen_tambah = $this->input->post('persen_tambah');

    if($jenis_tambah && $jumlah_tambah && $persen_tambah){
        $data_tambah = [
            'jenis' => $jenis_tambah,
            'jumlah' => $jumlah_tambah,
            'persen' => $persen_tambah
        ];

        $this->db->insert('tb_rules',$data_tambah);
    }

    if($id_rules){
        for($count = 0; $count<count($id_rules); $count++){
            $data_update = [
                'jenis' => $jenis[$count],
                'jumlah' => $jumlah[$count],
                'persen' => $persen[$count],
            ];

            $this->db->where('id_rules',$id_rules[$count]);
            $this->db->update('tb_rules',$data_update);
        }
    }

    if($this->input->post('komisi')){
        $data_uncheck = [
            'status' => 0
        ];
        $this->db->where('id_rules !=', $this->input->post('komisi'));
        $this->db->update('tb_rules',$data_uncheck);
    
        $data_check = [
            'status' => 1
        ];
        $this->db->where('id_rules', $this->input->post('komisi'));
        $this->db->update('tb_rules',$data_check);
    
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Rules berhasil diubah<div class="ml-5 btn btn-sm"></div></div>');
            redirect("Match/laporan_komisi?tgl1=$tgl1&tgl2=$tgl2");   
    }else{
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Pilih rules terlebih dahulu<div class="ml-5 btn btn-sm"></div></div>');
            redirect("Match/laporan_komisi?tgl1=$tgl1&tgl2=$tgl2"); 
    }
    
}

public function drop_rules($id_rules)
{
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $this->db->where('id_rules',$id_rules);
    $this->db->delete('tb_rules');

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Rules berhasil dihapus<div class="ml-5 btn btn-sm"></div></div>');
    redirect("Match/laporan_komisi?tgl1=$tgl1&tgl2=$tgl2");   
}

public function summary_laporan_komisi(){

    
    $dt_a   = $this->input->post('tgl3');
    // $dt_b   = $this->input->post('tgl2');
    // $dt_b = date('Y-m-d', strtotime('+1 days', strtotime($this->input->get('tgl2'))));
    $dt_b   = $this->input->post('tgl4');
    
    $dt_sum_app = $this->db->select('SUM(qty * biaya) as total')->get_where('tb_app',[
        'tgl >=' => $dt_a,
        'tgl <=' => $dt_b
        ])->row();
     
    $dt_sum_pembelian = $this->db->select('SUM(jumlah * harga) as total')->get_where('tb_pembelian',[
        'tanggal >=' => $dt_a,
        'tanggal <=' => $dt_b
    ])->row();
    $data = [
        'title' => 'laporan komisi',
        'komisi' => $this->db->query("SELECT tb_karyawan.id_kry, nm_kry, tb_komisi_app.total_app, komisi.total_produk FROM tb_karyawan
        LEFT JOIN (SELECT komisi.id_kry, SUM(komisi.komisi) as total_produk FROM komisi WHERE komisi.tgl BETWEEN '$dt_a' AND '$dt_b' GROUP BY komisi.id_kry) komisi ON tb_karyawan.id_kry = komisi.id_kry
        LEFT JOIN (SELECT tb_komisi_app.id_kry, SUM(tb_komisi_app.komisi) as total_app FROM tb_komisi_app WHERE tb_komisi_app.tgl BETWEEN '$dt_a' AND '$dt_b' GROUP BY tb_komisi_app.id_kry) tb_komisi_app ON tb_karyawan.id_kry = tb_komisi_app.id_kry")->result(),
        'tgl1' => $dt_a,
        'tgl2' => $dt_b,
        'dt_sum_app' => $dt_sum_app,
        'dt_sum_pembelian' => $dt_sum_pembelian,
        'invoice' => $this->db->select_sum('diskon')->get_where('tb_invoice',['tgl_jam >=' => $dt_a, 'tgl_jam <=' => $dt_b])->row(),
        'dt_masuk' => $this->db->query("SELECT COUNT(tgl) as jml_masuk FROM `view_masuk` WHERE tgl >= '$dt_a' AND tgl <= '$dt_b'")->row()
    ];

$this->load->view('invoice/summary_laporan_komisi',$data);
}

public function excel_komisi(){

    
    $tgl1   = $this->input->get('tgl1');
    // $dt_b   = $this->input->post('tgl2');
    // $dt_b = date('Y-m-d', strtotime('+1 days', strtotime($this->input->get('tgl2'))));
    $tgl2   = $this->input->get('tgl2');
    
    $dt_sum_app = $this->db->select('SUM(qty * biaya) as total')->get_where('tb_app',[
        'tgl >=' => $tgl1,
        'tgl <=' => $tgl2
        ])->row();
     
    $dt_sum_pembelian = $this->db->select('SUM(jumlah * harga) as total')->get_where('tb_pembelian',[
        'tanggal >=' => $tgl1,
        'tanggal <=' => $tgl2
    ])->row();
    $data = [
        'title' => 'laporan komisi',
        // 'komisi' => $this->db->query("SELECT tb_karyawan.id_kry, nm_kry, tb_komisi_app.total_app, komisi.total_produk FROM tb_karyawan
        // LEFT JOIN (SELECT komisi.id_kry, SUM(komisi.komisi) as total_produk FROM komisi WHERE komisi.tgl BETWEEN '$tgl1' AND '$tgl2' GROUP BY komisi.id_kry) komisi ON tb_karyawan.id_kry = komisi.id_kry
        // LEFT JOIN (SELECT tb_komisi_app.id_kry, SUM(tb_komisi_app.komisi) as total_app FROM tb_komisi_app WHERE tb_komisi_app.tgl BETWEEN '$tgl1' AND '$tgl2' GROUP BY tb_komisi_app.id_kry) tb_komisi_app ON tb_karyawan.id_kry = tb_komisi_app.id_kry")->result(),
        'komisi' => $this->db->query("SELECT tb_karyawan.id_kry, komisi2.total_produk2, nm_kry, tb_komisi_app.total_app, komisi.total_produk FROM tb_karyawan
                                        LEFT JOIN (SELECT komisi.id_kry, SUM(komisi.komisi) as total_produk 
                                        FROM komisi 
                                        left join tb_pembelian on tb_pembelian.id_pembelian = komisi.id_pembelian
                                        WHERE komisi.tgl BETWEEN '$tgl1' AND '$tgl2' and tb_pembelian.id_produk NOT IN('139','134','135','136','137','138') GROUP BY komisi.id_kry) komisi ON tb_karyawan.id_kry = komisi.id_kry
                                        
                                        left join (
                                        SELECT komisi.id_kry, komisi.id_pembelian, SUM(komisi.komisi) as total_produk2 , tb_pembelian.id_produk
                                        FROM komisi 
                                        left join tb_pembelian on tb_pembelian.id_pembelian = komisi.id_pembelian
                                        WHERE komisi.tgl BETWEEN '$tgl1' AND '$tgl2' and tb_pembelian.id_produk IN('139','134','135','136','137','138')
                                        GROUP BY komisi.id_kry
                                        ) komisi2 on tb_karyawan.id_kry = komisi2.id_kry
                                        LEFT JOIN (SELECT tb_komisi_app.id_kry, SUM(tb_komisi_app.komisi) as total_app FROM tb_komisi_app WHERE tb_komisi_app.tgl BETWEEN '$tgl1' AND '$tgl2' GROUP BY tb_komisi_app.id_kry) tb_komisi_app ON tb_karyawan.id_kry = tb_komisi_app.id_kry")->result(),
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
        'dt_sum_app' => $dt_sum_app,
        'dt_sum_pembelian' => $dt_sum_pembelian,
        'invoice' => $this->db->select_sum('diskon')->get_where('tb_invoice',['tgl_jam >=' => $tgl1, 'tgl_jam <=' => $tgl2])->row(),
        'dt_masuk' => $this->db->query("SELECT COUNT(tgl) as jml_masuk FROM `view_masuk` WHERE tgl >= '$tgl1' AND tgl <= '$tgl2'")->row(),
        'rules_active' => $this->db->get_where('tb_rules',['status' => 1])->row(),
    ];

$this->load->view('invoice/excel_laporan_komisi',$data);
}

public function check_komisi(){
    $id_kry = $this->input->get('id');
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $data = [
        'title' => 'Detail Laporan Komisi',

        'komisi_app' => $this->db->select('tb_komisi_app.tgl, tb_servis.nm_servis, tb_app.nm_karyawan, tb_app.qty, tb_app.biaya, tb_app.total, tb_komisi_app.komisi ')->join('tb_app','tb_komisi_app.id_app = tb_app.id_app','left')->join('tb_servis','tb_app.id_servis = tb_servis.id_servis','left')->where('tb_komisi_app.id_kry',$id_kry)->where('tb_komisi_app.tgl >=', $tgl1)->where('tb_komisi_app.tgl <=', $tgl2)->get('tb_komisi_app')->result(),

        'komisi_pembelian' => $this->db->select('komisi.tgl, tb_produk.nm_produk, tb_pembelian.jumlah, tb_pembelian.harga, tb_pembelian.total, komisi.komisi, tb_pembelian.nm_karyawan')->join('tb_pembelian','komisi.id_pembelian = tb_pembelian.id_pembelian','left')->join('tb_produk','tb_pembelian.id_produk = tb_produk.id_produk','left')->where('komisi.id_kry',$id_kry)->where('komisi.tgl >=', $tgl1)->where('komisi.tgl <=', $tgl2)->get('komisi')->result(),

        'karyawan' => $this->db->get_where('tb_karyawan',[
            'id_kry' => $id_kry
        ])->result()[0],
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
        'id_kry' => $id_kry
        
    ];

    $this->load->view('invoice/check_komisi',$data);
}

public function print_komisi_perorang(){
    $id_kry = $this->input->get('id');
    $tgl1 = $this->input->get('tgl1');
    $tgl2 = $this->input->get('tgl2');

    $data = [
        'title' => 'Detail Laporan Komisi',

        'komisi_app' => $this->db->select('tb_komisi_app.tgl, tb_servis.nm_servis, tb_app.nm_karyawan, tb_app.qty, tb_app.biaya, tb_app.total, tb_komisi_app.komisi ')->join('tb_app','tb_komisi_app.id_app = tb_app.id_app','left')->join('tb_servis','tb_app.id_servis = tb_servis.id_servis','left')->where('tb_komisi_app.id_kry',$id_kry)->where('tb_komisi_app.tgl >=', $tgl1)->where('tb_komisi_app.tgl <=', $tgl2)->get('tb_komisi_app')->result(),

        'komisi_pembelian' => $this->db->select('komisi.tgl, tb_produk.nm_produk, tb_pembelian.jumlah, tb_pembelian.harga, tb_pembelian.total, komisi.komisi, tb_pembelian.nm_karyawan')->join('tb_pembelian','komisi.id_pembelian = tb_pembelian.id_pembelian','left')->join('tb_produk','tb_pembelian.id_produk = tb_produk.id_produk','left')->where('komisi.id_kry',$id_kry)->where('komisi.tgl >=', $tgl1)->where('komisi.tgl <=', $tgl2)->get('komisi')->result(),

        'karyawan' => $this->db->get_where('tb_karyawan',[
            'id_kry' => $id_kry
        ])->result()[0],
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
        'id_kry' => $id_kry
        
    ];

    $this->load->view('invoice/print_komisi_perorang',$data);
}

function laporan_diagram_test()
{
    // $now = date('Y-m-d');
    $tgl = '2021-02-13';

    // $d_order = $this->db->get_where('tb_terapis', ['tanggal' => date('Y-m-d')])->result_array();

    // $d_order_d = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', ['tb_order.tanggal' => date('Y-m-d')])->result_array();

    // $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => date('Y-m-d')))->result();

    // $d_order = $this->db->get_where('tb_terapis', array('tb_terapis.tanggal' => $tgl))->result_array();
    $d_order = $this->db->select('*')->from('tb_terapis')->join('tb_order', 'tb_terapis.id_terapis = tb_order.id_terapis')->where('tb_terapis.tanggal' , $tgl)->group_by('tb_terapis.id_terapis')->select_sum('tb_order.total')->get()->result_array();

    $d_order_d = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result_array();

    $d_order_all = $this->db->join('tb_customer', 'tb_order.id_customer = tb_customer.id_customer', 'left')->join('tb_terapis', 'tb_order.id_terapis = tb_terapis.id_terapis', 'left')->join('tb_servis', 'tb_order.id_servis = tb_servis.id_servis', 'left')->get_where('tb_order', array('tb_order.tanggal' => $tgl))->result();

    $customer = $this->db->get('tb_customer')->result();

    
    $data = array(
        'title'  => "Appointment | Crepese Signature", 
        'anak'   => $this->db->get('tb_karyawan')->result(),
        'terapis'   => $this->db->get_where('tb_terapis', array('tanggal' => $tgl))->result(),
        'servis'   => $this->db->get('tb_servis')->result(),
        'd_order' => $d_order,
        'd_order_d' => $d_order_d,
        'd_order_all' => $d_order_all,
        'tgl'       => $tgl,
        'customer'  => $customer,
    );
    // if($tgl > date('Y-m-d')){
    //     $this->load->view('app/app_priode_besok', $data);
    // }else{
    //     $this->load->view('app/app_priode', $data);
    // }  
    $this->load->view('app/laporan_diagram',$data);
   
}

// ================================== END APP ==========================================


//==================================INVOICE======================================

public function invoice(){
    if (empty($this->input->get('tgl1'))) {
        $dt_a = date('Y-m-d');
        $dt_b   = date('Y-m-d');        
        $data = array(
            'title'  => "Crepe Signature | Daftar Invoice",
            'invoice' => $this->M_salon->daftar_invoice(" where tgl_jam = '$dt_a' AND status = 0"),
            'tgl1' => $dt_a,
            'tgl2' => $dt_b
        );
    }else{
        $dt_a   = $this->input->get('tgl1');
        $dt_b   = $this->input->get('tgl2');
        // $dt_b = date('Y-m-d', strtotime('+1 days', strtotime($this->input->post('tgl2'))));
        $data = array(
            'title'  => "Crepe Signature | Daftar Invoice", 
            'invoice' => $this->M_salon->daftar_invoice(" where tb_invoice.tgl_jam >= '$dt_a' AND tb_invoice.tgl_jam <= '$dt_b' AND status = 0"),
            'tgl1' => $dt_a,
            'tgl2' => $dt_b
        );
    }
    $this->load->view('invoice/tabel', $data);
}

public function laporan_invoice(){

    $dt_a   = $this->input->post('tgl1');
    // $dt_b   = $this->input->post('tgl2');
    $dt_b = $this->input->post('tgl2');
    $data = array(
        'title'  => "Laporan Invoice", 
        'invoice' => $this->M_salon->daftar_invoice(" where tb_invoice.tgl_jam >= '$dt_a' AND tb_invoice.tgl_jam <= '$dt_b' AND status = 0"),
        'tgl1' => $this->input->post('tgl1'),
        'tgl2' => $this->input->post('tgl2'),
    );

    $this->load->view('invoice/laporan_invoice', $data);

}

public function laporan_pemasukan(){
    if (empty($this->input->post('tgl1'))) {
        // $bulan = date('m');
        // $year = date('Y');
        $tanggal = date('Y-m-d');        
        $data = array(
            'title'  => "Crepese Signature | Laporan Pemasukan",
            // 'invoice' => $this->M_salon->daftar_invoice(" where MONTH(tb_invoice.tgl_jam) = '$bulan' AND YEAR(tb_invoice.tgl_jam) = $year AND status = 0"),
            'servis' => $this->M_salon->summary_servis($tanggal, $tanggal),  
            'penjualan'     => $this->M_salon->summary_penjualan_produk($tanggal, $tanggal), 
        );
    }else{
        $dt_a   = $this->input->post('tgl1');
        $dt_b   = $this->input->post('tgl2');
        // $dt_b = date('Y-m-d', strtotime('+1 days', strtotime($this->input->post('tgl2'))));
        $data = array(
            'title'  => "Crepese Signature | Laporan Pemasukan", 
            'servis' => $this->M_salon->summary_servis($dt_a, $dt_b),  
            'penjualan'     => $this->M_salon->summary_penjualan_produk($dt_a, $dt_b),
        );
    }
    $this->load->view('invoice/laporan_pemasukan', $data);
}

public function summary_laporan_pemasukan(){
    if (empty($this->input->get('tgl1'))) {
        // $bulan = date('m');
        // $year = date('Y');
        $tanggal = date('Y-m-d');        
        $data = array(
            'title'  => "Crepese Signature | Laporan Pemasukan",
            // 'invoice' => $this->M_salon->daftar_invoice(" where MONTH(tb_invoice.tgl_jam) = '$bulan' AND YEAR(tb_invoice.tgl_jam) = $year AND status = 0"),
            'servis' => $this->M_salon->summary_servis($tanggal, $tanggal),  
            'penjualan'     => $this->M_salon->summary_penjualan_produk($tanggal, $tanggal),
            'invoice' => $this->db->select_sum('total')->select_sum('diskon')->select_sum('nominal_voucher')->get_where('tb_invoice',['tgl_jam >=' => $tanggal, 'tgl_jam <=' => $tanggal])->row(),
            'sort'      => date('d-M-y', strtotime($tanggal))." ~ ".date('d-M-y', strtotime($tanggal)),
             // 'dp' => $this->db->select_sum('jumlah_dp')->get_where('tb_dp',['tgl_dp' => $tanggal,'status' => 1])->row()
            'dp' => $this->db->query("SELECT tb_dp.jumlah_dp, invoice.tgl_pakai, IF(tb_dp.status = 2 AND invoice.tgl_pakai >= '$tanggal' AND invoice.tgl_pakai <= '$tanggal', 'Terpakai', 'Belum') as pakai FROM `tb_dp` 
            LEFT JOIN(SELECT kd_dp, tgl_jam as tgl_pakai FROM tb_invoice) invoice ON tb_dp.kd_dp = invoice.kd_dp 
            WHERE tgl_dp >= '$tanggal' AND tgl_dp <= '$tanggal'")->result(),
        );
    }else{
        $dt_a   = $this->input->get('tgl1');
        $dt_b   = $this->input->get('tgl2');
        // $dt_b = date('Y-m-d', strtotime('+1 days', strtotime($this->input->post('tgl2'))));
        $data = array(
            'title'  => "Crepese Signature | Laporan Pemasukan", 
            'servis' => $this->M_salon->summary_servis($dt_a, $dt_b),  
            'penjualan'     => $this->M_salon->summary_penjualan_produk($dt_a, $dt_b),
            'invoice' => $this->db->select_sum('total')->select_sum('diskon')->select_sum('nominal_voucher')->get_where('tb_invoice',['tgl_jam >=' => $dt_a, 'tgl_jam <=' => $dt_b , 'status' => '0'])->row(),
            'sort'      => date('d-M-y', strtotime($dt_a))." ~ ".date('d-M-y', strtotime($dt_b)),
             // 'dp' => $this->db->select_sum('jumlah_dp')->get_where('tb_dp',[
            //     'tgl_dp >=' => $dt_a,
            //     'tgl_dp <=' => $dt_b,
            //     'status' => 1,
            //     ])->row()
            'dp' => $this->db->query("SELECT tb_dp.jumlah_dp, invoice.tgl_pakai, IF(tb_dp.status = 2 AND invoice.tgl_pakai >= '$dt_a' AND invoice.tgl_pakai <= '$dt_b', 'Terpakai', 'Belum') as pakai FROM `tb_dp` 
            LEFT JOIN(SELECT kd_dp, tgl_jam as tgl_pakai FROM tb_invoice) invoice ON tb_dp.kd_dp = invoice.kd_dp 
            WHERE tgl_dp >= '$dt_a' AND tgl_dp <= '$dt_b'")->result(),
        );
    }
    $this->load->view('invoice/summary_laporan_pemasukan', $data);
}

public function void(){
    $voucher = $this->input->post('voucher');
    $no_nota = $this->input->post('no_nota');
    $nm_void = $this->session->userdata('nm_user');

    $get_voucher = $this->db->get_where('voucher_void',['voucher' => $voucher ])->row();

    if ($get_voucher->terpakai != 'Y' && !empty($get_voucher)) {
        # code...


    $data_void = [
        'status' => '1',
        'nm_void' => $nm_void,
        'ket_void' => $this->input->post('ket_void')
    ];

    $this->db->where('no_nota', $no_nota);
	$this->db->update('tb_invoice', $data_void);

    $data_void = [
        'void' => '1',
    ];
    $this->db->where('no_nota', $no_nota);
	$this->db->update('tb_pembelian', $data_void);

    $data_void = [
        'void' => 'T',
    ];
    $this->db->where('kode_stok_produk', $no_nota);
	$this->db->update('tb_stok_produk', $data_void);

    $detail_invoice = $this->db->get_where('tb_invoice',['no_nota' => $no_nota])->result()[0];

    $tanggal = date('Y-m-d', strtotime($detail_invoice->tgl_jam));
    $dt_jurnal = $this->db->get_where('tb_jurnal',['tgl' => $tanggal])->result();

        $this->db->where('kd_gabungan', $no_nota);
        $this->db->delete('tb_jurnal');
    if(!empty($detail_invoice->kd_dp)){
        $data_dp = [
            'status' => '1'
        ];
        $this->db->where('kd_dp', $detail_invoice->kd_dp);
	    $this->db->update('tb_dp', $data_dp);
    }

    $this->db->where('voucher',$voucher);
    $this->db->update('voucher_void',['terpakai' => 'Y']);
    
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Berhasil Void<div class="ml-5 btn btn-sm"></div></div>');
        redirect("Match/invoice");   
}else{
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Voucher sudah terpakai / tidak ditemukan<div class="ml-5 btn btn-sm"></div></div>');
    redirect("Match/invoice");   
}
    

}

public function data_void(){
    if (empty($this->input->post('tgl1'))) {
        $bulan = date('m');
        $year = date('Y');        
        $data = array(
            'title'  => "Crepese Signature | Daftar Void",
            'invoice' => $this->M_salon->daftar_invoice(" where MONTH(tb_invoice.tgl_jam) = '$bulan' AND YEAR(tb_invoice.tgl_jam) = $year AND status = 1") 
            
        );
    }else{
        $dt_a   = $this->input->post('tgl1');
        // $dt_b   = $this->input->post('tgl2');
        $dt_b = date('Y-m-d', strtotime('+1 days', strtotime($this->input->post('tgl2'))));
        $data = array(
            'title'  => "Crepese Signature | Daftar Void", 
            'invoice' => $this->M_salon->daftar_invoice(" where tb_invoice.tgl_jam BETWEEN '$dt_a' AND '$dt_b' AND status = 1")
        );
    }
    $this->load->view('invoice/data_void', $data);
}

public function get_invoice(){
    $no_nota = $this->input->post('no_nota');
    $invoice = $this->db->join('tb_customer','tb_invoice.id_customer = tb_customer.id_customer','left')->get_where('tb_invoice', [
        'no_nota' => $no_nota
    ])->result()[0];
    $produk = $this->db->join('tb_invoice','tb_invoice.no_nota = tb_pembelian.no_nota','left')->join('tb_produk','tb_pembelian.id_produk = tb_produk.id_produk','left')->get_where('tb_pembelian',[
        'tb_pembelian.no_nota' => $no_nota
    ])->result();
    
    $servis = $this->db->join('tb_invoice','tb_invoice.no_nota = tb_app.no_nota','left')->join('tb_servis','tb_app.id_servis = tb_servis.id_servis','left')->get_where('tb_app',[
        'tb_app.no_nota' => $no_nota
    ])->result();


    echo '<div class="card-header">
    <p class="float-left"><strong>'. $invoice->no_nota.'</strong></p>';
    if($invoice->id_customer != 0){
        echo '<p class="float-right">'. $invoice->nama  .'-'.  date('d/M/Y', strtotime($invoice->tgl_jam)).'</p>';
    }else{
        echo '<p class="float-right">'. date('d/M/Y', strtotime($invoice->tgl_jam)) .'</p>';
    }
    echo '</div>
    <div class="card-body">';
    $total_app = 0;
    $qty_app = 0;
    if(!empty($servis)){
        echo '<h4 class="text-center mb-2">--Service--</h4>
        <br>
        <div class="row">';
        foreach($servis as $s){
            $total_app += $s->biaya * $s->qty;
			$qty_app += $s->qty;
             echo ' <div class="col-md-8">
             <p>'. $s->qty .' &nbsp '. $s->nm_servis .'</p>
          </div>
          <div class="col-md-4">
           <p class="float-right">'. number_format($s->biaya * $s->qty,0) .'</p>
          </div>';
        }
        echo '</div>
                    
        <hr>';
    }		
    $total_produk = 0;
    $qty_produk = 0;
    if(!empty($produk)){
        echo '<h4 class="text-center mb-2">--Product--</h4>
        <br>
        <div class="row">';
        foreach($produk as $p){
            $total_produk += $p->harga * $p->jumlah; 
			$qty_produk += $p->jumlah;
            echo '<div class="col-md-8">
            <p>'. $p->jumlah.' &nbsp'. $p->nm_produk.'</p>
            </div>
            <div class="col-md-4">
            <p class="float-right">'. number_format($p->harga * $p->jumlah,0) .'</p>
            </div>';
        }
        echo '</div>';
    }
    
    echo '</div>
    <hr>
    <div class="card-footer">
        
        <div class="row">
            <div class="col-md-6">
                <p class="float-left">Total '. $qty_produk .' Product</p>
            </div>
            <div class="col-md-6">
                <p class="float-right">'. $total_produk .'</p>
            </div>
            <div class="col-md-6">
                <p class="float-left">Total '. $qty_app .' Service</p>
            </div>
            <div class="col-md-6">
                <p class="float-right">'. $total_app .'</p>
            </div>
            <div class="col-md-6">
                <p class="float-left">Total</p>
            </div>
            <div class="col-md-6">
            <p class="float-right">'. number_format($total_app + $total_produk).'</p>
            </div>
        </div>
        <hr>
        <div class="row">
            
            <div class="col-md-6">
                <p class="float-left">Cash</p>
            </div>
            <div class="col-md-6">
                <p class="float-right">'.number_format($invoice->cash,0).'</p>
            </div>

            <div class="col-md-6">
                <p class="float-left">Mandiri Kredit</p>
            </div>
            <div class="col-md-6">
                <p class="float-right">'.number_format($invoice->mandiri_kredit,0).'</p>
            </div>

            <div class="col-md-6">
                <p class="float-left">Mandiri Debit</p>
            </div>
            <div class="col-md-6">
                <p class="float-right">'.number_format($invoice->mandiri_debit,0).'</p>
            </div>

            <div class="col-md-6">
                <p class="float-left">BCA Kredit</p>
            </div>
            <div class="col-md-6">
                <p class="float-right">'.number_format($invoice->bca_kredit,0).'</p>
            </div>

            <div class="col-md-6">
                <p class="float-left">BCA Debit</p>
            </div>
            <div class="col-md-6">
                <p class="float-right">'.number_format($invoice->bca_debit,0).'</p>
            </div>

            <div class="col-md-6">
                <p class="float-left">Kembalian</p>
            </div>';
            $kembalian = $invoice->bayar - $invoice->total;
        echo'<div class="col-md-6">
                <p class="float-right">'.number_format($kembalian,0).'</p>
            </div>
        </div>

        </div>

        </div>';



}

public function dp(){
    $dt_dp = $this->db->query("SELECT tb_dp.*, inv.tgl_jam, tb_customer.nama 
    FROM tb_dp
    LEFT JOIN tb_customer ON tb_dp.id_customer = tb_customer.id_customer
    LEFT JOIN(SELECT kd_dp, tgl_jam FROM tb_invoice) inv ON tb_dp.kd_dp = inv.kd_dp
    group by tb_dp.kd_dp
    ORDER BY tb_dp.id_dp DESC
    ")->result();
    $data = array(
        'title'  => "Crepese Signature | Data DP",
        'customer' => $this->db->get('tb_customer')->result(),
        // 'dp' => $this->db->select('*, tb_dp.status as status, tb_dp.admin as admin')->join('tb_customer','tb_dp.id_customer = tb_customer.id_customer')->join('tb_invoice','tb_dp.kd_dp = tb_invoice.kd_dp')->get('tb_dp')->result(),
        'dp' => $dt_dp,
        'kas' => $this->db->where('id_kategori','1')->get('tb_akun')->result()
    );
     $this->load->view('dp/table', $data);
}
public function delete_dp(){
    $kd_dp = $this->input->get('kd_dp');
    
    $this->db->where('kd_dp',$kd_dp);
    $this->db->delete('tb_dp');
    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data berhasil dihapus!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/dp');
}



public function add_dp(){
    if (!empty($this->input->post('id_customer'))) 
    {
        $id_customer = $this->input->post('id_customer');
    }
    else
    {
        $customer = $this->input->post('customer');
        $data = array(
            'nama'  => $customer
        );
        $this->db->insert('tb_customer', $data);
        $id_customer = $this->db->insert_id();
    }

    $jumlah_dp = $this->input->post('dp');
    $metode = $this->input->post('metode');
    $tgl_dp = $this->input->post('tgl_dp');
    $tgl_input = date('Y-m-d H:i:s');
    $admin = $this->session->userdata('nm_user');    
    $kd_dp = 'DP'.date('ym', strtotime($tgl_input)) . strtoupper(random_string('alpha',3));

    $month = date('m' , strtotime($tgl_input));
        $year = date('Y' , strtotime($tgl_input));

    $data = [
        'kd_dp' => $kd_dp,
        'id_customer' => $id_customer,
        'jumlah_dp' => $jumlah_dp,
        'metode' => $metode,
        'tgl_input' => $tgl_input,
        'tgl_dp' => $tgl_dp,
        'admin' => $admin,
        'status' => 1,
        'ket' => $this->input->post('ket')
    ];

    $this->db->insert('tb_dp',$data);
    
    $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 5])->result()[0];
        $kode_akun = $this->db->where('id_akun', 5)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
        if($kode_akun == 0){
            $kode_akun = 1;
        }else{
            $kode_akun += 1;
        }
    $data_dp = [
        'id_buku' => 1,
        'id_akun' => 5,
        'debit' => 0,
        'kredit' => $jumlah_dp,
        'tgl' => date('Y-m-d'),
        'tgl_input' => date('Y-m-d H:i:s'),
        'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl_input)).'-'.$kode_akun,
        'kd_gabungan' => $kd_dp,
        'admin' => $admin,
        'ket' =>  $this->input->post('ket').'-'.$kd_dp
    ];

    $this->db->insert('tb_jurnal',$data_dp);


        if($metode == 'Cash'){
            $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 1])->result()[0];
            $kode_akun = $this->db->where('id_akun', 1)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
            if($kode_akun == 0){
                $kode_akun = 1;
            }else{
                $kode_akun += 1;
            }
            $data = [
                'id_buku' => 1,
                'id_akun' => 1,
                'debit' => $jumlah_dp,
                'kredit' => 0,
                'tgl' => date('Y-m-d'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl_input)).'-'.$kode_akun,
                'kd_gabungan' => $kd_dp,
                'admin' => $admin,
                'ket' => $this->input->post('ket').'-'.$kd_dp
            ];

            $this->db->insert('tb_jurnal',$data);
        }elseif($metode == 'BCA'){
            $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 2])->result()[0];
            $kode_akun = $this->db->where('id_akun', 2)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
            if($kode_akun == 0){
                $kode_akun = 1;
            }else{
                $kode_akun += 1;
            }
            $data = [
                'id_buku' => 1,
                'id_akun' => 2,
                'debit' => $jumlah_dp,
                'kredit' => 0,
                'tgl' => date('Y-m-d'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl_input)).'-'.$kode_akun,
                'kd_gabungan' => $kd_dp,
                'admin' => $admin,
                'ket' => $this->input->post('ket').'-'.$kd_dp
            ];

            $this->db->insert('tb_jurnal',$data);
        }else{
            $get_kd_akun = $this->db->get_where('tb_akun',['id_akun' => 3])->result()[0];
            $kode_akun = $this->db->where('id_akun', 3)->where('MONTH(tgl)', $month)->where('YEAR(tgl)',$year)->get('tb_jurnal')->num_rows();
            if($kode_akun == 0){
                $kode_akun = 1;
            }else{
                $kode_akun += 1;
            }
            $data = [
                'id_buku' => 1,
                'id_akun' => 3,
                'debit' => $jumlah_dp,
                'kredit' => 0,
                'tgl' => date('Y-m-d'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'no_nota' => $get_kd_akun->kd_akun.date('my' , strtotime($tgl_input)).'-'.$kode_akun,
                'kd_gabungan' => $kd_dp,
                'admin' => $admin,
                'ket' => $this->input->post('ket').'-'.$kd_dp
            ];

            $this->db->insert('tb_jurnal',$data);
        }
        
       

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/dp');
}

public function get_dp(){

    $id_dp = $this->input->post('id_dp');
    $dp = $this->db->get_where('tb_dp',['id_dp' => $id_dp])->result();
     $output = [];
     foreach($dp as $d){
        $output['kd_dp']= $d->kd_dp;  
        $output['id_customer']= $d->id_customer;
        $output['tgl_dp']= $d->tgl_dp;
        $output['kredit']= $d->jumlah_dp;   
        $output['metode']= $d->metode; 
     }   
     

     echo json_encode($output);

}

public function print_dp()
{
    $kd_dp = $this->input->get('kd_dp');

    $dt_dp = $this->db->join('tb_customer','tb_dp.id_customer = tb_customer.id_customer')->get_where('tb_dp',['kd_dp' => $kd_dp])->row();

    $data = [
        'dt_dp' => $dt_dp
    ];

    $this->load->view('dp/print',$data);
}

public function get_diskon(){

    $id_diskon = $this->input->post('id_diskon');
    $diskon = $this->db->get_where('tb_diskon',['id_diskon' => $id_diskon])->result();
     $output = [];
     foreach($diskon as $d){
        $output['id_diskon']= $d->id_diskon;  
        $output['jenis']= $d->jenis;
        $output['jumlah']= $d->jumlah;
     }   
     

     echo json_encode($output);

}


public function diskon(){
    $data = array(
        'title'  => "Crepese Signature | Data Diskon",

        'diskon' => $this->db->order_by('id_diskon','DESC')->get('tb_diskon')->result(),

        'voucher' => $this->db->order_by('id_voucher','DESC')->get('tb_voucher')->result(),
        'voucher_invoice' => $this->db->order_by('id_voucher','DESC')->get('tb_voucher_invoice')->result(),
    );
     $this->load->view('diskon/table', $data);
}

public function add_diskon(){
    $data = [
        'nm_diskon' => $this->input->post('nm_diskon'),
        'jenis' => $this->input->post('jenis'),
        'jumlah' => $this->input->post('jumlah'),
        'tgl_awal' => $this->input->post('tgl_awal'),
        'tgl_akhir' => $this->input->post('tgl_akhir'),
        'admin' => $this->session->userdata('nm_user'),
        'tgl' => date('Y-m-d')
    ];

    $this->db->insert('tb_diskon',$data);
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}

public function edit_diskon(){
    $data = [
        'nm_diskon' => $this->input->post('nm_diskon'),
        'jenis' => $this->input->post('jenis'),
        'jumlah' => $this->input->post('jumlah'),
        'tgl_awal' => $this->input->post('tgl_awal'),
        'tgl_akhir' => $this->input->post('tgl_akhir'),
        'admin' => $this->session->userdata('nm_user'),
        'tgl' => date('Y-m-d')
    ];

    $where = array('id_diskon' => $this->input->post('id_diskon'));
    $res  = $this->M_salon->UpdateData('tb_diskon', $data, $where);

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil diedit!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}

function drop_diskon($id_diskon){
    $where = array('id_diskon' => $id_diskon);
    $res = $this->M_salon->DropData('tb_diskon', $where);

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil diedit!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}

public function add_voucher(){
    // $kode = $this->input->post('kode');
    $no_voucher = 'VCR'. random_string('numeric',5);

    $data = [
        'no_voucher' => $no_voucher,
        // 'kode' => $this->input->post('kode'),
        'jenis' => $this->input->post('jenis'),
        'jumlah' => $this->input->post('jumlah'),
        'tgl_akhir' => $this->input->post('tgl_akhir'),
        'admin' => $this->session->userdata('nm_user'),
        'tgl_input' => date('Y-m-d'),
        'status' => 1,
        'ket' => $this->input->post('ket'),
    ];

    $this->db->insert('tb_voucher',$data);
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}

public function edit_voucher(){
    $id_voucher = $this->input->post('id_voucher');

    $data = [
        'jenis' => $this->input->post('jenis'),
        'jumlah' => $this->input->post('jumlah'),
        'tgl_akhir' => $this->input->post('tgl_akhir'),
        'ket' => $this->input->post('ket'),
        'status' => $this->input->post('status')
    ];

    $this->db->where('id_voucher',$id_voucher);
    $this->db->update('tb_voucher',$data);

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil diubah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}

public function drop_voucher($id_voucher){
    $this->db->where('id_voucher',$id_voucher);
    $this->db->delete('tb_voucher');

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil dihapus!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}


public function add_voucher_invoice(){
    // $kode = $this->input->post('kode');
    $no_voucher = 'VINV'. random_string('numeric',5);

    $data = [
        'no_voucher' => $no_voucher,
        // 'kode' => $this->input->post('kode'),
        'jenis' => $this->input->post('jenis'),
        'jumlah' => $this->input->post('jumlah'),
        'tgl_akhir' => $this->input->post('tgl_akhir'),
        'admin' => $this->session->userdata('nm_user'),
        'tgl_input' => date('Y-m-d'),
        'status' => 1,
        'ket' => $this->input->post('ket'),
    ];

    $this->db->insert('tb_voucher_invoice',$data);
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil ditambah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}

public function edit_voucher_invoice(){
    $id_voucher = $this->input->post('id_voucher');

    $data = [
        'jenis' => $this->input->post('jenis'),
        'jumlah' => $this->input->post('jumlah'),
        'tgl_akhir' => $this->input->post('tgl_akhir'),
        'ket' => $this->input->post('ket'),
        'status' => $this->input->post('status')
    ];

    $this->db->where('id_voucher',$id_voucher);
    $this->db->update('tb_voucher_invoice',$data);

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil diubah!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}


public function drop_voucher_invoice($id_voucher){
    $this->db->where('id_voucher',$id_voucher);
    $this->db->delete('tb_voucher_invoice');

    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Data berhasil dihapus!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect('Match/diskon');
}

public function cek_vcr_inv()
{
    $no_voucher = $this->input->get('no_voucher');
    $cek = $this->db->get_where('tb_voucher_invoice',['no_voucher' => $no_voucher])->row();

    if($cek){
        if($cek->status == 1){
            if($cek->tgl_akhir >= date('Y-m-d')){
                $data = [
                    'id_voucher' => $cek->id_voucher,
                    'jenis' => $cek->jenis,
                    'jumlah' => $cek->jumlah,
                    'status' => 'ada'
                ];  
            }else{
                $data = [
                    'status' => 'expired'
                ];
            }
        }else{
            $data = [
                'status' => 'terpakai'
            ];
        }
        
                 
        
    }else{
        $data = [
            'status' => 'kosong'
        ];
    }

    echo json_encode($data);
}


public function check_voucher()
{
    $no_voucher = $this->input->post('no_voucher');

    $get_voucher = $this->db->get_where('tb_voucher',[
        'no_voucher' => $no_voucher,
        'status' => 1
    ])->row();

    if($get_voucher){
        echo '
        <div class="row">
        <input type="hidden" name="id_voucher" value="'.$get_voucher->id_voucher.'" id="id_voucher">
            <div class="col-4">
                <label>No Vocher</label>
                <p>'.$get_voucher->no_voucher.'</p>			
            </div>
            <div class="col-4">
                <label>Diskon</label>';
                if($get_voucher->jenis == 1){
                    echo '<p>Rp '.number_format($get_voucher->jumlah,0).'</p>';
                }else{
                    echo '<p>'.$get_voucher->jumlah.'%</p>';
                }
                			
            echo'</div>
            <div class="col-4">
                <label>Keterangan</label>
                <p>'.$get_voucher->ket.'</p>			
            </div>				
        </div>
        ';
    }else {
        echo 'gagal';
    }

}

public function claim_voucher()
{
    $row_id = $this->input->post('id_cart');
    $price_cart = $this->input->post('price_cart');
    $id_voucher = $this->input->post('id_voucher');

    // echo $price_cart;

    $d_voucher = $this->db->get_where('tb_voucher',['id_voucher' => $id_voucher])->row();

    if($price_cart >= 0){
        if($d_voucher->jenis == 1){
            $harga = $price_cart - $d_voucher->jumlah;
        }else{
            $jml_d_voucher = $price_cart * $d_voucher->jumlah / 100;
            $harga = $price_cart - $jml_d_voucher;
        }
    
        $data = array(
            'rowid' => $row_id,
            'price'   => $harga
    );
    $this->cart->update($data);

    $off_voucher = [
        'status' => 0
    ];

    $this->db->where('id_voucher',$id_voucher);
    $this->db->update('tb_voucher',$off_voucher);
    
    echo  'success' ;
    }else {
        echo 'gagal';
    }

   

}






// ================================== ABSEN ==========================================

public function absen()
{
$names = ['T1', 'T2', 'T3','T4','T5','T6','T7','T8','T9','T10'];
if(empty($this->input->get('tgl'))){
    $today = date('Y-m-d');
}else{
    $today = $this->input->get('tgl');
}
 $data = array(
    'title'  => "Crepese Signature", 
    'anak'   => $this->db->where_not_in('nm_kry', $names)->get('tb_karyawan')->result(),
    'komisi'    => $this->M_salon->dt_kom(),
    'today' => $today
);
 $this->load->view('absen/tabel', $data);
}

public function absen2()
{
 $dt_a = date('Y-m-31');
 $dt_b = date('Y-m-26');
 $dt_c = date('Y-m-d', strtotime($dt_b.'-1 month'));
 $data  = array(
    'title'     => "Crepese Signature - Absen",
    'absen'     => $this->M_salon->absen(" where tgl BETWEEN '$dt_c' AND '$dt_a'"), 
    'd_nama'    => $this->M_salon->d_nama(),
    'komisi'    => $this->M_salon->dt_kom()

);
 $this->load->view('absen/edit', $data);
}

public function absen3()
{
  $dt_a = date('Y-m-31');
  $dt_b = date('Y-m-26');
  $dt_c = date('Y-m-d', strtotime($dt_b.'-1 month'));
  $data  = array(
    'title'     => "Absen ",
    'absen'     => $this->M_salon->absen(" where tgl BETWEEN '$dt_c' AND '$dt_a'"), 
    'd_nama'    => $this->M_salon->d_nama(),
    'komisi'    => $this->M_salon->dt_kom()

);
  $this->load->view('absen/detail', $data);
}

public function absenFilter()
{
    $data = [
        'title' => 'Absen',
        'karyawan' => $this->db->query("select * from tb_karyawan")->result(),
        'bulan' => $this->input->post('bulan'),
        'tahun' => $this->input->post('tahun'),
    ];
    $this->load->view('absen/detailFilter', $data);
}

function input_absen(){
    $nama = $this->input->post('nm_karyawan');
    $tgl  = $this->input->post('tgl');
    $cek = $this->db->get_where("tb_absen where nm_karyawan = '$nama' and tgl = '$tgl' ")->row();
    if (!empty($cek)) {
        echo "Data sudah ada!! Kembali....";
    }else{
        $data_input = array(
            'nm_karyawan' => $this->input->post('nm_karyawan'),
            'tgl'         => $this->input->post('tgl'),
            'ket'         => $this->input->post('ket'),
            'bulan'       => date('my'),
            'admin'       => $this->session->userdata('nm_user'),
            'tgl_input'   => date('Y-m-d')
        );
        $res = $this->M_salon->InputData('tb_absen', $data_input);
        $this->session->set_flashdata('message', '<div class="alert alert-primary" role="alert">Data Berhasil Di Tambah </div>');
        redirect("match/absen2");
    }
}

function input_absen3()
{       
    $nm = $this->uri->segment('3');
    $sp = $this->uri->segment('4');
    $tgl = $this->uri->segment('5');
    $data_input = array(
        'nm_karyawan' => $nm,
        'tgl'         => $tgl,
        'ket'         => $sp,
        'bulan'       => date('my'),
        'admin'       => $this->session->userdata('nm_user'),
        'tgl_input'   => date('Y-m-d')
    );
        // var_dump($data_input); exit;
    $res = $this->M_salon->InputData('tb_absen', $data_input);
    $this->session->set_flashdata('message', '<div class="alert alert-primary" role="alert">Data Berhasil Di Tambah </div>');
    redirect("match/absen?tgl=$tgl");
}

function update_absen3()
{
    $id = $this->uri->segment('3');
    $sp = $this->uri->segment('4');
    $tgl = $this->uri->segment('5');
    $data_update = array(
        'ket'   => $sp,
        'admin' => $this->session->userdata('nm_user')

    );
    $where = array( 'id_absen' => $id );
    $res = $this->M_salon->UpdateData('tb_absen', $data_update, $where);
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Data Berhasil Di Update </div>');
    redirect("match/absen?tgl=$tgl");
}

function update_absen(){
    $id_absen = $this->input->post('id_absen');
    $data_update = array(
        'nm_karyawan' => $this->input->post('nm_karyawan'),
        'ket'         => $this->input->post('ket'),
        'tgl'         => $this->input->post('tgl'),
        'tgl_input'   => date('Y-m-d')
    );
    $where = array( 'id_absen' => $id_absen );
    $res = $this->M_salon->UpdateData('tb_absen', $data_update, $where);
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Data Berhasil Di Update </div>');
    redirect("match/absen2");
}

function drop_absen3($id_absen)
{
    $tgl = $this->uri->segment('4');
    $where = array('id_absen' => $id_absen);
    $res = $this->M_salon->DropData('tb_absen', $where);
    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Berhasil Di Delete </div>');
    redirect("match/absen?tgl=$tgl");
}

function drop_absen2($id_absen)
{
    $where = array('id_absen' => $id_absen);
    $res = $this->M_salon->DropData('tb_absen', $where);
    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Berhasil Di Delete </div>');
    redirect("match/absen2");
}

// ================================== END ABSEN ==========================================

// ================================== DENDA ==========================================

public function denda(){
    if (!empty($this->session->userdata('tgl1'))) {
        $dt_a   = $this->session->userdata('tgl1');
        $dt_b   = $this->session->userdata('tgl2');
        $data = array(
            'title'  => "Crepese Signature", 
            'anak'   => $this->M_salon->ambil_anak(),
            'denda'  => $this->M_salon->dt_denda(" where tanggal BETWEEN '$dt_a' AND '$dt_b' "),
        );
    }else{
        $dt   = date('Y-m-d');
        $data = array(
            'title'  => "Crepese Signature", 
            'anak'   => $this->M_salon->ambil_anak(),
            'denda'  => $this->M_salon->dt_denda(" where tgl_input = '$dt' "),
        );
    }

    $this->load->view('denda/tabel', $data);
}

public function denda2(){
    $dt_a = $this->input->post('tgl1');
    $dt_b = $this->input->post('tgl2');
    $sesi = array(
        'tgl1' => $this->input->post('tgl1'), 
        'tgl2' => $this->input->post('tgl2'), 
    );
    $this->session->set_userdata($sesi);
    $data = array(
        'title'  => "Crepese Signature", 
        'anak'   => $this->M_salon->ambil_anak(),
        'denda'  => $this->M_salon->dt_denda(" where tanggal BETWEEN '$dt_a' AND '$dt_b' "),
    );
    $this->load->view('denda/tabel', $data);
}

function input_denda(){
    $data_input = array(
        'tanggal'   => $this->input->post('tanggal'),
        'id_kry_2'  => $this->input->post('id_kry_2'),
        'nm_denda'  => strtoupper($this->input->post('nm_denda')),
        'alasan'    => $this->input->post('alasan'),
        'nominal'   => $this->input->post('nominal'),
        'admin'     => $this->session->userdata('nm_user'),
        'tgl_input' => date('Y-m-d')
    );
    $res  = $this->M_salon->InputData('ctt_denda', $data_input);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/denda");
}

public function edit_denda($id_denda){
    $a = $this->M_salon->dt_denda(" where id_denda ='$id_denda' ");
    $data = array(
        "id_denda"   => $a[0]->id_denda,
        "id_kry_2"   => $a[0]->id_kry_2,
        "nm_denda"   => $a[0]->nm_denda,
        "tanggal"     => $a[0]->tanggal,
        "alasan"     => $a[0]->alasan,
        "nominal"    => $a[0]->nominal,
        'title'      => "Edit Denda", 
        'anak'       => $this->M_salon->ambil_anak()
    );
    $this->load->view('denda/form_edit', $data);
}

function update_denda(){
    $id_denda  = $this->input->post('id_denda');
    $data_input = array(
        'tanggal'   => $this->input->post('tanggal'),
        'id_kry_2'  => $this->input->post('id_kry_2'),
        'nm_denda'  => strtoupper($this->input->post('nm_denda')),
        'alasan'    => $this->input->post('alasan'),
        'nominal'   => $this->input->post('nominal')
    );
    $where = array('id_denda' => $id_denda);
    $res  = $this->M_salon->UpdateData('ctt_denda', $data_input, $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/denda");
}

function drop_denda($id_denda){
    $where = array('id_denda' => $id_denda);
    $res = $this->M_salon->DropData('ctt_denda', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Hapus !!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/denda");
}

function summary_denda(){
    $tgl1 = $this->input->post('tgl3');
    $tgl2 = $this->input->post('tgl4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'denda'     => $this->M_salon->summary_denda($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('denda/summary_export', $data);
}

function excel_denda_sum(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'denda'     => $this->M_salon->summary_denda($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('denda/excel_sum', $data);
}


// =============================== DENDA ==========================================

// ================================== SERVIS ==========================================

public function dt_servis()
{
  $data = array(
      'title'  => "Crepe Signature", 
      'kasbon' => $this->db->query("SELECT a.*, b.id_kategori, b.nm_kategori, c.harga as harga_offline, d.harga as hrga_online FROM tb_servis as a 
      left join tb_kategori as b on b.id_kategori = a.id_kategori
      left join tb_harga as c on c.id_servis = a.id_servis and c.distirbusi = '1' 
      left join tb_harga as d on d.id_servis = a.id_servis and d.distirbusi = '2' 
      order by a.id_servis DESC;")->result(),
      'bahan' => $this->db->get('tb_produk')->result(),
      'kategori' => $this->db->get_where('tb_kategori',['id_kategori !=' => '20'])->result()
  );
  $this->load->view('servis/tabel', $data);
}
public function dt_resep()
{
  $data = array(
      'title'  => "Crepe Signature", 
      'kasbon' => $this->db->query("SELECT a.id_produk,b.nm_produk, GROUP_CONCAT(c.nm_servis SEPARATOR ', ') AS nm_menu FROM `tb_resep` as a
      JOIN tb_produk as b on a.id_produk = b.id_produk
      JOIN tb_servis as c on a.id_servis = c.id_servis
      GROUP BY a.id_produk;")->result(),
  );
  $this->load->view('servis/resep', $data);
}

public function load_edit_resep()
{
    $id_produk = $this->input->get('id_produk');
    $produkOne = $this->db->query("SELECT a.nm_produk,b.satuan FROM `tb_produk` as a
    JOIN tb_satuan as b on a.id_satuan =b.id_satuan
    WHERE a.id_produk = $id_produk")->row();
    $data = [
        'title'  => "Crepe Signature", 
        'produk' => $this->db->query("SELECT a.id_resep,a.id_servis,a.id_produk,b.nm_servis,a.takaran FROM `tb_resep` as a
        JOIN tb_servis as b on a.id_servis = b.id_servis
        WHERE a.id_produk = $id_produk;")->result(),
        'produkOne' => $produkOne
    ];
  $this->load->view('servis/edit_resep', $data);
}

public function save_edit_resep()
{
    $takaran = $this->input->post('takaran');
    $id_produk = $this->input->post('id_produk');
    $id_servis = $this->input->post('id_servis');
    for ($i=0; $i < count($takaran); $i++) { 
        $this->db->where('id_servis', $id_servis[$i]);
        $this->db->where('id_produk', $id_produk[$i]);
        $this->db->update('tb_resep', ['takaran' => $takaran[$i]]);
    }
   $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Ubah !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/dt_resep");
}

public function add_servis()
{
   $data = array(
    'nm_servis'   => $this->input->post('servis'),
    'id_kategori'  => $this->input->post('id_kategori'),
    );
   $this->db->insert('tb_servis',$data);
   $inserted_id = $this->db->insert_id();

   $data = [
        'id_servis' => $inserted_id,
        'distirbusi' => '1',
        'harga' => $this->input->post('biaya_offline')
   ];
   $this->db->insert('tb_harga',$data);
   $data = [
        'id_servis' => $inserted_id,
        'distirbusi' => '2',
        'harga' => $this->input->post('biaya_online')
   ];
   $this->db->insert('tb_harga',$data);


   $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
   redirect("Match/dt_servis");
}

public function edit_servis()
{
    $id_servis  = $this->input->post('id_servis');
    $data_input = array(
        'nm_servis'   => $this->input->post('servis'),
        'id_kategori'  => $this->input->post('id_kategori'),
    );
    $this->db->where('id_servis', $this->input->post('id_servis'));
    $this->db->update('tb_servis',$data_input);

    $this->db->where('id_servis', $this->input->post('id_servis'));
    $this->db->delete('tb_harga');

    $data = [
        'id_servis' => $this->input->post('id_servis'),
        'distirbusi' => '1',
        'harga' => $this->input->post('biaya_offline')
   ];
   $this->db->insert('tb_harga',$data);
   $data = [
        'id_servis' => $this->input->post('id_servis'),
        'distirbusi' => '2',
        'harga' => $this->input->post('biaya_online')
   ];
   $this->db->insert('tb_harga',$data);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/dt_servis");
}

function drop_servis($id_servis){
    $where = array('id_servis' => $id_servis);
    $res = $this->M_salon->DropData('tb_servis', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Hapus !!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/dt_servis");
}

// =============================== END SERVIS ==========================================

//========================diskon servis============

public function diskon_servis(){
    $data = array(
        'title'  => "Diskon Service | Crepese Signature", 
        'diskon' => $this->db->order_by('id_diskon','DESC')->get('tb_diskon_servis')->result()
    );
    $this->load->view('diskon/diskon_servis', $data);
}

public function add_diskon_servis(){
    $data = [
        'jenis' => $this->input->post('jenis'),
        'jumlah' => $this->input->post('jumlah'),
        'admin' => $this->session->userdata('nm_user'),
        'tgl' => date('Y-m-d')
    ];

    $this->db->insert('tb_diskon_servis',$data);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
   redirect("Match/diskon_servis");
    
}

public function edit_diskon_servis(){
    $data = [
        'jenis' => $this->input->post('jenis'),
        'jumlah' => $this->input->post('jumlah'),
        'admin' => $this->session->userdata('nm_user'),
        'tgl' => date('Y-m-d')
    ];

    $id_diskon = $this->input->post('id_diskon');

    $this->db->where('id_diskon',$id_diskon);
    $this->db->update('tb_diskon_servis',$data);

    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Ubah !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
   redirect("Match/diskon_servis");
}

function drop_diskon_servis($id_diskon){
    $where = array('id_diskon' => $id_diskon);
    $res = $this->M_salon->DropData('tb_diskon_servis', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Hapus !!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/diskon_servis");
}


//===========================Bahan=============================

public function bahan()
{
  $data = array(
      'title'  => "Crepese Signature", 
      'bahan' => $this->db->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan','left')->get_where('tb_produk',['id_kategori' => '20'])->result(),
      'satuan' => $this->db->get('tb_satuan')->result()
  );
  $this->load->view('bahan/table', $data);
}
 
public function export_template_resep()
{
    $style_atas = array(
        'font' => [
            'bold' => true, // Mengatur teks menjadi tebal
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ]
        ],
    );

    $style = [
        'borders' => [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            ],
        ],
    ];
    $spreadsheet = new Spreadsheet();

    $spreadsheet->setActiveSheetIndex(0);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Resep');


    $kolom = [
        'A' => 'id resep',
        'B' => 'id menu',
        'C' => 'nama menu',
        'D' => 'id bahan',
        'E' => 'nama bahan',
        'F' => 'takaran',
        'G' => 'satuan',

        'J' => 'id bahan',
        'K' => 'nama bahan',
        'L' => 'satuan',
    ];
    // looping kolom
    foreach($kolom as $d => $i) {
        $sheet->setCellValue($d . '1', $i);
    }
    // looping bg warna merah
    foreach(['A1', 'B1', 'D1', 'F1'] as $d) {
        $sheet->getStyle($d)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
    }
    // border
    $sheet->getStyle("A1:G1")->applyFromArray($style_atas);
    $sheet->getStyle("J1:L1")->applyFromArray($style_atas);

    $resep = $this->db->query("SELECT a.id_resep,a.id_servis,a.id_produk,a.takaran,b.nm_servis,c.nm_produk,d.satuan FROM tb_resep as a
    JOIN tb_servis as b on a.id_servis = b.id_servis
    JOIN tb_produk as c on a.id_produk = c.id_produk
    JOIN tb_satuan as d on c.id_satuan = d.id_satuan")->result();
    $kolom = 2;
    foreach ($resep as $p) {
        $sheet->setCellValue('A' . $kolom, $p->id_resep);
        $sheet->setCellValue('B' . $kolom, $p->id_servis);
        $sheet->setCellValue('C' . $kolom, $p->nm_servis);
        $sheet->setCellValue('D' . $kolom, $p->id_produk);
        $sheet->setCellValue('E' . $kolom, $p->nm_produk);
        $sheet->setCellValue('F' . $kolom, $p->takaran);
        $sheet->setCellValue('G' . $kolom, $p->satuan);
        $kolom++;
    }
    $baris = count($resep) + 1;
    $sheet->getStyle('A2:G' . $baris)->applyFromArray($style);

    $produk = $this->db->query("SELECT a.id_produk,a.nm_produk,b.satuan FROM tb_produk as a
            JOIN tb_satuan as b on a.id_satuan = b.id_satuan
            WHERE a.id_kategori in (20,26);")->result();
    $kolom = 2;
    foreach ($produk as $p) {
        $sheet->setCellValue('J' . $kolom, $p->id_produk);
        $sheet->setCellValue('K' . $kolom, $p->nm_produk);
        $sheet->setCellValue('L' . $kolom, $p->satuan);
        $kolom++;
    }
    $baris = count($produk) + 1;
    $sheet->getStyle('J2:L' . $baris)->applyFromArray($style);


    $namafile = "Resep Bahan.xlsx";

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namafile);
    header('Cache-Control: max-age=0');


    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit();
}
public function import_resep()
{
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file']['tmp_name'];
        $spreadsheet = IOFactory::load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $admin = $this->session->userdata('nm_user');
        $tgl= date('Y-m-d');
        $this->db->query("UPDATE tb_resep SET hapus = 'Y', tgl_hapus = '$tgl', admin_hapus = '$admin'");

        try {
            foreach(array_slice($sheetData,1) as $row) {
                if (empty(array_filter($row))) {
                    continue;
                }

                $id_resep = $row['A'];
                $id_servis = $row['B'];
                $nm_menu = $row['C'];
                $id_produk = $row['D'];
                $takaran = $row['F'];

                // if(empty($id_resep)){
                //     $data = [
                //         'id_servis' => $id_servis,
                //         'id_produk' => $id_produk,
                //         'takaran' => $takaran,
                //         'hapus' => 'T',
                //         'tgl_hapus' => '',
                //         'admin_hapus' => ''
                //     ];
                //     $this->db->insert('tb_resep',$data);
                // } else {
                //     $this->db->where('id_servis', $id_servis);
                //     $this->db->where('id_produk', $id_produk);
                //     $this->db->update('tb_resep', [
                //         'takaran' => $takaran,
                //         'hapus' => 'T',
                //         'tgl_hapus' => '',
                //         'admin_hapus' => ''
                //     ]);
                // }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $pesan = "Gagal menyimpan data";
                throw new Exception($pesan);
            } else {
                $this->db->trans_commit();
                $pesan = "Data berhasil diimpor";
                $this->session->set_flashdata('message', $pesan);
                redirect('Match/dt_resep');
            }
        } catch (\Exception  $th) {
            $this->db->trans_rollback();
            $pesan_error = $e->getMessage();
            $this->session->set_flashdata('message', $pesan_error);
            redirect('Match/dt_resep');
        }
    } else {
        echo 'error';
    }
}

public function Export_bahan()
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
        'title'  => "Crepese Signature", 
        'produk' => $produk,
    );
  $this->load->view('bahan/export', $data);
    
}
public function add_bahan()
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
$sku = 'BHN'.$id_produk;

$data_sku = [
    'sku' => $sku
];

$this->db->where('id_produk', $id_produk);
$this->db->update('tb_produk', $data_sku);
   $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
   redirect("Bahan");
}

public function edit_bahan()
{
    $id_produk  = $this->input->post('id_produk');
    $data_input = array(
        'nm_produk'   => $this->input->post('nm_produk'),
        'id_satuan'  => $this->input->post('id_satuan'),
        'stok'   => $this->input->post('stok'),
        'harga'   => $this->input->post('harga')
    );
    $where = array('id_produk' => $id_produk);
    $res  = $this->M_salon->UpdateData('tb_produk', $data_input, $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/bahan");
}

function drop_bahan($id_produk){
    $where = array('id_produk' => $id_produk);
    $res = $this->M_salon->DropData('tb_produk', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Hapus !!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/bahan");
}

public function add_resep()
{
   $data_input = array(
    'id_servis'   => $this->input->post('id_servis'),
    'id_produk'  => $this->input->post('id_produk'),
    'takaran'  => $this->input->post('takaran')
);
   $this->M_salon->InputData('tb_resep', $data_input);
   echo 'berhasil';
}

public function get_input_resep(){
    $id_servis = $this->input->post('id_servis');
    $bahan = $this->db->get('tb_produk')->result();

    echo '
    
    <input type="text" id="id_servis_bahan" value="'.$id_servis.'" name="id_servis" hidden>
        <tr>
            <td width="50%">
                <select name="id_produk" id="id_produk" class="form-control select" required>
                    <option value="">Pilih Bahan</option>';
                    foreach($bahan as $b){
                        echo '<option value="'.$b->id_produk.'">'. $b->nm_produk.'</option>';
                    }
                    
                    
              echo'  </select>
            </td>
            <td width="30%">
                <input type="text" class="form-control" name="takaran" id="takaran" required>
            </td>
            <td>
                <input type="text" class="form-control" name="satuan" id="satuan" disabled>		
            </td>
            <td width="20%"><button type="submit" class="btn btn-sm btn-info">Tambah</button></td>
        </tr>
    
    ';


}

public function get_resep(){
    // $id = $_POST['id'];
    $id_servis = $this->input->post('id_servis');
    $resep = $this->db->join('tb_produk','tb_resep.id_produk = tb_produk.id_produk','left')->join('tb_satuan','tb_produk.id_satuan = tb_satuan.id_satuan')->get_where('tb_resep',['id_servis' => $id_servis])->result();
    $bahan = $this->db->where_in('id_kategori',['20','26'])->get('tb_produk')->result();
    foreach ($resep as $d) {
      
      echo "<input type='hidden' value='".$d->id_resep."' name='id_resep[]' >
      <tr>
      <td>
      <select name='id_produk[]' class='form-control select' required>";
      foreach($bahan as $b){
          if($b->id_produk == $d->id_produk){
            echo "<option value='". $b->id_produk ."' selected>". $b->nm_produk ."</option>";
          }else{
            echo "<option value='". $b->id_produk ."'>". $b->nm_produk ."</option>";
          }
        }
  echo "</select>
      </td>
      <td width='15%'>
      <input type='text' class='form-control' value='".$d->takaran."' name='takaran[]' required>
      </td>
      <td>
      <input type='text' class='form-control' value='".$d->satuan."' name='satuan' disabled>
      </td>
      <td width='20%'>";
      if($this->session->userdata('id_role')=='1'){
        echo "<button type='submit' class='btn btn-sm btn-info'>Ubah</button>
        <button type='button' class='btn btn-sm btn-danger hapus_resep' id='$d->id_resep'>Hapus</button>";
      }
      
      echo "</td>
      
  </tr>";
    }
}

public function get_btn_reseps(){
    $data_btn = $this->db->select("COUNT(tb_resep.id_resep) as jml , tb_servis.id_servis")->join('tb_resep','tb_servis.id_servis = tb_resep.id_servis','left')->group_by('tb_servis.id_servis')->get('tb_servis')->result();

    $output = [];
    //  foreach($data_btn as $r){
    //     $output['jml'] = $r->jml;
    //     $output['id_servis'] = $r->id_servis;
    //  }   
    //  echo json_encode($output);

    foreach($data_btn as $r){
        $output [] = [
            'jml' => $r->jml,
            'id_servis' => $r->id_servis
        ];
    }
    // var_dump($output);
    echo json_encode($output);
}

public function get_btn_resep(){
    $id_servis = $this->input->post('id_servis');
    $data_btn = $this->db->select("COUNT(tb_resep.id_resep) as jml , tb_servis.id_servis")->join('tb_resep','tb_servis.id_servis = tb_resep.id_servis','left')->group_by('tb_servis.id_servis')->get_where('tb_servis',['tb_servis.id_servis' => $id_servis])->row();

    $output = [];
     
        $output['jml'] = $r->jml;
        $output['id_servis'] = $r->id_servis;
       
     echo json_encode($output);
}

public function edit_resep()
{
    $id_resep  = $this->input->post('id_resep');
    $id_produk = $this->input->post('id_produk');
    $takaran = $this->input->post('takaran');
    
    for ($x=0; $x <count($id_resep) ; $x++) { 
        $data_input = array(
            'id_produk'   => $id_produk[$x],
            'takaran'  => $takaran[$x]
        );
        $where = array('id_resep' => $id_resep[$x]);
        $res  = $this->M_salon->UpdateData('tb_resep', $data_input, $where);
    }
    
    echo "berhasil";
}

public function get_satuan(){
    $id_produk = $this->input->post('id_produk');
    $satuan = $this->db->join('tb_produk','tb_satuan.id_satuan = tb_produk.id_satuan','left')->get_where('tb_satuan',['tb_produk.id_produk' => $id_produk])->result()[0];

    echo $satuan->satuan;
}

function drop_resep(){
    $id_resep = $this->input->post('id_resep');
    $where = array('id_resep' => $id_resep);
    $res = $this->M_salon->DropData('tb_resep', $where);
    echo "berhasil";
}


//==========================End Bahan=========================

// ================================== KASBON ==========================================

public function kasbon(){
    if (!empty($this->session->userdata('tgl1'))) {
        $dt_a   = $this->session->userdata('tgl1');
        $dt_b   = $this->session->userdata('tgl2');
        $data = array(
            'title'  => "Crepese Signature", 
            'anak'   => $this->M_salon->ambil_anak(),
            'kasbon' => $this->M_salon->dt_kasbon(" where tanggal BETWEEN '$dt_a' AND '$dt_b' "),
        );
    }else{
        $dt   = date('Y-m-d');
        $data = array(
            'title'  => "Crepese Signature", 
            'anak'   => $this->M_salon->ambil_anak(),
            'kasbon' => $this->M_salon->dt_kasbon(" where tgl_input = '$dt' "),
        );
    }

    $this->load->view('kasbon/tabel', $data);
}

public function kasbon2(){
    $dt_a = $this->input->post('tgl1');
    $dt_b = $this->input->post('tgl2');
    $sesi = array(
        'tgl1' => $this->input->post('tgl1'), 
        'tgl2' => $this->input->post('tgl2'), 
    );
    $this->session->set_userdata($sesi);
    $data = array(
        'title'  => "Crepese Signature", 
        'anak'   => $this->M_salon->ambil_anak(),
        'kasbon' => $this->M_salon->dt_kasbon(" where tanggal BETWEEN '$dt_a' AND '$dt_b' "),
    );
    $this->load->view('kasbon/tabel', $data);
}

function input_kasbon(){
    $data_input = array(
        'tanggal'   => $this->input->post('tanggal'),
        'id_kry_2'  => $this->input->post('id_kry_2'),
        'nm_kasbon' => strtoupper($this->input->post('nm_kasbon')),
        'nominal'   => $this->input->post('nominal'),
        'admin'     => $this->session->userdata('nm_user'),
        'tgl_input' => date('Y-m-d')
    );
    $res  = $this->M_salon->InputData('ctt_kasbon', $data_input);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/kasbon");
}

public function edit_kasbon($id_kasbon){
    $a = $this->M_salon->dt_kasbon(" where id_kasbon ='$id_kasbon' ");
    $data = array(
        "id_kasbon"   => $a[0]->id_kasbon,
        "id_kry_2"   => $a[0]->id_kry_2,
        "nm_kasbon"   => $a[0]->nm_kasbon,
        "tanggal"     => $a[0]->tanggal,
        "nominal"    => $a[0]->nominal,
        'title'      => "Edit kasbon", 
        'anak'       => $this->M_salon->ambil_anak()
    );
    $this->load->view('kasbon/form_edit', $data);
}

function update_kasbon(){
    $id_kasbon  = $this->input->post('id_kasbon');
    $data_input = array(
        'tanggal'   => $this->input->post('tanggal'),
        'id_kry_2'  => $this->input->post('id_kry_2'),
        'nm_kasbon'  => strtoupper($this->input->post('nm_kasbon')),
        'nominal'   => $this->input->post('nominal')
    );
    $where = array('id_kasbon' => $id_kasbon);
    $res  = $this->M_salon->UpdateData('ctt_kasbon', $data_input, $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/kasbon");
}

function drop_kasbon($id_kasbon){
    $where = array('id_kasbon' => $id_kasbon);
    $res = $this->M_salon->DropData('ctt_kasbon', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Hapus !!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/kasbon");
}

function detail_kasbon(){
    $tgl1 = $this->input->post('tgl1');
    $tgl2 = $this->input->post('tgl2');
    $data['kasbon'] = $this->M_salon->detail_kasbon($tgl1, $tgl2);

    $this->load->view('kasbon/detail_export', $data);
}

function summary_kasbon(){
    $tgl1 = $this->input->post('tgl3');
    $tgl2 = $this->input->post('tgl4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'kasbon'    => $this->M_salon->summary_kasbon($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('kasbon/summary_export', $data);
}

function excel_kasbon_sum(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'kasbon'    => $this->M_salon->summary_kasbon($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('kasbon/excel_sum', $data);
}

function excel_kasbon_det(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'kasbon'    => $this->M_salon->dt_kasbon(" where tanggal BETWEEN '$tgl1' AND '$tgl2' "),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('kasbon/excel_det', $data);
}

// =============================== KASBON ==========================================

// ================================== TIPS ==========================================

public function tips(){
    if (!empty($this->session->userdata('tgl1'))) {
        $dt_a   = $this->session->userdata('tgl1');
        $dt_b   = $this->session->userdata('tgl2');
        $data = array(
            'title'  => "Crepese Signature", 
            'anak'   => $this->M_salon->ambil_anak(),
            'tips'   => $this->M_salon->dt_tips(" where tanggal BETWEEN '$dt_a' AND '$dt_b' "),
        );
    }else{
        $dt   = date('Y-m-d');
        $data = array(
            'title'  => "Crepese Signature", 
            'anak'   => $this->M_salon->ambil_anak(),
            'tips'   => $this->M_salon->dt_tips(" where tgl_input = '$dt' "),
        );
    }

    $this->load->view('tips/tabel', $data);
}

public function tips2(){
    $dt_a = $this->input->post('tgl1');
    $dt_b = $this->input->post('tgl2');
    $sesi = array(
        'tgl1' => $this->input->post('tgl1'), 
        'tgl2' => $this->input->post('tgl2'), 
    );
    $this->session->set_userdata($sesi);
    $data = array(
        'title'  => "Crepese Signature", 
        'anak'   => $this->M_salon->ambil_anak(),
        'tips'   => $this->M_salon->dt_tips(" where tanggal BETWEEN '$dt_a' AND '$dt_b' "),
    );
    $this->load->view('tips/tabel', $data);
}

function input_tips(){
    $data_input = array(
        'tanggal'   => $this->input->post('tanggal'),
        'id_kry_2'  => $this->input->post('id_kry_2'),
        'nm_tips'  => strtoupper($this->input->post('nm_tips')),
        'nominal'   => $this->input->post('nominal'),
        'admin'     => $this->session->userdata('nm_user'),
        'tgl_input' => date('Y-m-d')
    );
    $res  = $this->M_salon->InputData('ctt_tips', $data_input);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/tips");
}

public function edit_tips($id_tips){
    $a = $this->M_salon->dt_tips(" where id_tips ='$id_tips' ");
    $data = array(
        "id_tips"   => $a[0]->id_tips,
        "id_kry_2"   => $a[0]->id_kry_2,
        "nm_tips"   => $a[0]->nm_tips,
        "tanggal"     => $a[0]->tanggal,
        "nominal"    => $a[0]->nominal,
        'title'      => "Edit tips", 
        'anak'       => $this->M_salon->ambil_anak()
    );
    $this->load->view('tips/form_edit', $data);
}

function update_tips(){
    $id_tips  = $this->input->post('id_tips');
    $data_input = array(
        'tanggal'   => $this->input->post('tanggal'),
        'id_kry_2'  => $this->input->post('id_kry_2'),
        'nm_tips'  => strtoupper($this->input->post('nm_tips')),
        'nominal'   => $this->input->post('nominal')
    );
    $where = array('id_tips' => $id_tips);
    $res  = $this->M_salon->UpdateData('ctt_tips', $data_input, $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/tips");
}

function drop_tips($id_tips){
    $where = array('id_tips' => $id_tips);
    $res = $this->M_salon->DropData('ctt_tips', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Hapus !!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect("Match/tips");
}

function summary_tips(){
    $tgl1 = $this->input->post('tgl3');
    $tgl2 = $this->input->post('tgl4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'tips'      => $this->M_salon->summary_tips($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('tips/summary_export', $data);
}

function excel_tips_sum(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'tips'      => $this->M_salon->summary_tips($tgl1, $tgl2),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('tips/excel_sum', $data);
}

function excel_tips_det(){
    $tgl1 = $this->uri->segment('3');
    $tgl2 = $this->uri->segment('4');
    $data = array(
        'tgl1'      => $tgl1,
        'tgl2'      => $tgl2,
        'tips'      => $this->M_salon->dt_tips(" where tanggal BETWEEN '$tgl1' AND '$tgl2' "),
        'sort'      => date('d-M-y', strtotime($tgl1))." ~ ".date('d-M-y', strtotime($tgl2))
    );

    $this->load->view('tips/excel_det', $data);
}


// =============================== TIPS ==========================================

// ================================== CANCEL ==========================================

public function cancel()
{
 if(empty($this->input->post('tgl1'))){
     $bulan = date('m');
     $year = date('Y');
     $data = array(
        'title'  => "Cancel | Crepese Signature", 
        'cancel'   => $this->M_salon->dt_cancel(" where MONTH(tb_cancel.tgl) = '$bulan' AND YEAR(tb_cancel.tgl) = '$year'"),
        // 'summary'   => $this->M_salon->dt_cancel_sum(),
    );
     $this->load->view('cancel/tabel', $data);

 }else{
    $dt_a   = $this->input->post('tgl1');
    $dt_b   = $this->input->post('tgl2');
    $data = array(
       'title'  => "Cancel | Crepese Signature", 
       'cancel'   => $this->M_salon->dt_cancel(" where tb_cancel.tgl BETWEEN '$dt_a' AND '$dt_b' "),
       // 'summary'   => $this->M_salon->dt_cancel_sum(),
   );
    $this->load->view('cancel/tabel', $data); 

 }   
//  $data = array(
//     'title'  => "Cancel | Crepese Signature", 
//     'cancel'   => $this->M_salon->dt_cancel(),
//     // 'summary'   => $this->M_salon->dt_cancel_sum(),
// );
//  $this->load->view('cancel/tabel', $data);
}

function add_cancel()
{
    $data_input = array(
        'tgl'    => $this->input->post('tgl'),
        'nama'    => $this->input->post('nama'),
        'telepon'    => $this->input->post('telp'),
        'ket'    => $this->input->post('ket')
    );
    $res  = $this->M_salon->InputData('tb_cancel', $data_input);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/cancel");
}

function edit_c($id_cancel)
{
   $data = array(
    'title'  => "Edit Cancel | Crepese Signature", 
    'detail'   => $this->db->get_where('tb_cancel', array('id_cancel' => $id_cancel))->row(),
    'summary'   => $this->M_salon->dt_cancel_sum(),
);
   $this->load->view('cancel/edit', $data);
}

function edit_cancel()
{
    $data_input = array(
        'tgl'    => $this->input->post('tgl'),
        'nama'    => $this->input->post('nama'),
        'telepon'    => $this->input->post('telp'),
        'ket'    => $this->input->post('ket')
    );
    $where = array('id_cancel' => $this->input->post('id_cancel'));
    $res  = $this->M_salon->UpdateData('tb_cancel', $data_input, $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/cancel");
}

function del_cancel($id_cancel)
{
  $app    = "Delete from tb_cancel WHERE id_cancel='$id_cancel'";
  $res1   = $this->db->query($app);
  $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
  redirect("Match/cancel");
}

function summary_cancel()
{
    $nama = $this->input->post('nama');
    $data = array(
        'nama'      => $nama,
        'data'      => $this->M_salon->summary_canel($nama)
    );

    $this->load->view('cancel/summary_cancel', $data);
}

// =============================== CANCEL ==========================================

// ================================== CUSTOMER ==========================================

public function customer()
{
 $data = array(
    'title'  => "Customer | Crepese Signature", 
    'data'   => $this->M_salon->dt_customer(),
    'summary'   => $this->M_salon->dt_customer_sum(),
);
 $this->load->view('customer/tabel', $data);
}

function add_customer()
{
    $data_input = array(
        'nama'    => $this->input->post('nama'),
        'tanggal_lahir'    => $this->input->post('tgl_lahir'),
        'email'    => $this->input->post('email'),
        'telepon'    => $this->input->post('telp'),
        'alamat'    => $this->input->post('alamat')
    );
    $res  = $this->M_salon->InputData('tb_customer', $data_input);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/customer");
}

function edit_cus($id_customer)
{
   $data = array(
    'title'  => "Edit Customer | Crepese Signature", 
    'detail'   => $this->db->get_where('tb_customer', array('id_customer' => $id_customer))->row(),
    'summary'   => $this->M_salon->dt_customer_sum(),
);
   $this->load->view('customer/edit', $data);
}

function edit_customer()
{
   $data_input = array(
    'nama'    => $this->input->post('nama'),
    'tanggal_lahir'    => $this->input->post('tgl_lahir'),
    'email'    => $this->input->post('email'),
    'telepon'    => $this->input->post('telp'),
    'alamat'    => $this->input->post('alamat')
);
   $where = array('id_customer' => $this->input->post('id_customer'));
   $res  = $this->M_salon->UpdateData('tb_customer', $data_input, $where);
   $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
   redirect("Match/customer");
}

function del_customer($id_customer)
{
  $app    = "Delete from tb_customer WHERE id_customer='$id_customer'";
  $res1   = $this->db->query($app);
  $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
  redirect("Match/customer");
}

function f_customer()
{
   $f = $this->input->get('abj');
   if ($f=="semua")
   {
    redirect("Match/customer");
}
else
{
  $app    = "SELECT * FROM tb_customer WHERE nama LIKE '$f%'";
  $res1   = $this->db->query($app);
  $data = array(
    'title'  => "Customer | Crepese Signature", 
    'data'   => $res1->result(),
    'summary'   => $this->M_salon->dt_customer_sum(),
);
  $this->load->view('customer/filter', $data);
}

}

// =============================== CUSTOMER ==========================================

// ================================== DATA KARYAWAN ==========================================
public function dt_anak(){
    $data = array(
        'title' => "Data Karyawan", 
        'karyawan'  => $this->db->query("SELECT * FROM tb_karyawan as a 
        left join tb_tipe as b on b.id_tipe = a.id_tipe
        left join tb_kelompok_tunjangan as c on c.id_posisi = a.id_posisi
        order by a.id_kry DESC
        ")->result(), 
        'posisi' => $this->db->get('tb_kelompok_tunjangan')->result()
    );
    $this->load->view('anak_laki/tabel', $data);
}

function input_kry(){
    $data_input = array(
        'nm_kry'    => strtoupper($this->input->post('nm_kry')),
        'tgl_masuk' => $this->input->post('tgl_masuk')
    );
    $res  = $this->M_salon->InputData('tb_karyawan', $data_input);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Input !! <div class="ml-5 btn btn-sm"><i class="fas fa-cloud-download-alt fa-2x"></i></div></div>');
    redirect("Match/dt_anak");
}

function update_kry(){
    $id_kry  = $this->input->post('id_kry');
    $data_input = array(
        'nm_kry'    => strtoupper($this->input->post('nm_kry')),
        'tgl_masuk' => $this->input->post('tgl_masuk')
    );
    $where = array('id_kry' => $id_kry);
    $res  = $this->M_salon->UpdateData('tb_karyawan', $data_input, $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Update !!  <div class="ml-5 btn btn-sm"><i class="fas fa-sync-alt fa-2x"></i></div></div>');
    redirect("Match/dt_anak");
}

function drop_kry($id_kry){
    $where = array('id_kry' => $id_kry);
    $res = $this->M_salon->DropData('tb_karyawan', $where);
    $this->session->set_flashdata('message', '<div style="background-color: #FFA07A;" class="alert" role="alert">Data Berhasil Di Hapus !!  <div class="ml-5 btn btn-sm"><i class="fas fa-times-circle fa-2x"></i></div></div>');
    redirect('Match/dt_anak');
}
// ================================== END DATA KARYAWAN ==========================================

// ================================================  HAK AKSES ===========================================
function input_role(){
    $data = array(
        'nm_role'     => $this->input->post('nm_role')
    );

    $res = $this->M_salon->InputData('tb_role', $data);     
    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role Berhasil Di Tambah </div>');
    redirect('Match/dt_user');
}

function hak_edit($id_role){
    $a = $this->M_salon->dt_role(" where id_role ='$id_role'");
    $data = array(
        "edit_hapus"  => $a[0]->edit_hapus
    );
            // var_dump($data['edit_hapus']); exit;
    if ($data['edit_hapus'] == '1') {
        $sql = "UPDATE `tb_role` SET `edit_hapus` = '0' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }else{
        $sql = "UPDATE `tb_role` SET `edit_hapus` = '1' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Hak Akses Berhasil di Update !!</div>');
    redirect("Match/dt_user");
}

function hak_input($id_role){
    $a = $this->M_salon->dt_role(" where id_role ='$id_role'");
    $data = array(
        "input"  => $a[0]->input
    );
            // var_dump($data['input']); exit;
    if ($data['input'] == '1') {
        $sql = "UPDATE `tb_role` SET `input` = '0' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }else{
        $sql = "UPDATE `tb_role` SET `input` = '1' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Hak Akses Berhasil di Update !!</div>');
    redirect("Match/dt_user");
}

function hak_edit2($id_role){
    $a = $this->M_salon->dt_role(" where id_role ='$id_role'");
    $data = array(
        "i_grade"  => $a[0]->i_grade
    );
            // var_dump($data['i_grade']); exit;
    if ($data['i_grade'] == '1') {
        $sql = "UPDATE `tb_role` SET `i_grade` = '0' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }else{
        $sql = "UPDATE `tb_role` SET `i_grade` = '1' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Hak Akses Berhasil di Update !!</div>');
    redirect("Match/dt_user");
}

function hak_input2($id_role){
    $a = $this->M_salon->dt_role(" where id_role ='$id_role'");
    $data = array(
        "e_grade"  => $a[0]->e_grade
    );
            // var_dump($data['e_grade']); exit;
    if ($data['e_grade'] == '1') {
        $sql = "UPDATE `tb_role` SET `e_grade` = '0' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }else{
        $sql = "UPDATE `tb_role` SET `e_grade` = '1' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Hak Akses Berhasil di Update !!</div>');
    redirect("Match/dt_user");
}

function hak_gudang($id_role){
    $a = $this->M_salon->dt_role(" where id_role ='$id_role'");
    $data = array(
        "gudang"  => $a[0]->gudang
    );
            // var_dump($data['gudang']); exit;
    if ($data['gudang'] == '1') {
        $sql = "UPDATE `tb_role` SET `gudang` = '0' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }else{
        $sql = "UPDATE `tb_role` SET `gudang` = '1' WHERE `tb_role`.`id_role` = '$id_role'";
        $res = $this->db->query($sql);
    }
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Hak Akses Berhasil di Update !!</div>');
    redirect("Match/dt_user");
}
    // ================================================ END HAK AKSES ===========================================

// ================================================ USER ==================================================================

public function dt_user(){
    $data = array(
        'title' => "Data User", 
        'user'  => $this->M_salon->dt_user(" order by kd_user DESC"),
        'hak'   => $this->M_salon->dt_role(),
        'menu' => $this->db->get('tb_menu')->result(),
        'sub_menu' => $this->db->get('tb_sub_menu')->result()

    );
    $this->load->view('user/tb_user', $data);
}

function input_user(){
    $this->form_validation->set_rules('username','Name','required|trim|is_unique[tb_user.username]',[
        'is_unique'=> 'Akun dengan username ini sudah terdaftar',
        'required'=> 'Username tidak boleh kosong!'
    ]);
    if($this->form_validation->run() == false){
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data Username sudah terdaftar</div>');
		redirect ('Match/dt_user');
		}else{
            $nm_user  = $this->input->post('nm_user',true);
            $username = $this->input->post('username',true);
            $password = password_hash($this->input->post('password',true), PASSWORD_DEFAULT);
            $id_role  = $this->input->post('id_role',true);
            $aktif    = 1;

            $data_input = array(
                'nm_user'     => $nm_user,
                'username'    => $username,
                'password'    => $password,
                'id_role'     => $id_role,
                'aktif'       => $aktif,
                'tgl_masuk'   => date('Y-m-d')
            );
                // var_dump($data_input); exit;
            // $res = $this->M_salon->InputData('tb_user', $data_input);

            $this->db->insert('tb_user', $data_input);

            $id_user = $this->db->insert_id();
            $permission =  $this->input->post('permission');

            for($count = 0; $count<count($permission); $count++){
            $data_permission = [
                'id_user' => $id_user,
                'permission' => $permission[$count]
            ];

            $this->db->insert('tb_permission',$data_permission);
            }  
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengguna Baru Berhasil Di Tambah</div>');
            redirect ('Match/dt_user');
        }

    
}

function edit_user($kd_user){
    $a = $this->M_salon->dt_user("where kd_user ='$kd_user'");
    $data = array(
        "kd_user"   => $a[0]->kd_user,
        "nm_user"   => $a[0]->nm_user,
        "username"  => $a[0]->username,
        "password"  => $a[0]->password,
        "tgl_masuk" => $a[0]->tgl_masuk,
        "id_role"   => $a[0]->id_role,
        "nm_role"   => $a[0]->nm_role,
        "aktif"     => $a[0]->aktif,
        'title'     => "Edit User", 
        'role'      => $this->M_salon->dt_role()
    );

    $this->load->view('tema/Header', $data);
    $this->load->view('user/edit_user', $data);
    $this->load->view('tema/Footer');
}

public function get_permission(){
    $id_user = $this->input->post('kd_user');
  
    $data_permission = $this->db->select('permission')->get_where('tb_permission',['id_user' => $id_user])->result_array();

    $menu = $this->db->get('tb_menu')->result();
    $sub_menu = $this->db->get('tb_sub_menu')->result();
    
    $permission = [];
    foreach($data_permission as $d){
      $permission [] = $d['permission'];
    }
    
    foreach($menu as $mn){
        echo'
        <div class="form-group col-md-12">
        <label class="form-check-label"><strong>'.$mn->menu.'</strong></label>
        </div>
      ';
        foreach($sub_menu as $smn){
            if($mn->id_menu == $smn->id_menu){
                echo'<div class="form-group col-md-4">
                <div class="form-check">';
                if(in_array($smn->id_sub_menu, $permission)){
                    echo '<input class="form-check-input" type="checkbox" name="permission[]" value="'.$smn->id_sub_menu.'" checked>';
                }else{
                    echo '<input class="form-check-input" type="checkbox" name="permission[]" value="'.$smn->id_sub_menu.'">';
                }
                echo '<label class="form-check-label">'.$smn->sub_menu.'</label>
                </div>
                </div>';
            }
        }
        echo'<br><br><br>';
    }

    // echo var_dump($permission);
    // for($i=1; $i<=14; $i++){
    //     echo '<div class="form-group col-md-2">
    //     <div class="form-check">';
    //     if(in_array($i, $permission)){
    //         echo ' <input class="form-check-input" type="checkbox" name="permission[]" value="'.$i.'" checked>';
    //     }else{
    //         echo ' <input class="form-check-input" type="checkbox" name="permission[]" value="'.$i.'">';
    //     }
    //       echo '<label class="form-check-label">'.$i.'</label>
    //     </div>
    //     </div>';
    // }

  }

function update_user(){
    $kd_user = $this->input->post('kd_user');
    $data_update = array(
        'nm_user'   => $this->input->post('nm_user',true),
        // 'username'  => $this->input->post('username',true),
        // 'password'  => password_hash($this->input->post('password',true), PASSWORD_DEFAULT),
        // 'tgl_masuk' => $this->input->post('tgl_masuk',true),
        'id_role'   => $this->input->post('id_role',true),
        'aktif'     => $this->input->post('aktif',true)
        // 'email'     => $this->input->post('email',true)
    );

    $where = array('kd_user' => $kd_user);
    $res = $this->M_salon->UpdateData('tb_user', $data_update, $where);
    
    // $this->db->where('id_user',$kd_user);
    // $this->db->delete('tb_permission');

    // $permission =  $this->input->post('permission');

    // for($count = 0; $count<count($permission); $count++){
    //   $data_permission = [
    //     'id_user' => $kd_user,
    //     'permission' => $permission[$count]
    //   ];

    //   $this->db->insert('tb_permission',$data_permission);
    // }
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">User Berhasil Di Update </div>');
    redirect('Match/dt_user');
}

public function update_permission(){
    $kd_user = $this->input->post('kd_user');
    $permission =  $this->input->post('permission');

    $this->db->where('id_user',$kd_user);
    $this->db->delete('tb_permission');

    

    for($count = 0; $count<count($permission); $count++){
      $data_permission = [
        'id_user' => $kd_user,
        'permission' => $permission[$count]
      ];

      $this->db->insert('tb_permission',$data_permission);
    }
    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Akses Berhasil Diupdate </div>');
    redirect('Match/dt_user');
}

function drop_user($kd_user){
    $where = array('kd_user' => $kd_user);
    $res = $this->M_salon->DropData('tb_user', $where);
    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Pengguna Berhasil Di Hapus !!</div>');
    redirect('Match/dt_user');
}

// ================================================ END USER ==================================================================

// ================================================ EXTRA ==================================================================
public function cari_anak(){
    $nm_kry = $_GET['nm_kry'];
    $cari =$this->M_salon->cari_anak($nm_kry)->result();
    echo json_encode($cari);
}
// ================================================ END EXTRA ==================================================================
public function tambah_karywan_new()
{
    $data = [
        'nm_kry' => $this->input->post('nama'),
        'tgl_masuk' => $this->input->post('tgl'),
        'id_posisi' => $this->input->post('id_posisi'),
        'id_tipe' => '1'
    ];
    $this->db->insert('tb_karyawan',$data);

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data Karywan Berhasil Ditambahkan</div>');
    redirect('Match/dt_anak');
}

public function get_edit_karywan()
{
    $id_karywan = $this->input->get('id_karyawan');

    
    $kry = $this->db->query("SELECT * FROM tb_karyawan as a 
    where a.id_kry = '$id_karywan'
    order by a.id_kry DESC
    ")->row();

    $data = [
        'karywan' => $kry,
        'posisi' => $this->db->get('tb_kelompok_tunjangan')->result()
    ];
    
    $this->load->view('anak_laki/get_edit', $data);

}

public function edit_karywan_new()
{
    $data = [
        'nm_kry' => $this->input->post('nama'),
        'tgl_masuk' => $this->input->post('tgl'),
        'id_posisi' => $this->input->post('id_posisi'),
    ];
    $this->db->where('id_kry',$this->input->post('id_kry'));
    $this->db->update('tb_karyawan',$data);

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data Karywan Berhasil Ditambahkan</div>');
    redirect('Match/dt_anak');
}

}