<script setup lang="ts">
import {ref} from 'vue'; 
import { AppStore } from '@/stores/AppStore';
const store = AppStore()
import { Link } from '@inertiajs/vue3';
defineProps({
    breadcrumbs: Array,
})
</script>

<template>
    <v-breadcrumbs
      :items="breadcrumbs"
    >
    <template v-slot:prepend>
      <v-btn rounded="lg" :icon="store.SidebarApp ? 'mdi-menu-open' : 'mdi-menu-close'" size="x-small" variant="flat" @click="store.SidebarApp = !store.SidebarApp "></v-btn>
    </template>
    <template v-slot:title="{ item }">
      <span style="font-size:0.875rem;">{{ item.title}}</span>
    </template>
    <template v-slot:divider>
        <v-icon icon="mdi-chevron-right" size="16"></v-icon>
      </template>
    </v-breadcrumbs>
</template>
