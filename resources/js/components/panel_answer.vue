<template>
<button class=" w-48 h-24 flex justify-center items-center
    bg-blue-700 font-bold text-5xl border border-black"
    @click="clicked"
    :class="[ answer.answered_correctly !== 0 ? 'cursor-default' : 'text-yellow-400 btnTextShadow' ]">
    <span class="text-4xl" v-text=" answer.answered_correctly === 0 ? '$' : '' ">$</span>
    <span class="leading-none align-text-top" v-text="answer.answered_correctly === 0 ? answer.answer_value : ''"></span>

</button>
</template>

<script>
export default {
    props: {
        answer: Object,
        columnIndex: Number,
        answerIndex: Number,
    },
    methods: {
        clicked: function() {
            console.info( 'Clicked on button Col: ' + this.columnIndex + '; Row: ' + this.answerIndex )
            if( !this.answer.answered_correctly ) {
                this.$emit( 'show-answer', this.columnIndex, this.answerIndex );
            }
        }
    }
}

</script>