<template>
    <v-dialog v-model="dialog" max-width="650px" scrollable scrim="black" opacity="0.80" origin="top center" location="top">
        
        <v-card rounded="xl">
            <v-card-title class="d-flex align-center pa-0 ">
                <v-container fluid class="pa-0">
                    <v-row no-gutters class="d-flex align-center">
                        <v-col cols="11">
                            <v-container fluid>
                                <v-row no-gutters class="pa-4 mx-2">
                                    <v-col cols="12">
                                        <span class="poppins-bold">Registros {{ importados ? 'importados' : 'no importados' }}</span>
                                    </v-col>
                                    <v-col cols="12" class="text-wrap mt-n2">
                                        <span class="text-medium-emphasis poppins-regular" style="font-size:0.875rem">Lista de usuarios que fueron procesados durante la importación.</span>
                                    </v-col>
                                </v-row>
                            </v-container>
                            
                        </v-col>
                        <v-col cols="1" class="ml-n5">
                            <v-btn
                                icon="mdi-close"
                                variant="text"
                                @click="closeDialog"
                            ></v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-title>
            
            <v-card-text class="mx-4 pa-0" style="height: 80%;">
                <div class="d-flex align-center px-4 py-2  rounded-t-lg">
                    <v-icon 
                        size="small" 
                        color="primary" 
                        class="mr-2"
                    >
                        {{ importados ? 'mdi-database-check' : 'mdi-database-alert' }}
                    </v-icon>
                    <span class="poppins-medium">
                        <span class="font-weight-bold">{{ listaUsuarios.length }}</span>
                        <span class="mx-1">registros {{ importados ? 'importados' : 'no importados' }}</span>
                    </span>
                </div>
                <v-list class="rounded-lg px-4">
                    <v-virtual-scroll
                        :items="listaUsuarios"
                        :max-height="400"
                        item-height="72"
                    >
                        <template v-slot="{ item,index }">
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
                                        {{ item.medico }}
                                    </v-list-item-title>
                                    
                                    <div class="d-flex align-center gap-2">
                                        <div class="d-flex align-center">
                                            <v-chip
                                                size="x-small"
                                                :color="importados ? 'primary' : 'grey'"
                                                :variant="importados ? 'tonal' : 'tonal'"
                                                class="text-none elevation-0"
                                            >
                                                Consultorio: {{ item.consultorio ?? 'No disponible o no registrado'}}
                                            </v-chip>
                                        </div>

                                        
                                    </div>
                                    <div class="d-flex align-center gap-2">
                                        <div class="d-flex align-center">
                                            <v-icon size="x-small" color="primary" class="mr-1">mdi-card-account-details</v-icon>
                                            <span class="poppins-medium text-medium-emphasis text-caption">
                                                {{ item.cuenta ?? 'No.Cuenta no disponible' }}
                                            </span>
                                        </div>

                                        <div class="d-flex align-center mx-2">
                                            <v-icon size="x-small" color="primary" class="mr-1">mdi-email</v-icon>
                                            <span class="poppins-medium text-medium-emphasis text-caption">
                                                {{ item.correo_institucional ?? 'Correo no disponible' }}
                                            </span>
                                        </div>

                                        
                                    </div>

                                    <template v-if="!importados && item.error">
                                        <div class="d-flex align-center mt-1">
                                            <v-icon size="x-small" color="error" class="mr-1">mdi-alert-circle</v-icon>
                                            <span class="poppins-regular text-grey-darken-2 text-caption">
                                                {{ item.error }}
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </v-list-item>
                            <v-divider color="grey"></v-divider>
                        </template>
                        
                    </v-virtual-scroll>
                </v-list>

                <div v-if="!listaUsuarios.length" class="d-flex justify-center align-center py-8">
                    <v-empty-state
                        icon="mdi-account-group"
                        text="No hay usuarios para mostrar"
                        title="Sin datos"
                    ></v-empty-state>
                </div>
            </v-card-text>

            <v-card-actions class="pa-4 mb-4 mr-4">
                <v-spacer></v-spacer>
                <v-btn
                    color="grey-darken-2"
                    variant="outlined"
                    @click="closeDialog"
                >
                    Cerrar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { ref,computed } from 'vue';
/*
const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true
    },
    listaUsuarios: {
        type: Array,
        required: true,
        default: () => []
    },
    importados: {
        type: Boolean,
        default: false
    }
});*/
export default{
    setup(){
        const dialog = ref(false);
        const listaUsuarios = ref([]);
        const importados = ref(0);
        const closeDialog = () => {
            dialog.value = false;
        };

        const headers = computed(()=>{
            return importados.value ? [
            { title: 'Médico', key: 'medico', sortable: true },
                { title: 'Dependencia', key: 'consultorio', sortable: true },
            ] : [
                { title: 'Médico', key: 'medico', sortable: true },
                { title: 'Dependencia', key: 'consultorio', sortable: true },
                { title: 'Motivo', key: 'error', sortable: true } 
            ]
        });
        return{
            dialog,
            headers,
            listaUsuarios,
            importados,
            closeDialog
        }
    }
}


// Use sample users if no users are provided
/*const displayUsers = computed(() => {
    return props.users.length > 0 ? props.users : sampleUsers;
});

const headers = [
    { title: 'Nombre', key: 'nombre', sortable: true },
    { title: 'Dependencia', key: 'dependencia', sortable: true }
];*/
</script>
