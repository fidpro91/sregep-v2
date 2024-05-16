<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_employee",
            "data-url" => route("employee.create"),
            "data-url-store"    => route("employee.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data-pegawai",[
                "class" => "table table-hover"
            ],[
                "url"       => "employee/get_dataTable",
                "filter"    => [
                    "empcat_id" => "empCatId"
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
                    'employee_photo','employee_nip','employee_name','employee_address','employee_active'
                ]
            ])
        }}
    </div>
</div>