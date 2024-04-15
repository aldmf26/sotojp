<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher_void extends CI_Controller
{
    public function index()
    {
        $data = [
            'title'  => "Voucher Void",
            'voucher' => $this->db->get('voucher_void')->result()
        ];
        $this->load->view('voucher/index', $data);
    }

    public function tambah_voucher()
    {
        $this->load->helper('string');
        $random_string = random_string('alpha', 6);
        $kode = strtoupper('CRP-' . $random_string);

        $this->db->insert('voucher_void', ['voucher' => $kode, 'terpakai' => 'T']);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Voucher berhasil ditambahkan<div class="ml-5 btn btn-sm"></div></div>');
        redirect("Voucher_void");
    }
}
