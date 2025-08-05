<script setup>
import moment from "moment";
const props = defineProps({
  
  consulta_datos: {
    type: Array,
    default: () => []
  },
  consulta_datos_comunidad: {
    type: String,
    default: ""
  }
})
</script>
<template>
    <v-container>
        <v-row>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Consultorio o
                    comunidad</span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos_comunidad
                    }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Nombre completo:
                </span>
                <span class="text-medium-emphasis mx-2">{{ consulta_datos.primer_apellido }}
                    {{ consulta_datos.segundo_apellido }}
                    {{ consulta_datos.nombre }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Fecha de
                    nacimiento:</span>
                <span class="text-medium-emphasis mx-2">{{
                    moment(
                        consulta_datos.fecha_nacimiento
                    ).format("DD-MM-YYYY")
                }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Edad:</span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.edad
                    }}</span>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Género:</span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.etiqueta_genero
                    }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Ocupación:</span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.ocupacion
                    }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Estado civil:</span>
                <span class="text-medium-emphasis mx-2">{{ consulta_datos.estado_civil }}
                </span>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Nacionalidad:</span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.nacionalidad
                    }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">CURP:</span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.curp
                    }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Tipo de sangre:
                </span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.tipo_sangre
                    }}</span>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Teléfono fijo:
                </span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.telefono
                    }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Teléfono celular:
                </span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.telefono_celular
                    }}</span>
            </v-col>
            <v-col cols="12" sm="12" md="12" lg="4" xl="4">
                <span class="poppins-semibold" style="font-size: 0.955rem">Correo
                    electrónico:</span>
                <span class="text-medium-emphasis mx-2">{{
                    consulta_datos.correo
                    }}</span>
            </v-col>
        </v-row>
    </v-container>
  </template>