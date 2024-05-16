<?php
use \fidpro\builder\Create;
?>
<fieldset>
    <legend><i class="far fa-address-card"></i> Alamat Pasien</legend>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="px_prov">Provinsi</label>
                <select class="px_prov" name="px_prov" id="px_prov"></select>
            </div>
            <div class="form-group">
                <label for="px_city">Kabupaten</label>
                <select class="px_city" name="px_city" id="px_city"></select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="px_district">Kecamatan</label>
                <select class="px_district" name="px_district" id="px_district"></select>
            </div>
            <div class="form-group">
                <label for="px_resident">Desa</label>
                <select class="px_resident" name="px_resident" id="px_resident"></select>
            </div>
        </div>
        <div class="col-md-4">
            {!!
            Create::text("px_address",[
            "value" => $patient->px_address,
            "option" => [
            "rows" => 5
            ]
            ])->render("group");
            !!}
        </div>
    </div>
</fieldset>
<script>
var kabupatenCache = {};
var kecamatanCache = {};
var desaCache = {};

function getRegion(parent_id, type) {
    if (type == 'kabupaten') {
        if (kabupatenCache[parent_id]) {
            populateSelect('px_city', kabupatenCache[parent_id]);
            return;
        }
    } else if (type == 'kecamatan') {
        if (kecamatanCache[parent_id]) {
            populateSelect('px_district', kecamatanCache[parent_id]);
            return;
        }
    } else if (type == 'desa') {
        if (desaCache[parent_id]) {
            populateSelect('px_resident', desaCache[parent_id]);
            return;
        }
    }

    $.ajax({
        url: '{{url("ms_region/get_region_child")}}/' + parent_id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Cache data dan isi Select2
            if (type == 'kabupaten') {
                kabupatenCache[parent_id] = data;
                populateSelect('px_city', data);
            } else if (type == 'kecamatan') {
                kecamatanCache[parent_id] = data;
                populateSelect('px_district', data);
            } else if (type == 'desa') {
                desaCache[parent_id] = data;
                populateSelect('px_resident', data);
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.error('Error:', textStatus);
        }
    });
}

function populateSelect(selectId, data) {
    $('#' + selectId).empty().trigger('change');
    if (data) {
        $.each(data, function(index, item) {
            $('#' + selectId).append($('<option></option>').attr('value', item.id).text(item.text));
        });
        $('#' + selectId).trigger('change');
    }
}

$(document).ready(()=>{
    
    $('#px_prov').select2({
        placeholder: 'Pilih data',
        minimumInputLength: 2, // Minimal panjang kata kunci sebelum pencarian dilakukan
        ajax: {
            url: '{{url("ms_region/get_region")}}', // Ganti dengan URL ke script PHP Anda
            dataType: 'json',
            data: function(params) {
                var query = {
                    q: params.term, // Kata kunci pencarian
                    prov_id: $("#px_prov").val()
                }
                return query;
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#px_city, #px_district, #px_resident').select2();

    $('#px_prov').on('change', function() {
        var provinsiId = $(this).val();
        // Hapus opsi lama pada kabupaten dan kecamatan
        $('#px_city, #px_district').empty().trigger('change');

        // Panggil fungsi untuk mendapatkan kabupaten berdasarkan provinsi
        if (provinsiId) {
            getRegion(provinsiId, 'kabupaten');
        }
    });

    $('#px_city').on('change', function() {
        var kabupatenId = $(this).val();

        // Hapus opsi lama pada kecamatan
        $('#px_district').empty().trigger('change');

        // Panggil fungsi untuk mendapatkan kecamatan berdasarkan kabupaten
        if (kabupatenId) {
            getRegion(kabupatenId, 'kecamatan');
        }
    });

    $('#px_district').on('change', function() {
        var kecmatanId = $(this).val();

        // Hapus opsi lama pada kecamatan
        $('#px_resident').empty().trigger('change');

        // Panggil fungsi untuk mendapatkan kecamatan berdasarkan kabupaten
        if (kecmatanId) {
            getRegion(kecmatanId, 'desa');
        }
    });
})
</script>