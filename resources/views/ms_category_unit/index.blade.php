@extends('templates.layout2')
@section('content')
<?php
use \fidpro\builder\Bootstrap;
?>
<div class="card border-0 shadow rounded" id="page_ms_category_unit">
    @include("ms_category_unit.data")
</div>
{{
    Bootstrap::modal('modal_unit',[
        "title"   => 'List Data Unit',
        "size"    => "modal-xl",
        "body"    => [
                        "content"   => ""
                    ]
    ])
}}
<script>
    var catUnitId;
    function show_unit(id) {
        $("#modal_unit").modal("show");
        $("#modal_unit").find(".modal-body").load("{{route('ms_unit.index')}}",function(){
            catUnitId = id;
        });
    }
</script>
@endsection