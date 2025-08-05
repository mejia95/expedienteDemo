<script setup>
  const api = "/catalogos";
  const api_consulta = "/consulta";
  import {ref,onMounted,defineProps,provide} from 'vue';
  import { useGoTo } from 'vuetify'
  import { AplicacionStore } from '@/stores/Aplicacion.js';
  import Alerta from '@/components/AppAlerta.vue';
  import { router, Head, usePage } from '@inertiajs/vue3';
  const goTo = useGoTo()
  const store = AplicacionStore();
    const page = usePage()
    const id_consulta = page.props.id
  //const seleccion_clinica = route.params.seleccion_clinica;
  const MensajeAppAlerta = ref(null);
  const AlertaCallback = ref(null);
  //const plan_terapeutico = ref(null);
  //const ordenes_estudios = ref(null);
  const medicamentos = ref([]);
  const contador = ref(1);
  //const arreglo_medicamento = ref([]);
  const id_medicamento = ref([]);
  const nombre_medicamento = ref(null);
  const dosis_medicamento = ref(null);
  const dosis = ref([]);
  const mostrar_file = ref(false);
  const valid = ref(false);
  const consulta_tratamiento = ref([]);
  const emit = defineEmits();
  const terapeutico = ref(null);
  const DosisRules = ref([
      v => /^[a-zA-Z0-9 -/().,:;*áéíóúÁÉÍÓÚ]+$/.test(v) || 'Debe contener letras y un números',
  ]);
  const MedicamentoRules = ref([
    v => !!v || 'El campo es requerido',
  ]);
  const form = ref(null);

  /*const props = defineProps({
    ref1: Object,
  })*/

  onMounted(async ()=>{   
  })

 
  const submitForm = () => {
    if (v.value) {  
      submitted.value = true;
    } else {
      submitted.value = false;
    }
  }

  const validate = async () => {
        const { valid } = await form.value.validate()

        if (valid) {
            AgregarLista();
        }
  }

  const ConsultaMedicamentos = () =>{
    const url = `${api}/medicamentos/consultorio`;
    axios.get(url).then((response)=>{
        medicamentos.value = response.data[0].medicamentos;
    }).catch((error)=>{
        return error.response.status==419 || error.response.status==420 || error.response.status==401 || error.response.status==409 ? location.reload():console.log(error);
    });
  }


  const AgregarLista = () => {  
    id_medicamento.value.push( nombre_medicamento.value );
    dosis.value.push(dosis_medicamento.value);
    //console.log("Este es el arreglo", store.arreglo_medicamento);
    store.arreglo_medicamento.push({
        id : nombre_medicamento.value,
        dosis : dosis_medicamento.value
    })
  //  console.log("Este es el arreglo", store.arreglo_medicamento);
    mostrar_file.value = false;
    nombre_medicamento.value = null;
    dosis_medicamento.value = null;
    //
  }

  const BorrarRegistroArray = (index) => {
    store.arreglo_medicamento.splice(index,1);
    id_medicamento.value.splice(index,1);
    dosis.value.splice(index,1);
  }

  const AlertaGuardar = async () => {
    await goTo('#contenedor-consulta', {});
    store.EstadoAlerta = 1;
    store.MostrarAlerta = true;
    store.TituloAlerta = "¿Estás seguro?";
    store.IconoAlerta = "mdi-help-circle";
		store.MensajeAlerta = "La información que ha ingresado se guardará. Asegúrese de que todos los datos sean correctos antes de continuar." ;
    store.CallbackAlerta="AgregarTratamiento"; 
  }

  const AgregarTratamiento = async ()  =>{   
    const params ={
            id_consulta : id_consulta,
            plan_terapeutico : store.plan_terapeutico,
            ordenes_estudios : store.ordenes_estudios,
            indicaciones_receta : store.indicaciones_receta,
            arreglo_medicamento : store.arreglo_medicamento,
            edad : store.paciente.edad,
            consultorio : store.seleccion_clinica,
    }

    if(store.seleccion_clinica != null  && store.peso && store.altura && store.temperatura && store.paciente.edad ){
        
        const url_tratamiento = `${api_consulta}/${id_consulta}/tratamiento`;
        axios.post(url_tratamiento,params).then(async(response)=>{
          await Importar_PDF();
        }).catch((error)=>{
                store.EstadoAlerta = 2;
                store.MostrarAlerta = true;
                store.TituloAlerta = "Error";
                store.IconoAlerta = "mdi-close-circle";
                store.MensajeAlerta = error.response ? error.response.data.message : 'Ha ocurrido un error';
                store.CallbackAlerta=null; 
          
          });
      store.opcion_faltante = 0;
    }else{
      const mensaje = !store.seleccion_clinica ? 'CLÍNICA' : !store.peso ? 'PESO' : !store.altura ? 'ALTURA' : !store.temperatura ? 'TEMPERATURA' : null;
      store.opcion_clinica_faltante = !store.seleccion_clinica ? 1 : 0;
      store.opcion_faltante = !store.peso ? 1 : !store.altura ? 1 : !store.temperatura ? 1 : 0;
      terapeutico.value.validate();
      store.EstadoAlerta = 0;
      store.MostrarAlerta = true;
      store.TituloAlerta = "Error";
      store.IconoAlerta = "mdi-close-circle";
      store.MensajeAlerta = "Falta agregar el campo de: "+ mensaje;
      store.CallbackAlerta=null;

    }    
  }

  const ConsultarTratamiento = () => {
    var url = `${api_consulta}/${id_consulta}/tratamiento`;
    axios.get(url).then(async(response)=>{
      store.plan_terapeutico = response.data.tratamiento ? response.data.tratamiento.plan_terapeutico :null;
      store.ordenes_estudios = response.data.tratamiento ? response.data.tratamiento.ordenes_estudios :null;
      store.indicaciones_receta = response.data.tratamiento ? response.data.tratamiento.indicaciones_receta :null;
      store.arreglo_medicamento = response.data.tratamiento ? response.data.tratamiento.arreglo_medicamento :[];
    }).catch((error)=>{
      
    });
  }


  const Cerrar = () =>{
    router.push("/pacientes");
  }

  const Importar_PDF = () => {
    router.get(`/pacientes/resumen/${store.paciente._id}/consulta/${id_consulta}`);
	}



  provide('FuncionesPadre',{AgregarTratamiento,Importar_PDF});

  defineExpose({
    ConsultaMedicamentos
  });

