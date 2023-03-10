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

createApp({
	delimiters: ['${', '}$'],
    components: {
        Player
    },
    data() {
        return {
			deck: false,
			pot: 0,
			players: false,
			communityCards: false,
			winner: false,
			errors: {},
			loading: false,
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
            }
		}
    },
    methods: {
		action(action, player){
			let active = 1;

			if(action.id === 1){ active = 0; }

			let payload = {
				deck:           this.deck,
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
			axios.post('/action/' + window.location.pathname.split('/')[2], payload).then(response => {
				console.log(response.data);

                let data = response.data;

				this.loading        = false
				this.players        = data.players;
				this.communityCards = data.communityCards;
				this.deck           = data.deck;
				this.winner         = data.winner ? data.winner : false;
                this.pot            = data.pot;
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

				this.winner         = false;
				this.players        = data.players;
				this.communityCards = data.communityCards;
				this.deck           = data.deck;
				this.pot            = data.pot;
			});
		},
		showOptions(action_on){
            return action_on === true && this.winner === false;
        },
	},
    mounted() {
        this.gameData();
    }
}).mount('#app');
