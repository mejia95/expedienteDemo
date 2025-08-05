<script setup >
import {useGoTo} from 'vuetify'
import { Head, router,Link } from '@inertiajs/vue3';
import { ref, nextTick,provide } from 'vue';
import App from '@/layouts/app/App.vue';
import { AppStore } from '@/stores/AppStore';
import AppAlerta from '@/components/AppAlerta.vue';
import { Store } from 'lucide-vue-next';
import moment from 'moment';
import 'moment/locale/es-mx';
moment.locale('es-mx');
const goTo = useGoTo()
const StoreApp = AppStore();

const props = defineProps({
    breadcrumbs: Array,
    InfoUsuario:Object,
    consultorios:Object
});


const PermitirCambioEliminacionSede =  ref(false);
const QuitarSedeMedico = ()=>{
    formData.value.cambioSede = 1;
    PermitirCambioEliminacionSede.value=true;
   
}
const CancelarModificacionSede = ()=>{
    formData.value.cambioSede = null;
    formData.value.sede = AuxSedeMedico.value;
    formData.value.motivoCambioSede = null;
    formData.value.evidenciaCambioSede = null;
    formData.value.sede = AuxSedeMedico.value;
    PermitirCambioEliminacionSede.value=false;
}
const CancelarModificacion = async()=>{
    await goTo(0,{duration:200})
    formData.value = {...formDataDef.value};
    formData.value.motivoCambioSede = null;
    formData.value.evidenciaCambioSede = null;
    formData.value.motivoCorreccion = null;
    AccionVistaMedicoPasante.value=0;
}

const HabilitarModificacion = async()=>{
    await goTo(0,{duration:200})
    AccionVistaMedicoPasante.value=1;
    formData.value.motivoCambioSede = null;
    formData.value.evidenciaCambioSede = null;
    formData.value.motivoCorreccion = null;
}


const AccionVistaMedicoPasante = ref(0);
const AuxSedeMedico = ref(props.InfoUsuario.dependenciaUID)
const formData = ref({
    nombre: props.InfoUsuario.nombre,
    primer_apellido: props.InfoUsuario.primer_apellido,
    segundo_apellido: props.InfoUsuario.segundo_apellido,
    no_cuenta: props.InfoUsuario.no_cuenta,
    email: props.InfoUsuario.email,
    sede: props.InfoUsuario.dependenciaUID,
    status: props.InfoUsuario.status,
    fecha_inicio: props.InfoUsuario.fecha_inicio_promocion ? moment(props.InfoUsuario.fecha_inicio).format('YYYY-MM-DD') : null,
    fecha_termino: props.InfoUsuario.fecha_termino_promocion ? moment(props.InfoUsuario.fecha_termino).format('YYYY-MM-DD') : null,
    fecha_inicio_promocion: props.InfoUsuario.fecha_inicio_promocion ? props.InfoUsuario.fecha_inicio_promocion : null,
    fecha_termino_promocion: props.InfoUsuario.fecha_termino_promocion ? props.InfoUsuario.fecha_termino_promocion : null,
    observaciones: props.InfoUsuario.observaciones,
    licenciatura: props.InfoUsuario.licenciatura,
    motivoCorreccion:null,
    motivoCambioSede:null,
    evidenciaCambioSede:null,
    cambioSede:null
})
const formDataDef = ref({
    nombre: props.InfoUsuario.nombre,
    primer_apellido: props.InfoUsuario.primer_apellido,
    segundo_apellido: props.InfoUsuario.segundo_apellido,
    no_cuenta: props.InfoUsuario.no_cuenta,
    email: props.InfoUsuario.email,
    sede: props.InfoUsuario.dependenciaUID,
    status: props.InfoUsuario.status,
    fecha_inicio: props.InfoUsuario.fecha_inicio_promocion ? moment(props.InfoUsuario.fecha_inicio).format('YYYY-MM-DD') : null,
    fecha_termino: props.InfoUsuario.fecha_termino_promocion ? moment(props.InfoUsuario.fecha_termino).format('YYYY-MM-DD') : null,
    fecha_inicio_promocion: props.InfoUsuario.fecha_inicio_promocion ? props.InfoUsuario.fecha_inicio_promocion : null,
    fecha_termino_promocion: props.InfoUsuario.fecha_termino_promocion ? props.InfoUsuario.fecha_termino_promocion : null,
    observaciones: props.InfoUsuario.observaciones,
    licenciatura: props.InfoUsuario.licenciatura,
    motivoCorreccion:null,
    motivoCambioSede:null,
    evidenciaCambioSede:null,
    cambioSede:null
})



