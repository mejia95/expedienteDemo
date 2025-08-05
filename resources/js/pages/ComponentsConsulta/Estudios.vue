<script setup type="text/javascript">
	import { useGoTo } from 'vuetify';
	import Alerta from '@/components/AppAlerta.vue';
	import {AplicacionStore} from '@/stores/Aplicacion.js'
	import Laboratorio from '@/pages/ComponentsConsulta/Laboratorio.vue';
	import Electrocardiograma from '@/pages/ComponentsConsulta/Electrocardiograma.vue';
	import RadiografiaTorax from '@/pages/ComponentsConsulta/RadiografiaTorax.vue';
    import { ref, onMounted, computed, provide } from 'vue'
	import { router, Head, usePage } from '@inertiajs/vue3';
	const api = "/consulta/";

	
			//Estudios Variables
			const goTo = useGoTo();
			const estudios_pestania = ref(1);
			const store = AplicacionStore();
			const FuncionCallback = ref(null);
			const guardarInformacion = ref(0);
			const Laboratorio_ref = ref(null);
			const Electrocardiograma_ref = ref(null);
			const Radiografia = ref(null);
			const camposLaboratorio = ref([]);
			const camposElectrocardiograma = ref([]);
			const camposRadiografia = ref([]);
			const otrosEstudios = ref(null);
			const MensajeAppAlerta = ref(null);
        	const AlertaCallback = ref(null);
			const page = usePage()
            const id_consulta = page.props.id
			//Estudios Metodos

			const base64ToBlob =(base64, contentType = '', sliceSize = 512) =>{
			    const byteCharacters = atob(base64); // Decodifica la cadena Base64
			    const byteArrays = [];

			    for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
			        const slice = byteCharacters.slice(offset, offset + sliceSize);

			        const byteNumbers = new Array(slice.length);
			        for (let i = 0; i < slice.length; i++) {
			            byteNumbers[i] = slice.charCodeAt(i);
			        }

			        const byteArray = new Uint8Array(byteNumbers);
			        byteArrays.push(byteArray);
			    }

			    return new Blob(byteArrays, { type: contentType });
			}

			const setValoresLaboratorio = (DatosLaboratorio)=>{
			//	console.log("Estos son los datos de laboratorio", DatosLaboratorio[0].archivoLab1);
				if(!DatosLaboratorio || DatosLaboratorio.length <1){return null;}
				if(('archivoLab1' in DatosLaboratorio[0]) && DatosLaboratorio[0].archivoLab1){
					const archivo1Base64 = DatosLaboratorio[0].archivoLab1.base64_archivo;
					const blob = base64ToBlob(archivo1Base64,DatosLaboratorio[0].archivoLab1.tipo_archivo);
					const nombreArchivo = DatosLaboratorio[0].archivoLab1.nombre_archivo;
					const newFile = new File([blob],nombreArchivo,{type:DatosLaboratorio[0].archivoLab1.tipo_archivo});					
					Laboratorio_ref.value.archivoLab1 = newFile;
					if (!Laboratorio_ref.value.archivoDatos) {
						Laboratorio_ref.value.archivoDatos = {};
					}
					Laboratorio_ref.value.archivoDatos.type = DatosLaboratorio[0].archivoLab1.tipo_archivo;
					Laboratorio_ref.value.archivoDatos.base64=DatosLaboratorio[0].archivoLab1.base64_archivo;
					Laboratorio_ref.value.NombreArchivo = DatosLaboratorio[0].archivoLab1.nombre_archivo;
					//console.log("Estos son los datos de laboratorio",Laboratorio_ref.value.archivoDatos.type);
					//console.log(Laboratorio.value.archivoDatos);
				}	
				
				
				Laboratorio_ref.value.link_laboratorio = DatosLaboratorio[0].link_laboratorio;
				Laboratorio_ref.value.seccionGuardada = DatosLaboratorio[0].guardada;
				//Laboratorio.value.archivoLab1 = DatosLaboratorio[0].archivoLab1;
				Laboratorio_ref.value.archivoLab2 = DatosLaboratorio[0].archivoLab2;
				Laboratorio_ref.value.archivoLab3 = DatosLaboratorio[0].archivoLab3;
				Laboratorio_ref.value.hematocrito = DatosLaboratorio[0].hematocrito;
				Laboratorio_ref.value.leucocitos = DatosLaboratorio[0].leucocitos;
				Laboratorio_ref.value.linfocitos = DatosLaboratorio[0].linfocitos;
				Laboratorio_ref.value.monocitos = DatosLaboratorio[0].monocitos;
				Laboratorio_ref.value.volumen_corpuscular = DatosLaboratorio[0].volumen_corpuscular;
				Laboratorio_ref.value.plaquetas = DatosLaboratorio[0].plaquetas;
				Laboratorio_ref.value.glucemia = DatosLaboratorio[0].glucemia;
				Laboratorio_ref.value.urea = DatosLaboratorio[0].urea;
				Laboratorio_ref.value.creatinina = DatosLaboratorio[0].creatinina;
				Laboratorio_ref.value.sodio = DatosLaboratorio[0].sodio;
				Laboratorio_ref.value.potasio = DatosLaboratorio[0].potasio;
				Laboratorio_ref.value.cloro = DatosLaboratorio[0].cloro;
				Laboratorio_ref.value.got = DatosLaboratorio[0].got;
				Laboratorio_ref.value.gpt = DatosLaboratorio[0].gpt;
				Laboratorio_ref.value.fosfatasa = DatosLaboratorio[0].fosfatasa;
				Laboratorio_ref.value.bilirrubina = DatosLaboratorio[0].bilirrubina;
				Laboratorio_ref.value.coagulograma = DatosLaboratorio[0].coagulograma;
				Laboratorio_ref.value.ph = DatosLaboratorio[0].ph;
				Laboratorio_ref.value.co2 = DatosLaboratorio[0].co2;
				Laboratorio_ref.value.hco3 = DatosLaboratorio[0].hco3;
				Laboratorio_ref.value.po2 = DatosLaboratorio[0].po2;
				Laboratorio_ref.value.saturacion_oxigeno = DatosLaboratorio[0].saturacion_oxigeno;
				Laboratorio_ref.value.anion = DatosLaboratorio[0].anion;
				Laboratorio_ref.value.orina = DatosLaboratorio[0].orina;
				Laboratorio_ref.value.glucosa = DatosLaboratorio[0].glucosa;
				Laboratorio_ref.value.hemocultivo = DatosLaboratorio[0].hemocultivo;
				Laboratorio_ref.value.urocultivo = DatosLaboratorio[0].urocultivo;
				Laboratorio_ref.value.descripcion_urocultivo = DatosLaboratorio[0].descripcion_urocultivo;
			}

			const setValoresElectrocardiograma = (DatosElectro)=>{
				if(!DatosElectro || DatosElectro.length <1){return null;}
				Electrocardiograma_ref.value.link_electro = DatosElectro[0].link_electro;
				Electrocardiograma_ref.value.archivoElect1 = DatosElectro[0].archivoElect1;
				Electrocardiograma_ref.value.archivoElect2 = DatosElectro[0].archivoElect2;
				Electrocardiograma_ref.value.archivoElect3 = DatosElectro[0].archivoElect3;
				Electrocardiograma_ref.value.descripcion = DatosElectro[0].descripcion;
				Electrocardiograma_ref.value.ritmo = DatosElectro[0].ritmo;
				Electrocardiograma_ref.value.frecuencia_cardiaca = DatosElectro[0].frecuencia_cardiaca;
				Electrocardiograma_ref.value.eje = DatosElectro[0].eje;
				Electrocardiograma_ref.value.duracionQRS = DatosElectro[0].duracionQRS;
				Electrocardiograma_ref.value.ondaP = DatosElectro[0].ondaP;
				Electrocardiograma_ref.value.ondaT = DatosElectro[0].ondaT;
				Electrocardiograma_ref.value.segmento = DatosElectro[0].segmento;
				Electrocardiograma_ref.value.intervaloPR = DatosElectro[0].intervaloPR;
				Electrocardiograma_ref.value.intervaloQTC = DatosElectro[0].intervaloQTC;
				Electrocardiograma_ref.value.conclusion = DatosElectro[0].conclusion;
			}
			const setValoresRadiografia = (DatosRadiografia)=>{
				Radiografia.value.link_radiografia = DatosRadiografia[0].link_radiografia;
				Radiografia.value.archivoRadio1 = DatosRadiografia[0].archivoRadio1;
				Radiografia.value.archivoRadio2 = DatosRadiografia[0].archivoRadio2;
				Radiografia.value.archivoRadio3 = DatosRadiografia[0].archivoRadio3;
				Radiografia.value.partes_blandas = DatosRadiografia[0].partes_blandas;
				Radiografia.value.partes_oseas = DatosRadiografia[0].partes_oseas;
				Radiografia.value.campos_pulmonares = DatosRadiografia[0].campos_pulmonares;
				Radiografia.value.silueta_cardiovascular = DatosRadiografia[0].silueta_cardiovascular;
				Radiografia.value.indice_cardiotoracico = DatosRadiografia[0].indice_cardiotoracico;
				Radiografia.value.conclusiones = DatosRadiografia[0].conclusiones;
			}
			const GuardarEstudiosLaboratorio = async()=>{
				
				
				camposLaboratorio.value= {
						link_laboratorio:Laboratorio_ref.value.link_laboratorio,
						archivoLab1:Laboratorio_ref.value.archivoLab1,
						archivoLab2:Laboratorio_ref.value.archivoLab2,
						archivoLab3:Laboratorio_ref.value.archivoLab3,
						hematocrito:Laboratorio_ref.value.hematocrito,
						leucocitos:Laboratorio_ref.value.leucocitos,
						linfocitos:Laboratorio_ref.value.linfocitos,
						monocitos:Laboratorio_ref.value.monocitos,
						volumen_corpuscular:Laboratorio_ref.value.volumen_corpuscular,
						plaquetas:Laboratorio_ref.value.plaquetas,
						glucemia:Laboratorio_ref.value.glucemia,
						urea:Laboratorio_ref.value.urea,
						creatinina:Laboratorio_ref.value.creatinina,
						sodio:Laboratorio_ref.value.sodio,
						potasio:Laboratorio_ref.value.potasio,
						cloro:Laboratorio_ref.value.cloro,
						got:Laboratorio_ref.value.got,
						gpt:Laboratorio_ref.value.gpt,
						fosfatasa:Laboratorio_ref.value.fosfatasa,
						bilirrubina:Laboratorio_ref.value.bilirrubina,
						coagulograma:Laboratorio_ref.value.coagulograma,
						ph:Laboratorio_ref.value.ph,
						co2:Laboratorio_ref.value.co2,
						hco3:Laboratorio_ref.value.hco3,
						po2:Laboratorio_ref.value.po2,
						saturacion_oxigeno:Laboratorio_ref.value.saturacion_oxigeno,
						anion:Laboratorio_ref.value.anion,
						orina:Laboratorio_ref.value.orina,
						glucosa:Laboratorio_ref.value.glucosa,
						hemocultivo:Laboratorio_ref.value.hemocultivo,
						urocultivo:Laboratorio_ref.value.urocultivo,
						descripcion_urocultivo:Laboratorio_ref.value.descripcion_urocultivo,
				};

				camposElectrocardiograma.value = {
					link_electro:Electrocardiograma_ref.value.link_electro,
					archivoElect1:Electrocardiograma_ref.value.archivoElect1,
					archivoElect2:Electrocardiograma_ref.value.archivoElect2,
					archivoElect3:Electrocardiograma_ref.value.archivoElect3,
					descripcion:Electrocardiograma_ref.value.descripcion,
					ritmo:Electrocardiograma_ref.value.ritmo,
					frecuencia_cardiaca:Electrocardiograma_ref.value.frecuencia_cardiaca,
					eje:Electrocardiograma_ref.value.eje,
					duracionQRS:Electrocardiograma_ref.value.duracionQRS,
					ondaP:Electrocardiograma_ref.value.ondaP,
					ondaT:Electrocardiograma_ref.value.ondaT,
					segmento:Electrocardiograma_ref.value.segmento,
					intervaloPR:Electrocardiograma_ref.value.intervaloPR,
					intervaloQTC:Electrocardiograma_ref.value.intervaloQTC,
					conclusion:Electrocardiograma_ref.value.conclusion,
				};
				//console.log("Que traes en camposLaboratorio", camposElectrocardiograma.value);

				camposRadiografia.value = {
					link_radiografia:Radiografia.value.link_radiografia,
					archivoRadio1:Radiografia.value.archivoRadio1,
					archivoRadio2:Radiografia.value.archivoRadio2,
					archivoRadio3:Radiografia.value.archivoRadio3,
					partes_blandas:Radiografia.value.partes_blandas,
					partes_oseas:Radiografia.value.partes_oseas,
					campos_pulmonares:Radiografia.value.campos_pulmonares,
					silueta_cardiovascular:Radiografia.value.silueta_cardiovascular,
					indice_cardiotoracico:Radiografia.value.indice_cardiotoracico,
					conclusiones:Radiografia.value.conclusiones,
				}
				// console.log("Que traes en camposElectrocardiograma");
				// console.log(camposElectrocardiograma.value);
				var url = `${api}${id_consulta}/estudios`;
				await axios.post(url,{consulta:id_consulta,datos_laboratorio:camposLaboratorio.value,datos_electro : camposElectrocardiograma.value,datos_radiografia:camposRadiografia.value,otros:otrosEstudios.value,guardar:guardarInformacion.value},{headers: {'Content-Type': 'multipart/form-data'}}).then((response)=>{				
					if(response.data.estudios[0]){
						if(response.data.estudios[0].Laboratorio){setValoresLaboratorio(response.data.estudios[0].Laboratorio)};
						if(response.data.estudios[0].Electrocardiograma){setValoresElectrocardiograma(response.data.estudios[0].Electrocardiograma)};
						if(response.data.estudios[0].Radiografia){setValoresRadiografia(response.data.estudios[0].Radiografia)};
						otrosEstudios.value=response.data.estudios[0].otros;
						if(guardarInformacion.value==1){
							 store.EstadoAlerta = 2;
							store.MostrarAlerta = true;
							store.TituloAlerta = "Éxito";
                			store.IconoAlerta = "mdi-check-circle";
							store.MensajeAlerta = response.data.message ;
                			store.CallbackAlerta=null; 
						}
						store.seccion_estudios = true;
					}

				}).catch((error)=>{
				//	console.log("Error al guardar estudios",error);
					if(error.response && guardarInformacion.value==1){
						 store.EstadoAlerta = 0;
						store.MostrarAlerta = true;
						store.TituloAlerta = "Error";
						store.IconoAlerta = "mdi-close-circle";
						store.MensajeAlerta = error.response.data.message ?  error.response.data.message : "<p>No se pudo completar el registro de la sección de <strong>Estudios</strong>. Por favor, intente nuevamente más tarde o contacta con el soporte si el problema persiste.</p>";
						store.CallbackAlerta = null;	
						store.seccion_estudios = false;
					}else{
						store.MostrarAlerta = true;
						store.TituloAlerta = "Error";
						store.IconoAlerta = "mdi-close-circle";
						store.MensajeAlerta = "<p>No se pudo completar el registro de la sección de <strong>Estudios</strong>. Por favor, intente nuevamente más tarde o contacta con el soporte si el problema persiste.</p>";
						store.CallbackAlerta = null;	
					}
					
				})
			}

			const ConfirmacionDeGuardado = async()=>{				
				await goTo('#contenedor-consulta', {});
				//console.log("Guardando estudios de laboratorio");
				//guardarInformacion.value=1;
				// Alerta.value.MostrarAlerta = true;
				// Alerta.value.TipoAlerta = 1;
				// Alerta.value.MensajeAlerta = "La información que ha ingresado se guardará. Asegúrese de que todos los datos sean correctos antes de continuar.";
				// FuncionCallback.value="GuardarEstudiosLaboratorio";				
				store.EstadoAlerta = 1;
				store.MostrarAlerta = true;
            	store.TituloAlerta = "¿Estás seguro?";
            	store.IconoAlerta = "mdi-help-circle";
				store.MensajeAlerta = "La información que ha ingresado se guardará. Asegúrese de que todos los datos sean correctos antes de continuar." ;
            	store.CallbackAlerta = "GuardarEstudiosLaboratorio"; 
			}

			provide('FuncionesPadre',{GuardarEstudiosLaboratorio});
	defineExpose({
    	GuardarEstudiosLaboratorio
	});
		
