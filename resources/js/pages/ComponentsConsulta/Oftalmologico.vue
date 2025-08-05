<script type="text/javascript" setup>
    import {ref} from 'vue'
    const CatOcularPresiones = ref([
        {valor:"normal", etiqueta:"Normal"},
        {valor:"alta", etiqueta:"Alta"},
        {valor:"baja", etiqueta:"Baja"}
    ])
    const CatAgudezas = defineModel('CatAgudezas');
    //Oftalmologico Variables
    const AgudezaOjoDerecho = defineModel('AgudezaOjoDerecho');
    const AgudezaOjoIzquierdo = defineModel('AgudezaOjoIzquierdo');
    const RefraccionOjoDerecho = defineModel('RefraccionOjoDerecho');
    const RefraccionOjoIzquierdo = defineModel('RefraccionOjoIzquierdo');
    const PresionOcularOjoDerecho = defineModel('PresionOcularOjoDerecho');
    const PresionOcularOjoIzquierdo = defineModel('PresionOcularOjoIzquierdo');
    const ExploracionSegmentoAnterior = defineModel('ExploracionSegmentoAnterior');
    const ExploracionRetinayVitreo = defineModel('ExploracionRetinayVitreo');

    /*const LentesGraduados = defineModel('LentesGraduados');
    const Graduacion = defineModel('Graduacion');
    const ExploracionOftalmologica = defineModel('ExploracionOftalmologica');*/
</script>
<template>
    <v-container fluid class="pa-0">
            <v-row class="rounded-t-lg mt-0">
                <v-col class="text-primary">
                    <span style="font-size: 1.025rem;">Oftalmológico</span>
                </v-col>
            </v-row>
            <v-row class="mx-2" >
                <v-col cols="12" sm="12" md="12" lg="6" xl="6" >
                    <v-container fluid class="py-0 pa-0">
                        <v-row class="d-flex align-center" no-gutters>
                          <v-col cols="12" sm="12" md="12" lg="3" xl="3">
                            <span style="font-size: 0.875rem;">Agudeza visual ojo derecho</span>
                          </v-col>
                          <v-col cols="12" sm="12" md="12" lg="9" xl="9">
                            <v-select
                                :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
                                clearable
                                :items="CatAgudezas"
                                item-value="aID"
                                item-title="aEtiqueta"
                                placeholder="Selecciona la agudeza visual ojo derecho"
                                variant="outlined" flat
                                rounded="lg"
                                hide-details
                                v-model="AgudezaOjoDerecho"
                            ></v-select>
                          </v-col>
                        </v-row>
                    </v-container>
                </v-col>
                <v-col cols="12" sm="12" md="12" lg="6" xl="6">
                    <v-container fluid class="py-0 pa-0">
                        <v-row class="d-flex align-center" no-gutters>
                          <v-col cols="12" sm="12" md="12" lg="3" xl="3">
                            <span style="font-size: 0.875rem;">Agudeza visual ojo izquierdo</span>
                          </v-col>
                          <v-col cols="12" sm="12" md="12" lg="9" xl="9">
                            <v-select
                                clearable
                                :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
                                :items="CatAgudezas"
                                item-value="aID"
                                item-title="aEtiqueta"
                                placeholder="Selecciona la agudeza visual ojo izquierdo"
                                variant="outlined" flat
                                rounded="lg"
                                hide-details
                                v-model="AgudezaOjoIzquierdo"
                            ></v-select>
                          </v-col>
                        </v-row>
                    </v-container>
                </v-col>

                <v-col cols="12" sm="12" md="12" lg="6" xl="6">
                    <span style="font-size: 0.875rem;">Refracción ojo derecho</span>
                    <v-text-field placeholder="Ingrese los resultados de la refracción del ojo derecho (ej. -2.00 = -1.00 x 90°)" :counter="50" maxlength="50" variant="outlined" flat v-model="RefraccionOjoDerecho" rounded="lg"></v-text-field>
                </v-col>
                <v-col cols="12" sm="12" md="12" lg="6" xl="6">
                    <span style="font-size: 0.875rem;">Refracción ojo izquierdo</span>
                    <v-text-field :counter="50" maxlength="50" placeholder="Ingrese los resultados de la refracción del ojo Izquierdo (ej. -2.00 = -1.00 x 90°)" variant="outlined" flat v-model="RefraccionOjoIzquierdo" rounded="lg"></v-text-field>
                </v-col>
                <v-col cols="12" sm="12" md="12" lg="6" xl="6">
                    <span style="font-size: 0.875rem;">Presión ocular ojo derecho</span>
                    <v-select
                        hide-details
                        :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
                        clearable
                        variant="outlined" flat v-model="PresionOcularOjoDerecho" rounded="lg"
                        :items="CatOcularPresiones"
                        item-value="valor"
                        item-title="etiqueta"
                        placeholder="Ingrese la presión ocular del ojo derecho (ej. normal, alta, baja)"
                    ></v-select>
                </v-col>
                <v-col cols="12" sm="12" md="12" lg="6" xl="6">
                    <span style="font-size: 0.875rem;">Presión ocular ojo izquierdo</span>
                    <v-select
                        :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
                        clearable
                        hide-details
                        variant="outlined" flat v-model="PresionOcularOjoIzquierdo" rounded="lg"
                        :items="CatOcularPresiones"
                        item-value="valor"
                        item-title="etiqueta"
                        placeholder="Ingrese la presión ocular del ojo derecho (ej. normal, alta, baja)"
                    ></v-select>
                </v-col>
                <v-col cols="12" sm="12" md="12" lg="6" xl="6">
                    <span style="font-size: 0.875rem;">Exploración segmento anterior</span>
                    <v-textarea :counter="255"maxlength="255" auto-grow variant="outlined" flat v-model="ExploracionSegmentoAnterior" rounded="lg" placeholder="Describa los hallazgos de la exploración del segmento anterior (ej. claridad de la córnea, estado del iris)"></v-textarea>
                </v-col>
                <v-col cols="12" sm="12" md="12" lg="6" xl="6">
                    <span style="font-size: 0.875rem;">Exploración retina y vítreo</span>
                    <v-textarea :counter="255"maxlength="255" auto-grow variant="outlined" flat v-model="ExploracionRetinayVitreo" rounded="lg" placeholder="Describa los hallazgos de la exploración de retina y vítreo (ej. presencia de lesiones, cambios en la pigmentación)"></v-textarea>
                </v-col>
            </v-row>

    </v-container>
</template>