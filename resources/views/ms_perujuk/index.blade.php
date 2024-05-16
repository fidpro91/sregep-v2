@extends('templates.layout2')
@section('content')
<?php
use \fidpro\builder\Bootstrap;
?>
<div class="card border-0 shadow rounded" id="page_ms_perujuk">
    @include("ms_perujuk.data")
</div>
@endsection