<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    public $model   = "Visit";
    public $folder  = "visit";
    public $route   = "visit";
    public $schema  = "public";

    public $param = [
        'created_at'   =>  '',
        'diagnosa_awal'   =>  '',
        'dpjp_id'   =>  '',
        'last_srv_type'   =>  '',
        'no_suratrujukan'   =>  '',
        'nomor_antrian'   =>  '',
        'sep_no'   =>  '',
        'perujuk_id'   =>  '',
        'px_id'   =>  'required',
        'pxsurety_no'   =>  '',
        'reg_code'   =>  '',
        'reg_from'   =>  'required',
        'surety_id'   =>  'required',
        'unit_id'   =>  '',
        'updated_at'   =>  '',
        'user_act'   =>  '',
        'user_id'   =>  '',
        'user_ip'   =>  '',
        'user_mac'   =>  '',
        'visit_age_d'   =>  '',
        'visit_age_m'   =>  '',
        'visit_age_y'   =>  '',
        'visit_date'   =>  'required',
        'visit_desc'   =>  '',
        'visit_finish'   =>  '',
        'visit_px_address'   =>  '',
        'visit_status'   =>  '',
        'visit_type'   =>  'required'
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
        $data = Visit::from($this->schema.".visit as v")
                ->join("patient as p","p.px_id","=","v.px_id")
                ->join("ms_unit as mu","mu.unit_id","=","v.unit_id")
                ->join("employee as e","e.employee_id","=","v.dpjp_id")
                ->join("ms_surety as sur","sur.surety_id","=","v.surety_id")
                ->select([
                    'v.visit_id',
                    'v.nomor_antrian',
                    'p.px_id',
                    'v.visit_date',
                    'v.visit_status',
                    'mu.unit_id',
                    'p.px_norm',
                    'p.px_name',
                    'p.px_address',
                    'mu.unit_name',
                    DB::raw("concat(e.employee_ft, ' ', e.employee_name, ' ', e.employee_bt) as dokter")
                ]);
            $data->whereRaw(
                "date(v.visit_date) = '".date_db($request->visit_date)."'"
            );

            if ($request->unit_id) {
                $data->where([
                    "s.unit_id"   => $request->unit_id
                ]);
            }
            $datatables = DataTables::of($data)->addIndexColumn()
            ->editColumn('px_norm',function($data){
                $html = '
                <div class="cardbox">
                    <div class="user avatar-lg float-left mr-2">
                        <img src="'.asset('assets/images/patient.png').'" alt="" class="img-fluid rounded-circle">
                    </div>
                    <div class="user-desc">
                        <h5 class="name mt-0 mb-1 font-22">'.$data->px_norm.'</h5>
                        <p class="desc text-muted mb-0 font-18">'.$data->px_name.'</p>
                        <p class="desc text-muted mb-0 font-14">'.$data->px_address.'</p>
                    </div>
                </div>';
                return $html;
            })
            ->editColumn('unit_name',function($data){
                $button = "";
                //if(auth()->user()->can('18-edit')){
                $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                    "class"     => "btn btn-primary btn-xs",
                    "onclick"   => "set_edit(this)",
                    "data-url"  => route($this->route . ".edit", $data->visit_id),
                    "ajax-url"  => route($this->route . '.update', $data->visit_id),
                    "data-target"  => "page_visit"
                ]);
                //}

                //if(auth()->user()->can('18-edit')){
                $button .= Create::action("<i class=\"fas fa-trash\"></i>", [
                    "class"     => "btn btn-danger btn-xs",
                    "onclick"   => "delete_row(this)",
                    "x-token"   => csrf_token(),
                    "data-url"  => route($this->route . ".destroy", $data->visit_id),
                ]);
                //}
                $html = '
                <div class="cardbox">
                    <div class="float-right">'.$button.'</div>
                    <h5 class="name mt-0 mb-1 font-18">'.$data->unit_name.'</h5>
                    <p class="desc text-muted mb-0 font-14">'.$data->dokter.'</p>
                    <span class="badge badge-success font-16">Released</span>
                </div>';
                return $html;
            })
            ->editColumn('visit_date',function($data){
                $html = '
                <div class="cardbox text-center">
                    <h3 class="name mt-3 mb-0">'.$data->nomor_antrian.'</h3>
                    <p class="desc mb-0 font-14"><i class="far fa-calendar-alt"></i> '.date_indo($data->visit_date).'</p>
                </div>';
                return $html;
            })->rawColumns(['action','px_norm','unit_name','visit_date']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["visit_id"] = null;
        $defaultValue["kll_id"] = null;
        $defaultValue["px_norm"] = null;
        $defaultValue["px_name"] = null;
        $defaultValue["px_noktp"] = null;
        $defaultValue["px_phone"] = null;
        $defaultValue["px_address"] = null;
        $defaultValue["unit_type"] = null;
        $defaultValue["srv_note"] = null;
        $defaultValue["reff_id"] = null;
        $defaultValue["px_id"] = null;
        $defaultValue["visit_id"] = null;
        $visit = (object)$defaultValue;
        return view($this->folder . '.form', compact('visit'));
    }

    public function store(Request $request)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $valid['message']
            ]);
        }
        try {
            Visit::create($valid['data']);
            $resp = [
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'redirect'  => view($this->folder . ".data")->render()
            ];
        } catch (\Exception $e) {
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Disimpan! <br>' . $e->getMessage()
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

    public function edit(Visit $visit)
    {
        return view($this->folder . '.form', compact('visit'));
    }
    public function update(Request $request, Visit $visit)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Visit::findOrFail($visit->visit_id);
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
        $data = Visit::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
