const selectOneOnly = (type, choices, index) => {
    if (type === 'radio' || type === 'select') {
        choices.forEach(choice => choice.selected = false)
        choices[index].selected === true ? false : true
    }
}

export default selectOneOnly