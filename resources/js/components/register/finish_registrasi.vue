<template>
    <div class="card">
        <div class="card-header text-center">
            <h3>PERSIAPAN PENDAFTARAN RAWAT JALAN</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img style="width: 80%;" class="thumb-img img-fluid" src="/images/dokter/doctor-science.gif" alt="Card image cap">
                </div>
                <div class="col-md-7">
                    <table class="table">
                        <tr>
                            <th style="width: 20%;">NORM</th>
                            <th>
                                {{ viewRegister.px_norm }}
                            </th>
                        </tr>
                        <tr>
                            <th>NAMA</th>
                            <th>
                                {{ viewRegister.px_name }}
                            </th>
                        </tr>
                        <tr>
                            <th>NO. KTP</th>
                            <th>
                                {{ viewRegister.px_noktp }}
                            </th>
                        </tr>
                        <tr>
                            <th>ALAMAT</th>
                            <th>
                                {{ viewRegister.px_address }}
                            </th>
                        </tr>
                        <tr>
                            <th>POLI TUJUAN</th>
                            <th>
                                {{ viewRegister.poli }}
                            </th>
                        </tr>
                        <tr>
                            <th>DOKTER</th>
                            <th>
                                {{ viewRegister.dokter }}
                            </th>
                        </tr>
                        <tr>
                            <th>CARA BAYAR</th>
                            <th>
                                {{ viewRegister.cara_bayar }}
                            </th>
                        </tr>
                    </table>
                </div>
                <div class="col-md-2">
                    <div class="text-center">
                        <img style="width: 60%;" class="rounded-circle img-fluid" src="/images/checkout.png" alt="Card image cap">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-block btn-lg btn-success btn-rounded" onclick="daftar()">Daftar Poli</button>
                    </div>
                    <div class="form-group">
                        <router-link to="/" class="btn btn-block btn-lg btn-danger btn-rounded">Kembali</router-link>
                    </div>
                </div>
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
        }
    }
}
</script>