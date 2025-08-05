<script setup >
import { Head } from '@inertiajs/vue3';
import { ref,onMounted,nextTick,provide,computed } from 'vue';
import moment from 'moment';
import App from '@/layouts/app/App.vue';
import { AppStore } from '@/stores/AppStore';
import AppAlerta from '@/components/AppAlerta.vue';
const api_catalogos = "/api/catalogos";
const api = "/api";

const StoreApp = AppStore();
defineProps({
    breadcrumbs: Array,
    ruta_valor:String
});
onMounted(async ()=>{
    ConsultoriosDisponibles.value =  await ObtenerComunidades();
    await toggleComunidades();
});

const TodasLasComunidadesSeleccionadas = computed(()=>{
    return ParametrosReporte.value.consultorios.length === ConsultoriosDisponibles.value.length;
})
const AlgunasComunidadesSeleccionadas = computed(()=>{
    return ParametrosReporte.value.consultorios.length > 0;
})

const CatDiagnosticos = ref([
    {title:"Diagnóstico Sindromático",valor:1},
    {title:"Diagnóstico Etiológico",valor:2},
    {title:"Diagnóstico Diferencial",valor:3},
]);
const ComunidadesReporteDefault = ref([]);
const DiagnosticosSeleccionadosDefault = ref(1);
const FechaInicioReporteDefault = ref(new Date())
const FechaCierreReporteDefault = ref(new Date())
const ConsultoriosDisponibles = ref([]);
const ParametrosReporte = ref({
    diagnostico:1,
    fechaInicial:new Date(),
    fechaFinal:new Date(),
    consultorios:[]
});


const toggleComunidades = ()=>{
    if(TodasLasComunidadesSeleccionadas.value){
        ParametrosReporte.value.consultorios = [];
    }else{
        const TodasLasComunidades = [];
        ConsultoriosDisponibles.value.forEach((item)=>{
            TodasLasComunidades.push(item.id);
        })
        ParametrosReporte.value.consultorios = TodasLasComunidades;
        ComunidadesReporteDefault.value = TodasLasComunidades;
    }
}

const validarFechaCierre = async(value)=>{
    if(ParametrosReporte.value.fechaFinal< value){
        await nextTick(()=>{
            ParametrosReporte.value.fechaFinal = null;
        })

    }
}
const ObtenerComunidades = async()=>{
    const url = `${api_catalogos}/consultorios`;
    var comunidades = [];
    await axios.get(url).then((response)=>{
        comunidades =  response.data;
    }).catch((error)=>{
        console.log(error);
    })
    return comunidades;
}

const ReiniciarCamposBusqueda = ()=>{
    ParametrosReporte.value.diagnostico= DiagnosticosSeleccionadosDefault.value;
    ParametrosReporte.value.consultorios  = ComunidadesReporteDefault.value ;
    ParametrosReporte.value.fechaInicial  = FechaInicioReporteDefault.value ;
    ParametrosReporte.value.fechaInicial  = FechaCierreReporteDefault.value ;
};



const ObtenerReporteSUIVE = async()=>{
    var url = `${api}/reportes/suive-obtener`;
    const datos = {
        params:{
            tipoDiagnostico:ParametrosReporte.value.diagnostico,
            comunidades:ParametrosReporte.value.consultorios,
            fechaInicioReporte:ParametrosReporte.value.fechaInicial,
            fechaCierreReporte:ParametrosReporte.value.fechaFinal,
        },
        timeout:18000,
        responseType: 'blob',
    };
    await axios.get(url,datos).then(async (response)=>{
        console.log("Ok")
        var date = moment().format("YYYY_MM_DD_H_m_s");
        var TipoDiagnostico = CatDiagnosticos.value.find((element)=>element.valor == ParametrosReporte.value.diagnostico);
        var TipoDiagnosticolabel = TipoDiagnostico.title;
        var name = `ReporteSUIVE ${TipoDiagnosticolabel} ${date}.xlsx`;
        name = name.replace(/ /g,"");
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download',name);
        link.setAttribute('target','_blank')
        document.body.appendChild(link);
        link.click();
        StoreApp.LoaderPeticionenCurso=false;

    }).catch((error)=>{
        console.log(error)
        StoreApp.LoaderPeticionenCurso=false;
        var mensaje = error.code == 'ECONNABORTED' ? 'El tiempo de espera para generar el reporte ha finalizado. Por favor, intenta modificando los filtros de búsqueda o intenta nuevamente más tarde.' : "No se han encontrado datos disponibles para generar el reporte solicitado. Por favor, verifique los parámetros de búsqueda o intente nuevamente más tarde.";
        StoreApp.IconoAlerta ="mdi-alert-circle";
        StoreApp.TituloAlerta = "Error";
        StoreApp.MostrarAlerta =true;
        StoreApp.EstadoAlerta =2;
        StoreApp.MensajeAlerta =mensaje;
        StoreApp.CallbackAlerta =null;
    })
}
const PeticionReporte = StoreApp.debounce(ObtenerReporteSUIVE,350);

