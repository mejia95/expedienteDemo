<script setup >
    import { ref, defineAsyncComponent, onMounted, computed, watch,provide } from 'vue';
    import App from '@/layouts/app/App.vue';
    import DialogMedicamentos from '@/pages/Medicamentos/DialogMedicamentos.vue';
    import axios from 'axios';
    import { useGoTo, useDisplay } from 'vuetify';
    import Alerta from '@/components/AppAlerta.vue';
    import { AplicacionStore } from '@/stores/Aplicacion';

    const api_catalogos = '/catalogos';
    const goTo = useGoTo();
    const store = AplicacionStore();
    const dialog_medicamento = ref(false);    
    const medicamentoSeleccionado = ref(null);
    const PaginaInformacion = ref(1);
    const OrdenarPor = ref(null);
    const OrdenInformacion = ref(null);
    const TotalMedicamentos = ref(0);
    const TotalMedicamentosPagina = ref(0);    
    const medicamentos = ref([]);
    const selectedMedicamentos = ref([]);
    const selectAll = ref(false); 
    const BusquedaTexto = ref(null);
    const loader_info = ref(false);
    const lista_medicamentos_seleccionados = ref([]);
    const MensajeAppAlerta = ref(null);
    const AlertaCallback = ref(null);
    const medicamentos_visibles = ref([]);

    onMounted(async ()=>{
		await goTo(0,{});
		await ObterMedicamentos();
        selectAll.value = selectedMedicamentos.value.length == TotalMedicamentos.value ? true : false;
        //StoreAplicacion.currentUrl = usePage().url;
	})

    const ObterMedicamentos =  async () =>{
        await goTo(0,{});
        loader_info.value = true;
         var parametros = {params:{
            pagina :PaginaInformacion.value,
            ordenarPor:OrdenarPor.value,
            orden:OrdenInformacion.value,
            busqueda:BusquedaTexto.value 
         }};
         console.log('Parametros:', parametros);
        const url = `${api_catalogos}/medicamentos`;  
        await axios.get(url, parametros).then((response)=>{
            medicamentos.value = response.data && response.data.data ? response.data : { data: [] };
            TotalMedicamentos.value=response.data.total;
            TotalMedicamentosPagina.value=response.data.to;  
            loader_info.value = false;        
            lista_medicamentos_seleccionados.value = response.data.medicamentos_seleccionados ? response.data.medicamentos_seleccionados : [];
            if(!selectAll.value) {
               lista_medicamentos_seleccionados.value.forEach((item)=>{
                    if(!selectedMedicamentos.value.includes(item)){
                        selectedMedicamentos.value.push(item);
                    }
                });
            }
            console.log("Este es el response de medicamentos:", response.data);
        }).catch((error)=>{
            console.log(error);
            loader_info.value = false;
        })
    }

    const OrdenarInformacion = async (columna)=>{
		OrdenarPor.value = columna;
		if(!OrdenInformacion.value){OrdenInformacion.value = 1;}else if(OrdenInformacion.value ==1){OrdenInformacion.value  = "-1";}else{OrdenInformacion.value =null;OrdenarPor.value=null;}
		
		await ObterMedicamentos();
	}


    const ObtenerDatosPorPagina = async (valor)=>{
        PaginaInformacion.value = valor;
        await ObterMedicamentos();
    }
  
    watch(selectedMedicamentos, (nuevoValor) => {
        //console.log('Seleccionados:', nuevoValor);
    });

    const agregaraSeleccionados = (value) => {
        selectAll.value = selectedMedicamentos.value.length == TotalMedicamentos.value ? true : false;
        // if (value) {
        //     // Si se selecciona un medicamento, se agrega a la lista de seleccionados
        //     if (!selectedMedicamentos.value.includes(value)) {
        //         selectedMedicamentos.value.push(value);
        //     }
        // } else {
        //     // Si se deselecciona, se elimina de la lista de seleccionados            
        //     selectedMedicamentos.value = selectedMedicamentos.value.filter(item => item !== value);
        // }
    };

    function toggleSelectAll() {
        //console.log('Select All:', selectAll.value);
        if (selectAll.value) {
            const url = `${api_catalogos}/medicamentos/todos`;  
            axios.get(url).then((response)=>{
                selectedMedicamentos.value =  response.data;
            }).catch((error)=>{
          //      console.log(error);
                loader_info.value = false;
            })
          
        } else {
            // Deselecciona todos
            selectedMedicamentos.value = [...lista_medicamentos_seleccionados.value];
        }
    }

    const FiltrosBusqueda  = async ()=>{	
        PaginaInformacion.value = 1;	
		await ObterMedicamentos();
	}

    function debounce(func, wait) {
        let timeout
        return function(...args) {
            clearTimeout(timeout)
            timeout = setTimeout(() => func.apply(this, args), wait)
        }
    }

    const AlertaGuardar = () => {
        store.EstadoAlerta = 1;
        store.MostrarAlerta = true;          
        store.TituloAlerta = "¿Estás seguro?";
        store.IconoAlerta = "mdi-help-circle";
		store.MensajeAlerta = "Se guardarán los medicamentos seleccionados." ;
        store.CallbackAlerta="GuardarMedicamentos"; 
    }

    const GuardarMedicamentos = async () =>{
        var params ={            
            medicamentos :selectedMedicamentos.value
         };
        const url = `${api_catalogos}/medicamentos/guardar/consultorio`;  
        await axios.post(url, params).then((response)=>{
            store.EstadoAlerta = 2;
            store.MostrarAlerta = true; 
            store.TituloAlerta = "Éxito";
            store.IconoAlerta = "mdi-check-circle";
			store.MensajeAlerta = response.data.mensaje ;
            store.CallbackAlerta="";    
            ObterMedicamentos();         
        }).catch((error)=>{
            //console.log(error);
            loader_info.value = false;
            store.EstadoAlerta = 0;
            store.MostrarAlerta = true; 
            store.CallbackAlerta=null; 				
            store.TituloAlerta = "Error";
            store.IconoAlerta = "mdi-close-circle";
			store.MensajeAlerta = error.response.data.message ;
            
        })
    }

