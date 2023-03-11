const selectOneOnly = (choices, index) => {
    choices.forEach(choice => choice.selected = false)
    choices[index].selected === true ? false : true
}

export default selectOneOnly