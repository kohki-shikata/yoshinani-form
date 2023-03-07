import Alpine from "https://cdn.skypack.dev/alpinejs@3.10.5";
import component from "https://unpkg.com/alpinejs-component@latest/dist/component.esm.js"

// Load variables and constants.
import formElementTypes from "./constant/form_element_types.mjs"
import initialSetting from "./constant/initial_setting.mjs"

// Load functions.
import addElement from "./helpers/add_element.mjs"
import removeElement from "./helpers/remove_element.mjs"
import addChoice from "./helpers/add_choise.mjs"
import removeChoice from "./helpers/remove_choice.mjs"
import postData from "./helpers/remove_choice.mjs"

document.addEventListener('alpine:init', () => {
    Alpine.data('alpineCodebehind', () => ({
        types: formElementTypes,
        setType: 'text',
        formData: {
            initialSetting,
            formElements: []
        },
        addElement,
        removeElement,
        addChoice,
        removeChoice,
        postData,

    }))
})
Alpine.plugin(component)
Alpine.start()