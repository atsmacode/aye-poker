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
			mode: 1,
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
			mercureUpdate: {},
			mercureUrl: ''
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
		updatePlayers(data){
			this.players = data.players ? data.players : [];
		},
		updateCommunityCards(data){
			this.communityCards = data.communityCards ? data.communityCards : [];
		},
		updateWinner(data){
			this.winner = data.winner ? data.winner : false;
		},
		updatePot(data){
			this.pot = data.pot ? data.pot : 0;
		},
		updateSittingOut(data){
			this.sittingOut = data.sittingOut ? Object.values(data.sittingOut) : [];
		},
		updateMessage(data){
			this.message = data.message ? data.message : '';
		},
		updateMercureUrl(data){
			this.mercureUrl = data.mercureUrl ? data.mercureUrl : '';
		},
		updateMode(data){
			this.mode = data.mode ? data.mode : 1;
		},
		action(action, player){
			let gameId = document.getElementById('game_id').value;

			let payload = {
				player_id:      player.player_id,
				action_id:      action.id,
				table_seat_id:  player.table_seat_id,
				hand_street_id: player.hand_street_id,
				bet_amount:     this.actionBetAmounts[action.name],
				stack:          player.stack,
				gameId:         gameId
			};

			this.loading = true

			/**
			 * @todo Improve dynamic generation of action URL - currently using string split (plhe/plom).
			 */ 
			// let urlParts  = window.location.pathname.split('/');
			// let actionUrl = urlParts.includes('dev') ? urlParts[3] : urlParts[2];

			axios.post('/action/plhe', payload).then(response => {
				console.log(response.data);

                let data = response.data;

				this.handleResponseData(data);
			}).catch(error => {
				console.log(error);
				this.loading = false
				this.errors = error.response.data.errors

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
			this.updatePlayers(data);
			this.updateCommunityCards(data);
			this.updateWinner(data);
			this.updatePot(data);
			this.updateSittingOut(data);
			this.updateMessage(data);
			this.updateMercureUrl(data);
			this.updateMode(data);
		},
		gameData(){
			let gameId = document.getElementById('game_id').value;
			let tableId = document.getElementById('table_id').value;

			axios.post('/play/plhe', {gameId, tableId}).then(response => {
				console.log(response);
	
				let data = response.data;
	
				this.handleResponseData(data);
	
				const eventSource = new EventSource(data.mercureUrl);
				eventSource.onmessage = event => {
					let response = toRaw(event.data);
	
					this.updateMercure(response);
				}
			});
		}
	},
    mounted() {
		this.gameData();
    }
}).mount('#app');
