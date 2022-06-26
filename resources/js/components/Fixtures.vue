<template>
    <Layout title="Fixtures">
        <div class="grid grid-cols-4 gap-2">
            <div class="col-span-1" v-for="(week, index) in weeks" :key="week.id">
                <div class="p-4 bg-blue-800 text-white">
                    {{ week.name }}
                </div>
                <div class="p-4 border-b-2" v-for="competition in week.competitions" :key="competition.id">
                    {{ competition.host_team.name }} - {{ competition.guest_team.name }}
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center p-4">
            <router-link :to="{ name: 'simulation' }" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Start Simulation
            </router-link>
        </div>
    </Layout>
</template>
<script>
import axios from 'axios'
import Layout from './layouts/Layout.vue'
export default {
    components: {
        Layout
    },
    name: 'Fixtures',
    data() {
        return {
            weeks: [],
        }
    },
    methods: {
        fetchAllWeeks() {
            axios.get('/api/fixtures/get-fixtures')
                .then(response => {
                    this.weeks = response.data;

                    if (!this.weeks[0].competitions.length) {
                        this.generateFixtures();
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        },
        generateFixtures() {
            axios.get('/api/fixtures/prepare-fixtures')
                .then(response => {
                    this.weeks = response.data;
                })
                .catch(error => {
                    console.log(error)
                })
        }
    },
    mounted() {
        this.fetchAllWeeks();
    },
}
</script>
