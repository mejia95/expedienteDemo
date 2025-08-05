<script setup>
    const CraneoCara = defineModel('CraneoCara');
    const CueroCabelludo = defineModel('CueroCabelludo');
    const RegionOrbitoNasal = defineModel('RegionOrbitoNasal');
    const RegionOrofaringea = defineModel('RegionOrofaringea');
    const InspeccionCuello = defineModel('InspeccionCuello');
    const PalpacionCuello = defineModel('PalpacionCuello');
    const PercusionCuello = defineModel('PercusionCuello');
    const AuscultacionCuello = defineModel('AuscultacionCuello');
</script>
<template>
        <v-container fluid class="pa-0">
            <v-row class="rounded-t-lg mt-0">
                <v-col class="text-primary">
                    <span style="font-size: 1.025rem;">Cabeza</span>
                </v-col>
            </v-row>
            <v-row class="mx-2">
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Cráneo y cara</span>
                    <v-textarea
                        hide-details="auto"
                        :counter="255"
                        maxlength="255"
                        v-model="CraneoCara"
                        placeholder="Cráneo y cara"
                        required
                        auto-grow
                        row-height="4"
                        rows="3"
                        variant="outlined" flat
                        rounded="lg"
                    ></v-textarea>
                </v-col>
                <v-col cols="12" class="mt-4">
                    <span style="font-size: 0.875rem;">Cuero cabelludo</span>
                    <v-textarea
                            :counter="255"
                             hide-details="auto"
                            maxlength="255"
                            v-model="CueroCabelludo"
                            placeholder="Cuero cabelludo"
                            required
                            auto-grow
                            row-height="4"
                            rows="3"
                            variant="outlined" flat
                            rounded="lg"
                    ></v-textarea>
                </v-col>
                <v-col cols="12">                        
                    <span style="font-size: 0.875rem;">Región orbito nasal</span>
                    <v-textarea
                    :counter="255"
                     hide-details="auto"
                    maxlength="255"
                    v-model="RegionOrbitoNasal"
                    placeholder="Región orbito nasal"
                    required
                    auto-grow
                    row-height="4"
                    rows="3"
                    variant="outlined" flat
                    rounded="lg"
                    ></v-textarea>
                </v-col>
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Región orofaríngea</span>
                    <v-textarea
                         hide-details="auto"
                        :counter="255"
                        maxlength="255"
                        v-model="RegionOrofaringea"
                        placeholder="Región orofaríngea"
                        required
                        auto-grow
                        row-height="4"
                        rows="3"
                        variant="outlined" flat
                        rounded="lg"
                    ></v-textarea>
                </v-col>
            </v-row>
            <v-row class="rounded-t-lg mt-0">
                <v-col class="text-primary">
                    <span style="font-size: 1.025rem;">Cuello</span>
                </v-col>
            </v-row>
            <v-row class="mx-2 ">
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Inspección</span>
                    <v-textarea
                         hide-details="auto"
                      variant="outlined" flat rounded="lg" 
                      maxlength="255"
                      v-model="InspeccionCuello"
                      :counter="255"
                      auto-grow
                        row-height="4"
                        rows="3"
                      placeholder="Describa los hallazgos de la inspección del cuello (ej. simetría, presencia de masas, movimientos, venas visibles)"
                      
                    ></v-textarea>
                </v-col>
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Palpación</span>
                    <v-textarea

                     hide-details="auto"
                      variant="outlined" flat rounded="lg" 
                      v-model="PalpacionCuello"
                      :counter="255"
                      maxlength="255"
                      auto-grow
                      row-height="4"
                      rows="3"
                      placeholder="Describa los hallazgos de la palpación del cuello (ej. sensibilidad, tamaño de ganglios, rigidez)"
                      
                    ></v-textarea>
                </v-col>
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Percusión</span>
                    <v-textarea
                        hide-details="auto"
                      variant="outlined" flat rounded="lg" 
                      v-model="PercusionCuello"
                      :counter="255"
                      maxlength="255"
                      auto-grow
                      row-height="4"
                      rows="3"
                      placeholder="Describa los hallazgos de la percusión del cuello (ej. matidez, resonancia, áreas de interés)"
                      
                    ></v-textarea>
                </v-col>
                <v-col cols="12" >
                    <span style="font-size: 0.875rem;">Auscultación</span>
                    <v-textarea
                        hide-details="auto"
                      variant="outlined" flat rounded="lg" 
                      v-model="AuscultacionCuello"
                      :counter="255"
                      maxlength="255"
                      auto-grow
                      row-height="4"
                      rows="3"
                      placeholder="Describa los hallazgos de la auscultación del cuello (ej. soplos, ruidos vasculares, características del sonido)"
                      
                    ></v-textarea>
                </v-col>
                
            </v-row>
    </v-container>
</template>