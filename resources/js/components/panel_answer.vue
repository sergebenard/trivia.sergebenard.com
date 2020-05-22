<template>
    <button class="px-2 py-3 flex justify-center items-center
        bg-blue-700 font-bold text-6xl border border-black"
    :disabled="[ answer.answered_correctly !== 0 ]"
    :class="[ answer.answered_correctly !== 0 ? 'cursor-default' : 'text-yellow-400 btnTextShadow' ]">

    <span class="text-4xl" v-text=" answer.answered_correctly === 0 ? '$' : '' ">$</span>
    <span class="leading-none align-text-top" v-text="answer.answered_correctly === 0 ? answer.answer_value : ''">

</span>

    </button>
</template>

<script>
export default {
    data: function () {
        return {
            // answer: null
        }
    },
    props: {
        answer: Object,
    }
}

</script>