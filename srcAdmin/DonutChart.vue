<template>
    <div>
        <svg viewBox="0 0 36 36" class="circular-chart">
            <path
                class="circle"
                :stroke-dasharray="percent+', 100'"
                d="M18 2.0845
                a 15.9155 15.9155 0 0 1 0 31.831
                a 15.9155 15.9155 0 0 1 0 -31.831">
            </path>
        </svg>
    </div>
</template>

<script>
    module.exports = {
        name: "DonutChart",
        props: [
            'percent'
        ],
        data() {
            return {};
        },
        computed: {},
        methods: {},
        beforeMount() {
        }
    };
</script>

<style lang="scss">
    .circular-chart {
        display: block;
        margin: 10px auto;
        max-width: 100px;
        max-height: 100px;
    }
    
    .circle {
        stroke: #4CC790;
        fill: none;
        stroke-width: 2;
        stroke-dasharray: 0 100;
        stroke-linecap: butt;
        animation: progress .3s ease-out forwards;
    }
    
    @keyframes progress {
        0% {
            stroke-dasharray: 0 100;
        }
    }
</style>