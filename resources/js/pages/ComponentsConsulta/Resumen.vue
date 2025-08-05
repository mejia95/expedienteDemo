<script setup>
import App from '@/layouts/app/App.vue';
import { ref, onMounted } from "vue";
import { router, Head, usePage } from '@inertiajs/vue3';
import { AplicacionStore } from "@/stores/Aplicacion";
import { useGoTo } from "vuetify";
const goTo = useGoTo();
import moment from "moment";
import informacion from "@/pages/ComponentsConsulta/ResumenInformacionPaciente.vue";
import antecedentes from "@/pages/ComponentsConsulta/ResumenAntecedentes.vue";

const api = "/consulta";
const page = usePage();
const id_consulta = page.props.id_consulta;
const id_paciente = page.props.id_paciente;
const StoreAplicacion = AplicacionStore();
const loading_ske_exploracion = ref(false);
const loading_ske_laboratorio = ref(false);
const loading_ske_electrocardiograma= ref(false);
const loading_ske_radiografia = ref(false);
const loading_ske_otros_estudios = ref(false);
const loading_ske_diagnosticos = ref(false);
const loading_ske_tratamiento = ref(false);
const seleccion = ref(0);
const secciones = ref([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);
const consulta_datos_comunidad = ref([]);
const consulta_datos = ref([]);
const consulta_antecedentes = ref(null);
const consulta_estudios_laboratorio = ref([]);
const consulta_electrocardiograma = ref([]);
const consulta_radiografias = ref([]);
const consulta_otros_estudios = ref([]);
const consulta_tratamiento = ref([]);
const consulta_exploracion = ref([]);
const consulta_exploracion_piel = ref([]);
const consulta_exploracion_cabeza_cuello = ref([]);
const exploracion_oftalmologico = ref([]);
const exploracion_respiratorio = ref([]);
const exploracion_cardiovascular = ref([]);
const exploracion_abdomen = ref([]);
const exploracion_neurologico = ref([]);
const exploracion_ginecoobstetrico = ref([]);
const diagnostico_sindromaticos = ref([]);
const diagnostico_etiologicos = ref([]);
const diagnostico_diferenciales = ref([]);
const LoaderReceta = ref(false);
const id_consulta_modificada = ref(null);
const consulta_modificada = ref(0);
const modificacion_exploracion = ref(null);
const btn_consulta_exploracion = ref(false);
const btn_consulta_laboratorio = ref(false);
const btn_radiografias = ref(false);
const btn_diagnostico = ref(false);
const btn_otros_estudios = ref(false);
const btn_tratamiento = ref(false);
const btn_consulta_electrocardiograma = ref(false);
const consulta_modificada_estudios_laboratorio = ref([]);
const consulta_modificada_electrocardiograma = ref([]);
const consulta_modificada_radiografias = ref([]);
const consulta_modificada_otros_estudios = ref([]);
const consulta_modificada_tratamiento = ref([]);
const consulta_modificada_exploracion = ref([]);
const consulta_modificada_exploracion_piel = ref([]);
const consulta_modificada_exploracion_cabeza_cuello = ref([]);
const exploracion_modificada_oftalmologico = ref([]);
const exploracion_modificada_respiratorio = ref([]);
const exploracion_modificada_abdomen = ref([]);
const exploracion_modificada_cardiovascular = ref([]);
const exploracion_modificada_ginecoobstetrico = ref([]);
const exploracion_modificada_neurologico = ref([]);
const diagnostico_modificada_sindromaticos = ref([]);
const diagnostico_modificada_etiologicos = ref([]);
const diagnostico_modificada_diferenciales = ref([]);
const date_created = ref(null);
const date_updated = ref(null);
const mostrar_btn_consulta_exploracion = ref(false);
const mostrar_btn_consulta_laboratorio = ref(false);
const mostrar_btn_consulta_electrocardiograma = ref(false);
const mostrar_btn_radiografias = ref(false);
const mostrar_btn_otros_estudios = ref(false);
const mostrar_btn_diagnostico = ref(false);
const mostrar_btn_tratamiento = ref(false);
const btn_modificar = ref(1);
const sindromaticos = ref([]);
const etiologicos = ref([]);
const diferenciales = ref([]);
const consulta_original = ref(null);
const LoaderModificar = ref(false);

onMounted(async () => {
    await goTo(0, {});
    ConsultaResumen();
});

const props = defineProps({
    rol_usuario: {
        type: Number,
        default: null,
    },
});

const ConsultaResumen = () => {
    const url = `${api}/resumen/` + id_consulta;
    axios.get(url).then((response) => {
            btn_modificar.value = response.data.btn_modificar;
            var consultorio = response.data.consultorio;
            consulta_datos_comunidad.value = consultorio;
            date_created.value = response.data.created_at;
            consulta_datos.value = response.data.paciente;
            consulta_antecedentes.value = response.data.antecedentes;
            consulta_original.value = response.data.consulta_original;
            consulta_estudios_laboratorio.value =
                response.data.estudios_laboratorio;
            consulta_electrocardiograma.value =
                response.data.estudios_electrocardiograma;
            consulta_radiografias.value =
                response.data.estudios_radiografias;
            consulta_otros_estudios.value = response.data.otros_estudios;
            consulta_tratamiento.value = response.data.tratamiento;
            consulta_exploracion.value = response.data.exploracion;
            consulta_exploracion_piel.value = response.data.exploracion_piel;
            consulta_exploracion_cabeza_cuello.value =
                response.data.exploracion_cabeza_cuello;
            exploracion_oftalmologico.value =
                response.data.exploracion_oftalmologico;
            exploracion_respiratorio.value =
                response.data.exploracion_respiratorio;
            exploracion_cardiovascular.value =
                response.data.exploracion_cardiovascular;
            exploracion_abdomen.value = response.data.exploracion_abdomen;
            exploracion_neurologico.value =
                response.data.exploracion_neurologico;
            exploracion_ginecoobstetrico.value =
                response.data.exploracion_ginecoobstetrico;
            diagnostico_sindromaticos.value =
                response.data.diagnostico_sindromaticos;
            sindromaticos.value =
                response.data.sindromaticos;
            diagnostico_etiologicos.value =
                response.data.diagnostico_etiologicos;
            etiologicos.value =
                response.data.etiologicos;
            diagnostico_diferenciales.value =
                response.data.diagnostico_diferenciales;
            diferenciales.value =
                response.data.diferenciales;
            consulta_modificada.value = response.data.bandera_consulta_modificada;
            PermisoBtn();  
            if(consulta_original.value){
                ConsultaModificada(consulta_original.value.$oid);
            }
        })
        .catch((error) => {
            // console.log("error");
            // console.log(error);
            // return error.response.status == 419 ||
            //     error.response.status == 420 ||
            //     error.response.status == 401 ||
            //     error.response.status == 409
            //     ? location.reload()
            //     : router.push("/pacientes");
        });
};

const ConsultaModificada = (valor) => {
    //console.log("Este es el id modificado", valor);
    const url = `${api}/resumen/${valor}/modificada`;
    axios.get(url).then((response) => {
            modificacion_exploracion.value = response.data.modificacion_exploracion;            
            consulta_modificada_estudios_laboratorio.value =
                response.data.modificacion_estudios_laboratorio;
            consulta_modificada_electrocardiograma.value =
                response.data.modificacion_estudios_electrocardiograma;
            consulta_modificada_radiografias.value =
                response.data.modificacion_estudios_radiografias;
            consulta_modificada_otros_estudios.value = response.data.otros_estudios;
            date_updated.value = response.data.updated_at;            
            consulta_modificada_exploracion.value = response.data.modificacion_exploracion;
            consulta_modificada_exploracion_piel.value = response.data.modificacion_exploracion_piel;
            consulta_modificada_exploracion_cabeza_cuello.value =
                response.data.modificacion_exploracion_cabeza_cuello;
            exploracion_modificada_oftalmologico.value =
                response.data.modificacion_exploracion_oftalmologico;
            exploracion_modificada_respiratorio.value =
                response.data.modificacion_exploracion_respiratorio;
            exploracion_modificada_cardiovascular.value =
                response.data.modificacion_exploracion_cardiovascular;
            exploracion_modificada_abdomen.value = response.data.modificacion_exploracion_abdomen;
            exploracion_modificada_neurologico.value =
                response.data.modificacion_exploracion_neurologico;
            exploracion_modificada_ginecoobstetrico.value =
                response.data.modificacion_exploracion_ginecoobstetrico;
            diagnostico_modificada_sindromaticos.value =
                response.data.modificacion_diagnostico_sindromaticos;
            diagnostico_modificada_etiologicos.value =
                response.data.modificacion_diagnostico_etiologicos;
            diagnostico_modificada_diferenciales.value =
                response.data.modificacion_diagnostico_diferenciales;           
            consulta_modificada_tratamiento.value = response.data.modificacion_tratamiento;
                     
        })
        .catch((error) => {          
            console.log("Este es el error", error)
            return error.response.status == 419 ||
                error.response.status == 420 ||
                error.response.status == 401 ||
                error.response.status == 409
                ? location.reload()
                : router.push("/pacientes");
        });
}

const Importar_PDF = async () => {
    var url = `/consulta/importarpdf/` + id_consulta;
    LoaderReceta.value = true;
    await axios({ url: url, method: "POST", responseType: "blob" })
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement("a");
            link.href = url;
            console.log(response.data);
            var date = new Date();
            var day = date.getDate().toString().padStart(2,'0');
            var month = (date.getMonth() + 1).toString().padStart(2,'0');
            var year = date.getFullYear().toString().padStart(2,'0');
            var hour = date.getHours().toString().padStart(2,'0');
            var minutes = date.getMinutes().toString().padStart(2,'0');
            var seconds = date.getSeconds().toString().padStart(2,'0');
            var formatearFecha = `${year}_${month}_${day}`;
            var formatearHora = `${hour}_${minutes}_${seconds}`;
            var name = `Receta_${id_consulta}_${formatearFecha}_${formatearHora}.pdf`;
            link.setAttribute("download", name);
            link.setAttribute("target", "_blank");
            document.body.appendChild(link);
            link.click();
            LoaderReceta.value = false;
        })
        .catch((error) => {
            console.log(error);
            LoaderReceta.value = false;
            return error.response.status == 419 ||
                error.response.status == 420 ||
                error.response.status == 401 ||
                error.response.status == 409
                ? this.isAuth()
                : console.log(error);
        });
    }

    const ModificarConsulta = () => {
        LoaderModificar.value = true;
        const url = `${api}/modificar/resumen/${id_consulta}/consulta`;
        axios.get(url).then((response)=>{
            id_consulta_modificada.value = response.data.consulta_nueva;
            const id_consulta = id_consulta_modificada.value._id;
            if(response.data.estatus == 1){            
                router.push(`/consulta/${id_consulta}`);
            }
            LoaderModificar.value = false;
        }).catch((error)=>{
            LoaderModificar.value = false;
            console.log("error");
            console.log(error);
            return error.response.status==419 || error.response.status==420 || error.response.status==401 || error.response.status==409 ? location.reload(): router.push("/pacientes");
        });
    }

    const PermisoBtn = () =>{
        if (consulta_exploracion) {
            const seccionesExploracion = [
                consulta_exploracion.value,
                consulta_exploracion_piel.value,
                consulta_exploracion_cabeza_cuello.value,
                exploracion_oftalmologico.value,
                exploracion_respiratorio.value,
                exploracion_cardiovascular.value,
                exploracion_abdomen.value,
                exploracion_neurologico.value,
                exploracion_ginecoobstetrico.value,
            ];

            const hayCopia = seccionesExploracion.some(seccion => seccion?.bandera_copia);
            if (hayCopia) {
                mostrar_btn_consulta_exploracion.value = true;
            }
        }

        // Mapa de botones y sus respectivas variables
        const secciones = [
            { data: consulta_estudios_laboratorio, btn: mostrar_btn_consulta_laboratorio },
            { data: consulta_electrocardiograma, btn: mostrar_btn_consulta_electrocardiograma },
            { data: consulta_radiografias, btn: mostrar_btn_radiografias },
            { data: consulta_otros_estudios, btn: mostrar_btn_otros_estudios },
            { data: consulta_tratamiento, btn: mostrar_btn_tratamiento },
        ];

        // Recorre y activa los botones si tienen bandera_copia
        secciones.forEach(({ data, btn }) => {
            if (data?.value?.bandera_copia) {
                data.value.bandera_copia = 1;
                btn.value = true;
            }
        });

        // Diagnóstico (combinado)
        const diagnosticos = [sindromaticos, diferenciales, etiologicos];
        if (diagnosticos.some(d => d?.value?.bandera_copia)) {
            console.log("Este es el if de los diagnostivos");
            mostrar_btn_diagnostico.value = true;
        }
    }

    async function startLoading (valor) {   
        
        const loaders = {
        1: loading_ske_exploracion,
        2: loading_ske_laboratorio,
        3: loading_ske_electrocardiograma,
        4: loading_ske_radiografia,
        5: loading_ske_otros_estudios,
        6: loading_ske_diagnosticos,
        7: loading_ske_tratamiento,
    };

    const loader = loaders[valor];
    if (loader) {
        loader.value = true;
        setTimeout(() => {
            loader.value = false;
        }, 100);
    }
        
    }

    // const breadcrumbs = [
    //     { title: 'Inicio', href: '/' },
    //     { title: 'Mis pacientes', href: 'pacientes/mis-pacientes' },
    //     { title: 'Consultas', href: 'pacientes/mis-pacientes/historial' },
    //     { title: 'Resumen consultas', href: 'pacientes/mis-pacientes/historial' },
    // ];
    const breadcrumbs = [
        { title: 'Inicio', href: '/' },
        { title: 'Pacientes', href: StoreAplicacion.currentUrl },
        { title: 'Historial de consultas', href:`/pacientes/consulta/${id_paciente}` },
        { title: 'Resumen de la consulta',  href: StoreAplicacion.currentUrl },
    ];	
