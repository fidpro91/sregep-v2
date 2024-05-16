<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'ms_region.store','id'=>'form_ms_region']) !!}
    <div class="card-body">
        {!! Form::hidden('reg_code', $ms_region->reg_code, array('id' => 'reg_code')) !!}
        {!! 
                    Create::input("reg_active",[
                    "value"     => $ms_region->reg_active,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("reg_level",[
                    "value"     => $ms_region->reg_level,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("reg_name",[
                    "value"     => $ms_region->reg_name,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("reg_parent",[
                    "value"     => $ms_region->reg_parent
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
        $('#form_ms_region').parsley().on('field:validated', function() {
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
                        'data': $('#form_ms_region').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_ms_region").html(data.redirect);
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