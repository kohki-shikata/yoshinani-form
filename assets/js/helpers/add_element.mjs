const addElement = (type, currentIndex, formData) => {
    const inline = /^(text|email|password|tel|url)$/
    const singleSelect = /^radio$/
    const multiSelect = /^checkbox$/
    const number = /^number$/
    const textarea = /^textarea$/
    const select = /^select$/
    const hidden = /^hidden$/

    if (inline.test(type)) {
        formData.formElements.splice(currentIndex + 1, 0, {
            type,
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
        formData.formElements.push({
            type,
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
        formData.formElements.push({
            type,
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
        formData.formElements.push({
            type,
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
        formData.formElements.push({
            type,
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
        formData.formElements.push({
            type,
            name: '',
            id: '',
            value: '',
            isHidden: true,
        })
    } else if (multiSelect.test(type)) {
        formData.formElements.push({
            type,
            name: '',
            id: '',
            label: '',
            choices: [{
                text: '',
                value: '',
                selected: false,
            }],
            required: false,
            isMultiSelect: true,
        })
    }

}

export default addElement