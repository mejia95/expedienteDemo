<template>
    <v-container fluid >
        
        <v-row class="mt-0 mx-4" no-gutters >
            
            <v-col cols="12">
                <v-card class="rounded-lg" elevation="0">
                    <v-card-text>
                        <div class="d-flex align-center">
                            <v-icon
                                :icon="getHeaderIcon"
                                size="28"
                                color="primary"
                                class="me-3"
                            ></v-icon>
                            <div>
                                <div class="text-h5 poppins-medium" v-if="!StoreApp.LoaderPeticionenCurso">{{ getHeaderTitle }}</div>
                                <div class=" text-medium-emphasis">{{ getHeaderSubtitle }}</div>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        
        <v-row class="mt-4 mx-4" no-gutters >
            <v-col cols="4" class="d-flex align-center  mb-2" >
               <v-text-field 
                    v-model="searchField"
                    density="compact" 
                    prepend-inner-icon="mdi-magnify" 
                    variant="solo" 
                    class="border-thin rounded-lg" 
                    @update:modelValue="BusquedaPorCampo" 
                    rounded="lg" 
                    flat 
                    color="grey-lighten-2" 
                    placeholder="Buscar por: Nombre médico" 
                    hide-details   
                />
            </v-col>
            <v-col cols="8" class="d-flex align-center justify-end mb-2" v-if="tipoVista!=1">
                <v-spacer/>
                <span class="mr-2" v-if="MedicosSeleccionados.length>0" style="font-size:0.775rem">Seleccionados: {{MedicosSeleccionados.length}}</span>
                <v-btn  color="primary" variant="tonal"  @click="PermitirAccesoConfirmacion" v-if="(tipoVista==2 || tipoVista==4) && MedicosSeleccionados.length>0" size="small">Aprobar</v-btn>
                <v-btn v-if="tipoVista==3 && MedicosSeleccionados.length>0" @click="SuspenderAccesoConfirmacion" color="primary" variant="tonal" size="small">Suspender</v-btn>
            </v-col>
            <v-progress-linear v-if="StoreApp.LoaderPeticionenCurso" color="primary" indeterminate></v-progress-linear >
            <v-col cols="12" v-if="ListaDeMedicos.length>0">
                        <v-table class="border-thin rounded-lg" density="compact">
                            <thead>
                                <tr>
                                    <th class="text-left" width="50" v-if="tipoVista!=1">
                                        <v-checkbox
                                            hide-details
                                            :disabled="LoaderSeleccionTodos"
                                            color="primary"
                                            density="compact"
                                            :model-value="MedicosSeleccionados.length === props.totalRegistros"
                                            :indeterminate="MedicosSeleccionados.length > 0 && MedicosSeleccionados.length < props.totalRegistros"
                                            @update:modelValue="TodosMedicosPasantesSeleccionados"
                                        ></v-checkbox>
                                    </th>
                                    <th class="text-left font-weight-medium">
                                        Médico Pasante
                                    </th>
                                    <th class="text-left font-weight-medium">
                                        Periodo de Promoción
                                    </th>
                                    <th class="text-left font-weight-medium">
                                        Estado
                                    </th>
                                    <th class="text-left font-weight-medium">
                                        Sede
                                    </th>
                                    <th class="text-left font-weight-medium"  v-if="tipoVista!=2 && tipoVista!=3 && tipoVista!=4">
                                        Fecha de solicitud
                                    </th>
                                    <th class="text-left font-weight-medium" v-if="tipoVista!=2 && tipoVista!=4">
                                        Acceso desde
                                    </th>
                                    <th class="text-left font-weight-medium"  v-if="tipoVista!=2 && tipoVista!=4">
                                        Acceso hasta
                                    </th>
                                    <th class="text-center font-weight-medium" width="100">
                                        Opciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="medico in ListaDeMedicos" :key="medico.uid" class="table-row">
                                    
                                    <td  v-if="tipoVista!=1">
                                        <v-checkbox
                                            hide-details
                                            :key="medico.uid"
                                            density="compact"
                                            color="primary"
                                            v-model="MedicosSeleccionados"
                                            :value="medico.uid"
                                        ></v-checkbox>

                                    </td>
                                    <td>
                                        <div class="d-flex align-center py-2">
                                            <v-avatar color="primary" size="25" class="me-3" variant="tonal">
                                                <v-icon color="primary" size="15">mdi-doctor</v-icon>
                                            </v-avatar>
                                            <div>
                                                <div class="poppins-medium text-no-wrap pa-0">
                                                    <span style="font-size: 0.715rem;">{{ medico.nombre }} {{ medico.primer_apellido }} {{ medico.segundo_apellido }}</span>
                                                </div>
                                                <div class="text-caption text-medium-emphasis pa-0">
                                                    <span class="mr-2 poppins-bold text-no-wrap" style="font-size: 0.685rem;">Licenciatura: {{ medico.licenciatura }}</span>
                                                </div>
                                                <div class="text-caption text-medium-emphasis text-no-wrap">
                                                    <span class="mr-2" style="font-size: 0.685rem;">No.Cuenta {{ medico.no_cuenta }}</span>
                                                </div>
                                                
                                                <div class="poppins-medium text-medium-emphasis text-caption text-no-wrap pa-0">
                                                    
                                                    <span style="font-size: 0.685rem;"><v-icon size="10" class="mr-2">mdi-email</v-icon>{{ medico.email }}</span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <div class="poppins-medium text-medium-emphasis text-caption text-no-wrap">
                                            Del {{ medico.fecha_inicio_promocion }} a {{ medico.fecha_termino_promocion }}
                                        </div>
                                    </td>
                                    <td>
                                        <v-chip
                                            :color="getStatusColor(medico.status)"
                                            :variant="getStatusVariant(medico.status)"
                                            size="small"
                                            class="font-weight-medium"
                                        >
                                            {{ getStatusText(medico.status) }}
                                        </v-chip>
                                    </td>
                                    <td class="text-body-2 text-no-wrap">{{medico.dependencia ?? 'Sin sede asignada'}}</td>
                                    <td class="text-body-2 text-no-wrap" v-if="tipoVista!=2 && tipoVista!=3 && tipoVista!=4">{{medico.fecha_solicitud_acceso ?? 'No registrada'}}</td>
                                    <td class="text-body-2 text-no-wrap"  v-if="tipoVista!=2 && tipoVista!=4">{{medico.fecha_acceso_inicio ?? 'Ninguna'}}</td>
                                    <td class="text-body-2 text-no-wrap"  v-if="tipoVista!=2 && tipoVista!=4">{{medico.fecha_acceso_vencimiento ?? 'Ninguna'}}</td>
                                    <td>
                                        <div class="d-flex justify-center gap-2">
                                            <v-btn
                                                v-tooltip="{text:'Observaciones del médico',location:'bottom'}"
                                                v-if="(tipoVista==2 || tipoVista==1) && medico.observaciones"
                                                icon="mdi-comment-text-outline"
                                                variant="tonal"
                                                color="primary"
                                                @click="MostrarObservacionesMedico(medico.observaciones)"
                                                size="small"
                                                density="comfortable"
                                            ></v-btn>
                                            <Link :href="`/personal/medico-pasante/${medico.uid}/editar`">
                                                <v-btn
                                                    v-tooltip="{text:'Consultar / Editar información',location:'bottom'}"
                                                    icon="mdi-pencil"
                                                    variant="text"
                                                    color="primary"
                                                    size="small"
                                                    density="comfortable"
                                                ></v-btn>
                                            </Link>
                                            
                                            <v-btn
                                                v-tooltip="{text:'Autorizar acceso',location:'bottom'}"
                                                v-if="tipoVista==2 || tipoVista==4"
                                                icon="mdi-account-check"
                                                variant="text"
                                                color="primary"
                                                @click="PermitirAccesoIndividual(medico.uid)"
                                                size="small"
                                                density="comfortable"
                                            ></v-btn>
                                            <v-btn
                                                v-tooltip="{text:'Suspender acceso',location:'bottom'}"
                                                v-if="tipoVista==3"
                                                icon="mdi-lock-outline"
                                                variant="text"
                                                color="primary"
                                                @click="SuspenderAccesoIndividual(medico.uid)"
                                                size="small"
                                                density="comfortable"
                                            ></v-btn>
                                            <v-btn
                                                v-tooltip="{text:'Eliminar médico',location:'bottom'}"
                                                 v-if="medico.status==1"
                                                icon="mdi-delete"
                                                variant="text"
                                                color="primary"
                                                disabled
                                                @click="SuspenderAccesoIndividual(medico.uid)"
                                                size="small"
                                                density="comfortable"
                                            ></v-btn>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="elevation-1 bg-white">
                                    <td :colspan="setColspanFooter" >
                                        <v-container fluid class="pa-0">
                                            <v-row no-gutters>
                                                <v-col class="d-flex align-center text-primary">
                                                    Mostrando {{totalRegistrosPagina}} de {{totalRegistros}} registros
                                                </v-col>
                                                <v-col class="d-flex justify-end">
                                                    <v-pagination v-model="PaginaInformacionPadre" @update:modelValue="ObtenerDatosPorPagina" color="primary"   :length="lastPage" :total-visible="5"></v-pagination>
                                                </v-col>
                                            </v-row>
                                        </v-container>

                                    </td>
                                </tr>
                            </tfoot>
                        </v-table>
                        
            </v-col>
            <v-empty-state
            v-else
            :icon="getEmptyStateIcon"
            :text="getEmptyStateText"
            :title="getEmptyStateTitle"
            width="100%"
        >
            <template v-slot:actions>
                <v-btn color="primary" v-if="tipoVista === 0">
                    Nueva importación
                </v-btn>
            </template>
        </v-empty-state>
        </v-row>
        
    </v-container>
    <AppAlerta></AppAlerta>

    <!-- Modal de Observaciones -->
    <v-dialog v-model="dialogObservaciones" max-width="600" transition="dialog-bottom-transition">
        <v-card class="rounded-lg overflow-hidden">
            <v-card-title class="d-flex align-center pa-4 bg-primary text-white position-relative">
                <div class="d-flex align-center">
                    <v-avatar color="white" size="36" class="me-3">
                        <v-icon icon="mdi-comment-text-outline" color="primary"></v-icon>
                    </v-avatar>
                    <div>
                        <div class="text-h6">Observaciones del Médico</div>
                        <div class="text-caption text-white text-opacity-75">Información detallada</div>
                    </div>
                </div>
                <v-spacer></v-spacer>
                <v-btn icon="mdi-close" variant="text" color="white" @click="dialogObservaciones = false" class="ml-2"></v-btn>
            </v-card-title>

            <v-card-text class="pa-6">
                <div v-if="observacionesMedico" class="observaciones-content">
                    <div class="d-flex align-center mb-4">
                        <v-icon icon="mdi-text-box-outline" color="primary" class="me-2"></v-icon>
                        <div class="text-subtitle-1 font-weight-medium">Detalles de las observaciones</div>
                    </div>
                    <v-card 
                        class="pa-4 rounded-lg bg-grey-lighten-3 position-relative elevation-0"
                    >
                        <div class="text-body-1 line-height-1-8">
                            {{ observacionesMedico }}
                        </div>
                        <div class="position-absolute top-0 end-0 pa-2">
                            <v-icon icon="mdi-format-quote-close" color="primary" class="opacity-25" size="24"></v-icon>
                        </div>
                    </v-card>
                </div>
                <div v-else class="text-center py-8">
                    <v-avatar color="grey-lighten-3" size="64" class="mb-4">
                        <v-icon icon="mdi-information-outline" size="32" color="grey"></v-icon>
                    </v-avatar>
                    <div class="text-h6 text-grey mb-2">Sin observaciones</div>
                    <div class="text-body-1 text-medium-emphasis">
                        No hay observaciones registradas para este médico.
                    </div>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" variant="tonal" @click="dialogObservaciones = false">
                    Cerrar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { AppStore } from '@/stores/AppStore';
