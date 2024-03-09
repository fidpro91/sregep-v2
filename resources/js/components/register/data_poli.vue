<template>
    <div>
        <div class="card-header">
            <H1>PILIH POLI TUJUAN</H1>
            <input type="text" name="filter_poli" @keyup="getDataPoli" class="form-control input-lg search-poli text-center" placeholder="Ketikkan nama poli tujuan berobat" v-model="searchPoli">
        </div>
        <div class="card-body">
            <div v-if="dataPoli" class="row">
                <div v-for="(poli,key) in dataPoli" :key="key" class="col-sm-3">
                    <div class="card" @click="get_data_dokter(poli.unit_id)">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="avatar-lg float-left mr-3" style="width: 100%;">
                                        <img src="/images/klinik/klinik.png" class="img-fluid rounded-circle" alt="user" style="max-width: 100%; height: auto;">
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <h3 class="mt-0"><a href="#" class="text-dark">{{ poli.unit_name }}</a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="row">
                <p>Data pasien tidak ditemukan.</p>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name:"dataPoli",
        data(){
            return {
                dataPoli :[],
                searchPoli : ""
            }
        },
        mounted(){
            this.getDataPoli()
        },
        methods:{
            async getDataPoli(){
                await this.axios.post('/api/data_poli',{
                    "keywords" : this.searchPoli
                }).then(response=>{
                    this.dataPoli = response.data
                }).catch(error=>{
                    console.log(error)
                    this.dataPoli = []
                })
            },
            get_data_dokter(id){
                this.$setDataRegister({
                    'poli' : id
                });
                this.axios.get('/api/get_jadwal_dokter/',{
                    params : {
                        "unit_id" : id
                    }
                }).then(response=>{
                    const dataDokter = response.data;
                    this.$router.push({ name: "jadwalDokter", params: { dataDokter } });
                }).catch(error=>{
                    console.log(error)
                    this.dataPoli = []
                })
            }
        }
    };
</script>