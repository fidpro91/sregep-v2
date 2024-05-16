<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_ms_reff",
            "data-url" => route("ms_reff.create"),
            "data-url-store"    => route("ms_reff.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data",[
                "class" => "table table-hover"
            ],[
                "url"   => "ms_reff/get_dataTable",
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
                    'reff_id','has_detail','refcat_id','reff_active','reff_code','reff_name','reff_id','has_detail','refcat_id','reff_active','reff_code','reff_name','reff_id','has_detail','refcat_id','reff_active','reff_code','reff_name'
                ]
            ])
        }}
    </div>
</div>