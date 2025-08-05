<script setup >
    import { Head } from '@inertiajs/vue3';
    import { ref, defineAsyncComponent, onMounted, computed } from 'vue'
    import App from '@/layouts/app/App.vue';
    import Breadcrumbs from '@/components/Breadcrumbs.vue';
    import { useGoTo, useDisplay } from 'vuetify';
    import axios from 'axios';
    import { AplicacionStore } from '@/stores/Aplicacion';
    import { router,usePage } from '@inertiajs/vue3';

    
    const OpcionesPaciente = defineAsyncComponent(() => import('@/pages/Pacientes/OpcionesListaPacientes.vue'));

    const api = `/pacientes`;

    const goTo = useGoTo();
    const PaginaInformacion = ref(1);
    const CatPacientes = ref([]);
    const TotalPacientes = ref(0);
    const TotalPacientesPagina = ref(0);
    const OrdenarPor = ref(null);
    const OrdenInformacion = ref(null);
    const FechaDesdeFiltro = ref(null);
	const FechaHastaFiltro = ref(null);
    const NoConsultasCondicionFiltro = ref(null);
	const NoConsultasFiltro = ref(null);
	const BusquedaTexto = ref(null)
    const CargaInformacion=ref(false);
    const menu = ref(false);
    const FiltrosAplicados = ref(null);
    const StoreAplicacion = AplicacionStore();
    const menuFechaDesde = ref(false);
    const menuFechaHasta = ref(false);
    const OperadoresConsultas = ref([
		{etiqueta: "Igual a ", valor:"1"},
		{etiqueta: "Mayor a ", valor:"2"},
		{etiqueta: "Menor a ", valor:"3"},
	]);
    const FiltrosLoader = ref(false);

	onMounted(async ()=>{
		await goTo(0,{});
		await ObtenerPacientes();
        StoreAplicacion.currentUrl = usePage().url;
	})

    const breadcrumbs = [
        { title: 'Inicio', href: '/' },
        { title: 'Todos los pacientes ', href: '/pacientes/todos' },
    ];

    const FechaHastaFiltroFormateada = computed(() => {
        if (!FechaHastaFiltro.value) return ''
        if (typeof FechaHastaFiltro.value === 'string') {
            const [year, month, day] = FechaHastaFiltro.value.split('-')
            return `${day}/${month}/${year}`
        }
        // Si es Date u objeto, formatea con Intl.DateTimeFormat
        if (FechaHastaFiltro.value instanceof Date) {
            return new Intl.DateTimeFormat('es-MX').format(FechaHastaFiltro.value)
        }
        return ''
    });

    const FechaDesdeFiltroFormateada = computed(() => {
        if (!FechaDesdeFiltro.value) return ''
        if (typeof FechaDesdeFiltro.value === 'string') {
            const [year, month, day] = FechaDesdeFiltro.value.split('-')
            return `${day}/${month}/${year}`
        }
        // Si es Date u objeto, formatea con Intl.DateTimeFormat
        if (FechaDesdeFiltro.value instanceof Date) {
            return new Intl.DateTimeFormat('es-MX').format(FechaDesdeFiltro.value)
        }
        return ''
    });

    const irANuevoPaciente = () => {
        router.get('/pacientes/nuevo');
    }
    const ObtenerPacientes = async ()=>{
        await goTo(0,{});
        const url = `${api}/lista-pacientes`;        
        var parametros = {params:{
            pagina :PaginaInformacion.value,
            ordenarPor:OrdenarPor.value,
            orden:OrdenInformacion.value,
            FiltroDesde:FechaDesdeFiltro.value,
            FiltroHasta:FechaHastaFiltro.value,
            NoConsultasCondicion:NoConsultasCondicionFiltro.value,
            NoConsultas:NoConsultasFiltro.value,
            busqueda:BusquedaTexto.value 
        }}
        //console.log('Obteniendo pacientes desde: ', parametros);
            await axios.get(url,parametros).then((response)=>{
                CargaInformacion.value=false;
                CatPacientes.value= response.data;
                TotalPacientes.value=response.data.total;
                TotalPacientesPagina.value=response.data.to;
            }).catch((error)=>{
                console.log(error);
            })
	}

    const exportarExcel = async () => {
        try {
            const response = await axios.get('/exportar-pacientes', {
            responseType: 'blob' // muy importante
            })

            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', 'pacientes.xlsx')
            document.body.appendChild(link)
            link.click()
            link.remove()
        } catch (error) {
            console.error('Error exportando:', error)
        }
    }

    const ObtenerDatosPorPagina = async (valor)=>{
          PaginaInformacion.value = valor;
          await ObtenerPacientes();
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

    const OrdenarInformacion = async (columna)=>{
		OrdenarPor.value = columna;
		if(!OrdenInformacion.value){OrdenInformacion.value = 1;}else if(OrdenInformacion.value ==1){OrdenInformacion.value  = "-1";}else{OrdenInformacion.value =null;OrdenarPor.value=null;}
		
		await ObtenerPacientes();
	}

    const FiltrosBusquedaPacientes  =  ()=>{		
        //console.log('FiltrosBusquedaPacientes', BusquedaTexto.value);
		ObtenerPacientes();
	}
    
    function debounce(func, wait) {
        let timeout
        return function(...args) {
            clearTimeout(timeout)
            timeout = setTimeout(() => func.apply(this, args), wait)
        }
    }
    // const ObtenerPacientesPeticion = StoreAplicacion.debounce(FiltrosBusquedaPacientes,340);
    const ObtenerPacientesPeticion = debounce(FiltrosBusquedaPacientes, 340)
</script>

<template>
    <App :breadcrumbs="breadcrumbs">
        <v-container fluid>
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">Información de pacientes </span>
                </v-col>
                <v-col cols="12">
                    <div class="poppins-light text-grey-darken-1">
                        Gestión de pacientes: agregar nuevos pacientes, consulta la información y actualización de datos.
                    </div>
                </v-col>
                 <!-- <v-col cols="12" class="d-flex justify-end mt-lg-n10">
                    <v-btn color="primary" rounded="lg" @click="irANuevoPaciente" class="ma-2">
                        Agregar Paciente
                    </v-btn>
                </v-col> -->
            </v-row>                           
            <v-row class="mx-5">
                <v-col cols="12" class="mt-2">
                    <v-container fluid class="pa-0">
                    <v-row no-gutters class="d-flex align-end pa-0">
                        <v-col cols="4" sm="8" md="8" lg="8" xl="8">

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
                                                        <!-- <v-text-field type="date" placeholder="dd/mm/yyyy" :min="FechaDesdeFiltro" flat :disabled="!FechaDesdeFiltro" density="compact" variant="solo-filled"  v-model="FechaHastaFiltro"  rounded="lg" hide-details  ok-text="Aceptar" cancel-text="Cancelar"></v-text-field> -->
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
                                                    <v-col cols="12"><span class="poppins-semibold text-primary-darken-3" style="font-size:0.825rem;">No. Consultas</span></v-col>
                                                </v-row>
                                                <v-row class="px-4" no-gutters>
                                                    <v-col cols="8" class="mr-2 mt-3">
                                                        <v-select variant="solo-filled" v-model="NoConsultasCondicionFiltro" :items="OperadoresConsultas" item-title="etiqueta" item-value="valor" hide-details density="compact" flat></v-select>
                                                    </v-col>
                                                    <v-col class="mt-3">
                                                        <v-text-field
                                                        v-model="NoConsultasFiltro"
                                                        density="compact"
                                                        :disabled="!NoConsultasCondicionFiltro"
                                                        type="number"
                                                        variant="solo-filled"
                                                        hide-details
                                                        flat
                                                        min="0"
                                                    ></v-text-field>
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
                            variant="outlined"
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
                <v-col v-if="TotalPacientes>0">
                    <v-table density="compact" class="rounded-lg elevation-0 border-thin">
                        <thead>
                            <tr>
                                <th class="text-primary">#</th>
                                <th class="text-primary">
                                    <span class="mr-2">Paciente </span>
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
                                <th class="text-primary">
                                    <span class="mr-2">Consultas</span>
	    							<v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('TotalConsultas')">
	    								<v-icon color="primary">{{OrdenarPor == 'TotalConsultas' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'TotalConsultas' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							</v-btn>
                                </th>
                                <th class="text-primary">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>                                                                       
                             <tr v-for="paciente in CatPacientes.data">
                                <td>
                                    {{paciente.index}}
                                </td>
                                <td>	          
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
                                <td>
                                    <v-container fluid >
		            					<v-row class="d-flex align-center" no-gutters>
		            						<v-col cols="12"><span style="font-size:0.785rem">{{paciente.fecha_nacimiento}}</span></v-col>
		            					</v-row>
	            				    </v-container>
                                </td> 
                                <!-- <td>
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
                                <td>
                                
                                    <v-chip color="primary" class="ma-1 cursor-pointer" @click="$router.push(`/pacientes/consultas/${paciente.pid}`)">
	            					    <v-avatar size="25" class="pa-3 text-center" icon color="primary">
	      							        <span class="poppins-semibold chip-avatar">{{paciente.consultas >=100 ? '99+' : paciente.consultas}}</span>
	      							    </v-avatar>
	      								    <span class="mx-1" >consulta(s)</span>
	      							</v-chip>
                                </td>
                                <td>                                    
                                    <!-- <OpcionesPaciente :paciente="paciente"></OpcionesPaciente> -->
                                     <OpcionesPaciente :paciente="paciente"></OpcionesPaciente> 
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
					  		<p class="text-primary poppins-semibold">
					  			Sin resultados
					  		</p>
					  	</template>
					  	<template v-slot:text>
					  		<p class="text-primary">
					  			No se encontraron datos que coincidan con los filtros aplicados o no hay información disponible en este momento.
					  		</p>
					  	</template>
					</v-empty-state>
	  		    </v-col>
            </v-row>
        </v-container>
    </App>
  </template>