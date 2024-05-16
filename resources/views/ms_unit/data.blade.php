<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_ms_unit",
            "data-url" => route("ms_unit.create"),
            "data-url-store"    => route("ms_unit.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data-unit",[
                "class" => "table table-hover"
            ],[
                "url"       => "ms_unit/get_dataTable",
                "filter"    => [
                    "unit_type" => "catUnitId"
                ],
                "raw"   => [
                    '#'     => [
                        "data" => "action", 
                        "name" => "action", 
                        "settings" => [
                            "orderable" => "false", 
                            "searchable" => "false"
                        ]
                    ],
                    'no'    => [
                        "data" => "DT_RowIndex", 
                        "settings" => [
                            "orderable" => "false", 
                            "searchable" => "false"
                        ]
                    ],
                    'unit_logo','unit_code','unit_name','unit_nickname','is_vip','kode_antrean','kode_poli_jkn','kode_subspesialis','kodeaskes','unit_active'
                ]
            ])
        }}
    </div>
</div>