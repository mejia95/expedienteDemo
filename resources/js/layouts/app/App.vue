<script setup>
import { Head, usePage, router, Link } from '@inertiajs/vue3';
import { ref,computed } from 'vue'
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import AppContent from '@/components/AppContent.vue';

import { AppStore } from '@/stores/AppStore';
import { useDisplay } from 'vuetify'

const { smAndDown,mdAndUp } = useDisplay()
const drawer = ref(true)
const showAlert = ref(true)
const store = AppStore();

const open = ref([])
const items = ref([
    {
        title: 'Dashboard',
        icon: 'mdi-view-dashboard',
        to: '/dashboard'
    },
    {
        title: 'Expedientes',
        icon: 'mdi-file-document',
        to: '/expedientes'
    },
    {
        title: 'Consultas',
        icon: 'mdi-calendar-clock',
        to: '/consultas'
    },
    {
        title: 'Pacientes',
        icon: 'mdi-account-group',
        to: '/pacientes'
    },
    {
        title: 'Reportes',
        icon: 'mdi-chart-bar',
        to: '/reportes'
    }
]);

const ModeloDrawer = computed(()=>{
    return store.SidebarApp;
})
const props = defineProps({
    breadcrumbs: Array,
    ruta_valor:String
})
const page = usePage()
const currentRoute = computed(() => usePage().url)
const session = computed(() => page.props.session)
const isPersonalGroupOpen = ref([page.props.ruta_valor]);

</script>

