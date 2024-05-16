<div class="card-header">
    {!!
        Form::button("Tambah",[
            "class" => "btn btn-primary add-form",
            "data-target" => "page_ms_menu",
            "data-url" => route("ms_menu.create"),
            "data-url-store"    => route("ms_menu.store")
        ])
    !!}
</div>
<div class="card-body">
    <div class="table-responsive">
        {{
            Bootstrap::DataTable("table-data",[
                "class" => "table table-hover"
            ],[
                "url"   => "ms_menu/get_dataTable",
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
                    'menu_id','menu_code','menu_function','menu_icon','menu_name','menu_parent_id','menu_status','menu_target','menu_url','modul_id','menu_id','menu_code','menu_function','menu_icon','menu_name','menu_parent_id','menu_status','menu_target','menu_url','modul_id','menu_id','menu_code','menu_function','menu_icon','menu_name','menu_parent_id','menu_status','menu_target','menu_url','modul_id'
                ]
            ])
        }}
    </div>
</div>