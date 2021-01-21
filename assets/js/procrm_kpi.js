$(function () {
    $('#form-filter-kpi').submit(function (e) {
        e.preventDefault()
        const data = $(e.currentTarget).serialize()
        calls(data)
    })
    
    calls({})
})

function calls(data) {
    $('.calls-missed, .calls-answered, .calls-outgoing, .calls-incoming').find('.text').html('<i class="fa fa-spin fa-refresh"></i>')

    $.ajax({
        url: admin_url + '/procrm_kpi/api',
        method: 'post',
        dataType: 'json',
        data: data,
        success(data) {
            // $('.calls-total').find('.text').text(data.calls.total)

            const percentMissed = data.calls.missed === 0 ? 0 : (data.calls.missed / (data.calls.total / 100)).toFixed(0)
            $('.calls-missed').find('.text').text(data.calls.missed)
            $('.calls-missed').find('.percent').text(percentMissed + '%')
            $('.calls-missed').find('.progress-circle').attr('class', `progress-circle danger p${percentMissed}`)

            const percentAnswered = data.calls.answered === 0 ? 0 : (data.calls.answered / (data.calls.total / 100)).toFixed(0)
            $('.calls-answered').find('.text').text(data.calls.answered)
            $('.calls-answered').find('.percent').text(percentAnswered + '%')
            $('.calls-answered').find('.progress-circle').attr('class', `progress-circle success p${percentAnswered}`)

            const percentOutgoing = data.calls.outgoing === 0 ? 0 : (data.calls.outgoing / (data.calls.total / 100)).toFixed(0)
            $('.calls-outgoing').find('.text').text(data.calls.outgoing)
            $('.calls-outgoing').find('.percent').text(percentOutgoing + '%')
            $('.calls-outgoing').find('.progress-circle').attr('class', `progress-circle primary p${percentOutgoing}`)

            const percentIncoming = data.calls.incoming === 0 ? 0 : (data.calls.incoming / (data.calls.total / 100)).toFixed(0)
            $('.calls-incoming').find('.text').text(data.calls.incoming)
            $('.calls-incoming').find('.percent').text(percentIncoming + '%')
            $('.calls-incoming').find('.progress-circle').attr('class', `progress-circle primary p${percentIncoming}`)
        }
    })
}