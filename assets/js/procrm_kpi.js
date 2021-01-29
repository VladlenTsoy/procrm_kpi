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
            if (data.calls)
                calls(data.calls)

            if (data.tasks)
                tasks(data.tasks)

            if (data.projects)
                projects(data.projects)

            if (data.leads)
                leads(data.leads)

            if (data.contracts)
                contracts(data.leads)
        }
    })
}

const contracts = (data) => {
    const options = {
        series: [44, 55, 41, 17],
        labels: ["Активность", "Истек срок действия", "Истекает срок действия", "Недавно добавленные"],
        chart: {
            type: 'donut',
        },
        dataLabels: {
            dropShadow: {
                enabled: false,
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '40%'
                },
            },
        },
        legend: {
            position: 'bottom',
        }
    };

    new ApexCharts(document.querySelector("#contracts-block"), options).render();
}

const leads = (data) => {
    const optionsStatuses = {
        series: [44, 55, 41, 17, 15, 17, 15],
        labels: ["Facebook", "Google", "olx", "почта", "С телефоного справончика", "сайт", "Телеграм"],
        chart: {
            type: 'donut',
        },
        dataLabels: {
            dropShadow: {
                enabled: false,
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '40%'
                },
            },
        },
        legend: {
            position: 'bottom',
        }
    };

    new ApexCharts(document.querySelector("#leads-statuses-block"), optionsStatuses).render();

    const optionsSources = {
        series: [44, 55, 41, 17, 15, 17, 15],
        labels: ["Новый", "Выслано предложение", "В работе", "Нужно позвонить", "Потерянный", "Нужен выезд", "Не обработан"],
        chart: {
            type: 'donut',
        },
        dataLabels: {
            dropShadow: {
                enabled: false,
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '40%'
                },
            },
        },
        legend: {
            position: 'bottom',
        }
    };

    new ApexCharts(document.querySelector("#leads-sources-block"), optionsSources).render();
}

const projects = (data) => {
    const options = {
        series: [{
            name: 'Задачи',
            data: [21, 22, 10, 28, 16]
        }],
        chart: {
            height: 250,
            type: 'bar',
        },
        plotOptions: {
            column: {
                borderRadiusTopLeft: 10,
                borderRadiusTopRight: 10
            },
            bar: {
                columnWidth: '90%',
                distributed: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: [
                'Не начат',
                'Завершено',
                'В ожидании',
                'В процессе',
                'Отменено',
            ],
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        }
    };

    new ApexCharts(document.querySelector("#projects-block"), options).render();
}

const tasks = (data) => {
    const options = {
        series: [{
            name: 'Задачи',
            data: [21, 22, 10, 28, 16]
        }],
        chart: {
            height: 250,
            type: 'bar',
        },
        plotOptions: {
            column: {
                borderRadiusTopLeft: 10,
                borderRadiusTopRight: 10
            },
            bar: {
                columnWidth: '90%',
                distributed: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: [
                'Не начата',
                'В процессе',
                'На проверке',
                'В ожидании',
                'Завершена',
            ],
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        }
    };

    new ApexCharts(document.querySelector("#tasks-block"), options).render();
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
            lineCap: "round",
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