provide('FuncionesPadre',{})
</script>
<template>
    <Head title="Reporte SUIVE"></Head>
    <App :breadcrumbs="breadcrumbs">
        <AppAlerta></AppAlerta>
        <v-container fluid>
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">Reporte SUIVE</span>
                </v-col>
                <v-col cols="12">
                    <div class="poppins-light text-grey-darken-1">
                        Completa todos los filtros requeridos para generar y descargar el reporte SUIVE en formato Excel, utilizando la información que ingreses a continuación.
                    </div>
                </v-col>
            </v-row>
            <v-row class="mt-10 mx-5" no-gutters>
                <v-col cols="12">
                    <v-card class="rounded-lg" elevation="0" >
                        <!-- Encabezado general -->
                        <v-row no-gutters>
                            <v-col>
                                <div class="d-flex align-center  bg-grey-lighten-4 pa-4 rounded-lg">
                                            <v-icon color="primary" class="me-2" size="15">mdi-file-excel</v-icon>
                                            <div class="poppins-semibold text-grey-darken-3" style="font-size: 0.98rem;">Genera y descarga el reporte SUIVE en Excel con los datos seleccionados</div>
                                        </div>
                                <!--<span class="poppins-semibold " style="font-size: 0.98rem;">Genera y descarga el reporte SUIVE en Excel con los datos seleccionados</span>-->
                            </v-col>
                        </v-row>
                        <!-- Diagnóstico -->
                        <v-row no-gutters class="mt-8 mb-6 px-8">
                            <v-col>
                                <div class="d-flex align-center rounded-lg mb-1">
                                    <v-icon color="primary" class="me-2" size="13">mdi-stethoscope</v-icon>
                                    <span class="poppins-semibold text-medium-emphasis text-grey-darken-3" style="font-size: 0.85rem;">Diagnóstico <span class='text-red'>*</span>
                                        <v-tooltip activator='parent' location='top'>Selecciona el tipo de diagnóstico para filtrar el reporte.</v-tooltip>
                                    </span>
                                </div>
                                <v-autocomplete
                                    density="compact"
                                    width="60%"
                                    rounded="lg"
                                    variant="outlined"
                                    v-model="ParametrosReporte.diagnostico"
                                    bg-color="white"
                                    clearable
                                    item-value="valor"
                                    item-title="title"
                                    class="mb-4"
                                    color="primary"
                                    placeholder="(ej. Sindromático, Diferencial)"
                                    flat
                                    hide-details
                                    :items="CatDiagnosticos || []"
                                />
                                <div class="text-grey-darken-1 mt-1" style="font-size:0.8rem;">Elige el diagnóstico principal del reporte.</div>
                            </v-col>
                        </v-row>
                        <!-- Periodo -->
                        <v-row no-gutters class="mt-8 mb-6 px-8">
                            <v-col>
                                <div class="d-flex align-center mb-2 rounded-lg">
                                    <v-icon color="primary" class="me-2" size="13">mdi-calendar-range</v-icon>
                                    <span class="poppins-semibold text-medium-emphasis text-grey-darken-3" style="font-size: 0.85rem;">Periodo <span class='text-red'>*</span>
                                        <v-tooltip activator='parent' location='top'>Selecciona el rango de fechas del reporte.</v-tooltip>
                                    </span>
                                </div>
                                <v-row class="d-flex align-center">
                                    <v-col cols="12" md="6">
                                        <span class="poppins-light" style="font-size: 0.75rem;">Desde</span>
                                        <v-date-input
                                            rounded="lg"
                                            @update:modelValue="validarFechaCierre" 
                                            variant="outlined"
                                            v-model="ParametrosReporte.fechaInicial"
                                            color="primary"
                                            density="compact"
                                            position="sticky"
                                            placeholder="Fecha de inicio (dd/mm/yyyy)"
                                            flat
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <span class="poppins-light" style="font-size: 0.75rem;">Hasta</span>
                                        <v-date-input
                                            rounded="lg"
                                            variant="outlined"
                                            color="primary"
                                            density="compact"
                                            position="sticky"
                                            v-model="ParametrosReporte.fechaFinal"
                                            :min="ParametrosReporte.fechaInicial"
                                            placeholder="Fecha de fin (dd/mm/yyyy)"
                                            flat
                                        />
                                    </v-col>
                                </v-row>
                                <div class="text-grey-darken-1" style="font-size:0.8rem;">Selecciona el periodo de tiempo para el reporte.</div>
                            </v-col>
                        </v-row>
                        <!-- Consultorios -->
                        <v-row no-gutters class="mt-8 mb-6 px-8">
                            <v-col>
                                <div class="d-flex align-center rounded-lg">
                                    <v-icon color="primary" class="me-2" size="13">mdi-hospital-building</v-icon>
                                    <span class="poppins-semibold text-medium-emphasis text-grey-darken-3" style="font-size: 0.85rem;">Consultorios <span class='text-red'>*</span>
                                        <v-tooltip activator='parent' location='top'>Selecciona uno o varios consultorios.</v-tooltip>
                                    </span>
                                </div>
                                <v-autocomplete
                                    hide-details
                                    rounded="lg"
                                    variant="outlined"
                                    multiple
                                    clearable
                                    v-model="ParametrosReporte.consultorios"
                                    item-value="id"
                                    item-title="nombre"
                                    color="primary"
                                    placeholder="Ejemplo: San Felipe Tlalmimilolpan, Capultitlan, etc."
                                    flat
                                    :items="ConsultoriosDisponibles || []"
                                >
                                    <!--<template v-slot:item="{ props, item }">
                                        <v-list-item
                                            v-bind="props"
                                            :subtitle="item.raw.direccion"
                                            :title="item.raw.nombre"
                                        ></v-list-item>
                                    </template>-->
                                    <template v-slot:prepend-item>
                                        <v-list-item
                                            title="Todas"
                                            color="primary"
                                            @click="toggleComunidades"
                                        >
                                            <template v-slot:prepend>
                                            <v-checkbox-btn
                                                :color="AlgunasComunidadesSeleccionadas ? 'primary' : 'primary'"
                                                :indeterminate="AlgunasComunidadesSeleccionadas && !TodasLasComunidadesSeleccionadas"
                                                :model-value="TodasLasComunidadesSeleccionadas"
                                            ></v-checkbox-btn>
                                            </template>
                                        </v-list-item>

                                        <v-divider class="mt-2"></v-divider>
                                    </template>
                                </v-autocomplete>
                                <div class="text-grey-darken-1 mt-1" style="font-size:0.8rem;">Puedes seleccionar más de un consultorio.</div>
                            </v-col>
                        </v-row>
                        <!-- Botón de acción -->
                        <v-row no-gutters>
                            <v-col class="d-flex justify-end">
                                <v-btn color="primary" :disabled="StoreApp.LoaderPeticionenCurso" variant="text" @click="ReiniciarCamposBusqueda" rounded="lg" prepend-icon="mdi-microsoft-excel">
                                    Limpiar campos
                                </v-btn>
                                <v-btn color="primary" :loading="StoreApp.LoaderPeticionenCurso" rounded="lg" @click="PeticionReporte" prepend-icon="mdi-microsoft-excel">
                                    Generar
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        
    </App>
</template>
