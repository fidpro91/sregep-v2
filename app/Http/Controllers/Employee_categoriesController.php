<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee_categories;
use Illuminate\Support\Facades\Validator;
use DataTables;
use fidpro\builder\Create;

class Employee_categoriesController extends Controller
{
    public $model   = "Employee_categories";
    public $folder  = "employee_categories";
    public $route   = "employee_categories";

    public $param = [
        'empcat_active'   =>  'required',
        'empcat_code'   =>  '',
        'empcat_name'   =>  'required',
        'empcat_parent'   =>  ''
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
        $data = Employee_categories::select([
            'empcat_id',
            'empcat_active',
            'empcat_code',
            'empcat_name',
            'empcat_parent'
        ]);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button = "";
            //if(auth()->user()->can('18-edit')){
            $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                "class"     => "btn btn-primary btn-xs",
                "onclick"   => "set_edit(this)",
                "data-url"  => route($this->route . ".edit", $data->empcat_id),
                "ajax-url"  => route($this->route . '.update', $data->empcat_id),
                "data-target"  => "page_employee_categories"
            ]);
            //}

            //if(auth()->user()->can('18-edit')){
            $button .= Create::action("<i class=\"fas fa-trash\"></i>", [
                "class"     => "btn btn-danger btn-xs",
                "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                "x-token"   => csrf_token(),
                "data-url"  => route($this->route . ".destroy", $data->empcat_id),
            ]);
            //}

            $button .= Create::action("<i class=\"fas fa-list\"></i>", [
                "class"     => "btn btn-purple btn-xs",
                "onclick"   => "show_data($data->empcat_id)",
            ]);
            return $button;
        })->rawColumns(['action']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["empcat_id"] = null;
        $employee_categories = (object)$defaultValue;
        return view($this->folder . '.form', compact('employee_categories'));
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
            Employee_categories::create($valid['data']);
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

    public function edit(Employee_categories $employee_categories)
    {
        return view($this->folder . '.form', compact('employee_categories'));
    }
    public function update(Request $request, Employee_categories $employee_categories)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Employee_categories::findOrFail($employee_categories->empcat_id);
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
        $data = Employee_categories::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
