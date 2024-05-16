<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'antrian_service.store','id'=>'form_antrian_service']) !!}
    <div class="card-body">
        {!! Form::hidden('antrian_id', $antrian_service->antrian_id, array('id' => 'antrian_id')) !!}
        {!! 
                    Create::input("antrian_code",[
                    "value"     => $antrian_service->antrian_code,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("antrian_date",[
                    "value"     => $antrian_service->antrian_date,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("antrian_group_id",[
                    "value"     => $antrian_service->antrian_group_id,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("antrian_num",[
                    "value"     => $antrian_service->antrian_num,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("antrian_status",[
                    "value"     => $antrian_service->antrian_status
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("visit_id",[
                    "value"     => $antrian_service->visit_id
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("visit_id_online",[
                    "value"     => $antrian_service->visit_id_online
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
        $('#form_antrian_service').parsley().on('field:validated', function() {
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
                        'data': $('#form_antrian_service').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_antrian_service").html(data.redirect);
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