<?php
use \fidpro\builder\Create;
use \fidpro\builder\Widget;
use \fidpro\builder\Bootstrap;
Widget::_init(["select2"]);
?>
{!! Form::open(['route' => 'visit.store','id'=>'form_visit' , 'autocomplete' => 'off']) !!}
<div class="card-body">
    {!! 
        Form::hidden('visit_id', $visit->visit_id, array('id' => 'visit_id')) 
    !!}
    {!! 
        Form::hidden('px_id', $visit->px_id, array('id' => 'px_id')) 
    !!}
    <fieldset>
        <legend>Data Pasien</legend>
        <div class="row">
            <div class="col-md-2">
                <div class="text-center">
                    <img src="{{asset('assets/themes/assets/images/users/user-2.jpg')}}" alt="" class="img-fluid rounded-circle avatar-lg">
                    <h4 id="px_name_txt">{{$visit->px_name}}</h4>
                    <button class="btn btn-primary  btn-sm" data-toggle="modal" data-target="#modal_info" type="button">
                        <i class="fas fa-info-circle"></i> Data Pasien
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                {!!
                    Create::input("px_norm",[
                        "value"     => $visit->px_norm,
                        "required"  => "true"
                    ])->render("group");
                !!}
                {!!
                    Create::input("px_noktp",[
                        "value" => $visit->px_noktp,
                        "required"  => "true"
                    ])->render("group");
                !!}
                {!!
                    Widget::select2("surety_id",[
                        "data"  => [],
                        "extra" => [
                            "required"  => "true"
                        ]
                    ])->render("group","Cara Bayar");
                !!}
            </div>
            <div class="col-md-4">
                {!!
                    Create::input("px_phone",[
                        "value" => $visit->px_phone
                    ])->render("group");
                !!}
                {!!
                    Create::input("visit_px_address",[
                    "value" => ($visit->visit_px_address ?? $visit->px_address)
                    ])->render("group");
                !!}
                {!!
                    Create::input("pxsurety_no",[
                        "value" => $visit->pxsurety_no
                    ])->render("group");
                !!}
            </div>
            <div class="col-md-3">
                <fieldset>
                    <legend>Kontak Darurat</legend>
                    {!!
                        Create::input("pic_px_name")->render("group");
                    !!}
                    {!!
                        Create::input("phone_pic_px")->render("group");
                    !!}
                    {!!
                        Create::dropDown("status_hubungan",[
                            "data" => [
                                "model" => "Ms_reff",
                                "filter" => ["refcat_id" => "45"],
                                "column" => ["reff_id", "reff_name"]
                            ]
                        ])->render("group")
                    !!}
                </fieldset>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>Kunjungan Medis</legend>
        <div class="row">
            <div class="col-md-3">
                    <div class="text-center">
                        <img src="{{asset('assets/images/klinik/klinik.png')}}" alt="" class="img-fluid rounded-circle avatar-lg">
                        <p></p>
                        <button class="btn btn-danger btn-sm btn-rounded" data-toggle="modal" data-target="#modal_kll" type="button"><i class="fas fa-car-crash"></i> Pasien KLL</button>
                    </div>
                </div>
            <div class="col-md-3">
                {!!
                    Create::dropDown("visit_type",[
                        "data" => [
                            "model"     => "Ms_reff",
                            "filter"    => ["refcat_id" => "40"],
                            "column"    => ["reff_code","reff_name"]
                        ],
                        "extra"     => [
                            "required"  => "true",
                            "value"     => $visit->visit_type
                        ]
                    ])->render("group","Tujuan Kunjungan")
                !!}
                {!!
                    Widget::select2("jns_pelayanan",[
                        "data" => [
                            "model"     => "ms_category_unit",
                            "column"    => ["catunit_id","catunit_name"]
                        ],
                        "extra" => [
                            "onchange"  => "get_poli($(this).val())",
                            "required"  => "true",
                            "value"     => $visit->unit_type
                        ]
                    ])->render("group","Jenis Pelayanan");
                !!}
            </div>
            <div class="col-md-3">
                {!!
                    Widget::select2("unit_id_pelayanan",[
                        "data" => [],
                        "extra" => [
                            "onchange"  => "get_dokter($(this).val())",
                            "required"  => "true"
                        ]
                    ])->render("group","Poli");
                !!}
                {!!
                    Widget::select2("dokter_id",[
                        "data"      => [],
                        "extra"     => [
                            "required"  => "true"
                        ]
                    ])->render("group","Dokter");
                !!}
            </div>
            <div class="col-md-3">
                {!!
                    Create::text("srv_note",[
                        "value"     => $visit->srv_note,
                        "option"    => [
                            "rows"  => 4
                        ]
                    ])->render("group","Keterangan");
                !!}
            </div>
        </div>
    </fieldset>
    <div class="col-md-12">
        <div class="form-group">
            <label for="tarif">Tarif Retribusi</label>
            
        </div>
    </div>
    <fieldset>
        <legend>Referensi Pendaftaran</legend>
        <div class="row">
            <div class="col-md-4">
                {!!
                    Create::dropDown("reff_id",[
                        "data" => [
                            "model"     => "Ms_reff",
                            "filter"    => ["refcat_id" => "6"],
                            "column"    => ["reff_id","reff_name"]
                        ],
                        "extra"     => [
                            "onchange"  => "get_rujukan()",
                            "required"  => "true"
                        ]
                    ])->render("group","Asal Pendaftaran")
                !!}
                {!!
                    widget::select2("perujuk_id",[
                        "data"      => [
                            "model"     => "Ms_perujuk",
                            "filter"    => ["perujuk_active" => "t"],
                            "column"    => ["perujuk_id","perujuk_name"]
                        ]
                    ])->render("group","Perujuk")
                !!}
            </div>
            <div class="col-md-4">
                {!!
                    widget::select2("no_rujukan",[
                        "data"      => [],
                        "select2"   => [
                            "tags"  => "true"
                        ]
                    ])->render("group","Nomor Rujukan")
                !!}
                {!!
                    Create::dropdown("diagnosa_awal",[
                        "data"      => [],
                        "extra"     => [
                            "required"  => "true"
                        ]
                    ])->render("group");
                !!}
            </div>
            <div class="col-md-4">
                {!!
                    Create::input("sep_no",[
                        "value"         => $visit->sep_no,
                        "class"         => "form-control mb-2",
                        "placeholder"   => "Nomor SEP"
                    ])->render();
                !!}
                {!! Form::button('SEP OTOMATIS',[
                        'class'     => 'btn btn-primary btn-block',
                        'onclick'   => 'otomatis_sep()',
                        'type'      => 'button'
                    ]); 
                !!}
                {!! 
                    Form::button('MANUAL SEP',[
                        'class'             => 'btn btn-secondary btn-block',
                        "data-toggle"       => "modal",
                        "data-target"       => "#sep_manual"
                    ]); 
                !!}
            </div>
        </div>
    </fieldset>
