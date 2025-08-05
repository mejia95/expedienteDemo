<script setup>
    import { onMounted, ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import axios from 'axios';

    const props = defineProps({
        paciente: String,
        folio: String,
        consulta: String
    });

    const datos_consulta = ref([]);
    
    onMounted(() => {
        RevisonDatos();
    });

    const RevisonDatos = () => {
        var url = '/qr/consulta/confirmacion';    
        var datos = {params:{
             paciente : props.paciente,
            folio : props.folio,
            consulta : props.consulta
        }}
        axios.get(url,datos).then((response)=>{ 
            datos_consulta.value = response.data;                       
        }).catch((error)=>{
            console.log(error)
        });
    };
</script>
<template>
    <v-container fill-height>
        <v-row class="d-flex justify-center align-center" style="height: 80vh;">
            <v-col cols="8">
                <v-card color="primary" elevation="2" class="card-with-background" v-if="datos_consulta.length > 0">
                    <v-card-title class="text-h5 text-center">
                         Información de la Consulta
                    </v-card-title>
                    <v-card-subtitle class="text-center">
                        Información de la Consulta
                    </v-card-subtitle>
                    <v-card-text class="bg-white pt-4 text-center">
                        <p><strong>Fecha y hora de la consulta:</strong> {{ datos_consulta['created_at_formateado'] }}</p>
                        <p><strong>Paciente:</strong> {{ datos_consulta['nombreCompleto'] }}</p>
                        <p><strong>Curp:</strong> {{ datos_consulta['paciente_CURP'] }}</p>                                            
                        <p><strong>Médico: </strong>{{ datos_consulta['medico_nombre'] }}</p>
                        <p><strong>Consultorio:</strong>{{ datos_consulta['consultorio_nombre'] }}</p>                        
                        <p><strong>Folio:</strong> {{ datos_consulta['contador_folio'] }}</p>    
                        <!-- <v-icon class="d-flex justify-end" color="green" size="60px" icon="mdi-check"></v-icon> -->
                    </v-card-text>
                </v-card>  
                <v-card>
                    <v-card-title class="text-h5 text-center">
                        Información no encontrada
                    </v-card-title>
                </v-card>              
            </v-col>
        </v-row>
    </v-container>
</template>