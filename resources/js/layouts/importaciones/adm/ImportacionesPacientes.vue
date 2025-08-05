<script setup>
const api = `/importaciones`;
import FiltrosImportacionesAdm from '@/components/importaciones/filtros/ImportacionesAdm.vue';
import AppAlerta from '@/components/AppAlerta.vue';
import { AppStore } from '@/stores/AppStore';
import { ref, provide, onMounted} from 'vue'
import moment from 'moment';
onMounted(async ()=>{
    await ObtenerListaImportaciones();
});
const StoreApp = AppStore();
const MostrarFiltros = ref(false);
const PaginaInformacion = ref(1);
const ListadeImportaciones = ref({data:[]})
const ImportacionInd = ref(null);
const busqueda = ref(null);
const filtrosActivos = ref(false);
const FechaInicioFiltro = ref(null);
const FechaFinFiltro = ref(null);
const ConfirmacionEliminarImportacion = (importacion)=>{
    StoreApp.CallbackAlerta = null;
    ImportacionInd.value = null;
    ImportacionInd.value = importacion;
    StoreApp.MostrarAlerta = true;
    StoreApp.IconoAlerta = 'mdi-alert-circle';
    StoreApp.EstadoAlerta = 1;
    StoreApp.TituloAlerta = 'Confirmar eliminación de importación';
    StoreApp.MensajeAlerta = 'Todos los usuarios y registros asociados a esta importación serán eliminados permanentemente. ¿Está seguro de que desea continuar?';
    StoreApp.CallbackAlerta = "EliminarImportacion";
}


const EliminarImportacion = async()=>{
    let url = `${api}/${ImportacionInd.value}/eliminar-importacion`
    var titulo,mensaje,estado,icono,callback;
    await axios.delete(url).then((response)=>{
        titulo = "Operación exitosa";
        mensaje = response.data.message;
        estado = 2;
        icono = "mdi-check-circle";
        callback = "ObtenerListaImportaciones";
    }).catch((error)=>{
        titulo = "No se pudo eliminar";
        mensaje = error.response.data ? `${error.response.data.message}` : 'Ocurrió un error. Por favor, inténtalo de nuevo más tarde.';
        estado = 2;
        icono = "mdi-close-circle";
        callback = null;
    }).finally(()=>{
        StoreApp.MostrarAlerta = true;
        StoreApp.TituloAlerta = titulo;
        StoreApp.MensajeAlerta = mensaje;
        StoreApp.EstadoAlerta = estado;
        StoreApp.IconoAlerta = icono;
        StoreApp.CallbackAlerta = callback;
    });
}


const limpiarFiltros = async()=>{
    FechaInicioFiltro.value=null;
    FechaFinFiltro.value = null;
    filtrosActivos.value =false;
    PaginaInformacion.value=1;
    await ObtenerListaImportaciones();
    MostrarFiltros.value=false;
}

const  aplicarFiltros  = async()=>{
  filtrosActivos.value = false;
  if (FechaInicioFiltro.value || FechaFinFiltro.value) {
    filtrosActivos.value = true;
  }
  PaginaInformacion.value=1;
  await PeticionListaImportaciones();
  //await ObtenerListaImportaciones();
  MostrarFiltros.value=false;
}

