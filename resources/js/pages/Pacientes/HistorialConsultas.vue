<script setup type="text/javascript">
    import App from '@/layouts/app/App.vue';
	import { ref, onMounted } from 'vue';
	import { useGoTo } from 'vuetify';
	import { AplicacionStore } from '@/stores/Aplicacion'
	// import { RouteStore } from '@/Stores/Rutas';
	import moment from 'moment';
    import { router, Head, usePage } from '@inertiajs/vue3';
	const api = `/pacientes/{paciente}/consultas`;
	const api_consultorios = `/catalogos/consultorios`;
	const api_medicos = `/catalogos/medicos`;
	const consulta = "/consulta";
	//Poner un medico a todas las consultas menos a la de 67226abb00e1753bbc062024

			const goTo = useGoTo();
			onMounted(async()=>{
				await goTo(0,{});
				await ObtenerHistorialConsultas();

			});
			//Historial-Consultas Catalogos
			const CatConsultoriosFiltros = ref([]);
			const CatMedicosFiltros = ref([]);
			//Historial-Consultas Variables
            const page = usePage()
            const id_paciente = page.props.id_paciente
			const StoreAplicacion = AplicacionStore();
			const ActivadorMenu = ref(null)
			const BusquedaTexto = ref(null);
			const CargaInformacion = ref(false);
			const menu = ref(false);
			const FiltrosLoader = ref(0);
			const FiltrosContador = ref(0);
			const FiltrosContent = ref([]);
			const FiltrosAplicados = ref(0);
			const TotalConsultas = ref(0);
			const TotalConsultasPagina = ref(0);
			const PaginaInformacion = ref(1);
			const ListaConsultas = ref([]);
			const InformacionPaciente = ref([]);
			const FechaDesdeFiltro = ref(null);
			const FechaHastaFiltro = ref(null);
			const MedicosFiltros = ref([]);
			const ConsultoriosFiltros = ref([]);
			const OrdenarPor = ref(null);
			const OrdenInformacion = ref(null);
			//Historial-Consultas Metodos


			const NuevaConsulta = (id) => {
                console.log("Estos son los antecedentes");
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
			const OrdenarInformacion = async (columna)=>{
				OrdenarPor.value = columna;
				if(!OrdenInformacion.value){OrdenInformacion.value = 1;}else if(OrdenInformacion.value ==1){OrdenInformacion.value  = "-1";}else{OrdenInformacion.value =null;OrdenarPor.value=null;}
				//OrdenInformacion.value = orden;
				await ObtenerHistorialConsultas();
			}
			const LimpiarFiltros = async()=>{
				FiltrosLoader.value=true;
				ConsultoriosFiltros.value = [];
				FiltrosContador.value = 0;
				MedicosFiltros.value = [];
				FechaDesdeFiltro.value=null;
				FechaHastaFiltro.value=null;
				await ObtenerHistorialConsultas();
				FiltrosLoader.value=false;
				menu.value = false;
			}


			const toggleMenu= async ()=>{
 				if(!menu.value){
 					FiltrosLoader.value=true;
 					await axios.get(api_consultorios).then((response)=>{
	 				 	CatConsultoriosFiltros.value=response.data;
	 				 }).catch((error)=>{
	 				 	CatConsultoriosFiltros.value= [];
	 				 	console.log(error)
	 				 });
	 				 await axios.get(api_medicos).then((response)=>{
	 				 	CatMedicosFiltros.value=response.data;
	 				 }).catch((error)=>{
	 				 	CatMedicosFiltros.value= [];
	 				 	console.log(error)
	 				 });
	 				FiltrosLoader.value=false;
	 			}				
 				menu.value = !menu.value;  
			}

			const ObtenerDatosPorPagina = async (valor)=>{
		          PaginaInformacion.value = valor;
		          await ObtenerHistorialConsultas();
		        
		      }


			

			const ObtenerHistorialConsultas = async()=>{
				CargaInformacion.value=true;
				let FiltroFecha = 0;
				let FiltroMedicos = 0;
				let FiltroConsultorios = 0;
				if(FechaDesdeFiltro.value || FechaHastaFiltro.value){
					FiltroFecha = 1;
				}
				if(MedicosFiltros.value.length>0){
					FiltroMedicos = 1;
				}if(ConsultoriosFiltros.value.length>0){
					FiltroConsultorios = 1;
				}
				FiltrosContador.value = FiltroFecha + FiltroMedicos +FiltroConsultorios;
                console.log("Este es el id del paciente", id_paciente);
				var url = api.replace("{paciente}",id_paciente)
				url = `${url}/historial`;
				var parametros = {
					params:{
						pagina:PaginaInformacion.value,
						ordenarPor:OrdenarPor.value,
						orden:OrdenInformacion.value,
						busqueda:BusquedaTexto.value,
						ConsultoriosFiltros:ConsultoriosFiltros.value,
						MedicosFiltros:MedicosFiltros.value,
						FechaDesdeFiltro:FechaDesdeFiltro.value,
						FechaHastaFiltro:FechaHastaFiltro.value
					}
				}
				
					await axios.get(url,parametros).then((response)=>{
						menu.value=false;
						InformacionPaciente.value = response.data.paciente;
						CargaInformacion.value=false;
						ListaConsultas.value = response.data.consulta;
						TotalConsultas.value=response.data.consulta.total;
						TotalConsultasPagina.value=response.data.consulta.to;
					}).catch((error)=>{
						menu.value=false;
						CargaInformacion.value=false;
						console.log(error)
					});
				
			}
        
        function debounce(func, wait) {
            let timeout
            return function(...args) {
                clearTimeout(timeout)
                timeout = setTimeout(() => func.apply(this, args), wait)
            }
        }
			const ObtenerHistorialConsultasPeticion = debounce(ObtenerHistorialConsultas,350);
    const breadcrumbs = [
        { title: 'Inicio', href: '/' },
        { title: 'Pacientes', href: StoreAplicacion.currentUrl },
        { title: 'Historial de consultas', href: StoreAplicacion.currentUrl },
    ];			
</script>
<template>
	<Head title="Historial de consultas"></Head>
    <App :breadcrumbs="breadcrumbs">
	<v-container fluid class="bg-white my-5 rounded-lg  pa-0 px-0 px-sm-4 px-md-4 px-lg-4 px-xl-4 py-2 contenedor">
		<v-row class="d-flex align-start mx-2 border-b-thin" no-gutters>
            <v-col cols="12" class="text-primary pa-0 py-2">
            	<span style="font-size:0.775rem">Paciente</span>
            </v-col>
        </v-row>
        <v-row class="d-flex align-start mx-6 border-thin rounded-lg pa-0 py-4 bg-primary-lighten-4 mt-6">
            <v-col cols="12" class="text-grey-darken-2 poppins-medium pa-0 px-6">            	
            	<span class="text-primary-darken-2" style="font-size: 1.195rem;">
            		{{InformacionPaciente.nombre}}
            	</span>
            </v-col>
            <v-col cols="12" class="text-grey-darken-2 poppins-medium pa-0 px-6" style="font-size: 0.795rem;">{{InformacionPaciente.edad}}</v-col>
            <v-col cols="12" class="text-grey-darken-2 poppins-medium pa-0 px-6 mt-2" style="font-size: 0.795rem;" v-if="page.props.session.rolUsuario == 3">
            	<v-btn @click="router.get(`/pacientes/perfil/${InformacionPaciente.paciente}`)" variant="flat" size="small" class="text-none" color="primary">
            		<v-icon size="12">mdi-account</v-icon>
            		<span class="mx-2">Ver perfil</span>
            	</v-btn>
            </v-col>
        </v-row>

        <v-row class="d-flex align-center mx-2">
	  		<v-col>
	  			<v-container fluid>
	  				<v-row no-gutters>
	  					<v-col cols="12" class="poppins-medium text-primary-darken-4" style="font-size: 0.995rem;">Lista de consultas</v-col>
	  					<v-col cols="12" style="font-size: 0.895rem;" class="text-grey-darken-3">{{TotalConsultas}} consultas registradas</v-col>
	  				</v-row>
	  			</v-container>
	  		</v-col>
	  		<v-col class="d-flex justify-end" cols="auto" v-if="StoreAplicacion.RolUsuario==2 || StoreAplicacion.RolUsuario==4 || StoreAplicacion.RolUsuario==5">
	  			<v-btn color="primary" @click="NuevaConsulta(InformacionPaciente.paciente)" v-tooltip="{text:'Nueva consulta', location:'bottom'}">Nueva</v-btn>
	  		</v-col>
	  	</v-row>
	  	<v-row class="d-flex align-center mx-10 mt-8  mb-8" no-gutters>
	  		<v-col cols="12" class="mt-2">
	  			<v-container fluid class="pa-0">
	  				<v-row no-gutters class="d-flex align-end pa-0">
	  					<v-col cols="6" sm="8" md="8" lg="8" xl="8">
						    <v-menu
						    	:model-value="menu"
						      	:close-on-content-click="false"
						      	location="bottom"
						      	offset-y
						      	width="300"
						      	persistent
						      	:transition="false"
						      	class="elevation-2"
								scroll-strategy="block"
						    >
						    	<template #activator="{ props }">
						        <v-btn v-bind="props"
									ref="ActivadorMenu"
						        	class="pa-0"

						        	:loading="FiltrosLoader"
						        	color="white"
						          	rounded="lg"
						          	variant="tonal"
						          	@click="toggleMenu"
						        >
						        	<v-icon size="15" color="primary">mdi-filter-variant</v-icon>
						         	<span class="mx-2 text-primary text-none">Filtros</span>
						         	<v-badge
						         		v-if="FiltrosContador>0"
						         		floating
							          color="primary"
							          :content="FiltrosContador"
							        ></v-badge>
						        </v-btn>
						      </template>
						      <template #default="{ isActive, props }">
						        <v-card v-bind="props" width="350" class="rounded-lg">
						          <v-card-text >
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
										<v-expansion-panels  multiple v-model="FiltrosContent" class="px-4 mb-5 elevation-0" color="red-lighten-4" variant="accordion">
										 <v-expansion-panel
									        rounded="lg"
									        color="primary-lighten-4"
									        value="Fecha de registro"
									      >
									      	<v-expansion-panel-title>
									      		<span class="poppins-semibold text-primary-darken-3" style="font-size:0.825rem;">Fecha de registro</span>
									      	</v-expansion-panel-title>
									      	<v-expansion-panel-text>
									      		<v-row no-gutters>
									      			<v-col cols="12" class="px-4 mt-2"><span class="poppins-semibold text-primary-darken-3" style="font-size:0.725rem;">Desde</span></v-col>
													<v-col cols="12" class="px-4">
														<v-date-input placeholder="dd/mm/yyyy" flat  density="compact" variant="solo-filled"  v-model="FechaDesdeFiltro"  rounded="lg" hide-details  ok-text="Aceptar" cancel-text="Cancelar"></v-date-input>
													</v-col>
													<v-col cols="12" class="px-4 mt-2"><span class="poppins-semibold text-primary-darken-3" style="font-size:0.725rem;">Hasta</span></v-col>
													<v-col cols="12" class="px-4">
														<v-date-input placeholder="dd/mm/yyyy" flat  density="compact" :disabled="!FechaDesdeFiltro" variant="solo-filled" :max="moment().format('YYYY-MM-DD')" v-model="FechaHastaFiltro"  rounded="lg" hide-details  ok-text="Aceptar" cancel-text="Cancelar"></v-date-input>
													</v-col>
									      		</v-row>
									      	</v-expansion-panel-text>
									      </v-expansion-panel>
									      <v-expansion-panel
									        value="Medicos"
									        color="primary-lighten-4"
									        rounded="lg"
									      >
									      	<v-expansion-panel-title>
									      		<span class="poppins-semibold text-primary-darken-3" style="font-size:0.825rem;">Medicos</span>
									      	</v-expansion-panel-title>
									      	<v-expansion-panel-text>
									      		<v-row no-gutters>
													<v-col cols="12" class="px-4">
														<v-autocomplete multiple placeholder="(ej. Medico 001)" variant="solo-filled" v-model="MedicosFiltros" flat :items="CatMedicosFiltros" item-value="_id" item-title="nombreMedico" hide-details density="compact"></v-autocomplete>
													</v-col>
									      		</v-row>
									      	</v-expansion-panel-text>
									      </v-expansion-panel >
									      <v-expansion-panel
									      	rounded="lg"
									      	color="primary-lighten-4"
									        value="Consultorios"
									      >
									      	<v-expansion-panel-title>
									      		<span class="poppins-semibold text-primary-darken-3" style="font-size:0.825rem;">Consultorios</span>
									      	</v-expansion-panel-title>
									      	<v-expansion-panel-text>
									      		<v-row no-gutters>
													<v-col cols="12" class="px-4">
														<v-autocomplete multiple placeholder="(ej. Clínica 001)" variant="solo-filled" v-model="ConsultoriosFiltros" flat :items="CatConsultoriosFiltros" item-value="no_cc"  hide-details item-title="nombre" density="compact"></v-autocomplete>
													</v-col>
									      		</v-row>
									      	</v-expansion-panel-text>
									      </v-expansion-panel>
									    </v-expansion-panels>

										<v-row no-gutters class="my-2" >
											
											<v-col cols="6" class="d-flex justify-center ">
												<v-btn  rounded="lg" color="primary"
										          variant="tonal" width="95%" :loading="FiltrosLoader"  @click="LimpiarFiltros">
										          <span class="mx-1 text-none">Limpiar</span>
										        </v-btn>
											</v-col>
											<v-col cols="6" class="d-flex justify-center">
												<v-btn  rounded="lg" color="primary" width="95%" @click="ObtenerHistorialConsultasPeticion">Aplicar</v-btn>
											</v-col>
										</v-row>
						          </v-card-text>
						        </v-card>
						      </template>
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
					        @update:modelValue="ObtenerHistorialConsultasPeticion"
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
	  			<v-col class="mt-1" v-if="ListaConsultas.total>0" cols="12">
		  			<v-table density="compact" class="rounded-lg elevation-0 border-thin" >
		  				<thead>
		  					<tr>
		  						<th class="text-primary-darken-2 text-center">No</th>
		  						<th class="text-primary-darken-2" style="width: 220px;">
		  							<span class="mr-2">Identificador</span>
		  							<v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('idConsulta')">
	    								<v-icon color="primary">{{OrdenarPor == 'idConsulta' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'idConsulta' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							</v-btn>
		  						</th>
		  						<th class="text-primary-darken-2" style="width: 200px !important;">
		  						
		  						<span class="mr-2">Fecha de registro</span>
		  							<v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('fecha_creacion')">
	    								<v-icon color="primary">{{OrdenarPor == 'fecha_creacion' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'fecha_creacion' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							</v-btn>
		  						</th>
		  						<th class="text-primary-darken-2" style="width: 300px;">
		  							<span class="mr-2">Médico</span>
		  							<v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('NombreMedico')">
	    								<v-icon color="primary">{{OrdenarPor == 'NombreMedico' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'NombreMedico' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							</v-btn>
		  						</th>
		  						<th class="text-primary-darken-2">
		  							<span class="mr-2">Consultorio</span>
		  							<v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('consultorio')">
	    								<v-icon color="primary">{{OrdenarPor == 'consultorio' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'consultorio' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							</v-btn>
		  						</th>
		  						<th class="text-primary-darken-2"></th>
		  					</tr>
		  				</thead>
		  				<tbody>
		  					<template v-for="consulta,i in ListaConsultas.data">
		  						<v-hover>
	                        		<template v-slot:default="{ isHovering, props }">
	                        			<tr v-bind="props" :class="isHovering ? 'bg-grey-lighten-5 cursor-pointer':null">
					  						<td class="text-center" style="width: 100px !important;">{{consulta.index}}</td>
					  						<td class="pa-0">
					  							<v-container fluid >
			            							<v-row class="d-flex align-center" no-gutters>
			            								<v-col cols="12" class="text-uppercase"><span style="font-size:0.725rem;">{{consulta._id}}</span></v-col>
			            							</v-row>
		            							</v-container>
					  						</td>
					  						<td class="pa-0" >
					  							<v-container fluid style="min-width: 200px !important;">
			            							<v-row class="d-flex align-center" no-gutters>
			            								<v-col cols="12">
			            									<span>
			            										<v-icon size="16">mdi-clock</v-icon>
			            									</span>
			            									<span style="font-size:0.775rem;" class="ml-1">{{consulta.fecha_creacion}}</span>
			            								</v-col>
			            							</v-row>
		            							</v-container>
					  						</td>
					  						<td>
					  							<v-container fluid class="pa-0 d-flex align-center py-2" style="min-width: 300px;">
					  								<v-row no-gutters class="d-flex align-center justify-start">
					  									<v-col cols="12" class="text-start">
					  										<v-avatar color="primary-lighten-4" size="18">
					  											<v-icon size="16" color="primary">mdi-doctor</v-icon>
					  										</v-avatar>
					  										<span style="font-size:0.755rem;" class="mx-2 text-primary-darken-2 poppins-semibold">{{consulta.NombreMedico ? consulta.NombreMedico : 'S/D' }}</span>
					  									</v-col>
					  									<v-col cols="12">
					  										<span  style="font-size:0.725rem;" class="text-grey-darken-3">
		  														{{consulta.medico_titulado ? `Ced. ${consulta.medico_cedula}` : `No. ${consulta.medico_cuenta}`}}
		  													</span>
					  									</v-col>
					  								</v-row>
					  							</v-container>
					  						</td>
					  						<td class="pa-0"  >
					  							<v-container fluid class="pa-0">
			            							<v-row class="d-flex align-center" no-gutters style="max-width: 100%;">
			            								<v-col cols="12" class="text-no-wrap">
			            									<span>
			            										<v-icon size="12">mdi-domain</v-icon>
			            									</span>
			            									<span style="font-size:0.785rem" class="text-grey-darken-2 poppins-medium mx-1">{{consulta.consultorio ? consulta.consultorio : 'S/R'}}</span>
			            								</v-col>
			            							</v-row>
		            							</v-container>
					  							
					  						</td>
											
					  						<td style="width: 100px !important;">
					  							<v-btn size="small" icon variant="flat" @click="router.get(`/pacientes/resumen/${InformacionPaciente.paciente}/consulta/${consulta._id}`)">
					  								<v-icon color="primary">mdi-eye</v-icon>
					  							</v-btn>
					  						</td>
					  					</tr>
	                        		</template>
	                        	</v-hover>
		  					</template>
		  					
		  				</tbody>
		  				<tfoot>
		  					<tr class="elevation-1 bg-white">
		  						<td colspan="6" >
		  							<v-container fluid class="pa-0">
		  								<v-row no-gutters>
		  									<v-col class="d-flex align-center text-primary-darken-2">
		  										Mostrando {{TotalConsultasPagina}} de {{TotalConsultas}} registros
		  									</v-col>
		  									<v-col class="d-flex justify-end align-center">
		  										<v-pagination v-model="PaginaInformacion" density="compact" :length="ListaConsultas.last_page" :total-visible="5"  color="primary" @update:modelValue="ObtenerDatosPorPagina"></v-pagination>
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
	</v-container>
    </App>
</template>
<style scoped>
	.consultorio{
		overflow: hidden;
		display: flex;
		align-items: center;
		max-height: 40px;
		-webkit-line-clamp: 2;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		text-overflow: ellipsis;  
         white-space: normal; 
         font-size: 0.905rem; 
	}
</style>