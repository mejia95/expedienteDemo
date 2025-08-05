<script setup >
import { Head,Link } from '@inertiajs/vue3';
import { ref,onMounted} from 'vue';
import { useDisplay } from 'vuetify';
import moment from 'moment';
import App from '@/layouts/app/App.vue';
import { AppStore } from '@/stores/AppStore';
import AppAlerta from '@/components/AppAlerta.vue';
const api_catalogos = "/api/catalogos";
const api = "/api";

const StoreApp = AppStore();
const props = defineProps({
    breadcrumbs: Array,
    ruta_valor:String,
    pacientes:Object,
});
const { mobile,xs,smAndDown,mdAndUp,lgAndUp,xlAndUp,smAndUp } = useDisplay();
const ListaPacientes = ref({data:[]})
onMounted(()=>{
    ListaPacientes.value = props.pacientes;
})


const getConsultorios = async()=>{
    await axios.get(`${api_catalogos}/consultorios`).then((response)=>{
        consultoriosOptions.value = response.data;
    }).catch(async(error)=>{
        consultoriosOptions.value = [];
    });
}
const getGeneros = async()=>{
    await axios.get(`${api_catalogos}/generos`).then((response)=>{
        sexosOptions.value = response.data;
    }).catch(async(error)=>{
        sexosOptions.value = [];
    });
}

// Utilidad para icono de género
function getGeneroIcon(genero) {
    if (genero === 'Hombre') return 'mdi-gender-male';
    if (genero === 'Mujer') return 'mdi-gender-female';
    return 'mdi-account';
}


// Filtros reactivos y opciones
const mostrarFiltros = ref(false);
const tabPacientes = ref(1);
const busqueda = ref('');
const edadMin = ref(null);
const edadMax = ref(null);
const selectedConsultorio = ref([]);
const fechaInicio = ref();
const fechaFin = ref();
const selectedSexo = ref([]);
const filtrosActivos = ref(false);
const PaginaInformacion = ref(1);

const consultoriosOptions = ref([]);
const sexosOptions = ref([]);


const mostrarFiltrosForm = async()=>{
    console.log("Que traes en mostar filtros");
    console.log(mostrarFiltros.value);
    if(!mostrarFiltros.value){
        await getConsultorios();
        await getGeneros();
        mostrarFiltros.value = true;
    }else{
        mostrarFiltros.value = false;
    }
}


const  aplicarFiltros  = async()=>{
  filtrosActivos.value = false;
  if (edadMin.value || edadMax.value) {
    let txt = 'Edad:';
    if (edadMin.value) txt += ` desde ${edadMin.value}`;
    if (edadMax.value) txt += ` hasta ${edadMax.value}`;
    filtrosActivos.value = true;
  }
  if (selectedConsultorio.value)filtrosActivos.value = true;
  if (selectedSexo.value)filtrosActivos.value = true;;
  PaginaInformacion.value=1;
  await ObtenerPacientes();
  mostrarFiltros.value=false;
}
const limpiarFiltros = async()=>{
  edadMin.value = null;
  edadMax.value = null;
  selectedConsultorio.value = null;
  fechaInicio.value = null;
  fechaFin.value = null;
  selectedSexo.value = [];
  filtrosActivos.value =false;
  PaginaInformacion.value=1;
  await ObtenerPacientes();
  mostrarFiltros.value=false;
}

const GetUltimosPacientesCreados = ()=>{
    axios.get("/api/reportes/ultimos-pacientes-creados").then((response)=>{
        ListaPacientes.value = response.data;
    }).catch((error)=>{
        ListaPacientes.value = [];
    })
}
const GetUltimosPacientesModificados = ()=>{
    axios.get("/api/reportes/ultimos-pacientes-modificados").then((response)=>{
        ListaPacientes.value = response.data;
    }).catch((error)=>{
        ListaPacientes.value = [];
    })
}
const GetPacientes = async()=>{
    await limpiarFiltros();
}

