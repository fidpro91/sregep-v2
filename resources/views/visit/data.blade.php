<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_visit",
            "data-url" => route("visit.create"),
            "data-url-store"    => route("visit.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data",[
                "class" => "table table-hover"
            ],[
                "url"   => "visit/get_dataTable",
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
                    'diagnosa_awal','dpjp_id','last_srv_type','no_suratrujukan','nomor_antrian','perujuk_id','px_id','pxsurety_no','reg_code','reg_from','surety_id','unit_id','updated_at','user_act','user_id','user_ip','user_mac','visit_age_d','visit_age_m','visit_age_y','visit_date','visit_desc','visit_finish','visit_px_address','visit_status','visit_type'
                ]
            ])
        }}
    </div>
</div>