const ObtenerMedicamentosPeticion = debounce(FiltrosBusqueda, 340);
provide('FuncionesPadre',{GuardarMedicamentos});
</script>
<template>
    <App :breadcrumbs="breadcrumbs">
        <v-container fluid>
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">Lista de medicamentos</span>
                </v-col>
                <v-col cols="12">
                    <div class="poppins-light text-grey-darken-1">
                       Selecciona los medicamentos que necesitas para tu consultorio.
                    </div>
                </v-col>
                
            </v-row>
            <v-row class="d-flex justify-end mx-5">
                 <v-col cols="6" sm="4" md="4" lg="8" xl="8" class="d-flex justify-start">
                    <v-tooltip text="Guardar medicamentos para el consultorio">
                        <template #activator="{ props }">
                            <v-btn
                            v-if="TotalMedicamentosPagina > 0"
                            color="primary"
                            rounded="lg"
                            @click="AlertaGuardar"
                            class="ma-2"
                            density="comfortable"
                            v-bind="props"
                            >
                            Agregar
                            </v-btn>
                        </template>
                    </v-tooltip>
                </v-col>
                <v-col cols="6" sm="8" md="8" lg="4" xl="4" class="d-flex justify-end">
                      <v-text-field
                            density="compact"
                            label="Buscar"
                            prepend-inner-icon="mdi-magnify"
                            variant="outlined"
                            flat
                            v-model="BusquedaTexto"
                            @update:modelValue="ObtenerMedicamentosPeticion"
                            hide-details
                            rounded="lg"
                            single-line
                            color="primary"
                        ></v-text-field>
                </v-col>
            </v-row>
            <span class="font-weight-light ml-10" style="font-size: .9rem;">No olvides guardar los cambios que realices en los medicamentos.</span>
            <v-row class="mx-5 mt-n2">            
                <v-col v-if="TotalMedicamentosPagina>0" cols="12" class="mt-2">
                    <v-container fluid class="pa-0">
                        <v-progress-linear  
                            :active="loader_info"
                            :indeterminate="loader_info"
                            color="primary"
                            location="bottom"
                            >
                        </v-progress-linear>
                              <v-table density="compact" class="rounded-lg elevation-0 border-thin">
                                    <thead>
                                    <tr>
                                        <th class="text-primary" >
                                        <v-checkbox-btn                                            
                                            class="pe-2"
                                            v-model="selectAll"
                                            @change="toggleSelectAll"
                                            :indeterminate="selectedMedicamentos.length > 0 && selectedMedicamentos.length < TotalMedicamentosPagina"
                                        ></v-checkbox-btn>
                                        </th>
                                        <th class="text-primary">
                                            <span class="mr-2">#</span>                                           
                                        </th>
                                        <th class="text-primary">
                                            <span class="mr-2">Fármaco(s) </span>
                                            <v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('farmacos')">
	    								        <v-icon color="primary">{{OrdenarPor == 'farmacos' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'farmacos' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							        </v-btn>
                                        </th>
                                        <th class="text-primary">
                                            <span class="mr-2">Forma farmacéutica </span>
                                            <v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('forma_farmaceutica')">
	    								        <v-icon color="primary">{{OrdenarPor == 'forma_farmaceutica' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'forma_farmaceutica' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							        </v-btn>
                                        </th>
                                        <th class="text-primary">
                                            <span class="mr-2">Concentración</span>
                                             <v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('concentracion')">
	    								        <v-icon color="primary">{{OrdenarPor == 'concentracion' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'concentracion' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							        </v-btn>
                                        </th>
                                        <th class="text-primary">
                                            <span class="mr-2">Denominación distintiva</span>
                                             <v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('fecha_nacimiento')">
	    								        <v-icon color="primary">{{OrdenarPor == 'fecha_nacimiento' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'fecha_nacimiento' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							        </v-btn>
                                        </th>
                                        <!-- <th class="text-primary">
                                            <span class="mr-2">Titular</span>
                                             <v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('fecha_nacimiento')">
	    								        <v-icon color="primary">{{OrdenarPor == 'fecha_nacimiento' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'fecha_nacimiento' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							        </v-btn>
                                        </th> -->
                                        <th class="text-primary">
                                            <span class="mr-2">Estado</span>
                                            <v-btn icon variant="flat" size="x-small" @click="OrdenarInformacion('estado')">
	    								        <v-icon color="primary">{{OrdenarPor == 'estado' && OrdenInformacion==1 ? 'mdi-menu-up': OrdenarPor == 'estado' && OrdenInformacion==-1 ? 'mdi-menu-down': 'mdi-menu-swap'}}</v-icon>
	    							        </v-btn>
                                        </th>
                                        <th class="text-primary">
                                          
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                     <tr
                                        v-for="item in medicamentos.data"
                                        :key="item.id" 
                                    >
                                       <!--  -->
                                        <td class="text-primary">
                                            <v-checkbox 
                                                :value="item.numero" 
                                                v-model="selectedMedicamentos" 
                                                @update:model-value="agregaraSeleccionados()"
                                                hide-details 
                                                multiple 
                                                class="pe-2"
                                            ></v-checkbox>
                                        </td>
                                        <td>
                                            {{ item.numero }}
                                        </td>
                                        <td>
                                            {{ item.farmacos }}
                                        </td>
                                        <td>
                                            {{ item.forma_farmaceutica }}
                                        </td>
                                        <td>
                                            {{ item.concentracion }}
                                        </td>
                                        <td>
                                            {{ item.denominacion_distintiva }}
                                        </td>
                                        <!-- <td>
                                            {{ item.titular }}
                                        </td> -->
                                        <td>
                                            <v-icon class="mr-2" icon="mdi-circle" size="x-small" :color="selectedMedicamentos.includes(item.numero) ? 'success' : '#abb2b9'"></v-icon>
                                            
                                            <span>{{ item.visible }}</span>
                                        </td>
                                        <td>
                                            <!-- <v-dialog v-model="dialog_medicamento" max-width="500">
                                                <template v-slot:activator="{ props: activatorProps }">
                                                    <v-tooltip text="Más información" location="left">
                                                        <template #activator="{ props }">
                                                            <v-btn
                                                                v-bind="{ ...activatorProps, ...props }"
                                                                variant="text"
                                                                icon
                                                                color="primary"  
                                                                @click="() => { medicamentoSeleccionado = item; dialog_medicamento = true; }"                                                             
                                                            >
                                                                <v-icon>mdi-message-text</v-icon>
                                                            </v-btn>
                                                        </template>
                                                    </v-tooltip>
                                                </template>
                                                <template v-slot:default>
                                                   <DialogMedicamentos :item="medicamentoSeleccionado" @close="dialog_medicamento = false" ></DialogMedicamentos>
                                                </template>
                                            </v-dialog> -->
                                            <v-tooltip text="Más información" location="left">
                                                <template #activator="{ props }">
                                                <v-btn
                                                    v-bind="props"
                                                    variant="text"
                                                    icon
                                                    color="primary"
                                                    @click="() => { medicamentoSeleccionado = item; dialog_medicamento = true; }"
                                                >
                                                    <v-icon>mdi-message-text</v-icon>
                                                </v-btn>
                                                </template>
                                            </v-tooltip>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="elevation-1 bg-white">
                                            <td  colspan="8" >
                                                <v-container fluid class="pa-0">
                                                    <v-row no-gutters>
                                                        <v-col class="d-flex align-center text-primary">
                                                             Mostrando {{TotalMedicamentosPagina}} de {{TotalMedicamentos}} registros
                                                        </v-col>
                                                        <v-col class="d-flex justify-end">
                                                            <v-pagination v-model="PaginaInformacion" @update:modelValue="ObtenerDatosPorPagina" color="primary"   :length="medicamentos.last_page" :total-visible="5"></v-pagination>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>

                                            </td>
                                        </tr>
                                    </tfoot>
                                </v-table>
                    </v-container>
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
    <v-dialog v-model="dialog_medicamento" max-width="500">
        <DialogMedicamentos
            v-if="medicamentoSeleccionado"
            :item="medicamentoSeleccionado"
            @close="dialog_medicamento = false"
        />
    </v-dialog>
     <Alerta ref="MensajeAppAlerta" :callback="AlertaCallback"></Alerta>
</template>