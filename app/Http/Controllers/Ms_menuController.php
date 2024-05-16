<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ms_menu;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;

class Ms_menuController extends Controller
{
    public $model   = "Ms_menu";
    public $folder  = "ms_menu";
    public $route   = "ms_menu";
    
    public $param = [
'menu_code'   =>  'required',
'menu_function'   =>  '',
'menu_icon'   =>  '',
'menu_name'   =>  'required',
'menu_parent_id'   =>  '',
'menu_status'   =>  '',
'menu_target'   =>  '',
'menu_url'   =>  '',
'modul_id'   =>  ''
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
        $data = Ms_menu::select([
'menu_id',
'menu_code',
'menu_function',
'menu_icon',
'menu_name',
'menu_parent_id',
'menu_status',
'menu_target',
'menu_url',
'modul_id'
]);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button= "";
            //if(auth()->user()->can('18-edit')){
                $button = Create::action("<i class=\"fas fa-edit\"></i>",[
                    "class"     => "btn btn-primary btn-xs",
                    "onclick"   => "set_edit(this)",
                    "data-url"  => route($this->route.".edit",$data->menu_id),
                    "ajax-url"  => route($this->route.'.update',$data->menu_id),
                    "data-target"  => "page_ms_menu"
                ]);
            //}

            //if(auth()->user()->can('18-edit')){
                $button .= Create::action("<i class=\"fas fa-trash\"></i>",[
                    "class"     => "btn btn-danger btn-xs",
                    "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                    "x-token"   => csrf_token(),
                    "data-url"  => route($this->route.".destroy",$data->menu_id),
                ]);
            //}
            return $button;
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["menu_id"] = null;
        $ms_menu = (object)$defaultValue;
        return view($this->folder . '.form',compact('ms_menu'));
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
            Ms_menu::create($valid['data']);
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

    public function edit(Ms_menu $ms_menu)
    {
        return view($this->folder . '.form', compact('ms_menu'));
    }
    public function update(Request $request, Ms_menu $ms_menu)
    {
        $valid = $this->form_validasi($request->all());
        if($valid['code'] != 200){
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Ms_menu::findOrFail($ms_menu->menu_id);
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
        $data = Ms_menu::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
