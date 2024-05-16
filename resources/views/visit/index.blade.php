@extends('templates.layout2')
@section('content')
<?php
use \fidpro\builder\Bootstrap;
use \fidpro\builder\UiDatatable;

Widget::_init(["select2","datepicker"]);
?>
<div class="card border-0 shadow rounded" id="page_visit">
    <div class="card-header bg-success">
        <div class="btn-group-vertical">
            <button type="button" class="btn btn-danger dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-plus"></i> Daftar Pasien <i class="mdi mdi-chevron-down"></i> </button>
            <div class="dropdown-menu">
                <!-- item-->
                
                <a href="javascript:void(0);" class="dropdown-item add-form" data-target="page_visit" data-url="{{route('visit.create')}}">PASIEN LAMA</a>
            </div>
        </div>
        {!!
            Form::button("TV ANTRIAN",[
            "class" => "btn btn-warning add-form",
            "data-target" => "page_visit",
            "data-url" => route("visit.create")
            ])
        !!}
    </div>
    <div class="card-body">
        {{
            UiDatatable::init([
                "name"      => "table_visit",
                "url"       => "visit/get_dataTable",
                "attr"      => [
                    "class" => "table table-hover"
                ]
            ])
            ->column([
                'antrian/tanggal'    => [
                    "data"          => "visit_date",
                    "name"          => "v.visit_date",
                    "settings"   => [
                        "width"     => "'15%'"
                    ]
                ],
                'PASIEN'    => [
                    "data" => "px_norm",
                    "name" => "p.px_norm",
                    "settings"   => [
                        "width"     => "'45%'"
                    ]
                ],
                'POLI TUJUAN'    => [
                    "data" => "unit_name",
                    "name" => "mu.unit_name",
                    "settings"   => [
                        "width"     => "'40%'"
                    ]
                ]
            ])
            ->filterColumn([
                "visit_date"       =>   Widget::datepicker("visit_date",[
                                            "format"		=>"dd-mm-yyyy",
                                            "autoclose"		=>true
                                        ],[
                                            "readonly"      => true,
                                            "value"         => date('d-m-Y'),
                                            "required"      => "true"
                                        ])->render(),
                "px_norm"          =>   Create::input("filter_pasien",[
                                            "class"         => "form-control filter_col",
                                            "placeholder"   => ""
                                        ])->render(),
                "unit_name"        =>   Widget::select2("unit_id",[
                                            "data" => [
                                                "model"     => "Ms_unit",
                                                "column"    => ["unit_id","unit_name"]
                                            ]
                                        ])->render()
            ])
            ->extensions([
                "searching" => 'false'
            ])
            ->render();
        }}
    </div>
</div>
@endsection