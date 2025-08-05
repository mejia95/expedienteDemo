<template>
    <v-container fluid>
        <v-row class="mb-0">
            <v-col class="d-flex align-center">
                <span class="poppins-semibold text-grey-darken-3" style="font-size: 1.2rem;">Antecedentes del paciente</span>
            </v-col>
            <v-col class="d-flex justify-end align-center">
                <v-btn icon="mdi-close" flat @click="CerrarModalAntecedentes"></v-btn>
            </v-col>
        </v-row>
        <v-divider></v-divider>
        <v-row  class="my-8 mx-2">
            <v-col cols="6" class=" ">
                <v-container fluid>
                    <v-row class="d-flex align-center">
                        <v-col cols="auto">
                            <v-avatar color="primary">
                                <v-icon>mdi-account-edit</v-icon>
                            </v-avatar>
                        </v-col>
                        <v-col>
                            
                            <v-row no-gutters class="my-2">
                                <v-col cols="12">
                                    <span class="poppins-semibold">{{store.AntecedentesPaciente.nombre}}</span>
                                </v-col>
                                <v-col cols="12">
                                    <span style="font-size:0.695rem" class="text-grey-darken-2">ESTELA ID # {{store.AntecedentesPaciente.pId}}</span>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <v-row class="d-flex align-center" no-gutters>
                        <v-col>
                            <span style="font-size:0.695rem">Género: {{store.AntecedentesPaciente.genero ? store.AntecedentesPaciente.genero : 'S/R'}}</span>

                        </v-col>
                        <v-col>
                            <span style="font-size:0.695rem">Tipo de sangre: {{store.AntecedentesPaciente.tipo_sangre ? store.AntecedentesPaciente.tipo_sangre : 'S/R'}}</span>
                        </v-col>
                        <v-col cols="12">
                            <span style="font-size:0.695rem">{{ store.AntecedentesPaciente.edad }}</span>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col class="d-flex justify-center"> 
                            <v-btn color="primary" @click="router.get(`/pacientes/perfil/${store.AntecedentesPaciente.pId}`)">
                                Actualizar información
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
            <v-col cols="6" class="border-thin pa-4 rounded-lg">
                <v-row>
                    <v-col>
                        <span style="font-size:0.895rem" class="poppins-semibold">Resumen del paciente</span>
                        </v-col>
                </v-row>
                <v-row>
                    <v-col cols="6">
                        <v-row no-gutters>
                            <v-col>
                                <span style="font-size:0.795rem"  class="text-grey-darken-1">Correo electrónico</span>
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col>
                                <span style="font-size:0.795rem">{{ store.AntecedentesPaciente.correo ? store.AntecedentesPaciente.correo : ' Aún no hay información registrada.' }}</span>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="6">
                        <v-row no-gutters>
                            <v-col>
                                <span style="font-size:0.795rem" class="text-grey-darken-1">Estado civil</span>
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col>
                                
                                <span style="font-size:0.795rem">{{ store.AntecedentesPaciente.estado_civil ? store.AntecedentesPaciente.estado_civil : ' Aún no hay información registrada.' }}</span>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <v-row class="px-4">
            <v-col>
                <v-tabs @click="CambiarPestaniaAntecedentes" density="compact"  bg-color="primary" v-model="enfermedades_pestania" >
                    <v-tab :value="1"  class="text-none">
                        <v-icon size="13">mdi-file-document-outline</v-icon>
                        <span class="mx-2" style="font-size: 0.775rem;">Antecedentes</span>
                    </v-tab>
                    <v-tab :value="2" class="text-none">
                        <v-icon size="13">mdi-heart-pulse</v-icon>
                        <span class="mx-2" style="font-size: 0.775rem;">Enfermedades</span>
                    </v-tab>
                </v-tabs>
            </v-col>
        </v-row>

        <v-card width="98%" elevation="0" color="grey-lighten-4" class="mx-4 pa-2 my-4" rounded="lg">
            <v-card-text v-if="enfermedades_pestania==1">
                <v-expansion-panels variant="accordion" multiple rounded="lg" elevation="0" v-model="antecedentes_pestania">
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-heart-outline" class="mr-2" size="15"></v-icon>
                            <span>Antecedentes heredofamiliares</span>
                        </v-expansion-panel-title>
                        <v-expansion-panel-text class="text-grey-darken-2">
                            - {{ store.AntecedentesHeredo ? store.AntecedentesHeredo : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-heart-outline" class="mr-2" size="15"></v-icon>
                            Antecedentes personales no patológicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text class="text-grey-darken-2">
                            - {{ store.antecedentesNoPatologicos ? store.antecedentesNoPatologicos : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-smoking" class="mr-2" size="15"></v-icon>
                            
                            Toxicomanías
                        </v-expansion-panel-title>
                        <v-expansion-panel-text class="mx-10">
                            <ul v-if="store.toxicomanias">
                                <li v-for="toxicomania in store.toxicomanias">
                                    {{ toxicomania.etiqueta }}
                                </li>
                            </ul>
                            <span v-else>Aún no hay información registrada.</span>
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-heart-outline" class="mr-2" size="15"></v-icon>
                            Antecedentes personales patológicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text class="mx-10">
                            <ul v-if="store.antecedentesPerPatologicos">
                                <li v-for="antecedente in store.antecedentesPerPatologicos">
                                    {{ antecedente.eNombre }}
                                </li>
                            </ul>
                            <span v-else>Aún no hay información registrada.</span>
                            
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-hospital-box-outline" class="mr-2" size="15"></v-icon>
                            Cirugías y hospitalizaciones
                        </v-expansion-panel-title>
                        <v-expansion-panel-text class="text-grey-darken-2">
                            - {{ store.cirugias_hospitalizaciones ? store.cirugias_hospitalizaciones : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-needle" class="mr-2" size="15"></v-icon>
                            Inmunizaciones
                        </v-expansion-panel-title>
                        <v-expansion-panel-text class="text-grey-darken-2">
                            - {{ store.inmunizaciones ? store.inmunizaciones : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-heart-outline" class="mr-2" size="15"></v-icon>
                            Antecedentes gineco-obstétricos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text class="text-grey-darken-2">
                            - {{ store.antecedentesGinecoObs ? store.antecedentesGinecoObs : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-allergy" class="mr-2" size="15"></v-icon>
                            Alergias
                        </v-expansion-panel-title>
                        <v-expansion-panel-text class="text-grey-darken-2">
                            - {{ store.alergias ? store.alergias : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-card-text>
            <v-card-text v-if="enfermedades_pestania==2">
                <v-expansion-panels variant="accordion" multiple rounded="lg" elevation="0" v-model="antecedentes_enfermedades_pestania">
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Cardiovascular (CV)
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.cardiovascular ? store.cardiovascular : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Respiratorio
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.respiratorio ? store.respiratorio : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Nefro-urológico
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.NefroUrologico ? store.NefroUrologico : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Neurológico
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.Neurologico ? store.Neurologico : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Hematológicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.Hematologicos ? store.Hematologicos : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Ginecológicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.Ginecologicos ? store.Ginecologicos : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Infectológicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.Infectologicos ? store.Infectologicos : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Endocrinológicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.Endocrinologicos ? store.Endocrinologicos : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Quirúrgicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.Quirurgicos ? store.Quirurgicos : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Alérgicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.Alergicos ? store.Alergicos : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                    <v-expansion-panel
                    >
                        <v-expansion-panel-title class="poppins-semibold text-primary-darken-2">
                            <v-icon icon="mdi-stethoscope" class="mr-2" size="15"></v-icon>
                            Socioeconómicos/Epidemiológicos
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            - {{ store.SocioecEpide ? store.SocioecEpide : 'Aún no hay información registrada.' }}
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-card-text>
        </v-card>
    </v-container>
</template>
<script>
    import { ref,inject } from 'vue';
    import { AplicacionStore } from '@/stores/Aplicacion';
    import { router, Head, usePage } from '@inertiajs/vue3';
    export default{
        setup(){
            const CerrarModalAntecedentes = inject('CerrarModal');
            const store = AplicacionStore();
            const MostrarAntecedentesModal = ref(false);
            const enfermedades_pestania = ref(1);
            const antecedentes_pestania = ref([0,1,2,3,4,5,6,7])
            const antecedentes_enfermedades_pestania = ref([0,1,2,3,4,5,6,7,8,9,10]);

            const CambiarPestaniaAntecedentes = ()=>{
                antecedentes_pestania.value = [0,1,2,3,4,5,6,7];
                antecedentes_enfermedades_pestania.value = [0,1,2,3,4,5,6,7,8,9,10];
                
            };
            return {
                enfermedades_pestania,antecedentes_pestania,
                antecedentes_enfermedades_pestania,
                CambiarPestaniaAntecedentes,
                MostrarAntecedentesModal,
                CerrarModalAntecedentes,
                store,
                router
            }
        }
    }
</script>