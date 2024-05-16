<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'ms_category_unit.store','id'=>'form_ms_category_unit']) !!}
    <div class="card-body">
        {!! Form::hidden('catunit_id', $ms_category_unit->catunit_id, array('id' => 'catunit_id')) !!}
        {!! 
                    Create::input("catunit_code",[
                    "value"     => $ms_category_unit->catunit_code
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("catunit_name",[
                    "value"     => $ms_category_unit->catunit_name,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("catunit_status",[
                    "value"     => $ms_category_unit->catunit_status
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("has_child",[
                    "value"     => $ms_category_unit->has_child
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("parent_id",[
                    "value"     => $ms_category_unit->parent_id
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
        $('#form_ms_category_unit').parsley().on('field:validated', function() {
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
                        'data': $('#form_ms_category_unit').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_ms_category_unit").html(data.redirect);
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