</script>
<template>
	<v-toolbar color="white" class="pa-0 border-sm rounded-t-lg">
        <v-row>
            <v-col class="d-flex align-center">
                <v-toolbar-title class="ps-5 text-primary-darken-3">
                  <v-icon size="18">mdi-test-tube</v-icon>
                  <span class="mx-1 poppins-semibold" style="font-size: 1.1rem;">
                    Estudios
                  </span>
				  <v-avatar size="25" rounded="pill" color="primary-darken-1" class="mx-2">
                        <v-icon size="15">
							{{store.seccion_estudios ? 'mdi-check-circle' : 'mdi-content-save-alert' }}
                        </v-icon>
                    </v-avatar>
                </v-toolbar-title>
            </v-col>
        </v-row>
   	</v-toolbar>
   	<v-container class="rounded-b-xl px-4 px-sm-6 px-md-6 px-lg-6 px-xl-6 border" fluid>
		<v-row class="mt-0">
			<v-col cols="12">
				<v-tabs density="compact"  color="primary-darken-1" v-model="estudios_pestania" >
					<v-tab :value="1" :variant="estudios_pestania == 1 ? 'elevated' : 'tonal'" class="text-none mr-2" rounded="t-lg" hide-slider>
					<span style="font-size: 0.775rem;">Ex. Laboratorio</span>
					</v-tab>
					<v-tab :value="2" :variant="estudios_pestania == 2 ? 'elevated' : 'tonal'" class="text-none mr-2" rounded="t-lg" hide-slider>
					<span style="font-size: 0.775rem;">Electrocardiograma</span>
					</v-tab>
					<v-tab :value="3" :variant="estudios_pestania == 3 ? 'elevated' : 'tonal'" class="text-none mr-2" rounded="t-lg" hide-slider>
						<span style="font-size: 0.775rem;">Radiografía</span>
					</v-tab>
					<v-tab :value="4" :variant="estudios_pestania == 4 ? 'elevated' : 'tonal'" class="text-none mr-2" rounded="t-lg" hide-slider>
						<span style="font-size: 0.775rem;">Otros</span>
					</v-tab>
				</v-tabs>
			</v-col>
			<v-alert
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



      	<v-row class="">
	        <v-col cols="12" class=" pt-3">
	        	<Laboratorio ref="Laboratorio_ref" v-show="estudios_pestania == 1"></Laboratorio>
	        	<Electrocardiograma ref="Electrocardiograma_ref" v-show="estudios_pestania == 2"></Electrocardiograma>
	        	<RadiografiaTorax ref="Radiografia" v-show="estudios_pestania == 3"></RadiografiaTorax>
	        	<v-container v-show="estudios_pestania == 4">
					<v-row class="px-sm-2 px-md-2 px-lg-2 px-xl-4 mt-n4">
						<v-col cols="12">
				          <v-container fluid class="pa-0">
				            <v-row class="d-flex align-center" no-gutters>
				              <v-col cols="12"><span style="font-size: 0.875rem;" class="poppins-semibold">Describa otros estudios</span></v-col>
				              <v-col cols="12">
				                <v-textarea
				                  v-model="otrosEstudios"
				                  :counter="255"
				                  auto-grow 
				                  maxlength="255"
				                  required
				                  variant="outlined" flat
				                  rounded="lg"
				                ></v-textarea>
				              </v-col>
				            </v-row>
				          </v-container>
				        </v-col>
					</v-row>
				</v-container>
	        </v-col>
    	</v-row>
    	<v-row class="d-flex align-center ma-1">
	        <v-col cols="12" class="d-flex justify-end">
	          <v-btn @click="$router.push('/pacientes')" class="mx-2 text-none" color="primary" variant="text">Cancelar</v-btn>
	          <v-btn class="mx-2" color="primary" @click="ConfirmacionDeGuardado(); guardarInformacion = 1">Guardar</v-btn>
	        </v-col>
	    </v-row> 
	    
    </v-container>
	<Alerta ref="MensajeAppAlerta" :callback="FuncionCallback" v-if="store.SeccionConsulta == 2"></Alerta>
</template>