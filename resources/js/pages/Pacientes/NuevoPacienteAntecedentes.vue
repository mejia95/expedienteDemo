<script setup>
    import { ref, onMounted, computed, provide } from 'vue';
    import { AplicacionStore } from '@/stores/Aplicacion';
    import { router, Head, usePage } from '@inertiajs/vue3';
    import axios from 'axios';
    import Alerta from '@/components/AppAlerta.vue';


    const apiCatalogos = "/catalogos";
    
    const page = usePage()
    const id_paciente = page.props.id
    const store = AplicacionStore();
    const nuevaToxicomania = ref(null);
    const nuevoAntecedente = ref(null);
    const CatToxicomanias = ref([]);
    const toxicomaniaBusqueda = ref(null);
    const CatAntecedentesPatologicos = ref([]);
    const antecedentesPP = ref(null);
    const MensajeAppAlerta = ref(null);
    const AlertaCallback = ref(null);
    
    onMounted(async() => {
        await ConsultarToxicomania();
        await ObtenerAntecedentesPatologicos();
        await ConsultarAntecedente();
       // console.log("id paciente", props.id_paciente);
    });

    const ConsultarAntecedente = async () => {
            const datos = {
              params: {
                id_consulta: store.paciente._id
              }
            };
            
          const url = `/pacientes/${id_paciente}/antecedente`;
            //La busqueda de los antecedentes se modifico
            
          await axios.get(url,datos).then((response)=>{
           // consulta_datos.value = response.data;
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

    const ConsultarToxicomania = async (valor)=>{
        const url = `${apiCatalogos}/toxicomanias`;
        await axios.get(url).then((response)=>{        
            CatToxicomanias.value= response.data;
        }).catch((error)=>{
            console.log(error);
        })
    }

    const ObtenerAntecedentesPatologicos = async ()=>{
          const url = `${apiCatalogos}/enfermedades-frecuentes`;
          await axios.get(url).then((response)=>{
            CatAntecedentesPatologicos.value= response.data;
          }).catch((error)=>{
            console.log(error);
          })
    }
    
        const agregarValorToxicomania = (valor)=>{
          const url = `${apiCatalogos}/toxicomanias/agregar`;
          axios.post(url,{toxicomania:valor}).then(async (response)=>{
          ConsultarToxicomania();

            store.toxicomanias.push(response.data.nueva_toxicomania);
            toxicomaniaBusqueda.value.search="";
          }).catch((error)=>{
            // if(error.response.status === 422){
            //     MensajeAppAlerta.value.StoreApp.TipoAlerta = 0;
            //     MensajeAppAlerta.value.StoreApp.MensajeAlerta = error.response.data.message;
            //     MensajeAppAlerta.value.StoreApp.MostrarAlerta = true;
            //     MensajeAppAlerta.value.StoreApp.oaderAlerta = false;
            //     AlertaCallback.value = null;
            //  }
          });
        }

    const BuscarToxicomania = (valor)=>{        
        var coincidenciasT = 0;
        console.log("Buscando toxicomania: ", valor);
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
    const buscarAntecedente = (valor) => {
        var coincidencias = 0;
        console.log("Buscando antecedente: ", valor);
        CatAntecedentesPatologicos.value.forEach((item) => {
            if (item.eNombre.toLowerCase() === valor.toLowerCase().trim()) {
                coincidencias++;
            }
        });
        if (coincidencias < 1) {
            nuevoAntecedente.value = valor;
        }
     }

    const agregarValorAntecedentes = (valor)=>{
        const url = `${apiCatalogos}/enfermedades-frecuentes/agregar`;
        axios.post(url,{enfermedad:valor}).then((response)=>{
           // console.log("ok");
            ObtenerAntecedentesPatologicos();
            store.antecedentesPerPatologicos.push(response.data.nueva_enfermedad);
            antecedentesPP.value.search="";
        }).catch((error)=>{
            // if(error.response.status === 422){
            //     MensajeAppAlerta.value.StoreApp.TipoAlerta = 0;
            //     MensajeAppAlerta.value.StoreApp.MensajeAlerta = error.response.data.message;
            //     MensajeAppAlerta.value.StoreApp.MostrarAlerta = true;
            //     MensajeAppAlerta.value.StoreApp.LoaderAlerta = false;
            //     AlertaCallback.value = null;
            //   }
        });
    }
   provide('FuncionesPadre',{});
</script>
<template>
    <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 ">
        <v-col cols="12">
            <span style="font-size: 0.875rem;" class="poppins-semibold">Antecedentes heredofamiliares</span>                            
                <v-textarea                                
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
            <span style="font-size: 0.875rem;" class="poppins-semibold">Toxicomanías </span>
            <v-autocomplete hide-details="auto" color="primary" ref="toxicomaniaBusqueda" @update:search="BuscarToxicomania" v-model="store.toxicomanias" multiple :items="CatToxicomanias" item-value="valor" item-title="etiqueta" variant="outlined" flat rounded="lg" placeholder="Seleccione una o varias toxicomanías" :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"  >
                <template v-slot:no-data>
                    <v-list-item @click="agregarValorToxicomania(nuevaToxicomania)">
                    <v-list-item-title>Agregar - {{nuevaToxicomania}}</v-list-item-title>
                    </v-list-item>
                </template>
            </v-autocomplete>
            </v-col>
            <v-col  cols="12">
            <span style="font-size: 0.875rem;" class="poppins-semibold">Antecedentes personales patológicos</span>
            <v-autocomplete hide-details="auto" ref="antecedentesPP"  color="primary"  variant="outlined" flat  @update:search="buscarAntecedente" v-model="store.antecedentesPerPatologicos" multiple :items="CatAntecedentesPatologicos" item-value="eID" item-title="eNombre" rounded="lg" placeholder="Seleccione uno o varios antecedentes" :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"  >
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
            <v-textarea v-model="store.antecedentesGinecoObs " color="primary" auto-grow :counter="255" maxlength="255" rounded="lg" variant="outlined" flat ></v-textarea>
            </v-col>
            <v-col cols="12">
            <span style="font-size: 0.875rem;" class="poppins-semibold">Alergias</span>
            <v-textarea  v-model="store.alergias"  color="primary" rounded="lg" auto-grow placeholder="Ingrese las alergias del paciente" :counter="255" maxlength="255" variant="outlined" flat ></v-textarea>
            </v-col>
        </v-row>    
</template>


