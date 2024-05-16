<style>
.btn-rounded {
    margin: 10px !important;
    padding: 20px !important;
    font-size: 30pt;
}
</style>
<template>
    <div>
        <div class="col-12">
            <div class="card bg-info">
                <div class="card-body text-light">
                    <div class="float-right">
                        <img src="/images/dokter/dokter_welcome.gif" style="width:80% !important">
                    </div>
                    <h1 class="text-white" style="font-weight:1000 !important; font-family: 'Segoe UI', sans-serif;">SELAMAT DATANG ...</h1>
                    <h3 class="text-white" style="font-family: 'Montserrat', sans-serif;">
                        DI RSUD IBNU SINA KABUPATEN GRESIK
                        <p></p>
                        Terima kasih telah memilih layanan kesehatan kami. Kami hadir untuk memberikan pengalaman pendaftaran pasien yang lebih mudah, cepat, dan nyaman.
                    </h3>
                    <h2 class="text-white mt-5" style="font-family: 'Roboto',sans-serif;">Silahkan pilih menu pendaftaran pelayanan rawat jalan dibawah ini</h2>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card border border-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <router-link to="apm/registrasi-mandiri" class="btn btn-xl btn-secondary btn-rounded btn-block">
                                <i class="fas fa-address-card"></i> REGISTRASI MANDIRI
                            </router-link>
                        </div>
                        <div class="form-group col-md-4">
                            <router-link to="/poli-tujuan" class="btn btn-lg btn-secondary btn-rounded btn-block">
                                <i class="fas fa-calendar-check"></i> CHECKIN ONLINE
                            </router-link>
                        </div>
                        <div class="form-group col-md-4">
                            <a href="#" id="btn-pxnew" class="btn btn-lg btn-secondary btn-rounded btn-block"><i class="fas fa-user-plus"></i> PASIEN BARU</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

