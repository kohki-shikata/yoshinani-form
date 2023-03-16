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
    Alpine.store('app', {
        inputScreenSwitch: 'general',
        sendMethod: 'smtp',
        types: formElementTypes,
        setType: 'text',
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
        autocompleteList,
        addElement,
        removeElement,
        addChoice,
        removeChoice,
        selectOneOnly,
        watchChoices,
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
        list: ['osaka', 'kyoto', 'kobe', 'nara', 'wakayama'],
        oldIndex: null,
        newIndex: null,
        dropEffect() {
            this.$event.dataTransfer.dropEffect = 'move'
        },
        sort() {
            if (this.oldIndex < this.newIndex) {
                this.list = [
                    ...this.list.slice(0, this.oldIndex),
                    ...this.list.slice(this.oldIndex + 1, this.newIndex + 1),
                    this.list[this.oldIndex],
                    ...this.list.slice(this.newIndex + 1)
                ];
            } else {
                this.list = [
                    ...this.list.slice(0, this.newIndex),
                    this.list[this.oldIndex],
                    ...this.list.slice(this.newIndex, this.oldIndex),
                    ...this.list.slice(this.oldIndex + 1)
                ]
            }
        },
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