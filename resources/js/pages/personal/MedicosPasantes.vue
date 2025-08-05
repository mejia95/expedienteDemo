<script setup >
import { Head } from '@inertiajs/vue3';

import { ref, nextTick,provide,onMounted,computed } from 'vue';
import AppAlerta from '@/components/AppAlerta.vue';
import App from '@/layouts/app/App.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import MedicosPasantes from '@/layouts/personal/MedicosPasantes.vue';
import { AppStore } from '@/stores/AppStore';
import { Store } from 'lucide-vue-next';
const api = "/personal/medico-pasante";

defineProps({
    breadcrumbs: Array,
});
    const StoreApp = AppStore();
    const pestania_medico_pasante = ref(1);
    const MedicoPasanteStatus = ref('importados');
    const ListaMedicoPasante = ref([]);
    const FiltroCampoBusqueda = ref(null);
    const totalMedicoPasante = ref(0);
    const totalMedicoPasantePagina = ref(0);
    const lastPageMedicoPasante = ref(0);
    const loading = ref(false);
    const MedicosPasantesRef= ref(null);
    

    onMounted(()=>{
        PeticionListaMedicos();
        MedicosPasantesRef.value.MedicosSeleccionados = null;
    });

    const setPestaniaMedicoPasante = async(valor)=>{
        console.log("Cambio de vista")
        MedicoPasanteStatus.value = valor;
        MedicosPasantesRef.value.MedicosSeleccionados = [];
        PaginaInformacion.value=1;
        totalMedicoPasante.value=0;
        await nextTick();
        FiltroCampoBusqueda.value=null;
        await PeticionListaMedicos();
    }
    const PaginaInformacion = ref(1);
    const getListaMedicosPasantes = async () => {
            console.log("Que traes en el campo busqueda");
            console.log(FiltroCampoBusqueda.value);
            let data_request = {
                params: {
                    estado: MedicoPasanteStatus.value,
                    pagina:PaginaInformacion.value,
                    busquedaCampo:FiltroCampoBusqueda.value
                }
            }
           await axios.get(`${api}/obtener`, data_request).then((response)=>{
                ListaMedicoPasante.value = response.data.data ?? [];
                totalMedicoPasante.value = response.data.total;
                lastPageMedicoPasante.value = response.data.last_page;
                totalMedicoPasantePagina.value = response.data.to;
            }).catch((error)=>{
                console.error('Error:', error);
            });
        
    }

    const varianteEstado = computed(() => {
        return (status) => {
            switch (status) {
                case 0:
                    return 'text';
                case 1:
                    return 'outlined';
                case 2:
                    return 'tonal';
                case 3:
                    return 'elevated';
            }
        }
    });
    const etiquetaEstado = computed(() => {
        return (status) => {
            switch (status) {
                case 0:
                    return 'Suspendido';
                case 1:
                    return 'En espera';
                case 2:
                    return 'Pendiente';
                case 3:
                    return 'Autorizado';
            }
        }
    });

    const RecargarInformacion = () => {
        PaginaInformacion.value = 1;
        PeticionListaMedicos();
    }
    provide('RecargarInformacion', RecargarInformacion)
    provide('PaginaInformacion', PaginaInformacion)
    provide('ObtenerListaMedicos', getListaMedicosPasantes)
    provide('FiltroCampoBusqueda', FiltroCampoBusqueda)
    const PeticionListaMedicos = StoreApp.debounce(getListaMedicosPasantes,350);
</script>
<template>
    <App :breadcrumbs="breadcrumbs">
        <v-container fluid>
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">Médicos Pasantes de Servicio Social</span>
                </v-col>
                <v-col cols="12">
                    <div class="poppins-light text-grey-darken-1">
                        Gestión de médicos pasantes: consulta de información, asignación de accesos al sistema, actualización de datos y seguimiento de su servicio social.
                    </div>
                </v-col>
            </v-row>
            <v-row class="mt-10 mx-5" no-gutters>
                <v-col cols="12">
                    <v-tabs
                        color="primary"
                        v-model="pestania_medico_pasante"
                        >
                        <v-tab @click="setPestaniaMedicoPasante('importados')" :value="1" class="mr-2" variant="tonal" rounded="t-lg">
                            <v-icon start icon="mdi-database-import-outline" class="mr-2"></v-icon>
                            Importados
                            <v-chip size="x-small" class="ml-1" v-if="pestania_medico_pasante == 1 && !StoreApp.LoaderPeticionenCurso" rounded="lg" color="primary" variant="elevated">{{totalMedicoPasante}}</v-chip>
                        </v-tab>
                        <v-tab @click="setPestaniaMedicoPasante('pendientes')" :value="2" class="mr-2" variant="tonal" rounded="t-lg">
                            <v-icon start icon="mdi-clock-outline" class="mr-2"></v-icon>
                            Acceso pend
                            <v-chip size="x-small" class="ml-1" v-if="pestania_medico_pasante == 2 && !StoreApp.LoaderPeticionenCurso" rounded="lg" color="primary" variant="elevated">{{totalMedicoPasante}}</v-chip>
                        </v-tab>
                        <v-tab @click="setPestaniaMedicoPasante('autorizados')" :value="3" variant="tonal" class="mr-2" rounded="t-lg" >
                            <v-icon start icon="mdi-check-circle-outline" class="mr-2"></v-icon>
                            Acceso Aut
                            <v-chip size="x-small" class="ml-1" v-if="pestania_medico_pasante == 3 && !StoreApp.LoaderPeticionenCurso" rounded="lg" color="primary" variant="elevated">{{totalMedicoPasante}}</v-chip>
                        </v-tab>
                        <v-tab @click="setPestaniaMedicoPasante('suspendidos')" :value="4" variant="tonal" rounded="t-lg" >
                            <v-icon start icon="mdi-account-cancel-outline" class="mr-2"></v-icon>
                            Acceso Susp
                            <v-chip size="x-small" class="ml-1" v-if="pestania_medico_pasante == 4 && !StoreApp.LoaderPeticionenCurso" rounded="lg" color="primary" variant="elevated">{{totalMedicoPasante}}</v-chip>
                        </v-tab>
                        </v-tabs>
                </v-col>
            </v-row>
            <MedicosPasantes ref="MedicosPasantesRef" :tipoVista="pestania_medico_pasante" :ListaDeMedicos="ListaMedicoPasante" :totalRegistros="totalMedicoPasante" :totalRegistrosPagina="totalMedicoPasantePagina" :lastPage="lastPageMedicoPasante"></MedicosPasantes>
            
        </v-container>
        
    </App>
</template>
