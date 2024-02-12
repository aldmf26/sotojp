<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TestApi extends CI_Controller
{


    public function index()
    {
        $data = $this->db->get('tb_produk')->result_array();
        echo json_encode($data);
    }
    public function apites()
    {
        $data = array(
            'tes' => $this->input->post('tes'),
        );

        $this->db->insert('api_tes', $data);
    }
}
