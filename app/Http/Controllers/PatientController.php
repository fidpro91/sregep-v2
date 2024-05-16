<?php

namespace App\Http\Controllers;

use App\Libraries\Apivclaim;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public $model   = "Patient";
    public $folder  = "patient";
    public $route   = "patient";

    public $param = [
        'company_id'   =>  '',
        'edu_id'   =>  '',
        'is_data_migrasi'   =>  '',
        'lag_id'   =>  '',
        'position_id'   =>  '',
        'px_active'   =>  '',
        'px_address'   =>  '',
        'px_birthdate'   =>  '',
        'px_bloodgroup'   =>  '',
        'px_born'   =>  '',
        'px_city'   =>  '',
        'px_district'   =>  '',
        'px_id_ektp'   =>  '',
        'px_name'   =>  'required',
        'px_nik'   =>  '',
        'px_nokk'   =>  '',
        'px_noktp'   =>  '',
        'px_norm'   =>  '',
        'px_phone'   =>  '',
        'px_prov'   =>  '',
        'px_reg'   =>  '',
        'px_regby'   =>  '',
        'px_resident'   =>  '',
        'px_rfid'   =>  '',
        'px_sex'   =>  'required',
        'px_status'   =>  '',
        'religion_id'   =>  '',
        'status_id_khusus'   =>  '',
        'tribe_id'   =>  '',
        'user_ip'   =>  '',
        'work_id'   =>  ''
    ];

    function __construct()
    {
        /*
        $this->middleware('permission:18-read', ['only' => ['index']]);
        $this->middleware('permission:18-create', ['only' => ['create','store']]);
        $this->middleware('permission:18-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:18-delete', ['only' => ['destroy']]);
        */
    }

    public function index()
    {
        return $this->themes($this->folder . '.index', null, $this);
    }

    public function get_dataTable(Request $request)
    {
        $data = Patient::select([
            'px_id',
            'company_id',
            'edu_id',
            'is_data_migrasi',
            'lag_id',
            'position_id',
            'px_active',
            'px_address',
            'px_birthdate',
            'px_bloodgroup',
            'px_born',
            'px_city',
            'px_district',
            'px_id_ektp',
            'px_name',
            'px_nik',
            'px_nokk',
            'px_noktp',
            'px_norm',
            'px_phone',
            'px_prov',
            'px_reg',
            'px_regby',
            'px_resident',
            'px_rfid',
            'px_sex',
            'px_status',
            'religion_id',
            'status_id_khusus',
            'tribe_id',
            'user_ip',
            'work_id'
        ]);
        if ($request->px_norm) {
            $data->where("px_norm",$request->px_norm);
        }
        if ($request->px_name) {
            $data->whereRaw("lower(px_name) like '%".strtolower($request->px_name)."%'");
        }
        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button = "";
            //if(auth()->user()->can('18-edit')){
            $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                "class"     => "btn btn-primary btn-xs",
                "onclick"   => "set_edit(this)",
                "data-url"  => route($this->route . ".edit", $data->px_id),
                "ajax-url"  => route($this->route . '.update', $data->px_id),
                "data-target"  => "page_patient"
            ]);
            //}

            //if(auth()->user()->can('18-edit')){
            $button .= Create::action("<i class=\"fas fa-trash\"></i>", [
                "class"     => "btn btn-danger btn-xs",
                "onclick"   => "delete_row(this)",
                "x-token"   => csrf_token(),
                "data-url"  => route($this->route . ".destroy", $data->px_id),
            ]);
            //}
            return $button;
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function get_peserta_bpjs(Request $request){
        $noka = $request->noka;
        $type = $request->type ?? "nokartu";
        $url = "Peserta/$type/$noka/tglSEP/".date("Y-m-d");
        $data = Apivclaim::connect("get",$url);

        if ($data["metaData"]["code"] == 200) {
            $data["response"]["peserta"]["tglLahir"] = date_indo($data["response"]["peserta"]["tglLahir"]);
            $resp = [
                "code"      => $data["metaData"]["code"],
                "message"   => "OK",
                "response"  => $data["response"]["peserta"]
            ];
        }else{
            $resp = [
                "code"      => $data["metaData"]["code"],
                "message"   => $data["metaData"]["message"]
            ];
        }
        return response()->json($resp,$resp["code"]);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $patient = (object)$defaultValue;
        return view($this->folder . '.form', compact('patient'));
    }

    public function get_patient($type,Request $request)
    {
        $data = Patient::where($type,$request->term)
                ->selectRaw("*,px_norm as value,px_name as label")
                ->get();
        
        return response()->json($data,200);
    }

    public function store(Request $request)
    {
        $request["px_birthdate"] = date_db($request->px_birthdate);
        $request["px_norm"] = Patient::max('px_norm')+1;
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $valid['message']
            ]);
        }
        DB::beginTransaction();
        try {
            $px=Patient::create($valid['data']);
            //insert family patient
            if (is_array($request->family_px)) {
                foreach ($request->family_px as $key => $value) {
                    $family = [
                        "px_id"             => $px->px_id,
                        "name"              => $value["family_name"],
                        "status_hubungan"   => $value["status_hubungan"],
                        "no_telp"           => $value["no_telp"]
                    ];
                    DB::table("family_patient")->insert($family);
                }
            }

            //insert cara bayar
            if (is_array($request->multipay)) {
                foreach ($request->multipay as $key => $value) {
                    $data = [
                        "px_id"             => $px->px_id,
                        "surety_id"         => $value["surety_id"],
                        "pxsurety_no"       => $value["pxsurety_no"],
                        "class_id"          => 4,
                        "pxsurety_status"   => "PESERTA"
                    ];
                    DB::table("patient_surety")->insert($data);
                }
            }
            DB::commit();
            $resp = [
                'success'       => true,
                'message'       => 'Data Berhasil Disimpan. Lanjutkan Pendaftaran Poli?',
                "response"      => [
                    "px_id"     => $px->px_id,
                    "px_norm"   => $request->px_norm,
                    "px_name"   => $request->px_name
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Disimpan! <br>' . $e->getMessage()." line ".$e->getLine()
            ];
        }
        return response()->json($resp);
    }

    private function form_validasi($data)
    {
        $validator = Validator::make($data, $this->param);
        //check if validation fails
        if ($validator->fails()) {
            return [
                "code"      => "201",
                "message"   => implode("<br>", $validator->errors()->all())
            ];
        }
        //filter
        $filter = array_keys($this->param);
        $input = array_filter(
            $data,
            fn ($key) => in_array($key, $filter),
            ARRAY_FILTER_USE_KEY
        );
        return [
            "code"      => "200",
            "data"      => $input
        ];
    }

    public function edit(Patient $patient)
    {
        return view($this->folder . '.form', compact('patient'));
    }
    public function update(Request $request, Patient $patient)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Patient::findOrFail($patient->px_id);
            $data->update($valid['data']);
            $resp = [
                'success' => true,
                'message' => 'Data Berhasil Diupdate!',
                'redirect'  => view($this->folder . ".data")->render()
            ];
        } catch (\Exception $e) {
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Diupdate! <br>' . $e->getMessage()
            ];
        }
        return response()->json($resp);
    }

    public function destroy($id)
    {
        $data = Patient::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
