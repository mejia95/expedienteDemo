<script setup>

    const props = defineProps({
    item: {
        type: Object,
        required: true
    }
    });
</script>

<template>
    <v-card title="Medicamento" subtitle="Inormación del medicamento">
        <v-divider></v-divider>
    <v-card-text>
        <v-row>
            <v-col>
                <span class="font-weight-medium">Fármaco(s):</span>
                <br>
                <span class="font-weight-light "> {{ props.item.farmacos }}</span>       
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <span class="font-weight-medium">Forma Farmacéutica (consideración de uso): </span>
                <br>
                <span class="font-weight-light ">{{ props.item.forma_farmaceutica }}</span>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                 <span class="font-weight-medium">Concentración: </span>
                <br>
                <span class="font-weight-light ">{{ props.item.concentracion }}</span>
            </v-col>
        </v-row>
        <v-row>
            <v-col>                
                 <span class="font-weight-medium">Fracción (Art. 226 LGS): </span>
                <br>
                <span class="font-weight-light ">{{ props.item.fraccion }}</span>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                 <span class="font-weight-medium">Denominación Distintiva: </span>
                <br>
                <span class="font-weight-light ">{{ props.item.denominacion_distintiva }}</span>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                 <span class="font-weight-medium">Registro Sanitario: </span>
                <br>
                <span class="font-weight-light ">{{ props.item.registro_sanitario }}</span>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                 <span class="font-weight-medium">Titular: </span>
                <br>
                <span class="font-weight-light ">{{ props.item.titular }}</span>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                 <span class="font-weight-medium">Indicación terapéutica: </span>
                <br>
                <span class="font-weight-light ">{{ props.item.indicacion_terapeutica }}</span>                
            </v-col>
        </v-row>
    </v-card-text>
    <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
            color="primary"
            rounded="lg"
            variant="flat"
            @click="$emit('close')"
        >
            Cerrar
        </v-btn>
    </v-card-actions>
    </v-card>
</template>