<template>
    
    <v-app id="inspire">
    
        <v-navigation-drawer
            :model-value="mdAndUp ? true : store.SidebarApp"
            @update:modelValue = "store.SidebarApp = ! store.SidebarApp"
            :mobile-breakpoint="960"
            
            color="grey-lighten-4"
            floating
            :rail="mdAndUp ? store.SidebarApp : false"
            :rail-width="60"
            elevation="0"
        >
            <v-list nav density="compact">
                
                <v-list-item
                    :title="`${page.props.session.nombreUsuario}`"
                >
                <template v-slot:prepend>
                    <v-avatar color="primary" rounded size="30">{{ $page.props.session.inicialesUsuario }}</v-avatar>
                </template> 
                <template v-slot:subtitle  v-if="!store.SidebarApp">
                    <v-row no-gutters>
                        <v-col cols="12">{{ $page.props.session.Dependencia }}</v-col>
                        <v-col cols="12"> {{ $page.props.session.rolEtiquetaUsuario }}</v-col>
                    </v-row>
                </template>
            </v-list-item>
            </v-list>
            <v-list density="compact" nav item-props v-model:opened="isPersonalGroupOpen">
                <Link href="/dashboard" class="text-decoration-none text-grey-darken-4">
                    <v-list-item
                    slim
                    :active="currentRoute === '/dashboard'"
                    key="principal"
                    value="principal"
                    href="/dashboard"
                    :class="currentRoute === '/dashboard' ? 'bg-primary' : ''"
                >
                    <template v-slot:prepend>
                        <v-icon icon="mdi-home-outline" class="ml-1" size="20"></v-icon>
                    </template>
                    <template v-slot:title>
                        <span :class="currentRoute === '/dashboard' ? 'font-weight-bold text-white' : 'font-weight-medium text-medium-emphasis'">Principal</span>
                    </template>
                </v-list-item>
                </Link>
                
                <v-list-group
                    v-if="page.props.session.rolUsuario == 2 || page.props.session.rolUsuario == 1"
                    fluid
                    value="importaciones"
                    key="importaciones"
                >
                    <template v-slot:activator="{ isOpen, props }">
                    <v-list-item
                        v-bind="props"
                        slim
                        key="/medicos-pasantes"
                        value="/medicos-pasantes"
                        :active="currentRoute === '/importaciones/medico-pasante'"
                        :class="currentRoute === '/importaciones/medico-pasante' ? 'bg-primary opacity-70' : ''"
                    >
                        <template v-slot:prepend>
                        <v-icon class="ml-1" size="20">mdi-database</v-icon>
                        </template>
                        <template v-slot:title>
                        <span :class="currentRoute === '/importaciones/medico-pasante' ? 'font-weight-bold text-white' : 'font-weight-medium text-medium-emphasis'">
                            Importaciones
                        </span>
                        </template>
                    </v-list-item>
                    </template>

                    <Link href="/importaciones/medico-pasante" class="text-decoration-none text-grey-darken-2">
                        <v-list-item
                            value="medicos2"
                        prepend-icon="mdi-alpha-p"
                        title="Médicos Pasantes"
                        :class="currentRoute === '/importaciones/medico-pasante' ? 'bg-grey-lighten-2' : 'text-grey-darken-1'"
                            link
                        >
                        </v-list-item>
                    </Link>
                    
                </v-list-group>     
                
                <v-list-group
                    v-if="page.props.session.rolUsuario == 2"
                    fluid
                    key="personal"
                    value="personal"
                >
                    <template v-slot:activator="{ isOpen, props }">
                    <v-list-item
                        v-bind="props"
                        slim
                        key="/personal"
                        value="/personal"
                        :active="currentRoute === '/personal/medico-pasante'"
                        :class="currentRoute === '/personal/medico-pasante' ? 'bg-primary opacity-70' : ''"
                    >
                        <template v-slot:prepend>
                        <v-icon class="ml-1" size="20">mdi-account-multiple-outline</v-icon>
                        </template>
                        <template v-slot:title>
                        <span :class="currentRoute === '/personal/medico-pasante' ? 'font-weight-bold text-white' : 'font-weight-medium text-medium-emphasis'">
                            Personal
                        </span>
                        </template>
                    </v-list-item>
                    </template>

                    <Link href="/personal/medico-pasante" class="text-decoration-none text-grey-darken-2">
                    <v-list-item
                        prepend-icon="mdi-alpha-p"
                        title="Médicos Pasantes"
                        :class="currentRoute === '/personal/medico-pasante' ? 'bg-grey-lighten-2' : 'text-grey-darken-1'"
                        link
                    />
                    </Link>
                </v-list-group>
                <v-list-group  value="pacientes" fluid v-if="page.props.session.rolUsuario == 3">
                            <template v-slot:activator="{ isOpen, props }">

                                <v-list-item
                                v-bind="props"
                                nav slim
                                key="pacientes"
                                value="pacientes"
                                :active="currentRoute.startsWith('/consulta/nuevo') || currentRoute === '/pacientes/todos' || currentRoute === '/pacientes/mis-pacientes' || currentRoute === '/pacientes/antecedentes' || currentRoute === '/pacientes/nuevo'"
                                :class="currentRoute.startsWith('/consulta/nuevo') ||currentRoute === '/pacientes/todos' || currentRoute === '/pacientes/mis-pacientes' || currentRoute === '/pacientes/antecedentes' || currentRoute === '/pacientes/nuevo' ? 'bg-primary opacity-70 ' : ''"
                                >
                                    <template v-slot:prepend>
                                        <v-icon class="ml-1" size="20">mdi-account-injury-outline</v-icon>
                                    </template>

                                    <template v-slot:title>
                                        <span :class="currentRoute.startsWith('/consulta/nuevo') || currentRoute === '/pacientes/todos' || currentRoute === '/pacientes/mis-pacientes' || currentRoute === '/pacientes/antecedentes' || currentRoute === '/pacientes/nuevo' ? 'font-weight-bold text-white' : 'font-weight-medium text-medium-emphasis'">Pacientes</span>
                                    </template>
                                </v-list-item>
                            </template>
                            <Link href="/pacientes/todos" class="text-decoration-none text-grey-darken-2">
                                <v-list-item
                                    key="pacientes/todos"                            
                                    to="pacientes/todos"
                                    prepend-icon="mdi-alpha-g"
                                    title="Globales"
                                    :class="currentRoute === '/pacientes/todos' ? ' bg-grey-lighten-2' : 'text-grey-darken-2'"
                                    link
                                    ></v-list-item>
                            </Link>
                            <Link href="/pacientes/mis-pacientes" class="text-decoration-none text-grey-darken-2">
                                <v-list-item
                                key="/pacientes/mis-pacientes"                       
                                prepend-icon="mdi-alpha-m"
                                title="Mis pacientes"
                                :class="currentRoute === '/pacientes/mis-pacientes' ? ' bg-grey-lighten-2' : 'text-grey-darken-2'"
                                link
                                ></v-list-item>
                            </Link>
                            <Link href="/pacientes/antecedentes" class="text-decoration-none text-grey-darken-2">
                                <v-list-item
                                key="/pacientes/antecedentes"
                                prepend-icon="mdi-alpha-a"
                                title="Antecedentes"
                                :class="currentRoute === '/pacientes/antecedentes' ? 'bg-grey-lighten-2' : 'text-grey-darken-2'"
                                link
                                ></v-list-item>
                            </Link>
                            
                            
                            
                </v-list-group> 
                <v-list-group  value="reportes" fluid v-if="page.props.session.rolUsuario == 2">
                    <template v-slot:activator="{ isOpen, props }">
                        <v-list-item
                            v-bind="props"
                            slim
                            key="/reportes"
                            value="/reportes"
                            :active="currentRoute.startsWith('/reportes')"
                            :class="currentRoute.startsWith('/reportes')  ? 'bg-primary opacity-70 ' : ''"
                        >
                            <template v-slot:prepend>
                                <v-icon class="ml-1" size="20">mdi-file-document-multiple-outline</v-icon>
                            </template>
                            <template v-slot:title>
                            <span :class="currentRoute.startsWith('/reportes') ? 'font-weight-bold text-white' : 'font-weight-medium text-medium-emphasis'">
                                Reportes
                            </span>
                            </template>
                        </v-list-item>
                    </template>
                    <Link href="/reportes/suive" class="text-decoration-none ">
                        <v-list-item
                            prepend-icon="mdi-alpha-s"
                            color="red"
                            title="SUIVE"
                            :class="currentRoute === '/reportes/suive' ? 'bg-grey-lighten-2 text-grey-darken-3' : 'text-grey-darken-1'"
                            link
                        />
                    </Link>
                    <Link href="/reportes/pacientes" class="text-decoration-none text-grey-darken-2">
                        <v-list-item
                            prepend-icon="mdi-alpha-p"
                            title="Pacientes"
                            :class="currentRoute === '/reportes/pacientes' ?  'bg-grey-lighten-2 text-grey-darken-3' : 'text-grey-darken-1'"
                            link
                        />
                    </Link>
                </v-list-group>
                <Link href="/lista/medicamentos" class="text-decoration-none text-grey-darken-4">
                    <v-list-item
                    v-if="page.props.session.rolUsuario == 4"                    
                    slim
                    :active="currentRoute === '/lista/medicamentos'"
                    key="medicamentos"
                    value="medicamentos"
                    href="/lista/medicamentos"
                    :class="currentRoute === '/lista/medicamentos' ? 'bg-primary' : ''"
                >
                    <template v-slot:prepend>
                        <v-icon icon="mdi-pill" class="ml-1" size="20"></v-icon>
                    </template>
                    <template v-slot:title>
                        <span :class="currentRoute === '/lista/medicamentos' ? 'font-weight-bold text-white' : 'font-weight-medium text-medium-emphasis'">Medicamentos</span>
                    </template>
                </v-list-item>
                </Link>   
            </v-list>
            <template v-slot:append>
                <div class="pa-2"  >
                    <v-list-item
                        class="cursor-pointer"
                        slim
                        @click="router.post('/logout')"
                    >
                        <template v-slot:prepend>
                            <v-icon icon="mdi-logout" size="20"></v-icon>
                        </template>
                        <template v-slot:title>
                            <span style="font-size:0.875rem" class = "poppins-medium text-medium-emphasis text-decoration-none text-center">Cerrar sesión</span>
                        </template>
                    </v-list-item>
                </div>
            </template>
        </v-navigation-drawer>
       
        <AppContent :breadcrumbs="breadcrumbs">
            <slot/>
        </AppContent>
    </v-app>
</template>
