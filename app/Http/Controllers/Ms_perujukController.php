<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ms_perujuk;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;

class Ms_perujukController extends Controller
{
    public $model   = "Ms_perujuk";
    public $folder  = "ms_perujuk";
    public $route   = "ms_perujuk";
    
    public $param = [
'created_at'   =>  '',
'kode_faskes_bpjs'   =>  '',
'perujuk_active'   =>  '',
'perujuk_address'   =>  '',
'perujuk_city'   =>  '',
'perujuk_district'   =>  '',
'perujuk_name'   =>  'required',
'perujuk_phone'   =>  '',
'perujuk_prov'   =>  '',
'perujuk_resident'   =>  '',
'tipe_ppk'   =>  'required',
'updated_at'   =>  ''
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
        return $this->themes($this->folder . '.index',null,$this);
    }

    public function get_dataTable(Request $request)
    {
        $data = Ms_perujuk::select([
'perujuk_id',
'created_at',
'kode_faskes_bpjs',
'perujuk_active',
'perujuk_address',
'perujuk_city',
'perujuk_district',
'perujuk_name',
'perujuk_phone',
'perujuk_prov',
'perujuk_resident',
'tipe_ppk',
'updated_at'
]);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button= "";
            //if(auth()->user()->can('18-edit')){
                $button = Create::action("<i class=\"fas fa-edit\"></i>",[
                    "class"     => "btn btn-primary btn-xs",
                    "onclick"   => "set_edit(this)",
                    "data-url"  => route($this->route.".edit",$data->perujuk_id),
                    "ajax-url"  => route($this->route.'.update',$data->perujuk_id),
                    "data-target"  => "page_ms_perujuk"
                ]);
            //}

            //if(auth()->user()->can('18-edit')){
                $button .= Create::action("<i class=\"fas fa-trash\"></i>",[
                    "class"     => "btn btn-danger btn-xs",
                    "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                    "x-token"   => csrf_token(),
                    "data-url"  => route($this->route.".destroy",$data->perujuk_id),
                ]);
            //}
            return $button;
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["perujuk_id"] = null;
        $ms_perujuk = (object)$defaultValue;
        return view($this->folder . '.form',compact('ms_perujuk'));
    }

    public function store(Request $request)
    {
        $valid = $this->form_validasi($request->all());
        if($valid['code'] != 200){
            return response()->json([
                'success' => false,
                'message' => $valid['message']
            ]);
        }
        try {
            Ms_perujuk::create($valid['data']);
            $resp = [
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'redirect'  => view($this->folder.".data")->render()
            ];
        }catch(\Exception $e){
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Disimpan! <br>'.$e->getMessage()
            ];
        }
        return response()->json($resp);
    }

    private function form_validasi($data){
        $validator = Validator::make($data, $this->param);
        //check if validation fails
        if ($validator->fails()) {
            return [
                "code"      => "201",
                "message"   => implode("<br>",$validator->errors()->all())
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

    public function edit(Ms_perujuk $ms_perujuk)
    {
        return view($this->folder . '.form', compact('ms_perujuk'));
    }
    public function update(Request $request, Ms_perujuk $ms_perujuk)
    {
        $valid = $this->form_validasi($request->all());
        if($valid['code'] != 200){
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Ms_perujuk::findOrFail($ms_perujuk->perujuk_id);
            $data->update($valid['data']);
            $resp = [
                'success' => true,
                'message' => 'Data Berhasil Diupdate!',
                'redirect'  => view($this->folder.".data")->render()
            ];
        }catch(\Exception $e){
            $resp = [
                'success' => false,
                'message' => 'Data Gagal Diupdate! <br>'.$e->getMessage()
            ];
        }
        return response()->json($resp);
    }

    public function destroy($id)
    {
        $data = Ms_perujuk::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
