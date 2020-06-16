<template>
    <div>
    <div id="terminal"></div>
    </div>
</template>

<script>
    import {Terminal} from "xterm";

    export default {
        name: "TerminalComponent",
        props: {
            ip: String,
            guid: String,
        },
        data() {
            return {
                terminal: new Terminal(),
                socket: null
            }
        },
        mounted() {
            this.terminal.open(document.getElementById('terminal'));
            this.terminal.write('Server Console')

            let socket = this.websocketConnect();

        },
        methods: {
            websocketConnect() {
                const socket = new WebSocket(`ws://${this.ip}:2375/containers/mc-${this.guid}/attach/ws?logs=0&stream=1&stdin=1&stdout=1&stderr=1`);
                let that = this;
                socket.onclose = function(e) {
                    console.log('Socket is closed. Reconnect will be attempted in 1 second.', e.reason);
                    setTimeout(function() {
                        that.websocketConnect();
                    }, 1000);
                };

                socket.onerror = function(err) {
                    console.error('Socket encountered error: ', err.message, 'Closing socket');
                    socket.close();
                };
                console.log(this.terminal)
                console.log(that.terminal)
                socket.onmessage = async function (e) {
                    console.log({e})
                    var text = await e.data.text();
                    console.log({text})
                    that.terminal.write(text);
                }

                socket.onopen = function() {
                    that.terminal.onData(function (data) {
                        socket.send(data)
                    });
                }
                return socket;
            }
        }
    }
</script>

<style scoped>

</style>
