<?php
use \fidpro\builder\Create;
?>

<style>
    .kbw-signature { width: 100%; height: 200px;}
    #sig canvas{
        width: 100% !important;
        height: auto;
    }
</style>
    {!! Form::open(['route' => 'employee.store','id'=>'form_employee']) !!}
    <div class="card-body">
        {!! Form::hidden('employee_id', $employee->employee_id, array('id' => 'employee_id')) !!}
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <strong>Foto Pegawai</strong>
                        </div>
                        <div class="card-body">
                            <?php
                                $image = (!empty($employee->employee_photo)?asset('storage/photo-pegawai/'.$employee->employee_photo):asset('images/no-photo.jpeg'));
                            ?>
                            <div class="gal-detail thumb">
                                <img id="photo_preview" src="{{$image}}" class="thumb-img img-fluid" alt="work-thumbnail">
                            </div>
                            {!! 
                                Create::upload("employee_photo",[
                                    "value"     => $employee->employee_photo            
                                ])->render(); 
                            !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <strong>Tanda Tangan Pegawai</strong>
                        </div>
                        <div class="card-body">
                            <div id="sig" ></div>
                            <button id="clear" class="btn btn-danger btn-sm btn-block">Hapus 
                                    Tandatangan
                            </button>
                            <textarea id="signature" name="signature" style="display: 
                                none">
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {!! 
                        Create::input("employee_ft",[
                            "value"     => $employee->employee_ft
                        ])->render("group"); 
                    !!}
                    {!! 
                        Create::input("employee_bt",[
                        "value"     => $employee->employee_bt
                        ])->render("group"); 
                    !!}
                    {!! 
                        Create::input("employee_name",[
                            "value"     => $employee->employee_name,
                            "required"  => "true"
                        ])->render("group"); 
                    !!}
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    {!! 
                        Create::input("employee_jabatan",[
                        "value"     => $employee->employee_jabatan
                        ])->render("group"); 
                    !!}
                    {!! 
                        Create::input("employee_nik",[
                            "value"     => $employee->employee_nik
                        ])->render("group"); 
                    !!}
                    {!! 
                        Create::input("employee_nip",[
                            "value"     => $employee->employee_nip
                        ])->render("group"); 
                    !!}
                    {!! 
                        Create::input("kodehfis",[
                            "value"     => $employee->kodehfis
                        ])->render("group"); 
                    !!}
                </div>
                <div class="col-md-4">
                    {!! 
                        Create::input("employee_pendidikan",[
                        "value"     => $employee->employee_pendidikan
                        ])->render("group"); 
                    !!}
                    {!!
                        Create::dropDown("employee_permanent",[
                            "data" => [
                                "t" => ["Ya"],
                                "f" => ["Tidak"]
                            ],
                            "extra" => [
                                "required" => "true",
                                "value"     => $employee->employee_permanent
                            ]
                        ])->render("group","Pegawai Tetap")
                    !!}
                    {!! 
                        Create::input("employee_salary",[
                            "value"     => $employee->employee_salary
                        ])->render("group"); 
                    !!}
                    {!!
                        Create::dropDown("employee_active",[
                            "data" => [
                                "t" => ["Ya"],
                                "f" => ["Tidak"]
                            ],
                            "extra" => [
                                "required" => "true",
                                "value"     => $employee->employee_active
                            ]
                        ])->render("group","Status Pegawai")
                    !!}
                </div>
                <div class="col-md-4">
                    {!!
                        Create::dropDown("employee_sex",[
                            "data" => [
                                "L" => ["Laki-laki"],
                                "P" => ["Perempuan"]
                            ],
                            "extra" => [
                                "required" => "true",
                                "value"     => $employee->employee_sex
                            ]
                        ])->render("group","Jenis Kelamin")
                    !!}
                    {!! 
                        Create::input("employee_tmp_tgl_lahir",[
                            "value"     => $employee->employee_tmp_tgl_lahir
                        ])->render("group"); 
                    !!}
                    {!! 
                        Create::input("employee_tmt",[
                            "value"     => $employee->employee_tmt
                        ])->render("group"); 
                    !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! 
                        Create::text("employee_address",[
                            "value"     => $employee->employee_address
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
        $('#employee_photo').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => { 
              $('#photo_preview').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]); 
        });

        $('#form_employee').parsley().on('field:validated', function() {
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
                    var formData = new FormData($("#form_employee")[0]);
                    formData.append("empcat_id", empCatId);
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
                                    $("#page_employee").html(data.redirect);
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
        var sig = $('#sig').signature({syncField: '#signature', syncFormat: 'PNG'});
        $('#clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $("#signature").val('');
        });
    })
</script>