/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import panelAnswerComponent from './components/panel_answer.vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
	el: '#app',
	components: {
		'panel-answer': panelAnswerComponent,
	},
	data: {
		answerCountdownSeconds: 0,
		answerCountdownSecondsDefault: 12,
		
		roundCountdownSeconds: 0,
		roundCountdownSecondsDefault: 363,
		
        readingCountdownSeconds: 0,
        readingCountdownProgress: 0,
		readingCountdownSecondsDefault: 4,
		
		buzzerPenaltySeconds: 0,
		buzzerPenaltySecondsDefault: .5,
        
        buzzerCountdownSeconds: 0,
        buzzerCountdownProgress: 0,
        buzzerCountdownDefault: 5,

		finalJeopardySeconds: 0,
		finalJeopardySecondsDefault: 30,
		
        currentAnswer: {},
        currentAnswerColumn: -1,
        currentAnswerRow: -1,
		
		showAnswer: false,

		showBuzzerBlinkers: false,

        showLoading: false,
		
		roundType: 0,
		
		// View Types are:
		// 0 - Start Up
		// 1 - Select Answer
		// 2 - Reading Answer
		// 3 - Waiting for Buzzer
		// 4 - Answer Time Up
		// 5 - End of Round
		viewType: 0,
		columns: [],
		
		
	},
	methods: {
		reset: function() {
			this.answerCountdownSeconds = 0;
			this.roundCountdownSeconds = 0;
			this.readingCountdownSeconds = 0;
			this.buzzerPenaltySeconds = 0;
			this.finalJeopardySeconds = 0;
			this.currentAnswer = {};
			this.showAnswer = false;
			this.round = 1;
			this.viewType = 0;
		},

		getNewRoundColumns: function( roundType ) {
			this.showLoading = true;

			this.columns = [];

			axios.get('/newRound/' + roundType)
				.then((response)=>{

					this.showLoading = false;
					// return response.data;
                    this.columns = response.data;
                    
                    this.fixAnswerValue();

				});
		},
		
		
		setupJeopardyRound: function() {
			
			console.info( 'Inside setupJeopardyRound function.' );

            console.info( 'Making a request for first round of Jeopardy...' );

			this.getNewRoundColumns( this.roundType );

		},
		
		setupDoubleJeopardyRound: function() {

            this.roundType = 1;
            
			this.getNewRoundColumns( this.roundType );
		},
		
		setupFinalJeopardyRound: function() {
            
            this.roundType = 2;
            
            this.getNewRoundColumns( this.roundType );
		},
		
		setupNewRoundView: function( columnsData ) {
			
		},
		
		setupSelectAnswerView: function() {
            
		},
		
		setupReadingAnswerView: function( columnIndex, answerIndex ) {
            //
            this.currentAnswer = {};

            this.currentAnswerColumn = columnIndex;
            this.currentAnswerRow = answerIndex;

            this.viewType = 2;

            console.info('setupSelectAnswerView; current answer: ' + this.columns[columnIndex][answerIndex].answer);
            
            this.currentAnswer = this.columns[columnIndex][answerIndex];

            this.showAnswer = true;
            
            this.readingCountdownSeconds = this.readingCountdownSecondsDefault;

            this.readAnswer();

        },
        
        readAnswer: function() {
            //
            
        },
		
		setupWaitingForBuzzer: function( ) {

            this.viewType = 3;

            this.buzzerCountdownSeconds = this.buzzerCountdownDefault;

            this.showBuzzerBlinkers = true;
            
		},
		
		setupAnswerTimeUp: function() {
            this.viewType = 4;

            this.buzzerCountdownSeconds = 0;

            this.showBuzzerBlinkers = false;

            this.columns[this.currentAnswerColumn][this.currentAnswerRow].answered_correctly = -1;

            this.showAnswer = false;

            
		},
		
		setupRoundTimeUp: function() {
			
        },
		
		clickAnswer: function(horizontal, vertical) {
			
		},
		
		changeViewType: function(viewType = null) {
			if ( viewType !== null && ( viewType > -1 && viewType < 6) ) {
				
				// Let's set up the view as the view requires
				switch( viewType ) {
					case 0:
						if ( this.setupNewRoundView() ) {
							this.viewType = viewType;
						}
						break;
					case 1:
						if ( this.setupSelectAnswerView() ) {
							this.viewType = viewType;
						}
						break;
					case 2:
						if ( this.setupReadingAnswerView() ) {
							this.viewType = viewType;
						}
						break;
					case 3:
						if ( this.setupWaitingForBuzzer() ) {
							this.viewType = viewType;
						}
						break;
					case 4:
						if ( this.setupAnswerTimeUp( ) ) {
							this.viewType = viewType;
						}
						break;
					case 5:
						this.setupRoundTimeUp();
						break;		
				};
				
				// Finally, set up the View Type!
				this.viewType = viewType;
				console.log('Changed View Type to ' + viewType);
				return true;
				
			}
			
			console.log('Unable to Change View Type to ' + viewType);
			return false;
			
        },
		
		changeRound: function( roundType ) {
				
            this.setRound( roundType );
            
            this.setUpRound();
            
            console.log('Changed round type to ' + roundType);
            return true;
        
		},
		
		nextRound: function() {
			if ( this.round < 2 ) {
				return this.roundType + 1;
            }
            
			return 0;
		},

		setRound: function( roundType ) {
			this.roundType = roundType;
		},
		
		setUpRound: function() {
			switch( this.roundType ) {
				case 0:
					// Jeopardy! round
					this.setupJeopardyRound();
					break;
				case 1:
					// Double Jeopardy! round
					this.setupDoubleJeopardyRound();
					break;
				case 2:
					// Final Jeopardy! round
					this.setupFinalJeopardyRound();
					break;
            }
            
        },

		// countDownTimer() {
        //     if(this.countDown > 0) {
        //         setTimeout(() => {
        //             this.countDown -= 1
        //             this.countDownTimer()
        //         }, 1000)
        //     }
        // }
        

		getAnswerValue: function( row ) {
            
            // Null this out if we're at Final Jeopardy!
            if( this.roundType >= 2 ) {
                console.warn('roundType at or beyond Final Jeopardy!; returning with error.')
                return -1;
            }

            var ret = 0;

            switch ( row ) {
                case 0:
                    ret = 200 * (this.roundType + 1);
                    break;
                case 1:
                    ret = 400 * (this.roundType + 1);
                    break;
                case 2:
                    ret = 600 * (this.roundType + 1);
                    break;
                case 3:
                    ret = 800 * (this.roundType + 1);
                    break;
                case 4:
                    ret = 1000 * (this.roundType + 1);
                    break;
            }
            
            return ret;
            
        },
        
        fixAnswerValue: function() {

            // console.info( this.columns );
            
            console.info( 'Columns: ' + this.columns.length );
            
            let currentRow = 0,
                currentColumn = 0;

            this.columns.forEach( function( column, vue ) {
                currentRow = 0;

                column.forEach( function( answer, vue ) {
                    
                    answer.answer_value = this.getAnswerValue( currentRow );

                    currentRow++;

                }, this, currentRow, currentColumn)

                currentColumn++;
                
            }, this, currentRow, currentColumn);
        },
	},
	mounted: function() {
		
    },
    watch: {

        readingCountdownSeconds: {
            handler(value) {

                if (value > 0 && this.viewType === 2) {
                    setTimeout(() => {
                        this.readingCountdownSeconds--;
                        this.readingCountdownProgress = this.readingCountdownSeconds*100/this.readingCountdownSecondsDefault;
                    }, 1000);
                }
                else if ( value <= 0 && this.viewType === 2 ) {
                    
                    this.setupWaitingForBuzzer();
                    
                    // return;
                }

            },
            immediate: true // This ensures the watcher is triggered upon creation
        },

        buzzerCountdownSeconds: {
            handler(value) {

                if (value > 0 && this.viewType === 3) {
                    setTimeout(( ) => {
                        this.buzzerCountdownSeconds--;
                        this.buzzerCountdownProgress = this.buzzerCountdownSeconds*100/this.buzzerCountdownDefault;
                        
                        console.info('Still time to buzz in...');
                    }, 1000);
                }
                else if ( value < 1 && this.viewType === 3 ) {
                    this.setupAnswerTimeUp();
                }

            },
            immediate: true // This ensures the watcher is triggered upon creation
        }

    }
});