import AppAlerta from '@/components/AppAlerta.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed,provide,inject, watch } from 'vue';
const LoaderSeleccionTodos = ref(false);
const StoreApp = AppStore();
const props = defineProps({
    ListaDeMedicos:{
        type:Object,
        default:{}
    },
    totalRegistros:{
        type:Number,
        default:0,
    },
    totalRegistrosPagina:{
        type:Number,
        default:0,
    },
    
    lastPage:{
        type:Number,
        default:0,
    },
    tipoVista:{
        type:Number,
        default:0
    },
});
const RecargarInformacion = inject('RecargarInformacion');
const PaginaInformacionPadre = inject('PaginaInformacion');
const ObtenerListaMedicos = inject('ObtenerListaMedicos');
const FiltroCampoBusqueda = inject('FiltroCampoBusqueda');
const MedicosSeleccionados = ref([]);
const dialogObservaciones = ref(false);
const observacionesMedico = ref('');
const searchField = ref('');

// Watch for changes in FiltroCampoBusqueda to update the search field
watch(FiltroCampoBusqueda, (newValue) => {
    searchField.value = newValue;
});

const getHeaderIcon = computed(() => {
    switch (props.tipoVista) {
        case 1:
            return 'mdi-database-import'
        case 2:
            return 'mdi-account-clock'
        case 3:
            return 'mdi-account-check'
        case 4:
            return 'mdi-account-cancel'
        default:
            return 'mdi-database-search'
    }
})

