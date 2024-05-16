<?php
namespace App\Libraries;

use App\Models\Setting_bridging;
use Illuminate\Support\Facades\DB;

class Apivclaim
{
    public static function connect($tipe, $servis, $cparam = null)
    {
        $config = Setting_bridging::where([
            "bridging_name"      => "VCLAIM",
            "bridging_status"    => "t"
        ])->first();
        $urlServis    = $config->server_url;
        $xcons_id     = $config->rs_cons_id;
        $xsecretKey   = $config->rs_secret_key;
        $user_key     = $config->rs_user_key;
        $x_timestamp  = self::GetTimeStamp();
        $x_signature  = self::GetSignature($xcons_id, $xsecretKey, $x_timestamp);
        $urlServisAct = $urlServis . $servis;
        $ch           = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlServisAct);
        if ($tipe == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $cparam);
        } else if ($tipe == 'put') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $cparam);
        } else if ($tipe == 'delete') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $cparam);
        } else if ($tipe == 'get') {
            curl_setopt($ch, CURLOPT_POST, 0);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'x-cons-id: ' . $xcons_id,
            'x-timestamp: ' . $x_timestamp,
            'x-signature: ' . $x_signature,
            'user_key: ' . $user_key,
        ));
        curl_setopt($ch, CURLOPT_HEADER, false);  // DO NOT RETURN HTTP HEADERS
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 15);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // RETURN THE CONTENTS OF THE CALL
        $data = curl_exec($ch);
        $error_msg = curl_error($ch);
        if ($error_msg) {
            $data = [
                "metaData" => [
                    "code"      => "400",
                    "message"   => $error_msg
                ],
                "response" => null
            ];
            return $data;
            exit();
        }
        // print_r($data);die;
        // echo($urlServisAct."<br>".$x_signature."<br>".$x_timestamp);die;
        $key = $xcons_id . $xsecretKey . $x_timestamp;
        $data = json_decode($data, true);
        $data['response'] = self::stringDecrypt($key, $data['response']);
        $data['response'] = \LZCompressor\LZString::decompressFromEncodedURIComponent($data['response']);
        $data['response'] = json_decode($data['response'], true);
        // print_r($data);die;
        curl_close($ch);
        return $data;
    }

    function GetSignature($consId, $secretKey, $timestamp)
    {
        $tdata = "{$consId}&{$timestamp}";
        // Computes the signature by hashing the salt with the secret key as the key
        $sign = hash_hmac('sha256', $tdata, $secretKey, true);
        // base64 encode…
        $encodedSignature = base64_encode($sign);
        return $encodedSignature;
    }

    function GetTimeStamp()
    {
        // Computes the timestamp
        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        // Computes the signature by hashing the salt with the secret key as the key
        date_default_timezone_set("Asia/Jakarta"); // tambahan dari mas Yusak (bug session time kacau)
        return $tStamp;
    }

    function stringDecrypt($key, $string)
    {
        $encrypt_method = 'AES-256-CBC';

        // hash
        $key_hash = hex2bin(hash('sha256', $key));

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hex2bin(hash('sha256', $key)), 0, 16);

        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);

        return $output;
    }

    public static function generate_param_sep($respon)
    {
        $data = [];
        if (is_array($respon->detail_reff)) {
            $data = $respon->detail_reff;
        }else{
            $data = json_decode($respon->detail_reff,true);
        }

        switch ($respon->jeniskunjungan) {
            case '1':
                $data["dpjpLayan"]      = $respon->kode_dokter;
                $data["tujuanKunj"]     = 0;
                $data["assesmentPel"]   = "";
                break;
            case '2':
                //get data rujukan by nomorreferensi
                $data = [
                    "poli"            => [
                        "tujuan"       => $respon->kode_poli,
                        "eksekutif"    => "0"
                    ],
                    "dpjpLayan"    => $respon->kode_dokter,
                    "tujuanKunj"      => "0",
                    "assesmentPel"    => "2",
                ];
                break;
            case '3':
                //get data suratkontrol by nomorreferensi
                if ($data["reg_from"] == 'Rawat Inap') {
                    $data["tujuanKunj"] = 0;
                    $data["assesmentPel"] = "";
                } else {
                    $data["tujuanKunj"] = 2;
                    $data["assesmentPel"] = 5;
                } 
                unset($data["reg_from"]);
                //print_r($data);die;
                break;
            case '4':
                $rujukfk1 = "Rujukan/RS/" . $respon["nomorreferensi"];
                $dataRujuk = $this->vclaim->connect('get', $rujukfk1); //1
                if ($dataRujuk['metaData']['code'] != 200) {
                    echo json_encode([
                        "code"      => $dataRujuk['metaData']['code'],
                        "message"   => $dataRujuk['metaData']['message']
                    ]);
                    exit;
                }
                $dataRujuk = $dataRujuk["response"];
                //get data rujukan by nomorreferensi
                $this->session->set_userdata("rujukan", $dataRujuk);
                $data = [
                "diagAwal"        => $dataRujuk['rujukan']['diagnosa']['kode'],
                "poli"            => [
                    "tujuan"       => $dataRujuk['rujukan']['poliRujukan']['kode'],
                    "eksekutif"    => "0"
                ],
                "skdp"   =>  [
                    "noSurat"   =>  "",
                    "kodeDPJP"  =>  ""
                ],
                "dpjpLayan"    => $respon["kode_dokter"],
                "rujukan"         => [
                    "asalRujukan"     => $dataRujuk['asalFaskes'],
                    "tglRujukan"      => $dataRujuk['rujukan']['tglKunjungan'],
                    "noRujukan"       => $dataRujuk['rujukan']['noKunjungan'],
                    "ppkRujukan"      => $dataRujuk['rujukan']['provPerujuk']['kode']
                ],
                ];
                $data["tujuanKunj"] = 0;
                $data["assesmentPel"] = "";
                break;
            /* case '5':
                $input_visit = array(
                    'visit_status'          => 10,
                    'visit_end_cause_id'    => 48,
                    'visit_end_date'        => "now()",
                    'modifieddate'          => date("Y-m-d H:i:s"),
                    'visit_end_condition_id' => 125
                );
                $this->db->trans_begin();
                $this->db->where("visit_id", $respon['visit_id']);
                $this->db->update('yanmed.visit', $input_visit);
                $input_srv = array(
                'srv_in'       => "now()",
                'srv_out'      => "now()",
                'srv_status'   => 10
                );
                $this->db->where("visit_id", $respon['visit_id']);
                $this->db->update('yanmed.services', $input_srv);
                if ($this->db->trans_status() !== false) {
                $this->db->trans_commit();
                $respon = [
                    "code"      => "211",
                    "message"   => "Iterasi berhasil dikonfirmasi, silahkan menuju loket farmasi"
                ];
                } else {
                $this->db->trans_rollback();
                $respon = [
                    "code"      => "212",
                    "message"   => "Iterasi gagal dikonfirmasi, silahkan menuju loket pendaftaran"
                ];
                }
                echo json_encode($respon);
                exit;
                break; */
            default:
                # code...
                break;
        }
        
        return self::build_sep($respon, $data);
    }

    private function build_sep($respon,$data)
	{
		$kdPPKRS = '1302R001';
		$noTelp = trim(preg_replace('/[^0-9]/', '', $respon->nohp));
		$param = [
			"t_sep" => [
				"noKartu" => "" . $respon->px_noka . "",
				"tglSep" => "" . date('Y-m-d',strtotime($respon->visit_date)) . "",
				"ppkPelayanan" => "$kdPPKRS",
				"jnsPelayanan" => "2",
				"kdPenunjang"  => "",
				"klsRawat" => [
					"klsRawatHak" => "1",
					"klsRawatNaik" => "",
					"pembiayaan" => "",
					"penanggungJawab" => ""
				],
				"noMR" => "" . $respon->px_norm . "",
				"catatan" => "PENDAFTARAN RAWAT JALAN",
				"cob" => [
					"cob" => "0"
				],
				"katarak" => [
					"katarak" => "0"
				],
				"jaminan" => [
					"lakaLantas" => "0",
					"penjamin" => [
						"penjamin" => "",
						"tglKejadian" => "",
						"keterangan" => "",
						"suplesi" => [
							"suplesi" => "",
							"noSepSuplesi" => "",
							"lokasiLaka" => [
								"kdPropinsi" => "",
								"kdKabupaten" => "",
								"kdKecamatan" => ""
							]
						]
					]
				],
				"flagProcedure" => "",
				"noTelp" => "" . $noTelp . "",
				"user" => "APM@RSUDIBNUSINA"
			]
		];
		$param['t_sep'] = array_merge($param['t_sep'], $data);
		$cparam = '
		{ 
			"request": ' . json_encode($param) . '
		}';
        $resp=self::connect('post', 'SEP/2.0/insert', $cparam);
        print_r($resp);
        die;

        DB::table("services.e_sep")->insert([
            'esep_date'     => $respon->visit_date,
            'esep_noka'     => $respon->px_noka,
            'esep_param'    => $cparam,
            'esep_status'   => 1,
            'esep_created'  => date("Y-m-d H:i:s"),
            'esep_type'     => 'RJ',
            'visit_id'      => $respon->visit_id
        ]);

        return [
            "code"      => "200",
            "message"   => "OK"
        ];
    }

    public static function get_rujukan_pasien($req){
        $rujukanFktp = self::connect("get", "Rujukan/List/Peserta/" . $req->no_ka);
		$rujukan1 = $rujukan2 = [];
		if ($rujukanFktp['metaData']['code'] == 200) {
			$rujukan1 = $rujukanFktp["response"]["rujukan"];
			$rujukan1 = array_map(function ($arr) {
				return $arr + ['asalFaskes' => 1];
			}, $rujukan1);
		}

		$rujukanRs 	 = self::connect("get", "Rujukan/RS/List/Peserta/" . $req->no_ka);
		if ($rujukanRs['metaData']['code'] == 200) {
			$rujukan2 = $rujukanRs["response"]["rujukan"];
			$rujukan2 = array_map(function ($arr) {
				return $arr + ['asalFaskes' => 2];
			}, $rujukan2);
		}

		$rujukan = array_merge($rujukan1, $rujukan2);
		if ($rujukan) {
			$rujukanAktif = array_filter($rujukan, function ($var) use($req){
				return ((date('Y-m-d', strtotime($var['tglKunjungan'])) > date('Y-m-d', strtotime($req->tanggal."- 90 days"))));
			});
			if (!empty($rujukanAktif)) {
				$resp = [
					"code"		=> "200",
					"message"	=> "OK",
					"list"		=> $rujukanAktif
				];
			}else{
				$resp = [
					"code"		=> "202",
					"message"	=> "Rujukan tidak ditemukan"
				];
			}
		}else{
			$resp = [
				"code"		=> "202",
				"message"	=> "Rujukan tidak ditemukan"
			];
		}
		return $resp;
    }

    public static function get_rujukan_pasien2($noka){
        $rujukanFktp = self::connect("get", "Rujukan/List/Peserta/" . $noka);
		$rujukan1 = $rujukan2 = [];
		if ($rujukanFktp['metaData']['code'] == 200) {
			$rujukan1 = $rujukanFktp["response"]["rujukan"];
			$rujukan1 = array_map(function ($arr) {
				return $arr + ['asalFaskes' => 1];
			}, $rujukan1);
		}

		$rujukanRs 	 = self::connect("get", "Rujukan/RS/List/Peserta/" . $noka);
		if ($rujukanRs['metaData']['code'] == 200) {
			$rujukan2 = $rujukanRs["response"]["rujukan"];
			$rujukan2 = array_map(function ($arr) {
				return $arr + ['asalFaskes' => 2];
			}, $rujukan2);
		}

		$rujukan = array_merge($rujukan1, $rujukan2);
		if ($rujukan) {
			$rujukanAktif = array_filter($rujukan, function ($var){
				return ((date('Y-m-d', strtotime($var['tglKunjungan'])) > date('Y-m-d', strtotime("- 90 days"))));
			});
			if (!empty($rujukanAktif)) {
				$resp = [
					"code"		=> "200",
					"message"	=> "OK",
					"list"		=> $rujukanAktif
				];
			}else{
				$resp = [
					"code"		=> "202",
					"message"	=> "Rujukan tidak ditemukan"
				];
			}
		}else{
			$resp = [
				"code"		=> "202",
				"message"	=> "Rujukan tidak ditemukan"
			];
		}
		return $resp;
    }

    public function generate_sep($request,$poli,$dokter) {
        if ($request->visit_type == 1) {
            $rujukan = explode('-',$request->no_rujukan);
            if ($rujukan[1] == 1) {
                $rujukfk = "Rujukan/" . $rujukan[0];
            }else{
                $rujukfk = "Rujukan/RS/" . $rujukan[0];
            }
            $dataRujuk = self::connect('get', $rujukfk);
            $dataRujuk = $dataRujuk["response"]["rujukan"];
            $data = [
                "diagAwal"        => $dataRujuk['diagnosa']['kode'],
                "poli"            => [
                    "tujuan"       => $dataRujuk['poliRujukan']['kode'],
                    "eksekutif"    => "0"
                ],
                "skdp"   =>  [
                    "noSurat"   =>  "",
                    "kodeDPJP"  =>  ""
                ],
                "dpjpLayan"    => $dokter->kodehfis,
                "rujukan"         => [
                    "asalRujukan"     => $rujukan[1],
                    "tglRujukan"      => $dataRujuk['tglKunjungan'],
                    "noRujukan"       => $dataRujuk['noKunjungan'],
                    "ppkRujukan"      => $dataRujuk['provPerujuk']['kode']
                ],
            ];
        }elseif ($request->visit_type == 2) {
            $rujukan = explode('-',$request->no_rujukan);
            if ($rujukan[1] == 1) {
                $rujukfk = "Rujukan/" . $rujukan[0];
            }else{
                $rujukfk = "Rujukan/RS/" . $rujukan[0];
            }
            $dataRujuk = self::connect('get', $rujukfk);
            $dataRujuk = $dataRujuk["response"]["rujukan"];
            $data = [
                "diagAwal"        => $dataRujuk['diagnosa']['kode'],
                "poli"            => [
                    "tujuan"       => $poli->kodeaskes,
                    "eksekutif"    => "0"
                ],
                "skdp"   =>  [
                    "noSurat"   =>  "",
                    "kodeDPJP"  =>  ""
                ],
                "dpjpLayan"    => $dokter->kodehfis,
                "rujukan"         => [
                    "asalRujukan"     => $rujukan[1],
                    "tglRujukan"      => $dataRujuk['tglKunjungan'],
                    "noRujukan"       => $dataRujuk['noKunjungan'],
                    "ppkRujukan"      => $dataRujuk['provPerujuk']['kode']
                ],
            ];
        }elseif ($request->visit_type == 3) {
            //kontrol
            $kontrol = DB::table("services.kontrol_pasien")
                       ->orderBy("tgl_kontrol","desc")
                       ->where([
                            "nomor_kartu"       => $request->no_ka,
                            "kode_poli"         => $poli->kodeaskes,
                            "status_kontrol"    => 1
                        ])->get()->first();
            if ($kontrol) {
                $kontrol = self::connect('get', "RencanaKontrol/noSuratKontrol/".$kontrol->nomor_kontrol);
                if ($kontrol["metaData"]["code"] != 200) {
                    return $kontrol["metaData"];
                    exit();
                }
                $kontrol = $kontrol["response"];
                $diagnosa = explode('*-*',$request->diagnosa_awal);
                $data = [
                    "diagAwal"        => $diagnosa[0],
                    "poli"            => [
                        "tujuan"       => $poli->kodeaskes,
                        "eksekutif"    => "0"
                    ],
                    "skdp"   =>  [
                        "noSurat"   =>  $kontrol["noSuratKontrol"],
                        "kodeDPJP"  =>  $kontrol["kodeDokterPembuat"]
                    ],
                    "dpjpLayan"     => $dokter->kodehfis,
                    "rujukan"       => [
                        "asalRujukan"     => $kontrol["provPerujuk"]["asalRujukan"],
                        "tglRujukan"      => $kontrol["provPerujuk"]["tglRujukan"],
                        "noRujukan"       => $kontrol["provPerujuk"]["tglRujukan"],
                        "ppkRujukan"      => $kontrol["provPerujuk"]["kdProviderPerujuk"]
                    ],
                ];
            }else{
                return [
                    "code"      => 201,
                    "message"   => "Surat kontrol tidak ditemukan"
                ];
            }
        }
        $request['kode_poli']     = $poli->kodeaskes;
        $request['kode_dokter']   = $dokter->kodehfis;
        $request['detail_reff']   = $data;
        $request['nohp']          = $request->px_phone;
        $request['px_norm']       = $request->px_norm;
        $request['jeniskunjungan']       = $request->visit_type;
        $request['px_noka']       = $request->pxsurety_no;
        $request['visit_date']    = date('Y-m-d H:i:s');
        $resp = self::build_sep($request,$data);
        print_r($resp);
        die;
        return $data;
    }
}