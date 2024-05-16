<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_ms_perujuk",
            "data-url" => route("ms_perujuk.create"),
            "data-url-store"    => route("ms_perujuk.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data",[
                "class" => "table table-hover"
            ],[
                "url"   => "ms_perujuk/get_dataTable",
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
                    'perujuk_id','created_at','kode_faskes_bpjs','perujuk_active','perujuk_address','perujuk_city','perujuk_district','perujuk_name','perujuk_phone','perujuk_prov','perujuk_resident','tipe_ppk','updated_at','perujuk_id','created_at','kode_faskes_bpjs','perujuk_active','perujuk_address','perujuk_city','perujuk_district','perujuk_name','perujuk_phone','perujuk_prov','perujuk_resident','tipe_ppk','updated_at','perujuk_id','created_at','kode_faskes_bpjs','perujuk_active','perujuk_address','perujuk_city','perujuk_district','perujuk_name','perujuk_phone','perujuk_prov','perujuk_resident','tipe_ppk','updated_at'
                ]
            ])
        }}
    </div>
</div>