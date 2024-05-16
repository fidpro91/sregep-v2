<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ms_category_unit;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;

class Ms_category_unitController extends Controller
{
    public $model   = "Ms_category_unit";
    public $folder  = "ms_category_unit";
    public $route   = "ms_category_unit";

    public $param = [
        'catunit_code'   =>  '',
        'catunit_name'   =>  'required',
        'catunit_status'   =>  '',
        'has_child'   =>  '',
        'parent_id'   =>  ''
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
        $data = Ms_category_unit::select([
            'catunit_id',
            'catunit_code',
            'catunit_name',
            'catunit_status',
            'has_child',
            'parent_id'
        ]);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button = "";
            //if(auth()->user()->can('18-edit')){
            $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                "class"     => "btn btn-primary btn-xs",
                "onclick"   => "set_edit(this)",
                "data-url"  => route($this->route . ".edit", $data->catunit_id),
                "ajax-url"  => route($this->route . '.update', $data->catunit_id),
                "data-target"  => "page_ms_category_unit"
            ]);
            //}

            //if(auth()->user()->can('18-edit')){
            $button .= Create::action("<i class=\"fas fa-trash\"></i>", [
                "class"     => "btn btn-danger btn-xs",
                "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                "x-token"   => csrf_token(),
                "data-url"  => route($this->route . ".destroy", $data->catunit_id),
            ]);
            //}
            $button .= Create::action("<i class=\"fas fa-list\"></i>", [
                "class"     => "btn btn-purple btn-xs",
                "onclick"   => "show_unit($data->catunit_id)"
            ]);
            return $button;
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["catunit_id"] = null;
        $ms_category_unit = (object)$defaultValue;
        return view($this->folder . '.form', compact('ms_category_unit'));
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
            Ms_category_unit::create($valid['data']);
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

    public function edit(Ms_category_unit $ms_category_unit)
    {
        return view($this->folder . '.form', compact('ms_category_unit'));
    }
    public function update(Request $request, Ms_category_unit $ms_category_unit)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Ms_category_unit::findOrFail($ms_category_unit->catunit_id);
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
        $data = Ms_category_unit::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
