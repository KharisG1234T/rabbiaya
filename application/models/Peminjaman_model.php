<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peminjaman_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getData($start, $length)
	{
		$this->db->limit($length, $start);
		$query = $this->db->get("peminjaman");
		return $query->result();
	}

	public function getCountAll($status = "ALL", $searchValue = "", $tgl_awal = "", $tgl_akhir = "", $from = "ALL", $direction = "ALL", $isArchieve = false)
	{
		$this->db->select("peminjaman.*, user.name, cabang.nama_cabang as to_cb, cb.nama_cabang as from_cb");
		$this->db->from('peminjaman');
		$this->db->join("user", 'user.id = peminjaman.id_user', 'inner');
		$this->db->join("cabang", "cabang.id_cabang = peminjaman.id_cabang", "inner");
		// fiele id_cabang itu relasi ke tabel cabang
		// field from itu relasi ke tabel area
		// ketika ingin mencari peminjaman dari cabang apa, relasikan peminjaman.from -> area -> cabang

		$this->db->join("area", "area.id_area = peminjaman.from", "inner");
		$this->db->join("cabang AS cb", "cb.id_area = area.id_area", "inner");

		if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2) {
			$areas = $this->session->userdata("area");
			$areaIds = [0];

			foreach ($areas as $area) {
				array_push($areaIds, (int) $area['area_id']);
			}
			$this->db->where_in('peminjaman.from', $areaIds);
		}

		if ($this->session->userdata('role_id') == 2) {
			$this->db->where('user.id', $this->session->userdata('id'));
		}

		if ($status !== 'ALL') {
			$this->db->where('peminjaman.status', $status);
		}

		if ($from != "ALL") {
			$this->db->where("peminjaman.from", intval($from));
		}

		if ($direction != "ALL") {
			$this->db->where("peminjaman.id_cabang", intval($direction));
		}

		if ($isArchieve) {
			$this->db->where('peminjaman.deleted_at !=', NULL);
		} else {
			// filter data no softdeleted
			$this->db->where('peminjaman.deleted_at IS NULL');
		}

		if ($searchValue && $searchValue != "") {
			$this->db->group_start();
			$this->db->like('user.name', $searchValue);
			$this->db->or_like('peminjaman.kode_pengajuan', $searchValue);
			$this->db->or_like('peminjaman.dinas', $searchValue);
			$this->db->group_end();
		}


		if ($tgl_awal != "" && $tgl_akhir != "") {
			$this->db->group_start();
			$this->db->where('date >=', $tgl_awal);
			$this->db->where('date <=', $tgl_akhir);
			$this->db->group_end();
		}

		// return $this->db->count_all("peminjaman");
		return $this->db->count_all_results(); // count with filtered
	}

	public function getCountFiltered($status, $searchValue, $tgl_awal, $tgl_akhir, $from, $direction, $isArchieve = false)
	{
		// Jika Anda memiliki logika filter, implementasikan di sini
		// Untuk sederhana, asumsikan sama dengan getCountAll
		return $this->getCountAll($status, $searchValue, $tgl_awal, $tgl_akhir, $from, $direction,  $isArchieve);
	}

	public function getAll($status, $start, $length, $order_by = 'kode_pengajuan', $order_direction = 'asc', $searchValue = "", $tgl_awal = "", $tgl_akhir = "", $from = "ALL", $direction = "ALL", $isArchieve = false)
	{
		$this->db->select("peminjaman.*, user.name, cabang.nama_cabang as to_cb, cb.nama_cabang as from_cb");
		$this->db->from('peminjaman');
		$this->db->join("user", 'user.id = peminjaman.id_user', 'inner');
		$this->db->join("cabang", "cabang.id_cabang = peminjaman.id_cabang", "inner");

		// fiele id_cabang itu relasi ke tabel cabang
		// field from itu relasi ke tabel area
		// ketika ingin mencari peminjaman dari cabang apa, relasikan peminjaman.from -> area -> cabang

		$this->db->join("area", "area.id_area = peminjaman.from", "inner");
		$this->db->join("cabang AS cb", "cb.id_area = area.id_area", "inner");

		if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2) {
			$areas = $this->session->userdata("area");
			$areaIds = [0];

			foreach ($areas as $area) {
				array_push($areaIds, (int) $area['area_id']);
			}
			$this->db->where_in('peminjaman.from', $areaIds);
		}

		if ($this->session->userdata('role_id') == 2) {
			$this->db->where('user.id', $this->session->userdata('id'));
		}

		if ($status !== 'ALL') {
			$this->db->where('peminjaman.status', $status);
		}

		if ($from != "ALL") {
			// from mengambil dari id area suatu cabang
			$this->db->where("peminjaman.from", intval($from));
		}

		if ($direction != "ALL") {
			$this->db->where("peminjaman.id_cabang", intval($direction));
		}

		if ($isArchieve) {
			$this->db->where('peminjaman.deleted_at !=', NULL);
		} else {
			// filter data no softdeleted
			$this->db->where('peminjaman.deleted_at IS NULL');
		}

		if ($searchValue && $searchValue != "") {
			$this->db->group_start();
			$this->db->like('user.name', $searchValue);
			$this->db->or_like('peminjaman.kode_pengajuan', $searchValue);
			$this->db->or_like('peminjaman.dinas', $searchValue);
			$this->db->group_end();
		}

		if ($tgl_awal != "" && $tgl_akhir != "") {
			$this->db->group_start();
			$this->db->where('date >=', $tgl_awal);
			$this->db->where('date <=', $tgl_akhir);
			$this->db->group_end();
		}

		// Tambahkan sorting berdasarkan kode_pengajuan
		$this->db->order_by($order_by, $order_direction);

		// Tambahkan limit dan offset untuk server-side processing
		if (is_int($start) && is_int($length)) {
			$this->db->limit($length, $start);
		}

		// print_r($this->db->get_compiled_select()); 
		// die;
		$query = $this->db->get();
		return $query->result_array();
	}

	function getDetail($id_peminjaman)
	{
		$this->db->select("peminjaman.*, cabang.nama_cabang, cb.nama_cabang as from_cb, user.name");
		$this->db->from('peminjaman');
		$this->db->join("cabang", "cabang.id_cabang = peminjaman.id_cabang", "inner");
		$this->db->join("cabang AS cb", "cb.id_area = peminjaman.from", "inner");
		$this->db->join("user", "user.id = peminjaman.id_user", "inner");
		$this->db->where('peminjaman.id_peminjaman', $id_peminjaman);
		$query = $this->db->get()->row_array();

		// relation one to many
		$barangpeminjaman = $this->db->from('barangpeminjaman')->where('id_peminjaman', $id_peminjaman)->get()->result_array();
		$userapproval = $this->db->select('createdat, id_user, status')->from('userapproval')->where('id_peminjaman', $id_peminjaman)->get()->result_array();
		$users = [];

		foreach ($userapproval as $key => $user) {
			$data = $this->db->select('ttd, role_id')->from('user')->where('id', $user['id_user'])->get()->row_array();
			if ($user['status'] == "REJECT") {
				$data['ttd'] = 'rejected.png';
			} else if ($data['ttd'] == null) {
				$data['ttd'] = '';
			}
			$data['createdat'] = $user['createdat'];
			array_push($users, $data);
			unset($userapproval[$key]);
		}

		$userapproval['users'] = $users;
		$query['barangpeminjaman'] = $barangpeminjaman;
		$query['userapproval'] = $userapproval;
		return $query;
	}

	public function getById($id_peminjaman)
	{
		return $this->db->get_where('peminjaman', ['id_peminjaman' => $id_peminjaman])->row_array();
	}

	public function checkSkuComplete($id_peminjaman)
	{
		$query = $this->db->where('id_peminjaman', $id_peminjaman)
			->where('status', 'PROCESS')
			->get('peminjaman');

		if ($query->num_rows() > 0) {
			// SKU dalam proses, periksa SKU di barangpeminjaman
			$queryBarang = $this->db->where('id_peminjaman', $id_peminjaman)
				->where('sku', 'N/A')
				->get('barangpeminjaman');

			return $queryBarang->num_rows() == 0;
		}

		return false;
	}



	public function save($data)
	{
		$this->db->insert('peminjaman', $data);
		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	public function update($data, $id)
	{
		$this->db->where('id_peminjaman', $id);
		$this->db->update('peminjaman', $data);
	}

	public function delete($id_peminjaman)
	{

		$data["deleted_at"] =  date('Y-m-d');

		$this->db->where('id_peminjaman', $id_peminjaman);
		$this->db->update('peminjaman', $data);

		$this->db->where('id_peminjaman', $id_peminjaman);
		$this->db->update('barangpeminjaman', $data);

		$this->db->where('id_peminjaman', $id_peminjaman);
		$this->db->update('userapproval', $data);
	}

	public function restore($id_peminjaman)
	{
		$data["deleted_at"] =  NULL;

		$this->db->where('id_peminjaman', $id_peminjaman);
		$this->db->update('peminjaman', $data);

		$this->db->where('id_peminjaman', $id_peminjaman);
		$this->db->update('barangpeminjaman', $data);

		$this->db->where('id_peminjaman', $id_peminjaman);
		$this->db->update('userapproval', $data);
	}

	public function generateKodePengajuan($peminjamanId)
	{
		$newNumberFormatted = sprintf('%06d', $peminjamanId);
		$newKodePengajuan = 'FPB-' . $newNumberFormatted;
		return $newKodePengajuan;
	}



	public function savekp($kode_pengajuan)
	{
		// Generate kode pengajuan baru.
		$newKodePengajuan = $this->generateKodePengajuan();

		// Set kode pengajuan dalam data.
		$kode_pengajuan['kode_pengajuan'] = $newKodePengajuan;

		$this->db->trans_start(); // Memulai transaksi database

		// Simpan data ke tabel peminjaman
		$this->db->insert('peminjaman', $kode_pengajuan);

		$insert_id = $this->db->insert_id(); // Ambil ID dari data yang baru disimpan

		$this->db->trans_complete(); // Selesaikan transaksi database

		if ($this->db->trans_status() === FALSE) {
			return false; // Transaksi gagal
		} else {
			return $insert_id; // Transaksi berhasil
		}
	}

	public function getUserApprovalByPeminjamanId($id_peminjaman)
	{
		$this->db->select('status, id_user');
		$this->db->where('id_peminjaman', $id_peminjaman);
		return $this->db->get('userapproval')->result_array();
	}
}
