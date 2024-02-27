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
        $id_distribusi = $this->input->post('id_distribusi');
        $tgl = date('Y-m-d');

        if ($this->input->post('keyword')) {
            $keyword = $this->input->post('keyword');
        }

        if ($this->input->post('kategori') !== null) {
            $kategori = $this->input->post('kategori');
        }

        if ($kategori == '0') {

            $load = $this->db->query("SELECT a.id_servis, a.nm_servis, b.harga as biaya, a.foto, c.diskon
            FROM tb_servis as a
            LEFT JOIN tb_harga as b ON b.id_servis = a.id_servis
            LEFT JOIN diskon_item as c ON c.id_servis = a.id_servis AND c.start_date <= '$tgl' AND c.finish_date >= '$tgl' and c.id_distribusi ='$id_distribusi'

            WHERE a.nm_servis LIKE '%$keyword%'  AND b.distirbusi = '$id_distribusi'
            ORDER BY a.nm_servis ASC;
             ")->result();
        } else {

            $load = $this->db->query("SELECT a.id_servis, a.nm_servis, b.harga as biaya, a.foto, c.diskon
            FROM tb_servis as a
            LEFT JOIN tb_harga as b ON b.id_servis = a.id_servis
            LEFT JOIN diskon_item as c ON c.id_servis = a.id_servis AND c.start_date <= '$tgl' AND c.finish_date >= '$tgl' and c.id_distribusi ='$id_distribusi'

            WHERE a.nm_servis LIKE '%$keyword%' AND a.id_kategori = '$kategori' AND b.distirbusi = '$id_distribusi'
            ORDER BY a.nm_servis ASC;
             ")->result();
        }

        $data = [
            'data' => $load,
            'dis' => $id_distribusi,
        ];

        $this->load->view('produknew/search_produk', $data);
    }

    function kategori()
    {
        $cek = ['13', '20', '26', '29'];
        $data = [
            'kategori' => $this->db->where_not_in('id_kategori', $cek)->get('tb_kategori')->result(),
        ];
        $this->load->view('produknew/ketegori', $data);
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
        $distribusi = $this->input->post('distribusi');

        $tgl = date('Y-m-d');



        $detail = $this->db->query("SELECT a.id_servis, a.nm_servis, b.harga as biaya, a.foto, c.diskon, a.id_kategori
        FROM tb_servis as a 
        left join tb_harga as b on b.id_servis = a.id_servis
        LEFT JOIN diskon_item as c ON c.id_servis = a.id_servis AND c.start_date <= '$tgl' AND c.finish_date >= '$tgl' and c.id_distribusi = '$distribusi'
        where a.id_servis = '$id_produk' and b.distirbusi ='$distribusi';")->row();

        $harga = $detail->biaya;

        $data = array(
            'id'      =>  "prd" .  $id_produk,
            'id_produk' => $id_produk,
            'qty'     => $jumlah,
            'type'    => 'barang',
            'price'   => $harga,
            'name'    => preg_replace("/[^a-zA-Z0-9]/", " ", $detail->nm_servis),
            'picture' => $detail->foto,
            'satuan'  => 'Pcs',
            'catatan' => $catatan,
            'kategori' => 'product',
            'diskon' => $detail->diskon,
            'kategori_produk' => $detail->id_kategori
        );
        $this->cart->insert($data);

        for ($x = 0; $x < count($id_toping); $x++) {
            $random = random_string('alnum', 8);
            $toping = $this->db->query("SELECT a.id_produk, a.nm_produk, a.qty_toping, b.harga, a.foto, a.id_kategori
            FROM tb_produk as a
            left JOIN(
            SELECT b.id_produk, b.harga
                FROM harga_toping as b 
                where b.id_distibusi ='$distribusi'
            ) as b on b.id_produk = a.id_produk
            where a.id_produk ='$id_toping[$x]';")->row();

            if ($qty_toping[$x] == 0) {
                # code...
            } else {
                $data = array(
                    'id'      => $random,
                    'id_produk' => $toping->id_produk,
                    'qty'     => $qty_toping[$x],
                    'type'    => 'barang',
                    'price'   => $toping->harga,
                    'name'    => preg_replace("/[^a-zA-Z0-9]/", " ", $toping->nm_produk),
                    'picture' => $toping->foto,
                    'satuan'  => 'Pcs',
                    'kategori' => 'toping',
                    'ibu' => $id_produk,
                    'diskon' => 0,
                    'kategori_produk' => $toping->id_kategori
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
            'cart' => $productItems,
            'dis' => $this->input->get('dis')
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
        $id_distribusi = $this->input->post('id_distribusi');

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
            $servis = $this->db->get_where('tb_resep', ['id_servis' => $value['id_produk']])->result();
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
            $subharga = ($value['qty'] * $value['price']) - $value['diskon'];
            $data_pembelian = array(
                'no_nota' => $no_nota,
                'id_produk'   => $value['id_produk'],
                'tanggal'   => $tanggal,
                'jumlah'        => $value['qty'],
                'harga'  => $value['price'],
                'total'      => $subharga,
                'admin'  => $admin,
                'kategori' => 'product',
                'id_distribusi' => $id_distribusi,
                'diskon' => $value['diskon']
            );
            $this->db->insert('tb_pembelian', $data_pembelian);
            foreach ($toping as $t) {
                $dt_toping = $this->db->get_where('tb_produk', ['id_produk' => $t['id_produk']])->row();

                $data = [
                    'kode_stok_produk' => $no_nota,
                    'id_produk' => $t['id_produk'],
                    'debit' => '0',
                    'kredit' => $dt_toping->qty_toping,
                    'tgl' => date('Y-m-d'),
                    'tgl_input' => date('Y-m-d'),
                    'admin' => $admin,
                    'jenis' => 'Penjualan'
                ];
                $this->db->insert('tb_stok_produk', $data);
                $subharga = $t['qty'] * $t['price'];
                $data_pembelian = array(
                    'no_nota' => $no_nota,
                    'id_produk'   => $t['id_produk'],
                    'tanggal'   => $tanggal,
                    'jumlah'        => $t['qty'],
                    'harga'  => $t['price'],
                    'total'      => $subharga,
                    'admin'  => $admin,
                    'kategori' => 'toping',
                    'id_produk_toping' => $value['id_produk'],
                    'id_distribusi' => $id_distribusi
                );
                $this->db->insert('tb_pembelian', $data_pembelian);
            }
        }

        $bayar = $cash + $bca_kredit + $bca_debit + $shoope + $mandiri_kredit + $mandiri_debit;

        $ttl = $total - $diskon - $nominal_voucher;

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
            'id_distribusi' => $id_distribusi

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

        $produk = $this->db->select('tb_pembelian.harga as harga,  tb_pembelian.id_produk, tb_pembelian.jumlah as jumlah, tb_servis.nm_servis, tb_pembelian.diskon')->join('tb_invoice', 'tb_invoice.no_nota = tb_pembelian.no_nota', 'left')->join('tb_servis', 'tb_pembelian.id_produk = tb_servis.id_servis', 'left')->get_where('tb_pembelian', [
            'tb_pembelian.no_nota' => $no_nota, 'kategori' => 'product'
        ])->result();


        $data = [
            'title'  => "Crepe Signature | Detail Invoice",
            'invoice' => $invoice,
            'produk' => $produk,
            'no_nota' => $no_nota,
            'dp' => $this->db->join('tb_customer', 'tb_dp.id_customer = tb_customer.id_customer')->get_where('tb_dp', ['status' => '1'])->result(),
        ];

        $this->load->view('invoice/detail_invoice', $data);
    }

    function save_nota()
    {
        $no_nota = $this->input->get('invoice');
        $invoice = $this->db->join('tb_customer', 'tb_invoice.id_customer = tb_customer.id_customer', 'left')->get_where('tb_invoice', [
            'no_nota' => $no_nota
        ])->row();
        $tgl = date('Y-m-d');
        $antrian = $this->db->query("SELECT max(a.antrian) as antrian FROM tb_invoice as a where a.tgl_jam = '$tgl' and a.status = '0'")->row();

        if ($invoice->antrian == '0') {
            $data_antri = [
                'antrian' => $antrian->antrian + 1
            ];
            $this->db->where('no_nota', $no_nota);
            $this->db->update('tb_invoice', $data_antri);
        }

        redirect(base_url("produk/nota?invoice=$no_nota"), 'refresh');
    }

    public function nota()
    {
        $no_nota = $this->input->get('invoice');
        $invoice = $this->db->join('tb_customer', 'tb_invoice.id_customer = tb_customer.id_customer', 'left')->get_where('tb_invoice', [
            'no_nota' => $no_nota
        ])->row();
        $produk = $this->db->select('tb_pembelian.diskon, tb_pembelian.harga as harga, tb_pembelian.id_produk, tb_pembelian.jumlah as jumlah, tb_servis.nm_servis, tb_servis.biaya as harga_asli, tb_pembelian.diskon as diskon, tb_servis.id_kategori as id_kategori')->join('tb_invoice', 'tb_invoice.no_nota = tb_pembelian.no_nota', 'left')->join('tb_servis', 'tb_pembelian.id_produk = tb_servis.id_servis', 'left')->get_where('tb_pembelian', [
            'tb_pembelian.no_nota' => $no_nota,
            'tb_pembelian.kategori' => 'product',
        ])->result();
        $tgl = date('Y-m-d');
        $antrian = $this->db->query("SELECT max(a.antrian) as antrian FROM tb_invoice as a where a.tgl_jam = '$tgl' and a.status = '0'")->row();

        if ($invoice->antrian == '0') {
            $data_antri = [
                'antrian' => $antrian->antrian + 1
            ];
            $this->db->where('no_nota', $no_nota);
            $this->db->update('tb_invoice', $data_antri);
        }

        $data = [
            'title'  => "Orchard Beauty | Detail Invoice",
            'invoice' => $invoice,
            'produk' => $produk,
            'no_nota' => $no_nota,
        ];

        $this->load->view('invoice/nota', $data);
    }

    function save_checker()
    {
        $no_nota = $this->input->get('invoice');
        $invoice = $this->db->join('tb_customer', 'tb_invoice.id_customer = tb_customer.id_customer', 'left')->get_where('tb_invoice', [
            'no_nota' => $no_nota
        ])->row();
        $tgl = date('Y-m-d');
        $antrian = $this->db->query("SELECT max(a.antrian) as antrian FROM tb_invoice as a where a.tgl_jam = '$tgl' and a.status = '0'")->row();

        if ($invoice->antrian == '0') {
            $data_antri = [
                'antrian' => $antrian->antrian + 1
            ];
            $this->db->where('no_nota', $no_nota);
            $this->db->update('tb_invoice', $data_antri);
        }

        redirect(base_url("produk/checker?invoice=$no_nota"), 'refresh');
    }

    public function checker()
    {
        $no_nota = $this->input->get('invoice');
        $invoice = $this->db->join('tb_customer', 'tb_invoice.id_customer = tb_customer.id_customer', 'left')->get_where('tb_invoice', [
            'no_nota' => $no_nota
        ])->result()[0];
        $produk = $this->db->select('tb_pembelian.diskon, tb_pembelian.harga as harga, tb_pembelian.id_produk, tb_pembelian.jumlah as jumlah, tb_servis.nm_servis, tb_servis.biaya as harga_asli, tb_pembelian.diskon as diskon, tb_servis.id_kategori as id_kategori')->join('tb_invoice', 'tb_invoice.no_nota = tb_pembelian.no_nota', 'left')->join('tb_servis', 'tb_pembelian.id_produk = tb_servis.id_servis', 'left')->get_where('tb_pembelian', [
            'tb_pembelian.no_nota' => $no_nota,
            'tb_pembelian.kategori' => 'product',
        ])->result();
        $tgl = date('Y-m-d');




        $data = [
            'title'  => "Orchard Beauty | Detail Invoice",
            'invoice' => $invoice,
            'produk' => $produk,
            'no_nota' => $no_nota,
        ];

        $this->load->view('invoice/checker', $data);
    }


    function detail_order()
    {
        $id_produk = $this->input->get('id_produk');
        $id_distribusi = $this->input->get('id_distribusi');
        $tgl = date('Y-m-d');

        $detail = $this->db->query("SELECT a.id_servis, a.nm_servis, b.harga as biaya, a.foto, c.diskon
        FROM tb_servis as a 
        left join tb_harga as b on b.id_servis = a.id_servis
        LEFT JOIN diskon_item as c ON c.id_servis = a.id_servis AND c.start_date <= '$tgl' AND c.finish_date >= '$tgl' and c.id_distribusi = '$id_distribusi'
        where a.id_servis = '$id_produk' and b.distirbusi ='$id_distribusi';")->row();

        $data = [
            'produk' => $detail
        ];
        $this->load->view('order/detail', $data);
    }

    public function laporan_invoice()
    {

        $dt_a   = $this->input->get('tgl1');
        $dt_b = $this->input->get('tgl2');
        $data = array(
            'title'  => "Laporan Invoice",
            'invoice' => $this->M_salon->daftar_invoice(" where tb_invoice.tgl_jam >= '$dt_a' AND tb_invoice.tgl_jam <= '$dt_b' AND status = 0"),
            'tgl1' => $dt_a,
            'tgl2' => $dt_b,
        );

        $this->load->view('invoice/laporan_invoice', $data);
    }

    public function summary()
    {
        $tgl1 = $this->input->get('tgl1');
        $tgl2 = $this->input->get('tgl2');

        $summary = $this->db->query("SELECT 'CASH' AS payment_method, 
        SUM(CASE WHEN cash > 0 THEN cash - kembali  ELSE 0 END) AS total_amount, 
        COUNT(CASE WHEN cash > 0 THEN no_nota END) AS transaction_count
        FROM tb_invoice
        WHERE tgl_jam BETWEEN '$tgl1' AND '$tgl2' and status = 0
        
        UNION
        
        SELECT 'GOPAY' AS payment_method, 
                SUM(CASE WHEN gopay > 0 THEN gopay - kembali  ELSE 0 END) AS total_amount, 
                COUNT(CASE WHEN gopay > 0 THEN no_nota END) AS transaction_count
        FROM tb_invoice
        WHERE tgl_jam BETWEEN '$tgl1' AND '$tgl2' and status = 0
        
        
        UNION
        
        SELECT 'GRABFOOD' AS payment_method, 
                SUM(CASE WHEN bca_debit > 0 THEN bca_debit - kembali   ELSE 0 END) AS total_amount, 
                COUNT(CASE WHEN bca_debit > 0 THEN no_nota END) AS transaction_count
        FROM tb_invoice
        WHERE tgl_jam BETWEEN '$tgl1' AND '$tgl2' and status = 0;")->result();

        $data = [
            'summary' => $summary,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2
        ];
        $this->load->view('invoice/metode_pembayaran', $data);
    }
    public function shift_out()
    {
        $tgl1 = $this->input->get('tgl1');
        $tgl2 = $this->input->get('tgl2');

        $dinein = $this->db->query("SELECT b.nm_servis, sum(a.total + if(c.total_toping is null ,0,c.total_toping)) as total, sum(a.jumlah) as jumlah
        FROM tb_pembelian as a
        left join tb_servis as b on b.id_servis = a.id_produk
        left join(
            SELECT c.id_produk_toping, c.no_nota, sum(c.total) as total_toping
            FROM tb_pembelian as c 
            where c.kategori = 'toping'
            group by c.id_produk_toping, c.no_nota
        )as c on c.id_produk_toping = a.id_produk and c.no_nota = a.no_nota
        where a.kategori = 'product' and a.tanggal between '$tgl1' and '$tgl2' and a.id_distribusi = '1'
        group by a.id_produk;")->result();

        $gojek = $this->db->query("SELECT b.nm_servis, sum(a.total + if(c.total_toping is null ,0,c.total_toping)) as total, sum(a.jumlah) as jumlah
        FROM tb_pembelian as a
        left join tb_servis as b on b.id_servis = a.id_produk
        left join(
            SELECT c.id_produk_toping, c.no_nota, sum(c.total) as total_toping
            FROM tb_pembelian as c 
            where c.kategori = 'toping'
            group by c.id_produk_toping, c.no_nota
        )as c on c.id_produk_toping = a.id_produk and c.no_nota = a.no_nota
        where a.kategori = 'product' and a.tanggal between '$tgl1' and '$tgl2' and a.id_distribusi = '2'
        group by a.id_produk;")->result();

        $data = [
            'dinein' => $dinein,
            'gojek' => $gojek,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2
        ];
        $this->load->view('invoice/shift_out', $data);
    }
}