const ObtenerPacientes = async() => {
    await axios.get("/api/reportes/pacientes-obtener",
        {params:
            {
                page: PaginaInformacion.value,
                busqueda: busqueda.value,
                consultorio: selectedConsultorio.value,
                sexo: selectedSexo.value,
                edadMin: edadMin.value,
                edadMax: edadMax.value,
                fechaInicio: fechaInicio.value,
                fechaFin: fechaFin.value,
            }
        }
    ).then((response)=>{
        ListaPacientes.value = response.data;
    });

    
};
const PeticionListaMedicos = StoreApp.debounce(ObtenerPacientes,350);
</script>
<template>
    <Head title="Reporte de pacientes"></Head>
    <App :breadcrumbs="breadcrumbs">
        <v-container fluid>
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">Reporte de pacientes</span>
                </v-col>
                <v-col cols="12">
                    <div class="poppins-light text-grey-darken-1">
                        En este reporte se muestran los pacientes registrados en los consultorios, incluyendo su nombre, consultorio asignado, edad y sexo.
                    </div>
                </v-col>
            </v-row>
            <v-row class="mt-5 mx-5 mb-2" no-gutters >
                <v-col class="d-flex align-center" cols="6" sm="6" md="4">
                    <v-text-field hide-details="" v-model="busqueda" @update:modelValue="PaginaInformacion = 1; PeticionListaMedicos()" variant="outlined" rounded="lg" prepend-inner-icon="mdi-magnify" density="compact" placeholder="Buscar por: Id, Paciente, Sexo, Consultorio"></v-text-field>
                </v-col>
                <v-col class="d-flex align-center justify-end justify-sm-end justify-md-end justify-lg-end justify-xl-end">
                    <v-btn @click="mostrarFiltrosForm" icon="mdi-filter-variant" color="primary" variant="text"></v-btn>
                </v-col>
            </v-row>
            <v-navigation-drawer v-model="mostrarFiltros" location="right" width="400" scrim="black"  temporary persistent disable-resize-watcher floating>
              <v-card flat class="pa-4" color="grey-lighten-4" style=" min-height: 100vh;">
                <v-row align="center" class="mb-2 mt-n2" no-gutters>
                  <v-col>
                    <span style="font-size: 1rem; font-weight: 600;">Filtros de búsqueda</span>
                  </v-col>
                  <v-col cols="auto">
                    <v-btn icon="mdi-close" variant="text" @click="mostrarFiltros = false"></v-btn>
                  </v-col>
                </v-row>
                <v-divider class="mb-4"></v-divider>
                <!-- Primera fila: edad, sexo, consultorio -->
                <v-row dense class="mb-2">
                  <v-col cols="12" sm="6" md="12" class="mb-3">
                    <v-text-field
                      v-model="edadMin"
                      label="Edad mínima"
                      type="number"
                      min="0"
                      clearable
                      bg-color="white"
                      variant="outlined"
                      density="compact"
                      prepend-inner-icon="mdi-numeric"
                      hide-details
                    />
                  </v-col>
                  <v-col cols="12" sm="6" md="12" class="mb-3">
                    <v-text-field
                      v-model="edadMax"
                      clearable
                      label="Edad máxima"
                      type="number"
                      :min="edadMin"
                      bg-color="white"
                      variant="outlined"
                      density="compact"
                      prepend-inner-icon="mdi-numeric"
                      hide-details
                    />
                  </v-col>
                  <v-col cols="12" sm="6" md="12" class="mb-3">
                    <v-select
                      v-model="selectedConsultorio"
                      :items="consultoriosOptions"
                      item-value="nombre"
                      item-title="nombre"
                      label="Consultorio"
                      variant="outlined"
                      density="compact"
                      multiple
                      bg-color="white"
                      chips
                      clearable
                      prepend-inner-icon="mdi-hospital"
                      hide-details
                    />
                  </v-col>
                  <v-col cols="12" sm="6" md="12" class="mb-3">
                    <v-select
                      v-model="selectedSexo"
                      :items="sexosOptions"
                      item-value="GeneroEtiqueta"
                      item-title="GeneroEtiqueta"
                      label="Sexo"
                      variant="outlined"
                      bg-color="white"
                      density="compact"
                      multiple
                      chips
                      clearable
                      prepend-inner-icon="mdi-gender-male-female"
                      hide-details
                    />
                  </v-col>
                </v-row>
                <!-- Segunda fila: rango de fechas y botones -->
                <v-row dense align="end" class="mt-n2">
                  <v-col cols="12" md="12" >
                    <span class="text-caption text-grey-darken-1 mg-5">Rango de fechas</span>
                    <v-date-input
                      v-model="fechaInicio"
                      label="Desde"
                      variant="outlined"
                      bg-color="white"
                      density="compact"
                      prepend-icon=""
                      prepend-inner-icon="mdi-calendar-start"
                      hide-details
                    ></v-date-input>
                  </v-col>
                  <v-col cols="12" md="12" class="mt-n3">
                    <span class="text-caption">&nbsp;</span>
                    <v-date-input
                      v-model="fechaFin"
                      label="Hasta"
                      variant="outlined"
                      prepend-icon=""
                      :min="fechaInicio"
                      bg-color="white"
                      density="compact"
                      prepend-inner-icon="mdi-calendar-end"
                      hide-details
                    ></v-date-input>
                  </v-col>
                  <v-col cols="12" md="12" class="d-flex align-end justify-end ga-2 mb-3">
                    <v-spacer />
                    <v-btn color="primary" variant="text" class="text-none" @click="limpiarFiltros" rounded="lg">
                        <v-icon class="mr-2" size="15">mdi-trash-can</v-icon>
                        Limpiar
                    </v-btn>
                    <v-btn color="primary" class="text-none" @click="aplicarFiltros" rounded="lg">
                        <v-icon class="mr-2" size="15">mdi-filter-check</v-icon>
                        Aplicar
                    </v-btn>
                  </v-col>
                </v-row>
              </v-card>
            </v-navigation-drawer>
            <v-row v-if="filtrosActivos && !mostrarFiltros" class="mx-5 mt-n1 mb-n3">
                <v-col cols="12">
                    <span class="text-caption text-grey-darken-1 mr-2">Filtros activos:</span>
                    <v-chip
                        color="primary"
                        text-color="white"
                        size="small"
                        class="ma-1"
                        v-if="edadMin || edadMax"
                    >
                        Edad: Desde {{ edadMin }} {{ edadMax ? ` a ${edadMax}`: null}} años
                    </v-chip>
                    <v-chip
                        color="primary"
                        text-color="white"
                        size="small"
                        class="ma-1"
                        v-if="selectedConsultorio.length > 0"
                    >
                        Consultorio: {{ selectedConsultorio.join(', ') }}
                    </v-chip>
                    <v-chip
                        color="primary"
                        text-color="white"
                        size="small"
                        class="ma-1"
                        v-if="selectedSexo.length > 0"
                    >
                        Sexo: {{ selectedSexo.join(', ') }}
                    </v-chip>
                    <v-chip
                        color="primary"
                        text-color="white"
                        size="small"
                        class="ma-1"
                        v-if="fechaInicio || fechaFin"
                    >
                        Desde: {{ moment(fechaInicio).format('YYYY-MM-DD')}} {{ fechaFin ? ` a ${moment(fechaFin).format('YYYY-MM-DD') }`: null}}
                    </v-chip>
                </v-col>
            </v-row>
            <v-row class="mx-5 mt-5 mb-n4" no-gutters v-if="StoreApp.LoaderPeticionenCurso">
                <v-col cols="12">
                    <v-progress-linear
                    color="primary"
                    indeterminate
                    ></v-progress-linear>
                </v-col>
            </v-row>
            <v-row class="mx-5 mt-5" no-gutters>
                
                <v-col cols="12" class="border-thin rounded-lg" v-if="ListaPacientes.data.length > 0">
                    <v-table >
                        <thead>
                            <tr>
                                <th class="text-left pa-3">Paciente</th>
                                <th class="text-left pa-3">Consultorio</th>
                                <th class="text-left pa-3">Edad</th>
                                <th class="text-left pa-3">Sexo</th>
                                <th class="text-center pa-3">Consultas</th>
                                <th class="text-center pa-3">
                                    Act. reciente
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(paciente, idx) in ListaPacientes.data" :key="paciente.pid.$oid" :class="[idx % 2 === 0 ? 'bg-grey-lighten-4' : '', 'hover-row']">
                                <td class="pa-0" style="min-width: 250px !important;max-width: 300px !important;">
                                    <v-container fluid class="pa-0 px-2">
                                        <v-row no-gutters class="d-flex align-center ">
                                            <v-col cols="12" sm="12" md="auto" lg="auto" xl="auto" class="d-flex pa-2 justify-center" v-if="!smAndDown">
                                                <v-avatar size="20" color="primary" class="mr-2 opacity-70" >
                                                    <!--<span class="white--text">{{ getInitials(paciente.paciente) }}</span>-->
                                                    <v-icon color="white" size="12">mdi-hospital-box</v-icon>
                                                </v-avatar>
                                            </v-col>
                                            <v-col>
                                                <v-row no-gutters>
                                                    <v-col cols="12" >
                                                        <span style="font-size: 0.800rem;"  class="poppins-semibold text-primary">{{ paciente.paciente }}</span>
                                                    </v-col>
                                                    <v-col cols="12" >
                                                        <span class="text-caption text-grey-darken-2">ID: {{ paciente.pid.$oid }}</span>
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </td>
                                <td>
                                
                                        
                                    <v-chip color="primary" text-color="white" size="small"><v-icon icon="mdi-stethoscope" size="14" class="mr-1"/>{{ paciente.consultorio }}</v-chip>
                                </td>
                                <td>
                                    <v-chip color="grey-darken-2" text-color="black" size="small"><span class="text-caption">{{ paciente.edad }}</span></v-chip>
                                </td>
                                <td>
                                    <v-chip
                                        color="primary"
                                        text-color="primary"
                                        size="small"
                                    >
                                        <v-icon :icon="getGeneroIcon(paciente.genero)" color="primary" size="15" class="mr-1"/>
                                        {{ paciente.genero }}
                                    </v-chip>
                                </td>
                                <td class="text-center">
                                   
                                    <Link :href="`/pacientes/consulta/${paciente.pid.$oid}`">
                                        <v-chip 
                                            color="primary" 
                                            text-color="white" 
                                            size="small"
                                            class="font-weight-bold elevation-2"
                                        >
                                            <v-icon icon="mdi-calendar-check" size="14" class="mr-1"/>
                                            {{ paciente.consultas }}
                                        </v-chip>
                                    </Link>
                                    
                                </td>
                                <td class="text-center text-no-wrap">
                                    <span style="font-size: 0.755rem;">
                                         {{paciente.act_reciente}}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="pa-0">
                                    <v-container fluid class="pa-0">
                                        <v-row no-gutters>
                                            <v-col class="d-flex justify-start align-center">
                                                <v-pagination v-model="PaginaInformacion" v-if="ListaPacientes.last_page > 1" @update:modelValue="ObtenerPacientes" elevation="0" variant="elevated" active-color="primary" rounded density="compact" :length="ListaPacientes.last_page" :total-visible="5"></v-pagination>
                                            </v-col>
                                            <v-col class="d-flex align-center justify-end text-primary poppins-light px-2">
                                                Mostrando {{ ListaPacientes.to }} de {{ ListaPacientes.total }} registros
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </td>
                            </tr>
                        </tfoot>
                    </v-table>
                </v-col>
                <v-col v-else class="mt-10">
                    <v-empty-state
                        icon="mdi-account-off"
                        text="No hay registros disponibles, o no se encontró ninguna coincidencia con los filtros aplicados o la búsqueda."
                        title="Sin pacientes encontrados"
                    >
                    </v-empty-state>
                </v-col>
            </v-row>                 
                                
            
        </v-container>
        
    </App>
</template>

<style scoped>
.hover-row:hover {
    background-color: #e3f2fd !important;
    transition: background 0.2s;
}
</style>
