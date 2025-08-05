<script setup>
// Cuello Variables
    const Descripcion = defineModel('DescripcionCardiovascular');
</script>
<template>
    <v-container fluid class="pa-0">
            <v-row class="rounded-t-lg mt-0">
                <v-col class="text-primary">
                    <span style="font-size: 1.025rem;">Cardiovascular</span>
                </v-col>
            </v-row>
            <v-row class="mx-2 ">
                <v-col cols="12">
                    <span style="font-size: 0.875rem;">Describir ruidos cardiacos, campos pulmonares limpios, soplos, frémito, etc.</span>
                    <v-textarea
                        auto-grow
                      v-model="Descripcion"
                      :counter="255"maxlength="255"
                      placeholder="ej. intensidad, frecuencia, soplos, frémito, claridad de campos pulmonares."
                      required
                      variant="outlined" flat rounded="lg" 
                    ></v-textarea>
                </v-col>
                
            </v-row>
    </v-container>
</template>