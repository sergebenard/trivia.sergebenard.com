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
      <div class=" px-10 py-4 text-white text-6xl btnTextShadow font-bold leading-snug" v-text="showQuestion ? currentAnswer.question : currentAnswer.answer"></div>

      <progress class="readingCountdown w-full bg-yellow-400" max="100" :value="readingCountdownProgress" v-if="viewType != 'showQuestion'"></progress>


      <div class="mx-auto py-3 flex flex-wrap w-full justify-center" v-if="viewType == 'showQuestion'">
        <div class="grid grid-rows-2 w-32 grid-flow-col gap-0 rounded bg-blue-300 mx-1" v-for="(user, userIndex) in users">
          <div class="row-span-2 flex justify-center items-center" v-text="user.name">Serge</div>
          <button class="flex flex-wrap justify-center items-center px-3- py-2- rounded-tr
				bg-green-300
				text-3xl text-center text-green-600
				hover:bg-green-600 hover:text-green-300" v-on:click="givePointsToUser( userIndex )">+</button>
          <button class="flex flex-wrap justify-center items-center px-3- py-2- rounded-br
				bg-red-300
				text-3xl text-center text-red-600
				hover:bg-red-600 hover:text-red-300" v-on:click="givePointsToUser( userIndex, true )">-</button>
        </div>

        <button class=" text-indigo-100 bg-indigo-600 px-3 py-2
                                rounded
                                hover:bg-indigo-300 hover:text-indigo-700" v-on:click="setupSelectAnswerView">Continue</button>
      </div>

    </div>

    <div class="  absolute inset-0 z-30" v-if="viewType == 'answerTimeUp'" v-on:click=" viewType == 'answerTimeUp' ? setupShowQuestionView() : setupShowQuestionView()">
      &nbsp;
    </div>


  </div>
  <!-- 	end of focused answer -->

  <div class="flex justify-center items-center mx-auto pt-2 px-4">

    <button class="px-3 py-2 rounded-l flex-1-
                        bg-green-200 text-green-500 border border-green-400
                        hover:bg-green-400 hover:text-green-100" @click.prevent="changeRound(0)">Jeopardy!</button>
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
	<div class="bg-blue-700"
		v-for="user in users">
		<div class="flex flex-wrap justify-center items-center py-2">
			<span class="w-full text-blue-100 text-center px-3" v-text="user.name"></span>
			<span class="btnHeaderShadow pt-2 w-full text-xl text-center text-white" v-text="'$' + user.points"></span>
		</div>
		{{-- <div class="flex flex-wrap justify-between items-center py-2  px-1">
			<svg class=" w-4 text-green-300 fill-current" viewBox="0 0 24 24">
				<path fill="currentColor" d="M17,21L14.25,18L15.41,16.84L17,18.43L20.59,14.84L21.75,16.25M12.8,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19A2,2 0 0,1 21,5V12.8C20.12,12.29 19.09,12 18,12L17,12.08V11H7V13H14.69C13.07,14.07 12,15.91 12,18C12,19.09 12.29,20.12 12.8,21M12,15H7V17H12M17,7H7V9H17" />
			</svg>
			<span class="text-green-100 text-center" v-text="user.correct"></span>

			<svg class="w-4 text-red-300 fill-current" viewBox="0 0 24 24">
				<path fill="currentColor" d="M14.46,15.88L15.88,14.46L18,16.59L20.12,14.46L21.54,15.88L19.41,18L21.54,20.12L20.12,21.54L18,19.41L15.88,21.54L14.46,20.12L16.59,18L14.46,15.88M12,17V15H7V17H12M17,11H7V13H14.69C13.07,14.07 12,15.91 12,18C12,19.09 12.29,20.12 12.8,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19A2,2 0 0,1 21,5V12.8C20.12,12.29 19.09,12 18,12L17,12.08V11M17,9V7H7V9H17Z" />
			</svg>
			<span class="text-center text-red-100" v-text="user.wrong"></span>
		</div> --}}
	</div>

    <div class="max-w-sm mx-auto pt-2" v-if="viewType == 'startUp'">
      <label class=" block w-full text-blue-500 py-2" for="newUser">New User</label>
      <div class="flex justify-center items-center-">
        <input class=" rounded-l border border-blue-500 px-2 py-2" type="text" name="newUser" id="newUser" v-model="newUser.name">
        <button class=" rounded-r flex justify-center items-center p-2
                    border-t border-r border-b border-blue-500 text-blue-500 text-2xl" v-on:click="addNewUser">+</button>
      </div>
    </div>
  </div>

  <div class="px-4 inline py-4 mx-auto">
    <div class="flex flex-wrap justify-center mx-auto select-none">
      <div class=" bg-yellow-600 w-8 mt-10 mr-4 blink relative z-20" v-if="showBuzzerBlinkers"></div>

      <div class="">
        <div class="flex justify-center mx-auto">
          <div v-for="header in columns" class="w-48 h-24 grid grid-columns-6
                                            text-center">
            <div class="btnHeaderShadow flex justify-center items-center
                            bg-blue-700 font-bold text-lg text-white border border-b-4 border-black leading-tight" v-text="header[0].category"></div>
          </div>
        </div>
        <div class="flex justify-center inline mx-auto select-none">

          <div v-for="(column, columnIndex) in columns" class="w-48 grid grid-rows-5 inline
                                text-center">
            {{-- <div v-for="(answer, answerIndex) in column" v-text="answer.value">
                            
                        </div> --}}
            <panel-answer v-for="(answer, answerIndex) in column" v-bind:key="answer.question + answerIndex" v-bind:answer="answer" v-bind:answer-index="answerIndex" v-bind:column-index="columnIndex" v-on:show-answer="setupReadingAnswerView( columnIndex, answerIndex )"></panel-answer>
          </div>
        </div>
      </div>

      <div class=" bg-yellow-600 w-8 mt-10 ml-4 blink relative z-20 shadow-lg" v-if="showBuzzerBlinkers"></div>

    </div>
  </div>
</div>
@endsection

@section( 'javascript' )
<!-- Scripts -->
<script src="{{ asset('js/teams.js') }}" defer></script>
@endsection