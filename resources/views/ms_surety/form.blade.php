<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'ms_surety.store','id'=>'form_ms_surety']) !!}
    <div class="card-body">
        {!! Form::hidden('surety_id', $ms_surety->surety_id, array('id' => 'surety_id')) !!}
            {!! 
                Create::input("surety_code",[
                    "value"     => $ms_surety->surety_code
                ])->render("group"); 
            !!}
            {!! 
                Create::input("surety_group",[
                    "value"     => $ms_surety->surety_group
                ])->render("group"); 
            !!}
            {!! 
                Create::input("surety_group_antrian",[
                    "value"     => $ms_surety->surety_group_antrian
                ])->render("group"); 
            !!}
            {!! 
                Create::input("surety_name",[
                    "value"     => $ms_surety->surety_name,
                    "required"  => "true"
                ])->render("group"); 
            !!}
            {!! 
                Create::input("surety_organizer",[
                    "value"     => $ms_surety->surety_organizer
                ])->render("group","Penyelenggara Jaminan"); 
            !!}
            {!!
                Create::dropDown("surety_active",[
                    "data" => [
                        "t" => ["Ya"],
                        "f" => ["Tidak"]
                    ],
                    "extra" => [
                        "required" => "true",
                        "value"     => $ms_surety->surety_active
                    ]
                ])->render("group")
            !!}
    </div>
    <div class="card-footer text-center">
        {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
        {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
    </div>
    {!!Form::close()!!}

<script>
    $(document).ready(()=>{
        $('#form_ms_surety').parsley().on('field:validated', function() {
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
                        'data': $('#form_ms_surety').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_ms_surety").html(data.redirect);
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