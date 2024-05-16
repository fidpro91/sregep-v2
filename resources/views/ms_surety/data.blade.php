<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_ms_surety",
            "data-url" => route("ms_surety.create"),
            "data-url-store"    => route("ms_surety.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data",[
                "class" => "table table-hover"
            ],[
                "url"   => "ms_surety/get_dataTable",
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
                    'surety_code','surety_name','surety_group','surety_group_antrian','surety_organizer','surety_active'
                ]
            ])
        }}
    </div>
</div>