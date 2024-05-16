<?php
Widget::_init(["switcher"]);
?>
{!! Form::open(['route' => 'visit.store','id'=>'form_sep_manual' , 'autocomplete' => 'off']) !!}
<div class="row">
    <div class="col-md-6">
        {!!
            Create::dropDown("jenis_sep",[
                "data" => [
                    "2"    => "Rawat Jalan",
                    "1"    => "Rawat Inap",
                ]
            ])->render("group")
        !!}
        {!!
            Widget::select2("no_rujukan_sep",[
                "data"  => [],
                "extra" => [
                    "required"  => "true"
                ]
            ])->render("group","Nomor Rujukan");
        !!}
    </div>
    <div class="col-md-6">
        {!!
            Create::dropDown("jenis_rujukan",[
                "data" => [
                    "1"    => "Rujukan FKTL",
                    "2"    => "Rujukan RS",
                ]
            ])->render("group")
        !!}
        {!!
            Create::input("diagnosa_sep",[
                "readonly" => true
            ])->render("group");
        !!}
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-3">
        {!!
            Widget::switcher("is_lakalantas",[
                'option'    => [
                    'onText'      =>  'Ya',
                    'offText'     => 'Tidak',
                    'onColor'     => 'success',
                    'offColor'    => 'danger',
                    'state'       => true
                ]
            ])->render('group','Pasien Laka-lantas?')
        !!}
    </div>
    <div class="col-md-3">
        {!!
            Widget::switcher("is_eksekutif",[
                'option'    => [
                    'onText'      =>  'Ya',
                    'offText'     => 'Tidak',
                    'onColor'     => 'success',
                    'offColor'    => 'danger',
                    'state'       => true
                ]
            ])->render('group','Pelayanan Eksekutif?')
        !!}
    </div>
    <div class="col-md-3">
        {!!
            Widget::switcher("sep_cob",[
                'option'    => [
                    'onText'      =>  'Ya',
                    'offText'     => 'Tidak',
                    'onColor'     => 'success',
                    'offColor'    => 'danger',
                    'state'       => true
                ]
            ])->render('group','SEP Pasien COB ?')
        !!}
    </div>
    <div class="col-md-3">
        {!!
            Widget::switcher("sep_katarak",[
                'option'    => [
                    'onText'      =>  'Ya',
                    'offText'     => 'Tidak',
                    'onColor'     => 'success',
                    'offColor'    => 'danger',
                    'state'       => true
                ]
            ])->render('group','SEP Pasien Katarak?')
        !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {!!
            Create::radio("tujuan_kunjungan_sep",[
                "data" => [
                    "model"     => "Ms_reff",
                    "filter"    => ["refcat_id" => "40"],
                    "column"    => ["reff_code","reff_name"]
                ]
            ])->render("group");
        !!}
    </div>
    <div class="col-md-6">
    {!! 
        Create::formGroup([
            "group" => [
                "input" => [
                    "id"        => "no_skdp",
                    "required"  => "true"
                ],
                "button" => [
                    "value" => '<button type="button" class="btn btn-info" onclick="get_peserta()"><i class="fa fa-search"></i></button>'
                ]
            ]
        ])->render() 
    !!}
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-4">
        {!!
            Create::dropDown("flagprosedur",[
                "data" => [
                    "0"    => "Prosedur tidak berkelanjutan",
                    "1"    => "Prosedur dan terapi berkelanjutan",
                ],
                "nullable"  => true
            ])->render("group")
        !!}
    </div>
    <div class="col-md-4">
        {!!
            Create::dropDown("kodepenunjang",[
                "data" => [
                    "model"     => "Ms_reff",
                    "filter"    => ["refcat_id" => "48"],
                    "column"    => ["reff_code","reff_name"]
                ],
                "nullable"  => true
            ])->render("group")
        !!}
    </div>
    <div class="col-md-4">
        {!!
            Create::dropDown("assesment_pelayanan",[
                "data" => [
                    "model"     => "Ms_reff",
                    "filter"    => ["refcat_id" => "42"],
                    "column"    => ["reff_code","reff_name"]
                ],
                "nullable"  => true
            ])->render("group")
        !!}
    </div>
</div>
<div class="card-footer text-center mt-2">
    {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
    {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
</div>
{!!Form::close()!!}
<script>
    $(document).ready(()=>{
        $("#jenis_rujukan").change(()=>{
            get_rujukan();
        });
        get_rujukan();

        $('#form_sep_manual').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function() {
            Swal.fire({
                title: 'Buat pengajuan SEP?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        'data': $('#form_sep_manual').serialize()+"&noka="+$("#pxsurety_no").val()+"&norm="+$("#px_norm").val(),
                        'dataType': 'json',
                        'method'  : 'post',
                        'url'     : '{{url("bpjs/sep_manual/get_sep")}}',
                        'success': function(data) {
                            if (data.code == 200) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Oopss...!!", data.message, "error");
                            }
                        }
                    });
                }
            })
            return false;
        });
    })

    function get_rujukan() {
        $.get("{{url('bpjs/sep_manual/get_rujukan')}}/"+$("#pxsurety_no").val(),function(resp){
            if (resp.code != 200) {
                Swal.fire("Oopss...!!", resp.message, "error");
                return false;   
            }
            var data = resp.list;
            $("#no_rujukan_sep").empty();
            for (i in data) {
                var txt = data[i].noKunjungan+"-"+data[i].provPerujuk.nama;
                var option = new Option(txt,(data[i].noKunjungan+'-'+data[i].asalFaskes));
				$('#no_rujukan_sep').append(option);
			}
        },'json')
    }
</script>