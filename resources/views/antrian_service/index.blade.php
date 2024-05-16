@extends('templates.layout2')
@section('content')
<?php
use \fidpro\builder\Bootstrap;
?>
<div class="card border-0 shadow rounded" id="page_antrian_service">
    @include("antrian_service.data")
</div>
@endsection