</script>
<template>
    <App :breadcrumbs="breadcrumbs">
        <v-container fluid>
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">Resumen de la consulta</span>
                </v-col>
            </v-row> 
         <v-row class="d-flex align-start mx-2 " no-gutters>
            <v-col cols="6" class="text-primary pa-0 py-2">
                <span style="font-size: 0.775rem">Consulta #{{ id_consulta }}</span>
            </v-col>
            <v-col cols="6" class="d-flex justify-end">
                <v-btn class="mr-5" :loading="LoaderModificar" :disabled="LoaderReceta" color="primary"  > 
                   <v-icon icon="mdi-printer" class="mr-2"></v-icon> Imprimir receta
                </v-btn> 
                <v-btn :loading="LoaderReceta" :disabled="LoaderModificar" @click="Importar_PDF()" color="primary">
                    <v-icon icon="mdi-download" class="mr-2"></v-icon>Guardar receta
                </v-btn>
            </v-col>
        </v-row> 

        <v-row class="d-flex align-start ma-4 mt-n2">
            <v-col>
                <v-expansion-panels v-model="secciones" class="rounded-xl" variant="accordion" elevation="1" multiple>
                    <v-expansion-panel :value="0" rounded="lg" >
                        <v-expansion-panel-title >
                            <v-icon size="15" color="primary">mdi-account</v-icon>
                            <span class="mx-1 poppins-semibold text-primary" style="font-size: 1.075rem">
                                Información de consulta
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            <informacion :consulta_datos="consulta_datos" :consulta_datos_comunidad="consulta_datos_comunidad"></informacion>
                            
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel :value="1">
                        <v-expansion-panel-title>
                            <v-icon color="primary" size="15">mdi-file-document-edit</v-icon>
                            <span class="mx-1 poppins-semibold text-primary" style="font-size: 1.075rem">
                                Antecedentes y enfermedades
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text v-if="consulta_antecedentes">
                            <antecedentes :consulta_antecedentes="consulta_antecedentes"></antecedentes>
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel  :value="3">
                        <v-expansion-panel-title>
                            <v-icon color="primary" size="15">mdi-stethoscope</v-icon>
                            <span class="mx-1 poppins-semibold text-primary" style="font-size: 1.075rem">
                                Consulta y exploración 
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text >
                            <v-row v-if="!loading_ske_exploracion && consulta_exploracion && consulta_modificada_exploracion">
                                <v-col >                                    
                                    <span :class="!btn_consulta_exploracion ? 'poppins-bold' : 'poppins-medium'"> {{ !consulta_original ? 'Fecha de creación: '+date_created : consulta_original && !mostrar_btn_consulta_exploracion ? 'Fecha de creación: '+date_updated :!btn_consulta_exploracion && consulta_original ?  'Ultima actualización: '+date_created : 'Fecha de creación: '+date_updated}}</span>
                                </v-col>
                                <v-col class="d-flex justify-end" v-if="mostrar_btn_consulta_exploracion">
                                    <v-btn variant="text" color="black" @click="startLoading(1);btn_consulta_exploracion == false ? btn_consulta_exploracion = true : btn_consulta_exploracion = false">
                                      <v-icon size="15">{{!btn_consulta_exploracion ? 'mdi-backup-restore' : 'mdi-update'}}</v-icon> <span class="mx-2 text-none"> {{ !btn_consulta_exploracion ? 'Ver datos anteriores' : 'Ver cambios' }} </span>
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-divider class="mt-2"></v-divider>
                            <v-row>
                                <v-col class="text-primary"> Examen físico </v-col>
                            </v-row>
                            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////// -->
                             <v-row v-if="loading_ske_exploracion">
                                <v-col>
                                    <v-skeleton-loader
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row>
                                <v-col v-if="consulta_exploracion.temperatura && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Temperatura:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_consulta_exploracion ? consulta_exploracion.temperatura : consulta_modificada_exploracion.temperatura}} °
                                    </span>
                                    
                                </v-col>
                                <v-col v-if="consulta_exploracion.peso && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Peso:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.peso.$numberDecimal : consulta_modificada_exploracion.peso.$numberDecimal
                                        }}
                                        {{ !btn_consulta_exploracion ? consulta_exploracion.peso_unidad : consulta_modificada_exploracion.peso_unidad  }}
                                    </span>
                                </v-col>
                                <v-col v-if="consulta_exploracion.altura  && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Altura:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.altura.$numberDecimal : consulta_modificada_exploracion.altura.$numberDecimal
                                        }}
                                        {{  !btn_consulta_exploracion ? consulta_exploracion.altura_unidad : consulta_modificada_exploracion.altura_unidad}}
                                    </span>
                                </v-col>
                                
                            </v-row>
                            <v-row>
                                <v-col v-if="consulta_exploracion.imc && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Índice de masa corporal:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_consulta_exploracion ? consulta_exploracion.imc : consulta_modificada_exploracion.imc}}
                                        {{ !btn_consulta_exploracion ? consulta_exploracion.imc_unidad : consulta_modificada_exploracion.imc_unidad }}
                                    </span>
                                </v-col>
                                <v-col v-if="consulta_exploracion.glucosa_sanguinea && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Glucosa sanguínea:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.glucosa_sanguinea : consulta_modificada_exploracion.glucosa_sanguinea
                                        }}
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.glucosa_unidad : consulta_modificada_exploracion.glucosa_unidad
                                        }}
                                    </span>
                                </v-col>
                                <v-col v-if="consulta_exploracion.satuoxigeno && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Saturación de oxígeno:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.satuoxigeno :  consulta_modificada_exploracion.satuoxigeno
                                        }}</span>
                                </v-col>
                                <v-col v-if="consulta_exploracion.tension_sistolica && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Tensión arterial:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.tension_sistsatuoxigenoolica : consulta_modificada_exploracion.tension_sistsatuoxigenoolica
                                        }}
                                        /
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.tension_diastolica : consulta_modificada_exploracion.tension_diastolica
                                        }}</span>
                                </v-col>

                                <!-- //////////////////////////// -->
                               
                            </v-row>
                            <v-row>
                                <v-col v-if="consulta_exploracion.frecuencia_cardiaca && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Frecuencia cardiaca(lpm):
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.frecuencia_cardiaca : consulta_modificada_exploracion.frecuencia_cardiaca
                                        }}
                                    </span>
                                </v-col>
                                <v-col v-if="consulta_exploracion.frecuencia_respiratoria && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Frecuencia
                                        respiratoria(rpm):
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.frecuencia_respiratoria :  consulta_modificada_exploracion.frecuencia_respiratoria
                                        }}
                                    </span>
                                </v-col>

                               
                            </v-row>


                            <v-row>
                                <v-col class="text-primary"> Consulta </v-col>
                            </v-row>
                            <v-row v-if="loading_ske_exploracion">
                                <v-col>
                                    <v-skeleton-loader
                                        
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row v-if="consulta_exploracion.motivo_consulta && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Motivo de consulta:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion.motivo_consulta : consulta_modificada_exploracion.motivo_consulta
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                        
                            <v-row>
                                <v-col class="text-primary"> Exploración </v-col>
                            </v-row>
                            <v-row v-if="loading_ske_exploracion">
                                <v-col>
                                    <v-skeleton-loader
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Piel, faneras y tejido
                                        celular
                                        subcutáneo</span>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-4 mb-4"></v-divider>
                            <v-row class="ml-8" v-if="consulta_exploracion_piel.aspecto && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Aspecto general:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_consulta_exploracion ? consulta_exploracion_piel.aspecto : consulta_modificada_exploracion_piel.aspecto}}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="consulta_exploracion_piel.distribucion_pilosa && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Distribución de pelo /
                                        Implantación:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                           !btn_consulta_exploracion ? consulta_exploracion_piel.distribucion_pilosa : consulta_modificada_exploracion_piel.distribucion_pilosa
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="consulta_exploracion_piel.lesiones && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Lesiones:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? consulta_exploracion_piel.lesiones : consulta_modificada_exploracion_piel.lesiones
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="consulta_exploracion_piel.topografia && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Topografía:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_piel.topografia : consulta_modificada_exploracion_piel.topografia
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="consulta_exploracion_piel.sintomas && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Síntomas:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_piel.sintomas :  consulta_modificada_exploracion_piel.sintomas
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="consulta_exploracion_piel.mucosas && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Mucosas:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_piel.mucosas : consulta_modificada_exploracion_piel.mucosas
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="consulta_exploracion_piel.unas && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Uñas:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_piel.unas : consulta_modificada_exploracion_piel.unas
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion.tejido_celular_subcutaneo && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Tejido celular
                                        subcutáneo:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion.tejido_celular_subcutaneo :  consulta_exploracion.tejido_celular_subcutaneo
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion_piel.descripcion_piel && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Descripción de la piel:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_piel.descripcion_piel : consulta_modificada_exploracion_piel.descripcion_piel
                                        }}</span>
                                </v-col>
                            </v-row>
                            <!-- /////////////////////////////////////////////////////// -->
                           
                            <!-- ////////////////////////////////////////////////////////// -->
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Cabeza</span>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-4 mb-4"></v-divider>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion_cabeza_cuello.CraneoCara && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Cráneo y cara:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_cabeza_cuello.CraneoCara : consulta_modificada_exploracion_cabeza_cuello.CraneoCara
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion_cabeza_cuello.CueroCabelludo && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Cuero cabelludo:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_cabeza_cuello.CueroCabelludo : consulta_modificada_exploracion_cabeza_cuello.CueroCabelludo
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion_cabeza_cuello.RegionOrbitoNasal && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Región orbito nasal:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_cabeza_cuello.RegionOrbitoNasal : consulta_modificada_exploracion_cabeza_cuello.RegionOrbitoNasal
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion_cabeza_cuello.RegionOrofaringea && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Región orofaríngea:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_cabeza_cuello.RegionOrofaringea : consulta_modificada_exploracion_cabeza_cuello.RegionOrofaringea
                                        }}</span>
                                </v-col>
                            </v-row>
                            <!-- //////////////////////////////////////////////////////////////////////////// -->

                           

                            <!-- //////////////////////////////////////////////////////////////////////////// -->
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Cuello</span>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-4 mb-4"></v-divider>
                            <v-row class="ml-8" v-if="
                                 consulta_exploracion_cabeza_cuello.InspeccionCuello && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Inspección:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_cabeza_cuello.InspeccionCuello : consulta_modificada_exploracion_cabeza_cuello.InspeccionCuello
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion_cabeza_cuello.PalpacionCuello && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Palpación:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_cabeza_cuello.PalpacionCuello : consulta_modificada_exploracion_cabeza_cuello.PalpacionCuello
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion_cabeza_cuello.PercusionCuello && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Percusión:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_cabeza_cuello.PercusionCuello : consulta_modificada_exploracion_cabeza_cuello.PercusionCuello
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                consulta_exploracion_cabeza_cuello.AuscultacionCuello && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Auscultación:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? consulta_exploracion_cabeza_cuello.AuscultacionCuello : consulta_modificada_exploracion_cabeza_cuello.AuscultacionCuello
                                        }}</span>
                                </v-col>
                            </v-row>

                            <!-- //////////////////////////////////////////////////////////////////////////// -->

                            

                            <!-- //////////////////////////////////////////////////////////////////////////// -->
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Oftalmológico</span>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-4 mb-4"></v-divider>
                            <v-row class="ml-8">
                                <v-col v-if="
                                    exploracion_oftalmologico.AgudezaOjoDerecho && !loading_ske_exploracion
                                ">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Agudeza visual ojo
                                        derecho:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_oftalmologico.AgudezaOjoDerecho : exploracion_modificada_oftalmologico.AgudezaOjoDerecho
                                        }}</span>
                                </v-col>
                                <v-col v-if="
                                    exploracion_oftalmologico.AgudezaOjoIzquierdo && !loading_ske_exploracion
                                ">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Agudeza visual ojo
                                        izquierdo:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_oftalmologico.AgudezaOjoIzquierdo : exploracion_modificada_oftalmologico.AgudezaOjoIzquierdo
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8">
                                <v-col v-if="
                                    exploracion_oftalmologico.RefraccionOjoDerecho && !loading_ske_exploracion
                                ">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Refracción ojo derecho:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_oftalmologico.RefraccionOjoDerecho : exploracion_modificada_oftalmologico.RefraccionOjoDerecho
                                        }}</span>
                                </v-col>
                                <v-col v-if="
                                    exploracion_oftalmologico.RefraccionOjoIzquierdo && !loading_ske_exploracion
                                ">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Refracción ojo izquierdo:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_exploracion ? exploracion_oftalmologico.RefraccionOjoIzquierdo : exploracion_modificada_oftalmologico.RefraccionOjoIzquierdo
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8">
                                <v-col v-if="
                                    exploracion_oftalmologico.PresionOcularOjoDerecho && !loading_ske_exploracion
                                ">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Presión ocular ojo
                                        derecho:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_oftalmologico.PresionOcularOjoDerecho : exploracion_modificada_oftalmologico.PresionOcularOjoDerecho
                                        }}</span>
                                </v-col>
                                <v-col v-if="
                                    exploracion_oftalmologico.PresionOcularOjoIzquierdo && !loading_ske_exploracion
                                ">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Presión ocular ojo
                                        izquierdo:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_oftalmologico.PresionOcularOjoIzquierdo : exploracion_modificada_oftalmologico.PresionOcularOjoIzquierdo
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8">
                                <v-col v-if="
                                    exploracion_oftalmologico.ExploracionSegmentoAnterior && !loading_ske_exploracion
                                ">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Exploración segmento
                                        anterior:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_oftalmologico.ExploracionSegmentoAnterior: exploracion_modificada_oftalmologico.ExploracionSegmentoAnterior 
                                        }}</span>
                                </v-col>
                                <v-col v-if="
                                    exploracion_oftalmologico.ExploracionRetinayVitreo && !loading_ske_exploracion
                                ">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Exploración retina y
                                        vítreo:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_oftalmologico.ExploracionRetinayVitreo : exploracion_modificada_oftalmologico.ExploracionRetinayVitreo
                                        }}</span>
                                </v-col>
                            </v-row>

                            <!-- ///////////////////////////////////////////////////////////////////////////////////////// -->
                        
                            <!-- ////////////////////////////////////////////////////////////////////////////////// -->
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Respiratorio</span>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-4 mb-4"></v-divider>
                            <v-row class="ml-8" v-if="
                                exploracion_respiratorio.SaturacionOxigeno && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Saturación de oxígeno:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_respiratorio.SaturacionOxigeno : exploracion_modificada_respiratorio.SaturacionOxigeno
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                exploracion_respiratorio.InspeccionRespiratorio && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Inspección:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_respiratorio.InspeccionRespiratorio : exploracion_modificada_respiratorio.InspeccionRespiratorio
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                exploracion_respiratorio.PalpacionRespiratorio && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Palpación:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_respiratorio.PalpacionRespiratorio : exploracion_modificada_respiratorio.PalpacionRespiratorio
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                exploracion_respiratorio.PercusionRespiratorio && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Percusión:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_respiratorio.PercusionRespiratorio : exploracion_modificada_respiratorio.PercusionRespiratorio
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                exploracion_respiratorio.AuscultacionRespiratorio && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Auscultación:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_respiratorio.AuscultacionRespiratorio : exploracion_modificada_respiratorio.AuscultacionRespiratorio
                                        }}</span>
                                </v-col>
                            </v-row>

                            <!-- ////////////////////////////////////////////////// -->

                          
                             <!-- ////////////////////////////////////////////////// -->
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Cardiovascular</span>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-4 mb-4"></v-divider>
                            <v-row class="ml-8" v-if="
                                exploracion_cardiovascular.descripcion_cardiovascular && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Describir ruidos
                                        cardiacos, campos
                                        pulmonares limpios, soplos, frémito,
                                        etc. :
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_cardiovascular.descripcion_cardiovascular : exploracion_modificada_cardiovascular.descripcion_cardiovascular
                                        }}</span>
                                </v-col>
                            </v-row>
                            <!-- /////////////////////////////////////////////////// -->
                           
                             <!-- ////////////////////////////////////////////////////// -->
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Adbomen</span>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-4 mb-4"></v-divider>
                            <v-row class="ml-8" v-if="exploracion_abdomen.InspeccionAbdomen && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Inspección:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_abdomen.InspeccionAbdomen : exploracion_modificada_abdomen.InspeccionAbdomen
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_abdomen.PalpacionAbdomen && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Palpación:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_abdomen.PalpacionAbdomen : exploracion_modificada_abdomen.PalpacionAbdomen
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_abdomen.PercusionAbdomen && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Percusión:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_abdomen.PercusionAbdomen : exploracion_modificada_abdomen.PercusionAbdomen
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_abdomen.AuscultacionAbdomen && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Auscultación:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_abdomen.AuscultacionAbdomen : exploracion_modificada_abdomen.AuscultacionAbdomen
                                        }}</span>
                                </v-col>
                            </v-row>

                            <!-- ////////////////////////////////////////////////////////// -->
                           
                             <!-- /////////////////////////////////////////////////// -->
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Neurológico</span>
                                </v-col>
                            </v-row>
                            <v-divider class="mx-4 mb-4"></v-divider>
                            <v-row class="ml-8" v-if="exploracion_neurologico.Total && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Glasgow
                                    </span>
                                    <span class="text-medium-emphasis"></span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8">
                                <v-col v-if="exploracion_neurologico.Ocular && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Ocular:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.Ocular : exploracion_modificada_neurologico.Ocular
                                        }}</span>
                                </v-col>
                                <v-col v-if="exploracion_neurologico.Verbal && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Verbal:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.Verbal : exploracion_modificada_neurologico.Verbal
                                        }}</span>
                                </v-col>
                                <v-col v-if="exploracion_neurologico.Motora && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Motora:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.Motora : exploracion_modificada_neurologico.Motora
                                        }}</span>
                                </v-col>
                                <v-col v-if="exploracion_neurologico.Total && !loading_ske_exploracion">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Total:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.Total : exploracion_modificada_neurologico.Total
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_neurologico.EstadoAlerta && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Estado de alerta:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.EstadoAlerta : exploracion_modificada_neurologico.EstadoAlerta
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="
                                exploracion_neurologico.FuncionesMentalesSuperiores && !loading_ske_exploracion
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Funciones mentales
                                        superiores:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.FuncionesMentalesSuperiores : exploracion_modificada_neurologico.FuncionesMentalesSuperiores
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_neurologico.ParesCranales && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Pares craneales:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.ParesCranales : exploracion_modificada_neurologico.ParesCranales
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_neurologico.EsferaMotora && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Esfera motora:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.EsferaMotora : exploracion_modificada_neurologico.EsferaMotora
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_neurologico.EsferaSensitiva && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Esfera sensitiva:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.EsferaSensitiva : exploracion_modificada_neurologico.EsferaSensitiva
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_neurologico.Reflejos && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Reflejos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.Reflejos : exploracion_modificada_neurologico.Reflejos
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_neurologico.PruebasEspeciales && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Pruebas especiales:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_neurologico.PruebasEspeciales :exploracion_modificada_neurologico.PruebasEspeciales
                                        }}</span>
                                </v-col>
                            </v-row>
                            <!-- ////////////////////////////////////////////////////////////////////////////// -->
                          
                             <!-- ///////////////////////////////////////////////////////////////////////////// -->
                            <v-row class="d-flex align-center" v-if=!loading_ske_exploracion>
                                <v-col class="seccion-formulario poppins-regular text-grey-darken-2">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Gineco-obstétrico</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_ginecoobstetrico.Mamas && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Mamas:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_ginecoobstetrico.Mamas : exploracion_modificada_ginecoobstetrico.Mamas
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_ginecoobstetrico.Abdomen && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Abdomen:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ?  exploracion_ginecoobstetrico.Abdomen : exploracion_modificada_ginecoobstetrico.Abdomen
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row class="ml-8" v-if="exploracion_ginecoobstetrico.Pelvis && !loading_ske_exploracion">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Pelvis:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        !btn_consulta_exploracion ? exploracion_ginecoobstetrico.Pelvis : exploracion_modificada_ginecoobstetrico.Pelvis
                                        }}</span>
                                </v-col>
                            </v-row>

                            <!-- //////////////////////////////////////////////////////// -->
                          
                             <!-- //////////////////////////////////////////////////// -->
                            <v-divider class="mx-4 mb-4"></v-divider>
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel  :value="5">
                        <v-expansion-panel-title>
                            <v-icon color="primary" size="15">mdi-test-tube</v-icon>
                            <span class="mx-1 poppins-semibold text-primary" style="font-size: 1.075rem">
                                Examen de laboratorio 
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text v-if="consulta_estudios_laboratorio.length != 0 || consulta_modificada_estudios_laboratorio.length != 0">
                            <v-row v-if="loading_ske_laboratorio ">
                                <v-col>
                                    <v-skeleton-loader
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row v-if="!loading_ske_laboratorio && (consulta_estudios_laboratorio || consulta_modificada_estudios_laboratorio)">
                                <v-col>          
                                    <span :class="!btn_consulta_laboratorio ? 'poppins-bold' : 'poppins-medium'"> {{ !consulta_original ? 'Fecha de creación: '+date_created : consulta_original && !mostrar_btn_consulta_laboratorio ? 'Fecha de creación: '+date_updated : !btn_consulta_laboratorio && consulta_original ?  'Ultima actualización: '+date_created : 'Fecha de creación: '+date_updated}}</span>                                                              
                                </v-col>
                                <v-col class="d-flex justify-end" v-if="mostrar_btn_consulta_laboratorio">
                                    <v-btn variant="text" color="black" @click="startLoading(2);btn_consulta_laboratorio == false ? btn_consulta_laboratorio = true : btn_consulta_laboratorio = false">
                                      <v-icon size="15">{{!btn_consulta_laboratorio ? 'mdi-backup-restore' : 'mdi-update'}}</v-icon> <span class="mx-2 text-none"> {{ !btn_consulta_laboratorio ? 'Ver datos anteriores' : 'Ver cambios' }} </span>
                                    </v-btn>
                                    <!--<v-btn :color="!btn_consulta_laboratorio ? 'primary' : 'red'" @click="startLoading(2);btn_consulta_laboratorio == false ? btn_consulta_laboratorio = true : btn_consulta_laboratorio = false">
                                        {{ !btn_consulta_laboratorio ? 'Resgitro anterior' : 'Registro actualizado' }}
                                    </v-btn>  -->
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.hematocrito && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Hematocrito (Hto)
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.hematocrito : consulta_modificada_estudios_laboratorio.hematocrito
                                        }}
                                        %
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">45%</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.leucocitos && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Leucocitos
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.leucocitos : consulta_modificada_estudios_laboratorio.leucocitos
                                        }}
                                        células/µL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">6500 células/µL</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.linfocitos && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Linfocitos
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.linfocitos : consulta_modificada_estudios_laboratorio.linfocitos
                                        }}
                                        %
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">30%</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.monocitos && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Monocitos
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.monocitos : consulta_modificada_estudios_laboratorio.monocitos
                                        }}
                                        %
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">5%</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_estudios_laboratorio.volumen_corpuscular && !loading_ske_laboratorio
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Volumen corpuscular medio
                                        (VCM)
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.volumen_corpuscular : consulta_modificada_estudios_laboratorio.volumen_corpuscular
                                        }}
                                        femtolitros
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">90.0 femtolitros</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.plaquetas && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Plaquetas</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.plaquetas : consulta_modificada_estudios_laboratorio.plaquetas
                                        }}
                                        células/µL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">250000 células/µL</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.glucemia && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Glucemia</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.glucemia : consulta_modificada_estudios_laboratorio.glucemia
                                        }}
                                        mg/dL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">90.0 mg/dL</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.urea && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Urea</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.urea : consulta_modificada_estudios_laboratorio.urea
                                        }}
                                        mg/dL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">30.0 mg/dL</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.creatinina && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Creatinina</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.creatinina : consulta_modificada_estudios_laboratorio.creatinina
                                        }}
                                        mg/dL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">1 mg/dL</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.sodio && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Sodio
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.sodio : consulta_modificada_estudios_laboratorio.sodio
                                        }}
                                        mEq/L
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">140.0 mEq/L</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.potasio && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Potasio</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.potasio : consulta_modificada_estudios_laboratorio.potasio
                                        }}
                                        mEq/L
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">4.5 mEq/L</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.cloro && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Cloro
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.cloro : consulta_modificada_estudios_laboratorio.cloro
                                        }}
                                        mEq/L
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">100.0 mEq/L</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.got && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">GOT (AST)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.got : consulta_modificada_estudios_laboratorio.got
                                        }}
                                        U/L
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">30 U/L</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.gpt && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">GPT (ALT)
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.gpt : consulta_modificada_estudios_laboratorio.gpt
                                        }}
                                        U/L
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">28 U/L</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.fosfatasa && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Fosfatasa alcalina
                                        (FAL)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.fosfatasa : consulta_modificada_estudios_laboratorio.fosfatasa
                                        }}
                                        U/L
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">100 U/L</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.bilirrubina && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Bilirrubina total</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.bilirrubina : consulta_modificada_estudios_laboratorio.bilirrubina
                                        }}
                                        mg/dL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">0.8 mg/dL</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_estudios_laboratorio.coagulograma && !loading_ske_laboratorio
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Coagulograma (tiempos de
                                        protrombina,
                                        TTP)
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.coagulograma : consulta_modificada_estudios_laboratorio.coagulograma
                                        }}
                                        segundos
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">
                                        12.0 segundos</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.ph && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">pH
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_consulta_laboratorio ? consulta_estudios_laboratorio.ph : consulta_modificada_estudios_laboratorio.ph }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">7.40</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.co2 && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">CO2 (Dióxido de
                                        Carbono)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.co2 : consulta_modificada_estudios_laboratorio.co2
                                        }}
                                        mmHg
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">40.0 mmHg</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.hco3 && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">HCO3 (Bicarbonato)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.hco3 : consulta_modificada_estudios_laboratorio.hco3
                                        }}
                                        mEq/L
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">24.0 mEq/L</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.po2 && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">PO2 (Presión parcial de
                                        oxígeno)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.po2 : consulta_modificada_estudios_laboratorio.po2
                                        }}
                                        mmHg
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">95.0 mmHg</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_estudios_laboratorio.saturacion_oxigeno && !loading_ske_laboratorio
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Saturación de oxígeno
                                        (Sat)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.saturacion_oxigeno : consulta_modificada_estudios_laboratorio.saturacion_oxigeno
                                        }}
                                        %
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">98.0 %</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.anion && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Anion GAP (GAP)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.anion : consulta_modificada_estudios_laboratorio.anion
                                        }}
                                        mEq/L
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">12.0 mEq/L</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.orina && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Orina</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.orina : consulta_modificada_estudios_laboratorio.orina
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem"></span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.glucosa && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Glucosa o
                                        proteínas</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.glucosa : consulta_modificada_estudios_laboratorio.glucosa
                                        }}
                                        mg/dL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem"></span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.hemocultivo && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Hemocultivo</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.hemocultivo : consulta_modificada_estudios_laboratorio.hemocultivo
                                        }}
                                        mg/dL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">0 (Negativo)</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_estudios_laboratorio.urocultivo && !loading_ske_laboratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Urocultivo</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.urocultivo : consulta_modificada_estudios_laboratorio.urocultivo
                                        }}
                                        UFC/mL
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">40 (Negativo)</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_estudios_laboratorio.descripcion_urocultivo && !loading_ske_laboratorio
                            ">
                                <v-col cols="12" sm="4" lg="4" md="4" xl="4">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Descripción urocultivo
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_laboratorio ? consulta_estudios_laboratorio.descripcion_urocultivo : consulta_modificada_estudios_laboratorio.descripcion_urocultivo
                                        }}
                                    </span>
                                </v-col>
                            </v-row>

                            <!-- ////////////////////////////////////////////// -->
                            
                             <!-- /////////////////////////////////////////////////// -->
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel  :value="6">
                        <v-expansion-panel-title >
                            <v-icon color="primary" size="15">mdi-test-tube</v-icon>
                            <span class="mx-1 poppins-semibold text-primary" style="font-size: 1.075rem">
                                Electrocardiograma 
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text  v-if="consulta_electrocardiograma.length != 0 || consulta_modificada_electrocardiograma.length != 0">
                            <v-row v-if="loading_ske_electrocardiograma">
                                <v-col>
                                    <v-skeleton-loader
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row v-if="!loading_ske_electrocardiograma">
                                <v-col>                                    
                                    <span :class="!btn_consulta_electrocardiograma ? 'poppins-bold' : 'poppins-medium'"> {{ !consulta_original ? 'Fecha de creación: '+date_created : consulta_original && !mostrar_btn_consulta_electrocardiograma ? 'Fecha de creación: '+date_updated : !btn_consulta_electrocardiograma && consulta_original ?  'Ultima actualización: '+date_created : 'Fecha de creación: '+date_updated}}</span>
                                </v-col>
                                <v-col class="d-flex justify-end" v-if="mostrar_btn_consulta_electrocardiograma">
                                    <v-btn variant="text" color="black" @click="startLoading(3);btn_consulta_electrocardiograma == false ? btn_consulta_electrocardiograma = true : btn_consulta_electrocardiograma = false">
                                      <v-icon size="15">{{!btn_consulta_electrocardiograma ? 'mdi-backup-restore' : 'mdi-update'}}</v-icon> <span class="mx-2 text-none"> {{ !btn_consulta_electrocardiograma ? 'Ver datos anteriores' : 'Ver cambios' }} </span>
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.descripcion && !loading_ske_electrocardiograma">
                                <v-col cols="12" sm="4" lg="4" md="4" xl="4">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Descripción
                                        general</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_electrocardiograma ? consulta_electrocardiograma.descripcion : consulta_modificada_electrocardiograma.descripcion
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.ritmo && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Ritmo</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_consulta_electrocardiograma ? consulta_electrocardiograma.ritmo : consulta_modificada_electrocardiograma.ritmo }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">sinusal</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_electrocardiograma.frecuencia_cardiaca && !loading_ske_electrocardiograma
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Frecuencia cardíaca
                                        (FC)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_electrocardiograma ? consulta_electrocardiograma.frecuencia_cardiaca : consulta_modificada_electrocardiograma.frecuencia_cardiaca
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">60-100 latidos por minuto
                                        (lpm)</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.eje && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Eje</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_consulta_electrocardiograma ? consulta_electrocardiograma.eje : consulta_modificada_electrocardiograma.eje}}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">-30° a +90°</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.duracionQRS && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Duración QRS</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_electrocardiograma ? consulta_electrocardiograma.duracionQRS : consulta_modificada_electrocardiograma.duracionQRS
                                        }}
                                        milisegundos
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">90.0 ms</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.ondaP && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Onda P</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_consulta_electrocardiograma ? consulta_electrocardiograma.ondaP : consulta_modificada_electrocardiograma.ondaP }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">
                                        - Duración normal: <120 ms. - Amplitud normal: <2.5 mm. </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.ondaT && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Onda T</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_consulta_electrocardiograma ? consulta_electrocardiograma.ondaT : consulta_modificada_electrocardiograma.ondaT }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem"></span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.segmento && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Segmento ST</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_electrocardiograma ? consulta_electrocardiograma.segmento : consulta_modificada_electrocardiograma.segmento
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Debe estar en la línea de
                                        base
                                        (isoelectricidad), sin elevación ni
                                        depresión significativa.</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.intervaloPR && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Intervalo PR</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_electrocardiograma ? consulta_electrocardiograma.intervaloPR : consulta_modificada_electrocardiograma.intervaloPR
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">120-200 milisegundos (ms)
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.intervaloQTC && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Intervalo QTc</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_electrocardiograma ? consulta_electrocardiograma.intervaloQTC : consulta_modificada_electrocardiograma.intervaloQTC
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">
                                        <440 ms (hombres) y <460 ms (mujeres) </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_electrocardiograma.conclusion && !loading_ske_electrocardiograma">
                                <v-col cols="12" sm="4" lg="4" md="4" xl="4">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Conclusión</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_consulta_electrocardiograma ? consulta_electrocardiograma.conclusion : consulta_modificada_electrocardiograma.conclusion
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <!-- ///////////////////////////////////////////////// -->
                            <!-- <v-row v-if="consulta_modificada_electrocardiograma.descripcion && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col cols="12" sm="4" lg="4" md="4" xl="4">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Descripción
                                        general</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_modificada_electrocardiograma.descripcion
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.ritmo && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Ritmo</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ consulta_modificada_electrocardiograma.ritmo }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">sinusal</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_modificada_electrocardiograma.frecuencia_cardiaca && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Frecuencia cardíaca
                                        (FC)</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_modificada_electrocardiograma.frecuencia_cardiaca
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">60-100 latidos por minuto
                                        (lpm)</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.eje && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Eje</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ consulta_modificada_electrocardiograma.eje }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">-30° a +90°</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.duracionQRS && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Duración QRS</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_modificada_electrocardiograma.duracionQRS
                                        }}
                                        milisegundos
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">90.0 ms</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.ondaP && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Onda P</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ consulta_modificada_electrocardiograma.ondaP }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">
                                        - Duración normal: <120 ms. - Amplitud normal: <2.5 mm. </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.ondaT && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Onda T</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ consulta_modificada_electrocardiograma.ondaT }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem"></span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.segmento && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Segmento ST</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_modificada_electrocardiograma.segmento
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Debe estar en la línea de
                                        base
                                        (isoelectricidad), sin elevación ni
                                        depresión significativa.</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.intervaloPR && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Intervalo PR</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_modificada_electrocardiograma.intervaloPR
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">120-200 milisegundos (ms)
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.intervaloQTC && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Intervalo QTc</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_modificada_electrocardiograma.intervaloQTC
                                        }}
                                    </span>
                                </v-col>
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">
                                        <440 ms (hombres) y <460 ms (mujeres) </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_modificada_electrocardiograma.conclusion && btn_consulta_electrocardiograma && !loading_ske_electrocardiograma">
                                <v-col cols="12" sm="4" lg="4" md="4" xl="4">
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Conclusión</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_modificada_electrocardiograma.conclusion
                                        }}
                                    </span>
                                </v-col> 
                            </v-row>-->
                             <!-- /////////////////////////////////////////// -->
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel  :value="7">
                        <v-expansion-panel-title>
                            <v-icon color="primary" size="15">mdi-test-tube</v-icon>
                            <span class="mx-1 poppins-semibold text-primary" style="font-size: 1.075rem">
                                Radiografía de tórax 
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text v-if="consulta_radiografias.length != 0 || consulta_modificada_radiografias.length != 0">
                            <v-row v-if="loading_ske_radiografia">
                                <v-col>
                                    <v-skeleton-loader
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row v-if="!loading_ske_radiografia ">
                                <v-col>  
                                    <span :class="!btn_radiografias ? 'poppins-bold' : 'poppins-medium'"> {{ !consulta_original ? 'Fecha de creación: '+date_created : consulta_original && !mostrar_btn_radiografias ? 'Fecha de creación: '+date_updated : !btn_radiografias && consulta_original ?  'Ultima actualización: '+date_created : 'Fecha de creación: '+date_updated}}</span>                                    
                                </v-col>
                                <v-col class="d-flex justify-end" v-if="mostrar_btn_radiografias">
                                    <v-btn variant="text" color="black" @click="startLoading(4);btn_radiografias == false ? btn_radiografias = true : btn_radiografias = false">
                                      <v-icon size="15">{{!btn_radiografias ? 'mdi-backup-restore' : 'mdi-update'}}</v-icon> <span class="mx-2 text-none"> {{ !btn_radiografias ? 'Ver datos anteriores' : 'Ver cambios' }} </span>
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_radiografias.partes_blandas && !loading_ske_radiografia">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Partes blandas</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_radiografias ? consulta_radiografias.partes_blandas : consulta_modificada_radiografias.partes_blandas
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_radiografias.partes_oseas && !loading_ske_radiografia">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Partes óseas</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_radiografias ? consulta_radiografias.partes_oseas : consulta_modificada_radiografias.partes_oseas }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_radiografias.campos_pulmonares && !loading_ske_radiografia">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Campos pulmonares</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_radiografias ? consulta_radiografias.campos_pulmonares : consulta_modificada_radiografias.campos_pulmonares
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_radiografias.silueta_cardiovascular && !loading_ske_radiografia
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Silueta
                                        cardiovascular</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_radiografias ? consulta_radiografias.silueta_cardiovascular : consulta_modificada_radiografias.silueta_cardiovascular
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_radiografias.indice_cardiotoracico && !loading_ske_radiografia
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Índice
                                        cardiotorácico</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_radiografias ? consulta_radiografias.indice_cardiotoracico : consulta_modificada_radiografias.indice_cardiotoracico
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_radiografias.conclusiones && !loading_ske_radiografia">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Conclusiones</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_radiografias ? consulta_radiografias.conclusiones : consulta_modificada_radiografias.conclusiones }}
                                    </span>
                                </v-col>
                            </v-row>

                            <!-- ///////////////////////////////////////////////////////// -->
                           
                             <!-- ///////////////////////////////////////////// -->
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel  :value="8">
                        <v-expansion-panel-title>
                            <v-icon color="primary" size="15">mdi-test-tube</v-icon>
                            <span class="mx-1 poppins-semibold text-primary" style="font-size: 1.075rem">
                                Otros estudios 
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            <v-row v-if="loading_ske_otros_estudios">
                                <v-col>
                                    <v-skeleton-loader
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row v-if="!loading_ske_otros_estudios">
                                <v-col>     
                                    <span :class="!btn_otros_estudios ? 'poppins-bold' : 'poppins-medium'"> {{ !consulta_original ? 'Fecha de creación: '+date_created : consulta_original && !mostrar_btn_otros_estudios ? 'Fecha de creación: '+date_updated : !btn_otros_estudios && consulta_original ?  'Ultima actualización: '+date_created : 'Fecha de creación: '+date_updated}}</span>                               
                                    
                                </v-col>
                                <v-col class="d-flex justify-end" v-if="mostrar_btn_otros_estudios">
                                    <v-btn variant="text" color="black" @click="startLoading(5);btn_otros_estudios == false ? btn_otros_estudios = true : btn_otros_estudios = false">
                                      <v-icon size="15">{{!btn_otros_estudios ? 'mdi-backup-restore' : 'mdi-update'}}</v-icon> <span class="mx-2 text-none"> {{ !btn_otros_estudios ? 'Ver datos anteriores' : 'Ver cambios' }} </span>
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row  v-if=" !loading_ske_otros_estudios && (consulta_otros_estudios.length > 0 || consulta_modificada_otros_estudios > 0)">
                                <v-col >
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Descripción </span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{ !btn_otros_estudios ? consulta_otros_estudios.otros : consulta_modificada_otros_estudios ? consulta_modificada_otros_estudios.otros : ''}}
                                    </span>
                                </v-col>
                            </v-row>
                           
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel  :value="9">
                        <v-expansion-panel-title  >
                            <v-icon color="primary" size="15">mdi-medical-bag</v-icon>
                            <span class="mx-1 poppins-semibold text-primary" style="font-size: 1.075rem">
                                Diagnósticos
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text v-if="diagnostico_sindromaticos.length != 0 || diagnostico_etiologicos.length != 0 || diagnostico_diferenciales.length != 0 || diagnostico_modificada_sindromaticos.length != 0 || diagnostico_modificada_etiologicos.length != 0 || diagnostico_modificada_diferenciales.length != 0">
                            <v-row v-if="loading_ske_diagnosticos">
                                <v-col>
                                    <v-skeleton-loader
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row v-if="!loading_ske_diagnosticos ">
                                <v-col>        
                                    <span :class="!btn_diagnostico ? 'poppins-bold' : 'poppins-medium'"> {{ !consulta_original ? 'Fecha de creación: '+date_created : consulta_original && !mostrar_btn_diagnostico ? 'Fecha de creación: '+date_updated : !btn_diagnostico && consulta_original ?  'Ultima actualización: '+date_created : 'Fecha de creación: '+date_updated}}</span>                                   
                                </v-col>
                                <v-col class="d-flex justify-end" v-if="mostrar_btn_diagnostico">
                                    <v-btn variant="text" color="black" @click="startLoading(6);btn_diagnostico == false ? btn_diagnostico = true : btn_diagnostico = false">
                                      <v-icon size="15">{{!btn_diagnostico ? 'mdi-backup-restore' : 'mdi-update'}}</v-icon> <span class="mx-2 text-none"> {{ !btn_diagnostico ? 'Ver datos anteriores' : 'Ver cambios' }} </span>
                                    </v-btn>
                                    <!-- <v-btn :color="!btn_diagnostico ? 'primary' : 'red'" @click="startLoading(6);btn_diagnostico == false ? btn_diagnostico = true : btn_diagnostico = false">
                                        {{ !btn_diagnostico ? 'Resgitro anterior' : 'Registro actualizado' }}
                                    </v-btn> -->
                                </v-col>
                            </v-row>
                            <v-row v-if="diagnostico_sindromaticos.length > 0 && !loading_ske_diagnosticos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Diagnósticos
                                        sindromaticos:
                                    </span>
                                    <span class="text-medium-emphasis" v-for="(
