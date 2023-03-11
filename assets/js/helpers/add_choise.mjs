const addChoice = (choices, index) => {
    choices.splice(index + 1, 0, {
        text: '',
        value: '',
        selected: false,
    })
}

export default addChoice