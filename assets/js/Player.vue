<template>
    <div class="m-1 p-3">
        <template v-if="player">
            <div class="row mb-2 m-0 p-0 justify-content-center" :class="[player.active ? '' : 'opacity-25']">
                <div v-for="card in player.whole_cards" class="m-0 me-1 bg-white aye-card" v-bind:class="$root.suitColours[card.suit]">
                    <strong>{{card.rankAbbreviation}}</strong> {{card.suitAbbreviation}}
                </div>
            </div>

            <p class="bg-dark rounded text-left p-3 player-panel rounded-pill" :class="[player.action_on && !winner ? 'action-on' : '']">
                {{player.name}} {{ player.stack }}
                <span v-if="player.is_dealer" v-bind:class="'bg-primary'" class="d-inline rounded p-1"><strong>D</strong></span>
                <span v-else-if="player.big_blind" v-bind:class="'bg-primary'" class="d-inline rounded p-1 marker"><strong>BB</strong></span>
                <span v-else-if="player.small_blind" v-bind:class="'bg-primary'" class="d-inline rounded p-1 marker"><strong>SB</strong></span>
                <span v-if="player.action_id" v-bind:class="actionColours[player.action_name]" class="d-inline rounded p-1 marker"><strong>{{player.action_name}}</strong></span>
            </p>
        </template>
        <template v-else>
            <div class="m-0 me-1 aye-card"></div>
            <p class="bg-dark rounded text-center p-3 player-panel rounded-pill opacity-50">Empty Seat</p>
        </template>
    </div>
</template>

<script>
export default {
    name: "Player",
    data() {
        return {
            errors: {},
            loading: false,
            actionColours: {
                "Fold": [
                    "bg-info"
                ],
                "Check": [
                    "bg-info"
                ],
                "Call": [
                    "bg-success"
                ],
                "Bet": [
                    "bg-warning"
                ],
                "Raise": [
                    "bg-danger"
                ]
            },
            actionOn: "action-on"
        }
    },
    props: {
        player: {
            type: Object,
            default: null
        },
        winner: {
            type: [Object, Boolean],
            default: false
        }
    },
    methods: {
        isActive(){
            return this.player.active;
        },
        action(action, player){
            console.log('player');
            this.$emit('action', action, player);
        }
    },
}
</script>

<style scoped>
</style>