diagnostico_sindromaticos, index
                                        ) in diagnostico_sindromaticos" :key="index">
                                        <br />
                                        -
                                        {{ !btn_diagnostico ? diagnostico_sindromaticos.diNombre : diagnostico_modificada_sindromaticos.diNombre }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="diagnostico_etiologicos.length > 0 && !loading_ske_diagnosticos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Diagnósticos etiológicos:
                                    </span>
                                    <span class="text-medium-emphasis" v-for="(
diagnostico_etiologicos, index
                                        ) in diagnostico_etiologicos" :key="index">
                                        <br />
                                        - {{ !btn_diagnostico ? diagnostico_etiologicos.diNombre : diagnostico_modificada_etiologicos.diNombre }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="diagnostico_diferenciales.length > 0 && !loading_ske_diagnosticos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Diagnósticos
                                        diferenciales:
                                    </span>
                                    <span class="text-medium-emphasis" v-for="(
diagnostico_diferenciales, index
                                        ) in diagnostico_diferenciales" :key="index">
                                        <br />
                                        -
                                        {{ !btn_diagnostico ? diagnostico_diferenciales.diNombre : diagnostico_modificada_diferenciales.diNombre }}
                                    </span>
                                </v-col>
                            </v-row>

                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel  :value="10" >
                        <v-expansion-panel-title  >
                            <v-icon color="primary" size="15">mdi-medical-bag</v-icon>
                            <span class="mx-1 poppins-semibold text-primary " style="font-size: 1.075rem">
                                Tratamiento y medicamentos 
                            </span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text v-if="consulta_tratamiento.length != 0 || consulta_modificada_tratamiento.length != 0">
                            <v-row v-if="loading_ske_tratamiento">
                                <v-col>
                                    <v-skeleton-loader
                                        type="list-item-two-line"
                                    ></v-skeleton-loader>
                                </v-col>
                             </v-row>
                            <v-row v-if="!loading_ske_tratamiento">
                                <v-col>   
                                    <span :class="!btn_tratamiento ? 'poppins-bold' : 'poppins-medium'"> {{ !consulta_original ? 'Fecha de creación: '+date_created : consulta_original && !mostrar_btn_tratamiento ? 'Fecha de creación: '+date_updated :!btn_tratamiento && consulta_original ?  'Ultima actualización: '+date_created : 'Fecha de creación: '+date_updated}}</span>                                                                    
                                   
                                </v-col>
                                <v-col class="d-flex justify-end" v-if="mostrar_btn_tratamiento">
                                    <v-btn variant="text" color="black" @click="startLoading(7);btn_tratamiento == false ? btn_tratamiento = true : btn_tratamiento = false">
                                      <v-icon size="15">{{!btn_tratamiento ? 'mdi-backup-restore' : 'mdi-update'}}</v-icon> <span class="mx-2 text-none"> {{ !btn_tratamiento ? 'Ver datos anteriores' : 'Ver cambios' }} </span>
                                    </v-btn>
                                    <!-- <v-btn :color="!btn_tratamiento ? 'primary' : 'red'" @click="startLoading(7);btn_tratamiento == false ? btn_tratamiento = true : btn_tratamiento = false">
                                        {{ !btn_tratamiento ? 'Resgitro anterior' : 'Registro actualizado' }}
                                    </v-btn> -->
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col class="text-primary"> Tratamiento  </v-col>
                            </v-row>
                            <v-row v-if="consulta_tratamiento.plan_terapeutico && !loading_ske_tratamiento">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Plan terapéutico</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_tratamiento ? consulta_tratamiento.plan_terapeutico : consulta_modificada_tratamiento.plan_terapeutico
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_tratamiento.ordenes_estudios && !loading_ske_tratamiento">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Órdenes de
                                        estudios</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_tratamiento ? consulta_tratamiento.ordenes_estudios : consulta_modificada_tratamiento.ordenes_estudios
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_tratamiento.indicaciones_receta && !loading_ske_tratamiento">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Indicaciones</span>
                                </v-col>
                                <v-col>
                                    <span class="text-medium-emphasis">
                                        {{
                                            !btn_tratamiento ? consulta_tratamiento.indicaciones_receta : consulta_modificada_tratamiento.indicaciones_receta
                                        }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_tratamiento.arreglo_medicamento && !btn_tratamiento && !loading_ske_tratamiento">
                                <v-col class="text-primary"> Medicamentos </v-col>
                            </v-row>
                            <v-table density="compact" v-if="consulta_tratamiento.arreglo_medicamento && !btn_tratamiento && !loading_ske_tratamiento">
                                <thead>
                                    <tr>
                                        <th class="text-left">
                                            <span class="poppins-semibold" style="font-size: 0.955rem">Medicamento
                                            </span>
                                        </th>
                                        <th class="text-left">
                                            <span class="poppins-semibold" style="font-size: 0.955rem">Dosis
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(arreglo_medicamento, index) in consulta_tratamiento.arreglo_medicamento" :key="index">
                                        <td>
                                            <v-row>
                                                <v-col>
                                                    <span class="text-medium-emphasis">
                                                        {{
                                                            arreglo_medicamento.id
                                                        }}
                                                    </span>
                                                </v-col>
                                            </v-row>
                                        </td>
                                        <td>
                                            <v-row>
                                                <v-col>
                                                    <span class="text-medium-emphasis">
                                                        {{
                                                            arreglo_medicamento.dosis
                                                        }}
                                                    </span>
                                                </v-col>
                                            </v-row>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
     
                            <v-row v-if="consulta_modificada_tratamiento.arreglo_medicamento && btn_tratamiento && !loading_ske_tratamiento">
                                <v-col class="text-primary"> Medicamentos </v-col>
                            </v-row>
                            <v-table density="compact" v-if="consulta_modificada_tratamiento.arreglo_medicamento && btn_tratamiento && !loading_ske_tratamiento">
                                <thead>
                                    <tr>
                                        <th class="text-left">
                                            <span class="poppins-semibold" style="font-size: 0.955rem">Medicamento
                                            </span>
                                        </th>
                                        <th class="text-left">
                                            <span class="poppins-semibold" style="font-size: 0.955rem">Dosis
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(arreglo_medicamento, index) in consulta_modificada_tratamiento.arreglo_medicamento" :key="index">
                                        <td>
                                            <v-row>
                                                <v-col>
                                                    <span class="text-medium-emphasis">
                                                        {{
                                                            arreglo_medicamento.id
                                                        }}
                                                    </span>
                                                </v-col>
                                            </v-row>
                                        </td>
                                        <td>
                                            <v-row>
                                                <v-col>
                                                    <span class="text-medium-emphasis">
                                                        {{
                                                            arreglo_medicamento.dosis
                                                        }}
                                                    </span>
                                                </v-col>
                                            </v-row>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                           
                             <!-- ///////////////////////////////////// -->
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-col>
        </v-row>
    </v-container>
    </App>
</template>
