<?php
    use \fidpro\builder\Bootstrap;
?>
<?=
    Bootstrap::tabs([
        "tabs"  => [
            "Data Dasar"  => [
                "href"      => "data_px",
                "content"   => function() use($patient){
                    return view('patient.form_update',compact('patient'));
                }
            ],
            "Riwayat Kunjungan"  => [
                "href"      => "riwayat",
                "url"       => route("repo_rujukan.index")
            ],
            "CARA BAYAR"  => [
                "href"      => "cara_bayar",
                "url"       => route("patient_surety.index")
            ],
            "RIWAYAT ALERGI"  => [
                "href"      => "alergipx",
                "url"       => route("alergi_px.index")
            ]
        ]
    ]);
?>