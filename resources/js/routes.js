const Welcome = () => import('./components/Welcome.vue' /* webpackChunkName: "resource/js/components/welcome" */)
const RegistrasiHome = () => import('./components/register/registrasi.vue' /* webpackChunkName: "resource/js/components/welcome" */)
const Patient = () => import('./components/register/data_patient.vue' /* webpackChunkName: "resource/js/components/welcome" */)
const Poli = () => import('./components/register/data_poli.vue' /* webpackChunkName: "resource/js/components/welcome" */)
const jadwalDokter = () => import('./components/register/data_dokter.vue')
const caraBayar = () => import('./components/register/data_cara_bayar.vue')
const finishRegistrasi = () => import('./components/register/finish_registrasi.vue')

export const routes = [
    {
        name: 'home',
        path: '/apm',
        component: Welcome
    },
    {
        name: 'registrasiMandiri',
        path: '/apm/registrasi-mandiri', // Menambahkan 'apm/' di depan path
        component: RegistrasiHome
    },
    {
        name: 'dataPatient',
        path: '/apm/data-patient', // Menambahkan 'apm/' di depan path
        component: Patient
    },
    {
        name: 'dataPoli',
        path: '/apm/data-poli', // Menambahkan 'apm/' di depan path
        component: Poli,
        props: true
    },
    {
        name: 'jadwalDokter',
        path: '/apm/jadwal-dokter', // Menambahkan 'apm/' di depan path
        component: jadwalDokter,
        props: true
    },
    {
        name: 'caraBayar',
        path: '/apm/cara-bayar', // Menambahkan 'apm/' di depan path
        component: caraBayar
    },
    {
        name: 'finishRegistrasi',
        path: '/apm/finish-registrasi', // Menambahkan 'apm/' di depan path
        component: finishRegistrasi
    }
];
