const watchChoices = () => {
    this.$watch('formData', (newValue, oldValue) => {
        // if (newValue.formElements[formData.formElementSelect - 1].type.match(/(select|checkbox|radio)/) && newValue.formElements[formData.formElementSelect - 1].choices === undefined) {
        //     newValue.formElements[formData.formElementSelect - 1].choices = [{ text: '', value: '', selected: false }]
        // console.log('hoge')
        // }
        console.log('hage')
    })
}

export default watchChoices