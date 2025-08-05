<script setup type="text/javascript">
    import {ref} from 'vue';
            //Radiografia Variables
            const link_radiografia = ref(null);
            const archivoRadio1 = ref(null);
            const archivoRadio2 = ref(null);
            const archivoRadio3 = ref(null);
            const partes_blandas = ref(null);
            const partes_oseas = ref(null);
            const campos_pulmonares = ref(null);
            const silueta_cardiovascular = ref(null);
            const indice_cardiotoracico = ref(null);
            const conclusiones = ref(null);
    defineExpose({
      link_radiografia,
			archivoRadio1,
			archivoRadio2,
			archivoRadio3,
			partes_blandas,
			partes_oseas,
			campos_pulmonares,
			silueta_cardiovascular,
			indice_cardiotoracico,
			conclusiones,
    })
</script>
<template>
    <v-container class="rounded-b-xl pa-0" fluid>
        <!--<v-row class="bg-primary rounded mt-2">
            <v-col class="poppins-semibold">
                Radiografía de tórax
            </v-col>
        </v-row>-->
        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4">
            <v-col cols="12" sm="12" md="12" lg="12" xl="12">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Link del estudio</span> <small class="text-primary poppins-medium">(Ingrese el link , asegúrese de que tiene acceso al enlace o URL)</small>
                <v-text-field variant="outlined" flat rounded="lg"  v-model="link_radiografia" placeholder="https://www.ejemplo.com"></v-text-field>
            </v-col>
        </v-row>
            <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4">
                <v-col cols="12">
                  <v-container fluid class="pa-0">
                    <v-row class="d-flex align-center" no-gutters>
                      <v-col cols="12" sm="6" md="4" lg="3" xl="3"><span style="font-size: 0.875rem;" class="poppins-semibold">Partes blandas</span></v-col>
                      <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                        <v-text-field
                          v-model="partes_blandas"
                          :counter="255"
                          maxlength="255"
                          placeholder="Partes blandas"
                          hide-details
                          required
                          variant="outlined" flat
                          rounded="lg"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-col>
                <v-col cols="12">
                  <v-container fluid class="pa-0">
                    <v-row class="d-flex align-center" no-gutters>
                      <v-col cols="12" sm="6" md="4" lg="3" xl="3"><span style="font-size: 0.875rem;" class="poppins-semibold">Partes óseas</span></v-col>
                      <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                        <v-text-field
                          v-model="partes_oseas"
                          :counter="255"
                          maxlength="255"
                          placeholder="Partes óseas"
                          hide-details
                          required
                          variant="outlined" flat
                          rounded="lg"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-col>
                <v-col cols="12">
                  <v-container fluid class="pa-0">
                    <v-row class="d-flex align-center" no-gutters>
                      <v-col cols="12" sm="6" md="4" lg="3" xl="3"><span style="font-size: 0.875rem;" class="poppins-semibold">Campos pulmonares</span></v-col>
                      <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                        <v-text-field
                          v-model="campos_pulmonares"
                          :counter="255"
                          maxlength="255"
                          placeholder="Campos pulmonares"
                          hide-details
                          required
                          variant="outlined" flat
                          rounded="lg"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-col>
                <v-col cols="12">
                  <v-container fluid class="pa-0">
                    <v-row class="d-flex align-center" no-gutters>
                      <v-col cols="12" sm="6" md="4" lg="3" xl="3"><span style="font-size: 0.875rem;" class="poppins-semibold">Silueta cardiovascular</span></v-col>
                      <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                        <v-text-field
                          v-model="silueta_cardiovascular"
                          :counter="255"
                          maxlength="255"
                          placeholder="Silueta cardiovascular"
                          hide-details
                          required
                          variant="outlined" flat
                          rounded="lg"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-col>
                <v-col cols="12">
                  <v-container fluid class="pa-0">
                    <v-row class="d-flex align-center" no-gutters>
                      <v-col cols="12" sm="6" md="4" lg="3" xl="3"><span style="font-size: 0.875rem;" class="poppins-semibold">Índice cardiotorácico</span></v-col>
                      <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                        <v-text-field
                          v-model="indice_cardiotoracico"
                          :counter="255"
                          maxlength="255"
                          placeholder="Índice cardiotorácico"
                          hide-details
                          required
                          variant="outlined" flat
                          rounded="lg"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-col>
                <v-col cols="12">
                  <v-container fluid class="pa-0">
                    <v-row class="d-flex align-center" no-gutters>
                      <v-col cols="12" sm="6" md="4" lg="3" xl="3"><span style="font-size: 0.875rem;" class="poppins-semibold">Conclusiones</span></v-col>
                      <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                        <v-text-field
                          v-model="conclusiones"
                          :counter="255"
                          maxlength="255"
                          placeholder="Conclusiones"
                          hide-details
                          required
                          variant="outlined" flat
                          rounded="lg"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-col>
            </v-row>
    </v-container>
</template>