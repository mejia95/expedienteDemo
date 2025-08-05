<script setup>
// Cuello Variables
    const InspeccionAbdomen = defineModel('InspeccionAbdomen');
    const PalpacionAbdomen = defineModel('PalpacionAbdomen');
    const PercusionAbdomen = defineModel('PercusionAbdomen');
    const AuscultacionAbdomen = defineModel('AuscultacionAbdomen');
</script>
<template>
    <v-container fluid class="pa-0">
            <v-row class="rounded-t-lg mt-0">
                <v-col class="text-primary">
                    <span style="font-size: 1.025rem;">Abdomen</span>
                </v-col>
            </v-row>
            <v-row class="mx-2 ">
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Inspección</span>
                    <v-textarea
                        auto-grow
                      variant="outlined" flat rounded="lg" 
                      v-model="InspeccionAbdomen"
                      :counter="255"maxlength="255"
                      placeholder="Describa los hallazgos de la inspección del abdomen (ej. distensión, simetría, cicatrices, masas visibles)"
                      
                    ></v-textarea>
                </v-col>
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Palpación</span>
                    <v-textarea
                        auto-grow
                      variant="outlined" flat rounded="lg" 
                      v-model="PalpacionAbdomen"
                      :counter="255"maxlength="255"
                      placeholder="Describa los hallazgos de la palpación del abdomen (ej. dolor, masas, rigidez, temperatura)"
                      
                    ></v-textarea>
                </v-col>
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Percusión</span>
                    <v-textarea
                        auto-grow
                      variant="outlined" flat rounded="lg" 
                      v-model="PercusionAbdomen"
                      :counter="255"maxlength="255"
                      placeholder="Describa los hallazgos de la percusión del abdomen (ej. timpanismo, matidez, áreas de resonancia)"
                      
                    ></v-textarea>
                </v-col>
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Auscultación</span>
                    <v-textarea
                        auto-grow
                      variant="outlined" flat rounded="lg" 
                      v-model="AuscultacionAbdomen"
                      :counter="255"maxlength="255"
                      placeholder="Describa los hallazgos de la auscultación del abdomen (ej. presencia de ruidos intestinales, frecuencia, intensidad)"
                      
                    ></v-textarea>
                </v-col>
                
            </v-row>
            
    </v-container>
</template>