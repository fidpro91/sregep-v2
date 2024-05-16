<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'employee_categories.store','id'=>'form_employee_categories']) !!}
    <div class="card-body">
        {!! Form::hidden('empcat_id', $employee_categories->empcat_id, array('id' => 'empcat_id')) !!}
        {!! 
                    Create::input("empcat_active",[
                    "value"     => $employee_categories->empcat_active,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("empcat_code",[
                    "value"     => $employee_categories->empcat_code
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("empcat_name",[
                    "value"     => $employee_categories->empcat_name,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("empcat_parent",[
                    "value"     => $employee_categories->empcat_parent
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
        $('#form_employee_categories').parsley().on('field:validated', function() {
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
                        'data': $('#form_employee_categories').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_employee_categories").html(data.redirect);
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