<?php
use \fidpro\builder\Multirow;
?>
<div class="row">
    <div class="col-md-6">
        {!! Multirow::build([
            "id" => "family_px",
            "title" => "Kontak Keluarga Pasien",
            "data" => [
                "Nama" => [
                    "name" => "family_name",
                    "type" => "input"
                ],
                "Status Hubungan" => [
                    "name" => "status_hubungan",
                    "type" => "select",
                    "option" => [
                        "data" => [
                            "model" => "Ms_reff",
                            "filter" => ["refcat_id" => "45"],
                            "column" => ["reff_id", "reff_name"]
                        ]
                    ]
                ],
                "Telp" => [
                    "name" => "no_telp",
                    "type" => "input"
                ]
            ]
        ]) !!}

    </div>
    <div class="col-md-6">
        {!! Multirow::build([
            "id" => "multipay",
            "title" => "Cara Bayar Pasien",
            "data" => [
                "Penjamin" => [
                    "name" => "surety_id",
                    "type" => "select",
                    "option" => [
                        "data" => [
                            "model" => "Ms_surety",
                            "column" => ["surety_id", "surety_name"]
                        ]
                    ]
                ],
                "Nomor Penjamin" => [
                    "name" => "pxsurety_no",
                    "type" => "input"
                ]
            ]
        ]) !!}

    </div>
</div>