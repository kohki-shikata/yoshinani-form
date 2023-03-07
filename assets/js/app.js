import Alpine from "https://cdn.skypack.dev/alpinejs@3.10.5";
import component from "https://unpkg.com/alpinejs-component@latest/dist/component.esm.js"

document.addEventListener('alpine:init', () => {
    Alpine.data('alpineCodebehind', () => ({
        types: [{
                value: 'text',
                text: 'input text',
            },
            {
                value: 'number',
                text: 'input number'
            },
            {
                value: 'email',
                text: 'input email'
            },
            {
                value: 'password',
                text: 'input password'
            },
            {
                value: 'tel',
                text: 'input tel'
            },
            {
                value: 'url',
                text: 'input url'
            },
            {
                value: 'radio',
                text: 'input radio'
            },
            {
                value: 'checkbox',
                text: 'input checkbox'
            },
            {
                value: 'hidden',
                text: 'input hidden'
            },
            {
                value: 'select',
                text: 'select'
            },
            {
                value: 'textarea',
                text: 'textarea'
            },
        ],
        setType: 'text',
        formData: {
            initialSetting: {
                smtpHost: '',
                smtpUsername: '',
                smtpPassword: '',
                smtpPort: '',
                smtpAuth: false,
                smtpEncryption: false,
                recipientAddress: '',
                recipientName: '',
                autoreply: false,
                stepNavigation: false,
            },
            formElements: []
        },
        addElement(type) {
            const inline = /^(text|email|password|tel|url)$/
            const singleSelect = /^radio$/
            const number = /^number$/
            const textarea = /^textarea$/
            const select = /^select$/
            const hidden = /^hidden$/

            if (inline.test(type)) {
                this.formData.formElements.push({
                    type: this.setType,
                    name: '',
                    label: '',
                    id: '',
                    placeholder: '',
                    value: '',
                    minlength: '',
                    maxlength: '',
                    pattern: '',
                    required: false,
                    readonly: false,
                    disabled: false,
                    isInline: true,
                })
            } else if (singleSelect.test(type)) {
                this.formData.formElements.push({
                    type: this.setType,
                    name: '',
                    id: '',
                    title: '',
                    choices: [{
                        label: '',
                        value: '',
                    }],
                    required: false,
                    isMultiple: true,
                })
            } else if (number.test(type)) {
                this.formData.formElements.push({
                    type: this.setType,
                    name: '',
                    label: '',
                    id: '',
                    placeholder: '',
                    value: '',
                    min: '',
                    max: '',
                    pattern: '',
                    required: false,
                    readonly: false,
                    disabled: false,
                    isInline: true,
                })
            } else if (textarea.test(type)) {
                this.formData.formElements.push({
                    type: this.setType,
                    inputmode: '',
                    value: '',
                    minlength: '',
                    maxlength: '',
                    spellcheck: '',
                    wrap: '',
                    required: false,
                    readonly: false,
                    disabled: false,
                    isTextarea: true,
                })
            } else if (select.test(type)) {
                this.formData.formElements.push({
                    type: this.setType,
                    name: '',
                    id: '',
                    label: '',
                    choices: [{
                        text: '',
                        value: '',
                        selected: false,
                    }],
                    required: false,
                    isSelect: true,
                })
            } else if (hidden.test(type)) {
                this.formData.formElements.push({
                    type: this.setType,
                    name: '',
                    id: '',
                    value: '',
                    isHidden: true,
                })
            }

        },
        removeElement(i) {
            this.formData.formElements.splice(i, 1)
        },
        addChoice(element) {
            if (element.type === 'select') {
                element.choices.push({
                    text: '',
                    value: '',
                    selected: false,
                })
            } else {
                element.choices.push({
                    label: '',
                    value: ''
                })
            }
        },
        removeChoice(element, i) {
            element.choices.splice(i, 1)
        },
        postData() {
            fetch('./create_form.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(this.formData),
                })
                // console.log(JSON.stringify(this.formData.formElements))
        },

    }))
})
Alpine.plugin(component)
Alpine.start()