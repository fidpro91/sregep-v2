<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_patient_surety",
            "data-url" => route("patient_surety.create"),
            "data-url-store"    => route("patient_surety.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data",[
                "class" => "table table-hover"
            ],[
                "url"   => "patient_surety/get_dataTable",
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
                    'id','class_id','kode_ppk','ppk_rujukan','px_id','pxsurety_no','pxsurety_status','surety_id','id','class_id','kode_ppk','ppk_rujukan','px_id','pxsurety_no','pxsurety_status','surety_id','id','class_id','kode_ppk','ppk_rujukan','px_id','pxsurety_no','pxsurety_status','surety_id'
                ]
            ])
        }}
    </div>
</div>