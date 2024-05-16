@extends('templates.layout2')
@section('content')
<?php
use \fidpro\builder\Bootstrap;
?>
<div class="card border-0 shadow rounded" id="page_schedule_doctor_service">
    @include("schedule_doctor_service.data")
</div>
@endsection