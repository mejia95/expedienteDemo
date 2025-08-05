<template>
    <v-app >
      <Head title="Registro Médico Pasante"/>
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
                   <span style="font-size: 0.95rem">Solicitud de acceso</span>
                </v-col>
              </v-row>
              <v-row class="text-center mt-n4">
                <v-col  style="font-size: 0.9rem;">
                  <span class="text-medium-emphasis">Ingresa tu correo para validar tu acceso</span>
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
                    
                    :loading="LoaderPeticion"
                    @click="ValidacionCorreoAcceso"
                    class="text-none"
                    color="primary"
                    block
                  >
                    Verificar
                  </v-btn>
                </v-col>
              </v-row>
        </v-container>
          </v-card-text>
        </v-card>
        </v-col>
        </v-row>
      </v-container>
      <AppAlerta/>
    </v-app>
  </template >
  <script>
    import {ref,reactive,provide,onMounted} from 'vue';
    import { AppStore } from '@/stores/AppStore';
    import { Link, router, Head } from '@inertiajs/vue3';
    
    import AppAlerta from '@/components/AppAlerta.vue';
    import { useDisplay } from 'vuetify';
    export default{
        props:{
          mensaje:{
            type:String,
            default:null,
          }
        },
        components:{
            AppAlerta,
            Head
        },
        setup(props){

            onMounted(()=>{
              if(props.mensaje){
                  StoreApp.MostrarAlerta = true;
                  StoreApp.CallbackAlerta="RedireccionarSelf";  
                  StoreApp.MensajeAlerta = props.mensaje;
                  StoreApp.EstadoAlerta = 2;
                  StoreApp.TituloAlerta = "Exito";
                  StoreApp.IconoAlerta = "mdi-check-circle";
              }
            });


            const {xs} = useDisplay();
            const StoreApp = AppStore();
            const LoaderPeticion = ref(false);
            const form = reactive({
                correo: null,
                })
            const ValidacionCorreoAcceso = async ()=>{
                const url = `/registro_mpss`;
                LoaderPeticion.value= true;
                var mensaje_response = null;
                await axios.post(url,{correo:form.correo}).then((response)=>{
                  location.href=response.data.redirectTo;
                }).catch((error)=>{
                  console.log(error)
                  mensaje_response = error.response.data.message;
                  StoreApp.MostrarAlerta = true;
                  StoreApp.CallbackAlerta=null;  
                  StoreApp.EstadoAlerta = 2;
                  StoreApp.MensajeAlerta = mensaje_response ?? "Ha ocurrido un error";
                  StoreApp.TituloAlerta = "Aviso";
                  StoreApp.IconoAlerta = "mdi-alert-circle";
                  LoaderPeticion.value= false;
                });
            }

            const RedireccionarSelf = ()=>{
              location.href="/registro_mpss";
            }

            provide('FuncionesPadre',{RedireccionarSelf})
            return{
              ValidacionCorreoAcceso,
                form,xs,LoaderPeticion
            }
        }
    }
  </script>