const ObtenerListaImportaciones = async()=>{
    
    var url = `${api}/historial`;
    await axios.get(url,{
        params:{
            pagina:PaginaInformacion.value,           
            busqueda:busqueda.value,
            fecha_inicio:FechaInicioFiltro.value,
            fecha_fin:FechaFinFiltro.value,
            
        }
        
    }).then((response)=>{
        
        ListadeImportaciones.value = [];
        ListadeImportaciones.value = response.data;
    }).finally(()=>{
        StoreApp.MostrarAlerta = false;
    });
}
const PeticionListaImportaciones =  StoreApp.debounce(ObtenerListaImportaciones,350);
provide('MostrarFiltros',MostrarFiltros)
provide('aplicarFiltros',aplicarFiltros)
provide('limpiarFiltros',limpiarFiltros)
provide('FechaInicioFiltro',FechaInicioFiltro)
provide('FechaFinFiltro',FechaFinFiltro)
provide('FuncionesPadre',{EliminarImportacion,ObtenerListaImportaciones})
</script>
<template>
    <AppAlerta></AppAlerta>
    <FiltrosImportacionesAdm></FiltrosImportacionesAdm>
    <v-container fluid>
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
        <v-row class="mt-5 mx-5 mb-2" no-gutters >
            <v-col class="d-flex align-center" cols="6" sm="6" md="4">
                <v-text-field hide-details="" v-model="busqueda" @update:modelValue="PeticionListaImportaciones" variant="outlined" rounded="lg" prepend-inner-icon="mdi-magnify" density="compact" placeholder="Buscar por: No.Lote"></v-text-field>
            </v-col>
            <v-col class="d-flex align-center justify-end justify-sm-end justify-md-end justify-lg-end justify-xl-end">
                <v-btn @click="MostrarFiltros=true" icon="mdi-filter-variant" color="primary" variant="text"></v-btn>
            </v-col>
        </v-row>
        
        <!--<v-row v-if="filtrosActivos && !mostrarFiltros" class="mx-5 mt-n1 mb-n3">-->
        <v-row class="mx-2 mt-n2 mb-n1" v-if="filtrosActivos && !MostrarFiltros">
            <v-col cols="12">
                <span class="text-caption text-grey-darken-1 mr-2">Filtros activos:</span>
                <v-chip
                    color="primary"
                    text-color="white"
                    size="small"
                    class="ma-1"
                    v-if="busqueda"
                >
                    No.Lote : {{busqueda}}
                </v-chip>
                <v-chip
                    color="primary"
                    text-color="white"
                    size="small"
                    class="ma-1"
                    v-if="FechaInicioFiltro || FechaFinFiltro"
                >
                    Desde: {{ moment(FechaInicioFiltro).format('YYYY-MM-DD')}} {{ FechaFinFiltro ? ` a ${moment(FechaFinFiltro).format('YYYY-MM-DD') }`: null}}
                </v-chip>
            </v-col>
        </v-row>
        <v-row class="mx-5 mt-1" no-gutters>
                <v-col cols="12" class="border-thin rounded-lg" v-if="ListadeImportaciones.data.length > 0">
                    <v-progress-linear
                        indeterminate
                        v-if="StoreApp.LoaderPeticionenCurso"
                        color="primary"
                        height="4"
                        class="rounded-pill"
                    >
                    </v-progress-linear>
                    <v-table density="compact">
                        <thead>
                            <tr>
                                <th class="text-left pa-3">Tipo de importación</th>
                                <th class="text-left pa-3">Número de lote</th>
                                <th class="text-left pa-3">Fecha de importación</th>
                                <th class="text-center pa-3">Cantidad de datos importados</th>
                                <th class="text-center pa-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(importacion, idx) in ListadeImportaciones.data" :key="importacion.hID"
                                :class="[
                                    idx % 2 === 0 ? 'bg-grey-lighten-4' : '',
                                    'hover-row'
                                ]"
                                :style="importacion.medicosActivos > 0 ? 'cursor:not-allowed;' : ''"
                            >
                                <td>
                                    <span class="text-medium-emphasis">
                                        Médico pasante
                                    </span>
                                </td>
                                <td>
                                    <v-chip color="primary" size="small" class="chip-hid" prepend-icon="mdi-pound">
                                        <span class="chip-hid-text">{{ importacion.hID }}</span>
                                    </v-chip>
                                </td>
                                <td>
                                    <v-chip color="grey-darken-1" text-color="#616161" size="small" prepend-icon="mdi-calendar">
                                        <span>{{ importacion.fecha_creacion }}</span>
                                    </v-chip>
                                </td>
                                <td class="text-center">{{ importacion.total_registros_importados }}</td>
                                <td class="text-center">
                                    <v-tooltip v-if="importacion.medicosActivos > 0" text="No se puede eliminar porque ya existen médicos activos en esta importación" location="top">
                                        <template #activator="{ props }">
                                            <v-chip v-bind="props" color="primary" size="small" prepend-icon="mdi-alert-circle-outline">
                                                Médicos activos
                                            </v-chip>
                                        </template>
                                    </v-tooltip>
                                    <v-tooltip v-else text="Eliminar importación" location="top">
                                        <template #activator="{ props }">
                                            <v-btn v-bind="props" icon color="primary" variant="text" size="small" @click="ConfirmacionEliminarImportacion(importacion.hID)">
                                                <v-icon>mdi-trash-can</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-tooltip>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="pa-0">
                                    <v-container fluid class="pa-0">
                                        <v-row no-gutters>
                                            <v-col class="d-flex justify-start align-center">
                                               <v-pagination v-model="PaginaInformacion" v-if="ListadeImportaciones.last_page > 1" @update:modelValue="ObtenerListaImportaciones" elevation="0" variant="elevated" active-color="primary" rounded density="compact" :length="ListadeImportaciones.last_page" :total-visible="5"></v-pagination>
                                            </v-col>
                                            <v-col class="d-flex align-center justify-end text-primary poppins-light px-2">
                                                Mostrando {{ ListadeImportaciones.to }} de {{ ListadeImportaciones.total }} registros
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </td>
                            </tr>
                        </tfoot>
                    </v-table>
                </v-col>
                <v-col class="mt-10" v-else>
                    <v-empty-state
                        icon="mdi-database-off-outline"
                        text="No hay importaciones registradas, o no se encontró ninguna coincidencia con los filtros aplicados o la búsqueda."
                        title="Sin importaciones encontradas"
                    >
                    </v-empty-state>
                </v-col>
            </v-row> 
    </v-container>
    
</template>
<style scoped>
.hover-row:hover {
    background-color: #e3f2fd !important;
}
</style>