const setColspanFooter = computed(()=>{
    switch (props.tipoVista) {
        case 1:
            return 8
        case 2:
            return 7
        case 3:
            return 8
        case 4:
            return 6
    }
});

const getBusquedaEstatus = computed(() => {
    switch (props.tipoVista) {
        case 1:
            return 'importados'
        case 2:
            return 'pendientes'
        case 3:
            return 'autorizados'
        case 4:
            return 'suspendidos'
    }
})

const getHeaderTitle = computed(() => {
    switch (props.tipoVista) {
        case 1:
            return `${props.totalRegistros} usuarios importados`
        case 2:
            return `${props.totalRegistros} solicitudes pendientes`
        case 3:
            return `${props.totalRegistros} usuarios autorizados`
        case 4:
            return `${props.totalRegistros} usuarios suspendidos`
        default:
            return `${props.totalRegistros} registros`
    }
})

const getHeaderSubtitle = computed(() => {
    switch (props.tipoVista) {
        case 1:
            return 'Gestión y administración de médicos pasantes importados'
        case 2:
            return 'Solicitudes de acceso pendientes de revisión'
        case 3:
            return 'Usuarios con acceso autorizado al sistema'
        case 4:
            return 'Usuarios con acceso suspendido temporalmente'
        default:
            return 'Gestión y administración de registros'
    }
})