const estados = [
    { title: 'Importado', value: 1 },
    { title: 'Pendiente de acceso', value: 2 },
    { title: 'Autorizado', value: 3 },
    { title: 'Suspendido', value: 0 }
]

const ConfirmacionActualizacionDatos = async ()=>{
    await goTo(0,{duration: 200});
    StoreApp.IconoAlerta = 'mdi-alert-circle';
    StoreApp.MostrarAlerta = true;
    StoreApp.TituloAlerta = '¿Estás seguro?';
    StoreApp.MensajeAlerta = 'Estas a punto de modificar la información del usuario';
    StoreApp.EstadoAlerta = 1;
    StoreApp.CallbackAlerta = "GuardarActualizacionDatos";
}

const GuardarActualizacionDatos = ()=>{
    console.log("ok");
    var mensaje,estado,titulo,callback;
    let url = `/personal/medico-pasante/${props.InfoUsuario.id}/editar`;
    axios.post(url,formData.value,{
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json'
            }
        }).then((response)=>{
        mensaje = response.data.message;
        estado = 2;
        callback = "RedirectToSelf";
        StoreApp.IconoAlerta = 'mdi-check-circle';
        titulo = "Información actualizada";
        formDataDef.value = {...formData.value};
    }).catch((error)=>{
        callback = null;
        StoreApp.IconoAlerta = 'mdi-close-circle';
        titulo = "Error";
        mensaje=error.response.data.message ?? "No se pudo actualizar la información. Por favor, verifique los datos e intente nuevamente.";
        estado = 0;
        console.log(error);
    }).finally(()=>{
        StoreApp.MostrarAlerta = true;
        StoreApp.TituloAlerta = titulo;
        StoreApp.MensajeAlerta = mensaje;
        StoreApp.EstadoAlerta = estado;
        StoreApp.CallbackAlerta = callback;
    });
}

const RedirectToSelf=async ()=>{
    await goTo(0,{duration: 200});
    await router.reload({ only: ['InfoUsuario'] });
    AccionVistaMedicoPasante.value=0;
    StoreApp.MostrarAlerta = false;
}

provide('FuncionesPadre',{GuardarActualizacionDatos,RedirectToSelf})

