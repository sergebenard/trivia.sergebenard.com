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
                            bg-blue-700 text-white text-6xl text-center font-bold leading-snug btnTextShadow
                            rounded-md border-8 border-black">
            <div class=" px-10 py-4"
                v-text="showQuestion ? currentAnswer.question : currentAnswer.answer"></div>

            <progress class="readingCountdown w-full bg-yellow-400"
                max="100"
                :value="readingCountdownProgress"></progress>
            
                
        </div>

        <div class="  absolute inset-0 z-30"
            v-if="viewType == 'answerTimeUp' || viewType == 'showQuestion'"
            v-on:click=" viewType == 'answerTimeUp' ? setupShowQuestionView() : viewType == 'showQuestion' ? setupResetAnswerModal() : setupShowQuestionView()">
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

@section('javascript')
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
@endsection