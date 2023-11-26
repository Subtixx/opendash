<canvas width="400" height="400" x-data="{
            chart: null,
            initChart: function () {
                this.chart = new Chart($refs.chart.getContext('2d'),
                    {
                        type: 'line',
                        data: {
                            labels: [
                                'Red',
                                'Blue',
                                'Yellow',
                                'Green',
                                'Purple',
                                'Orange',
                                'Red',
                                'Blue',
                                'Yellow',
                                'Green'],
                            datasets: [{
                                label: 'Hello',
                                data: [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {}
                    });
            },
            init() {
                this.initChart();
            },
        }" x-ref="chart">
</canvas>
