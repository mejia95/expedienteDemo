
<script setup>
import { inject } from 'vue';

const mostrarFiltros = inject('MostrarFiltros')
const aplicarFiltros = inject('aplicarFiltros')
const limpiarFiltros = inject('limpiarFiltros')
const FechaInicioFiltro = inject('FechaInicioFiltro')
const FechaFinFiltro = inject('FechaFinFiltro')
</script>
<template>
    <v-navigation-drawer v-model="mostrarFiltros" location="right" width="400" scrim="black"  temporary persistent disable-resize-watcher floating>
        <v-card flat class="pa-4" color="grey-lighten-4" style=" min-height: 100vh;">
        <v-row align="center" class="mb-2 mt-n2" no-gutters>
            <v-col>
            <span style="font-size: 1rem; font-weight: 600;">Filtros de búsqueda</span>
            </v-col>
            <v-col cols="auto">
            <v-btn icon="mdi-close" variant="text" @click="mostrarFiltros = false"></v-btn>
            </v-col>
        </v-row>
        <v-divider class="mb-4"></v-divider>
       
        <v-row dense align="end" class="mt-n2">
            <v-col cols="12" md="12">
            <span class="text-caption text-grey-darken-1 ">Rango de fechas</span>
            <v-date-input
                v-model="FechaInicioFiltro"
                label="Desde"
                variant="outlined"
                bg-color="white"
                density="compact"
                prepend-icon=""
                prepend-inner-icon="mdi-calendar-start"
                hide-details
                class="mt-2"
            ></v-date-input>
            </v-col>
            <v-col cols="12" md="12" class="my-2">
            <v-date-input
                v-model="FechaFinFiltro"
                label="Hasta"
                variant="outlined"
                prepend-icon=""
                :min="FechaInicioFiltro"
                bg-color="white"
                density="compact"
                prepend-inner-icon="mdi-calendar-end"
                hide-details
            ></v-date-input>
            </v-col>
            <v-col cols="12" md="12" class="d-flex align-end justify-end ga-2 mb-3">
            <v-spacer />
            <v-btn color="primary" variant="text" class="text-none" @click="limpiarFiltros" rounded="lg">
                <v-icon class="mr-2" size="15">mdi-trash-can</v-icon>
                Limpiar
            </v-btn>
            <v-btn color="primary" class="text-none" @click="aplicarFiltros" rounded="lg">
                <v-icon class="mr-2" size="15">mdi-filter-check</v-icon>
                Aplicar
            </v-btn>
            </v-col>
        </v-row>
        </v-card>
    </v-navigation-drawer>
</template>