<template>
    <div v-if="authPlayerId === player.player_id && true === player.action_on">
        <div class="card aye-shadow">
            <div class="card-header text-bg-primary text-center">
                {{ player.name }}, action is on you
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center p-4 text-bg-dark">
                <button v-on:click="action(option, player)" class="btn btn-primary" v-for="option in player.availableOptions" :key="option.name" v-bind:data-action-id="option.id">
                    {{ option.name }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ActionOn",
    data() {
        return {
            errors: {},
            loading: false
        }
    },
    props: {
        authPlayerId: {
            type: Number
        },
        player: {
            type: Object,
            default: null
        }
    },
    methods: {
        action(action, player){
            console.log('player');
            this.$emit('action', action, player);
        }
    },
}
</script>

<style scoped>
</style>
