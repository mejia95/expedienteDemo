<template>
    <v-app >
      <Head title="Iniciar sesión"/>
      <v-container class="fill-height" fluid >
        <v-row>
          <v-col>
        <v-card
          elevation="0"
          class="mx-auto"
          :width="xs ? '85vw': '26rem'"
          rounded="xl"
        > 
          <v-card-text class=" pt-4 mt-3">
            <v-container>
              <v-row class="mx-6 text-center mb-5">
                <v-col style="font-size: 1.5rem;" class="poppins-bold">
                   <v-avatar color="blue">
                        E
                   </v-avatar>
                </v-col>
              </v-row>
              <v-row class="mx-6 text-center mt-n8">
                <v-col style="font-size: 1.5rem;" class="poppins-bold">
                   <span style="font-size: 0.95rem">Iniciar sesión</span>
                </v-col>
              </v-row>
              <v-row class="text-center mt-n4">
                <v-col  style="font-size: 0.9rem;">
                  <span class="text-medium-emphasis">Ingreso tu correo  para ingresar al sistema</span>
                </v-col>
              </v-row>
              <!-- MX, SM  -->
              <v-row class="mt-5"> 
                <v-col >
                  <span  style="font-size: 0.775rem;" class="poppins-semibold">Correo electrónico</span>
                    <v-text-field 
                        density="compact"
                      variant="outlined"
                      rounded="lg"
                      v-model="form.correo"
                      placeholder="correo electrónico"
                      >
                    </v-text-field>
                </v-col>
              </v-row>
             
              <v-row class="mx-sm-6 mx-md-6 mx-lg-6 mx-xl-6">
                <v-col>
                  <v-btn
                    @click="Logeo"
                    :loading="LoaderRequest"
                    class="text-none"
                    color="primary"
                    block
                  >
                    Ingresar
                  </v-btn>
                </v-col>
              </v-row>
        </v-container>
          </v-card-text>
        </v-card>
        </v-col>
        </v-row>
      </v-container>
    </v-app>
  </template >
  <script>
    import {ref,reactive} from 'vue';
    import { Link, router,Head } from '@inertiajs/vue3';
import axios from 'axios';
    export default{
        components:{
          
            Head
        },
        setup(){
            const form = reactive({
                correo: null,
                });
            const LoaderRequest = ref(false);
            const Logeo = async()=>{
                const url = `/login`;
                LoaderRequest.value = true;
                await router.post(url, form)
                LoaderRequest.value=false;
            }
            return{
                Logeo,
                form,
                LoaderRequest
            }
        }
    }
  </script>