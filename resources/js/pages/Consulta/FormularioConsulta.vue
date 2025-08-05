<script setup>
 import App from '@/layouts/app/App.vue';
  import { useGoTo } from 'vuetify';
  import { router, Head, usePage } from '@inertiajs/vue3';
    import {ref,computed,watch,onMounted,nextTick, provide} from 'vue';
    import Alerta from '@/components/AppAlerta.vue';
    //import Tratamiento from '@/pages/ComponentsConsulta/Tratamiento2.vue';
    import Tratamiento from '@/pages/ComponentsConsulta/Test.vue';
    import Antecedentes from '@/pages/ComponentsConsulta/Antecedentes.vue';
    import Exploracion from '@/pages/ComponentsConsulta/Exploracion.vue';        
  //  import Exploracionv2 from '@/pages/ComponentsConsulta/Exploracionv2.vue';
    import Estudios from '@/pages/ComponentsConsulta/Estudios.vue';
    import Diagnosticos from '@/pages/ComponentsConsulta/Diagnosticos.vue';
    import { AplicacionStore } from '@/stores/Aplicacion.js';

            const AlertaCallback = ref('miFuncionCallback')
            const goTo = useGoTo();
            const api = "/consulta";
            const page = usePage()
            const id_consulta = page.props.id
            //defineProps(['id']);
            const TruncarTexto = ref(false);
            //const SeccionConsulta = ref(0);
            const MostrarTodaOcupacion = ref(false);
            const fichaIdentificacion = ref([0]);
            const Ocupacion = ref(null);
            //const seleccion_clinica = ref(null);
            const ExploracionRef = ref(null);
            const RefAntecedentes = ref(null);
            const RefTratamiento = ref(null);
            const RefDiagnostico = ref(null);
            const clinicas = ref([]);
            const rules = ref([
                value => {
                    if (value) return true
                return 'El campo es requerido'
                },
            ]);
            const store = AplicacionStore();

            const LimpiarSeccionPiel = ()=>{

            }

            onMounted(async ()=>{
                store.seccion_exploracion=false;
                store.seccion_antecedentes=false;
                store.seccion_estudios=false;
                store.seccion_diagnostico=false;
                console.log("Id de consulta ashbsadghjds hgsdajds");
                await goTo(0, {});
                var el= document.getElementById("Ocupacion").clientWidth;
                var elText= document.getElementById("ocupacion-content").offsetWidth;
                if (elText > el) {
                    TruncarTexto.value = true;
                }
                await ConsultaDatosPaciente();
                await ConsultaConsultorios();

                ClinicaSeleccionada();
                //await nextTick();
                await ExploracionRef.value.ObtenerExploracionTodos();
                await ExploracionRef.value.seleccionarSeccion(10);
                ExploracionRef.value.ConsultarInformacionGuardada= 1;

            })

            const EstudiosLaboratorio = ref(null)
            const MostrarSeccionConsulta = async(seccion)=>{
                store.SeccionConsulta = seccion;
                switch(seccion){
                case 0:
                   
                    await ExploracionRef.value.ObtenerExploracionTodos();
                    await ExploracionRef.value.seleccionarSeccion(10);
                    ExploracionRef.value.ConsultarInformacionGuardada= 1;

                    break;
                case 1:
                    await RefAntecedentes.value.ConsultarAntecedente();
                    RefAntecedentes.value.BanderaBusqueda = 1;
                    break;
                case 2:
                  //  console.log("Entra a los estudios");
                        EstudiosLaboratorio.value.guardarInformacion=0;
                        EstudiosLaboratorio.value.GuardarEstudiosLaboratorio();
                        break;
                case 3:
                        
                        await RefDiagnostico.value.ObtenerDatosDiagnostico();
                        //await RefDiagnostico.value.ListaDiagnosticos();
                        RefDiagnostico.value.BanderaBusqueda = 1;
                        break;
                case 4:
                    await RefTratamiento.value.ConsultaMedicamentos();

                    break;
                }
            }

            function delay(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            const ConsultaDatosPaciente = () => {
                // store.opcion_clinica_faltante = 0;
      			// store.opcion_faltante = 0;
                store.loader_linear = true;
                const url = `${api}/consulta_paciente/`;
                axios.get(url+id_consulta).then((response)=>{
                    store.paciente = response.data[0];
                    store.loader_linear = false;
                    let consulta_duplicada = response.data[0] ? response.data[0].consulta_duplicada : 'Si';
                    if(consulta_duplicada=='Si' && store.RolUsuario != 4){
                        store.PermisosVista(router,`/pacientes/${store.RutaPacientes}`)
                    }
                }).catch((error)=>{
                    console.log(error.status);
                    return error.status === 510 ? store.PermisosVista(router,`/pacientes/${store.RutaPacientes}`):console.log(error.status);
                })
            }

            const ConsultaConsultorios = async () => {
                store.loader_linear = true;
                const url = `${api}/consulta_consultorios/`;
                const params ={
                    id_consulta :  id_consulta,
                }
                await axios.get(url+id_consulta).then((response)=>{
                    store.clinicas = response.data.consultorios;
                    store.seleccion_clinica = response.data.seleccion;
                    store.loader_linear = false;
                }).catch((error)=>{
                    return error.status === 510 ? store.PermisosVista(router,`/pacientes/${store.RutaPacientes}`):console.log(error.status);
                })
            }
            const ClinicaSeleccionada = async (seleccion) =>{
                ///consultorio : props.seleccion_clinica,
                store.consultorio = 'nombre1';
               setTimeout(async()=>{
                  const params ={
                    id_consulta : id_consulta,
                    seleccion : seleccion
                }
                const url = `${api}/agregar_clinica/`;
                await axios.put(url,params).then((response)=>{
                    console.log("Esta es la respuesta", response.data);
                }).catch((error)=>{

                })
            },500);

            }

            const seccionesConsulta = ref([
                {
                  title: 'Exploración y consulta',
                  to:'/consulta/valoracion',
                  icono: store.opcion_faltante == 0 ? 'mdi-stethoscope' : 'mdi-circle',
                  valor:2
                },{
                  title: 'Antecedentes',
                  to:'/consulta/antecedentes',
                  icono:'mdi-file-document-edit',
                  valor:1
                }
                ,{
                  title: 'Estudios',
                  to:'/consulta/estudios',
                  icono:'mdi-test-tube',
                  valor:2
                },{
                  title: 'Diagnóstico',
                  to:'/consulta/diagnosticos',
                  icono:'mdi-medical-bag',
                  valor:2
                },{
                  title: 'Tratamiento',
                  to:'/consulta/tratamiento',
                  icono:'mdi-clipboard-plus-outline',
                  valor:2
                },
            ]);
    
    const breadcrumbs = [
        { title: 'Inicio', href: '/' },
        { title: 'Pacientes', href: store.currentUrl },
        { title: 'Nueva consulta', href: store.currentUrl },
    ];
</script>
<template>
    <App :breadcrumbs="breadcrumbs">
    <v-container  class="bg-white my-5 rounded-lg  pa-0 px-0 px-sm-4 px-md-4 px-lg-4 px-xl-4 py-2 contenedor">
       <v-row class="d-flex align-start ma-4">
            <v-col cols="12" class="text-primary">Clínica</v-col>
            <v-divider></v-divider>
            <v-col cols="12" sm="6" md="4" lg="4" xl="4">
                <v-icon v-if="store.opcion_clinica_faltante == 1" color="red">mdi-circle</v-icon>
                <label>Selecciona la clínica </label>
                <v-progress-linear
                    v-if="store.loader_linear == true"
                    v-model="store.loader_linear"
                    color="primary"
                    indeterminate
                >
                </v-progress-linear>
                    <v-autocomplete
                    v-model="store.seleccion_clinica"
                    rounded="lg"
                    :items="store.clinicas"
                    item-title="nombre"
                    item-value="_id"
                    :rules="rules"
                    chips
                    color="primary"
                    :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
                    flat
                    disabled
                    variant="solo-filled"
                    @update:modelValue="ClinicaSeleccionada(store.seleccion_clinica)"                    
                    >
                    <template v-slot:chip="{ props, item }">
                        <v-chip
                        color="primary"
                        v-bind="props"
                        :subtitle="item.raw.turno"
                        :text="item.raw.nombre"
                        ></v-chip>
                    </template>

                    <template v-slot:item="{ props, item }">
                        <v-list-item
                        v-bind="props"
                        :subtitle="item.raw.turno"
                        :title="item.raw.nombre"
                        ></v-list-item>
                    </template>
                    </v-autocomplete>

            </v-col>

        </v-row>

        <v-row class="d-flex align-start ma-4">
            <v-col cols="12" class="text-primary">Ficha de identificación del paciente</v-col>
            <v-divider class="mx-2"></v-divider>
            <v-col class="mt-4 mb-n4" >
                <v-expansion-panels  color="primary-lighten-5" v-model="fichaIdentificacion" elevation="0">
                  <v-expansion-panel
                    class="rounded-sicodi border-thin"
                  >
                    <v-expansion-panel-title>
                        <template v-slot:default="{ expanded }">
                          <v-row no-gutters>
                            <v-col class="d-flex justify-start align-center poppins-semibold text-primary" cols="12" v-if="expanded">
                             <v-icon size="15" color="primary">mdi-account</v-icon><span class="mx-2">Información del paciente</span>
                            </v-col>
                            <v-col class="d-flex justify-start align-center" cols="12" v-else>
                                <v-icon size="15" color="primary">mdi-account</v-icon>
                              <span class="text-primary mx-2 poppins-semibold">{{ store.paciente.primer_apellido }} {{ store.paciente.segundo_apellido }} {{ store.paciente.nombre}} </span>
                              <span class="mx-2 text-grey" style="font-size:0.775rem;"># {{ store.paciente._id}}</span>
                            </v-col>
                          </v-row>
                        </template>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <v-container  class="pa-0">
                            <v-row  no-gutters>
                                <v-col>
                                    <v-container fluid>
                                        <v-row no-gutters>
                                            <v-col cols="12" class="ficha-identificacion-valor">{{ store.paciente.primer_apellido }} {{ store.paciente.segundo_apellido }} {{ store.paciente.nombre}}</v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Paciente</v-col>

                                        </v-row>
                                    </v-container>
                                </v-col>
                                <v-col>
                                    <v-container fluid>
                                        <v-row no-gutters>
                                            <v-col cols="12" class="ficha-identificacion-valor">{{ store.paciente.fecha_nacimiento}}</v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Fecha de nacimiento</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                                <v-col>
                                    <v-container fluid>
                                        <v-row no-gutters>
                                            <v-col cols="12" class="ficha-identificacion-valor">{{ store.paciente.edad }}</v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Edad</v-col>

                                        </v-row>
                                    </v-container>
                                </v-col>
                            </v-row>
                            <v-divider></v-divider>
                            <v-row  no-gutters>
                                <v-col cols="12" sm="6" md="4" lg="4" xl="4">
                                    <v-container fluid>
                                        <v-row no-gutters>
                                            <v-col cols="12" class="ficha-identificacion-valor">{{ store.paciente.etiqueta_genero}}</v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Sexo</v-col>

                                        </v-row>
                                    </v-container>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" lg="4" xl="4">
                                    <v-container fluid>
                                        <v-row no-gutters >

                                            <v-col cols="12" class="ficha-identificacion-valor">
                                                <v-container fluid class="pa-0">
                                                    <v-row>
                                                        <v-col cols="10" :class="!MostrarTodaOcupacion ? 'text-truncate':null" id="Ocupacion">
                                                            <span id="ocupacion-content">{{ store.paciente.ocupacion }}</span>
                                                        </v-col>
                                                        <v-col class="text-center" v-if="TruncarTexto">
                                                            <v-btn color="primary" icon density="compact" variant="text" @click="MostrarTodaOcupacion=!MostrarTodaOcupacion">
                                                                <v-icon>{{!MostrarTodaOcupacion ? 'mdi-plus':'mdi-minus'}}</v-icon>
                                                            </v-btn>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>

                                            </v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Ocupación</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" lg="4" xl="4">
                                    <v-container fluid>
                                        <v-row no-gutters>

                                            <v-col cols="12" class="ficha-identificacion-valor">
                                                {{ store.paciente.estado_civil}}
                                            </v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Estado civil</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                            </v-row>
                            <v-divider></v-divider>
                            <v-row  no-gutters >
                                <v-col cols="12" sm="6" md="4" lg="4" xl="4">
                                    <v-container fluid>
                                        <v-row no-gutters>

                                            <v-col cols="12" class="ficha-identificacion-valor">{{ store.paciente.nacionalidad}}</v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Nacionalidad</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" lg="4" xl="4">
                                    <v-container fluid>
                                        <v-row no-gutters>

                                            <v-col cols="12" class="ficha-identificacion-valor">{{ store.paciente.curp}}</v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">CURP</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" lg="4" xl="4">
                                    <v-container fluid>
                                        <v-row no-gutters>

                                            <v-col cols="12" class="ficha-identificacion-valor">{{ store.paciente.tipo_sangre }}</v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Tipo de sangre</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                            </v-row>
                            <v-divider></v-divider>
                            <v-row  no-gutters>
                                <v-col>
                                    <v-container fluid>
                                        <v-row no-gutters>

                                            <v-col cols="12" class="ficha-identificacion-valor">{{ store.paciente.telefono}}</v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Teléfono</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                                <v-col>
                                    <v-container fluid>
                                        <v-row no-gutters>

                                            <v-col cols="12" class="ficha-identificacion-valor">
                                                {{ store.paciente.telefono_celular}}
                                            </v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Teléfono celular</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                                <v-col>
                                    <v-container fluid>
                                        <v-row no-gutters>

                                            <v-col cols="12" class="ficha-identificacion-valor">
                                                {{ store.paciente.correo}}
                                            </v-col>
                                            <v-col cols="12" class="ficha-identificacion-titulo poppins-semibold text-grey">Correo electrónico</v-col>
                                        </v-row>
                                    </v-container>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-expansion-panel-text>
                  </v-expansion-panel>
                </v-expansion-panels>
            </v-col>
        </v-row>
        
        <v-row class="ma-4 mt-n1 rounded-lg elevation-0" >
            <v-col cols="12" class="text-primary" id="contenedor-consulta">Consulta </v-col>
            <v-divider class="mx-2"></v-divider>
            <v-col cols="12" class="mt-1">
                <v-slide-group show-arrows v-model="store.SeccionConsulta"
                >
                  <v-slide-group-item
                    v-for="(seccion,index) in seccionesConsulta"
                    v-slot="{ isSelected, toggle }"
                  >
                    <v-btn
                      class="ma-2"
                      rounded
                      size="small"
                      color="primary"
                      :variant="store.SeccionConsulta!=index ? 'outlined':'flat'"
                      @click="MostrarSeccionConsulta(index)"
                    >         
                        <v-icon v-if="seccion.icono" size="small" :color="store.opcion_faltante == 1 && seccion.icono == 'mdi-stethoscope'? 'red' : undefined" >{{ seccion.icono == 'mdi-stethoscope' && store.opcion_faltante == 1 ? 'mdi-circle' : seccion.icono }}</v-icon>
                        <span class="mx-2">{{ seccion.title }}</span>
                    </v-btn>
                  </v-slide-group-item>
                </v-slide-group>
            </v-col>
            <v-col cols="12" class="mt-3 pa-0 pa-sm-4 pa-md-4 pa-lg-4 pa-xl-4">
                <v-container fluid class="rounded-lg mt-n5 pa-0">
                    <v-row class="mb-2 mt-n10">
                        <v-col class="poppins-semibold" >
                            <v-alert
                        color="primary"
                        variant="tonal"
                        density="compact"
                        rounded="lg"
                        border
                        >
                            <v-icon size="15">mdi-information</v-icon>
                        <span style="font-size: 0.875rem;" class="mx-2">Recuerde guardar cada sección para evitar la pérdida de información en caso de imprevistos.</span>
                        </v-alert>
                        </v-col>
                    </v-row>
                   <v-row no-gutters class="overflow-auto"   id="consulta-secciones" v-show="store.SeccionConsulta==1">
                       <Antecedentes ref="RefAntecedentes" ></Antecedentes>
                   </v-row>
                   
                   <v-row no-gutters class="overflow-auto"  id="consulta-secciones" v-show="store.SeccionConsulta==0">                    
                       <Exploracion ref="ExploracionRef"></Exploracion>
                   </v-row>
                   <v-row no-gutters class="overflow-auto"  id="consulta-secciones" v-show="store.SeccionConsulta===2">
                       <Estudios  ref="EstudiosLaboratorio"></Estudios>
                   </v-row>
                   <v-row no-gutters class="overflow-auto"  id="consulta-secciones" v-show="store.SeccionConsulta==3">
                       <Diagnosticos ref="RefDiagnostico"></Diagnosticos>
                   </v-row>
                   <v-row no-gutters id="consulta-secciones" v-show="store.SeccionConsulta==4">
                    
                    <Tratamiento ref="RefTratamiento"></Tratamiento>
                   </v-row>
                   
                </v-container>
            </v-col>
        </v-row>
    </v-container>
    </App>
    
</template>

<style type="text/css">
    .borde_uno{
       border-color: #4DB6AC !important;
    }
    .ficha-identificacion-titulo{
        font-size:0.875rem ;
    }.ficha-identificacion-valor{
        font-size:0.955rem ;
    }
</style>