</script>
<template>
    <v-toolbar color="white" class="pa-0 border-sm rounded-t-lg">
        <v-row>
            <v-col class="d-flex align-center">
                <v-toolbar-title class="ps-5 text-primary-darken-3">
                  <v-icon size="18">mdi-clipboard-plus-outline</v-icon>
                  <span class="mx-1 poppins-semibold" style="font-size: 1.1rem;">
                    Tratamiento 
                  </span>  
                </v-toolbar-title>
            </v-col>
        </v-row>
   </v-toolbar>
    <v-container class="rounded-b-xl px-4 px-sm-6 px-md-6 px-lg-6 px-xl-6 border" fluid>
      <v-row class="px-4 mt-5">
          <v-col class="d-flex align-center" cols="12" sm="6" md="4" lg="3" xl="3">
            <span style="font-size: 0.875rem;" class="poppins-semibold">Plan terapéutico </span>
          </v-col>
          <v-col class="d-flex align-center" cols="12" sm="6" md="8" lg="9" xl="9">
            <v-textarea v-model="store.plan_terapeutico" color="primary" variant="outlined" flat density="compact" ref="terapeutico"  :counter="255"
            maxlength="255" auto-grow placeholder="Ingrese el plan terapéutico" rounded="lg"></v-textarea>
          </v-col>
        </v-row>
        <v-row class="px-4 mt-5">
          <v-col class="d-flex align-center" cols="12" sm="6" md="4" lg="3" xl="3">
            <span style="font-size: 0.875rem;" class="poppins-semibold">Órdenes de estudios</span>
          </v-col>
          <v-col class="d-flex align-center" cols="12" sm="6" md="8" lg="9" xl="9">
            <v-textarea v-model="store.ordenes_estudios" color="primary" variant="outlined" flat density="compact" :counter="255"
            maxlength="255"  auto-grow placeholder="Ingrese los órdenes de estudios" rounded="lg"></v-textarea>
          </v-col>
        </v-row> 
        <v-row class="px-4 mt-5">
          <v-col class="d-flex align-center" cols="12" sm="6" md="4" lg="3" xl="3">
            <span style="font-size: 0.875rem;" class="poppins-semibold">Indicaciones</span>
          </v-col>
          <v-col class="d-flex align-center" cols="12" sm="6" md="8" lg="9" xl="9">
            <v-textarea v-model="store.indicaciones_receta" color="primary" variant="outlined" flat density="compact" :counter="255"
            maxlength="255"  auto-grow placeholder="Proporcione las indicaciones que se le deben dar al paciente" rounded="lg"></v-textarea>
          </v-col>
        </v-row> 
        <v-row>
          <v-col>
            <span class="text-primary">
              Medicamento
            </span> 
          </v-col>
        </v-row>
        <v-row>
          <v-col v-if="!mostrar_file">
            <v-btn class="text-none" color="primary" variant="tonal" rounded="lg" @click="mostrar_file = true" >
                <v-icon size="15">mdi-plus-circle-outline</v-icon>
                <span class="mx-2" style="font-size: 0.825rem;">Agregar medicamento</span>
              </v-btn>
          </v-col>
          <v-col v-else class="d-flex justify-end">
            <v-btn class="text-none"  color="primary" variant="tonal" rounded="lg" @click="mostrar_file = false;nombre_medicamento=null;dosis_medicamento=null;">
              <v-icon size="15">mdi-close</v-icon>
              <span class="mx-2">Cancelar medicamento</span>
            </v-btn>
          </v-col>
        </v-row>
      <v-form ref="form">
      <v-row v-if="mostrar_file == true">
        <v-col cols="12" >
          <v-container fluid class="pa-0">
              <v-row >
                <v-col cols="12">
                  <v-table>
                    <thead class="rounded-lg bg-primary-lighten-5">
                      <tr>
                        <th class="poppins-semibold" style="font-size: 0.825rem;">Medicamento</th>
                        <th class="poppins-semibold" style="font-size: 0.825rem;">Dosis</th>
                        <th class="poppins-semibold" style="font-size: 0.825rem;"> </th>
                      </tr>
                    </thead>  
                    <tbody>
                      <tr>
                        <td>
                          <v-autocomplete
                            class="my-4"
                            :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" 
                            v-model="nombre_medicamento"
                            variant="outlined"
                            rounded="lg"
                            flat
                            color="primary"
                            placeholder="Ingrese el nombre del medicamento (ej. Paracetamol)"
                            :items="medicamentos"
                            :item-value="numero" 
                            :item-title="item => `${item.farmacos} - ${item.forma_farmaceutica}`"
                            :rules="MedicamentoRules"          
                          ></v-autocomplete>
                        </td>
                        <td class="pa-0">
                                <v-text-field
                                  v-model="dosis_medicamento"
                                :counter="255"
                                placeholder="Ingrese la dosis y frecuencia (ej. 500 mg cada 8 horas)"
                                variant="outlined"
                                class="my-2"
                                ></v-text-field>
                          
                        </td>
                        <td class="pa-0 d-flex justify-center mt-8">
                                <v-btn rounded="lg" density="compact"  icon="mdi-plus" color="primary-lighten-2" @click="validate()"></v-btn>
                              
                        </td>
                      </tr>
                    </tbody>
                  </v-table>  
                </v-col>
              </v-row>
          </v-container>
        </v-col>        
      </v-row>
    </v-form>
      <v-row v-if="store.arreglo_medicamento.length ">
        <v-col>
          <v-table class="border-thin rounded-lg">
            <thead  class="rounded-lg bg-primary-lighten-5">
              <tr>
                <th class="poppins-semibold" style="font-size: 0.875rem;">Medicamento</th>
                <th class="poppins-semibold" style="font-size: 0.875rem;">Dosis recetada</th>
                <th class="poppins-semibold" style="font-size: 0.875rem;"> </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(arreglo_medicamento,index) in store.arreglo_medicamento" :key="index">
                  <td><span style="font-size: 0.825rem;">{{arreglo_medicamento.id}}</span></td>
                  <td><span style="font-size: 0.825rem;">{{ arreglo_medicamento.dosis }}</span></td>
                  <td>
                      <v-btn variant="text" icon="mdi-delete" style="color:#e74c3c ;" @click="BorrarRegistroArray(index)"></v-btn>
                  </td>
              </tr>
            </tbody>
          </v-table>
        </v-col>
      </v-row>
      <v-divider></v-divider>
      <v-row class="d-flex align-center ma-2">
        <v-col cols="12" class="d-flex justify-end">
          <v-btn class="mx-2" color="primary" variant="text" @click="Cerrar()">Cancelar</v-btn>
          <v-btn class="mx-2" color="primary" :disabled="mostrar_file != false" @click="AlertaGuardar()">Finalizar</v-btn>
        </v-col>
      </v-row>
    </v-container>
    <Alerta ref="MensajeAppAlerta" :callback="AlertaCallback" v-if="store.SeccionConsulta == 4"></Alerta>
</template>