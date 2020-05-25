@extends('layouts.app')

@section('content')
<div id="app" class="">

    <div class="absolute inset-0 flex justify-center items-center" v-if="showLoading">
        <div class="absolute inset-0 bg-blue-800 opacity-75"></div>
        <div class="relative text-5xl text-yellow-400 blink font-extrabold btnTextShadow">Loading...</div>
    </div>

    <!-- 	The large text of the foccused answer -->
    <div class="absolute inset-0 px-12 pt-10 flex justify-center items-center" v-if="showAnswer">
        <div class=" absolute bg-white opacity-75 inset-0"
        v-on:click="showAnswer = false"></div>

        <div class="relative max-w-5xl mx-auto fixed-
                            bg-blue-700 text-white text-6xl text-center font-bold leading-snug btnTextShadow
                            rounded-md border-8 border-black">
            <div class=" px-10 py-4"
                v-text="viewType !== 'answerTimeUp' ? currentAnswer.question : currentAnswer.answer"></div>
            <progress class="readingCountdown w-full bg-yellow-400"
                max="100"
                :value="readingCountdownProgress"></progress>
                
        </div>
    </div>
    <!-- 	end of focused answer -->

    <div class="flex justify-center items-center mx-auto pt-2 px-4">
        {{-- <div class="bg-blue-400 px-3 py-2 rounded-l text-blue-200 border border-transparent">
            Change to view mode
        </div>
        <button class="px-3 py-2
                                    bg-blue-200 text-blue-500 border border-blue-400
                                    hover:bg-blue-600 hover:text-blue-100 hover:border-blue-600"
            v-on:click.prevent="changeViewType(0)">0</button>
        <button class="px-3 py-2
                                    bg-blue-200 text-blue-500 border border-blue-400
                                    hover:bg-blue-600 hover:text-blue-100 hover:border-blue-600"
            @click.prevent="changeViewType(1)">1</button>
        <button class="px-3 py-2
                                    bg-blue-200 text-blue-500 border border-blue-400
                                    hover:bg-blue-600 hover:text-blue-100 hover:border-blue-600"
            @click.prevent="changeViewType(2)">2</button>
        <button class="px-3 py-2
                                    bg-blue-200 text-blue-500 border border-blue-400
                                    hover:bg-blue-600 hover:text-blue-100 hover:border-blue-600"
            @click.prevent="changeViewType(3)">3</button>
        <button class="px-3 py-2
                                    bg-blue-200 text-blue-500 rounded-r border border-blue-400
                                    hover:bg-blue-600 hover:text-blue-100 hover:border-blue-600"
            @click.prevent="changeViewType(4)">4</button>

        <div class="w-12"></div>
            --}}

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
                    <div v-for="header in columns" class="w-48 grid grid-columns-6
                                            text-center">
                        <div class="btnHeaderShadow px-2 py-5 flex justify-center items-center
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
                            v-bind:key="answer.question"
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
