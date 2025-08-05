<template>
    <v-container fluid>
        <v-row class="mt-4 mx-4" no-gutters>
            <v-col cols="12">
                <div class="d-flex align-center">
                    <v-icon
                        icon="mdi-database"
                        color="primary"
                        size="28"
                        class="me-3"
                    ></v-icon>
                    <div>
                        <div class="text-h5 poppins-bold text-high-emphasis mb-1">Historial de importaciones</div>
                        <div class="poppins-regular text-medium-emphasis" style="font-size:0.9375rem">
                            Registro de todas las importaciones realizadas en el sistema
                        </div>
                    </div>
                </div>
                <!--<v-divider class="mt-3 mb-4"></v-divider>-->
            </v-col>
        </v-row>
        <v-row class="mt-10 mx-5 " no-gutters="">
            <v-col cols="12" v-if="HistorialdeImportaciones.length>0">
                <v-progress-linear
                    indeterminate
                    v-if="LoaderInformacion"
                    color="primary"
                    height="4"
                    class="rounded-pill"
                >
                </v-progress-linear>
                <v-table class="border-thin rounded-lg" density="compact">
                    <thead>
                        <tr>
                            <th class="text-grey-darken-2">
                                #
                            </th>
                            <th class="text-grey-darken-2">
                                Fecha creación
                            </th>
                            <th class="text-grey-darken-2">
                                Creada por
                            </th>
                            <th class="text-grey-darken-2 text-center">
                                Registros importados
                            </th>
                            <th class="text-grey-darken-2 text-center">
                                Registros no importados
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="historico in HistorialdeImportaciones">
                            <td>
                                <v-container fluid>
                                    <v-row no-gutters class="d-flex align-center">
                                        <v-col cols="12">
                                            <span class="poppins-semibold">{{ historico.index }}</span>
                                        </v-col>
                                        
                                    </v-row>
                                </v-container>
                                
                               
                            </td>
                            <td class="pa-0 text-no-wrap">
                                <v-container fluid>
                                    <v-row no-gutters class="d-flex align-center">
                                        <v-col cols="12">
                                            <v-icon class="mr-2" size="15" color="primary">mdi-calendar</v-icon>
                                            <span class="text-high-emphasis poppins-medium">
                                                {{ historico.fecha_creacion }}
                                            </span>
                                        </v-col>
                                        <v-col cols="12">
                                            <span class="poppins-regular text-medium-emphasis" style="font-size:0.775rem;">No. Lote {{ historico.hID }}</span>
                                        </v-col>
                                        
                                    </v-row>
                                </v-container>
                                
                                
                            </td>
                            <td class="pa-0 text-no-wrap">
                                <v-container fluid>
                                    <v-row no-gutters class="d-flex align-center">
                                        <v-col>
                                            <v-avatar color="blue" size="22" rounded>
                                                <v-icon size="18">mdi-account</v-icon>
                                            </v-avatar>
                                            <span  class="poppins-medium text-medium-emphasis mx-2">
                                                {{ historico.usuario }}
                                            </span>
                                        </v-col>
                                    </v-row>
                                </v-container>
                               
                            </td>
                            <td class="pa-0 text-no-wrap">
                                
                                <v-container fluid>
                                    <v-row no-gutters class="d-flex align-center text-center">
                                        <v-col>
                                            <v-chip @click="historico.registros_importados.length >0 ? showListaRegistros(historico.registros_importados,true):undefined" :class="historico.registros_importados.length >0 ? 'text-decoration-underline cursor-pointer':undefined" :color="historico.registros_importados.length >0 ? 'blue':undefined" variant="tonal">
                                                {{ historico.registros_importados.length }}
                                            </v-chip>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </td>
                            <td class="pa-0 text-no-wrap">
                                <v-container fluid>
                                    <v-row no-gutters class="d-flex align-center text-center">
                                        <v-col>
                                            <v-chip @click="historico.registros_no_importados.length >0 ? showListaRegistros(historico.registros_no_importados,false):undefined" v-tooltip="'Ver'" :class="historico.registros_no_importados.length >0 ? 'text-decoration-underline cursor-pointer':undefined" :color="historico.registros_no_importados.length >0 ? 'red':undefined" variant="tonal">
                                                {{ historico.registros_no_importados.length }}
                                            </v-chip>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <v-pagination :disabled="LoaderInformacion" density="default" @update:modelValue="PeticionObtenerHistorialPorPagina" :length="LastPage" v-model="PaginaHistorialImportacion" :total-visible="5" color="primary"></v-pagination>
                        </td>
                    </tr>              
                    
                </tfoot>
               
            </v-col>
            <v-col cols="12" v-else>
                <v-empty-state
                    icon="mdi-database-search"
                    text="No hay importaciones disponibles para visualizar en este momento. Realiza una nueva importación para comenzar a registrar médicos pasantes."
                    title="Sin importaciones disponibles"
                >
                    <template v-slot:actions>
                        <v-btn color="primary" @click="pestaniaImportacion=1">Nueva importación</v-btn>
                    </template>
                </v-empty-state>
            </v-col>
            
        </v-row>

        <ImportacionListaUsuarios ref="ImportacionListaUsuariosRef"></ImportacionListaUsuarios>
    </v-container>
