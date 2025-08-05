<script setup >
import { Head,usePage } from '@inertiajs/vue3';
import { ref, nextTick,provide } from 'vue';
import AppAlerta from '@/components/AppAlerta.vue';
import App from '@/layouts/app/App.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import NuevaImportacion from '@/layouts/importaciones/NuevaImportacion.vue';
import HistorialImportacion from '@/layouts/importaciones/HistorialImportaciones.vue';
import ImportacionesAdmPacientes from '@/layouts/importaciones/adm/ImportacionesPacientes.vue';
const drawer = ref(null);
const tab = ref(1);
const HistorialImportacionRef = ref(null);
const showDialog = ref(false);


defineProps({
    breadcrumbs: Array,
});
const page = usePage();
const GetHistorialImportaciones = async() => {
    console.log("Aqui entra")
    await nextTick();
    tab.value = 2;
    await HistorialImportacionRef.value.HistorialImportaciones();
    
}

const HistorialTab = async () => {
    console.log("Iniciando HistorialTab");
    tab.value = 2;
    await nextTick();
    await HistorialImportacionRef.value.HistorialImportaciones();
}

provide('GetHistorialImportaciones', GetHistorialImportaciones);
provide('tab', tab);
</script>
<template>
    <Head title="Importaciones"></Head>
    <App :breadcrumbs="breadcrumbs">
        <v-container fluid v-if="page.props.session.rolUsuario==2">
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">Importación de Médicos Pasantes de Servicio Social</span>
                </v-col>
                <v-col cols="12">
                    <div class="poppins-light text-grey-darken-1">
                        Historial de importaciones de médicos pasantes de Servicio Social realizadas.
                    </div>
                </v-col>
            </v-row>
            <v-row class="mt-10 mx-5" no-gutters>
                <v-col cols="12">
                    <v-tabs
                        v-model="tab"
                        color="primary"
                        
                        >
                        <v-tab :value="1" class="mr-2" variant="tonal" rounded="t-lg">Nueva</v-tab>
                        <v-tab :value="2" variant="tonal" rounded="t-lg" @click="GetHistorialImportaciones">Historial</v-tab>
                        </v-tabs>
                </v-col>
            </v-row>
            <NuevaImportacion v-if="tab==1"></NuevaImportacion>
            <HistorialImportacion v-show="tab==2" ref="HistorialImportacionRef"></HistorialImportacion>
        </v-container>
        <ImportacionesAdmPacientes v-if="page.props.session.rolUsuario==1"></ImportacionesAdmPacientes>
    </App>
</template>
