<script setup>
import Breadcrumbs from '@/components/Breadcrumbs.vue';

import { ref } from 'vue';
defineProps({
    breadcrumbs: Array,
})
</script>

<template>
    <v-main class="bg-grey-lighten-4">
        <v-container class="bg-grey-lighten-4 rounded-lg pa-0 py-2" fluid>
            <v-card width="100%" min-height="98vh" class="rounded-lg border-thin border-opacity" elevation="0">
                <v-row no-gutters>
                    <v-col class="pa-0">
                        <template v-if="breadcrumbs && breadcrumbs.length > 0">
                                <Breadcrumbs :breadcrumbs="breadcrumbs" class="pa-2" />
                        </template>
                    </v-col>
                </v-row>
                <v-divider></v-divider>
                
                <slot></slot>
                
            </v-card>
            
        </v-container>
    </v-main>    
</template>
