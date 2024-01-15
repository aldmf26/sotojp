<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model('M_invoice');
    }

    public function loadTabel()
    {
        $cek = ['13', '20'];
        $data = array(
            'title'  => "Orchard Produk",
            'produk'   => $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left')->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left')->where_not_in('tb_produk.id_kategori', $cek)->get('tb_produk')->result(),
            'kategori'    => $this->db->get('tb_kategori')->result(),
            'satuan'    => $this->db->get('tb_satuan')->result(),
        );
        $this->load->view('produk/loadTabel', $data);
    }

    public function search_produk()
    {
        $kategori = '0';
        $keyword = '';

        if ($this->input->post('keyword')) {
            $keyword = $this->input->post('keyword');
        }

        if ($this->input->post('kategori') !== null) {
            $kategori = $this->input->post('kategori');
        }

        if (empty($keyword) && $kategori == '0') {

            $load = $this->M_salon->search_produk_2();
        } else {

            $load = $this->M_salon->search_produk($keyword, $kategori);
        }

        $data = [
            'data' => $load
        ];

        $this->load->view('produknew/search_produk', $data);
    }

    public function get_cart()
    {
        $allCartItems = $this->cart->contents();
        $productItems = array_filter($allCartItems, function ($item) {
            return isset($item['kategori']) && $item['kategori'] == 'product';
        });
        $data = [
            'cart_content' => $productItems
        ];
        $this->load->view('produknew/get_cart', $data);
    }

    function hapus_cart()
    {
        $this->cart->destroy();
    }

    public function cart()
    {
        $id_produk = $this->input->post('id_produk');
        $jumlah = $this->input->post('jumlah');
        $satuan = $this->input->post('satuan');
        $catatan = $this->input->post('catatan');
        $id_toping = $this->input->post('id_toping');
        $qty_toping = $this->input->post('qty_toping');

        $detail = $this->db->get_where('tb_servis', array('id_servis' => $id_produk))->row();

        $harga = $detail->biaya;

        $data = array(
            'id'      => $id_produk,
            'id_produk' => $id_produk,
            'qty'     => $jumlah,
            'type'    => 'barang',
            'price'   => $harga,
            'name'    => preg_replace("/[^a-zA-Z0-9]/", " ", $detail->nm_servis),
            'picture' => $detail->foto,
            'satuan'  => 'Pcs',
            'catatan' => $catatan,
            'kategori' => 'product'
        );
        $this->cart->insert($data);

        for ($x = 0; $x < count($id_toping); $x++) {
            $toping = $this->db->get_where('tb_produk', ['id_produk' => $id_toping[$x]])->row();
            if ($qty_toping[$x] == 0) {
                # code...
            } else {
                $data = array(
                    'id'      => $id_toping[$x],
                    'id_produk' => $toping->id_produk,
                    'qty'     => $qty_toping[$x],
                    'type'    => 'barang',
                    'price'   => $toping->harga,
                    'name'    => preg_replace("/[^a-zA-Z0-9]/", " ", $toping->nm_produk),
                    'picture' => $toping->foto,
                    'satuan'  => 'Pcs',
                    'kategori' => 'toping',
                    'ibu' => $id_produk
                );
                $this->cart->insert($data);
            }
        }
    }

    public function payment()
    {

        $now = date('Y-m-d');
        $dsk = $this->db->where('tgl_awal <=', $now)->where('tgl_akhir >=', $now)->get('tb_diskon')->result();
        if (!empty($dsk)) {
            $diskon = $dsk;
        } else {
            $diskon = null;
        }

        $allCartItems = $this->cart->contents();
        $productItems = array_filter($allCartItems, function ($item) {
            return isset($item['kategori']) && $item['kategori'] == 'product';
        });
        $data = array(
            'title'  => "Crepe Payment Order Produk",
            'dp' => $this->db->join('tb_customer', 'tb_dp.id_customer = tb_customer.id_customer')->get_where('tb_dp', ['status' => '1'])->result(),
            'diskon' => $diskon,
            'customer' => $this->db->get('tb_customer')->result(),
            'cart' => $productItems
        );
        $this->load->view('order/payment', $data);
    }

    public function checkout()
    {
        $no_invoice = $this->M_invoice->get_no_invoice();
        $i_invoice = $this->M_invoice->simpan_invoice($no_invoice);

        $no_nota = 'CRP' . $no_invoice;
        $cash = $this->input->post('cash');
        $bca_kredit = $this->input->post('bca_kredit');
        $bca_debit = $this->input->post('bca_debit');
        $mandiri_kredit = $this->input->post('mandiri_kredit');
        $mandiri_debit = $this->input->post('mandiri_debit');
        $shoope = $this->input->post('shoope');
        $tokopedia = $this->input->post('tokopedia');
        $id_diskon = $this->input->post('id_diskon');
        $nominal_voucher = $this->input->post('nominal_voucher');

        $diskon = $this->input->post('diskon');

        $total = $this->input->post('total');

        $id_produk = $this->input->post('id_produk');
        $qty = $this->input->post('qty');
        $allCartItems = $this->cart->contents();
        $productItems = array_filter($allCartItems, function ($item) {
            return isset($item['kategori']) && $item['kategori'] == 'product';
        });
        $keranjang  = $productItems;

        $tanggal = date('Y-m-d');
        $admin = $this->session->userdata('nm_user');


        foreach ($keranjang as $key => $value) {
            $servis = $this->db->get_where('tb_resep', ['id_servis' => $value['id']])->result();
            $allCartItems = $this->cart->contents();
            // Filter item yang memiliki properti 'ibu' sesuai dengan 'id_produk' saat ini
            $productItems = array_filter($allCartItems, function ($item) use ($value) {
                return isset($item['ibu']) && $item['ibu'] == $value['id_produk'];
            });
            $toping = $productItems;
            foreach ($servis as $s) {
                $data = [
                    'kode_stok_produk' => $no_nota,
                    'id_produk' => $s->id_produk,
                    'debit' => '0',
                    'kredit' => $value['qty'] * $s->takaran,
                    'tgl' => date('Y-m-d'),
                    'tgl_input' => date('Y-m-d'),
                    'admin' => $admin,
                    'jenis' => 'Penjualan'
                ];
                $this->db->insert('tb_stok_produk', $data);
            }
            $subharga = $value['qty'] * $value['price'];
            $data_pembelian = array(
                'no_nota' => $no_nota,
                'id_produk'   => $value['id'],
                'tanggal'   => $tanggal,
                'jumlah'        => $value['qty'],
                'harga'  => $value['price'],
                'total'      => $subharga,
                'admin'  => $admin,
                'kategori' => 'product'
            );
            $this->db->insert('tb_pembelian', $data_pembelian);
            foreach ($toping as $t) {
                $data = [
                    'kode_stok_produk' => $no_nota,
                    'id_produk' => $t['id_produk'],
                    'debit' => '0',
                    'kredit' => $t['qty'],
                    'tgl' => date('Y-m-d'),
                    'tgl_input' => date('Y-m-d'),
                    'admin' => $admin,
                    'jenis' => 'Penjualan'
                ];
                $this->db->insert('tb_stok_produk', $data);
                $subharga = $t['qty'] * $t['price'];
                $data_pembelian = array(
                    'no_nota' => $no_nota,
                    'id_produk'   => $t['id'],
                    'tanggal'   => $tanggal,
                    'jumlah'        => $t['qty'],
                    'harga'  => $t['price'],
                    'total'      => $subharga,
                    'admin'  => $admin,
                    'kategori' => 'toping'
                );
                $this->db->insert('tb_pembelian', $data_pembelian);
            }
        }

        $bayar = $cash + $bca_kredit + $bca_debit + $shoope + $mandiri_kredit + $mandiri_debit;

        $ttl = $total - $diskon + $nominal_voucher;

        if ($ttl < 0) {
            $ttl_input = 0;
        } else {
            $ttl_input = $ttl;
        }


        $data = [
            'no_nota' => $no_nota,
            'total' => $ttl_input,
            'bayar' => $bayar,
            'kembali' => $bayar - $ttl_input,
            'cash' => $cash,
            'mandiri_kredit' => $mandiri_kredit,
            'mandiri_debit' => $mandiri_debit,
            'bca_debit' => $bca_debit,
            'bca_kredit' => $bca_kredit,
            'gopay' => $shoope,
            'diskon' => $diskon,
            'nominal_voucher' => $nominal_voucher,
            'tgl_jam' => $tanggal,
            'admin' => $admin,
            'status' => 0,

        ];
        $this->db->insert('tb_invoice', $data);


        $id_voucher = $this->input->post('id_voucher');

        if (empty($id_voucher)) {
            # code...
        } else {
            $data_off_voucher = [
                'tgl_pakai' => date('Y-m-d'),
                'status' => 0
            ];
            $this->db->where('id_voucher', $id_voucher);
            $this->db->update('tb_voucher_invoice', $data_off_voucher);
        }

        $this->cart->destroy();
        $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">Proses pembelian berhasil!<div class="ml-5 btn btn-sm"></div></div>');
        redirect(base_url("produk/detail_invoice?invoice=$no_nota"), 'refresh');
    }

    public function detail_invoice()
    {
        $no_nota = $this->input->get('invoice');

        $invoice = $this->db->join('tb_customer', 'tb_invoice.id_customer = tb_customer.id_customer', 'left')->get_where('tb_invoice', [
            'no_nota' => $no_nota
        ])->result()[0];

        $produk = $this->db->select('tb_pembelian.harga as harga, tb_pembelian.id_produk, tb_pembelian.jumlah as jumlah, tb_servis.nm_servis')->join('tb_invoice', 'tb_invoice.no_nota = tb_pembelian.no_nota', 'left')->join('tb_servis', 'tb_pembelian.id_produk = tb_servis.id_servis', 'left')->get_where('tb_pembelian', [
            'tb_pembelian.no_nota' => $no_nota, 'kategori' => 'product'
        ])->result();


        $data = [
            'title'  => "Crepe Beauty | Detail Invoice",
            'invoice' => $invoice,
            'produk' => $produk,
            'no_nota' => $no_nota,
            'dp' => $this->db->join('tb_customer', 'tb_dp.id_customer = tb_customer.id_customer')->get_where('tb_dp', ['status' => '1'])->result(),
        ];

        $this->load->view('invoice/detail_invoice', $data);
    }

    public function nota()
    {
        $no_nota = $this->input->get('invoice');
        $invoice = $this->db->join('tb_customer', 'tb_invoice.id_customer = tb_customer.id_customer', 'left')->get_where('tb_invoice', [
            'no_nota' => $no_nota
        ])->result()[0];
        $produk = $this->db->select('tb_pembelian.harga as harga, tb_pembelian.id_produk, tb_pembelian.jumlah as jumlah, tb_servis.nm_servis, tb_servis.biaya as harga_asli, tb_pembelian.diskon as diskon, tb_servis.id_kategori as id_kategori')->join('tb_invoice', 'tb_invoice.no_nota = tb_pembelian.no_nota', 'left')->join('tb_servis', 'tb_pembelian.id_produk = tb_servis.id_servis', 'left')->get_where('tb_pembelian', [
            'tb_pembelian.no_nota' => $no_nota,
            'tb_pembelian.kategori' => 'product',
        ])->result();

        $data = [
            'title'  => "Orchard Beauty | Detail Invoice",
            'invoice' => $invoice,
            'produk' => $produk
        ];

        $this->load->view('invoice/nota', $data);
    }


    function detail_order()
    {
        $id_produk = $this->input->get('id_produk');

        $data = [
            'produk' => $this->db->get_where('tb_servis', ['id_servis' => $id_produk])->row()
        ];
        $this->load->view('order/detail', $data);
    }
}
