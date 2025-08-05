<script setup>

    import { AplicacionStore } from '@/stores/Aplicacion';

    const store = AplicacionStore();

    // const cardiovascular = ref(null); 
    // const respiratorio = ref(null);
    // const NefroUrologico = ref(null);
    // const Neurologico = ref(null);
    // const Hematologicos = ref(null);
    // const Ginecologicos = ref(null);
    // const Infectologicos = ref(null);
    // const Endocrinologicos = ref(null);
    // const Quirurgicos = ref(null);
    // const Alergicos = ref(null);
    // const SocioecEpide = ref(null);

</script>
<template>
    <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4" >
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                 <span style="font-size: 0.875rem;" class="poppins-semibold">Cardiovascular (CV)</span>
                <v-text-field
                  v-model="store.cardiovascular"
                  :counter="255"
                  placeholder="CV"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  maxlength="255"
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                 <span style="font-size: 0.875rem;" class="poppins-semibold">Respiratorio</span>
                <v-text-field
                  v-model="store.respiratorio"
                  :counter="255"
                  placeholder="Respiratorio"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  maxlength="255"
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                 <span style="font-size: 0.875rem;" class="poppins-semibold">Nefro-urológico</span>
                <v-text-field
                  v-model="store.NefroUrologico"
                  :counter="255"
                  placeholder="Nefro-urológico"
                  hide-details
                  required
                  maxlength="255"
                  variant="outlined" 
                  flat                   
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Neurológico</span>
                <v-text-field
                  v-model="store.Neurologico"
                  :counter="255"
                  placeholder="Neurológico"
                  hide-details
                  maxlength="255"
                  required
                  variant="outlined" 
                  flat                   
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Hematológicos</span>
                <v-text-field
                  v-model="store.Hematologicos"
                  :counter="255"
                  placeholder="Hematológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  maxlength="255"
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                 <span style="font-size: 0.875rem;" class="poppins-semibold">Ginecológicos</span>
                <v-text-field
                  v-model="store.Ginecologicos"
                  :counter="255"
                  placeholder="Ginecológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  maxlength="255"
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Infectológicos</span>
                <v-text-field
                  v-model="store.Infectologicos"
                  :counter="255"
                  placeholder="Infectológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat                   
                  rounded="lg"
                  maxlength="255"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Endocrinológicos</span>
                <v-text-field
                  v-model="store.Endocrinologicos"
                  :counter="255"
                  placeholder="Endocrinológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  maxlength="255"
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Quirúrgicos</span>
                <v-text-field
                  v-model="store.Quirurgicos"
                  :counter="255"
                  placeholder="Quirúrgicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  maxlength="255"
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Alérgicos</span>
                <v-text-field
                  v-model="store.Alergicos"
                  :counter="255"
                  placeholder="Alérgicos"
                  hide-details
                  maxlength="255"
                  required
                  variant="outlined" 
                  flat                   
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Socioeconómicos/Epidemiológicos</span>
                <v-text-field
                  v-model="store.SocioecEpide"
                  :counter="255"
                  placeholder="Socioeconómicos/Epidemiológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  maxlength="255"
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
      </v-row> 
</template>