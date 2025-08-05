<script type="text/javascript" setup>
    import {ref} from 'vue';
    const Mamas = defineModel('Mamas');
    const Abdomen = defineModel('Abdomen');
    const Pelvis = defineModel('Pelvis');
</script>
<template>
	<v-container fluid class="pa-0">
        <v-row class="rounded-t-lg mt-0">
            <v-col class="text-primary">
                <span style="font-size: 1.025rem;">Gineco-obstétrico</span>
            </v-col>
        </v-row>
        <v-row class="mx-2">
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Mamas</span>
                <v-textarea v-model="Mamas" :counter="255" maxlength="255"auto-grow row-height="4"rows="3"variant="outlined" flat rounded="lg"></v-textarea>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Abdomen</span>
                <v-textarea v-model="Abdomen" placeholder="Describir solo los datos  obstétricos":counter="255" maxlength="255"auto-grow row-height="4"rows="3"variant="outlined" flat rounded="lg"></v-textarea>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Pelvis</span>
                <v-textarea v-model="Pelvis" placeholder="Describir  inspección y tacto vaginal." :counter="255" maxlength="255"auto-grow row-height="4"rows="3"variant="outlined" flat rounded="lg"></v-textarea>
            </v-col>
        </v-row>
    </v-container>
</template>