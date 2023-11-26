<div class="bg-base-100 p-4 rounded-box h-full">
    <canvas x-data="{
            chart: null,
            initChart: function () {
                this.chart = new Chart($refs.chart.getContext('2d'), {!! $chart->toJson() !!});
            },
            init() {
                this.initChart();
            },
        }" x-ref="chart">
    </canvas>
</div>
