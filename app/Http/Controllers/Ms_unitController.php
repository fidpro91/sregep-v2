<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ms_unit;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Image;
use fidpro\builder\Create;
use Illuminate\Support\Facades\Storage;

class Ms_unitController extends Controller
{
    public $model   = "Ms_unit";
    public $folder  = "ms_unit";
    public $route   = "ms_unit";

    public $param = [
        'is_service'   =>  '',
        'is_vip'   =>  '',
        'kode_antrean'   =>  '',
        'kode_poli_jkn'   =>  '',
        'kode_subspesialis'   =>  '',
        'kodeaskes'   =>  '',
        'unit_active'   =>  'required',
        'unit_code'   =>  '',
        // 'unit_id_parent'   =>  'required',
        'unit_inpatient_status'   =>  '',
        'unit_logo'   =>  '',
        'unit_name'   =>  'required',
        'unit_nickname'   =>  '',
        'unit_support_status'   =>  '',
        'unit_type'   =>  ''
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

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $this->themes($this->folder . '.index', null, $this);
    }

    public function get_dataTable(Request $request)
    {
        $data = Ms_unit::select([
            'unit_id',
            'is_service',
            'is_vip',
            'kode_antrean',
            'kode_poli_jkn',
            'kode_subspesialis',
            'kodeaskes',
            'unit_active',
            'unit_code',
            'unit_id_parent',
            'unit_inpatient_status',
            'unit_logo',
            'unit_name',
            'unit_nickname',
            'unit_support_status',
            'unit_type'
        ]);

        $data->where("unit_type",$request->unit_type);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button = "";
            //if(auth()->user()->can('18-edit')){
            $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                "class"     => "btn btn-primary btn-xs",
                "onclick"   => "set_edit(this)",
                "data-url"  => route($this->route . ".edit", $data->unit_id),
                "ajax-url"  => url($this->route . '/update_data'),
                "data-target"  => "page_ms_unit",
                "data-method"  => "post"
            ]);
            //}

            //if(auth()->user()->can('18-edit')){
            $button .= Create::action("<i class=\"fas fa-trash\"></i>", [
                "class"     => "btn btn-danger btn-xs",
                "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                "x-token"   => csrf_token(),
                "data-url"  => route($this->route . ".destroy", $data->unit_id),
            ]);
            //}
            return $button;
        })
        ->editColumn('unit_logo', function ($data) {
            $html = '<div class="user avatar-lg">
                <img src="'.(!empty($data->unit_logo)?asset("storage/klinik/".$data->unit_logo):asset("images/klinik/klinik.png")).'" alt="" class="img-fluid">
            </div>';
            return $html;
        })->rawColumns(['action','unit_logo']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["unit_id"] = null;
        $ms_unit = (object)$defaultValue;
        return view($this->folder . '.form', compact('ms_unit'));
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
            if ($request->file('unit_logo')) {
                //upload new image
                $image = $request->file('unit_logo');
                $path  = $image->storeAs('public/klinik', $image->hashName());
                $img = Image::make(Storage::get($path));
                $img->resize(362, 299); // Ubah ukuran gambar sesuai kebutuhan
                $img->save(storage_path('app/' . $path));
                $valid['data']['unit_logo'] = $image->hashName();
            }
            Ms_unit::create($valid['data']);
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

    public function get_unit($cat_unit)
    {
        $data = Ms_unit::where("unit_type",$cat_unit)->get();

        return response()->json($data,200);
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

    public function edit(Ms_unit $ms_unit)
    {
        return view($this->folder . '.form', compact('ms_unit'));
    }

    public function update_data(Request $request, Ms_unit $ms_unit)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Ms_unit::findOrFail($request->unit_id);
            if ($request->file('unit_logo')) {
                Storage::disk('local')->delete('public/klinik/'.$data->unit_logo);
                //upload new image
                $image = $request->file('unit_logo');
                $path  = $image->storeAs('public/klinik', $image->hashName());
                $img = Image::make(Storage::get($path));
                $img->resize(362, 299); // Ubah ukuran gambar sesuai kebutuhan
                $img->save(storage_path('app/' . $path));
                $valid['data']['unit_logo'] = $image->hashName();
            }
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
        $data = Ms_unit::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
