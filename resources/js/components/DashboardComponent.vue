<template>
    <div>
        <minecraft-server-form-component :csrf="csrf"></minecraft-server-form-component>
        <terminal-component :ip="ip" :guid="guid"></terminal-component>
    </div>
</template>

<script>
    export default {
        name: "Dashboard",
        mounted() {
            console.log(window.Echo);

        },
        props: {
            csrf: String,
            ip: String,
            guid: String
        },
    }
</script>

<style scoped>

</style>
