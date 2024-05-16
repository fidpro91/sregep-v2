<?php
use \fidpro\builder\Create;
?>
    {!! Form::open(['route' => 'ms_unit.store','id'=>'form_ms_unit']) !!}
    <div class="card-body">
    {!! Form::hidden('unit_id', $ms_unit->unit_id, array('id' => 'unit_id')) !!}
        <div class="row">
            <div class="col-md-4">
                <?php
                    $image = (!empty($ms_unit->unit_logo)?asset('storage/klinik/'.$ms_unit->unit_logo):asset('images/klinik/klinik.png'));
                ?>
                <div class="gal-detail thumb">
                    <img id="photo_preview" src="{{$image}}" class="thumb-img img-fluid" alt="work-thumbnail">
                </div>
                {!! 
                    Create::upload("unit_logo",[
                        "value"     => $ms_unit->unit_logo            
                    ])->render(); 
                !!}
            </div>
            <div class="col-md-8">
                {!! 
                    Create::input("unit_code",[
                    "value"     => $ms_unit->unit_code
                    ])->render("group"); 
                !!}
                {!! 
                    Create::input("unit_name",[
                        "value"     => $ms_unit->unit_name,
                        "required"  => "true"
                    ])->render("group"); 
                !!}
                {!! 
                    Create::input("unit_nickname",[
                    "value"     => $ms_unit->unit_nickname
                    ])->render("group"); 
                !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                {!!
                    Create::dropDown("is_service",[
                        "data" => [
                            "t" => ["Ya"],
                            "f" => ["Tidak"]
                        ],
                        "extra" => [
                            "required" => "true",
                            "value"     => $ms_unit->is_service
                        ]
                    ])->render("group","Unit Pelayanan")
                !!}
                {!!
                    Create::dropDown("is_vip",[
                        "data" => [
                            "t" => ["Ya"],
                            "f" => ["Tidak"]
                        ],
                        "extra" => [
                            "required" => "true",
                            "value"     => $ms_unit->is_vip
                        ]
                    ])->render("group","Unit VIP")
                !!}
            </div>
            <div class="col-md-4">
                {!! 
                    Create::input("kode_antrean",[
                    "value"     => $ms_unit->kode_antrean
                    ])->render("group"); 
                !!}
                {!! 
                    Create::input("kode_poli_jkn",[
                    "value"     => $ms_unit->kode_poli_jkn
                    ])->render("group"); 
                !!}
                {!!
                    Create::dropDown("unit_active",[
                        "data" => [
                            "t" => ["Ya"],
                            "f" => ["Tidak"]
                        ],
                        "extra" => [
                            "required" => "true",
                            "value"     => $ms_unit->unit_active
                        ]
                    ])->render("group")
                !!}
            </div>
            <div class="col-md-4">
                {!! 
                    Create::input("kode_subspesialis",[
                        "value"     => $ms_unit->kode_subspesialis
                    ])->render("group"); 
                !!}
                {!! 
                    Create::input("kodeaskes",[
                        "value"     => $ms_unit->kodeaskes
                    ])->render("group"); 
                !!}
            </div>
        </div>
    </div>
    <div class="card-footer text-center">
        {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
        {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
    </div>
    {!!Form::close()!!}

<script>
    $(document).ready(()=>{
        $('#unit_logo').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => { 
              $('#photo_preview').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]); 
        });

        $('#form_ms_unit').parsley().on('field:validated', function() {
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
                    var formData = new FormData($("#form_ms_unit")[0]);
                    formData.append("unit_type", catUnitId);
                    $.ajax({
                        'data': formData,
                        headers: {
                            'X-CSRF-TOKEN': '<?=csrf_token()?>'
                        },
                        'processData': false,
                        'contentType': false,
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_ms_unit").html(data.redirect);
                                });
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