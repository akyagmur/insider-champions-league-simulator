<template>
    <Layout title="Simulation">
        <div class="flex gap-4">
            <div class="w-1/2">
                <div class="grid grid-cols-8 bg-blue-800 text-white bg-blue p-4">
                    <div class="col-span-3"> Team Name </div>
                    <div class="col-span-1"> P </div>
                    <div class="col-span-1"> W </div>
                    <div class="col-span-1"> D </div>
                    <div class="col-span-1"> L </div>
                    <div class="col-span-1"> GD </div>
                </div>
                <div class="grid grid-cols-8 p-4 border-b-2" v-for="(team, index) in leagueTable" :key="team.id"
                    :class="{ 'bg-yellow-200': index == leader }">
                    <div class="col-span-3"> {{ team.team.name }} </div>
                    <div class="col-span-1"> {{ team.points }} </div>
                    <div class="col-span-1"> {{ team.won }} </div>
                    <div class="col-span-1"> {{ team.draw }} </div>
                    <div class="col-span-1"> {{ team.lost }} </div>
                    <div class="col-span-1"> {{ team.goal_difference }} </div>
                </div>
            </div>
            <div class="w-1/4">
                <div class="grid grid-cols-1 w-full">
                    <div class="col-span-1">
                        <div class="p-4 bg-blue-800 text-white">
                            {{ week.name }}
                        </div>
                        <div class="p-4 border-b-2" v-for="competition in week.competitions" :key="competition.id">
                            {{ competition.host_team.name }} - {{ competition.guest_team.name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/4">
                <div class="grid grid-cols-8 bg-blue-800 text-white bg-blue p-4">
                    <div class="col-span-7"> Championship Prediction </div>
                    <div class="col-span-1"> % </div>
                </div>
                <div class="grid grid-cols-8 bg-blue p-4 border-b-2" v-for="team in predictions" :key="team.id">
                    <div class="col-span-7"> {{ team.name }} </div>
                    <div class="col-span-1"> {{ team.probability }} </div>
                </div>
            </div>
        </div>
        <div class="flex justify-between p-4">
            <button v-on:click="playAllWeeks()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Play All
                Weeks
            </button>
            <button v-on:click="playNextWeek()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Play Next
                Week
            </button>
            <button v-on:click="resetData()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Reset Data
            </button>
        </div>
        <Results :weeks="weeks" />
    </Layout>
</template>
<script>
import axios from 'axios'
import Layout from './layouts/Layout.vue'
import Results from './Results.vue'
export default {
    components: {
        Layout,
        Results
    },
    name: 'Fixtures',
    data() {
        return {
            weeks: [],
            predictions: [],
            week: [],
            leagueTable: [],
            leader: {}
        }
    },
    methods: {
        fetchLatestWeeksFixture() {
            axios.get('/api/fixtures/get-latest-week')
                .then(response => {
                    this.week = response.data

                    if (this.week.length === 0) {
                        this.generateFixtures();
                    }

                    this.fetchAllWeeks();
                })
                .catch(error => {
                    console.log(error)
                })
        },
        generateFixtures() {
            axios.get('/api/fixtures/prepare-fixtures')
                .then(response => {
                    this.fetchLatestWeeksFixture();
                })
                .catch(error => {
                    console.log(error)
                })
        },
        fetchLeagueTable() {
            axios.get('/api/league-table')
                .then(response => {
                    this.leagueTable = response.data
                    this.leader = this.setLeader(this.leagueTable)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        resetData() {
            axios.get('/api/fixtures/reset-fixtures')
                .then(response => {
                    this.fetchLatestWeeksFixture();
                    this.fetchLeagueTable();
                    this.fetchPredictions();
                })
                .catch(error => {
                    console.log(error.message)
                })
        },
        playAllWeeks() {
            axios.get('/api/play/all-weeks')
                .then(response => {
                    this.fetchLatestWeeksFixture();
                    this.fetchLeagueTable();
                    this.fetchPredictions();
                })
                .catch(error => {
                    console.log(error)
                })

        },
        playNextWeek() {
            axios.get('/api/play/next-week')
                .then(response => {
                    this.fetchLatestWeeksFixture();
                    this.fetchLeagueTable();
                    this.fetchPredictions();
                })
                .catch(error => {
                    alert("Fixtures are already played!")
                })
        },
        fetchPredictions() {
            axios.get('/api/predictions')
                .then(response => {
                    this.predictions = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
        setLeader(table) {
            let max = Math.max.apply(Math, table.map(function (team) { return team.points; }))

            if (max === 0) {
                return -1;
            }

            max = table.filter(function (team) {
                return team.points == max
            }).reduce(function (a, b) {
                return a.goal_difference > b.goal_difference ? a : b
            })

            return table.indexOf(max)
        },
        fetchAllWeeks() {
            axios.get('/api/fixtures/get-fixtures')
                .then(response => {
                    this.weeks = response.data;
                })
                .catch(error => {
                    console.log(error)
                })
        }
    },
    mounted() {
        this.fetchLatestWeeksFixture();
        this.fetchLeagueTable();
        this.fetchPredictions();
    },
}
</script>
