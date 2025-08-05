<script setup type="text/javascript">
	import {ref,nextTick } from 'vue';

			//Laboratio variables
			const MimesPermitidos = ref(['application/pdf','image/jpeg','image/png'])
			const archivoDatos = ref({})
			const NombreArchivo = ref(null);
			const editarArchivo = ref(false);
			const seccionGuardada = ref(0);
			const link_laboratorio= ref(null)
			const archivoLab1Error = ref(null)
			const archivoLab1 = ref(null)
			const archivoLab2 = ref(null)
			const archivoLab3 = ref(null)
			const hematocrito = ref(null)
			const leucocitos = ref(null)
			const linfocitos = ref(null)
			const monocitos = ref(null)
			const volumen_corpuscular = ref(null)
			const plaquetas = ref(null)
			const glucemia = ref(null)
			const urea = ref(null)
			const creatinina = ref(null)
			const sodio = ref(null)
			const potasio = ref(null)
			const cloro = ref(null)
			const got = ref(null)
			const gpt = ref(null)
			const fosfatasa = ref(null)
			const bilirrubina = ref(null)
			const coagulograma = ref(null)
			const ph = ref(null)
			const co2 = ref(null)
			const hco3 = ref(null)
			const po2 = ref(null)
			const saturacion_oxigeno = ref(null)
			const anion = ref(null)
			const orina = ref(null)
			const glucosa = ref(null)
			const hemocultivo = ref(null)
			const urocultivo = ref(null)
			const descripcion_urocultivo = ref(null)

			const ObtenerDatosArchivo = (valor)=>{
				if(valor){
					archivoLab1.value=valor;
					NombreArchivo.value = valor.name;
					archivoLab1Error.value=null;



					if(!MimesPermitidos.value.includes(valor.type)){
						archivoLab1.value=null;
						NombreArchivo.value = null;
						nextTick(() => {
							archivoLab1Error.value="El archivo seleccionado no es válido. Solo se permiten archivos en formato PDF, PNG o JPEG.";
          					archivoLab1.value=null;
							NombreArchivo.value = null;
        				});
					}

					if(valor.size>3145728){
						archivoLab1.value=null;
						NombreArchivo.value = null;
						nextTick(() => {
							archivoLab1Error.value="El archivo supera el tamaño máximo de 3 MB.";
          					archivoLab1.value=null;
							NombreArchivo.value = null;
        				});
					}

				}


			}

			const Binario = (event)=>{
				const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab']; // Teclas de control permitidas
			    const isNumber = /^[0-1]$/.test(event.key); // Solo permitir números

			    // Solo permitir números o teclas de control
			    if (!isNumber && !allowedKeys.includes(event.key)) {
			       event.preventDefault();
			    }
			}
			const SoloEnteros = (event)=>{
				const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab']; // Teclas de control permitidas
			    const isNumber = /^[0-9]$/.test(event.key); // Solo permitir números

			    // Solo permitir números o teclas de control
			    if (!isNumber && !allowedKeys.includes(event.key)) {
			       event.preventDefault();
			    }
			}
			const Decimales = (event)=>{
					const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab', '.']; // Teclas permitidas
			      const isNumber = /^[0-9]$/.test(event.key); // Solo números
			      const hasDecimalPoint = event.target.value.includes('.'); // Verifica si ya hay un punto decimal

			      // Permitir solo un punto decimal, números o el signo negativo al inicio
			      if (
			        !isNumber &&
			        !allowedKeys.includes(event.key) &&
			        !(event.key === '.' && !hasDecimalPoint)
			      ) {
			        event.preventDefault();
			      }
			}

			const ValidarHematocrito = ()=>{
				var valor=hematocrito.value;
				hematocrito.value= Decimal5(valor);
			}
			const ValidarLeucocitos = ()=>{
				var valor=leucocitos.value;
				leucocitos.value= Int(valor);
			}
			const ValidarLinfocitos = ()=>{
				var valor=linfocitos.value;
				linfocitos.value= Decimal5(valor);
			}
			const ValidarMonocitos = ()=>{
				var valor=monocitos.value;
				monocitos.value= Decimal5(valor);
			}
			const ValidarVolumen_Corpuscular = ()=>{
				var valor=volumen_corpuscular.value;
				volumen_corpuscular.value= Decimal5(valor);
			}
			const ValidarPlaquetas = ()=>{
				var valor=plaquetas.value;
				plaquetas.value= Int(valor);
			}
			const ValidarGlucemia = ()=>{
				var valor=glucemia.value;
				glucemia.value= Decimal7(valor);
			}
			const ValidarUrea = ()=>{
				var valor=urea.value;
				urea.value= Decimal7(valor);
			}
			const ValidarCreatinina = ()=>{
				var valor=creatinina.value;
				creatinina.value= Decimal7(valor);
			}
			const ValidarSodio = ()=>{
				var valor=sodio.value;
				sodio.value= Decimal5(valor);
			}
			const ValidarPotasio = ()=>{
				var valor=potasio.value;
				potasio.value= Decimal5(valor);
			}
			const ValidarCloro = ()=>{
				var valor=cloro.value;
				cloro.value= Decimal5(valor);
			}
			const ValidarGot = ()=>{
				var valor=got.value;
				got.value= Int(valor);
			}
			const ValidarGpt = ()=>{
				var valor=gpt.value;
				gpt.value= Int(valor);
			}
			const ValidarFosfatasa = ()=>{
				var valor=fosfatasa.value;
				fosfatasa.value= Int(valor);
			}
			const ValidarBilirrubina = ()=>{
				var valor=bilirrubina.value;
				bilirrubina.value= Decimal7(valor);
			}
			const ValidarCoagulograma = ()=>{
				var valor=coagulograma.value;
				coagulograma.value= Decimal5(valor);
			}
			const ValidarPh = ()=>{
				var valor=ph.value;
				ph.value= Decimal4(valor);
			}
			const ValidarCo2 = ()=>{
				var valor=co2.value;
				co2.value= Decimal5(valor);
			}
			const ValidarHco3 = ()=>{
				var valor=hco3.value;
				hco3.value= Decimal5(valor);
			}
			const ValidarPo2 = ()=>{
				var valor=po2.value;
				po2.value= Decimal5(valor);
			}
			const ValidarSaturacion_Oxigeno = ()=>{
				var valor=saturacion_oxigeno.value;
				saturacion_oxigeno.value= Decimal5(valor);
			}
			const ValidarAnion = ()=>{
				var valor=anion.value;
				anion.value= Decimal5(valor);
			}
			const ValidarOrina = ()=>{
				var valor=orina.value;
				orina.value= Decimal5(valor);
			}
			const ValidarGlucosa = ()=>{
				var valor=glucosa.value;
				glucosa.value= Decimal7(valor);
			}
			const ValidarHemocultivo = ()=>{
				var valor=hemocultivo.value;
				hemocultivo.value= EsBinario(valor);
			}
			const ValidarUrocultivo = ()=>{
				var valor=urocultivo.value;
				urocultivo.value= Int(valor);
			}

			const EsBinario = (valor)=>{
				const value = valor;
				if(valor.length>1){
					valor = valor.slice(0, -1);
				}
				return  valor;
			}
			const Int = (valor)=>{
				const value = valor;
				const isNegative = value.startsWith('-');

				if (value.includes('.')) {
				    valor = value.replace('.', '');
				}

				if(valor.length>7){
					valor = valor.slice(0, -1);
				}
				return  valor;
			}
			const Decimal4 = (valor)=>{
				const regex = /^-?\d{0,4}(\.\d{0,2})?$/;
			      if (!regex.test(valor)) {

			        valor = valor.slice(0, -1);

			      }
			     return valor;

			}
			const Decimal5 = (valor)=>{
				const regex = /^-?\d{0,5}(\.\d{0,2})?$/;
			      if (!regex.test(valor)) {

			        valor = valor.slice(0, -1);

			      }
			     return valor;

			}
			const Decimal7 = (valor)=>{
				const regex = /^-?\d{0,7}(\.\d{0,2})?$/;
			      if (!regex.test(valor)) {

			        valor = valor.slice(0, -1);

			      }
			     return valor;

			}		
	defineExpose({
		link_laboratorio,			
		archivoLab1,
		archivoLab2,
		archivoLab3,
		hematocrito,
		leucocitos,
		linfocitos,
		monocitos,
		volumen_corpuscular,
		plaquetas,
		glucemia,
		urea,
		creatinina,
		sodio,
		potasio,
		cloro,
		got,
		gpt,
		fosfatasa,
		bilirrubina,
		coagulograma,
		ph,
		co2,
		hco3,
		po2,
		saturacion_oxigeno,
		anion,
		orina,
		glucosa,
		hemocultivo,
		urocultivo,
		descripcion_urocultivo,

	})
