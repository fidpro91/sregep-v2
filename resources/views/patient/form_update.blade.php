<?php

use fidpro\builder\Widget;
Widget::_init(["select2","inputmask"]);
?>
{!! Form::open(['route' => 'patient.store','id'=>'form_patient']) !!}
<div class="card-body">
    {!! Form::hidden('px_id', $patient->px_id, array('id' => 'px_id')) !!}
    @include('patient.data_dasarpx');
    @include('patient.informasi_tambahanpx');
    @include('patient.alamat_px');
</div>
<div class="card-footer text-center">
    {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
    {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
</div>
{!!Form::close()!!}
<script>
    $(document).ready(()=>{
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
                        'type'   : 'put',
                        'url'    : '{{route("patient.update",$patient->px_id)}}',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Success", data.message, "success");
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
</script>