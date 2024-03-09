<template>
    <div>
        <div class="col-12 text-center">
            <h1>DATA DOKTER</h1>
        </div>
        <div v-if="dataDokter" class="row">
            <div v-for="(dokter,key) in dataDokter" :key="key" class="col-sm-4">
                <div class="card-box widget-user" @click="set_dataDokter(dokter.employee_id)">
                    <div>
                        <div class="avatar-lg float-left mr-3">
                            <img src="/images/dokter/doctor.png" class="img-fluid rounded-circle" alt="user">
                        </div>
                        <div class="wid-u-info">
                            <h5 class="mt-0">{{ dokter.employee_name }}</h5>
                            <p class="text-muted mb-1 font-13 text-truncate">Jam Pelayanan : 000 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="row">
            <p>Data pasien tidak ditemukan.</p>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            dataDokter: {
                type: Array,
                default: () => [],
            }
        },
        methods:{
            async getCategories(){
                await this.axios.get('/api/category').then(response=>{
                    this.dataPoli = response.data
                }).catch(error=>{
                    console.log(error)
                    this.dataPoli = []
                })
            },
            set_dataDokter(id){
                this.$setDataRegister({
                    'dpjp_id' : id
                });
                this.$router.push({ name: "finishRegistrasi"});
            }
        }
    };
</script>