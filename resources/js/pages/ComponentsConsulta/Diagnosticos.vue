<script setup type="text/javascript">
	import {ref,onMounted,provide,computed} from 'vue';
	import { AplicacionStore } from '@/stores/Aplicacion'
	import { useGoTo } from 'vuetify';
	import { router, Head, usePage } from '@inertiajs/vue3';
	const api_catalogos = "/catalogos";
	const api = "/consulta/";
	import Alerta from '@/components/AppAlerta.vue';
			
			const NoDiagnosticosSDisponibles = ref(true);
			const NoDiagnosticosEDisponibles = ref(true);
			const NoDiagnosticosDDisponibles = ref(true);
			const page = usePage()
            const id_consulta = page.props.id

			const BuscarDiagnosticoS = async(valor)=>{
				//LoaderDiagnosticosS.value=true;
				const url = `${api_catalogos}/diagnosticos-todos`;
				NoDiagnosticosSDisponibles.value=false;
				await axios.get(url,{params:{busqueda:valor,seleccionados:store.diagnosticos_sindromaticos}}).then((response)=>{
					cat_diagnosticosS.value = response.data;	
					console.log("Diagnósticos sindromáticos", response.data);				
					LoaderDiagnosticosS.value=false;
				}).catch((error)=>{
					console.log(error)
					LoaderDiagnosticosS.value=false;
				})
			}
			const BuscarDiagnosticoE = async(valor)=>{
				//LoaderDiagnosticosE.value=true;
				const url = `${api_catalogos}/diagnosticos-todos`;
				NoDiagnosticosEDisponibles.value=false;
				await axios.get(url,{params:{busqueda:valor,seleccionados:store.diagnosticos_etiologicos}}).then((response)=>{
					cat_diagnosticosE.value = response.data;
					LoaderDiagnosticosE.value=false;
				}).catch((error)=>{
					console.log(error)
					LoaderDiagnosticosE.value=false;
				})
			}
			const BuscarDiagnosticoD = async(valor)=>{
				//LoaderDiagnosticosD.value=true;
				const url = `${api_catalogos}/diagnosticos-todos`;
				NoDiagnosticosDDisponibles.value=false;
				await axios.get(url,{params:{busqueda:valor,seleccionados:store.diagnosticos_diferenciales}}).then((response)=>{
					cat_diagnosticosD.value = response.data;
					LoaderDiagnosticosD.value=false;
				}).catch((error)=>{
					console.log(error)
					LoaderDiagnosticosD.value=false;
				})
			}

			const goTo = useGoTo()
			

			const cat_diagnosticosS =  ref([]);
			const cat_diagnosticosE =  ref([]);
			const cat_diagnosticosD =  ref([]);
			const FuncionCallback = ref(null);
			const BanderaBusqueda = ref(0);
			const LoaderDiagnosticosD = ref(false);
			const LoaderDiagnosticosS = ref(false);
			const LoaderDiagnosticosE = ref(false);
			const store = AplicacionStore();

			//Diagnostico Metodos

			const ObtenerDatosDiagnostico = async ()=>{

			//console.log("Obteniendo datos de diagnóstico", BanderaBusqueda.value);	
				if(BanderaBusqueda.value==0){
					
					var url = `${api}${id_consulta}/obten-diagnostico`;
					await axios.get(url).then(async (response)=>{
					//console.log("Entra al if", response.data.diagnosticos_diferenciales);
						store.diagnosticos_diferenciales = response.data.diagnosticos_diferenciales;
						if(store.diagnosticos_diferenciales.length>0){await BuscarDiagnosticoD();}
						store.diagnosticos_etiologicos = response.data.diagnosticos_etiologicos;
						if(store.diagnosticos_etiologicos.length>0){await BuscarDiagnosticoE();}
						store.diagnosticos_sindromaticos = response.data.diagnosticos_sindromaticos;
						//console.log("Diagnosticos sindromáticos", response.data.diagnosticos_sindromaticos);
						if(store.diagnosticos_sindromaticos.length>0){await BuscarDiagnosticoS();}
						if(response.data.diagnosticos_diferenciales.length > 0 || response.data.diagnosticos_sindromaticos.length > 0 || response.data.diagnosticos_etiologicos.length > 0 ){
							store.seccion_diagnostico = true;
						}else{
							store.seccion_diagnostico = false;
						}
						
					}).catch((error)=>{
						console.log(error);
						store.seccion_diagnostico = false;
					})	
				}
				

			}

			const ConfirmacionDeGuardado = async ()=>{
				await goTo('#contenedor-consulta', {});
				
				store.EstadoAlerta = 1;
				store.MostrarAlerta = true;
				store.TituloAlerta = "¿Estás seguro?";
				store.IconoAlerta = "mdi-help-circle";
				store.MensajeAlerta = "La información que ha ingresado se guardará. Asegúrese de que todos los datos sean correctos antes de continuar.";
				store.CallbackAlerta="GuardarSeccion"; 
				
			}

			const GuardarSeccion = ()=>{

				var DiagS = [];
				store.diagnosticos_sindromaticos.forEach((item)=>{
					DiagS.push(item.dId);
				});	
				var DiagE = [];
				store.diagnosticos_etiologicos.forEach((item)=>{
					DiagE.push(item.dId);
				});	
				var DiagD= [];
				store.diagnosticos_diferenciales.forEach((item)=>{
					DiagD.push(item.dId);
				});				



				var parametros = {
					consulta:id_consulta,
					diagnosticos_sindromaticos : DiagS ,
					diagnosticos_etiologicos : DiagE ,
					diagnosticos_diferenciales : DiagD 
				};
				var url = `${api}${id_consulta}/diagnostico`;
				axios.post(url,parametros).then((response)=>{
					store.seccion_diagnostico = true;

					store.EstadoAlerta = 2;
					store.MostrarAlerta = true;
					store.TituloAlerta = "Éxito";
					store.IconoAlerta = "mdi-check-circle";
					store.MensajeAlerta = response.data.message;
					store.CallbackAlerta=null; 
				}).catch((error)=>{
					store.EstadoAlerta = 0;
					store.MostrarAlerta = true;
					store.TituloAlerta = "Error";
					store.IconoAlerta = "mdi-close-circle";
					store.MensajeAlerta = error.response.data ? error.response.data.message : "Ha ocurrido un error. Por favor, inténtalo de nuevo más tarde.";
					store.CallbackAlerta=null; 
				});
			}
			provide('FuncionesPadre',{GuardarSeccion});

	defineExpose({
		ObtenerDatosDiagnostico,
	})

