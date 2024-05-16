<?php

use \fidpro\builder\Create;
use \fidpro\builder\Widget;
use \fidpro\builder\Multirow;
Widget::_init(["inputmask", "select2"]);
?>
{!! Form::open(['route' => 'patient.store','id'=>'form_patient']) !!}
<div class="card-body">
    {!! Form::hidden('px_id', $patient->px_id, array('id' => 'px_id')) !!}
    <fieldset>
        <legend>
            <div class="cardbox">
                <div class="user avatar-sm float-left mr-2">
                    <img src="{{asset('assets/images/patient.png')}}" alt="" class="img-fluid rounded-circle">
                </div>
                <div class="user-desc">
                    <h5 class="name mb-0 font-22">DATA DASAR PASIEN</h5>
                </div>
            </div>
        </legend>
        <div class="row">
            <div class="col-md-7">
                <fieldset style="margin-bottom: 20px;">
                    <legend>Nomor Kepesertaan BPJS</legend>
                    {!! Create::formGroup([
                    "group" => [
                    "select" => [
                    "id" => "identitas_peserta",
                    "option" => [
                    "data" => [
                    "nokartu" => ["Nomor Kartu"],
                    "nik" => ["Nomor KTP"]
                    ]
                    ]
                    ],
                    "input" => [
                    "id" => "nik_bpjs",
                    "value" => $patient->px_name,
                    "required" => "true"
                    ],
                    "button" => [
                    "value" => '<button type="button" class="btn btn-warning" onclick="get_peserta()"><i class="fa fa-search"></i></button>'
                    ]
                    ]
                    ])->render() !!}
                </fieldset>
            </div>
        </div>
        @include('patient.data_dasarpx');
        @include('patient.informasi_tambahanpx');
        @include('patient.alamat_px');
    </fieldset>
    @include('patient.multi_family');
</div>
<div class="card-footer text-center">
    {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
    {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
</div>
{!!Form::close()!!}

<script>
    $(document).ready(() => {
        $(document).on('focus', '.surety_id', function() {
            $(this).select2();
        });
        $('#form_patient').parsley().on('field:validated', function() {
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
                        'data': $('#form_patient').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: data.message,
                                    type: 'success',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes'
                                }).then((result) => {
                                    if (result.value) {
                                        location.assign("{{url('visit/pasien_baru?px_id=')}}"+data.response.px_id);
                                    }else{
                                        location.reload();
                                    }
                                })
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

    function get_peserta() {
        $.get("{{url('patient/get_peserta_bpjs')}}", {
            noka: $("#nik_bpjs").val(),
            type: $("#identitas_peserta").val()
        }, function(data) {
            if (data.code == "200") {
                $("#px_name").val(data.response.nama);
                $("#px_sex").val(data.response.sex);
                $("#px_noktp").val(data.response.nik);
                $("#px_birthdate").val(data.response.tglLahir);
                $("#px_phone").val(data.response.mr.noTelepon);
            } else {
                Swal.fire("Ooppss...!!", data.message, "error");
            }
        })
    }
</script>