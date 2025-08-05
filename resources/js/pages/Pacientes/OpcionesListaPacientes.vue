<script>
    import { ref, inject,defineAsyncComponent, provide } from 'vue';
    import { AplicacionStore } from '@/stores/Aplicacion'; 
    import {
        useDisplay
    } from 'vuetify';
    const paientes = "/pacientes";
    import { router, Head, usePage, Link } from '@inertiajs/vue3';
    import axios from 'axios';
    import moment from 'moment';

    const consulta = "/consulta";

    const AntecedentesComponente = defineAsyncComponent(() => import('@/pages/Pacientes/ComponentAntecedentes.vue'));
    export default{
        props:{
            paciente:{
                type:Object,
                default:null
            },
            solo_antecedentes:{
                type:Boolean,
                default:false,
            }
        },
        components:{
            AntecedentesComponente,
        },
        setup(paciente){
            const StoreAplicacion = AplicacionStore();
            const {lgAndUp} = useDisplay();
           // const router = useRouter();
            const MostrarAntecedentesModal = ref(false);


            const ConsultarAntecedente = async (paciente) => {
                const url = `${paientes}/antecedente`;
            //La busqueda de los antecedentes se modifico
            const datos = {
              params: {
                id_consulta: paciente
              }
            };
            
            /*const datos = {
              params: {
                id_consulta:id_consulta
              }
            };*/
            
          await axios.get(url,datos).then((response)=>{
            StoreAplicacion.cirugias_hospitalizaciones = response.data.cirugias_hospitalizaciones;
            StoreAplicacion.inmunizaciones = response.data.inmunizaciones;
            StoreAplicacion.antecedentesPerPatologicos = response.data.antecedentesPerPatologicosPaciente;
            StoreAplicacion.antecedentesNoPatologicos = response.data.antecedentesNoPatologicos;
            StoreAplicacion.antecedentesGinecoObs = response.data.antecedentesGinecoObs;
            StoreAplicacion.toxicomanias = response.data.ToxicomaniasPaciente ;
            StoreAplicacion.alergias = response.data.alergias;
            StoreAplicacion.cardiovascular = response.data.cardiovascular;
            StoreAplicacion.respiratorio = response.data.respiratorio;
            StoreAplicacion.NefroUrologico = response.data.NefroUrologico;
            StoreAplicacion.Neurologico = response.data.Neurologico;
            StoreAplicacion.Hematologicos = response.data.Hematologicos;
            StoreAplicacion.Ginecologicos = response.data.Ginecologicos;
            StoreAplicacion.Infectologicos = response.data.Infectologicos;
            StoreAplicacion.Endocrinologicos = response.data.Endocrinologicos;
            StoreAplicacion.Quirurgicos = response.data.Quirurgicos;
            StoreAplicacion.Alergicos = response.data.Alergicos;
            StoreAplicacion.SocioecEpide = response.data.SocioecEpide;
            StoreAplicacion.AntecedentesHeredo = response.data.AntecedentesHeredo;
          }).catch((error)=>{
            console.log(error);
            StoreAplicacion.cirugias_hospitalizaciones = null;
            StoreAplicacion.inmunizaciones = null;
            StoreAplicacion.antecedentesPerPatologicos = null;
            StoreAplicacion.antecedentesNoPatologicos = null;
            StoreAplicacion.antecedentesGinecoObs = null;
            StoreAplicacion.toxicomanias = null;
            StoreAplicacion.alergias = null;
            StoreAplicacion.cardiovascular = null;
            StoreAplicacion.respiratorio = null;
            StoreAplicacion.NefroUrologico = null;
            StoreAplicacion.Neurologico = null;
            StoreAplicacion.Hematologicos = null;
            StoreAplicacion.Ginecologicos = null;
            StoreAplicacion.Infectologicos = null;
            StoreAplicacion.Endocrinologicos = null;
            StoreAplicacion.Quirurgicos = null;
            StoreAplicacion.Alergicos = null;
            StoreAplicacion.SocioecEpide = null;
            StoreAplicacion.AntecedentesHeredo = null;
          });
          
        }

            const ObtenerAntecedentesPacientes = async(paciente)=>{
                await ConsultarAntecedente(paciente.pid);
                StoreAplicacion.AntecedentesPaciente = {correo:paciente.mail,celular:paciente.telefono_celular,telefono:paciente.telefono,edad:paciente.edad,pId:paciente.pid,nombre:paciente.paciente,genero:paciente.genero,tipo_sangre:paciente.tipo_sangre,estado_civil:paciente.estado_civil}
                MostrarAntecedentesModal.value=true;
            }

            const NuevaConsulta = (id) => {
                const url = `${consulta}/nueva`;
                // axios.post(url,{id:id}).then((response)=>{
                //     const id_consulta = response.data.id_consulta;
                //     if(response.data.estatus == 1){
                //         // router.get(`/consulta/nuevo/${id_consulta}`);               
                //     }
                // }).catch((error)=>{
                //     console.log(error)
                // });

                router.post(url,{id:id})

            }

            const CerrarModalAntecedentes = ()=>{
                MostrarAntecedentesModal.value = false;
            }
            provide('CerrarModal',CerrarModalAntecedentes)
            return{
                MostrarAntecedentesModal,
                CerrarModalAntecedentes,
                ObtenerAntecedentesPacientes,
                StoreAplicacion,
                NuevaConsulta,
                lgAndUp,
                router
            }
        }
    }
