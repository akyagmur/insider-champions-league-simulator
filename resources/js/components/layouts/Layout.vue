<template>
    <div class="container w-full md:max-w-7xl mx-auto pt-10">
        <div class="flex justify-center p-4">
            <h2 class="text-2xl">{{ title }}</h2>
        </div>
        <slot />
    </div>
</template>

<script>
export default {
    props: ['title'],
}
</script>