</script>

<template>
	<v-toolbar color="white" class="pa-0 border-sm rounded-t-lg">
        <v-row>
            <v-col class="d-flex align-center">
                <v-toolbar-title class="ps-5 text-primary">
                  <v-icon size="18">mdi-medical-bag</v-icon>
                  <span class="mx-1 poppins-semibold" style="font-size: 1.1rem;">
                    Diagnósticos
                  </span>
				  <v-avatar size="25" rounded="pill" color="primary-primary" class="mx-2">
                        <v-icon size="15">
                            {{
                                store.seccion_diagnostico
                                    ? "mdi-check-circle"
                                    : "mdi-content-save-alert"
                            }}
                        </v-icon>
                	</v-avatar>
                </v-toolbar-title>
            </v-col>
        </v-row>
       
   	</v-toolbar>
	<v-container class="rounded-b-xl px-4 px-sm-6 px-md-6 px-lg-6 px-xl-6 border" fluid>
		<v-row class="px-sm-2 px-md-4 px-lg-8 px-xl-8 mt-5" no-gutters>
			<v-col class="d-flex align-center" cols="12">
				<span style="font-size: 0.875rem;" class="poppins-semibold">Diagnósticos sindromáticos </span>
			</v-col>
			<v-col class="d-flex align-center" cols="12" >
				
				<!-- :hide-no-data="NoDiagnosticosSDisponibles" -->
				<v-autocomplete  @update:search="BuscarDiagnosticoS" :no-data-text="!NoDiagnosticosSDisponibles ? 'No se encontró ningún diagnóstico relacionado. Intente con otra búsqueda.':'Empieza a escribir para ver las coincidencias disponibles.'" return-object color="primary" hide-selected :loading="LoaderDiagnosticosS" :disabled="LoaderDiagnosticosS"  :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" chips hide-details v-model="store.diagnosticos_sindromaticos" multiple :items="cat_diagnosticosS" item-value="dId" item-title="diNombre" variant="outlined" flat rounded="lg" item-color="primary" closable-chips placeholder="Ingrese los diagnósticos sindromáticos del paciente (ej. Síndrome de fatiga crónica, Síndrome metabólico, etc.)">
					<template v-slot:chip="{ props, item }">
            <v-chip
              v-bind="props"
               color="primary"
              :text="item.raw.diNombre"
            ></v-chip>
          </template>
				</v-autocomplete>
			</v-col>
		</v-row>
		<v-row class="px-sm-2 px-md-4 px-lg-8 px-xl-8 mt-5">
			<v-col class="d-flex align-center" cols="12">
				<span style="font-size: 0.875rem;" class="poppins-semibold">Diagnósticos etiológicos</span>
			</v-col>
			<v-col class="d-flex align-center" cols="12">
				
				<v-autocomplete  :no-data-text="!NoDiagnosticosEDisponibles ? 'No se encontró ningún diagnóstico relacionado. Intente con otra búsqueda.':'Empieza a escribir para ver las coincidencias disponibles.'"  @update:search="BuscarDiagnosticoE" return-object hide-selected  color="primary" :loading="LoaderDiagnosticosE" :disabled="LoaderDiagnosticosE" :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" chips hide-details variant="outlined" flat rounded="lg" v-model="store.diagnosticos_etiologicos" multiple :items="cat_diagnosticosE" item-value="dId" item-title="diNombre" item-color="primary" closable-chips placeholder="Ingrese los diagnósticos sindromáticos del paciente (ej. Síndrome de fatiga crónica, Síndrome metabólico, etc.)">
					<template v-slot:chip="{ props, item }">
            <v-chip
              v-bind="props"
              color="primary"
              :text="item.raw.diNombre"
            ></v-chip>
          </template>
				</v-autocomplete>
			</v-col>
		</v-row>
		<v-row class="px-sm-2 px-md-4 px-lg-8 px-xl-8 mt-5" >
			<v-col class="d-flex align-center" cols="12" >
				<span style="font-size: 0.875rem;" class="poppins-semibold">Diagnósticos diferenciales</span>
			</v-col>
			<v-col class="d-flex align-center" cols="12">
				<v-autocomplete  :no-data-text="!NoDiagnosticosDDisponibles ? 'No se encontró ningún diagnóstico relacionado. Intente con otra búsqueda.':'Empieza a escribir para ver las coincidencias disponibles.'" color="primary" @update:search="BuscarDiagnosticoD" return-object hide-selected  :loading="LoaderDiagnosticosD" :disabled="LoaderDiagnosticosD" :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" chips hide-details variant="outlined" flat rounded="lg" v-model="store.diagnosticos_diferenciales" multiple :items="cat_diagnosticosD" item-value="dId" item-title="diNombre" item-color="primary" closable-chips placeholder="Ingrese los diagnósticos sindromáticos del paciente (ej. Síndrome de fatiga crónica, Síndrome metabólico, etc.)">
					<template v-slot:chip="{ props, item }">
            <v-chip
              v-bind="props"
               color="primary"
              :text="item.raw.diNombre"
            ></v-chip>
          </template>
				</v-autocomplete>
			</v-col>
		</v-row>
		<v-row class="d-flex align-center ma-2">
	        <v-col cols="12" class="d-flex justify-end">
	          <v-btn @click="$router.push('/pacientes')" class="mx-2 text-none" color="primary" variant="text">Cancelar</v-btn>
	          <v-btn class="mx-2" color="primary" @click="ConfirmacionDeGuardado">Guardar</v-btn>
	        </v-col>
	    </v-row>
		
	</v-container>
	<Alerta ref="Alerta" :callback="FuncionCallback" v-if="store.SeccionConsulta == 3"></Alerta>
</template>