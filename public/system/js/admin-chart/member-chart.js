jQuery(document).ready(async function() {
    // Variable
    const memberGraphChartCanvas = $("#line-chart").get(0).getContext("2d");
    let memberGraphChartOptions = {
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
                    labelString: "Thành viên",
                    fontStyle: "bold",
                    fontSize: 16,
                },
            }, ],
        },
    };

    // Init member chart
    let chart = await initMemberChart(memberGraphChartCanvas, memberGraphChartOptions);

    // Handle event change data
    $('.member-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result){
                updateChart(chart, {...result})
            }
        })

    })
});

async function initMemberChart(canvas, option){
    let memberGraphChartData = {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
        datasets: [{
            label: "Thành viên",
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

    let memberGraphChart = new Chart(canvas, {
        type: "line",
        data: memberGraphChartData,
        options: option,
    });

    $.ajax({
        url: $($('.member-options')[1]).attr('href'),
        success: function (result) {
            updateChart(memberGraphChart, {...result})
        }
    })

    return memberGraphChart;
}

function updateChart(chart, {title, labels, data}){
    $('#member-choose').html(title)
    chart.data.labels = labels
    chart.data.datasets[0].data = data
    chart.config.options.scales.xAxes[0].scaleLabel.labelString = title
    chart.update();
}
