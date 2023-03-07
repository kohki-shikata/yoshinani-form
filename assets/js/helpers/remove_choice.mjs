const removeChoice = (element, i) => {
    if (element.choices) {
        element.choices.splice(i, 1)
    }
}

export default removeChoice