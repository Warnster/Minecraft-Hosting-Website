<template>
    <div>
        <h2>Location</h2>
        <b-form-group label="Location">
            <b-form-radio v-model="mcForm.location" value="1">EU</b-form-radio>
        </b-form-group>
        <h2>Ram</h2>
        <label for="range-1">{{ mcForm.ram }} Gb</label>
        <b-form-input id="range-1" v-model="mcForm.ram" type="range" min="1" max="30"></b-form-input>
        <div>Price: ${{price}}</div>
        <div>Cost: ${{cost}}</div>
        <div>Profit: ${{profit}}</div>
        <b-button @click="createMinecraftServer">
            Create Minecraft Server
            <b-spinner v-if="loading" small></b-spinner>
        </b-button>
    </div>
</template>

<script>
    export default {
        name: "MinecraftServerFormComponent",
        mounted() {
            console.log(window.Echo)
            window.Echo.private(`App.User.2`)
                .listen('.test', (e) => {
                    console.log(e.update);
                    console.log('heys')
                });
        },
        data() {
            return {
                loading: false,
                mcForm: {
                    ram: 1,
                    location: 1
                }
            }
        },
        computed: {
            price: function() {
                return this.mcForm.ram * 5;
            },
            cost: function() {
                return ((35.88 * 1.12) / 30) * this.mcForm.ram;
            },
            profit: function() {
                return this.price - this.cost;
            }
        },
        props: {
            csrf: String,
        },
        methods: {
            createMinecraftServer(input, init) {
                this.loading = true;
                this.postData('/minecraft/server', { location: this.mcForm.location,
                    ram: this.mcForm.ram,
                    'csrf-token': this.csrf })
                    .then((data) => {
                        console.log(data); // JSON data parsed by `response.json()` call
                    });
            },
            // Example POST method implementation:
            async postData(url = '', data = {}) {

                const response = await fetch(url, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": this.csrf
                    },
                    method: 'post',
                    credentials: "same-origin",
                    body: JSON.stringify(data)
                })
                // Default options are marked with *
                return await response.json(); // parses JSON response into native JavaScript objects
            }
        }
    }
</script>

<style scoped>

</style>
