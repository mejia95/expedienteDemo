<script setup>
    import {onMounted,ref} from 'vue'
// Cuello Variables
    const CatOxigenoSaturaciones = ref([
        {valor:"no-explorado",etiqueta: "No explorado"}
    ])

    const SaturacionOxigeno = defineModel('SaturacionOxigeno');
    const InspeccionRespiratorio = defineModel('InspeccionRespiratorio');
    const PalpacionRespiratorio = defineModel('PalpacionRespiratorio');
    const PercusionRespiratorio = defineModel('PercusionRespiratorio');
    const AuscultacionRespiratorio = defineModel('AuscultacionRespiratorio');

    onMounted(()=>{
        console.log("Esta montado el respiratorio")
        for (let i = 0; i <= 100; i++) {
            CatOxigenoSaturaciones.value.push({valor:i,etiqueta:`${i}%`})
        }
        console.log("Saturacion",CatOxigenoSaturaciones.value)
    })
</script>
<template>
    <v-container fluid class="pa-0">
            <v-row class="rounded-t-lg mt-0">
                <v-col class="text-primary">
                    <span style="font-size: 1.025rem;">Respiratorio</span>
                </v-col>
            </v-row>
            <v-row class="mx-2">
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Saturación de oxígeno</span>
                    <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"
 clearable variant="outlined" flat rounded="lg"  placeholder="Ingrese el nivel de saturación de oxígeno (ej. 95%, 98%)" :items="CatOxigenoSaturaciones" v-model="SaturacionOxigeno" item-value="valor" item-title="etiqueta"></v-select>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Inspección</span>
                    <v-textarea auto-grow variant="outlined" v-model="InspeccionRespiratorio" maxlength="255" :counter="255" flat rounded="lg" placeholder="Describa los hallazgos de la inspección respiratoria (ej. frecuencia, patrones de respiración)"></v-textarea>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Palpación</span>
                    <v-textarea auto-grow variant="outlined" v-model="PalpacionRespiratorio" maxlength="255" :counter="255" flat rounded="lg" placeholder="Describa los hallazgos de la palpación (ej. sensibilidad, masas, temperatura)"></v-textarea>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Percusión</span>
                    <v-textarea auto-grow variant="outlined" v-model="PercusionRespiratorio" maxlength="255" :counter="255" flat rounded="lg" placeholder="Describa los hallazgos de la percusión (ej. matidez, resonancia, características del sonido)"></v-textarea>
                </v-col>
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Auscultación</span>
                    <v-textarea auto-grow variant="outlined" v-model="AuscultacionRespiratorio" maxlength="255" :counter="255" flat rounded="lg" placeholder="Describa los hallazgos de la auscultación (ej. ruidos respiratorios, soplos cardíacos, ritmos)"></v-textarea>
                </v-col>
            </v-row>
    </v-container>
</template>