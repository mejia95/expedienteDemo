<script setup >
	import {ref,onMounted,defineAsyncComponent,provide} from 'vue';
	import App from '@/layouts/app/App.vue';
	import { AplicacionStore } from '@/stores/Aplicacion';
	import Breadcrumbs from '@/components/Breadcrumbs.vue';
	import { useGoTo } from 'vuetify';
	import { router } from '@inertiajs/vue3';
	import axios from 'axios';

	const api = `/pacientes`;
	//const consulta = "/api/consulta";
    const AntecedentesComponente = defineAsyncComponent(() => import('@/pages/Pacientes/ComponentAntecedentes.vue'))
	const OpcionesPaciente = defineAsyncComponent(() => import('@/pages/Pacientes/OpcionesListaPacientes.vue'))

        // components:{
        //     AntecedentesComponente,
		// 	OpcionesPaciente
        // },
			
	const breadcrumbs = [
        { title: 'Inicio', href: '/' },
		{ title: 'Antecedentes', href: '/pacientes/antecedentes' },
    ];

            const AntecedentesComponenteRef = ref(null);
			//const router = useRouter();
			const goTo = useGoTo();
            const store = AplicacionStore();
			onMounted(async ()=>{
				await goTo(0,{});
				await ObtenerPacientes();
			})
            const MostrarAntecedentesModal = ref(false);

			//Pacientes Catalogos
			const CatPacientes = ref([]);

			//Pacientes Metodos
			const OrdenarInformacion = async (columna)=>{
				OrdenarPor.value = columna;
				if(!OrdenInformacion.value){OrdenInformacion.value = 1;}else if(OrdenInformacion.value ==1){OrdenInformacion.value  = "-1";}else{OrdenInformacion.value =null;OrdenarPor.value=null;}
				//OrdenInformacion.value = orden;
				await ObtenerPacientes();
			}

			const ObtenerDatosPorPagina = async (valor)=>{
          PaginaInformacion.value = valor;
          await ObtenerPacientes();

      }

      const ConsultarAntecedente = async (paciente) => {
          const url = `${consulta}/consulta/antecedente`;
            //La busqueda de los antecedentes se modifico
            const datos = {
              params: {
                id_consulta: paciente
              }
            };
            
            /*const datos = {
              params: {
                id_consulta:id_consulta
              }
            };*/
            
          await axios.get(url,datos).then((response)=>{
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
          }).catch((error)=>{
            console.log(error);
            store.cirugias_hospitalizaciones = null;
            store.inmunizaciones = null;
            store.antecedentesPerPatologicos = null;
            store.antecedentesNoPatologicos = null;
            store.antecedentesGinecoObs = null;
            store.toxicomanias = null;
            store.alergias = null;
            store.cardiovascular = null;
            store.respiratorio = null;
            store.NefroUrologico = null;
            store.Neurologico = null;
            store.Hematologicos = null;
            store.Ginecologicos = null;
            store.Infectologicos = null;
            store.Endocrinologicos = null;
            store.Quirurgicos = null;
            store.Alergicos = null;
            store.SocioecEpide = null;
            store.AntecedentesHeredo = null;
          });
          
        }

      const ObtenerAntecedentesPacientes = async(paciente)=>{
        console.log("paciente");
        console.log(paciente);
        await ConsultarAntecedente(paciente.id);
        store.AntecedentesPaciente = {correo:paciente.mail,celular:paciente.telefono_celular,telefono:paciente.telefono,edad:paciente.edad,pId:paciente.pid,nombre:paciente.paciente,genero:paciente.genero,tipo_sangre:paciente.tipo_sangre,estado_civil:paciente.estado_civil}
        MostrarAntecedentesModal.value=true;
      }

      const LimpiarFiltrosBusqueda = async()=>{
      	FiltrosLoader.value=true;
      	FechaDesdeFiltro.value=null;
      	FechaHastaFiltro.value = null;
		NoConsultasCondicionFiltro.value=null;
		NoConsultasFiltro.value=null;
		FiltrosAplicados.value =null;

		await ObtenerPacientes();
		menu.value=false;
		FiltrosLoader.value=false;
      }


      const NuevaConsulta = (id) => {
                console.log("Estos son los antecedentes");
				// store.opcion_faltante = 0;
				// store.opcion_clinica_faltante = 0;
                const url = `${consulta}/nueva`;
                axios.post(url,{id:id}).then((response)=>{
                    console.log("Este es el estatus");
                    const id_consulta = response.data.id_consulta;
                    if(response.data.estatus == 1){
                        router.push(`/consulta/${id_consulta}`);
                    }
                }).catch((error)=>{
                    console.log(error)
                });

            }

      const AplicarFiltrosBusqueda = async()=>{
      	var contador = 0;
      	FiltrosLoader.value=true;
      	if(FechaDesdeFiltro.value || FechaHastaFiltro.value){
      		contador++;
      	}
      	if(NoConsultasCondicionFiltro.value && NoConsultasFiltro.value>=0){
      		contador++;

      	}
      	FiltrosAplicados.value = contador;
      	PaginaInformacion.value=1;
      	await ObtenerPacientes();
      	FiltrosLoader.value=false;
      	menu.value=false;
      }
			const ObtenerPacientes = async ()=>{
				//BusquedaTexto.value = value ? value:null;
				await goTo(0,{});
				CargaInformacion.value=true;
				const url = `${api}/lista-pacientes`;
				var parametros = {params:{pagina :PaginaInformacion.value,ordenarPor:OrdenarPor.value,orden:OrdenInformacion.value,FiltroDesde:FechaDesdeFiltro.value,FiltroHasta:FechaHastaFiltro.value,NoConsultasCondicion:NoConsultasCondicionFiltro.value,NoConsultas:NoConsultasFiltro.value,busqueda:BusquedaTexto.value }}
				
					await axios.get(url,parametros).then((response)=>{
						CargaInformacion.value=false;
						CatPacientes.value= response.data;
						TotalPacientes.value=response.data.total;
						TotalPacientesPagina.value=response.data.to;
					}).catch((error)=>{
						console.log(error);
					})
				

			}
			//Pacientes Variables
			const menu = ref(false)
			const StoreAplicacion = AplicacionStore();
			/**
			 * Filtros de busqueda
			 */
			const FiltrosAplicados = ref(0);
			const FiltrosLoader = ref(false);
			const FechaDesdeFiltro = ref(null);
			const FechaHastaFiltro = ref(null);
			const NoConsultasCondicionFiltro = ref(null);
			const NoConsultasFiltro = ref(null);
			const OperadoresConsultas = ref([
				{etiqueta: "Igual a ", valor:"1"},
				{etiqueta: "Mayor a ", valor:"2"},
				{etiqueta: "Menor a ", valor:"3"},
			]);
			const BusquedaTexto = ref(null)
			const CargaInformacion=ref(false);
			const SubSeccionSeleccionada = ref("Pacientes");
			const TotalPacientes = ref(0);
			const TotalPacientesPagina = ref(0);
			const PaginaInformacion = ref(1);
			const OrdenarPor = ref(null);
			const OrdenInformacion = ref(null);
			
            const CerrarModalAntecedentes = ()=>{
                MostrarAntecedentesModal.value = false;
            }


			const FiltrosBusquedaPacientes  =  ()=>{
				PaginaInformacion.value=1;
				ObtenerPacientes();
			}

			function debounce(func, wait) {
        		let timeout
        		return function(...args) {
            	clearTimeout(timeout)
            	timeout = setTimeout(() => func.apply(this, args), wait)
        		}
    		}

			const ObtenerPacientesPeticion = debounce(FiltrosBusquedaPacientes,340);

            provide('CerrarModal',CerrarModalAntecedentes)
           // return {ObtenerPacientesPeticion,CerrarModalAntecedentes,AntecedentesComponenteRef,ConsultarAntecedente,ObtenerAntecedentesPacientes,MostrarAntecedentesModal,SubSeccionSeleccionada,CatPacientes,TotalPacientes,TotalPacientesPagina,ObtenerDatosPorPagina,OrdenInformacion,OrdenarInformacion,OrdenarPor,menu,OperadoresConsultas,NoConsultasFiltro,NoConsultasCondicionFiltro,FechaDesdeFiltro,
			//		FechaHastaFiltro,FiltrosAplicados,AplicarFiltrosBusqueda,LimpiarFiltrosBusqueda,BusquedaTexto,ObtenerPacientes,CargaInformacion,StoreAplicacion,NuevaConsulta,PaginaInformacion,FiltrosLoader,goTo}
		