const getEmptyStateIcon = computed(() => {
    switch (props.tipoVista) {
        case 1:
            return 'mdi-database-import'
        case 2:
            return 'mdi-account-clock'
        case 3:
            return 'mdi-account-check'
        case 4:
            return 'mdi-account-cancel'
        default:
            return 'mdi-database-search'
    }
})

const getEmptyStateTitle = computed(() => {
    switch (props.tipoVista) {
        case 1:
            return 'Sin importaciones disponibles'
        case 2:
            return 'Sin solicitudes pendientes'
        case 3:
            return 'Sin usuarios autorizados'
        case 4:
            return 'Sin usuarios suspendidos'
        default:
            return 'Sin registros disponibles'
    }
})

const getEmptyStateText = computed(() => {
    switch (props.tipoVista) {
        case 1:
            return 'No hay importaciones disponibles para visualizar en este momento. Realiza una nueva importación para comenzar a registrar médicos pasantes.'
        case 2:
            return 'No hay solicitudes de acceso pendientes en este momento. Las solicitudes aparecerán aquí cuando los médicos pasantes completen su registro.'
        case 3:
            return 'No hay usuarios autorizados en este momento. Los usuarios autorizados aparecerán aquí una vez que sus solicitudes sean aprobadas.'
        case 4:
            return 'No hay usuarios suspendidos en este momento. Los usuarios suspendidos aparecerán aquí cuando su acceso sea revocado temporalmente.'
        default:
            return 'No hay registros disponibles para mostrar en este momento.'
    }
})

const getStatusColor = (status) => {
    switch (status) {
        case 1:
        case 2:
        case 3:
        case 4:
            return 'primary'
        default:
            return 'grey'
    }
}

const getStatusVariant = (status) => {
    switch (status) {
        case 1:
        case 4:
            return 'tonal'
        case 2:
            return 'outlined'
        case 3:
            return 'elevated'
        default:
            return 'tonal'
    }
}

const getStatusText = (status) => {
    switch (status) {
        case 1:
            return 'Importado'
        case 2:
            return 'Pendiente de acceso'
        case 3:
            return 'Autorizado'
        case 4:
            return 'Pendiente'
        default:
            return 'Suspendido'
    }
}

const TodosMedicosPasantesSeleccionados = (valor)=>{
    
    if(valor){
        LoaderSeleccionTodos.value = true;
        axios.get("/personal/medico-pasante/seleccionar-todos",{params:{estado:getBusquedaEstatus.value}}).then((response)=>{
            MedicosSeleccionados.value = response.data;
        }).catch((error)=>{

        }).finally(()=>{
            LoaderSeleccionTodos.value = false;
        })
    }else{
        MedicosSeleccionados.value = [];
    }
    
}


const PermitirAccesoConfirmacion = ()=>{
    console.log("Muestra la alerta")
    StoreApp.IconoAlerta = 'mdi-alert-circle';
    StoreApp.MostrarAlerta = true;
    StoreApp.TituloAlerta = 'Autorización de Acceso';
    StoreApp.MensajeAlerta = `¿Deseas autorizar el acceso al sistema para <span class='poppins-bold'>${MedicosSeleccionados.value.length}</span> médico(s) pasante(s)?`;
    StoreApp.EstadoAlerta = 1;
    StoreApp.CallbackAlerta = "PeticionAccesoSistema";
}

