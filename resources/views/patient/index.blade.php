@extends('templates.layout2')
@section('content')
<?php
use \fidpro\builder\Bootstrap;
use fidpro\builder\UiDatatable;
?>
<div class="card border-0 shadow rounded" id="page_patient">
    <div class="card-header">
        {!!
            Form::button("Tambah",[
                "class" => "btn btn-primary add-form",
                "data-target" => "page_patient",
                "data-url" => route("patient.create")
            ])
        !!}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            {{
                UiDatatable::init([
                    "name"      => "table_patient",
                    "url"       => "patient/get_dataTable",
                    "attr"      => [
                        "class" => "table table-hover"
                    ]
                ])
                ->column([
                    '#'     => [
                        "data" => "action", 
                        "name" => "action", 
                        "orderable" => "false", 
                        "searchable" => "false"
                    ],
                    'no'    => [
                        "data" => "DT_RowIndex",
                        "orderable" => "false", 
                        "searchable" => "false"
                    ],
                    'px_norm','px_noktp','px_name','px_birthdate','px_sex','px_address','px_active'
                ])
                ->filterColumn([
                    "px_norm"       =>  Create::input("px_norm",[
                                            "class"         => "form-control filter_col",
                                            "placeholder"   => ""
                                        ])->render(),
                    "px_name"       =>  Create::input("px_name",[
                                            "class"         => "form-control filter_col",
                                            "placeholder"   => ""
                                        ])->render(),
                    "px_noktp"      =>  Create::input("px_noktp",[
                                            "class"         => "form-control filter_col",
                                            "placeholder"   => ""
                                        ])->render(),
                    "px_sex"        =>  Create::dropdown("px_sex",[
                                            "data" => [
                                                "L"   => ["Laki-laki"],
                                                "P"   => ["Perempuan"],
                                            ],
                                            "nullable"  => 'true'
                                        ])->render(),
                    "px_address"      =>  Create::input("px_address",[
                                            "class"         => "form-control filter_col",
                                            "placeholder"   => ""
                                        ])->render(),
                ])
                ->extensions([
                    "searching" => 'false'
                ])
                ->render();
            }}
        </div>
    </div>
</div>
@endsection