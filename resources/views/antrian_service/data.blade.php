<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_antrian_service",
            "data-url" => route("antrian_service.create"),
            "data-url-store"    => route("antrian_service.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data",[
                "class" => "table table-hover"
            ],[
                "url"   => "antrian_service/get_dataTable",
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
                    'antrian_id','antrian_code','antrian_date','antrian_group_id','antrian_num','antrian_status','visit_id','visit_id_online','antrian_id','antrian_code','antrian_date','antrian_group_id','antrian_num','antrian_status','visit_id','visit_id_online','antrian_id','antrian_code','antrian_date','antrian_group_id','antrian_num','antrian_status','visit_id','visit_id_online'
                ]
            ])
        }}
    </div>
</div>