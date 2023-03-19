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
import autocompleteList from "./constant/autocomplete_list.mjs"

// Load functions.
import addElement from "./helpers/add_element.mjs"
import removeElement from "./helpers/remove_element.mjs"
import addChoice from "./helpers/add_choise.mjs"
import removeChoice from "./helpers/remove_choice.mjs"
import selectOneOnly from "./helpers/select_one_only.mjs"
import watchChoices from "./helpers/watch_choices.mjs"

document.addEventListener('alpine:init', () => {

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

Alpine.store('app', {
    inputScreenSwitch: 'general',
    sendMethod: 'smtp',
    types: formElementTypes,
    setType: 'text',
    previewMode: 'realtime',
    formData: {
        initialSetting,
        formElements: [],
        formElementSelect: 0,
        dragManage: {
            dragging: null,
            dropping: null,
            timer: null
        },
    },
    previewRendered: '',
    price: 0,
    autocompleteList,
    addElement,
    removeElement,
    addChoice,
    removeChoice,
    selectOneOnly,
    watchChoices,
    elementObserve() {
        this.$watch('formData', (newValue, oldValue) => {
            this.getRenderedPreview()
        })
    },
    async getRenderedPreview() {
        const preview = await (await fetch('/api/preview', {
                header: {
                    'Content-type': 'application/json',
                },
                body: JSON.stringify({
                    formData: this.$store.app.formData
                }),
                method: 'POST',
            })).text()
            // console.log(preview)
        this.$store.app.previewRendered = preview
    },
    async loadSettings() {
        const settings = await (await fetch('../../form_data.json')).json()
        console.log(settings)
        this.$store.app.initialSetting = settings.initialSetting
        this.$store.app.formData.formElements = settings.formElements
    },
    dropElements() {
        if (this.formData.dragManage.dragging !== null && this.formData.dragManage.dropping !== null) {
            if (this.formData.dragManage.dragging < this.formData.dragManage.dropping) {
                this.formData.formElements = [...this.formData.formElements.slice(0, this.formData.dragManage.dragging), ...this.formData.formElements.slice(this.formData.dragManage.dragging + 1, this.formData.dragManage.dropping + 1), this.formData.formElements[this.formData.dragManage.dragging], ...this.formData.formElements.slice(this.formData.dragManage.dropping + 1)];
            } else {
                this.formData.formElements = [...this.formData.formElements.slice(0, this.formData.dragManage.dropping), this.formData.formElements[this.formData.dragManage.dragging], ...this.formData.formElements.slice(this.formData.dragManage.dropping, this.formData.dragManage.dragging), ...this.formData.formElements.slice(this.formData.dragManage.dragging + 1)]
            }
        }
        this.formData.dragManage.dropping = null;
    },
    dragover() {
        this.$event.dataTransfer.dropEffect = "move"
    },
    dragenter(index) {

        if (index !== this.formData.dragManage.dragging) {
            this.formData.dragManage.dropping = index
            console.log('inside condition')
        }
    },
    dragleave(index) {
        if (this.formData.dragManage.dropping === index) {
            this.formData.dragManage.dropping = null
        }
    },
    optionsOldIndex: null,
    optionsNewIndex: null,
    optionsSort() {
        console.log('drop')
        if (this.optionsOldIndex < this.optionsNewIndex) {
            this.formData.formElements[this.formData.formElementSelect - 1].choices = [
                ...this.formData.formElements[this.formData.formElementSelect - 1].choices.slice(0, this.optionsOldIndex),
                ...this.formData.formElements[this.formData.formElementSelect - 1].choices.slice(this.optionsOldIndex + 1, this.optionsNewIndex + 1),
                this.formData.formElements[this.formData.formElementSelect - 1].choices[this.optionsOldIndex],
                ...this.formData.formElements[this.formData.formElementSelect - 1].choices.slice(this.optionsNewIndex + 1)
            ];
        } else {
            this.formData.formElements[this.formData.formElementSelect - 1].choices = [
                ...this.formData.formElements[this.formData.formElementSelect - 1].choices.slice(0, this.optionsNewIndex),
                this.formData.formElements[this.formData.formElementSelect - 1].choices[this.optionsOldIndex],
                ...this.formData.formElements[this.formData.formElementSelect - 1].choices.slice(this.optionsNewIndex, this.optionsOldIndex),
                ...this.formData.formElements[this.formData.formElementSelect - 1].choices.slice(this.optionsOldIndex + 1)
            ]
        }
    },
    calcPrice() {
        let price = 0
        const unitPrice = 500;
        this.$watch('formData.formElements', (newValue, oldValue) => {
            const singleCount = this.formData.formElements.filter(element => {
                return (element.type !== 'select' || element.type !== 'checkbox' || element.type !== 'radio')
            }).length

            const multipleCount = this.formData.formElements.filter(element => {
                return (element.type === 'select' || element.type === 'checkbox' || element.type === 'radio')
            }).map(element => element.choices).map(choices => Math.floor(choices.length / 10) + ((choices.length % 10) ? 1 : 0)).reduce((a, b) => { return a + b }, 0)
            console.log(multipleCount)

            const count = singleCount + multipleCount

            if (count > 0 && count <= 5) {
                this.price = 0
            } else if (count > 5 && count <= 10) {
                this.price = 5000
            } else {
                this.price = unitPrice * count
            }

        })
    }
})

Alpine.store('formView', {
    template: Twig.twig({
        data: 'This is hardcoding {{ test }}',

    }),
    async render() {
        const template = await (await fetch('../../views/page/input.html.twig')).text()
        const view = Twig.twig({ allowInlineIncludes: true, path: '../../views/page/input.html.twig' })
        return view.render({ form: 'form here', csrf_token: 'hogehoge' })
    }
})

Alpine.store('optionsSort', {
    list: () => Alpine.effect(() => Alpine.store('app').formData.formElements[Alpine.store('app').formData.formElementSelect - 1].choices),
    oldIndex: null,
    newIndex: null,

})

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

Alpine.plugin(component)
Alpine.start()