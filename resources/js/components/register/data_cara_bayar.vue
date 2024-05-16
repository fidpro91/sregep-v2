<template>
    <div>
        <div>
            <div ref="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Penjamin Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Isi modal di sini
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Simpan Perubahan</button>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-12 text-center">
            <h1 class="font-title">PILIH PENJAMIN/CARA BAYAR PASIEN</h1>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="card bg-secondary">
                    <div class="card-body overflow-auto" style="max-height: 500px !important;">
                        <div v-if="caraBayar" class="row">
                            <div v-for="(data, key) in caraBayar" :key="key" class="form-group col-md-4">
                                <button class="btn btn-block btn-lg btn-warning btn-rounded" @click="get_data_poli(data.surety_id)">
                                    {{ data.surety_name }}
                                </button>
                            </div>
                        </div>
                        <div v-else class="row">
                            <p>Data pasien tidak ditemukan.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <img class="rounded-circle img-fluid" src="/images/payment2.gif">
                    <button @click="openModal" class="btn btn-purple btn-block btn-lg btn-rounded">Penjamin Baru</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "caraBayar",
    data() {
        return {
            caraBayar: []
        }
    },
    mounted() {
        this.getCaraBayar()
    },
    methods: {
        async getCaraBayar() {
            const dataPatient = localStorage.getItem("patientData");
            const patient = JSON.parse(dataPatient);
            await this.axios.get('/api/get_data_bayar', {
                params: {
                    "px_id": patient.px_id
                }
            }).then(response => {
                this.caraBayar = response.data
            }).catch(error => {
                console.log(error)
                this.caraBayar = []
            })
        },
        openModal() {
            $(this.$refs.myModal).modal('show');
        },
        get_data_poli(id) {
            this.$setDataRegister({
                'surety_id': id
            });
            this.$router.push({ name: "dataPoli" });
        }
    }
};
</script>