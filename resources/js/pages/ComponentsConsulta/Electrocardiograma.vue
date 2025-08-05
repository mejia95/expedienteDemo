<script setup type="text/javascript">
	import {ref,nextTick} from 'vue';

			//Electrocardiograma Variables
			const link_electro = ref(null);
			const archivoElect1 = ref(null);
			const archivoElect2 = ref(null);
			const archivoElect3 = ref(null);
			const descripcion = ref(null);
			const ritmo = ref(null);
			const frecuencia_cardiaca = ref(null);
			const eje = ref(null);
			const duracionQRS = ref(null);
			const ondaP = ref(null);
			const ondaT = ref(null);
			const segmento = ref(null);
			const intervaloPR = ref(null);
			const intervaloQTC = ref(null);
			const conclusion = ref(null);

			//Electrocardiograma Metodos
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
			      const isNegativeSign = event.key === '-' && event.target.value === ''; // El signo negativo solo puede estar al principio
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
			const Negativos = (event)=>{
				  const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab']; // Teclas permitidas
			      const isNumber = /^[0-9]$/.test(event.key); // Solo números
			      const isNegativeSign = event.key === '-' && event.target.value === ''; 
			      const hasNegativeSign = event.target.value.includes('-');

			      // Permitir solo un punto decimal, números o el signo negativo al inicio
			      if (!isNumber &&
			        !allowedKeys.includes(event.key) &&
			        !(event.key === '-' && !hasNegativeSign && event.target.value === '')
			      ) {
			        event.preventDefault();
			      }
			}

			const ValidarFrecuencia = ()=>{
				var valor = frecuencia_cardiaca.value;
				frecuencia_cardiaca.value = SmallInt(valor)
			}
			const ValidarEje = ()=>{
				var valor = eje.value;
				eje.value = NegativeSmallInt(valor)
			}
			const ValidarQRS = ()=>{
				var valor = duracionQRS.value;
				duracionQRS.value = Decimal(valor)
			}
			const ValidarIntervaloPR = ()=>{
				var valor = intervaloPR.value;
				intervaloPR.value = Decimal(valor)
			}
			const ValidarIntervaloQTC = ()=>{
				var valor = intervaloQTC.value;
				intervaloQTC.value = Decimal(valor)
			}

			/*const SmallInt = (valor)=>{
				const value = valor;
				const isNegative = value.startsWith('-');
				

				if (value.includes('.')) {
				    valor = value.replace('.', '');
				}
				if ((isNegative && value.length > 4) || (!isNegative && value.length > 3)) {
				    valor = value.slice(0, value.length - 1)
				}
				return  valor;
			}*/

			const SmallInt = (valor)=>{
				const value = valor;
				if (value.includes('.')) {
				    valor = value.replace('.', '');
				}
				if (value.length > 3) {
				    valor = value.slice(0, value.length - 1)
				}
				return  valor;
			}
			const NegativeSmallInt = (valor)=>{
				const value = valor;
				const regex = /^-?\d{0,4}(\.\d{0,2})?$/;
				const isNegative = value.startsWith('-');
				 if (!regex.test(value)) {
				 	var newCadena = value.replace('-','')
					if ((isNegative && value.length > 4) || (!isNegative && value.length > 3)) {
					    valor = newCadena.slice(0, newCadena.length - 1)
					}else{
						valor = newCadena
					}
			     }

				if (value.includes('.')) {
				    valor = value.replace('.', '');
				}
				if ((isNegative && value.length > 4) || (!isNegative && value.length > 3)) {
				    valor = value.slice(0, value.length - 1)

				}
				return  valor;
			}

			const Decimal = (valor)=>{
				const regex = /^-?\d{0,5}(\.\d{0,2})?$/;
			      if (!regex.test(valor)) {
			        
			        valor = valor.slice(0, -1);
			        
			      }
			     return valor;

			}	
	defineExpose({
		link_electro,
		archivoElect1,
		archivoElect2,
		archivoElect3,
		descripcion,
		ritmo,
		frecuencia_cardiaca,
		eje,
		duracionQRS,
		ondaP,
		ondaT,
		segmento,
		intervaloPR,
		intervaloQTC,
		conclusion,
	})
	
