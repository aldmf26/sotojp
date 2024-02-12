<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Diskon extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function index()
    {
        $data = array(
            'title' => "Diskon Item",
            'data_diskon' => $this->db->query("SELECT b.nm_servis, a.*
            FROM diskon_item as a 
            left join tb_servis as b on b.id_servis = a.id_servis;")->result(),
            'menu' => $this->db->get('tb_servis')->result()
        );
        $this->load->view('diskon/index', $data);
    }

    function add_diskon()
    {
        $id_servis = $this->input->post('id_servis');

        for ($x = 0; $x < count($id_servis); $x++) {
            $data = [
                'id_servis' => $id_servis[$x],
                'id_distribusi' => $this->input->post('distribusi'),
                'diskon' => $this->input->post('jumlah'),
                'start_date' => $this->input->post('dari'),
                'finish_date' => $this->input->post('sampai'),
            ];
            $this->db->insert('diskon_item', $data);
        }

        redirect('Diskon');
    }
}
