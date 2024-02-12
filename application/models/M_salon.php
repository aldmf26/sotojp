<?php
defined('BASEPATH') or exit('No direct script access allowed');

// public function __construct()
// {
// 	parent::__construct();
// 	$this->load->database();
// }

class M_salon extends CI_model
{

	public function dt_app($where = "")
	{
		return $this->db->query("Select * FROM tb_appoiment " . $where . " order by id_app DESC ")->result();
	}

	public function rkp_app($where = "")
	{
		return $this->db->query("Select * FROM rekap_app " . $where . " order by nm_app ASC ")->result();
	}

	public function dt_denda($where = "")
	{
		return $this->db->query("Select * FROM ctt_denda " . $where . " order by id_denda DESC ")->result();
	}

	public function get_list_penjualan($where = "")
	{
		return $this->db->query("Select tb_pembelian.no_nota, tb_pembelian.nm_karyawan, tb_pembelian.tanggal,
		tb_produk.nm_produk,
		tb_pembelian.jumlah,
		tb_satuan.satuan,
		tb_pembelian.harga,
		tb_pembelian.diskon,
		tb_pembelian.total,
		tb_pembelian.admin FROM tb_pembelian LEFT JOIN tb_produk ON tb_pembelian.id_produk = tb_produk.id_produk LEFT JOIN tb_satuan ON tb_produk.id_satuan = tb_satuan.id_satuan" . $where . " order by id_pembelian DESC ")->result();
	}

	public function daftar_komisi($where = "")
	{
		return $this->db->query("Select * FROM komisi LEFT JOIN tb_karyawan ON komisi.id_kry = tb_karyawan.id_kry" . $where . " order by komisi.id DESC ")->result();
	}

	// public function laporan_komisi($where=""){
	// 	return $this->db->query("SELECT nm_kry, tb_komisi_app.total_app, komisi.total_produk FROM tb_karyawan
	//     LEFT JOIN (SELECT komisi.id_kry, SUM(komisi.komisi) as total_produk FROM komisi GROUP BY komisi.id_kry ".$where." ) komisi ON tb_karyawan.id_kry = komisi.id_kry
	//     LEFT JOIN (SELECT tb_komisi_app.id_kry, SUM(tb_komisi_app.komisi) as total_app FROM tb_komisi_app GROUP BY tb_komisi_app.id_kry ".$where.") tb_komisi_app ON tb_karyawan.id_kry = tb_komisi_app.id_kry")->result();
	// }

	public function daftar_app($where = "")
	{
		return $this->db->query("Select tb_app.no_nota, tb_customer.nama, tb_app.nm_karyawan, tb_servis.nm_servis, tb_app.qty, tb_app.total, tb_app.tgl, tb_app.admin FROM tb_app LEFT JOIN tb_servis ON tb_app.id_servis = tb_servis.id_servis LEFT JOIN tb_customer ON tb_app.id_customer = tb_customer.id_customer" . $where . " order by tb_app.id_app DESC ")->result();
	}

	public function daftar_invoice($where = "")
	{
		return $this->db->query("Select * FROM tb_invoice LEFT JOIN tb_customer ON tb_invoice.id_customer = tb_customer.id_customer " . $where . " order by tb_invoice.id DESC ")->result();
	}

	public function daftar_opname($where = "")
	{
		return $this->db->query("Select * FROM tb_opname " . $where . " GROUP BY kode_opname ORDER BY id_opname DESC")->result();
	}

	public function daftar_komisi_app($where = "")
	{
		return $this->db->query("Select * FROM tb_komisi_app LEFT JOIN tb_karyawan ON tb_komisi_app.id_kry = tb_karyawan.id_kry" . $where . " order by tb_komisi_app.id_komisi DESC ")->result();
	}

	public function get_list_produk_masuk($where = "")
	{
		return $this->db->query("Select * FROM tbl_produk_masuk LEFT JOIN tb_produk ON tbl_produk_masuk.id_produk = tb_produk.id_produk LEFT JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori LEFT JOIN tb_satuan ON tb_produk.id_satuan = tb_satuan.id_satuan" . $where . " order by id DESC ")->result();
	}

	public function dt_kasbon($where = "")
	{
		return $this->db->query("Select * FROM ctt_kasbon " . $where . " order by id_kasbon DESC ")->result();
	}

	public function dt_tips($where = "")
	{
		return $this->db->query("Select * FROM ctt_tips " . $where . " order by id_tips DESC ")->result();
	}

	public function dt_kry($where = "")
	{
		return $this->db->query("Select * FROM tb_karyawan " . $where . " order by id_kry DESC ")->result();
	}

	public function dt_user($where = "")
	{
		return $this->db->query("Select * FROM view_user " . $where)->result();
	}

	public function dt_role($where = "")
	{
		return $this->db->query("Select * FROM tb_role " . $where)->result();
	}

	public function dt_servis($where = "")
	{
		return $this->db->order_by('nm_servis', 'ASC')->get('tb_servis')->result();
	}

	public function dt_kom($where = "")
	{
		$this->db->select('*');
		$this->db->from('tb_komisi' . $where);
		$data = $this->db->get();
		return $data->result();
	}

	public function dt_cancel($where = "")
	{
		return $this->db->query("Select * FROM tb_cancel " . $where . " order by id_cancel DESC ")->result();
	}

	public function dt_customer($where = "")
	{
		$this->db->select('*');
		$this->db->from('tb_customer' . $where);
		$this->db->order_by('id_customer', 'desc');
		$data = $this->db->get();
		return $data->result();
	}

	public function dt_cancel_sum($where = "")
	{
		$this->db->select('*');
		$this->db->from('tb_cancel' . $where);
		$this->db->group_by('nama');
		$data = $this->db->get();
		return $data->result();
	}

	public function dt_customer_sum($where = "")
	{
		$this->db->select('*');
		$this->db->from('tb_customer' . $where);
		$this->db->group_by('nama');
		$data = $this->db->get();
		return $data->result();
	}

	public function search_produk($search_keyword, $kategori, $id_distribusi)
	{
		// $this->db->select('*');
		// $this->db->from('tb_servis');

		// $this->db->where('id_kategori', $kategori);
		// $this->db->like('nm_servis', $search_keyword);

		// $this->db->order_by('nm_servis', 'asc');
		// $query = $this->db->get();

		$produk = $this->db->query("SELECT a.id_servis, a.nm_servis, b.harga as biaya, a.foto
		FROM tb_servis as a
		left join tb_harga as b on b.id_servis = a.id_servis
		where a.nm_servis like '%$search_keyword%' and a.id_kategori ='$kategori' and b.distirbusi = '$id_distribusi'
		order by a.nm_servis ASC;
 		")->result();

		return $produk;
	}
	public function search_produk_2($search_keyword, $id_distribusi)
	{

		$produk = $this->db->query("SELECT a.id_servis, a.nm_servis, b.harga as biaya , a.foto
		FROM tb_servis as a
		left join tb_harga as b on b.id_servis = a.id_servis
		where a.nm_servis like '%$search_keyword%' and b.distirbusi = '$id_distribusi'
		order by a.nm_servis ASC;
 		")->result();

		return $produk;
	}

	public function get_opname($search_keyword, $kategori)
	{
		$this->db->select('*');
		$this->db->from('tb_produk');
		$this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left');
		$this->db->join('tb_satuan', 'tb_produk.id_satuan = tb_satuan.id_satuan', 'left');
		$this->db->where('tb_produk.id_kategori', $kategori);
		$this->db->like('nm_produk', $search_keyword);
		// $this->db->or_like('harga', $search_keyword);
		$this->db->order_by('nm_produk', 'asc');
		$query = $this->db->get();
		// $query = $this->db->query("SELECT * FROM tb_produk  WHERE id_kategori = '$kategori' AND nm_produk LIKE '$search_keyword'");
		return $query->result();
	}

	// =============================================== PROSEDUR ================================================
	public function summary_app($tgl1, $tgl2)
	{
		return $this->db->query('call sum_app("' . $tgl1 . '", "' . $tgl2 . '" )')->result();
	}

	public function summary_app1($tgl1, $tgl2)
	{
		$this->db->select('*');
		$this->db->from('tb_order to');
		$this->db->join('tb_servis ts', 'to.id_servis = ts.id_servis', 'left');
		$this->db->join('tb_terapis tt', 'to.id_terapis = tt.id_terapis', 'left');
		$this->db->join('tb_customer tc', 'to.id_customer = tc.id_customer', 'left');
		$this->db->where('to.tanggal >=', $tgl1);
		$this->db->where('to.tanggal <=', $tgl2);
		$this->db->order_by('to.id_terapis');
		$query = $this->db->get();
		return $query->result();
	}

	public function summary_appointment($tgl1, $tgl2)
	{
		$this->db->select('*');
		$this->db->from('tb_app to');
		$this->db->join('tb_servis ts', 'to.id_servis = ts.id_servis', 'left');
		$this->db->join('tb_customer tc', 'to.id_customer = tc.id_customer', 'left');
		$this->db->where('to.tgl >=', $tgl1);
		$this->db->where('to.tgl <=', $tgl2);
		$query = $this->db->get();
		return $query->result();
	}

	public function summary_app2($tgl1, $tgl2)
	{
		$this->db->select('*');
		$this->db->from('tb_pembelian to');
		$this->db->join('tb_karyawan ts', 'to.id_karyawan = ts.id_kry', 'left');
		$this->db->join('tb_produk tt', 'to.id_produk = tt.id_produk', 'left');
		$this->db->where('to.tanggal >=', $tgl1);
		$this->db->where('to.tanggal <=', $tgl2);
		$this->db->order_by('id_pembelian');
		$query = $this->db->get();
		return $query->result();
	}

	public function summary_app3($tgl1, $tgl2)
	{
		$this->db->select('*');
		$this->db->from('komisi');
		$this->db->join('tb_karyawan', 'komisi.id_kry = tb_karyawan.id_kry', 'left');
		$this->db->where('komisi.tgl >=', $tgl1);
		$this->db->where('komisi.tgl <=', $tgl2);
		$this->db->select_sum('komisi');
		$this->db->group_by('komisi.id_kry');
		$query = $this->db->get();
		return $query->result();
	}

	public function summary_app4($tgl1, $tgl2)
	{
		$this->db->select('*');
		$this->db->from('tb_komisi_app');
		$this->db->join('tb_karyawan', 'tb_komisi_app.id_kry = tb_karyawan.id_kry', 'left');
		$this->db->where('tb_komisi_app.tgl >=', $tgl1);
		$this->db->where('tb_komisi_app.tgl <=', $tgl2);
		$this->db->select_sum('komisi');
		$this->db->group_by('tb_komisi_app.id_kry');
		$query = $this->db->get();
		return $query->result();
	}

	public function summary_tips($tgl1, $tgl2)
	{
		return $this->db->query('call sum_tips("' . $tgl1 . '", "' . $tgl2 . '" )')->result();
	}

	public function summary_kasbon($tgl1, $tgl2)
	{
		return $this->db->query('call sum_kasbon("' . $tgl1 . '", "' . $tgl2 . '" )')->result();
	}

	public function summary_denda($tgl1, $tgl2)
	{
		return $this->db->query('call sum_denda("' . $tgl1 . '", "' . $tgl2 . '" )')->result();
	}

	public function summary_produk_masuk($tgl1, $tgl2)
	{
		$this->db->select('*');
		$this->db->from('tbl_produk_masuk');
		$this->db->join('tb_produk', 'tbl_produk_masuk.id_produk = tb_produk.id_produk', 'left');
		$this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left');
		$this->db->where('tbl_produk_masuk.tgl >=', $tgl1);
		$this->db->where('tbl_produk_masuk.tgl <=', $tgl2);
		$query = $this->db->get();
		return $query->result();
	}

	public function summary_penjualan_produk($tgl1, $tgl2)
	{
		$this->db->select('*');
		$this->db->from('tb_pembelian');
		$this->db->join('tb_produk', 'tb_pembelian.id_produk = tb_produk.id_produk', 'left');
		$this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left');
		$this->db->where('tb_pembelian.tanggal >=', $tgl1);
		$this->db->where('tb_pembelian.tanggal <=', $tgl2);
		$this->db->select_sum('total');
		$this->db->select_sum('jumlah');
		$this->db->group_by('tb_pembelian.id_produk');
		$query = $this->db->get();
		return $query->result();
	}

	public function summary_servis($tgl1, $tgl2)
	{
		$this->db->select('*');
		$this->db->from('tb_app');
		$this->db->join('tb_servis', 'tb_app.id_servis = tb_servis.id_servis', 'left');
		// $this->db->join('tb_kategori', 'tb_produk.id_kategori = tb_kategori.id_kategori', 'left');
		$this->db->where('tb_app.tgl >=', $tgl1);
		$this->db->where('tb_app.tgl <=', $tgl2);
		$this->db->select_sum('total');
		$this->db->select_sum('qty');
		$this->db->group_by('tb_app.id_servis');
		$query = $this->db->get();
		return $query->result();
	}

	public function summary_canel($nama)
	{
		$this->db->select('*');
		$this->db->from('tb_cancel');
		$this->db->where('nama', $nama);
		$query = $this->db->get();
		return $query->result();
	}

	// =============================================== SEARCHING ================================================

	function ambil_anak()
	{
		return $this->db->get('tb_karyawan');
	}

	function cari_anak($id)
	{
		$query = $this->db->get_where('tb_karyawan', array('nm_kry' => $id));
		return $query;
	}

	function absen($where = "")
	{
		$this->db->select('*');
		$this->db->from('tb_absen' . $where);
		$this->db->order_by('id_absen', 'DESC');
		$data = $this->db->get();
		return $data->result();
	}

	function d_nama($where = "")
	{
		$this->db->select('nm_kry');
		$this->db->from('tb_karyawan' . $where);
		$this->db->order_by('nm_kry', 'ASC');
		$data = $this->db->get();
		return $data->result();
	}

	// =============================================== CRUD ================================================

	public function update_produk($data_update)
	{
		$this->db->where('id_produk', $data_update['id_produk']);
		$this->db->update('tb_produk', $data_update);
	}
	public function update_produk_masuk($data_update)
	{
		$this->db->where('id', $data_update['id']);
		$this->db->update('tbl_produk_masuk', $data_update);
	}


	public function update_stok($data)
	{
		$this->db->where('id_produk', $data['id_produk']);
		$this->db->update('tb_produk', $data);
	}


	public function InputData($tabelName, $data)
	{
		$res = $this->db->insert($tabelName, $data);
		return $res;
	}

	public function UpdateData($tabelName, $data, $where)
	{
		$res = $this->db->update($tabelName, $data, $where);
		return $res;
	}

	public function DropData($tabelName, $where)
	{
		$res = $this->db->delete($tabelName, $where);
		return $res;
	}

	//AKtiva

	public function aktiva($nota)
	{
		$this->db->select('*');
		$this->db->from('aktiva');
		$this->db->where('nota', $nota);
		$this->db->order_by('id_aktiva', 'ASC');
		$data = $this->db->get();
		return $data->result();
	}
	public function nota_aktiva()
	{
		$this->db->select('*');
		$this->db->select('SUM(aktiva.kredit_aktiva) as kredit');
		$this->db->from('aktiva');
		$this->db->join('tb_kelompok_aktiva', 'tb_kelompok_aktiva.id_kelompok = aktiva.id_kelompok');
		$this->db->group_by('nota');
		$this->db->order_by('id_aktiva', 'ASC');
		$data = $this->db->get();
		return $data->result();
	}
}
