<template>
    <Layout title="Begin INSIDER Championship!">
        <div class="flex items-center justify-center">
            <div class="w-1/2">
                <div class="p-4 bg-blue-800 text-white">
                    Teams in the league
                </div>
                <div class="p-4 border-b-2" v-for="team in teams" :key="team.id">{{ team.name }}</div>
                <div class="p-4">
                    <router-link :to="{ name: 'fixtures' }" custom v-slot="{ navigate }">
                        <button @click="navigate"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Generate
                            Fixtures
                        </button>
                    </router-link>
                </div>
            </div>
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
    name: 'Index',
    data() {
        return {
            teams: []
        }
    },
    methods: {
        fetchTeams() {
            axios.get('/api/teams')
                .then(response => {
                    this.teams = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        }
    },
    mounted() {
        this.fetchTeams()
    },
}
</script>
