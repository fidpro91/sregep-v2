<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'ms_perujuk.store','id'=>'form_ms_perujuk']) !!}
    <div class="card-body">
        {!! Form::hidden('perujuk_id', $ms_perujuk->perujuk_id, array('id' => 'perujuk_id')) !!}
        {!! 
                    Create::input("created_at",[
                    "value"     => $ms_perujuk->created_at
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("kode_faskes_bpjs",[
                    "value"     => $ms_perujuk->kode_faskes_bpjs
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("perujuk_active",[
                    "value"     => $ms_perujuk->perujuk_active
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("perujuk_address",[
                    "value"     => $ms_perujuk->perujuk_address
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("perujuk_city",[
                    "value"     => $ms_perujuk->perujuk_city
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("perujuk_district",[
                    "value"     => $ms_perujuk->perujuk_district
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("perujuk_name",[
                    "value"     => $ms_perujuk->perujuk_name,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("perujuk_phone",[
                    "value"     => $ms_perujuk->perujuk_phone
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("perujuk_prov",[
                    "value"     => $ms_perujuk->perujuk_prov
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("perujuk_resident",[
                    "value"     => $ms_perujuk->perujuk_resident
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("tipe_ppk",[
                    "value"     => $ms_perujuk->tipe_ppk,
               "required"  => "true"
                    ])->render("group"); 
                !!}
{!! 
                    Create::input("updated_at",[
                    "value"     => $ms_perujuk->updated_at
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
        $('#form_ms_perujuk').parsley().on('field:validated', function() {
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
                        'data': $('#form_ms_perujuk').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_ms_perujuk").html(data.redirect);
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