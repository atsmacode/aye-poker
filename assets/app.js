/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import { createApp } from 'vue'

import axios from 'axios';
import Player from './js/Player.vue'
import ActionOn from './js/ActionOn.vue'
import { toRaw } from 'vue';

createApp({
	delimiters: ['${', '}$'],
    components: {
        Player,
		ActionOn
    },
    data() {
        return {
			pot: 0,
			players: [],
			communityCards: [],
			winner: false,
			sittingOut: [],
			errors: {},
			loading: false,
			message: false,
			suitColours: {
				"Clubs": [
					"text-dark",
					"border border-3 border-dark"
				],
				"Diamonds": [
					"text-danger",
					"border border-3 border-danger"
				],
				"Hearts": [
					"text-danger",
					"border border-3 border-danger"
				],
				"Spades": [
					"text-dark",
					"border border-3 border-dark"
				]
			},
            actionBetAmounts: {
                "Fold": null,
                "Check": null,
                "Call": 50.0,
                "Bet": 50.0,
                "Raise": 50.0
            },
			mercureUpdate: {}
		}
    },
	watch: {
		mercureUpdate(response) {
			console.log('mercureUpdate');

			let data = JSON.parse(response);

			this.handleResponseData(data);
		}
	},
    methods: {
		updatePlayers(players){
			this.players = players;
		},
		updateCommunityCards(communityCards){
			this.communityCards = communityCards;
		},
		updateWinner(data){
			this.winner = data.winner ? data.winner : false;
		},
		updatePot(pot){
			this.pot = pot;
		},
		updateSittingOut(sittingOut){
			this.sittingOut = Object.values(sittingOut);
		},
		action(action, player){
			let active = 1;

			if(action.id === 1){ active = 0; }

			let payload = {
				player_id:      player.player_id,
				action_id:      action.id,
				table_seat_id:  player.table_seat_id,
				hand_street_id: player.hand_street_id,
				active:         active,
				bet_amount:     this.actionBetAmounts[action.name],
				stack:          player.stack
			};

			this.loading = true

			/**
			 * @todo Improve dynamic generation of action URL - currently using string split (plhe/plom).
			 */ 
			let urlParts  = window.location.pathname.split('/');
			let actionUrl = urlParts.includes('dev') ? urlParts[3] : urlParts[2];

			axios.post('/action/' + actionUrl, payload).then(response => {
				console.log(response.data);

                let data = response.data;

				this.handleResponseData(data);
			}).catch(error => {
				console.log(error);
				this.loading = false
				this.errors = error.response.data.errors

			});
		},
		gameData(){
			axios.post(window.location.pathname).then(response => {
                console.log(response);

                let data = response.data;

				this.handleResponseData(data);
			});
		},
		showOptions(action_on){
            return action_on === true && this.winner === false;
        },
		updateMercure(response) {
			this.mercureUpdate = response;
		},
		isSittingOut(auth_player_id){
			return Array.prototype.includes.call(this.sittingOut, auth_player_id);
		},
		handleResponseData(data){
			this.updatePlayers(data.players);
			this.updateCommunityCards(data.communityCards);
			this.updateWinner(data);
			this.updatePot(data.pot);
			this.updateSittingOut(data.sittingOut);
		}
	},
    mounted() {
		const eventSource = new EventSource("https://localhost:8443/.well-known/mercure?topic=player_action");
		eventSource.onmessage = event => {
			let response = toRaw(event.data);

			this.updateMercure(response);
		}

        this.gameData();
    }
}).mount('#app');