</template>

<script>
import { ref, onMounted,inject } from 'vue';

import ImportacionListaUsuarios from '@/components/importaciones/ImportacionListaUsuarios.vue';
import { AppStore } from '@/stores/AppStore';
export default {
    components:{
        ImportacionListaUsuarios
    },
    setup() {
        const pestaniaImportacion = inject('tab');
        const search = ref('');
        const loading = ref(false);
        const importaciones = ref([]);
        const detallesModal = ref(false);
        const StoreApp = AppStore();
        const importacionSeleccionada = ref(null);
        const LoaderInformacion = ref(false);
        const PaginaHistorialImportacion = ref(1);
        const HistorialdeImportaciones = ref([]);
        const MostrarListaUsuarios = ref(false);
        const ImportacionListaUsuariosRef = ref(null);
        const ListaUsuariosenImportacion = ref([]);
        const RegistroImportado = ref(false);
        const LastPage = ref(0);
        const api = "/importaciones";

        const headers = [
            { title: 'Fecha', key: 'fecha', sortable: true },
            { title: 'Estado', key: 'estado', sortable: true },
            { title: 'Total registros', key: 'total_registros', sortable: true },
            { title: 'Registros importados', key: 'registros_importados', sortable: true },
            { title: 'Acciones', key: 'acciones', sortable: false }
        ];


        

        const HistorialImportaciones = async()=>{
            PaginaHistorialImportacion.value=1;
            await ObtenerHistorial();
        }
        
        const ObtenerHistorial = async()=>{
            const url =`${api}/historial`;
            var data_request = {
                params:{
                    pagina:PaginaHistorialImportacion.value
                }
            };
            await axios.get(url,data_request).then((response)=>{
                console.log("ok")
                HistorialdeImportaciones.value = response.data.data ?? [];
                LastPage.value = response.data.last_page ?? 0;
               
            }).catch((error)=>{
                HistorialdeImportaciones.value =  [];
                LastPage.value =  0;
            }).finally(()=>{
                LoaderInformacion.value = false;
            });
        }

        const showListaRegistros = (registros,registrados)=>{
            ImportacionListaUsuariosRef.value.dialog=true;
            ImportacionListaUsuariosRef.value.listaUsuarios=registros;
            ImportacionListaUsuariosRef.value.importados=registrados;
            console.log("aqui modal")

        }

        const PeticionObtenerHistorialPorPagina = () => {
            LoaderInformacion.value = true;
            StoreApp.debounce(ObtenerHistorial, 350)();
        }


        return {
            search,
            loading,
            importaciones,
            headers,
            detallesModal,
            importacionSeleccionada,
            HistorialdeImportaciones,
            HistorialImportaciones,
            showListaRegistros,
            MostrarListaUsuarios,
            ImportacionListaUsuariosRef,
            ListaUsuariosenImportacion,
            RegistroImportado,
            LastPage,
            pestaniaImportacion,
            ObtenerHistorial,
            LoaderInformacion,
            PeticionObtenerHistorialPorPagina,
            PaginaHistorialImportacion
        };
    }
};
</script>

<style scoped>
.max-width-300 {
    max-width: 300px;
}
</style>