const SuspenderAccesoConfirmacion = ()=>{
    StoreApp.IconoAlerta = 'mdi-alert-circle';
    StoreApp.MostrarAlerta = true;
    StoreApp.TituloAlerta = 'Suspensión de acceso';
    StoreApp.MensajeAlerta = `¿Deseas suspender el acceso al sistema para <span class='poppins-bold'>${MedicosSeleccionados.value.length}</span> médico(s) pasante(s)?`;
    StoreApp.EstadoAlerta = 1;
    StoreApp.CallbackAlerta = "PeticionSuspenderAccesoSistema";
}

const PermitirAccesoIndividual = (medico)=>{
    MedicosSeleccionados.value =  [medico];
    PermitirAccesoConfirmacion();
}


const SuspenderAccesoIndividual = (medico)=>{
    MedicosSeleccionados.value =  [medico];
    SuspenderAccesoConfirmacion();
}


const LoadParentFunction = async()=>{
    console.log("Padre funcion");
    await RecargarInformacion();
    StoreApp.OcultarAlerta();
}

const PeticionAccesoSistema= async()=>{
    var titulo,mensaje,icono,estado,callback;
    await axios.post("/notificaciones/medico-pasante/permitir-acceso",{medicos:MedicosSeleccionados.value})
    .then((response)=>{
        titulo = "Autorización exitosa";
        icono="mdi-check-circle";
        mensaje = response.data.message;
        callback = "LoadParentFunction";
        estado = 2;
        MedicosSeleccionados.value=[];
    }).catch((error)=>{
        titulo = "Autorización fallida";
        icono="mdi-close-circle";
        callback = null;
        mensaje = error.response.data.message ?? 'Ha ocurrido un error, por favor intentelo más tarde.';
        estado = 0;
    }).finally(()=>{
        StoreApp.MostrarAlerta = true;
        StoreApp.IconoAlerta = icono;
        StoreApp.MensajeAlerta = mensaje;
        StoreApp.TituloAlerta = titulo;
        StoreApp.EstadoAlerta = estado;
        StoreApp.CallbackAlerta = callback;
    })
}
const PeticionSuspenderAccesoSistema= async()=>{
    var titulo,mensaje,icono,estado,callback;
    await axios.post("/personal/medico-pasante/suspender-acceso",{medicos:MedicosSeleccionados.value})
    .then((response)=>{
        titulo = "Suspensión de acceso exitosa";
        icono="mdi-check-circle";
        mensaje = response.data.message;
        callback = "LoadParentFunction";
        estado = 2;
        MedicosSeleccionados.value=[];
    }).catch((error)=>{
        titulo = "Suspensión de acceso fallida";
        icono="mdi-close-circle";
        callback = null;
        mensaje = error.response.data.message ?? 'Ha ocurrido un error, por favor intentelo más tarde.';
        estado = 0;
    }).finally(()=>{
        StoreApp.MostrarAlerta = true;
        StoreApp.IconoAlerta = icono;
        StoreApp.MensajeAlerta = mensaje;
        StoreApp.TituloAlerta = titulo;
        StoreApp.EstadoAlerta = estado;
        StoreApp.CallbackAlerta = callback;
    })
}

const BusquedaPorCampo = async(valor)=>{
    //FiltroCampoBusqueda.value=valor;
    console.log("entra qayi",valor);
    FiltroCampoBusqueda.value=valor;
    PeticionListaMedicos();
}


const PeticionListaMedicos = StoreApp.debounce(ObtenerListaMedicos,350);

const ObtenerDatosPorPagina = async(pagina)=>{
    await ObtenerListaMedicos();
}

const MostrarObservacionesMedico = (observaciones) => {
    observacionesMedico.value = observaciones;
    dialogObservaciones.value = true;
};

provide('FuncionesPadre',{PeticionAccesoSistema,LoadParentFunction,PeticionSuspenderAccesoSistema})
defineExpose({
    MedicosSeleccionados,
})
</script>

<style scoped>
.line-height-1-8 {
    line-height: 1.8;
}

.position-relative {
    position: relative;
}

.position-absolute {
    position: absolute;
}

.top-0 {
    top: 0;
}

.end-0 {
    right: 0;
}

.opacity-25 {
    opacity: 0.25;
}
</style> 