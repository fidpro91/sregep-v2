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
        path: '/',
        component: Welcome
    },
    {
        name: 'registrasiMandiri',
        path: '/registrasi-mandiri',
        component: RegistrasiHome
    },
    {
        name: 'dataPatient',
        path: '/data-patient',
        component: Patient
    },
    {
        name: 'dataPoli',
        path: '/data-poli',
        component: Poli,
        props : true
    },
    {
        name: 'jadwalDokter',
        path: '/jadwal-dokter',
        component: jadwalDokter,
        props : true
    },
    {
        name: 'caraBayar',
        path: '/cara-bayar',
        component: caraBayar
    },
    {
        name: 'finishRegistrasi',
        path: '/finish-registrasi',
        component: finishRegistrasi
    }
]