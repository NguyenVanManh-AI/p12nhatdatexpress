$(async function () {
    const customerChartCanvas = $("#customer-chart").get(0).getContext("2d");
    const customerChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: false,
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
                    stepSize: 1000,
                    fontColor: "#222222",
                    padding: 20,
                    suggestedMin: 0,
                    suggestedMax: 10000,
                    fontSize: 16,
                },
                gridLines: {
                    display: true,
                    color: "#e2e2e2",
                    drawBorder: false,
                },
                scaleLabel: {
                    display: true,
                    labelString: "Khách hàng",
                    fontStyle: "bold",
                    fontSize: 16,
                },
            }, ],
        },
    };

    // Init customer chart
    let chart_revenue = await initCustomerChart(customerChartCanvas, customerChartOptions);
    // Handle event change data
    $('.customer-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result){
                updateChartCustomer(chart_revenue, {...result})
            }
        })
    })

    $('.customer-week-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result) {
                $('.customer-week-choose').html(result.title)
                $('#customer-week-value').html(result.data)
                $('#customer-week-direction').html(result.percent > 0 ? 'Tăng' : 'Giảm')
                $('#customer-week-percent').html(Math.abs(result.percent) + '%').removeClass('text-red text-green').addClass(result.percent > 0 ? 'text-green' : 'text-red')
            }
        })
    })

});

async function initCustomerChart(canvas, option){
    let customerChartData = {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
        datasets: [{
            label: "Khách hàng",
            fill: false,
            borderWidth: 1,
            lineTension: 0,
            spanGaps: true,
            borderColor: "#f40000",
            pointRadius: 3,
            pointHoverRadius: 7,
            pointColor: "#f40000",
            pointBackgroundColor: "#f40000",
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        }, ],
    };

    let customerChart = new Chart(canvas, {
        type: "line",
        data: customerChartData,
        options: option,
    });


    $.ajax({
        url: $($('.customer-options')[1]).attr('href'),
        success: function (result) {
            updateChartCustomer(customerChart, {...result})
        }
    })

    return customerChart;
}

function updateChartCustomer(chart, {title, labels, data}){
    $('#customer-choose').html(title)
    chart.data.labels = labels
    chart.data.datasets[0].data = data
    chart.config.options.scales.xAxes[0].scaleLabel.labelString = title
    chart.update();
}
