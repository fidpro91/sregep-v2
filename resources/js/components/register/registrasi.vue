<style>
.slick-slide img {
    width: 90% !important; /* Mengisi lebar slide dengan lebar kontainer */
}
.keyboard {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-gap: 5px;
}

.keyboard button {
  padding: 10px;
  font-size: 16px;
  cursor: pointer;
}
</style>
<template>
    <div class="row">
        <div class="col-4">
            <div class="card">
                <img class="card-img-top img-fluid" src="/images/dokter/dokterkonsul.jpg" alt="Card image cap">
                <div class="card-body">
                    <p class="card-text">
                        Masukkan Nomor Kartu Penduduk (KTP)/Kartu Berobat/Kartu Indonesia Sehat Anda Dikolom Sebelah Kanan
                    </p>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header text-center">
                    <h1>GUNAKAN SALAH SATU NOMOR IDENTITAS ANDA</h1>
                </div>
                <div class="card-body">
                    <form ref="myForm" @submit.prevent="searchPerson">
                        <input type="text" placeholder="No KTP/No Rekam Medis/No KIS" class="form-control text-center no-identitas" v-model="medicalRecordNumber">
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="keyboard text-center">
                                <button class="btn btn-secondary" v-for="number in numbers" :key="number" @click="appendToInput(number)">{{ number }}</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="slider">
                                <div>
                                    <img src="/images/kis.webp">
                                </div>
                                <div>
                                    <img src="/images/ktp-elektronik.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-footer bg-dark">
                    <div class="float-right">
                        <button type="button" @click="searchPerson" class="btn btn-lg btn-primary btn-action">
                            <i class="fas fa-search"></i>
                            Cari Pasien
                        </button>
                    </div>
                    <router-link to="/apm" class="btn btn-success btn-lg btn-action">
                        <i class="fas fa-home"></i>
                        Beranda
                    </router-link>
                </div>
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
    computed: {
        numbers() {
           return [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        }
    },
    mounted(){
        $('.slider').slick({
            autoplay: true,
            autoplaySpeed: 2500,
            prevArrow: false,
            nextArrow: false 
        });
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
        },
        appendToInput(medicalRecordNumber) {
            this.medicalRecordNumber += medicalRecordNumber.toString();
        }
        /* create() {
            this.$router.push({ name: "dataPatient" });
        } */
    }
}
</script>

