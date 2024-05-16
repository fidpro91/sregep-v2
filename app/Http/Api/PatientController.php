<?php

namespace App\Http\Api;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Libraries\Servant;
use App\Models\Antrian_service;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class PatientController extends Controller
{
    public function get_patient($param) {
        $data = Patient::where("px_norm",$param)->first();
        if ($data) {
            $resp = [
                "code"      => 200,
                "message"   => "ok",
                "response"  => [
                    "data"  => $data
                ]
            ];
        }else{
            $resp = [
                "code"      => 201,
                "message"   => "Pasien tidak ditemukan"
            ];
        }
        return response()->json($resp);
    }

    public function get_poli(Request $request) {
        $data = DB::table("ms_unit")
                ->where("unit_type","1");
        if (!empty($request->keywords)) {
            $data->where(DB::raw('LOWER(unit_name)'), 'like', '%' . strtolower($request->keywords) . '%');
        }
        $data = $data->get();
        return response()->json($data);
    }

    public function get_jadwal_dokter(Request $request) {
    
        $validasi = Servant::validasi_register(1,[
            "px_id"     => $request->px_id,
            "date"      => Carbon::now()->format('Y-m-d'),
            "unit_id"   => $request->unit_id
        ]);
        
        if ($validasi["code"] != 200) {
            return response()->json($validasi);
        }
        
        $data = DB::table("employee as e")
                ->join("employee_on_unit as eo","e.employee_id","=","eo.employee_id")
                ->where("eo.unit_id",$request->unit_id)
                ->get();

        $resp = [
            "code"      => "200",
            "message"   => "OK",
            "data"      => $data
        ];
        return response()->json($resp);
    }

    public function get_data_bayar() {
        $data = DB::table("ms_surety")->get();
        return response()->json($data);
    }

    public function view_register(Request $request) {
        $pasien = Patient::findOrFail($request->px_id);
        $unit   = DB::table("ms_unit")->where("unit_id",$request->poli)->first();
        $dokter   = DB::table("employee")->where("employee_id",$request->dpjp_id)->first();
        $penjamin   = DB::table("ms_surety")->where("surety_id",$request->surety_id)->first();

        $viewRegistrasi = [
            "px_norm"   => $pasien->px_norm,
            "px_name"   => $pasien->px_name,
            "px_noktp"  => $pasien->px_noktp,
            "px_address"    => $pasien->px_address,
            "poli"          => $unit->unit_name,
            "dokter"        => $dokter->employee_ft.' '.$dokter->employee_name.$dokter->employee_bt,
            "cara_bayar"    => $penjamin->surety_name
        ];

        //variable table visit
        $now = Carbon::now();
        $tglLahir = Carbon::createFromFormat('Y-m-d', $pasien->px_birthdate);

        $dataVisit = [
            'diagnosa_awal'     => "1",
            'dpjp_id'           => $dokter->employee_id,
            'last_srv_type'     => "RJ",
            'no_suratrujukan'   => "1",
            'nomor_antrian'     => "1",
            'perujuk_id'        => "0",
            'px_id'             => $pasien->px_id,
            'pxsurety_no'       => "1",
            'reg_code'          => "1",
            'reg_from'          => "1",
            'surety_id'         => $penjamin->surety_id,
            'unit_id'           => $unit->unit_id,
            'user_act'          => Carbon::now(),
            'user_id'           => "1",
            'user_ip'           => "1",
            'user_mac'          => "1",
            'visit_age_d'       => "0",
            'visit_age_m'       => "0",
            'visit_age_y'       => $now->diffInYears($tglLahir),
            'visit_date'        => Carbon::now(),
            'visit_desc'        => "1",
            'visit_px_address'  => $pasien->px_address,
            'visit_status'      => 1,
            'visit_type'        => "1"
        ];
        Cache::put("dataVisit",$dataVisit);
        return response()->json($viewRegistrasi);
    }

    public function save_register(Request $request) {
        DB::beginTransaction();
        try {
            $unit =  DB::table("ms_unit")->where("unit_id",$request->poli)->first();
            // insert no antrian
            $lastAntrian = Antrian_service::where("antrian_code",$unit->kode_antrean)->max('antrian_num');
            $dataAntrian = [
                'antrian_code'      => $unit->kode_antrean,
                'antrian_date'      => Carbon::now(),
                'antrian_group_id'  => 1,
                'antrian_num'       => ($lastAntrian+1),
                'antrian_status'    => 1
            ];
            Antrian_service::create($dataAntrian);

            //insert visit
            $sessVisit = Cache::get("dataVisit");
            $sessVisit["nomor_antrian"] = ($dataAntrian["antrian_code"]."-".$dataAntrian["antrian_num"]);
            $visit=Visit::create($sessVisit);

            DB::commit();
            $resp = [
                "code"      => 200,
                "message"   => "ok",
                "response"  => [
                    "data"  => $visit
                ]
            ];

        } catch (\Exception $E) {
            DB::rollBack();
            $resp = [
                "code"      => 201,
                "message"   => "Gagal Mendaftarkan Pasien. ".$E->getMessage()
            ];
        }

        return response()->json($resp);
    }
}
