<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'patient_surety.store','id'=>'form_patient_surety']) !!}
    <div class="card-body">
        {!! Form::hidden('id', $patient_surety->id, array('id' => 'id')) !!}
        {!! 
                    Create::input("class_id",[
                    "value"     => $patient_surety->class_id,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("kode_ppk",[
                    "value"     => $patient_surety->kode_ppk
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("ppk_rujukan",[
                    "value"     => $patient_surety->ppk_rujukan
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("px_id",[
                    "value"     => $patient_surety->px_id,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("pxsurety_no",[
                    "value"     => $patient_surety->pxsurety_no,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("pxsurety_status",[
                    "value"     => $patient_surety->pxsurety_status,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("surety_id",[
                    "value"     => $patient_surety->surety_id,
               "required"  => "true"
                    ])->render("group"); 
                !!}
    </div>
    <div class="card-footer text-center">
        {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
        {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
    </div>
    {!!Form::close()!!}

<script>
    $(document).ready(()=>{
        $('#form_patient_surety').parsley().on('field:validated', function() {
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
                        'data': $('#form_patient_surety').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_patient_surety").html(data.redirect);
                                });
                            }else{
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