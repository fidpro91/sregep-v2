<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

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
        $data = DB::table("employee as e")
                ->join("employee_on_unit as eo","e.employee_id","=","eo.employee_id")
                ->where("eo.unit_id",$request->unit_id)
                ->get();
        return response()->json($data);
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
        return response()->json($viewRegistrasi);
    }
}
