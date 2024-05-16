<?php 

function convert_currency2($angka)
{
	if(!$angka) {
		return 0;
	}
	$rupiah= number_format($angka,2,',','.');
	return $rupiah;
}

function get_namaBulan($data = null){
	$bulan = [
		"",
		"Januari",
		"Februari",
		"Maret",
		"April",
		"Mei",
		"Juni",
		"Juli",
		"Agustus",
		"September",
		"Oktober",
		"November",
		"Desember"
	];
	if ($data) {
		if (strripos($data,'-')>0) {
			$data=explode("-",$data);
			$data = $bulan[($data[0]-1)].' '.$data[1];
		}else{
			$data = $bulan[(int)$data];
		}
		return $data;
	}else{
		return $bulan;
	}
}

function date_db($date)
{
	if ($date) {
		return date('Y-m-d',strtotime($date));
	}else{
		return false;
	}
}

function date_indo($date)
{
	if ($date) {
		return date('d-m-Y',strtotime($date));
	}else{
		return false;
	}
}

function date_indo2($date)
{
	$indo = explode('-',date_indo($date));

	$tanggal = $indo[0].' '.get_namaBulan($indo[1]).' '.$indo[2];
	return  $tanggal;
}

function set_status($param,$data)
{
	$attr = $data[$param];
	$status = '<span class="'.$attr['label_class'].'">'.$attr['label_status'].'</span>';
	return $status;
}


function getNamaHari($dayNumber) {
    $namaHari = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu'
    ];

    return $namaHari[$dayNumber] ?? 'Tidak diketahui';
}