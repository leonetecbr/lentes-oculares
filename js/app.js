let prescriptionSubmit = $('#prescription-submit'), prescription = $('#prescription')
let prescriptionError = $('#prescription-error'), lensError = $('#error-response'), lensOptions = $('#lens-options')

function processPrescription() {
    lensError.addClass('d-none')
    lensOptions.addClass('d-none')
    prescriptionError.addClass('d-none')
    prescriptionSubmit.attr('disabled', true).html('Aguarde ...')
    let data = {
        cilindrico: {
            od: parseFloat($('#cilindrico-od').val()),
            oe: parseFloat($('#cilindrico-oe').val())
        },
        esferico: {
            od: parseFloat($('#esferico-od').val()),
            oe: parseFloat($('#esferico-oe').val())
        }
    }

    $.ajax({
        url: prescription.attr('action'),
        data: JSON.stringify(data),
        dataType: 'json',
        contentType: 'application/json',
        type: 'POST'
    })
        .done(processResponse)
        .fail(() => {
            $(prescriptionError).removeClass('d-none')
        })
        .always(() => {
            initialState()
        })
    console.log(data)
    setTimeout(initialState, 500)
}

function processResponse(data) {
    if (typeof data.success !== 'undefined' && typeof data.lens !== 'undefined' && data.success) {
        lensOptions.removeClass('d-none')
        $('.lens-type').html(data.lens)
    } else if (typeof data.message !== 'undefined') {
        lensError.html(data.message).removeClass('d-none')
    } else {
        lensError.html('Erro desconhecido!').removeClass('d-none')
    }
}

function initialState() {
    prescriptionSubmit.attr('disabled', false).html('Buscar lentes')
    prescription.removeClass('was-validated')
}

$(document).ready(function () {
    'use strict'

    let form = document.querySelector('#prescription')
    form.addEventListener('submit', function (event) {
        let valid = form.checkValidity()
        event.preventDefault()
        event.stopPropagation()

        form.classList.add('was-validated')

        if (valid) processPrescription()
    })
})