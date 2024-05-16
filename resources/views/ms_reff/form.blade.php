<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'ms_reff.store','id'=>'form_ms_reff']) !!}
    <div class="card-body">
        {!! Form::hidden('reff_id', $ms_reff->reff_id, array('id' => 'reff_id')) !!}
        {!! 
                    Create::input("has_detail",[
                    "value"     => $ms_reff->has_detail
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("refcat_id",[
                    "value"     => $ms_reff->refcat_id,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("reff_active",[
                    "value"     => $ms_reff->reff_active,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("reff_code",[
                    "value"     => $ms_reff->reff_code
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("reff_name",[
                    "value"     => $ms_reff->reff_name,
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
        $('#form_ms_reff').parsley().on('field:validated', function() {
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
                        'data': $('#form_ms_reff').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_ms_reff").html(data.redirect);
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