import Alpine from "https://cdn.skypack.dev/alpinejs@3.10.5";
import component from "https://unpkg.com/alpinejs-component@latest/dist/component.esm.js"
// import ja from "../i18n/ja.json"

// Load multi lang data
const jaData = await fetch('assets/i18n/ja.json')
const ja = await jaData.json()
const enData = await fetch('assets/i18n/en.json')
const en = await enData.json()

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
        inputScreenSwitch: 'general',
        sendMethod: 'smtp',
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

    // i18n
    Alpine.data('lang', () => ({
        langCode: 'en',
        langSet: {
            en: en,
            ja: ja,
        },
        toggle(lang) {
            if (lang === 'en') {
                this.langCode = 'ja'
            } else {
                this.langCode = 'en'
            }
        }
    }))
})
Alpine.plugin(component)
Alpine.start()