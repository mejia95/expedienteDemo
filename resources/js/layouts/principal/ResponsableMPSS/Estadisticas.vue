<script setup>
    import TotalPacientes from '@/components/Estadisticos/TotalPacientes.vue';
    import ActividadRecientePacientes from '@/components/Estadisticos/ActividadRecientePacientes.vue';
    import { onMounted,ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import { AppStore } from '@/stores/AppStore';
    const page = usePage()
    const StoreAplicacion = AppStore();
    const Totales = ref([])
    onMounted(async()=>{

        if(page.props.session.rolUsuario == 2){
            StoreAplicacion.LoaderPeticionenCurso = true;
            await axios.get('/api/estadisticos/pacientes').then((response)=>{
                Totales.value=response.data?.totales ?? [];
            }).catch((error)=>{
                Totales.value= [];
            });
            StoreAplicacion.LoaderPeticionenCurso = false;
        }
        
    });
</script>
<template>
    <TotalPacientes :totales="Totales"  v-if="page.props.session.rolUsuario == 2"></TotalPacientes>
    <ActividadRecientePacientes  v-if="page.props.session.rolUsuario == 2"></ActividadRecientePacientes>
</template>