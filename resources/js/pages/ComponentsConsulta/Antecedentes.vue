<script setup>
    import {ref,onMounted,provide,nextTick} from 'vue';
    import Alerta from '@/components/AppAlerta.vue';
    import { AplicacionStore } from '@/stores/Aplicacion.js';
    import { useGoTo } from 'vuetify';
    import { router, Head, usePage } from '@inertiajs/vue3';
    
    const date = '2023-11-30';
    const api = "/catalogos";
    const pacientes = "/pacientes";
    const MensajeAppAlerta = ref({
      MostrarAlerta: false,
      TipoAlerta: 0,
      MensajeAlerta: '',
      LoaderAlerta: false
    })
    const AlertaCallback = ref(null);
   
        onMounted(async ()=>{
          ConsultarAntecedente();
          await ObtenerAntecedentesPatologicos();
          await ConsultarToxicomania();
        })
        //Catalogos Enfermedades
        const goTo = useGoTo();
        const enfermedades_pestania = ref(1);
        const items = ref([
          { title: 'Click Me' },
        { title: 'Click Me' },
        { title: 'Click Me' },
        { title: 'Click Me 2' },
        ])
   
        const page = usePage()
        const id_consulta = page.props.id
        const store = AplicacionStore();
        const consulta_datos = ([]);
        const CatAntecedentesPatologicos = ref([]);
        const CatToxicomanias = ref([]);
        const nuevoAntecedente = ref(null);
        const nuevaToxicomania = ref(null);
        const antecedentesPP = ref(null);
        const toxicomaniaBusqueda = ref(null);
        const BanderaBusqueda = ref(0);
        const PermitirEdicionInformacion = ref(true);

        const EditarInfoAntecedentes = async()=>{
          enfermedades_pestania.value=1;
          

          if(!PermitirEdicionInformacion.value){
            await goTo('#contenedor-consulta', {});
            // MensajeAppAlerta.value.TipoAlerta = 1;
            // MensajeAppAlerta.value.MensajeAlerta = "Cualquier cambio no guardado en los antecedentes se descartará al salir de la edición.";
            // MensajeAppAlerta.value.MostrarAlerta = true;
            // AlertaCallback.value = "DeshacerCambiosEdicion";
            store.MostrarAlerta = true;
            store.TituloAlerta = "¿Estás seguro?";
            store.IconoAlerta = "mdi-help-circle";
			      store.MensajeAlerta = "Cualquier cambio no guardado en los antecedentes se descartará al salir de la edición." ;
            store.CallbackAlerta="DeshacerCambiosEdicion"; 
            PermitirEdicionInformacion.value = !PermitirEdicionInformacion.value ;
          }
          PermitirEdicionInformacion.value = !PermitirEdicionInformacion.value ;
        }
        const DeshacerCambiosEdicion = ()=>{
          PermitirEdicionInformacion.value = true;
          //MensajeAppAlerta.value.MostrarAlerta = false;
           store.MostrarAlerta = false;
         // MensajeAppAlerta.value.TipoAlerta = 0;
          AlertaCallback.value = null;
            store.CallbackAlerta=null;
        }

        const ConsultarAntecedente = async () => {
          const url = `/pacientes/${store.paciente._id}/antecedente`;
            //La busqueda de los antecedentes se modifico
 
          await axios.get(url).then((response)=>{
            consulta_datos.value = response.data;
            store.cirugias_hospitalizaciones = response.data.cirugias_hospitalizaciones;
            store.inmunizaciones = response.data.inmunizaciones;
            store.antecedentesPerPatologicos = response.data.antecedentesPerPatologicos;
            store.antecedentesNoPatologicos = response.data.antecedentesNoPatologicos;
            store.antecedentesGinecoObs = response.data.antecedentesGinecoObs;
            store.toxicomanias = response.data.toxicomanias ;
            store.alergias = response.data.alergias;
            store.cardiovascular = response.data.cardiovascular;
            store.respiratorio = response.data.respiratorio;
            store.NefroUrologico = response.data.NefroUrologico;
            store.Neurologico = response.data.Neurologico;
            store.Hematologicos = response.data.Hematologicos;
            store.Ginecologicos = response.data.Ginecologicos;
            store.Infectologicos = response.data.Infectologicos;
            store.Endocrinologicos = response.data.Endocrinologicos;
            store.Quirurgicos = response.data.Quirurgicos;
            store.Alergicos = response.data.Alergicos;
            store.SocioecEpide = response.data.SocioecEpide;
            store.AntecedentesHeredo = response.data.AntecedentesHeredo;
            store.seccion_antecedentes=true;
          }).catch((error)=>{
            console.log(error);
            store.seccion_antecedentes=false;
          });
          
        }
        const Cerrar = async() =>{
          //router.push("/pacientes");
          await goTo('#contenedor-consulta', {});
          PermitirEdicionInformacion.value=true;
        }

        //Enfermedades Metodos
        const ObtenerAntecedentesPatologicos = async ()=>{
          const url = `${api}/enfermedades-frecuentes`;
          await axios.get(url).then((response)=>{
            CatAntecedentesPatologicos.value= response.data;
          }).catch((error)=>{
            console.log(error);
          })
        }

        const buscarAntecedente = (valor)=>{
          var coincidencias = 0;
          CatAntecedentesPatologicos.value.forEach((item)=>{
            if(item.eNombre.toLowerCase() === valor.toLowerCase().trim()){
              coincidencias++;
            }
          })

          if(coincidencias<1){
            nuevoAntecedente.value = valor;
          }
        }

        const agregarValorAntecedentes = (valor)=>{
         // console.log("Estos son los antecedentes", valor);
          const url = `${api}/enfermedades-frecuentes/agregar`;
          axios.post(url,{enfermedad:valor}).then((response)=>{
           // console.log("ok");
            ObtenerAntecedentesPatologicos();
            store.antecedentesPerPatologicos.push(response.data.nueva_enfermedad);
            antecedentesPP.value.search="";
          }).catch((error)=>{
            if(error.response.status === 422){
                MensajeAppAlerta.value.TipoAlerta = 0;
                MensajeAppAlerta.value.MensajeAlerta = error.response.data.message;
                MensajeAppAlerta.value.MostrarAlerta = true;
                MensajeAppAlerta.value.LoaderAlerta = false;
                AlertaCallback.value = null;
              }
          });
        }
        
        const ConsultarToxicomania = async (valor)=>{
          const url = `${api}/toxicomanias`;
          await axios.get(url).then((response)=>{
            CatToxicomanias.value= response.data;
          }).catch((error)=>{
            console.log(error);
          })
        }

        const BuscarToxicomania = (valor)=>{
          var coincidenciasT = 0;
          CatToxicomanias.value.forEach((item)=>{
            if(item.etiqueta.toLowerCase() === valor.toLowerCase().trim()){
              coincidenciasT++;
            }
          });

          //console.log("Que traes en toxicomanias");
          if(coincidenciasT<1){
            nuevaToxicomania.value = valor;
          }
        }

        const agregarValorToxicomania = (valor)=>{
          //console.log("Estos son los antecedentes", valor);
          const url = `${api}/toxicomanias/agregar`;
          axios.post(url,{toxicomania:valor}).then(async (response)=>{
            //console.log("ok");
            ConsultarToxicomania();

            store.toxicomanias.push(response.data.nueva_toxicomania);
            toxicomaniaBusqueda.value.search="";
          }).catch((error)=>{
            if(error.response.status === 422){
                MensajeAppAlerta.value.TipoAlerta = 0;
                MensajeAppAlerta.value.MensajeAlerta = error.response.data.message;
                MensajeAppAlerta.value.MostrarAlerta = true;
                MensajeAppAlerta.value.LoaderAlerta = false;
                AlertaCallback.value = null;
              }
          });
        }

        const GuardarValor = async()=>{
          await goTo('#contenedor-consulta', {});
          // MensajeAppAlerta.value.TipoAlerta = 1;
          // MensajeAppAlerta.value.MensajeAlerta = "Va a actualizar los antecedentes del paciente. Asegúrese de que los datos sean precisos antes de proceder.";
          // MensajeAppAlerta.value.MostrarAlerta = true;
          // AlertaCallback.value = "GuardarDatos";
          store.EstadoAlerta = 1;
          store.MostrarAlerta = true ;
          store.TituloAlerta = "¿Estás seguro?";
          store.IconoAlerta = "mdi-help-circle";
			    store.MensajeAlerta = "Va a actualizar los antecedentes del paciente. Asegúrese de que los datos sean precisos antes de proceder." ;
          store.CallbackAlerta= "GuardarDatos"; 
        }

        const GuardarDatos = async() => {
          MensajeAppAlerta.value.LoaderAlerta = true;
          //console.log("guadar", store.Neurologico);
          const params ={
            //id_consulta : id_consulta,
            id_consulta: store.paciente._id,
            cirugias_hospitalizaciones : store.cirugias_hospitalizaciones,
            inmunizaciones : store.inmunizaciones,
            antecedentesPerPatologicos : store.antecedentesPerPatologicos,
            antecedentesNoPatologicos : store.antecedentesNoPatologicos,
            antecedentesGinecoObs : store.antecedentesGinecoObs,
            toxicomanias : store.toxicomanias,
            alergias : store.alergias,
            cardiovascular : store.cardiovascular,
            respiratorio : store.respiratorio,
            NefroUrologico : store.NefroUrologico,
            Neurologico : store.Neurologico,
            Hematologicos : store.Hematologicos,
            Ginecologicos : store.Ginecologicos,
            Infectologicos : store.Infectologicos,
            Endocrinologicos : store.Endocrinologicos,
            Quirurgicos : store.Quirurgicos,
            Alergicos : store.Alergicos,
            SocioecEpide : store.SocioecEpide,
            AntecedentesHeredo : store.AntecedentesHeredo,
           
          }; 
          const url = `${pacientes}/${id_consulta}/antecedentes`;
          console.log("Estos son los datos que se van a guardar", consulta_datos.value.id_paciente);
          if(consulta_datos.value.id_paciente){
            axios.put(`${url}/edit`,params).then(async(response)=>{
             await ConsultarAntecedente();
              store.seccion_antecedentes = true;
              store.EstadoAlerta = 2;
              store.MostrarAlerta = true ;
              store.TituloAlerta = "Éxito";
              store.IconoAlerta = "mdi-check-circle";
              store.MensajeAlerta = response.data.msg ;
              store.CallbackAlerta= null; 
              
            }).catch((error)=>{
              if(error.response.status === 422){
                store.seccion_antecedentes = false;
                store.EstadoAlerta = 0;
                store.CallbackAlerta=null; 				
                store.TituloAlerta = "Error";
                store.IconoAlerta = "mdi-close-circle";
				        store.MensajeAlerta = error.response.data.message;
              }
            });
          }else{
            axios.post(url,params).then(async(response)=>{
              await ConsultarAntecedente();
              store.MostrarAlerta = true ;
              store.TituloAlerta = "Éxito";
              store.IconoAlerta = "mdi-check-circle";
              store.MensajeAlerta = response.data.msg ;
              store.CallbackAlerta= null; 
              store.seccion_antecedentes = true;
              
            }).catch((error)=>{
              if(error.response.status === 422){
                 store.CallbackAlerta=null; 				
                store.TituloAlerta = "Error";
                store.IconoAlerta = "mdi-close-circle";
				        store.MensajeAlerta = error.response.data.message;
                store.seccion_antecedentes = false;
              }
            });
          }
        }

    
        provide('FuncionesPadre',{GuardarDatos,DeshacerCambiosEdicion});

        defineExpose({
          ConsultarAntecedente,
          enfermedades_pestania,
          BanderaBusqueda
        });
