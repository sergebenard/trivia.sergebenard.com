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

        newUser: {
            name: "",
            points: 0,
			correct: 0,
			wrong: 0,
        },

        users: [],

        remoteUrl: "/teams/remote",

        answerCountdown: {},
        answerCountdownSeconds: 0,
		answerCountdownSecondsDefault: 12,
        
        roundCountdown: {},
		roundCountdownSeconds: 0,
        roundCountdownSecondsDefault: 363,
		
        readingCountdown: {},
        readingCountdownSeconds: 0,
        readingCountdownProgress: 0,
        readingCountdownSecondsDefault: 4,
        
        buzzerPenalty: {},
		buzzerPenaltySeconds: 0,
		buzzerPenaltySecondsDefault: .5,
        
        buzzerCountdown: {},
        buzzerCountdownSeconds: 0,
        buzzerCountdownProgress: 0,
        buzzerCountdownDefault: 5,

        givingQuestion: {},
        givingQuestionCountdownSeconds: 0,
        givingQuestionCountdownProgress: 0,
        givingQuestionCountdownDefault: 5,

        finalJeopardyCountdown: {},
		finalJeopardyCountdownSeconds: 0,
		finalJeopardyCountdownDefault: 30,
        
        successfulBuzzer: false,

        showAnswerModal: false,
        showQuestion: false,

		showBuzzerBlinkers: false,

        showLoading: false,
        
        newWindow: null,
        
		roundType: 0,
		
		// View Types are:
        // startUp - Start Up
        // loadingData - Loading data from server
		// selectAnswer - Select Answer
		// readingAnswer - Reading Answer
		// waitingForBuzzer - Waiting for Buzzer
        // answerTimeUp - Answer Time Up
        // givingQuestion - When the user is allowed to give the question
        // showQuestion - When the user is allowed to view the question
		// 6 - End of Round
        viewType: 'startUp',
        
        currentAnswer: {},
        currentAnswerColumn: -1,
        currentAnswerRow: -1,

		columns: [],
		
	},
	methods: {

        addNewUser: function() {
            if( this.newUser.name !== "" ) {
                this.users.push( this.newUser );
                this.newUser = {
                    name: "",
					points: 0,
					correct: 0,
					wrong: 0,
					currentAnswer: 0,
                }
            }
        },

        setupResetAnswerModal: function( ) {
            console.info('In setupResetAnswerModal...');

            //Hide the Answer Modal and also Show the Loading screen while we're doing this...
            this.showAnswerModal = false;
            this.showLoading = true;

            this.answerCountdown = {};
            this.answerCountdownSeconds = 0;
            
            this.roundCountdown = {};
            this.roundCountdownSeconds = 0;
            
            this.readingCountdown = {};
            this.readingCountdownSeconds = 0;
            this.readingCountdownProgress = 0;
            
            this.buzzerPenalty = {};
            this.buzzerPenaltySeconds = 0;
            
            this.buzzerCountdown = {};
            this.buzzerCountdownSeconds = 0;
            this.buzzerCountdownProgress = 0;

            this.givingQuestion = {};
            this.givingQuestionCountdownSeconds = 0;
            this.givingQuestionCountdownProgress = 0;

            this.finalJeopardyCountdown = {};
            this.finalJeopardyCountdownSeconds = 0;
            
            this.successfulBuzzer = false;

            this.showQuestion = false;

            this.showBuzzerBlinkers = false;

            this.viewType = 'selectAnswer',
        
            this.currentAnswer = {},
            this.currentAnswerColumn = -1,
            this.currentAnswerRow = -1,

            this.showLoading = false;

        },

		getNewRoundColumns: function( roundType ) {
            
            this.viewType = "loadingData";

            this.showLoading = true;

            this.columns = [];
            
            this.currentAnswer = {};

			axios.get('/newRound/' + roundType)
				.then((response)=>{

					this.showLoading = false;
					// return response.data;
                    this.columns = response.data;
                    
                    this.fixAnswerValue();

                    this.openNewWindow();

                    this.viewType = "selectAnswer";
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
        
        givePointsToUser: function( userIndex, subtract = false ) {
            console.debug("In givePointsToUser...");
            if ( subtract === false ) {
                console.debug("Adding points...");
                this.users[ userIndex ].points += this.currentAnswer.answer_value;

                // this.setupResetAnswerModal();
                return;
            }
            
            console.debug("Subtracting points...");
            this.users[ userIndex ].points -= this.currentAnswer.answer_value;
            
        },
		
		setupSelectAnswerView: function() {
            this.setupResetAnswerModal();

            this.viewType = 'selectAnswer';
		},
		
		setupReadingAnswerView: function( columnIndex, answerIndex ) {

            this.viewType = 'readingAnswer';
            //
            this.currentAnswer = {};

            this.currentAnswerColumn = columnIndex;
            this.currentAnswerRow = answerIndex;

            console.info('setupSelectAnswerView; current question: ' + this.columns[columnIndex][answerIndex].question);
            
            this.currentAnswer = this.columns[columnIndex][answerIndex];

            this.openNewWindow();

            this.showAnswerModal = true;
            
            this.readingCountdownSeconds = this.readingCountdownSecondsDefault;


        },

        getRemoteURL: function() {
            return this.remoteUrl + "/" + ( this.currentAnswer.question === null || this.currentAnswer.question === undefined ? 'Please Move This Window Away from the Shared Screen' : encodeURI( this.currentAnswer.question.replace(/\//g, '\\') ) );
        },

        openNewWindow: function() {

            let url = this.getRemoteURL();

            if ( this.newWindow === null || this.newWindow.closed ) {

                this.newWindow = window.open( url,
                    'triviaTrainingAnswer', 'menubar=no,location=no,resizable=yes,scrollbars=no,status=no, width=500,height=500');
                return true;
            }

            this.newWindow = window.open( url,
                'triviaTrainingAnswer');

        },
        
        setupHideAnswerModalView: function() {
            // Reset everything relating to the answer modal
            
        },
        
		setupWaitingForBuzzer: function( ) {

            this.viewType = 'waitingForBuzzer';

            this.buzzerCountdownSeconds = this.buzzerCountdownDefault;

            this.showBuzzerBlinkers = true;
            
		},
		
		setupAnswerTimeUp: function() {

            this.viewType = 'answerTimeUp';

            // this.buzzerCountdownSeconds = 0;

            this.showBuzzerBlinkers = false;

            this.columns[this.currentAnswerColumn][this.currentAnswerRow].answered_correctly = -1;

            // this.setupShowQuestionView();

        },
        
        setupShowQuestionView: function() {
            this.viewType = 'showQuestion';
            this.showQuestion = true;


        },

        
        setupHideAnswerModalView: function() {
            // Reset everything relating to the answer modal
            
        },
		
		setupRoundTimeUp: function() {
			
        },
		
		clickAnswer: function(horizontal, vertical) {
			
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

        givingQuestionCountdownSeconds: {
            handler(value) {

                if (value > 0 && this.viewType == 'readingAnswer') {
                    setTimeout(() => {
                        this.givingQuestionCountdownSeconds--;
                        this.readingCountdownProgress = this.givingQuestionCountdownSeconds*100/this.givingQuestionCountdownSecondsDefault;
                    }, 1000);
                }
                else if ( value <= 0 && this.viewType == 'readingAnswer' ) {
                    
                    this.setupWaitingForBuzzer();
                    
                    // return;
                }

            },
            immediate: true // This ensures the watcher is triggered upon creation
        },

        readingCountdownSeconds: {
            handler(value) {

                if (value > 0 && this.viewType == 'readingAnswer') {
                    this.readingCountdown = setTimeout(() => {
                        this.readingCountdownSeconds--;
                        this.readingCountdownProgress = this.readingCountdownSeconds*100/this.readingCountdownSecondsDefault;
                    }, 1000);
                }
                else if ( value <= 0 && this.viewType == 'readingAnswer' ) {
                    
                    this.setupWaitingForBuzzer();
                    
                    // return;
                }

            },
            immediate: true // This ensures the watcher is triggered upon creation
        },

        buzzerCountdownSeconds: {
            handler(value) {

                if (value > 0 && this.viewType == 'waitingForBuzzer') {
                    this.buzzerCountdown = setTimeout(( ) => {
                        
                        console.info('Still time to buzz in...');

                        this.buzzerCountdownSeconds--;
                        this.buzzerCountdownProgress = this.buzzerCountdownSeconds*100/this.buzzerCountdownDefault;

                    }, 1000);
                }
                else if ( value < 1 && this.viewType == 'waitingForBuzzer' ) {
                    console.info('Time is up!');

                    this.setupAnswerTimeUp();
                }

            },
            immediate: true // This ensures the watcher is triggered upon creation
        }

    },
    beforeDestroy: function() {
        this.newWindow.close();
    }
});
