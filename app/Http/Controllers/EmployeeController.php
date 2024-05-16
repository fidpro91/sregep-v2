<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Image;
use fidpro\builder\Create;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public $model   = "Employee";
    public $folder  = "employee";
    public $route   = "employee";

    public $param = [
        'absen_code'   =>  '',
        'empcat_id'   =>  '',
        'employee_active'   =>  'required',
        'employee_address'   =>  '',
        'employee_bt'   =>  '',
        'employee_ft'   =>  '',
        'employee_jabatan'   =>  '',
        'employee_name'   =>  'required',
        'employee_nik'   =>  '',
        'employee_nip'   =>  '',
        'employee_pendidikan'   =>  '',
        'employee_permanent'   =>  '',
        'employee_photo'   =>  '',
        'employee_salary'   =>  '',
        'employee_sex'   =>  'required',
        'employee_tmp_tgl_lahir'   =>  '',
        'employee_tmt'   =>  '',
        'employee_type'   =>  '',
        'kodehfis'   =>  '',
        'signature'   =>  ''
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
        return view($this->folder . '.index');
    }

    public function get_schedule_medis(Request $request)
    {
        $data = Employee::from("employee as e")
                ->where([
                    "eo.unit_id"   => $request->unit_id,
                    "sd.day"       => date("N",strtotime($request->tanggal))
                ])
                ->join("employee_on_unit as eo","e.employee_id","=","eo.employee_id")
                ->join("schedule_doctor_service as sd","e.employee_id","=","sd.par_id")
                ->selectRaw("e.employee_id,concat(e.employee_ft,e.employee_name,e.employee_bt)nama")
                ->get();
        
        return response()->json($data,200);
    }

    public function get_dataTable(Request $request)
    {
        $data = Employee::select([
            'employee_id',
            'absen_code',
            'empcat_id',
            'employee_active',
            'employee_address',
            'employee_bt',
            'employee_ft',
            'employee_jabatan',
            'employee_name',
            'employee_nik',
            'employee_nip',
            'employee_pendidikan',
            'employee_permanent',
            'employee_photo',
            'employee_salary',
            'employee_sex',
            'employee_tmp_tgl_lahir',
            'employee_tmt',
            'employee_type',
            'kodehfis',
            'signature'
        ]);

        $data->where("empcat_id",$request->empcat_id);

        $datatables = DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
            $button = "";
            //if(auth()->user()->can('18-edit')){
            $button = Create::action("<i class=\"fas fa-edit\"></i>", [
                "class"     => "btn btn-primary btn-xs",
                "onclick"   => "set_edit(this)",
                "data-url"  => route($this->route . ".edit", $data->employee_id),
                "ajax-url"  => url($this->route . '/update_data'),
                "data-target"  => "page_employee"
            ]);
            //}

            //if(auth()->user()->can('18-edit')){
            $button .= Create::action("<i class=\"fas fa-trash\"></i>", [
                "class"     => "btn btn-danger btn-xs",
                "onclick"   => "delete_row(this,function(){
                        location.reload();
                    })",
                "x-token"   => csrf_token(),
                "data-url"  => route($this->route . ".destroy", $data->employee_id),
            ]);
            //}
            return $button;
        })
        ->editColumn('employee_photo', function ($data) {
            $html = '<div class="user avatar-lg">
                <img src="'.(!empty($data->employee_photo)?asset("storage/photo-pegawai/".$data->employee_photo):asset("images/no-photo.jpeg")).'" alt="" class="img-fluid">
            </div>';
            return $html;
        })->rawColumns(['action','employee_photo']);
        return $datatables->make(true);
    }

    public function create()
    {
        $defaultValue =  array_fill_keys(array_keys($this->param), null);
        $defaultValue["employee_id"] = null;
        $employee = (object)$defaultValue;
        return view($this->folder . '.form', compact('employee'));
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
            $image=$this->upload_image($request);
            $valid["data"]["employee_photo"]    = $image["photo"];
            $valid["data"]["signature"]         = $image["signature"];

            Employee::create($valid['data']);
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

    private function upload_image($request,$oldPhoto=null)
    {
        $resp = [
            "photo"     => "",
            "signature" => ""
        ];

        if ($request->file('employee_photo')) {
            //upload new image
            Storage::disk('local')->delete('public/photo-pegawai/'.$oldPhoto);
            $image = $request->file('employee_photo');
            $path  = $image->storeAs('public/photo-pegawai', $image->hashName());
            $img = Image::make(Storage::get($path));
            $img->resize(362, 299); // Ubah ukuran gambar sesuai kebutuhan
            $img->save(storage_path('app/' . $path));
            $resp['photo'] = $image->hashName();
        }

        if ($request->input('signature')) {
            $image = $request->input('signature');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = $request->employee_nip. '.png';
            Storage::disk('ttd-pegawai')->put($imageName, base64_decode($image));
            $resp["signature"] = $imageName;
        }

        return $resp;

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

    public function edit(Employee $employee)
    {
        return view($this->folder . '.form', compact('employee'));
    }

    public function update_data(Request $request, Employee $employee)
    {
        $valid = $this->form_validasi($request->all());
        if ($valid['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => $this->form_validasi($request->all())['message']
            ]);
        }
        try {
            $data = Employee::findOrFail($request->employee_id);
            $image=$this->upload_image($request,$data->employee_photo);
            $valid["data"]["employee_photo"]    = $image["photo"];
            $valid["data"]["signature"]         = $image["signature"];

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
        $data = Employee::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);
    }
}
