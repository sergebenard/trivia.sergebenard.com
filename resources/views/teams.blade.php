@extends('layouts.app')

@section('content')
<div id="app" class="">

    <div class="absolute inset-0 flex justify-center items-center" v-if="showLoading">
        <div class="absolute inset-0 bg-blue-800 opacity-75"></div>
        <div class="relative text-5xl text-yellow-400 blink font-extrabold btnTextShadow">Loading...</div>
    </div>

    <!-- 	The large text of the foccused answer -->
    <div class="absolute inset-0 px-12 pt-10 flex justify-center items-center" v-if="showAnswerModal">
        <div class=" absolute bg-white opacity-75 inset-0"></div>

        <div class="relative flex-initial w-full max-w-5xl my-32 mx-auto fixed-
                            bg-blue-700 text-center
                            rounded-md border-8 border-black">
            <div class=" px-10 py-4 text-white text-6xl btnTextShadow font-bold leading-snug"
                v-text="showQuestion ? currentAnswer.question : currentAnswer.answer"></div>

            <progress class="readingCountdown w-full bg-yellow-400"
                max="100"
                :value="readingCountdownProgress"
                v-if="viewType != 'showQuestion'"></progress>
            
                
            <div class="mx-auto py-3 flex flex-wrap"
                v-if="viewType == 'showQuestion'">
                <div class="grid grid-rows-2 w-32 grid-flow-col gap-0 rounded bg-blue-300 mx-1"
                        v-for="(user, userIndex) in users">
                        <div class="row-span-2 flex justify-center items-center"
                                v-text="user.name">Serge</div>
                        <button class="flex flex-wrap justify-center items-center px-3- py-2- rounded-tr
                                        bg-green-300
                                        text-3xl text-center text-green-600
                                        hover:bg-green-600 hover:text-green-300"
                        v-on:click="givePointsToUser( userIndex )">+</button>
                        <button class="flex flex-wrap justify-center items-center px-3- py-2- rounded-br
                                        bg-red-300
                                        text-3xl text-center text-red-600
                                        hover:bg-red-600 hover:text-red-300"
                        v-on:click="givePointsToUser( userIndex, true )">-</button>
                </div>

                <button class=" text-indigo-100 bg-indigo-600 px-3 py-2
                                rounded
                                hover:bg-indigo-300 hover:text-indigo-700"
                        v-on:click="setupSelectAnswerView">Continue</button>
            </div>
            
        </div>

        <div class="  absolute inset-0 z-30"
            v-if="viewType == 'answerTimeUp'"
            v-on:click=" viewType == 'answerTimeUp' ? setupShowQuestionView() : setupShowQuestionView()">
            &nbsp;
        </div>

        
    </div>
    <!-- 	end of focused answer -->

    <div class="flex justify-center items-center mx-auto pt-2 px-4">

        <button class="px-3 py-2 rounded-l flex-1-
                        bg-green-200 text-green-500 border border-green-400
                        hover:bg-green-400 hover:text-green-100"
            @click.prevent="changeRound(0)">Jeopardy!</button>
        <button class="px-3 py-2
                                    bg-green-200 text-green-500 border border-green-400
                                    hover:bg-green-400 hover:text-green-100" @click.prevent="changeRound(1)">Double
            Jeopardy!</button>
        <button class="px-3 py-2
                                    bg-green-200 text-green-500 rounded-r border border-green-400
                                    hover:bg-green-400 hover:text-green-100" @click.prevent="changeRound(2)">Final
            Jeopardy!</button>

    </div>

    <div class="flex flex-wrap max-w-3xl px-10 mx-auto pt-4 justify-center items-center">
        <div class="bg-blue-700 flex flex-wrap justify-center items-center px-3 py-2"
            v-for="user in users">
            <span class="w-full text-blue-100 text-center"
                v-text="user.name"></span>
            <span class="btnHeaderShadow w-full text-xl text-center text-white"
                v-text="user.points"></span>
        </div>

        <div class="max-w-sm mx-auto pt-2"
            v-if="viewType == 'startUp'">
            <label class=" block w-full text-blue-500 py-2"
                for="newUser">New User</label>
            <div class="flex justify-center items-center-">
                <input class=" rounded-l border border-blue-500 px-2 py-2"
                    type="text" name="newUser" id="newUser"
                    v-model="newUser.name">
                <button class=" rounded-r flex justify-center items-center p-2
                    border-t border-r border-b border-blue-500 text-blue-500 text-2xl"
                    v-on:click="addNewUser">+</button>
            </div>
        </div>
    </div>

    <div class="px-4 inline py-4 mx-auto">
        <div class="flex flex-wrap justify-center mx-auto select-none">
            <div class=" bg-yellow-600 w-8 mt-10 mr-4 blink relative z-20"
                v-if="showBuzzerBlinkers"></div>

            <div class="">
                <div class="flex justify-center mx-auto">
                    <div v-for="header in columns" class="w-48 h-24 grid grid-columns-6
                                            text-center">
                        <div class="btnHeaderShadow flex justify-center items-center
                            bg-blue-700 font-bold text-lg text-white border border-b-4 border-black leading-tight"
                            v-text="header[0].category"></div>
                    </div>
                </div>
                <div class="flex justify-center inline mx-auto select-none">

                    <div v-for="(column, columnIndex) in columns"
                        class="w-48 grid grid-rows-5 inline
                                text-center">
                        {{-- <div v-for="(answer, answerIndex) in column" v-text="answer.value">
                            
                        </div> --}}
                        <panel-answer v-for="(answer, answerIndex) in column"
                            v-bind:key="answer.question + answerIndex"
                            v-bind:answer="answer"
                            v-bind:answer-index="answerIndex"
                            v-bind:column-index="columnIndex"
                            v-on:show-answer="setupReadingAnswerView( columnIndex, answerIndex )"></panel-answer>
                    </div>
                </div>
            </div>

            <div class=" bg-yellow-600 w-8 mt-10 ml-4 blink relative z-20 shadow-lg"
                v-if="showBuzzerBlinkers"></div>

        </div>
    </div>
</div>
@endsection

@section( 'javascript' )
    <!-- Scripts -->
    <script src="{{ asset('js/teams.js') }}" defer></script>
@endsection
