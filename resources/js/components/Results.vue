<template>
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1" v-for="(week, index) in weeks" :key="week.id">
            <div class="p-4 bg-blue-800 text-white">
                {{ week.name }}
            </div>
            <div class="p-4 border-b-2" v-for="competition in week.competitions" :key="competition.id">
                <div class="grid grid-cols-8">
                    <div class="col-span-3">
                        {{ competition.host_team.name }}
                    </div>
                    <div class="col-span-1">
                        {{ competition.host_team_score }}
                    </div>
                    <div class="col-span-1">
                        {{ competition.guest_team_score }}
                    </div>
                    <div class="col-span-3">
                        {{ competition.guest_team.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'Results',
    props: {
        weeks: {
            type: Array,
            required: true,
        },
    }
}
</script>
