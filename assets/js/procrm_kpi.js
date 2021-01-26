$(function () {
    $('#form-filter-kpi').submit(function (e) {
        e.preventDefault()
        const data = $(e.currentTarget).serialize()
        calls(data)
    })

    calls({})
})

function calls(data) {
    $('.output-statistics').find('.text').html('<i class="fa fa-spin fa-refresh"></i>')

    $.ajax({
        url: admin_url + '/procrm_kpi/api',
        method: 'post',
        dataType: 'json',
        data: data,
        success(data) {
            console.log(data)
            $('.calls-output').html(data.calls)
            $('.tasks-output').html(data.tasks)
            $('.lead-status-output').html(data.leads.status)
            $('.lead-source-output').html(data.leads.source)
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
        }
    })
}