<template>
    <v-app>
        <Head title="Validar información"></Head>
        <v-container class="fill-height bg-surface" fluid>
            <v-row justify="center" align="center">
                <v-col cols="12" sm="10" md="8" lg="6">
                    <v-card elevation="0" class="mx-auto rounded-xl surface-container">
                        <!-- Header con diseño M3 -->
                        <div class="bg-primary-container pa-8 rounded-t-xl">
                            <div class="text-center">
                                <v-avatar color="primary" size="72" class="mb-4 elevation-2">
                                    <span class="text-h4 text-on-primary">E</span>
                                </v-avatar>
                                <h1 class="poppins-semibold text-on-primary-container mb-2">Validar Información</h1>
                            </div>
                        </div>
                        <v-card-text class="pa-8">
                            <!-- Alerta Informativa M3 -->
                            <v-alert
                                type="info"
                                variant="tonal"
                                class="mb-10 mt-n5 rounded-lg"
                                icon="mdi-information"
                                border="start"
                                color="primary"
                            >
                                <div class="poppins-medium">
                                    Por favor, verifica cuidadosamente la siguiente información. Si encuentras algún error o dato incorrecto, utiliza el botón "Reportar errores" para notificarlo. Una vez que confirmes que todo está correcto, presiona "Guardar" para continuar.
                                </div>
                            </v-alert>

                            <!-- Información del Usuario -->
                            <v-card class="mb-8 rounded-lg elevation-0 bg-grey-lighten-4">
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12" md="6" class="border-e">
                                            <div class="mb-4">
                                                <div class="text-primary mb-2 poppins-semibold" style="font-size:1.025rem;">No. Cuenta</div>
                                                <div class="text-body-1 font-weight-medium">{{ medicoPasante.no_cuenta || 'No disponible' }}</div>
                                            </div>
                                        </v-col>
                                        <v-col cols="12" md="6" class="ps-md-6">
                                            <div class="mb-4">
                                                <div class="text-primary mb-2 poppins-semibold" style="font-size:1.025rem;">Nombre</div>
                                                <div class="text-body-1 font-weight-medium">{{ medicoPasante.nombre }} {{ medicoPasante.primer_apellido }} {{ medicoPasante.segundo_apellido }}</div>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>

                            <!-- Información del Servicio Social -->
                            <v-card class="mb-8 rounded-lg elevation-0 bg-grey-lighten-4">
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12" class="border-bottom pb-4">
                                            <div class="mb-2">
                                                <div class="text-primary mb-2 poppins-semibold" style="font-size:1.025rem;">Sede donde realizará su servicio social</div>
                                                <div class="text-body-1 font-weight-medium">{{ medicoPasante.sede || 'No disponible' }}</div>
                                            </div>
                                        </v-col>
                                        <v-col cols="12">
                                            <div>
                                                <div class="text-primary mb-2 poppins-semibold" style="font-size:1.025rem;">Periodo de promoción</div>
                                                <div class="text-body-1 font-weight-medium">
                                                    De: {{ medicoPasante?.fecha_inicio || 'No disponible' }} a {{ medicoPasante?.fecha_termino || 'No disponible' }}
                                                </div>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>

                            <!-- Formulario de Observaciones -->
                            <v-card class="mb-8 rounded-lg elevation-0 bg-grey-lighten-4" v-if="ReportarErrorSection">
                                <v-card-text>
                                    <div class="text-subtitle-1 font-weight-bold text-primary mb-4 ">Observaciones</div>
                                    <v-textarea
                                        v-model="observaciones"
                                        variant="outlined"
                                        color="primary"
                                        rows="4"
                                        placeholder="Ingresa tus observaciones aquí..."
                                        :counter="255"
                                        :maxlength="255"
                                        bg-color="surface"
                                        rounded="lg"
                                    ></v-textarea>
                                </v-card-text>
                            </v-card>
                        </v-card-text>

                        <!-- Acciones -->
                        <v-card-actions class="pa-8 pt-0">

                            <v-btn
                                color="primary"
                                :disabled="LoaderPeticion"
                                :loading="LoaderPeticion"
                                variant="elevated"
                                prepend-icon="mdi-content-save"
                                class="text-none px-8"
                                rounded="lg"
                                min-width="160"
                                elevation="2"
                                @click="guardarRegistro"
                            >
                                Validar solicitud
                            </v-btn>
                            <v-spacer/>
                            <v-btn
                                color="primary"
                                :disabled="LoaderPeticion"
                                variant="tonal"
                                prepend-icon="mdi-alert-circle"
                                class="text-none"
                                rounded="lg"
                                v-if="!ReportarErrorSection"
                                min-width="160"
                                @click="ReportarError"
                            >
                                Reportar errores
                            </v-btn>
                            <v-btn
                                color="primary"
                                variant="tonal"
                                :disabled="LoaderPeticion"
                                prepend-icon="mdi-close"
                                class="text-none"
                                rounded="lg"
                                v-if="ReportarErrorSection"
                                min-width="160"
                                @click="OcultarReportarError"
                            >
                                Cancelar reporte
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <AppAlerta/>
    </v-app>
