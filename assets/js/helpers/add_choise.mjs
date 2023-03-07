const addChoice = element => {
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
}

export default addChoice