</div>
<div class="card-footer text-center">
    {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
    {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
</div>
{!!Form::close()!!}
{{
    Bootstrap::modal('modal_info',[
        "title"   => 'Informasi Data Kunjungan Pasien',
        "size"    => "modal-xl",
        "body"    => [
                        "content"   => ""
                    ]
    ])
}}

{{
    Bootstrap::modal('sep_manual',[
        "title"   => 'Pengajuan Sep Manual',
        "size"    => "modal-lg",
        "body"    => [
                        "url"   => url("sep_manual")
                    ]
    ])
}}
<script>
    $(document).ready(() => {
        //ambil jika ada id di url
        const px_id = new URLSearchParams(window.location.search).get('px_id');
        if (px_id) {
            get_patient_surety(px_id);
            $.ajaxSetup({
                "url"   : "{{route('visit.store')}}",
                "type"  : "post",
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            });
        };

        //jika edit 
        if ($("#visit_id").val() != "") {
            get_patient_surety($("#px_id").val());
        }

        $("#modal_info").on('show.bs.modal', function(){
            $(this).find(".modal-body").load("{{url('patient/patient_info')}}/"+$("#px_id").val());
        });

        $('#diagnosa_awal').select2({
            placeholder: 'Pilih Diagnosa',
            minimumInputLength: 2, // Minimal panjang kata kunci sebelum pencarian dilakukan
            ajax: {
                url: "{{URL('ms_icd/get_autocomplete')}}", // Ganti dengan URL ke script PHP Anda
                dataType: 'json',
                data: function(params) {
                    var query = {
                        q: params.term, // Kata kunci pencarian
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#form_visit').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
        .on('form:submit', function() {
            Swal.fire({
                title: 'Simpan Data?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        'data': $('#form_visit').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    location.href = "{{route('visit.index')}}";
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

            $('body').on("keyup", "#px_norm", function() {
                $(this).autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "{{URL('patient/get_patient/px_norm')}}",
                            dataType: "json",
                            type : 'get',
                            data: {
                                term: request.term
                            },
                            success: function(data) {
                                response(data);
                            }
                        });
                    },
			        autoFocus: true,
                    minLength:5,
                    select: function( event, ui ) {
                        $("#px_id").val( ui.item.px_id);
                        $("#px_noktp").val( ui.item.px_noktp);
                        $("#px_phone").val( ui.item.px_phone);
                        $("#visit_px_address").val( ui.item.px_address);
                        $("#px_name_txt").text( ui.item.px_name);
                        get_patient_surety(ui.item.px_id);
                    }
                }).data("uiAutocomplete")._renderItem =  function( ul, item ){
                    const repoElement = setTemplateImage(item);
                    return $("<li>").append(repoElement).appendTo(ul);
                };
            });

            $('body').on("keyup", "#diagnosa_awal", function() {
                $(this).autocomplete({
                    source: function(request, response) {
                        $.get(
                            "{{URL('ms_icd/get_autocomplete')}}",
                            '&term='+request.term,
                            response, 'json'
                        );
                    },
                    minLength:2,
                    autofocus:true,
                    select: function( event, ui ) {
                        $("#diagnosa_awal").val( ui.item.icd_code+'-'+ui.item.name);
                    }
                }).data("uiAutocomplete")._renderItem =  function( ul, item ){
                    return $("<li>").append(`<b>${item.icd_code}</b> - ${item.value}`).appendTo(ul);
                };
            });
    })

    function setTemplateImage(repo) {
        var html = `
            <div class="card bg-info mb-2">
                <div class="d-flex align-items-center">
                <img src="{{asset('assets/themes/assets/images/users/user-2.jpg')}}" alt="" class="img-fluid rounded-circle avatar-md mr-2">
                <div>
                    <h4>${repo.px_norm}</h4>
                    <h5>${repo.px_name} (${repo.px_sex})</h5>
                    ${repo.px_address}
                    <p class="card-text">
                        <small class="text-muted">
                            <i class="fas fa-transgender"></i> ${repo.px_birthdate} Forks
                            <i class="mdi mdi-cake-variant ml-2"></i> Tgl. Lahir : {{date_indo('${repo.px_birthdate}')}}
                        </small>
                    </p>
                </div>
                </div>
            </div>
        `;
        return html;
    }

    function get_patient_surety(id) {
        $('#surety_id').empty();
        $.get("{{URL('patient_surety/get_patient_surety')}}/"+id,function(resp){
            var data = resp;
            for (i in data) {
                var option = new Option(data[i].surety_name, data[i].surety_id);
                option.setAttribute('data-noka', data[i].pxsurety_no);
				$('#surety_id').append(option);
			}
        },'json')
    }

    function get_poli(id) {
        $('#unit_id_pelayanan').empty();
        $.get("{{URL('ms_unit/get_unit')}}/"+id,function(resp){
            var data = resp;
            for (i in data) {
                var option = new Option(data[i].unit_name, data[i].unit_id);
				$('#unit_id_pelayanan').append(option);
			}
        },'json')
    }

    function otomatis_sep() {
        $.ajax({
            url: "{{ URL('visit/generate_sep') }}", // Ganti dengan URL endpoint Anda
            type: "POST",
            data: $("#form_visit").serialize(),
            success: function(response) {
                // Tanggapan dari server ketika permintaan berhasil
                alert(response.message);
            },
            error: function(error) {
                // Tanggapan dari server ketika permintaan gagal
                Swal.fire("Oopss...!!", error.responseJSON.message, "error");
            }
        });
    }

    function get_dokter(unit_id) {
        $('#dokter_id').empty();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}' // Tambahkan token CSRF ke header permintaan
            }
        });
        $.post("{{URL('employee/get_schedule_medis')}}",{
            unit_id : unit_id,
            tanggal : "{{date('Y-m-d')}}"
        },function(resp){
            var data = resp;
            for (i in data) {
                var option = new Option(data[i].nama, data[i].employee_id);
				$('#dokter_id').append(option);
			}
        },'json')
    }

    function get_rujukan() {
        //jika bukan BPJS return false
        if ($("#surety_id").val() != 54) {
            return false;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}' // Tambahkan token CSRF ke header permintaan
            }
        });
        $.post("{{URL('visit/get_data_rujukan')}}",{
            unit_id : $("#unit_id_pelayanan").val(),
            tanggal : "{{date('Y-m-d')}}",
            no_ka   : $("#pxsurety_no").val(),
        },function(resp){
            if (resp.code == 200) {
                var data = resp.response;
                for (i in data) {
                    var txt = data[i].noKunjungan+"-"+data[i].provPerujuk.nama;
                    var option = new Option(txt,(data[i].noKunjungan+'-'+data[i].asalFaskes));
                    var rujukan = data[i].diagnosa.kode+"*-*"+data[i].diagnosa.nama+"||"+data[i].provPerujuk.kode+"*-*"+data[i].provPerujuk.nama;
                    option.setAttribute('data-rujukan', rujukan);
                    $('#no_rujukan').append(option);
                }
            }else{
                toastr.info(resp.message, "Info : ");
            }
        },'json')
    }

    $("#surety_id").on("change",function(){
        $("#pxsurety_no").val($(this).find("option:selected").data('noka'));
    });

    $("#no_rujukan").on("change",function(){
        var data = $(this).find("option:selected").data('rujukan');
        data = data.split("||");
        $("#diagnosa_awal").val(data[0]);
        $("#ppk_rujukan").val(data[1]);
    });

</script>