</script>
<template>
	<v-container class="rounded-b-xl pa-0" fluid>
		<!--<v-row class="bg-primary rounded mt-2">
			<v-col class="poppins-semibold">Electrocardiograma</v-col>
		</v-row>-->
		<v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4">			
			<v-col cols="12" sm="12" md="12" lg="12" xl="12">
				<span style="font-size: 0.875rem;" class="poppins-semibold">Link del estudio</span> <small class="text-primary poppins-medium">(Ingrese el link , asegúrese de que tiene acceso al enlace o URL)</small>
				<v-text-field variant="outlined" flat placeholder="https://www.ejemplo.com" rounded="lg" :counter="255" maxlength="255"  v-model="link_electro"></v-text-field>
			</v-col>
		</v-row>

		<v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 mt-n4">
			<v-col cols="12">
	          <v-container fluid class="pa-0">
	            <v-row class="d-flex align-center" no-gutters>
	              <v-col cols="12" sm="12" md="12" lg="3" xl="3"><span style="font-size: 0.875rem;" class="poppins-semibold">Descripción general</span></v-col>
	              <v-col cols="12" sm="12" md="12" lg="9" xl="9">
	                <v-textarea auto-grow  v-model="descripcion" :counter="255" maxlength="255" variant="outlined" flat rounded="lg"></v-textarea>
	              </v-col>
	            </v-row>
	          </v-container>
	        </v-col>
		</v-row>
	<v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4">
		<v-col>

			<v-table class="border-thin rounded-lg">
				<thead>
					<tr>
						<th>Campo</th>
						<th>Valor</th>
						<th>Valor normal</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Ritmo</td>
						<td style="width: 50%;">
							<v-container fluid>
								<v-row no-gutters class="d-flex align-center">
									<v-col cols="12" sm="12" md="7" lg="6" xl="6">
										<v-text-field :counter="50" maxlength="50" v-model="ritmo" density="compact" variant="outlined" rounded="lg" flat class="my-2" hide-details>
										</v-text-field>
									</v-col>
									<v-col cols="12" sm="12" md="5" lg="6" xl="6">
										<span class="mx-2">sinusal, fibrilación, etc.</span>
									</v-col>
								</v-row>
							</v-container>
						</td>
						<td>sinusal</td>
					</tr>
					<tr>
						<td>Frecuencia Cardíaca (FC)</td>
						<td>
							<v-container fluid>
								<v-row no-gutters class="d-flex align-center">
									<v-col cols="12" sm="12" md="7" lg="6" xl="6">
										<v-text-field rounded="lg" @keydown="SoloEnteros" v-model="frecuencia_cardiaca" density="compact"  min="0" max="999" @input="ValidarFrecuencia" variant="outlined" flat class="my-2" hide-details>
										</v-text-field>
									</v-col>
									<v-col cols="12" sm="12" md="5" lg="6" xl="6">
										<span class="mx-2">(lpm, latidos por minuto)</span>
									</v-col>
								</v-row>
							</v-container>
						</td>
						<td> 70 lpm</td>
					</tr>
					<tr>
						<td>Eje</td>
						<td>
							<v-text-field rounded="lg" @keydown="Negativos" min="-999" max="999" v-model="eje" density="compact" variant="outlined" flat class="my-2" @input="ValidarEje" hide-details>
								
								<template v-slot:append>
							      °
							    </template>
							</v-text-field>
						</td>
						<td>60°</td>
					</tr>
					<tr>
						<td>Duración del QRS</td>
						<td>
							<v-text-field rounded="lg" step="0.01" min="0" @keydown="Decimales" @input="ValidarQRS" v-model="duracionQRS" density="compact" variant="outlined" flat class="my-2" hide-details>
								
								<template v-slot:append>
							      milisegundos
							    </template>
							</v-text-field>
						</td>
						<td>90.0 ms</td>
					</tr>
					<tr>
						<td>Onda P</td>
						<td>
							<v-text-field rounded="lg" :counter="50" maxlength="50" v-model="ondaP" density="compact" variant="outlined" flat class="my-2" hide-details>
							</v-text-field>
						</td>
						<td>Normal</td>
					</tr>
					<tr>
						<td>Onda T</td>
						<td>
							<v-text-field rounded="lg" :counter="50" maxlength="50" v-model="ondaT" density="compact" variant="outlined" flat class="my-2" hide-details>
							</v-text-field>
						</td>
						<td>Normal</td>
					</tr>
					<tr>
						<td>Segmento ST</td>
						<td>
							<v-text-field rounded="lg" :counter="50" maxlength="50" v-model="segmento" density="compact" variant="outlined" flat class="my-2" hide-details>
							</v-text-field>
						</td>
						<td>
							Normal
						</td>
					</tr>
					<tr>
						<td>Intervalo PR</td>
						<td>
							<v-text-field rounded="lg" step="1"min="0" max="99999" @keydown="Decimales" @input="ValidarIntervaloPR" v-model="intervaloPR" density="compact" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      ms
							    </template>
							</v-text-field>
						</td>
						<td>
							160 (ms)
						</td>
					</tr>
					<tr>
						<td>Intervalo QTc</td>
						<td>
							<v-text-field rounded="lg" step="1"
min="0" max="99999" @keydown="Decimales" @input="ValidarIntervaloQTC" v-model="intervaloQTC" density="compact" variant="outlined" flat class="my-2" hide-details>
								<template v-slot:append>
							      ms
							    </template>
							</v-text-field>
						</td>
						<td>
							400 ms
						</td>
					</tr>
				</tbody>
			</v-table>
		</v-col>
	</v-row>
	<v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4">
			<v-col cols="12">
	          <v-container fluid class="pa-0">
	            <v-row class="d-flex align-center" no-gutters>
	              <v-col cols="12" sm="12" md="12" lg="3" xl="3"><span style="font-size: 0.875rem;" class="poppins-semibold">Conclusión</span></v-col>
	              <v-col cols="12" sm="12" md="12" lg="9" xl="9">
	                <v-textarea auto-grow  :counter="255" maxlength="255" v-model="conclusion" variant="outlined" flat rounded="lg"></v-textarea>
	              </v-col>
	            </v-row>
	          </v-container>
	        </v-col>
		</v-row>
	</v-container>
</template>