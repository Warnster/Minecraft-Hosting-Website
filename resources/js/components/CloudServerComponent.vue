<template>
    <div class="table-container">
        <b-table :items="cloudServers">
            <template slot="actions" slot-scope="row">
                <b-button @click="deleteServer(row)"> Delete </b-button>
            </template>
        </b-table>
    </div>
</template>

<script>
    export default {
        name: "CloudServers",
        props: {
            cloudServers : Array
        },
        methods: {
            deleteServer() {

            }
        }
    }
</script>

<style scoped>
    .table-container {
        overflow-y: auto
    }
</style>
