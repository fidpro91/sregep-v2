<?php
use \fidpro\builder\Create;
use fidpro\builder\Widget;

Widget::_init(["select2","inputmask"]);
?>
    {!! Form::open(['route' => 'schedule_doctor_service.store','id'=>'form_schedule_doctor_service']) !!}
    <div class="card-body">
        {!! Form::hidden('id', $schedule_doctor_service->id, array('id' => 'id')) !!}
            {!!
                Create::dropDown("day",[
                    "data" => [
                        0 => ['Minggu'],
                        1 => ['Senin'],
                        2 => ['Selasa'],
                        3 => ['Rabu'],
                        4 => ['Kamis'],
                        5 => ['Jumat'],
                        6 => ['Sabtu']
                    ],
                    "extra" => [
                        "required" => "true",
                        "value"     => $schedule_doctor_service->par_id
                    ]
                ])->render("group","Hari")
            !!}
            {!!
                Widget::select2("par_id",[
                    "data" => [
                        "model"     => "Employee",
                        "column"    => ["employee_id","employee_name"]
                    ],
                    "extra" => [
                        "required"  => "true",
                        "value"     => $schedule_doctor_service->par_id
                    ]
                ])->render("group","Nama Dokter");
            !!}
            {!! 
                Create::input("kuota_jkn",[
                    "value"     => $schedule_doctor_service->kuota_jkn
                ])->render("group"); 
            !!}
            {!! 
                Create::input("kuota_non_jkn",[
                    "value"     => $schedule_doctor_service->kuota_non_jkn
                ])->render("group"); 
            !!}
            {!!
                Widget::inputMask("time_start",[
                    "prop" => [
                        "value" => $schedule_doctor_service->time_start,
                        "required" => true,
                    ],
                    "mask" => [
                        "99:99:99", [
                            "placeholder" => "00:00:00"
                        ]
                    ]
                ])->render("group","Jam Buka Pelayanan");
            !!}
            {!!
                Widget::inputMask("time_end",[
                    "prop" => [
                        "value" => $schedule_doctor_service->time_end,
                        "required" => true,
                    ],
                    "mask" => [
                        "99:99:99", [
                            "placeholder" => "00:00:00"
                        ]
                    ]
                ])->render("group","Jam Tutup Pelayanan");
            !!}
    </div>
    <div class="card-footer text-center">
        {!! Form::submit('Save',['class' => 'btn btn-success']); !!}
        {!! Form::button('Cancel',['class' => 'btn btn-warning btn-refresh']); !!}
    </div>
    {!!Form::close()!!}

<script>
    $(document).ready(()=>{
        $('#form_schedule_doctor_service').parsley().on('field:validated', function() {
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
                        'data': $('#form_schedule_doctor_service').serialize(),
                        'dataType': 'json',
                        'success': function(data) {
                            if (data.success) {
                                Swal.fire("Sukses!", data.message, "success").then(() => {
                                    $("#page_schedule_doctor_service").html(data.redirect);
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