</script>
<template>
    <App :breadcrumbs="breadcrumbs">
        <v-container fluid>
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="8" class="d-flex align-center">
                    <v-container fluid>
                        <v-row no-gutters>
                            <v-col cols="12">
                                <span class="poppins-semibold seccion-titulo">{{AccionVistaMedicoPasante == 0 ? 'Info.':'Editar'}} Médico Pasante</span>
                            </v-col>
                            <v-col cols="12">
                                <div class="poppins-light text-grey-darken-1">
                                    {{AccionVistaMedicoPasante == 0 ? 'Consulta':'Actualiza'}} la información del médico pasante de Servicio Social.
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-col>
                <v-col cols="4" class="d-flex align-center justify-end">
                    <v-btn :variant="AccionVistaMedicoPasante == 0 ? 'tonal':'elevated'" color="primary" @click="AccionVistaMedicoPasante==0 ? HabilitarModificacion() : CancelarModificacion()">{{AccionVistaMedicoPasante == 0 ? 'Editar':'Cancelar'}}</v-btn>
                </v-col>
            </v-row>
            <v-divider class="mx-4 mt-5"></v-divider>
            <v-row class="mx-5" no-gutters>
                <v-col cols="12">
                    <v-card class="rounded-lg" elevation="0">
                        <!-- User Info Header -->
                        <v-card-text class="bg-primary-lighten-5 pa-6">
                            <div class="d-flex flex-column flex-md-row align-center">
                                <div class="d-flex align-center mb-4 mb-md-0">
                                    <v-avatar color="primary" size="70" class="me-4" variant="tonal">
                                        <v-icon color="primary" size="x-large">mdi-doctor</v-icon>
                                    </v-avatar>
                                    <div>
                                        <div class="text-h5 poppins-medium mb-2 text-uppercase">
                                            {{ formData.nombre }} {{ formData.primer_apellido }} {{ formData.segundo_apellido }}
                                        </div>
                                        <v-chip
                                            color="primary"
                                            variant="tonal"
                                            class="text-none pa-4 text-uppercase"
                                            size="small"
                                        >
                                            <v-icon start size="small">mdi-card-account-details</v-icon>
                                            <span class="mr-2">No. Cuenta</span>{{ formData.no_cuenta }}
                                        </v-chip>
                                    </div>
                                </div>
                                <v-divider vertical class="mx-4 d-none d-md-block"></v-divider>
                                <div class="d-flex flex-column flex-grow-1">
                                    <div class="d-flex flex-wrap gap-4">
                                        <div class="d-flex align-center">
                                            <v-icon size="small" color="primary" class="me-2">mdi-email</v-icon>
                                            <span class="text-medium-emphasis">{{ formData.email }}</span>
                                        </div>
                                        <div class="d-flex align-center">
                                            <v-icon size="small" color="primary" class="me-2">mdi-hospital-building</v-icon>
                                            <span class="text-medium-emphasis text-uppercase">{{ InfoUsuario.dependencia ?? 'No se tiene un consultorio para el médico'}}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <v-chip
                                            variant="tonal"
                                            class="text-none"
                                            size="small"
                                        >
                                            <v-icon start size="small">
                                                {{ formData.status === 3 ? 'mdi-check-circle' : 'mdi-clock-outline' }}
                                            </v-icon>
                                            {{ estados.find(e => e.value === formData.status)?.title }}
                                        </v-chip>
                                    </div>
                                </div>
                            </div>
                        </v-card-text>

                        <!-- Sección de Observaciones -->
                        <v-card-text class="pa-6 pt-0 mb-n5" v-if="formData.observaciones">
                            <v-expansion-panels variant="accordion" class="mb-4 border-thin rounded-lg" elevation="0">
                                <v-expansion-panel
                                    color="blue-accent-2"
                                    class="rounded-lg"
                                >
                                    <v-expansion-panel-title class="poppins-medium ">
                                        <div class="d-flex align-center">
                                            <v-icon class="me-2">mdi-alert-circle</v-icon>
                                            <span >Observaciones del Médico Pasante</span>
                                            <v-chip
                                                size="x-small"
                                                class="text-none ms-2"
                                            >
                                                Importante
                                            </v-chip>
                                        </div>
                                    </v-expansion-panel-title>
                                    <v-expansion-panel-text>
                                        <div class="text-grey-darken-2 rounded-lg poppins-medium pa-4 ">
                                           {{ formData.observaciones }}
                                        </div>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-card-text>
                        <!-- Form Content -->
                        <v-card-text class="pa-6">
                            
                                <v-row>
                                    <!-- Información Personal -->
                                    <v-col cols="12">
                                        <div class="d-flex align-center mb-4 bg-grey-lighten-4 pa-4 rounded-lg">
                                            <v-icon color="primary" class="me-2">mdi-account-details</v-icon>
                                            <div class="poppins-semibold text-grey-darken-3">Información Personal</div>
                                        </div>
                                    </v-col>
                                    
                                    <v-col cols="12" md="4">
                                        <span>Nombre(s)</span> <span class="text-red">*</span>
                                        <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                            <v-icon color="primary" size="small" class="me-2">mdi-account-details</v-icon>
                                            {{ formData.nombre }}
                                        </p>
                                        <v-text-field
                                            v-else
                                            v-model="formData.nombre"
                                            variant="outlined"
                                            density="comfortable"
                                            hide-details
                                            class="mb-4"
                                            prepend-inner-icon="mdi-account"
                                            rounded="lg"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="4">
                                        <span>Primer apellido</span> <span class="text-red">*</span>
                                        <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                            <v-icon color="primary" size="small" class="me-2">mdi-account-details</v-icon>
                                            {{ formData.primer_apellido }}
                                        </p>
                                        <v-text-field
                                            v-else
                                            v-model="formData.primer_apellido"
                                            variant="outlined"
                                            density="comfortable"
                                            hide-details
                                            class="mb-4"
                                            prepend-inner-icon="mdi-account"
                                            rounded="lg"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="4">
                                        <span>Segundo apellido</span>
                                        <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                            <v-icon color="primary" size="small" class="me-2">mdi-account-details</v-icon>
                                            {{ formData.segundo_apellido }}
                                        </p>
                                        <v-text-field
                                            v-else
                                            v-model="formData.segundo_apellido"
                                            variant="outlined"
                                            density="comfortable"
                                            hide-details
                                            class="mb-4"
                                            prepend-inner-icon="mdi-account"
                                            rounded="lg"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <span>Número de cuenta</span> <span class="text-red">*</span>
                                        <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                            <v-icon color="primary" size="small" class="me-2">mdi-card-account-details</v-icon>
                                            {{ formData.no_cuenta }}
                                        </p>
                                        <v-text-field
                                            v-else
                                            v-model="formData.no_cuenta"
                                            variant="outlined"
                                            density="comfortable"
                                            hide-details
                                            class="mb-4"
                                            prepend-inner-icon="mdi-card-account-details"
                                            rounded="lg"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <span>Correo electrónico</span> <span class="text-red">*</span>
                                        <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                            <v-icon color="primary" size="small" class="me-2">mdi-email</v-icon>
                                            {{ formData.email }}
                                        </p>
                                        <v-text-field
                                            v-else
                                            v-model="formData.email"
                                            variant="outlined"
                                            density="comfortable"
                                            hide-details
                                            class="mb-4"
                                            type="email"
                                            prepend-inner-icon="mdi-email"
                                            rounded="lg"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <span>Licenciatura</span> <span class="text-red">*</span>
                                        <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                            <v-icon color="primary" size="small" class="me-2">mdi-school</v-icon>
                                            {{ formData.licenciatura }}
                                        </p>
                                        <v-text-field
                                            v-else
                                            v-model="formData.licenciatura"
                                            variant="outlined"
                                            density="comfortable"
                                            hide-details
                                            class="mb-4"
                                            prepend-inner-icon="mdi-school"
                                            rounded="lg"
                                        ></v-text-field>
                                    </v-col>

                                    <!-- Información de Servicio Social -->
                                    <v-col cols="12" class="mt-6">
                                        <div class="d-flex align-center mb-4 bg-grey-lighten-4 pa-4 rounded-lg">
                                            <v-icon color="primary" class="me-2">mdi-hospital-box</v-icon>
                                            <div class="poppins-semibold text-grey-darken-3">Información de Servicio Social</div>
                                        </div>
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <v-label class="text-grey-darken-3">Fecha de inicio de promoción</v-label>
                                        <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                            <v-icon color="primary" size="small" class="me-2">mdi-calendar</v-icon>
                                            {{ formData.fecha_inicio_promocion }}
                                        </p>
                                        <v-text-field
                                            v-else
                                            v-model="formData.fecha_inicio"
                                            type="date"
                                            variant="outlined"
                                            density="comfortable"
                                            hide-details
                                            class="mb-4"
                                            prepend-inner-icon="mdi-calendar"
                                            rounded="lg"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="6">
                                        <v-label class="text-grey-darken-3">Fecha de término de promoción</v-label>
                                        <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                            <v-icon color="primary" size="small" class="me-2">mdi-calendar</v-icon>
                                            {{ formData.fecha_termino_promocion }}
                                        </p>
                                        <v-text-field
                                            v-else
                                            v-model="formData.fecha_termino"
                                            type="date"
                                            variant="outlined"
                                            density="comfortable"
                                            hide-details
                                            class="mb-4"
                                            prepend-inner-icon="mdi-calendar"
                                            rounded="lg"
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" md="9" sm="9" lg="9" xl="6">
                                        <div class="d-flex align-center mb-2">
                                            <v-icon color="primary" size="small" class="me-2">mdi-hospital-building</v-icon>
                                            <span class="text-subtitle-2 font-weight-medium">Sede</span>
                                            <span class="text-red ms-1">*</span>
                                        </div>
                                        <div class="d-flex align-center">
                                            <p v-if="AccionVistaMedicoPasante==0" class="poppins-bold text-grey-darken-3 mt-2 pa-3 bg-grey-lighten-4 rounded-lg d-flex align-center">
                                                <v-icon color="primary" size="small" class="me-2">mdi-map-marker</v-icon>
                                                {{ formData.sede ? consultorios.find(c => c.id === formData.sede)?.nombre : 'No se ha asignado una sede de consulta' }}
                                            </p>
                                            <template v-else>
                                                <v-select
                                                    v-model="formData.sede"
                                                    :items="consultorios"
                                                    clearable
                                                    item-value="id"
                                                    item-title="nombre"
                                                    :disabled="!PermitirCambioEliminacionSede"
                                                    variant="outlined"
                                                    density="comfortable"
                                                    hide-details
                                                    class="mb-4 me-2"
                                                    placeholder="(ej. Consultorio Ejemplo)"
                                                    rounded="lg"
                                                ></v-select>
                                                <v-btn
                                                    v-tooltip="`Quitar sede`"
                                                    v-if="!PermitirCambioEliminacionSede"
                                                    color="primary"
                                                    variant="text"
                                                    class="mt-n4"
                                                    @click="QuitarSedeMedico"
                                                >
                                                <v-icon>mdi-swap-horizontal</v-icon>
                                                    Cambiar / Quitar sede
                                                </v-btn>
                                                <v-btn
                                                    v-if="PermitirCambioEliminacionSede"
                                                    color="primary"
                                                    class="mt-n4"
                                                    variant="text"
                                                    @click="CancelarModificacionSede"
                                                    v-tooltip="`Cancelar modificación de sede`"
                                                >
                                                    <v-icon start>mdi-close-circle</v-icon>
                                                    Cancelar modificación
                                                </v-btn>
                                            </template>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" v-if="AccionVistaMedicoPasante==1 && PermitirCambioEliminacionSede">
                                        <div class="d-flex align-center mb-2">
                                            <v-icon color="primary" size="small" class="me-2">mdi-hospital-building</v-icon>
                                            <span class="text-subtitle-2 font-weight-medium">Motivo de cambio de sede</span> <span class="text-red">*</span>
                                        </div>
                                        <v-textarea
                                            v-model="formData.motivoCambioSede"
                                            variant="outlined"
                                            density="comfortable"
                                            class="mb-4"
                                            placeholder="Ingrese el motivo del cambio de sede (máx 255 caracteres)"
                                            rows="4"
                                             hide-details="auto"
                                            :counter="255"
                                            :maxlength="255"
                                            rounded="lg"
                                        ></v-textarea>
                                    </v-col>
                                    <v-col cols="12"  v-if="AccionVistaMedicoPasante==1 && PermitirCambioEliminacionSede">
                                        <div class="d-flex align-center mb-2">
                                            <span class="text-subtitle-2 font-weight-medium">Evidencia de cambio de sede</span> <span class="text-red">*</span>
                                        </div>
                                        <v-file-input
                                            v-model="formData.evidenciaCambioSede"
                                            variant="outlined"
                                            density="comfortable"
                                            show-size
                                            hide-details="auto"
                                            class="mb-4"
                                            rounded="lg"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            :rules="[
                                                v => !v || v.size < 3 * 1024 * 1024 || 'El archivo debe ser menor a 3MB',
                                                v => !v || ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'].includes(v.type) || 'Solo se permiten archivos PDF o imágenes (JPG, JPEG, PNG)'
                                            ]"
                                            :error-messages="formData.evidenciaCambioSede && formData.evidenciaCambioSede.size > 3 * 1024 * 1024 ? 'El archivo debe ser menor a 3MB' : ''"
                                        ></v-file-input>
                                    </v-col>
                                    <!-- Motivo de correccion -->
                                    <v-col cols="12" class="mt-6" v-if="AccionVistaMedicoPasante==1">
                                        <div class="d-flex align-center mb-4 bg-grey-lighten-4 pa-4 rounded-lg">
                                            <v-icon color="primary" class="me-2">mdi-file-document-edit</v-icon>
                                            <div class="poppins-semibold text-grey-darken-3">Motivo de corrección de datos</div>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" v-if="AccionVistaMedicoPasante==1">
                                        <div class="d-flex align-center mb-2">
                                            <v-icon color="primary" size="small" class="me-2">mdi-text-box-edit</v-icon>
                                            <span class="text-subtitle-2 font-weight-medium">Justificación de la corrección</span>
                                            <span class="text-red ms-1">*</span>
                                        </div>
                                        <v-textarea
                                            v-model="formData.motivoCorreccion"
                                            variant="outlined"
                                            density="comfortable"
                                            class="mb-4"
                                            placeholder="Ingrese el motivo de la corrección (máx. 255 caracteres)"
                                            rows="4"
                                            :counter="255"
                                            :maxlength="255"
                                            rounded="lg"
                                        ></v-textarea>
                                    </v-col>
                                    <!-- Botones de Acción -->
                                    <v-col cols="12" class="d-flex justify-end gap-2 mt-4" >
                                        <Link href="/personal/medico-pasante" >
                                            <v-btn
                                                color="grey-darken-1"
                                                variant="tonal"
                                                class="text-none"
                                                rounded="lg"
                                            >
                                                Salir
                                            </v-btn>
                                        </Link>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            v-if="AccionVistaMedicoPasante==1"
                                            variant="tonal"
                                            class="text-none"
                                            rounded="lg"
                                            @click="CancelarModificacion"
                                            prepend-icon="mdi-close"
                                        >
                                            Cancelar
                                        </v-btn>
                                        <v-btn
                                            v-if="AccionVistaMedicoPasante==1"
                                            color="primary"
                                            class="text-none"
                                            rounded="lg"
                                            @click="ConfirmacionActualizacionDatos"
                                            prepend-icon="mdi-content-save"
                                        >
                                            Guardar Cambios
                                        </v-btn>
                                    </v-col>
                                </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <AppAlerta></AppAlerta>
    </App>
</template>

<style scoped>
.gap-2 {
    gap: 8px;
}
</style>
