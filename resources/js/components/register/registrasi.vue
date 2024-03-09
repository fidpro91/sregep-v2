<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h1>GUNAKAN SALAH SATU NOMOR IDENTITAS ANDA</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            NO KTP
                        </div>
                        <div class="col-4">
                            NO RM
                        </div>
                        <div class="col-4">
                            NO KIS
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <form @submit.prevent="searchPerson">
                    <div class="card-body">
                        <input type="text" placeholder="No KTP/No Rekam Medis/No KIS" class="form-control text-center no-identitas" v-model="medicalRecordNumber">
                    </div>
                    <div class="card-footer bg-dark">
                        <div class="float-right">
                            <button type="submit" class="btn btn-lg btn-primary">Cari</button>
                        </div>
                        <button type="button" class="btn btn-lg btn-success">Beranda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:"add-category",
    data(){
        return {
            medicalRecordNumber: "",
        }
    },
    methods:{
        async searchPerson(){
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
        }
        /* create() {
            this.$router.push({ name: "dataPatient" });
        } */
    }
}
</script>