</script>
<template>
    <v-toolbar color="white" class="pa-0 border-sm rounded-t-lg">
        <v-row>
            <v-col class="d-flex align-center">
                <v-toolbar-title class="ps-5 text-primary">
                  <v-icon size="18">mdi-file-document-edit</v-icon>
                  <span class="mx-1 poppins-semibold"  style="font-size: 1.1rem;">
                    Antecedentes 
                  </span>
                 <!--<v-avatar size="25" rounded="pill" color="primary-darken-1" class="mx-2">
                        <v-icon size="15">
                          {{store.seccion_antecedentes ? 'mdi-check-circle' : 'mdi-content-save-alert' }}
                        </v-icon>
                    </v-avatar>-->
                </v-toolbar-title>
            </v-col>
        </v-row>
       
   </v-toolbar>
    <v-container class="rounded-b-xl px-4 px-sm-6 px-md-6 px-lg-6 px-xl-6 border-sm" fluid>
      <v-row class="mt-0">
        <v-col cols="12" sm="12" md="8" lg="8" xl="8">
          <v-tabs density="compact"  color="primary" v-model="enfermedades_pestania" >
            <v-tab :value="1" :variant="enfermedades_pestania == 1 ? 'elevated' : 'tonal'" class="text-none mr-2" rounded="t-lg" hide-slider>
              <v-icon size="13">mdi-file-document-outline</v-icon>
              <span class="mx-2" style="font-size: 0.775rem;">Antecedentes</span>
            </v-tab>
            <v-tab :value="2" :variant="enfermedades_pestania == 2 ? 'elevated' : 'tonal'" class="text-none mr-2" rounded="t-lg" hide-slider>
              <v-icon size="13">mdi-heart-pulse</v-icon>
              <span class="mx-2" style="font-size: 0.775rem;">Enfermedades</span>
            </v-tab>
          </v-tabs>
          
        </v-col>
        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="d-flex justify-end">
          <v-btn :variant="PermitirEdicionInformacion ? 'outlined':'tonal'" color="primary" @click="EditarInfoAntecedentes">
            <v-icon size="13">{{PermitirEdicionInformacion ? 'mdi-pencil':'mdi-close'}}</v-icon>
            <span class="mx-2 text-none">{{ PermitirEdicionInformacion ? 'Editar antecedentes':'Cerrar modificación'}}</span>
            
          </v-btn>
        </v-col>
        <v-alert
          v-if="!PermitirEdicionInformacion"
              class="my-1 mx-4"
              color="primary"
              variant="tonal"
              density="compact"
              rounded="lg"
              
              >
                  <v-icon size="15">mdi-information</v-icon>
              <span style="font-size: 0.875rem;" class="mx-2">Recuerde guardar cada sección para evitar la pérdida de información en caso de imprevistos.</span>
          </v-alert>
        
      </v-row>
      <!--<v-row>
        <v-col class="text-primary">
          <span style="font-size: 1.025rem;">Antecedentes</span>
        </v-col>
      </v-row>-->
      <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 mt-2" v-if="enfermedades_pestania == 1">
        <v-col cols="12">
          <span style="font-size: 0.875rem;" class="poppins-semibold">Antecedentes heredofamiliares</span>
          
            <v-textarea
              :readonly="PermitirEdicionInformacion"
              v-model="store.AntecedentesHeredo"
              placeholder="Antecedentes heredofamiliares"
              required
              auto-grow
              row-height="4"
              rows="3"
              :counter="255"
              maxlength="255"
              variant="outlined"
              flat 
              rounded="lg"
              color="primary"
            ></v-textarea>
        </v-col>
        <v-col cols="12">
          <span style="font-size: 0.875rem;" class="poppins-semibold">Antecedentes personales no patológicos</span>  
          <v-textarea 
            :readonly="PermitirEdicionInformacion"
            v-model="store.antecedentesNoPatologicos" 
            placeholder="Antecedentes personales no patológicos"
            color="primary" 
            auto-grow  
            row-height="4"
            rows="3"
            variant="outlined" 
            flat  
            :counter="255" maxlength="255" rounded="lg"></v-textarea>
        </v-col>
        <v-col >
          <span style="font-size: 0.875rem;" class="poppins-semibold">Toxicomanías</span>
          <v-autocomplete :readonly="PermitirEdicionInformacion" hide-details="auto" ref="toxicomaniaBusqueda" color="primary"  @update:search="BuscarToxicomania" v-model="store.toxicomanias" multiple :items="CatToxicomanias" item-value="valor" item-title="etiqueta" variant="outlined" flat rounded="lg" placeholder="Seleccione una o varias toxicomanías" :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"  >="PermitirEdicionInformacion"
            <template v-slot:no-data>
                <v-list-item @click="agregarValorToxicomania(nuevaToxicomania)">
                  <v-list-item-title>Agregar - {{nuevaToxicomania}}</v-list-item-title>
                </v-list-item>
              </template>
          </v-autocomplete>
        </v-col>
        <v-col  cols="12">
          <span style="font-size: 0.875rem;" class="poppins-semibold">Antecedentes personales patológicos</span>
          <v-autocomplete :readonly="PermitirEdicionInformacion" hide-details="auto" ref="antecedentesPP"  color="primary"  variant="outlined" flat  @update:search="buscarAntecedente" v-model="store.antecedentesPerPatologicos" multiple :items="CatAntecedentesPatologicos" item-value="eID" item-title="eNombre" rounded="lg" placeholder="Seleccione uno o varios antecedentes" :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"  >="PermitirEdicionInformacion"
            <template v-slot:no-data>
                <v-list-item @click="agregarValorAntecedentes(nuevoAntecedente)">
                  <v-list-item-title>Agregar - {{nuevoAntecedente}}</v-list-item-title>
                </v-list-item>
              </template>
          </v-autocomplete>
        </v-col>
        <v-col cols="12">
            <span style="font-size: 0.875rem;" class="poppins-semibold">Cirugías y hospitalizaciones</span>
            <v-textarea
            :readonly="PermitirEdicionInformacion"
            v-model="store.cirugias_hospitalizaciones"
            placeholder="Cirugías y hospitalizaciones"
            required
            auto-grow
            row-height="4"
            rows="3"
            :counter="255"
            maxlength="255"
            variant="outlined"
            flat 
            rounded="lg"
            color="primary"
            ></v-textarea>
        </v-col>
        <v-col cols="12">
            <span style="font-size: 0.875rem;" class="poppins-semibold">Inmunizaciones</span>
            <v-textarea
            :readonly="PermitirEdicionInformacion"
            v-model="store.inmunizaciones"
            :counter="255"
            placeholder="Inmunizaciones"
            required
            auto-grow
            row-height="4"
            maxlength="255"
            rows="3"
            rounded="lg"
            variant="outlined" 
            flat 
            
            color="primary"
            ></v-textarea>
        </v-col>
        <v-col  cols="12">
          <span style="font-size: 0.875rem;" class="poppins-semibold">Antecedentes gineco-obstétricos</span>
          <v-textarea :readonly="PermitirEdicionInformacion" v-model="store.antecedentesGinecoObs " color="primary" auto-grow :counter="255" maxlength="255" rounded="lg" variant="outlined" flat ></v-textarea>
        </v-col>

        <v-col cols="12">
          <span style="font-size: 0.875rem;" class="poppins-semibold">Alergias</span>
          <v-textarea :readonly="PermitirEdicionInformacion"  v-model="store.alergias"  color="primary" rounded="lg" auto-grow placeholder="Ingrese las alergias del paciente" :counter="255" maxlength="255" variant="outlined" flat ></v-textarea>
        </v-col>
      </v-row>
      <!--<v-row>
        <v-col class="text-primary">Enfermedades</v-col>
      </v-row>-->
      <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4" v-if="enfermedades_pestania == 2">
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Cardiovascular (CV)</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                <v-text-field
                  v-model="store.cardiovascular"
                  :counter="255"
                  placeholder="CV"
                  required
                  variant="outlined" 
                  flat 
                  
                  rounded="lg"
                  :readonly="PermitirEdicionInformacion"
                  maxlength="255"
                  auto-grow
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Respiratorio</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                <v-text-field
                  v-model="store.respiratorio"
                  :counter="255"
                  placeholder="Respiratorio"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  :readonly="PermitirEdicionInformacion"
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
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Nefro-urológico</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
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
                  :readonly="PermitirEdicionInformacion"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Neurológico</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
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
                  :readonly="PermitirEdicionInformacion"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Hematológicos</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                <v-text-field
                  v-model="store.Hematologicos"
                  :counter="255"
                  placeholder="Hematológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  :readonly="PermitirEdicionInformacion"
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
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Ginecológicos</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                <v-text-field
                  v-model="store.Ginecologicos"
                  :counter="255"
                  placeholder="Ginecológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  :readonly="PermitirEdicionInformacion"
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
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Infectológicos</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                <v-text-field
                  v-model="store.Infectologicos"
                  :counter="255"
                  placeholder="Infectológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  
                  rounded="lg"
                  :readonly="PermitirEdicionInformacion"
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
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Endocrinológicos</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                <v-text-field
                  v-model="store.Endocrinologicos"
                  :counter="255"
                  placeholder="Endocrinológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  :readonly="PermitirEdicionInformacion"
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
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Quirúrgicos</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                <v-text-field
                  v-model="store.Quirurgicos"
                  :counter="255"
                  placeholder="Quirúrgicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  :readonly="PermitirEdicionInformacion"
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
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Alérgicos</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
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
                  :readonly="PermitirEdicionInformacion"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
        <v-col cols="12">
          <v-container fluid class="pa-0">
            <v-row class="d-flex align-center" no-gutters>
              <v-col cols="12" sm="6" md="4" lg="3" xl="3">
                <span style="font-size: 0.875rem;" class="poppins-semibold">Socioeconómicos/Epidemiológicos</span>
              </v-col>
              <v-col cols="12" sm="6" md="8" lg="9" xl="9">
                <v-text-field
                  v-model="store.SocioecEpide"
                  :counter="255"
                  placeholder="Socioeconómicos/Epidemiológicos"
                  hide-details
                  required
                  variant="outlined" 
                  flat 
                  :readonly="PermitirEdicionInformacion"
                  maxlength="255"
                  rounded="lg"
                  color="primary"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
      </v-row>
      <v-row class="d-flex align-center ma-2" v-if="!PermitirEdicionInformacion">
        <v-col cols="12" class="d-flex justify-end">
          <v-btn class="mx-2" color="primary" variant="text" @click="Cerrar()">Cancelar</v-btn>
          <v-btn class="mx-2" color="primary" @click="GuardarValor()">Guardar</v-btn>
        </v-col>
      </v-row>  
    </v-container>
    <Alerta :callback="AlertaCallback" v-if="store.SeccionConsulta == 1"></Alerta>
</template>