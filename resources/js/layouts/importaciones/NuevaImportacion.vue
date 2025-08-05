<template>
  
    <v-row class="my-4 mx-5" no-gutters>
        <v-col cols="12">
            <v-empty-state
                icon="mdi-upload"
                text="Para comenzar a importar médicos pasantes de servicio social, haz clic en el botón 'Importar' que se encuentra debajo."
                title="Importar médicos pasantes de servicio social"
            >
                <template v-slot:actions>
                    <input
                        type="file"
                        ref="archivoImportacion"
                        class="d-none"
                        accept=".xlsx,.xls,.csv"
                        @change="handleFileSelect"
                    />
                    <v-btn color="primary"  :disabled="isLoading" rounded="lg" @click="openFileDialog">Importar</v-btn>
                </template>
            </v-empty-state>
        </v-col>
    </v-row>
    <v-bottom-sheet inset  v-model="PreImportacionModal" scrim="black" opacity="0.80" persistent max-height="750">

        <template v-slot:default="{ isActive }">
            <v-card  rounded="t-lg">
                <v-card-title class="d-flex align-center">
                    <v-row no-gutters class="pa-4">
                        <v-col cols="11" class="pr-2">
                            <div class="text-h6 poppins-bold text-high-emphasis">Importar médicos pasantes de servicio social</div>
                            <div class="poppins-light text-medium-emphasis" style="font-size:0.875rem">
                                Revisa y confirma los registros que se importarán al sistema
                            </div>
                        </v-col>
                        <v-col cols="1" class="d-flex justify-end">
                            <v-btn
                                v-if="!CargaImportacion"
                                icon="mdi-close"
                                variant="text"
                                @click="isActive.value = false"
                                density="comfortable"
                            ></v-btn>
                        </v-col>
                    </v-row>
                </v-card-title>
                <v-card-text>
                    <v-row  v-show="!CargaImportacion">
                        <v-col>
                            <v-expansion-panels elevation="0">
                                <v-expansion-panel v-if=" DisponiblesdeImportacion.length>0">
                                    <v-expansion-panel-title class="border-b-thin">
                                        <div class="d-flex align-center px-4 py-2  rounded-t-lg">
                                            <v-icon 
                                                size="small" 
                                                color="primary" 
                                                class="mr-2"
                                            >
                                                mdi-database-check
                                            </v-icon>
                                            <span class="poppins-semibold">
                                                <span class="font-weight-bold" style="font-size:1.3rem">{{ DisponiblesdeImportacion.length }}</span>
                                                <span class="mx-1">registros a importar</span>
                                            </span>
                                        </div>
                                    </v-expansion-panel-title>
                                    <v-expansion-panel-text eager>
                                        <v-list class="rounded-lg px-4">
                                            <v-virtual-scroll
                                                :items="DisponiblesdeImportacion"
                                                :max-height="400"
                                                item-height="72"
                                            >
                                                <template v-slot="{ item, index }">
                                                    <v-list-item
                                                        class="border-bottom py-2 ma-2 bg-grey-lighten-4 rounded-lg"
                                                        :class="{'hover:bg-grey-lighten-4': true}"
                                                    >
                                                        <template v-slot:prepend>
                                                            <v-avatar color="primary" size="32" class="elevation-1">
                                                                <v-icon color="white" size="x-small">mdi-account</v-icon>
                                                            </v-avatar>
                                                        </template>

                                                        <div class="d-flex flex-column my-2">
                                                            <v-list-item-title class="poppins-semibold text-body-2 mb-1">
                                                                <span class="text-medium-emphasis" style="font-size: 0.775rem;;"># {{index + 1}}</span>
                                                                {{ item.nombre }} {{ item.primer_apellido }} {{ item.segundo_apellido }}
                                                            </v-list-item-title>
                                                            
                                                            <div class="d-flex align-center gap-2">
                                                                <div class="d-flex align-center">
                                                                    <v-chip
                                                                        size="x-small"
                                                                        color="primary"
                                                                        variant="tonal"
                                                                        class="text-none elevation-0"
                                                                    >
                                                                        Consultorio: {{ item.nombre_dependencia }}
                                                                    </v-chip>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-center gap-2">
                                                                <div class="d-flex align-center">
                                                                    <v-icon size="x-small" color="primary" class="mr-1">mdi-card-account-details</v-icon>
                                                                    <span class="poppins-medium text-medium-emphasis text-caption">
                                                                        {{ item.no_cuenta ?? 'No.Cuenta no disponible' }}
                                                                    </span>
                                                                </div>

                                                                <div class="d-flex align-center mx-2">
                                                                    <v-icon size="x-small" color="primary" class="mr-1">mdi-email</v-icon>
                                                                    <span class="poppins-medium text-medium-emphasis text-caption">
                                                                        {{ item.correo_institucional ?? 'Correo no disponible' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </v-list-item>
                                                    <v-divider color="grey"></v-divider>
                                                </template>
                                            </v-virtual-scroll>
                                        </v-list>

                                        <div v-if="!DisponiblesdeImportacion.length" class="d-flex justify-center align-center py-8">
                                            <v-empty-state
                                                icon="mdi-account-group"
                                                text="No hay registros para importar"
                                                title="Sin datos"
                                            ></v-empty-state>
                                        </div>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-col>
                    </v-row>
                    
                    <v-row v-show="!CargaImportacion">
                        <v-col >
                            <v-expansion-panels elevation="0">
                                <v-expansion-panel v-if=" NoDisponiblesdeImportacion.length>0">
                                    <v-expansion-panel-title class="border-b-thin bg-red-lighten-5">
                                        <div class="d-flex align-center px-4 py-2 ">
                                            <v-icon 
                                                size="small" 
                                                color="red-lighten-1" 
                                                class="mr-2"
                                            >
                                                mdi-database-alert
                                            </v-icon>
                                            <span class="poppins-semibold">
                                                <span class="font-weight-bold text-red" style="font-size:1.3rem">{{ NoDisponiblesdeImportacion.length }}</span>
                                                <span class="mx-1 text-red">registros no importables</span>
                                            </span>
                                        </div>
                                    </v-expansion-panel-title>
                                    <v-expansion-panel-text eager>
                                        
                                        <v-list class="rounded-lg px-4">
                                            <v-virtual-scroll
                                                :items="NoDisponiblesdeImportacion"
                                                :max-height="400"
                                                item-height="72"
                                            >
                                                <template v-slot="{ item, index }">
                                                    <v-list-item
                                                        class="border-bottom py-2 ma-2 bg-grey-lighten-4 rounded-lg"
                                                        :class="{'hover:bg-grey-lighten-4': true}"
                                                    >
                                                        <template v-slot:prepend>
                                                            <v-avatar color="grey-lighten-1" size="32" class="elevation-1">
                                                                <v-icon color="white" size="x-small">mdi-account</v-icon>
                                                            </v-avatar>
                                                        </template>

                                                        <div class="d-flex flex-column my-2">
                                                            <v-list-item-title class="poppins-semibold text-body-2 mb-1">
                                                                <span class="text-medium-emphasis" style="font-size: 0.775rem;;"># {{index + 1}}</span>
                                                                {{ item.nombre }} {{ item.primer_apellido }} {{ item.segundo_apellido }}
                                                            </v-list-item-title>
                                                            
                                                            <div class="d-flex align-center gap-2">
                                                                <div class="d-flex align-center">
                                                                    <v-chip
                                                                        size="x-small"
                                                                        color="grey"
                                                                        variant="tonal"
                                                                        class="text-none elevation-0"
                                                                    >
                                                                        Consultorio: {{ item.nombre_dependencia }}
                                                                    </v-chip>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-center gap-2">
                                                                <div class="d-flex align-center">
                                                                    <v-icon size="x-small" color="grey" class="mr-1">mdi-card-account-details</v-icon>
                                                                    <span class="poppins-medium text-medium-emphasis text-caption">
                                                                        {{ item.no_cuenta ?? 'No.Cuenta no disponible' }}
                                                                    </span>
                                                                </div>

                                                                <div class="d-flex align-center mx-2">
                                                                    <v-icon size="x-small" color="grey" class="mr-1">mdi-email</v-icon>
                                                                    <span class="poppins-medium text-medium-emphasis text-caption">
                                                                        {{ item.correo_institucional ?? 'Correo no disponible' }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex align-center mt-1">
                                                                <v-icon size="x-small" color="error" class="mr-1">mdi-alert-circle</v-icon>
                                                                <span class="poppins-semibold text-grey-darken-2" style="font-size: 0.775rem;">
                                                                    {{ item.error || 'No disponible' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </v-list-item>
                                                    <v-divider color="grey"></v-divider>
                                                </template>
                                            </v-virtual-scroll>
                                        </v-list>

                                        <div v-if="!NoDisponiblesdeImportacion.length" class="d-flex justify-center align-center py-8">
                                            <v-empty-state
                                                icon="mdi-account-group"
                                                text="No hay registros no importables"
                                                title="Sin datos"
                                            ></v-empty-state>
                                        </div>
                                    </v-expansion-panel-text>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-col>
                    </v-row>
                    <v-empty-state
                        v-show="!CargaImportacion && (NoDisponiblesdeImportacion.length<1 && DisponiblesdeImportacion.length < 1)"
                        icon="mdi-file-document-outline"
                        text="El archivo seleccionado está vacío. Por favor, seleccione un archivo que contenga registros para importar."
                        title="Archivo sin registros"
                    ></v-empty-state>
                    <v-empty-state
                        v-show="CargaImportacion"
                        icon="mdi-database-sync"
                        text="Por favor, no cierre esta ventana mientras se procesan los datos."
                        title="Procesando datos"
                    >
                    <v-progress-circular indeterminate></v-progress-circular>    
                </v-empty-state>
                </v-card-text>

                <v-card-actions v-show="!CargaImportacion">
                    <v-row class="mx-4 mb-6">
                        <v-spacer></v-spacer>
                    <v-btn color="primary" variant="outlined" class="text-none mr-2" @click="PreImportacionModal=false" v-if="DisponiblesdeImportacion.length<1 || NoDisponiblesdeImportacion.length>0">
                        <v-icon icon="mdi-close"></v-icon>
                        <span class="mx-2">Cerrar</span>
                    </v-btn>
                    <v-btn color="primary" variant="elevated" class="text-none" @click="ImportacionMedicos" v-if="DisponiblesdeImportacion.length>0">
                        <v-icon icon="mdi-database-import-outline"></v-icon>
                        <span class="mx-2">Importar registros</span>
                    </v-btn>
                    </v-row>
                    
                    
                </v-card-actions>
                </v-card>
            </template>
            </v-bottom-sheet>
            <AppAlerta/>
</template>
<script>
import AppAlerta from '@/components/AppAlerta.vue';
import { AppStore } from '@/stores/AppStore';
import {ref, onMounted, provide,inject} from 'vue';

export default{
    components:{
        AppAlerta
    },
    setup(){
        const MostrarHistorialImportaciones = inject('GetHistorialImportaciones');
        const StoreApp = AppStore();
        const callback = ref(null);
        const PreImportacionModal = ref(false);
        const CargaImportacion = ref(false);
        const DisponiblesdeImportacion = ref(0);
        const NoDisponiblesdeImportacion = ref([]);
        const archivoImportacion = ref(null);
        const ListaMedicosaImportar = ref(null);
        const isLoading = ref(false);

        const openFileDialog = () => {
            archivoImportacion.value.click();
        };

        const handleFileSelect = async (event) => {
            const file = event.target.files[0];
            if (file) {
                isLoading.value = true;
                try {
                    const url = "/importaciones/validar-importacion";
                    const response = await axios.post(url, 
                        {archivo:file},
                        {headers: {'Content-Type': 'multipart/form-data'}}
                    );
                    
                    DisponiblesdeImportacion.value = response.data.registros_a_importar;
                    NoDisponiblesdeImportacion.value = response.data.registros_no_importar;
                    ListaMedicosaImportar.value = response.data.mdUID;
                    PreImportacionModal.value = true;
                } catch (error) {
                    console.error('Error:', error);
                    StoreApp.MostrarAlerta = true;
                    StoreApp.CallbackAlerta=null;  
                    console.log(error)           
                    StoreApp.MensajeAlerta = error.response.data.message ?? "Ha ocurrido un error";
                    StoreApp.TituloAlerta = "Error";
                    StoreApp.IconoAlerta = "mdi-close-circle";
                    CargaImportacion.value=false;
                    PreImportacionModal.value=false;
                } finally {
                    isLoading.value = false;
                    event.target.value = '';
                }
            }
        };

        const ImportacionMedicos = async () => {
            
            CargaImportacion.value=true;
            await axios.post("/importaciones/nueva", 
                    {mdUID: ListaMedicosaImportar.value},
                    {timeout: 30000}
            ).then((response)=>{
                StoreApp.CallbackAlerta="GuardarSeccion"; 
                console.log("Que traes en  StoreApp.CallbackAlerta", StoreApp.CallbackAlerta)
                StoreApp.MensajeAlerta = "El proceso de importacion se ha completado con exito";
                StoreApp.EstadoAlerta = 2;
                StoreApp.TituloAlerta = "Exito";
                StoreApp.IconoAlerta = "mdi-check-circle";
                CargaImportacion.value=false;
                PreImportacionModal.value=false;
            }).catch((error)=>{
                StoreApp.CallbackAlerta=null;  
                console.log(error)           
                StoreApp.EstadoAlerta = 2;
                StoreApp.MensajeAlerta = error.response.data.message ?? "Ha ocurrido un error";
                StoreApp.TituloAlerta = "Error";
                StoreApp.IconoAlerta = "mdi-close-circle";
                CargaImportacion.value=false;
                PreImportacionModal.value=false;
            }).finally(()=>{
                StoreApp.MostrarAlerta = true;
            });
        };

        const GuardarSeccion = async () => {
            console.log("Ok se importaron y se cierra");
            await StoreApp.OcultarAlerta();
            await MostrarHistorialImportaciones();
        } 

        provide('FuncionesPadre',{GuardarSeccion});
        return {   
            CargaImportacion,
            MostrarHistorialImportaciones,
            archivoImportacion,
            PreImportacionModal,
            DisponiblesdeImportacion,
            NoDisponiblesdeImportacion,
            ImportacionMedicos,
            openFileDialog,
            handleFileSelect,
            callback,
            isLoading
        }
    }
}
</script>
