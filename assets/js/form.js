const update = event => {
    const type = event.target.type
    const validationMessage = event.target.validationMessage;
    const validity = event.target.validity
    console.log(type, validity, validationMessage)
    if (validity.valid === false) {
        if (!event.target.parentNode.querySelector('.error-message')) {
            const wrapper = document.createElement('div')
            wrapper.classList.add('field-wrapper')
            event.target.parentNode.insertAdjacentElement('beforeend', wrapper)
            wrapper.insertAdjacentElement('beforeend', event.target)
            wrapper.insertAdjacentHTML('beforeend', `<p class="error-message">${validationMessage}</p>`)
        }
    } else {
        event.target.nextElementSibling.remove()
    }

}

const checkTotalValid = (form, submitButton) => {
    const totalValid = form.checkValidity();
    if (!totalValid) {
        submitButton.disabled = true
    } else {
        submitButton.disabled = false
    }
    console.log(totalValid)
}

document.addEventListener('DOMContentLoaded', async() => {
    const spinnerData = await fetch('assets/img/spinner.svg')
    const spinner = await spinnerData.text()
    const forms = document.querySelectorAll('form')
    forms.forEach(form => {
        const submitButton = form.querySelector('[type="submit"]')
        checkTotalValid(form, submitButton)
        form.addEventListener('submit', e => {
            // e.preventDefault()
            console.log(spinner)
            submitButton.textContent = 'Processing...'
            submitButton.disabled = true
            submitButton.insertAdjacentHTML('afterbegin', spinner)
            submitButton.style.display = 'grid'
            submitButton.style.gridTemplateColumns = '30% 70%'
            submitButton.style.alignItems = 'center'
        })
        form.addEventListener('change', update)
        form.addEventListener('input', update)
        form.addEventListener('change', () => checkTotalValid(form, submitButton))
        form.addEventListener('input', () => checkTotalValid(form, submitButton))
    })
})