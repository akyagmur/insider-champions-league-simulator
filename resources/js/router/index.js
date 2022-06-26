import { createRouter, createWebHistory } from "vue-router";

import Index from "../components/Index.vue";
import Fixtures from "../components/Fixtures.vue";
import Simulation from "../components/Simulation.vue";

const routes = [{
        path: '/',
        name: 'index',
        component: Index
    },
    {
        path: '/fixtures',
        name: 'fixtures',
        component: Fixtures
    },
    {
        path: '/simulation',
        name: 'simulation',
        component: Simulation
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
});
