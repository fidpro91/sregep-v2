<?php
use \fidpro\builder\Create;
use \fidpro\builder\Widget;
?>
<div class="row">
    <div class="col-md-3">
        <div class="text-center">
            <img src="{{asset('assets/images/patient.png')}}" alt="" class="img-fluid rounded-circle avatar-xl">
            <p></p>
            <button type="button" class="btn btn-secondary" onclick="get_peserta()"><i class="mdi mdi-camera"></i> Foto
                Pasien</button>
        </div>
    </div>
    <div class="col-md-4">
        {!! Create::input("px_name", [
        "value" => $patient->px_name,
        "required" => "true"
        ])->render("group") !!}

        {!! Create::input("px_noktp", [
        "value" => $patient->px_noktp
        ])->render("group") !!}

        {!! Create::input("px_nokk", [
        "value" => $patient->px_nokk
        ])->render("group") !!}
    </div>
    <div class="col-md-4">
        {!!
        Widget::inputMask("px_birthdate",[
        "prop" => [
        "value" => $patient->px_birthdate,
        "required" => true,
        ],
        "mask" => [
        "99-99-9999", [
        "placeholder" => "dd-mm-yyyy"
        ]
        ]
        ])->render("group");
        !!}
        {!!
        Create::dropDown("px_sex",[
        "data" => [
        "L" => ["Laki-laki"],
        "P" => ["Perempuan"]
        ],
        "extra" => [
        "required" => "true"
        ]
        ])->render("group","Jenis Kelamin")
        !!}
        {!!
        Create::input("px_phone",[
        "value" => $patient->px_phone
        ])->render("group");
        !!}
    </div>
</div>