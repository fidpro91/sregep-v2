<style>
    .table-patient th {
        font-size: 16pt !important;
    }
</style>
<template>
    <div class="card">
        <div class="card-header text-center">
            <h3 class="font-title">PERSIAPAN PENDAFTARAN RAWAT JALAN</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-patient">
                        <tr>
                            <th style="width: 10%;">NORM</th>
                            <th style="width: 25%;">
                                {{ viewRegister.px_norm }}
                            </th>
                            <th rowspan="3" class="text-center">
                                <img style="width: 70%;"  class="thumb-img img-fluid" src="/images/dokter/doctor-science.gif" alt="Card image cap">
                            </th>
                            <th style="width: 10%;">POLI TUJUAN</th>
                            <th style="width: 25%;">
                                {{ viewRegister.poli }}
                            </th>
                        </tr>
                        <tr>
                            <th>NAMA</th>
                            <th>
                                {{ viewRegister.px_name }}
                            </th>
                            <th>DOKTER</th>
                            <th>
                                {{ viewRegister.dokter }}
                            </th>
                        </tr>
                        <tr>
                            <th>NO. KTP</th>
                            <th>
                                {{ viewRegister.px_noktp }}
                            </th>
                            <th>CARA BAYAR</th>
                            <th>
                                {{ viewRegister.cara_bayar }}
                            </th>
                        </tr>
                        <tr>
                            <th>ALAMAT</th>
                            <th colspan="4">
                                {{ viewRegister.px_address }}
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer bg-dark">
            <div class="float-right">
                <button @click="daftarPoli" class="btn btn-primary btn-lg btn-action">
                    <i class="fas fa-fast-forward"></i>
                    Selesaikan Pendaftaran
                </button>
            </div>
            <div class="float-left">
                <router-link to="back" class="btn btn-danger btn-lg btn-action">
                    <i class="fas fa-fast-backward"></i>
                    Kembali
                </router-link>
                <router-link to="/apm" class="btn btn-success btn-lg btn-action">
                    <i class="fas fa-home"></i>
                    Beranda
                </router-link>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:"add-category",
    data() {
        return {
            viewRegister: Object,
        }
    },
    mounted () {
        this.reviewRegistrasi();
    },
    methods:{
        /* async searchPerson(){
            try {
                localStorage.removeItem('dataRegister');
                // Lakukan permintaan GET ke server untuk mendapatkan data pasien
                const response = await this.axios.get(`/api/patient/get_patient/${this.medicalRecordNumber}`);
                // Dapatkan data pasien dari response
                const patientData = response.data;
                if (patientData.code == 200) {
                    localStorage.setItem("patientData", JSON.stringify(patientData.response.data));
                    this.$router.push({ name: "dataPatient"});
                }else{
                    throw new Error(patientData.message);
                }
            } catch (error) {
                // console.log(error);
                Swal.fire("Oopss...!!",error.message, "error");
            }
        }, */
        async reviewRegistrasi() {
            const dataRegister = localStorage.getItem("dataRegister");
            const response = await this.axios.post(`/api/view_registrasi`,JSON.parse(dataRegister));
            this.viewRegister = response.data;
        },
        async daftarPoli() {
            const dataRegister = localStorage.getItem("dataRegister");
            try {
                const response = await this.axios.post('/api/save_register',JSON.parse(dataRegister));
                // Dapatkan data pasien dari response
                if (response.data.code == 200) {
                    this.$router.push({ name: "registrasiMandiri"});
                }else{
                    throw new Error(patientData.message);
                }
            } catch (error) {
                Swal.fire("Oopss...!!",error.message, "error");
            }
        }
    }
}
</script>