</script>

<template>
	<App :breadcrumbs="breadcrumbs">    
		<v-container fluid>
			<v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">Antecedentes de pacientes</span>
                </v-col>
                <v-col cols="12">
                    <div class="poppins-light text-grey-darken-1">
                        Gestión de antecedentes: consulta la información y actualización de datos.
                    </div>
                </v-col>
            </v-row>  
			<v-row >
            <!--<v-col cols="4" class="text-grey-darken-2 poppins-medium pa-0 px-6 d-flex justify-end align-center" v-if="!CargaInformacion">
            	<v-btn color="primary" to="/pacientes/crear">Nuevo</v-btn>
            </v-col>-->
        </v-row>
  	<v-row class="d-flex align-center mx-1 mx-sm-10 mx-md-10 mx-lg-10 mx-xl-10 mt-8  mb-8"  no-gutters>
  		<v-col cols="12" class="mt-2">
  			<v-container fluid class="pa-0">
  				<v-row no-gutters class="d-flex align-end pa-0">
  					<v-col cols="6" sm="8" md="8" lg="8" xl="8">

  						<v-menu
					      v-model="menu"
					      persistent
					      scroll-strategy="block"
					      :close-on-content-click="false"
					      location="bottom"
						  offset-y
					    >
					      <template v-slot:activator="{ props }">
					        <v-btn
					        	class="pa-0"
					          color="white"
					          rounded="lg"
					          variant="tonal"
					          v-bind="props"
					        >
					        	<v-icon size="15" color="primary">mdi-filter-variant</v-icon>
					         	<span class="mx-2 text-primary text-none">Filtros</span>
					         	<v-badge
					         		v-if="FiltrosAplicados>0"
					         		floating
						          color="primary"
						          :content="FiltrosAplicados"
						        ></v-badge>
					        </v-btn>
					      </template>

					      <v-card width="350" class="rounded-xl" >
					      	<v-container fluid>
					      		<v-row class="d-flex align-center">
											<v-col>
												<span class="mx-4 poppins-semibold text-primary-darken-3" style="font-size:1.175rem">Filtros</span>
											</v-col>
											<v-col class="d-flex justify-end">
												<v-btn icon variant="flat" size="small" @click="menu=false">
													<v-icon color="primary">mdi-close</v-icon>
												</v-btn>
											</v-col>
										</v-row>
					      		<v-row no-gutters >
					      			<v-col>
					      				<v-container fluid>
					      					<v-row>
					      						<v-col><span class="poppins-semibold text-primary-darken-3" style="font-size:0.825rem;">Fecha de nacimiento</span></v-col>
					      					</v-row>
					      					<v-row class="px-4" no-gutters>
					      						<v-col cols="12" class="mt-2">
					      							<span style="font-size:0.775rem">Desde</span>
					      							<!-- <v-date-input flat placeholder="dd/mm/yyyy" density="compact" variant="solo-filled"  v-model="FechaDesdeFiltro"  rounded="lg" hide-details  ok-text="Aceptar" cancel-text="Cancelar"></v-date-input> -->
													  <v-menu
                                                            v-model="menuFechaDesde"
                                                            :close-on-content-click="false"
                                                            transition="scale-transition"
                                                            offset-y
                                                            min-width="auto"
                                                            >
                                                            <template #activator="{ props }">
                                                                <v-text-field
                                                                v-model="FechaDesdeFiltroFormateada"
                                                                placeholder="dd/mm/yyyy"
                                                                readonly
                                                                v-bind="props"
                                                                variant="solo-filled"
                                                                density="compact"
                                                                rounded="lg"
                                                                hide-details
                                                                ></v-text-field>
                                                            </template>
                                                            <v-date-picker
                                                                v-model="FechaDesdeFiltro"
                                                                locale="es"
                                                                color="primary"
                                                                 @update:model-value="menuFechaDesde = false"
                                                            >
                                                            </v-date-picker>
                                                        </v-menu>
					      						</v-col>
					      						<v-col  cols="12" class="mt-2">
					      							<span style="font-size:0.775rem">Hasta</span>
					      							<!-- <v-date-input placeholder="dd/mm/yyyy" :min="FechaDesdeFiltro" flat :disabled="!FechaDesdeFiltro" density="compact" variant="solo-filled"  v-model="FechaHastaFiltro"  rounded="lg" hide-details  ok-text="Aceptar" cancel-text="Cancelar"></v-date-input> -->
													  <v-menu
                                                            v-model="menuFechaHasta"
                                                            :close-on-content-click="false"
                                                            transition="scale-transition"
                                                            offset-y
                                                            min-width="auto"
                                                            >
                                                            <template #activator="{ props }">
                                                                <v-text-field
                                                                v-model="FechaHastaFiltroFormateada"
                                                                placeholder="dd/mm/yyyy"
                                                                readonly
                                                                v-bind="props"
                                                                variant="solo-filled"
                                                                density="compact"
                                                                rounded="lg"
                                                                :disabled="!FechaDesdeFiltro"
                                                                hide-details
                                                                ></v-text-field>
                                                            </template>
                                                            <v-date-picker
                                                                v-model="FechaHastaFiltro"
                                                                locale="es"
                                                                color="primary"
                                                                :min="FechaDesdeFiltro"
                                                                
                                                                 @update:model-value="menuFechaHasta = false"
                                                            >
                                                            </v-date-picker>
                                                        </v-menu>
					      						</v-col>
					      					</v-row>
					      					<v-row>

					      					</v-row>
					      				</v-container>
					      			</v-col>
					      		</v-row>
					      		<v-row no-gutters class="my-5">
					      			<v-col cols="12" sm="6" md="6" lg="6" xl="6" class="d-flex justify-center order-sm-1 order-md-1 order-lg-1 order-xl-1">
					      				<v-btn  rounded="lg" color="primary" width="85%" :loading="FiltrosLoader" @click="AplicarFiltrosBusqueda">Aplicar</v-btn>
					      			</v-col>
					      			<v-col cols="12" sm="6" md="6" lg="6" xl="6" class="mt-1 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0 d-flex justify-center order-sm-0 order-md-0 order-lg-0 order-xl-0">
					      				<v-btn  width="85%" @click="LimpiarFiltrosBusqueda" :loading="FiltrosLoader" rounded="lg" variant="tonal" color="primary">
					      					<v-icon size="15">mdi-trash-can</v-icon>
					      					<span class="mx-1 text-none">Limpiar</span>

					      				</v-btn>

					      			</v-col>
					      		</v-row>
					      	</v-container>
					      </v-card>
					    </v-menu>
  					</v-col>
  					<v-col cols="6" sm="4" md="4" lg="4" xl="4" class="d-flex justify-end">
  						<v-text-field
  						  density="compact"
				        label="Buscar"
				        prepend-inner-icon="mdi-magnify"
				        variant="solo-filled"
				        flat
				        :readonly="CargaInformacion"
				        v-model="BusquedaTexto"
				        @update:modelValue="ObtenerPacientesPeticion"
				        hide-details
				        rounded="lg"
				        single-line
				      ></v-text-field>
  					</v-col>
  				</v-row>
  			</v-container>
  		</v-col>
  		<template  v-if="CargaInformacion">
  			<v-col cols="12">
	  			<v-skeleton-loader type="table-tbody"></v-skeleton-loader>
	  		</v-col>
  		</template>
  		<template v-else>
	  		<v-col class="mt-1" v-if="parseInt(CatPacientes.to)>0" cols="12">
	  			<v-table density="compact" id="tabla"  class="rounded-lg elevation-0 border-thin">
	            <thead>
	              <tr>
	              	<th class="text-primary">#</th>
	    						<th class="text-primary">
	    							<span class="mr-2">Paciente</span>
	    							<v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('nombreCompleto')">
	    								<v-icon color="primary">{{OrdenarPor == 'nombreCompleto' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'nombreCompleto' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							</v-btn>
	    						</th>
	    						<th class="text-primary">
	    							<span class="mr-2">Fecha nacimiento</span>
	    							<v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('fecha_nacimiento')">
	    								<v-icon color="primary">{{OrdenarPor == 'fecha_nacimiento' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'fecha_nacimiento' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							</v-btn>
	    						</th>
	    						<!-- <th class="text-primary">
	    							<span class="mr-2">Contacto</span>
	    							<v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('correo')">
	    								<v-icon color="primary">{{OrdenarPor == 'correo' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'correo' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							</v-btn>
	    						</th> -->
	    						<th class="text-primary">Opciones</th>
	              </tr>
	            </thead>
	              <tbody>
	            					<tr v-for="paciente,index in CatPacientes.data">
	            						<td>{{paciente.index}}</td>
	            						<td class="pa-0">
	            							    <v-container fluid >
													<v-row class="d-flex align-center" no-gutters>
														<v-col cols="12" class="text-uppercase"><span class="poppins-semibold" style="font-size:0.710rem;">{{paciente.paciente}} </span></v-col>
													</v-row>
													<v-row class="d-flex align-center pa-0 mt-n1" no-gutters>
														<v-col cols="12">
															<v-icon size="11">mdi-mail</v-icon>
															<span class="mx-1" style="font-size:0.655rem">{{paciente.mail ? paciente.mail : 'S/C' }}</span>
														</v-col>

														</v-row>
														<v-row class="d-flex align-center mt-n1 pa-0" no-gutters >
															<v-col cols="12" >
																<v-icon size="11" class="mr-2">mdi-phone</v-icon>
																<span class="mx-1" style="font-size:0.655rem" v-if="paciente.telefono ||paciente.telefono_celular">Cel. {{paciente.telefono}}, {{paciente.telefono_celular}}</span><span style="font-size: 0.685rem;" v-else>S/T</span>
															</v-col>
														</v-row>
												</v-container>
	            						</td>
	            						<td class="pa-0">
	            							<v-container fluid >
		            							<v-row class="d-flex align-center" no-gutters>
		            								<v-col cols="12"><span style="font-size:0.785rem">{{paciente.fecha_nacimiento}}</span></v-col>
		            							</v-row>
	            							</v-container>
	            						</td>
	            						<!-- <td class="pa-0">
	            							<v-container fluid class="pa-0 px-4" >
		            							<v-row class="d-flex align-center mt-1 pa-0" no-gutters>
		            								<v-col cols="12">
		            									<v-icon size="11">mdi-mail</v-icon>
		            									<span class="mx-1" style="font-size:0.655rem">Correo</span>
		            								</v-col>
		            								<v-col cols="12" ><span style="font-size: 0.685rem;">{{paciente.mail ? paciente.mail : 'S/C' }}</span></v-col>

		            							</v-row>
		            							<v-row class="d-flex align-center mt-1 mb-1 pa-0" no-gutters >
		            								<v-col cols="12">
		            									<v-icon size="11">mdi-phone</v-icon>
		            									<span class="mx-1" style="font-size:0.655rem">Teléfono(s)</span>
		            								</v-col>
		            								<v-col v-if="paciente.telefono ||paciente.telefono_celular">
		            									<span style="font-size: 0.685rem;">{{paciente.telefono}}</span>
		            									<span v-if="paciente.telefono_celular" style="font-size: 0.685rem;" class="mx-2">Cel. {{paciente.telefono_celular}}</span>
		            								</v-col>
		            								<v-col v-else>
		            									<span style="font-size: 0.685rem;">S/T</span>
		            								</v-col>
		            							</v-row>
	            							</v-container>
	            						</td> -->
	            						<!--<td>
	            							<v-chip color="primary" class="ma-1 cursor-pointer" @click="$router.push(`/pacientes/consultas/${paciente.pid}`)">
	            								<v-avatar size="25" class="pa-3 text-center" icon color="primary">
	      												<span class="poppins-semibold chip-avatar">{{paciente.consultas >=100 ? '99+' : paciente.consultas}}</span>
	      											</v-avatar>
	      											<span class="mx-1" >consulta(s)</span>
	      										</v-chip>
	            						</td>-->
	            						<td class="pa-0">
	            							<OpcionesPaciente :paciente="paciente" :solo_antecedentes="true"></OpcionesPaciente>
	            						</td>
	            					</tr>
	            				</tbody>

	            <tfoot>
	          <tr class="elevation-1 bg-white">
	            <td  colspan="6" >
	            	<v-container fluid class="pa-0">
	            		<v-row no-gutters>
	            			<v-col class="d-flex align-center text-primary">
	            				Mostrando {{TotalPacientesPagina}} de {{TotalPacientes}} registros
	            			</v-col>
	            			<v-col class="d-flex justify-end">
	            				<v-pagination v-model="PaginaInformacion" @update:modelValue="ObtenerDatosPorPagina" color="primary"   :length="CatPacientes.last_page" :total-visible="5"></v-pagination>
	            			</v-col>
	            		</v-row>
	            	</v-container>

	            </td>
	          </tr>
	        </tfoot>
	        </v-table>
	  		</v-col>
	  		<v-col v-else cols="12">
	  			<v-empty-state
		  				color="primary"
					    icon="mdi-magnify"
					  >
					  	<template v-slot:title>
					  		<p class="text-primary-darken-2 poppins-semibold">
					  			Sin resultados
					  		</p>
					  	</template>
					  	<template v-slot:text>
					  		<p class="text-grey-darken-3">
					  			No se encontraron datos que coincidan con los filtros aplicados o no hay información disponible en este momento.
					  		</p>
					  	</template>
					  </v-empty-state>
	  		</v-col>
  		</template>
  	</v-row>
        <v-dialog fullscreen v-model="MostrarAntecedentesModal">
           
            <v-card >
                <AntecedentesComponente ></AntecedentesComponente>
                <v-card-actions>
                    <v-btn
                    text="Cerrar"
                    @click="MostrarAntecedentesModal = false"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
		</v-container>
	</App>
</template>
