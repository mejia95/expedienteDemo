<script setup>
const props = defineProps({
  
    consulta_antecedentes: {
    type: Array,
    default: () => []
  },
 
})
</script>
<template>
    <v-container>
        <v-row>
                                <v-col class="text-primary"> Antecedentes </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_antecedentes.cirugias_hospitalizaciones
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Cirugías y
                                        hospitalizaciones:
                                    </span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_antecedentes.cirugias_hospitalizaciones
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.inmunizaciones">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Inmunizaciones:</span>
                                    <span class="text-medium-emphasis">
                                        {{
                                            consulta_antecedentes.inmunizaciones
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.antecedentesPerPatologicos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Antecedentes personales
                                        patológicos:
                                    </span>
                                    <span class="text-medium-emphasis" v-for="(
enfermedad, index
                                        ) in consulta_antecedentes.NombresEnfermedades" :key="index">
                                        <br />
                                        - {{ enfermedad.eNombre }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_antecedentes.antecedentesNoPatologicos
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Antecedentes personales
                                        no patológicos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.antecedentesNoPatologicos
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="
                                consulta_antecedentes.antecedentesGinecoObs
                            ">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Antecedentes
                                        gineco-obstétricos:</span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.antecedentesGinecoObs
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.toxicomanias">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Toxicomanías:</span>

                                    <span class="text-medium-emphasis" v-for="(
toxicomania, index
                                        ) in consulta_antecedentes.Toxicomanias" :key="index">
                                        <br />
                                        - {{ toxicomania.etiqueta }}
                                    </span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.alergias">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Alergias:</span>
                                    <span class="text-red mx-2">{{
                                        consulta_antecedentes.alergias
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col class="text-primary"> Enfermedades </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.cardiovascular">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Cardiovascular (CV):
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.cardiovascular
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.respiratorio">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Respiratorio:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.respiratorio
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.NefroUrologico">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Nefro-urológico:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.NefroUrologico
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.Neurologico">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Neurológico:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.Neurologico
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.Hematologicos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Hematológicos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.Hematologicos
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.Ginecologicos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Ginecológicos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.Ginecologicos
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.Infectologicos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Infectológicos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.Infectologicos
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.Endocrinologicos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Endocrinológicos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.Endocrinologicos
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.Quirurgicos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Quirúrgicos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.Quirurgicos
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.Alergicos">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Alérgicos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.Alergicos
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.SocioecEpide">
                                <v-col>
                                    <span class="poppins-semibold"
                                        style="font-size: 0.955rem">Socioeconómicos/Epidemiológicos:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.SocioecEpide
                                        }}</span>
                                </v-col>
                            </v-row>
                            <v-row v-if="consulta_antecedentes.AntecedentesHeredo">
                                <v-col>
                                    <span class="poppins-semibold" style="font-size: 0.955rem">Antecedentes
                                        heredofamiliares:
                                    </span>
                                    <span class="text-medium-emphasis">{{
                                        consulta_antecedentes.AntecedentesHeredo
                                        }}</span>
                                </v-col>
                            </v-row>
    </v-container>
</template>