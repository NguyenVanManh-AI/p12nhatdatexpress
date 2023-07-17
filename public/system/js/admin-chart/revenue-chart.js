$(async function () {
    // Chart Bar revenueChart
    const revenueChartCanvas = $("#revenue-chart").get(0).getContext("2d");

    const revenueChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: true,
            position: "top",
        },
        scales: {
            xAxes: [{
                ticks: {
                    padding: 20,
                    fontColor: "#222222",
                    fontStyle: "bold",
                    fontSize: 16,
                },
                gridLines: {
                    display: false,
                    color: "#e2e2e2",
                    drawBorder: false,
                },
                scaleLabel: {
                    display: true,
                    labelString: "Tháng",
                    fontStyle: "bold",
                    fontSize: 16,
                },
            }, ],
            yAxes: [{
                ticks: {
                    stepSize: 100,
                    fontColor: "#222222",
                    padding: 20,
                    suggestedMin: 0,
                    suggestedMax: 1000,
                    fontSize: 16,
                },
                gridLines: {
                    display: true,
                    color: "#e2e2e2",
                    drawBorder: false,
                },
                scaleLabel: {
                    display: true,
                    labelString: "Doanh thu (Triệu/tháng)",
                    fontStyle: "bold",
                    fontSize: 16,
                },
            }, ],
        },
    };

    // Init revenue chart
    let chart_revenue = await initRevenueChart(revenueChartCanvas, revenueChartOptions);
    // Handle event change data
    $('.revenue-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result){
                updateChartRevenue(chart_revenue, {...result})
            }
        })
    })


    $('.revenue-coin-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result) {
                $('.revenue-coin-choose').html(result.title)
                $('#revenue-value').html(result.data)
                $('#revenue-direction').html(result.percent > 0 ? 'Tăng' : 'Giảm')
                $('#revenue-percent').html(Math.abs(result.percent) + '%').removeClass('text-red text-green').addClass(result.percent > 0 ? 'text-green' : 'text-red')
            }
        })
    })

    $('.revenue-other-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result) {
                $('.revenue-other-choose').html(result.title)
                $('#revenue-other-value').html(result.data)
                $('#revenue-other-direction').html(result.percent > 0 ? 'Tăng' : 'Giảm')
                $('#revenue-other-percent').html(Math.abs(result.percent) + '%').removeClass('text-red text-green').addClass(result.percent > 0 ? 'text-green' : 'text-red')
            }
        })
    })
})

async function initRevenueChart(canvas, option){
    let revenueChartData = {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
        datasets: [{
            label: "Nạp Coin",
            fill: false,
            backgroundColor: "#5b9bd5",
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        },
        {
            label: "Khác",
            fill: false,
            backgroundColor: "#ed7d31",
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        }],
    };

    const revenueChart = new Chart(canvas, {
        type: "bar",
        data: revenueChartData,
        options: option,
    });


    $.ajax({
        url: $($('.revenue-options')[1]).attr('href'),
        success: function (result) {
            updateChartRevenue(revenueChart, {...result})
        }
    })

    return revenueChart;
}

function updateChartRevenue(chart, {title, labels, data_coin, data_other}){
    $('#revenue-choose').html(title)
    chart.data.labels = labels
    chart.data.datasets[0].data = data_coin
    chart.data.datasets[1].data = data_other
    chart.config.options.scales.xAxes[0].scaleLabel.labelString = title
    chart.update();
}

