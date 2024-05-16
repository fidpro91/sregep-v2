@extends('templates.layout2')
@section('content')
<?php
use \fidpro\builder\Bootstrap;
?>
<div class="card border-0 shadow rounded" id="page_patient_surety">
    @include("patient_surety.data")
</div>
@endsection