</script>
<template>
	<v-container class="rounded-b-xl pa-0" fluid>

		<!--<v-row class="bg-primary rounded mt-2">
		<v-col class="poppins-semibold">Exámen de laboratorio</v-col>
	</v-row>-->

	<v-row>
		<v-col cols="12" class="d-flex align-center justify-center">
			<v-icon color="primary" size="50">mdi-file-eye-outline</v-icon>
		</v-col>
		<v-col cols="12" v-if="!archivoLab1 || seccionGuardada==0 || editarArchivo" class="d-flex  justify-center">
			<span><span style="font-size: 0.875rem;" class="text-grey-darken-3 poppins-semibold">Adjuntar exámen de laboratorio</span> <small class="text-primary poppins-medium">( 3 MB máximo )</small></span>
			
		</v-col>
		<v-col  cols="8" offset="2" sm="8" md="8" lg="4" xl="4" offset-sm="2" offset-md="2" offset-lg="4" offset-xl="4" v-if="!archivoLab1 || seccionGuardada==0 || editarArchivo" class="d-flex  justify-center mt-n2">
			<v-file-input density="compact" accept=".png, .jpg, .jpeg, .pdf" width="300px"  @update:modelValue="ObtenerDatosArchivo"   variant="outlined" flat rounded="lg" v-model="archivoLab1"  :hint="archivoLab1Error" persistent-hint >
				<template v-slot:selection="{fileNames,totalBytesReadable}">
					<span class="text-truncate">{{ fileNames[0]}}</span>
					<span class="text-grey ml-2">({{ totalBytesReadable}})</span>
				</template>
			</v-file-input>
		</v-col>
	</v-row>

	<v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4">
		
		<v-col cols="12" sm="6" md="6" lg="6" xl="6" class="d-flex align-center" v-if="seccionGuardada==1 && archivoLab1 && !editarArchivo">
			<v-container fluid class="pa-0">

				<v-row class="d-flex align-center" no-gutters>
					<v-col cols="12">
						<span>Archivo adjunto</span>
					</v-col>
					<v-col cols="8">
						<v-icon color="primary">mdi-file-eye-outline</v-icon>
						<a :href="`data:${archivoDatos.type};base64,${archivoDatos.base64}`" :download="NombreArchivo" target="__blank" class="text-primary text-decoration-underline mx-2 cursor-pointer">{{NombreArchivo}}-dsd</a>
					</v-col>
					<v-col cols="4" class="d-flex justify-end">
						<v-btn icon  variant="flat" size="x-small" class="mr-2" @click="editarArchivo=true;"><v-icon color="primary">mdi-pencil</v-icon></v-btn>
						<v-btn icon  variant="flat" @click="archivoLab1=null" size="x-small">
							<v-icon color="primary">mdi-delete</v-icon>
						</v-btn>
					</v-col>
				</v-row>
			</v-container>

		</v-col>
		<v-col cols="12" >
			<span class="poppins-semibold" style="font-size: 0.875rem;">Otros archivos </span><small class="text-primary poppins-semibold mx-2">(Ingrese el link , asegúrese de que tiene acceso al enlace o URL)</small>
			<v-text-field variant="outlined" :counter="255" maxlength="255"  placeholder="https://www.ejemplo.com"  rounded="lg" flat v-model="link_laboratorio"></v-text-field>
		</v-col>
	</v-row>

	<v-expansion-panels variant="accordion" multiple rounded="lg" elevation="0" >
		<v-expansion-panel
		
		>
			<v-expansion-panel-title>
				<v-icon color="primary-darken-3" size="small" class="mr-1">mdi-test-tube</v-icon>
				Biometría Hemática
			</v-expansion-panel-title>
			<v-expansion-panel-text>
				<v-row class="pa-0">
					<v-col>
						<v-table class="border-thin rounded-lg">
							<thead>
								<tr>
									<th class="poppins-semibold" style="font-size: 0.885rem;">Campo</th>
									<th class="poppins-semibold" style="font-size: 0.875rem;">Valor</th>
									<th class="poppins-semibold text-center" style="font-size: 0.875rem;">Valor normal</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="font-size: 0.825rem;" class="text-grey-darken-4">Hematocrito (Hto)</td>
									<td style="width: 50%;">
										<v-text-field v-model="hematocrito"   density="compact"  @keydown="Decimales" @input="ValidarHematocrito" variant="outlined" flat class="my-2" rounded="lg" hide-details>
											<template v-slot:append>
											%
											</template>
										</v-text-field>
									</td>
									<td class="text-center">45%</td>
								</tr>
								<tr>
									<td style="font-size: 0.825rem;" class="text-grey-darken-4">Leucocitos</td>
									<td style="width: 50%;">
										<v-container fluid class="pa-0">
											<v-row no-gutters class="d-flex align-center">
												<v-col cols="12" sm="12" md="9" lg="10" xl="10">
													<v-text-field v-model="leucocitos" density="compact"    @keydown="SoloEnteros"  @input="ValidarLeucocitos"
													rounded="lg" variant="outlined" flat class="my-2" hide-details>
													</v-text-field>
												</v-col>
												<v-col cols="12" sm="12" md="3" lg="2" xl="2" class="d-flex justify-xl-end justify-lg-end">
													<span class="mx-2">células/µL</span>
												</v-col>
											</v-row>
										</v-container>
									</td>
									<td class="text-center">6500 células/µL</td>
								</tr>
								<tr>
									<td style="font-size: 0.825rem;" class="text-grey-darken-4">Linfocitos</td>
									<td>
										<v-text-field v-model="linfocitos" density="compact"   @keydown="Decimales" @input="ValidarLinfocitos" variant="outlined" rounded="lg" flat class="my-2" hide-details>
											<template v-slot:append>
											%
											</template>
										</v-text-field>
									</td>
									<td class="text-center">30%</td>
								</tr>
								<tr>
									<td style="font-size: 0.825rem;" class="text-grey-darken-4">Monocitos</td>
									<td>
										<v-text-field v-model="monocitos" rounded="lg" density="compact"   @keydown="Decimales" @input="ValidarMonocitos" variant="outlined" flat class="my-2" hide-details>
											<template v-slot:append>
											%
											</template>
										</v-text-field>
									</td>
									<td class="text-center">5%</td>
								</tr>
								<tr>
									<td style="font-size: 0.825rem;" class="text-grey-darken-4">Volumen corpuscular medio (VCM)</td>
									<td style="width: 50%;">
										<v-container fluid class="pa-0">
											<v-row no-gutters class="d-flex align-center">
												<v-col cols="12" sm="12" md="9" lg="10" xl="10">
													<v-text-field rounded="lg" v-model="volumen_corpuscular" density="compact"   @keydown="Decimales" @input="ValidarVolumen_Corpuscular" variant="outlined" flat class="my-2" hide-details>
													</v-text-field>
												</v-col>
												<v-col cols="12" sm="12" md="3" lg="2" xl="2" class="d-flex justify-xl-end justify-lg-end">
													<span class="mx-2">femtolitros</span>
												</v-col>
											</v-row>
										</v-container>
									</td>
									<td class="text-center">90.0 femtolitros</td>
								</tr>
								<tr>
									<td style="font-size: 0.825rem;" class="text-grey-darken-4">Plaquetas</td>
									<td style="width: 50%;">
										<v-container fluid class="pa-0">
											<v-row no-gutters class="d-flex align-center">
												<v-col cols="12" sm="12" md="9" lg="10" xl="10">
													<v-text-field v-model="plaquetas" density="compact" rounded="lg"   @keydown="SoloEnteros" @input="ValidarPlaquetas" variant="outlined" flat class="my-2" hide-details>
													</v-text-field>
												</v-col>
												<v-col cols="12" sm="12" md="3" lg="2" xl="2" class="d-flex justify-xl-end justify-lg-end">
													<span class="mx-2">células/µL</span>
												</v-col>
											</v-row>
										</v-container>
									</td>
									<td class="text-center">250000 células/µL</td>
								</tr>
							</tbody>
						</v-table>
					</v-col>
				</v-row>
			</v-expansion-panel-text>
		</v-expansion-panel>
		<v-expansion-panel
		
		>
			<v-expansion-panel-title>
				<v-icon color="primary-darken-3" size="small" class="mr-1">mdi-flask</v-icon>
				Biometría Química
			</v-expansion-panel-title>
			<v-expansion-panel-text>
				<v-row class="px-sm-2 px-md-8 px-lg-8 px-xl-8">
					<v-col>
						<v-table class="border-thin rounded-lg">
				<thead>
					<tr>
						<th>Campo</th>
						<th>Valor</th>
						<th class="text-center">Valor normal</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Glucemia</td>
						<td>
							<v-text-field rounded="lg" v-model="glucemia" density="compact"   @keydown="Decimales" @input="ValidarGlucemia" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mg/dL
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">90.0 mg/dL</td>
					</tr>
					<tr>
						<td>Urea</td>
						<td>
							<v-text-field v-model="urea" density="compact"   @keydown="Decimales" rounded="lg" @input="ValidarUrea" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mg/dL
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">30.0 mg/dL</td>
					</tr>
					<tr>
						<td>Creatinina</td>
						<td>
							<v-text-field rounded="lg" v-model="creatinina" density="compact"   @keydown="Decimales" @input="ValidarCreatinina" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mg/dL
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">1 mg/dL</td>
					</tr>
					<tr>
						<td>Sodio</td>
						<td>
							<v-text-field rounded="lg" v-model="sodio" density="compact"   @keydown="Decimales" @input="ValidarSodio" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mEq/L
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">140.0 mEq/L</td>
					</tr>
					<tr>
						<td>Potasio</td>
						<td>
							<v-text-field rounded="lg" v-model="potasio" density="compact"   @keydown="Decimales" @input="ValidarPotasio" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mEq/L
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">4.5 mEq/L</td>
					</tr>
					<tr>
						<td>Cloro</td>
						<td>
							<v-text-field rounded="lg" v-model="cloro" density="compact"   @keydown="Decimales" @input="ValidarCloro" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mEq/L
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">100.0 mEq/L</td>
					</tr>
					<tr>
						<td>GOT (AST)</td>
						<td>
							<v-text-field rounded="lg" v-model="got" density="compact"    @keydown="SoloEnteros" @input="ValidarGot" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      U/L
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">30 U/L</td>
					</tr>
					<tr>
						<td>GPT (ALT)</td>
						<td>
							<v-text-field rounded="lg" v-model="gpt" density="compact"    @keydown="SoloEnteros" @input="ValidarGpt" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      U/L
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">28 U/L</td>
					</tr>
					<tr>
						<td>Fosfatasa alcalina (FAL)</td>
						<td>
							<v-text-field rounded="lg" v-model="fosfatasa" density="compact"    @keydown="SoloEnteros" @input="ValidarFosfatasa" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      U/L
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">100 U/L</td>
					</tr>
					<tr>
						<td>Bilirrubina total</td>
						<td>
							<v-text-field rounded="lg" v-model="bilirrubina" density="compact"   @keydown="Decimales" @input="ValidarBilirrubina" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mg/dL
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">0.8 mg/dL</td>
					</tr>
				</tbody>
			</v-table>
					</v-col>
				</v-row>
			</v-expansion-panel-text>
		</v-expansion-panel>
		<v-expansion-panel
		
		>
			<v-expansion-panel-title>
				<v-icon color="primary-darken-3" size="small" class="mr-1">mdi-timer</v-icon>
				Tiempos de coagulación
			</v-expansion-panel-title>
			<v-expansion-panel-text>
				<v-row class="px-sm-2 px-md-8 px-lg-8 px-xl-8">
					<v-col>
						<v-table class="border-thin rounded-lg">
				<thead>
					<tr>
						<th>Campo</th>
						<th>Valor</th>
						<th class="text-center">Valor normal</th>
					</tr>
				</thead>
				<tbody>

					<tr>
						<td>Coagulograma (tiempos de protrombina, TTP)</td>
						<td>
							<v-text-field rounded="lg" v-model="coagulograma" density="compact"   @keydown="Decimales" @input="ValidarCoagulograma" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      segundos
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">12.0 segundos</td>
					</tr>

				</tbody>
			</v-table>
					</v-col>
				</v-row>
			</v-expansion-panel-text>
		</v-expansion-panel>
		<v-expansion-panel
		
		>
			<v-expansion-panel-title>
				<v-icon color="primary-darken-3" size="small" class="mr-1">mdi-air-filter</v-icon>
				Gasometría
			</v-expansion-panel-title>
			<v-expansion-panel-text>
				<v-row class="px-sm-2 px-md-8 px-lg-8 px-xl-8">
					<v-col>
						<v-table class="border-thin rounded-lg">
				<thead>
					<tr>
						<th>Campo</th>
						<th>Valor</th>
						<th class="text-center">Valor normal</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>pH</td>
						<td>
							<v-text-field rounded="lg" v-model="ph" density="compact"   @keydown="Decimales" @input="ValidarPh" variant="outlined" flat class="my-2" hide-details>
							</v-text-field>
						</td>
						<td class="text-center">7.40</td>
					</tr>
					<tr>
						<td>CO2 (Dióxido de Carbono)</td>
						<td>
							<v-text-field rounded="lg" v-model="co2" density="compact"   @keydown="Decimales" @input="ValidarCo2" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mmHg
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">40.0 mmHg</td>
					</tr>
					<tr>
						<td>HCO3 (Bicarbonato)</td>
						<td>
							<v-text-field rounded="lg" v-model="hco3" density="compact"   @keydown="Decimales" @input="ValidarHco3" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mEq/L
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">24.0 mEq/L</td>
					</tr>
					<tr>
						<td>PO2 (Presión parcial de oxígeno)</td>
						<td>
							<v-text-field rounded="lg" v-model="po2" density="compact"   @keydown="Decimales" @input="ValidarPo2" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mmHg
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">95.0 mmHg</td>
					</tr>
					<tr>
						<td>Saturación de oxígeno (Sat)</td>
						<td>
							<v-text-field v-model="saturacion_oxigeno" density="compact"  rounded="lg" @keydown="Decimales" @input="ValidarSaturacion_Oxigeno" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      %
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">98.0 %</td>
					</tr>
					<tr>
						<td>Anion GAP (GAP)</td>
						<td>
							<v-text-field rounded="lg" v-model="anion" density="compact"   @keydown="Decimales" @input="ValidarAnion" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mEq/L
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">12.0 mEq/L</td>
					</tr>
					<tr>
						<td>Orina</td>
						<td>
							<v-text-field rounded="lg" v-model="orina" density="compact" :counter="100" maxlength="100" variant="outlined" flat class="my-2" >
							</v-text-field>
						</td>
						<td></td>
					</tr>
					<tr>
						<td>Glucosa o proteínas</td>
						<td>
							<v-text-field rounded="lg" v-model="glucosa" density="compact"   @keydown="Decimales" @input="ValidarGlucosa" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mg/dL
							    </template>
							</v-text-field>
						</td>
						<td></td>
					</tr>
					<tr>
						<td>Hemocultivo</td>
						<td>
							<v-text-field rounded="lg" v-model="hemocultivo" density="compact"   @keydown="Binario" @input="ValidarHemocultivo" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      mg/dL
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">0 (Negativo)</td>
					</tr>
					<tr>
						<td>Urocultivo</td>
						<td>
							<v-text-field rounded="lg" @keydown="SoloEnteros" @input="ValidarUrocultivo"     v-model="urocultivo" density="compact"  variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      UFC/mL
							    </template>
							</v-text-field>
						</td>
						<td class="text-center">0 (Negativo)</td>
					</tr>
					<tr>
						<td>Descripción Urocultivo</td>
						<td colspan="2">
							<v-textarea auto-grow rounded="lg" v-model="descripcion_urocultivo" class="my-2" :counter="255" maxlength="255" density="compact" variant="outlined" flat></v-textarea>
						</td>
					</tr>
				</tbody>
			</v-table>
					</v-col>
				</v-row>
			</v-expansion-panel-text>
		</v-expansion-panel>
	</v-expansion-panels>
	</v-container>


</template>