</template>

<script setup>
import { provide, ref } from 'vue'
import AppAlerta from '@/components/AppAlerta.vue';
import { AppStore } from '@/stores/AppStore';
import { Head, router } from '@inertiajs/vue3';
import { Store } from 'lucide-vue-next';
const api = "/completar-registro";
const StoreApp = AppStore();
const props = defineProps({
    registroUID:{
        type:String,
        default:null
    },
    medicoPasante:{
        type: Object,
        default:{
            nombre:null,
            no_cuenta:null,
            sede:null,
            fecha_inicio:null,
            fecha_termino:null
        }
    }
})

const observaciones = ref('')
const LoaderPeticion = ref(false);
const ReportarErrorSection = ref(false);

const RedirigirRegistroMPSS = () => {
    location.href="/registro_mpss";
}

const RedirigirLogin = () => {
    console.log("Que traes aqui e RedirigirLogin")
    location.href="/login";
}

provide('FuncionesPadre', { RedirigirRegistroMPSS, RedirigirLogin });

const ReportarError = ()=>{
    ReportarErrorSection.value=true;
    observaciones.value=null;
}
const OcultarReportarError = ()=>{
    ReportarErrorSection.value=false;
    observaciones.value=null;
}

const guardarRegistro = () => {
    // Implementar lógica de guardado
    LoaderPeticion.value=true;
    var tituloAlerta = null;
    var mensajeAlerta = null;
    var iconoAlerta = null;
    var estadoAlerta = null;
    axios.post(`${api}/${props.registroUID}`,{observaciones:observaciones.value}, {
        timeout: 30000
    }).then((response)=>{
        console.log("ok")
        tituloAlerta = "Solicitud de acceso completado";
        mensajeAlerta = " El responsable de servicio social revisará su solicitud y en breve recibirá una notificación cuando su acceso sea autorizado.";
        iconoAlerta = "mdi-check-circle";
        StoreApp.CallbackAlerta = "RedirigirLogin";
        estadoAlerta = 2;
        
    }).catch((error)=>{
        tituloAlerta = "Error al Procesar";
        mensajeAlerta = error.response.data.message ?? "Ocurrio un error. Por favor, intente nuevamente más tarde o contacte al responsable de servicio social si el problema persiste.";
        iconoAlerta = "mdi-alert-circle";
        estadoAlerta = 2;
        StoreApp.CallbackAlerta = null;
        if(error.response.data.status!=0){
            StoreApp.CallbackAlerta = "RedirigirRegistroMPSS";
        }

    }).finally(()=>{
        StoreApp.MostrarAlerta = true;
        StoreApp.EstadoAlerta = estadoAlerta;
        StoreApp.MensajeAlerta = mensajeAlerta;
        StoreApp.TituloAlerta = tituloAlerta;    ;
        StoreApp.IconoAlerta = iconoAlerta;
        LoaderPeticion.value=false;
    })
}
</script>

