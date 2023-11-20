<?php
	//Setting konten pada header sebagai JSON karena akan menerima data berupa JSON
	header('Content-Type: application/json');

	//Ambil data yang dikirimkan dari klien sendDataJSON.html dan mengubah JSON menjadi Array PHP
	$data = json_decode(file_get_contents('php://input'));
	
	//Persiapkan koneksi db
	$mysqli = new mysqli('localhost','root','');

	//Setup nama db dan tabel
	$namadb = 'kampus';
	$namatabel = 'mahasiswa';

	//Buat db jika belum ada 
	$newdb = $mysqli->query('CREATE DATABASE IF NOT EXISTS kampus');
	
	//Aktifkan db kampus yang sudah dibuat/ atau sudah ada sebelumnya
	$mysqli->query('USE ' . $namadb . ';');

	//Buat tabel mahasiswa jika belum ada
	buat_tabel_mahasiswa($mysqli);

	//Input data JSON ke database MySQL
	input_data_mahasiswa($data, $mysqli);


	//Kumpulan fungsi yang diperlukan//
	///////////////////////////////////
	function buat_tabel_mahasiswa($koneksi){
		/* buat tabel mahasiswa */
		$sql = 'CREATE TABLE IF NOT EXISTS mahasiswa(
			nim char(10) NOT NULL,
			nama varchar(50) NOT NULL,
			kdProdi char(3) NOT NULL,
			PRIMARY KEY (nim)
			)'; 

		$result = $koneksi->query($sql);
	}

	function input_data_mahasiswa($data, $koneksi){
		$status = false;
		
		foreach($data as $dt){
			$sql = "INSERT INTO mahasiswa (
					nim, 
					nama, 
					kdProdi
				) VALUES (
					'$dt->nim', 
					'$dt->nama', 
					'$dt->kdProdi'
				)";

			if($koneksi->query($sql)){
				$status = true;
			}
		}

		echo $status ? 'sukses' : 'gagal';

	}

	//Close koneksi db jika sudah selesai
	$mysqli->close();
?>