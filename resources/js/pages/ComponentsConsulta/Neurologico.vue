<script setup>
    import {ref} from 'vue';
// Neurologico Variables
    const CatAlertaEstados = ref([
        { valor: "despierto", etiqueta: "Despierto" },
        { valor: "somnoliento", etiqueta: "Somnoliento" },
        { valor: "soporoso", etiqueta: "Soporoso" },
        { valor: "comatoso", etiqueta: "Comatoso" }
    ]);
    const CatEsferaMotora = ref([
        { valor: "grado-v", etiqueta: "Grado V vence gravedad" },
        { valor: "grado-iv", etiqueta: "Grado IV vence gravedad, y alguna resistencia" },
        { valor: "grado-iii", etiqueta: "Grado III vence gravedad nada de resistencia" },
        { valor: "grado-ii", etiqueta: "Grado II movimiento sin vencer gravedad" },
        { valor: "grado-i", etiqueta: "Grado I, Fasciculaciones" },
        { valor: "grado-0", etiqueta: "Grado 0 Ausencia total" }
    ]);
    const CatReflejos = ref([
        { valor: "normal", etiqueta: "Normal" },
        { valor: "hiperreflexia", etiqueta: "Hiperreflexia" },
        { valor: "hiporreflexia", etiqueta: "Hiporreflexia" },
        { valor: "arreflexia", etiqueta: "Arreflexia" }
    ]);
    const CatEspecialesPruebas = ref([
        { valor: "normal", etiqueta: "Normal" },
        { valor: "babinsky", etiqueta: "Babinsky" },
        { valor: "klonus", etiqueta: "Klonus" },
        { valor: "romberg", etiqueta: "Romberg" }
    ]);
    const Ocular = defineModel('Ocular')
    const Verbal = defineModel('Verbal')
    const Motora = defineModel('Motora')
    const Total = defineModel('Total')

    const EstadoAlerta = defineModel('EstadoAlerta');
    const FuncionesMentalesSuperiores = defineModel('FuncionesMentalesSuperiores');
    const ParesCranales = defineModel('ParesCranales');
    const EsferaMotora = defineModel('EsferaMotora');
    const EsferaSensitiva = defineModel('EsferaSensitiva');
    const Reflejos = defineModel('Reflejos');
    const PruebasEspeciales = defineModel('PruebasEspeciales');
    /*const MotilidadActiva = defineModel('MotilidadActiva')
    const MotilidadPasiva = defineModel('MotilidadPasiva')
    const MotilidadRefleja = defineModel('MotilidadRefleja')

    const Sensibilidad = defineModel('Sensibilidad')
    const DescripcionNeurologico = defineModel('DescripcionNeurologico')
    */

</script>
<template>
    <v-container fluid class="pa-0">
            <v-row class="rounded-t-lg mt-0">
                <v-col class="text-primary">
                    <span style="font-size: 1.025rem;">Neurológico</span>
                </v-col>
            </v-row>
            <v-row class="mx-2">
                <v-col>
                    <span style="font-size: 0.875rem;">Glasgow:</span>
                </v-col>
                <v-col cols="12">
                    <v-container fluid class="py-0">
                        <v-row class="d-flex align-center" no-gutters>
                          <v-col cols="12" sm="12" md="12" lg="3" xl="3" class="px-1">
                            <span style="font-size: 0.875rem;">Ocular</span>
                            <v-select :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
                            hide-details
                            :counter="10"
                            :items="[1,2,3,4]"
                            v-model="Ocular"
                            required
                            rounded="lg"variant="outlined" flat
                            ></v-select>
                          </v-col>
                          <v-col cols="12" sm="12" md="12" lg="3" xl="3" class="px-1">
                            <span style="font-size: 0.875rem;">Verbal</span>
                            <v-select :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
                            hide-details
                            :items="[1,2,3,4,5]"
                            :counter="10"
                            v-model="Verbal"
                            required
                            rounded="lg"variant="outlined" flat
                            ></v-select>
                          </v-col>
                          <v-col cols="12" sm="12" md="12" lg="3" xl="3" class="px-1">
                            <span style="font-size: 0.875rem;">Motora</span>
                            <v-select :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
                            hide-details
                            :counter="10"
                            :items="[1,2,3,4,5,6]"
                            v-model="Motora"
                            required
                            rounded="lg"variant="outlined" flat
                            ></v-select>
                          </v-col>
                          <v-col cols="12" sm="12" md="12" lg="12" xl="12" class="px-1">
                            <span style="font-size: 0.875rem;">Total</span>
                            {{parseInt(Ocular) + parseInt(Verbal) + parseInt(Motora)}}
                          </v-col>
                        </v-row>
                    </v-container>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Estado de alerta</span>
                    <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
 clearable variant="outlined" v-model="EstadoAlerta" flat rounded="lg"  placeholder="(ej. Despierto, somnoliento, soporoso)" :items="CatAlertaEstados" item-value="valor" item-title="etiqueta"></v-select>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Funciones mentales superiores</span>
                    <v-textarea
                        auto-grow
                      variant="outlined" v-model="FuncionesMentalesSuperiores" flat rounded="lg"
                      :counter="255"
                      placeholder="Describa: Abstracción, Juicio, Cálculo, Praxias, Lenguaje"

                    ></v-textarea>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Pares craneales</span>
                    <v-textarea
                        auto-grow
                      variant="outlined" v-model="ParesCranales" flat rounded="lg"
                      :counter="255"
                      placeholder="Describa la evaluación de los pares craneales aquí"

                    ></v-textarea>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Esfera motora</span>
                    <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
 clearable variant="outlined" v-model="EsferaMotora" flat rounded="lg"  placeholder="Describa la esfera motora" :items="CatEsferaMotora" item-value="valor" item-title="etiqueta"></v-select>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Esfera sensitiva</span>
                    <v-textarea
                        auto-grow
                      variant="outlined" v-model="EsferaSensitiva" flat rounded="lg"
                      :counter="255"
                      placeholder="Describa la evaluación de los dermatomas"

                    ></v-textarea>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Reflejos</span>
                    <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
 clearable variant="outlined" v-model="Reflejos" flat rounded="lg"  placeholder="(ej. Normal, Hiperreflexia)" :items="CatReflejos" item-value="valor" item-title="etiqueta"></v-select>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Pruebas especiales</span>
                    <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
 clearable variant="outlined" v-model="PruebasEspeciales" flat rounded="lg"  placeholder="(ej. babinsky, klonus)" :items="CatEspecialesPruebas" item-value="valor" item-title="etiqueta"></v-select>
                </v-col>
            </v-row>
    </v-container>
</template>