$(async function () {
    // website line chart
    const classifiedChartCanvas = $("#website-chart").get(0).getContext("2d");

    const classifiedChartOptions = {
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
                    labelString: "Tin rao",
                    fontStyle: "bold",
                    fontSize: 16,
                },
            }, ],
        },
    };

    // Init classified chart
    let classified_chart = await initClassifiedChart(classifiedChartCanvas, classifiedChartOptions);

    // Handle event change data
    $('.classified-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result){
                updateClassifiedChart(classified_chart, {...result})
            }
        })
    })

    $(".slick").slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        responsive: [
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                    nav: false,
                }
            },
            {
                breakpoint: 0,
                settings: {
                    slidesToShow: 1,
                    nav: false,
                }
            }
        ],
        prevArrow: '<div class="nav-left"><i class="fas fa-chevron-left"></i></div>',
        nextArrow: '<div class="nav-right"><i class="fas fa-chevron-right"></i></div>',
    });

    // Handle change option small box
    $('.classified-total-month-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result) {
                $('.classified-total-month-choose').html(result.title)
                $('#classified-total-month-value').html(result.data)
                $('#classified-total-month-direction').html(result.percent > 0 ? 'Tăng' : 'Giảm')
                $('#classified-total-month-percent').html(Math.abs(result.percent) + '%').removeClass('text-red text-green').addClass(result.percent > 0 ? 'text-green' : 'text-red')
            }
        })
    })
    $('.classified-sell-month-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result) {
                $('.classified-sell-month-choose').html(result.title)
                $('#classified-sell-month-value').html(result.data)
                $('#classified-sell-month-direction').html(result.percent > 0 ? 'Tăng' : 'Giảm')
                $('#classified-sell-month-percent').html(Math.abs(result.percent) + '%').removeClass('text-red text-green').addClass(result.percent > 0 ? 'text-green' : 'text-red')
            }
        })
    })
    $('.classified-rent-month-options').click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $.ajax({
            url,
            success: function (result) {
                $('.classified-rent-month-choose').html(result.title)
                $('#classified-rent-month-value').html(result.data)
                $('#classified-rent-month-direction').html(result.percent > 0 ? 'Tăng' : 'Giảm')
                $('#classified-rent-month-percent').html(Math.abs(result.percent) + '%').removeClass('text-red text-green').addClass(result.percent > 0 ? 'text-green' : 'text-red')
            }
        })
    })

})

async function initClassifiedChart(canvas, option){
    let classifiedChartData = {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
        datasets: [{
            label: "Tin rao",
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

    let classifiedGraphChart = new Chart(canvas, {
        type: "line",
        data: classifiedChartData,
        options: option,
    });

    $.ajax({
        url: $($('.classified-options')[1]).attr('href'),
        success: function (result) {
            updateChart(classifiedGraphChart, {...result})
        }
    })

    return classifiedGraphChart;
}

function updateClassifiedChart(chart, {title, labels, data}){
    $('#classified-choose').html(title)
    chart.data.labels = labels
    chart.data.datasets[0].data = data
    chart.config.options.scales.xAxes[0].scaleLabel.labelString = title
    chart.update();
}
