@extends('templates.layout2')
@section('content')
<?php
use \fidpro\builder\Bootstrap;
?>
<div class="card border-0 shadow rounded" id="page_employee_categories">
    @include("employee_categories.data")
</div>
{{
    Bootstrap::modal('modal_employee',[
        "title"   => 'List Data Pegawai',
        "size"    => "modal-xl",
        "body"    => [
                        "content"   => ""
                    ]
    ])
}}
<script>
    var empCatId;
    function show_data(id) {
        $("#modal_employee").modal("show");
        $("#modal_employee").find(".modal-body").load("{{route('employee.index')}}",function(){
            empCatId = id;
        });
    }
</script>
@endsection