</script>
<template>
    <v-container fluid >
        <v-row class="d-flex align-center" no-gutters>
            <v-col cols="12" v-if="lgAndUp">
                <v-btn  icon variant="flat" @click="ObtenerAntecedentesPacientes(paciente)" >
                    <v-icon size="20" color="primary">mdi-account-file-text</v-icon>
                    <v-tooltip
                                activator="parent"
                                location="bottom"
                                >Antecedentes del paciente</v-tooltip>
                </v-btn>
                <v-btn v-if="!solo_antecedentes" icon variant="flat" @click="router.get(`/pacientes/perfil/${paciente.pid}`)" >
                    <v-icon size="20" color="primary">mdi-account-edit</v-icon>
                    <v-tooltip
                                activator="parent"
                                location="bottom"
                                >Ver paciente</v-tooltip>
                </v-btn>
                <v-btn v-if="!solo_antecedentes" icon variant="flat" @click="NuevaConsulta(paciente.pid)">
                    <v-icon size="20" color="primary">mdi-medical-bag</v-icon>
                    <v-tooltip
                                activator="parent"
                                location="bottom"
                                >Nueva consulta</v-tooltip>
                </v-btn>
                <v-btn v-if="!solo_antecedentes"  icon variant="flat" @click="router.get(`/pacientes/consulta/${paciente.pid}`)">
                    <v-icon size="20" color="primary">mdi-file-clock-outline</v-icon>
                    <v-tooltip
                                activator="parent"
                                location="bottom"
                                >Historial de consultas</v-tooltip>
                </v-btn>
            </v-col>
            <v-col cols="12" v-else>
                <v-menu scroll-strategy="block" content-class="rounded-lg">
                    <template v-slot:activator="{ props }">
                        <v-btn
                        flat
                        icon variant="flat"
                        v-bind="props"
                        >
                        <v-icon size="20" color="primary">mdi-dots-vertical</v-icon>
                        
                        </v-btn>
                    </template>
                    <v-list nav>
                        <v-list-item density="compact" nav slim link @click="ObtenerAntecedentesPacientes(paciente)"
                        >
                            <template v-slot:prepend>
                                <v-icon size="20" color="primary-darken-1">mdi-account-file-text</v-icon>
                            </template>
                            <v-list-item-title class="text-primary-darken-1">Antecedentes</v-list-item-title>
                        </v-list-item>
                        <v-list-item  v-if="!solo_antecedentes" density="compact" nav slim link @click="router.get(`/pacientes/perfil/${paciente.pid}`)"
                        >
                            <template v-slot:prepend>
                                <v-icon size="20" color="primary-darken-1">mdi-account-injury</v-icon>
                            </template>
                            <v-list-item-title class="text-primary-darken-1">Ver paciente </v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="!solo_antecedentes"  density="compact" nav slim link @click="NuevaConsulta(paciente.pid)"
                        >
                            <template v-slot:prepend>
                                <v-icon size="20" color="primary-darken-1">mdi-medical-bag</v-icon>
                            </template>
                            <v-list-item-title class="text-primary-darken-1">Nueva consulta</v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="!solo_antecedentes" density="compact" nav slim link @click="router.get(`/pacientes/consulta/${paciente.pid}`)"
                        >
                            <template v-slot:prepend>
                                <v-icon size="20" color="primary-darken-1">mdi-file-clock-outline</v-icon>
                            </template>
                            <v-list-item-title class="text-primary-darken-1">Historial de consultas</v-list-item-title>
                        </v-list-item>
                    </v-list>
                    </v-menu>
            </v-col>
        </v-row>
    </v-container>
    <v-dialog fullscreen v-model="MostrarAntecedentesModal">
           
       <v-card >
            <AntecedentesComponente ></AntecedentesComponente>
           <v-card-actions>
               <v-btn
               text="Cerrar"
               @click="MostrarAntecedentesModal = false"
               ></v-btn>
           </v-card-actions>
       </v-card>
   </v-dialog>
</template>
