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
import selectOneOnly from "./helpers/select_one_only.mjs";

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
        selectOneOnly
    }))

    Alpine.store('send', {
        response: '',
        async postData(data) {
            const resData = await fetch('./create_form.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            const res = await resData.json()
            console.log(res)
            this.response = res
        }
    })

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