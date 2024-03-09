<style>
    .info-patient {
        font-size: 30pt;
        font-family: 'Roboto',sans-serif;
    }
</style>
<template>
    <div>
        <div v-if="patientData" class="card">
            <div class="card-header text-center">
                <h1>INFORMASI BIODATA PASIEN</h1>
            </div>
            <div class="card-body info-patient">
                <div class="row">
                    <div class="col-sm-3">
                        <img src="/images/no-photo.jpeg" alt="" style="width:100%">
                    </div>
                    <div class="col-sm-9">
                        <p><strong>Nomor Rekam Medis:</strong> {{ patientData.px_norm }}</p>
                        <p><strong>No. KTP:</strong> {{ patientData.px_noktp }}</p>
                        <p><strong>Nama:</strong> {{ patientData.px_name }}</p>
                        <p><strong>Alamat:</strong> {{ patientData.px_address }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-dark">
                <div class="float-right">
                    <router-link to="/cara-bayar" class="btn btn-primary btn-lg">Register</router-link>
                </div>
                <div class="float-left">
                    <router-link to="/registrasi-mandiri" class="btn btn-danger btn-lg">Kembali</router-link>
                </div>
            </div>
        </div>
        <div v-else class="alert alert-danger">
            Data pasien tidak ditemukan
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                patientData: Object
            }
        },
        mounted() {
            // Retrieve data from localStorage
            const storedData = localStorage.getItem("patientData");
            if (storedData) {
                this.patientData = JSON.parse(storedData);
                this.$setDataRegister({
                    'px_id' : this.patientData.px_id
                });
            }
        }
    };
</script>