<?php
use \fidpro\builder\Create;
?>

<fieldset>
    <legend><i class="fas fa-info-circle"></i> Informasi Tambahan</legend>
    <div class="row">
        <div class="col-md-4">
            {!!
            Create::dropDown("tribe_id",[
            "data" => [
            "model" => "Ms_reff",
            "filter" => ["refcat_id" => "32"],
            "column" => ["reff_id","reff_name"]
            ],
            "extra" => [
            "required" => "true"
            ]
            ])->render("group","Suku Bangsa")
            !!}
            {!!
            Create::dropDown("lag_id",[
            "data" => [
            "model" => "Ms_reff",
            "filter" => ["refcat_id" => "31"],
            "column" => ["reff_code","reff_name"]
            ],
            "extra" => [
            "required" => "true"
            ]
            ])->render("group","Bahasa")
            !!}
        </div>
        <div class="col-md-4">
            {!!
            Create::dropDown("religion_id",[
            "data" => [
            "model" => "Ms_reff",
            "filter" => ["refcat_id" => "1"],
            "column" => ["reff_id","reff_name"]
            ],
            "extra" => [
            "required" => "true"
            ]
            ])->render("group","Agama")
            !!}
            {!!
            Create::dropDown("px_bloodgroup",[
            "data" => [
            "model" => "Ms_reff",
            "filter" => ["refcat_id" => "3"],
            "column" => ["reff_code","reff_name"]
            ],
            "extra" => [
            "required" => "true"
            ]
            ])->render("group","Golongan Darah")
            !!}
        </div>
        <div class="col-md-4">
            {!!
            Create::dropDown("edu_id",[
            "data" => [
            "model" => "Ms_reff",
            "filter" => ["refcat_id" => "44"],
            "column" => ["reff_code","reff_name"]
            ],
            "extra" => [
            "required" => "true"
            ]
            ])->render("group","Pendidikan")
            !!}
            {!!
            Create::dropDown("work_id",[
            "data" => [
            "model" => "Ms_reff",
            "filter" => ["refcat_id" => "39"],
            "column" => ["reff_code","reff_name"]
            ],
            "extra" => [
            "required" => "true"
            ]
            ])->render("group","Pekerjaan")
            !!}
            {!!
            Create::dropDown("px_status",[
            "data" => [
            "model" => "Ms_reff",
            "filter" => ["refcat_id" => "4"],
            "column" => ["reff_code","reff_name"]
            ],
            "extra" => [
            "required" => "true"
            ]
            ])->render("group","Status Perkawinan")
            !!}
        </div>
    </div>
</fieldset>