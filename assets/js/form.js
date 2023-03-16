document.addEventListener('DOMContentLoaded', async() => {
    const spinnerData = await fetch('assets/img/spinner.svg')
    const spinner = await spinnerData.text()
    const forms = document.querySelectorAll('form')
    forms.forEach(form => {
        form.addEventListener('submit', e => {
            // e.preventDefault()
            const submitButton = form.querySelector('[type="submit"]')
            console.log(spinner)
            submitButton.textContent = 'Processing...'
            submitButton.disabled = true
            submitButton.insertAdjacentHTML('afterbegin', spinner)
            submitButton.style.display = 'grid'
            submitButton.style.gridTemplateColumns = '30% 70%'
            submitButton.style.alignItems = 'center'
        })
    })
})