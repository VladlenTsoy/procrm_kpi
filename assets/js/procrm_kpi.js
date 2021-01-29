$(function () {
    $('#form-filter-kpi').submit(function (e) {
        e.preventDefault()
        const data = $(e.currentTarget).serialize()
        statistics(data)
    })

    statistics({})
})

function statistics(data) {
    $('.output-statistics').find('.text').html('<i class="fa fa-spin fa-refresh"></i>')

    $.ajax({
        url: admin_url + '/procrm_kpi/api',
        method: 'post',
        dataType: 'json',
        data: data,
        success(data) {
            console.log(data)
            $('.tasks-output').html(data.tasks)
            $('.lead-status-output').html(data.leads.status)
            $('.lead-source-output').html(data.leads.source)
            $('.projects-output').html(data.projects)
            $('.clients-output').html(data.clients)
            $('.contracts-output').html(data.contracts)
            // Object.entries(data).map(function ([_key, _value]) {
            //     Object.entries(_value).map(function ([key, value]) {
            //         if (key !== 'total') {
            //             const percent = value === 0 ? 0 : (value / (_value.total / 100)).toFixed(0)
            //             $(`.${_key}-${key}`).find('.text').text(value)
            //             $(`.${_key}-${key}`).find('.percent').text(percent + '%')
            //             $(`.${_key}-${key}`).find('.progress-circle').attr('class', `progress-circle primary p${percent}`)
            //         }
            //     })
            // })

            if(data.calls)
                calls(data.calls)
        }
    })
}

const calls = (data) => {
    $('.calls-output').html(data.view)

    const radialBarOptions = {
        chart: {
            height: 400,
            type: "radialBar",
        },
        stroke: {
            lineCap: "round",
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 5,
                    size: "40%",
                },
                track: {
                    show: false,
                },
                dataLabels: {
                    name: {
                        offsetY: -13,
                        fontSize: "13px",
                        fontWeight: 400,
                    },
                    value: {
                        offsetY: 7,
                        color: "#111",
                        fontSize: "30px",
                        show: true,
                    },
                    total: {
                        show: true,
                        label: 'Всего',
                        color: "#777",
                        fontSize: '16px',
                        fontFamily: undefined,
                        fontWeight: 400,
                        formatter: function () {
                            return data.data.total
                        }
                    }
                }
            }
        },
        labels: data.data.radialBar.labels,
        series: data.data.radialBar.series,
        colors: ['#feb019', '#008ffb', '#00e396', '#ff4560'],
    };

    new ApexCharts(document.querySelector("#calls-radial-bar"), radialBarOptions).render();

    const growthOptions = {
        chart: {
            type: 'bar',
            height: 320,
            stacked: true,
            // stackType: '100%'

        },
        series: [
            {
                name: 'Входящих',
                data: data.data.growth.series[0],
                color: '#feb019'
            }, {
                name: 'Исходнящие',
                data: data.data.growth.series[1],
                color: '#008ffb'
            }, {
                name: 'Отвеченных',
                data: data.data.growth.series[2],
                color: '#00e396'
            }, {
                name: 'Пропущенных',
                data: data.data.growth.series[3],
                color: '#ff4560'
            }
        ],
        plotOptions: {
            bar: {
                horizontal: true,
            },
        },
        stroke: {
            width: 1,
            colors: ['#fff']
        },
        xaxis: {
            categories: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
            axisBorder: {
                show: false,
            },
        },
        tooltip: {
            enabled: false,
            y: {
                formatter: function (val) {
                    return val + "K"
                }
            }
        },
        fill: {
            opacity: 1
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
        }
    };
    new ApexCharts(document.querySelector("#calls-growth-chart"), growthOptions).render();
}