# YOSHINANI Form: Japanse style UX oriented contact mail form with PHP

** This project is still under development. **

YOSHINANI Form has various features for common required specification for Japanese web production field.

## Overview

### Single file make good both frontend and backend.

Basically, when we develop a contact form, we have to pay attention two dimentions, frontend side, and backend side. So far, we always markup HTML and develop sending mail application as backend.

YOSHINANI Form requires just single JSON configuration file, the app generates frontend HTML and backend process automatically.

### Simple, but multiple features

As previously stated, YOSHINANI Form keeps it simple, but do not give up accommodate various requirements.

In the general Japanese web production field, there are multiple requirements related with contact forms, rather than global web productions field.

For example, the popular requirement in Japanese form is a confirm screen. Many clients in Japan need a confirm screen before user pushing the submit button. Of course, YOSHINANI Form has a confirm screen by default.

Also, validation is often required. YOSHINANI Form handles form validation both frontend and backend with one single JSON config file. No need hard coding validation settings, regex pattarn twice -- frontend and backend -- any more.

### Flexilibity and extensibility

YOSHINANI Form is developed with PHP and Composer. No use specific framework, but we use some Composer libraries. You can extend and modify as you need.

## Features

### General
| Feature  | Status |
| :------------ | :-------------: |
| Config JSON file to generate form by PHP  | ✅  |
| Generate config file with GUI | ✅ |
| File Uploading | coming soon |
| Variable form field increment, decrement and sortable | coming soon |

### Multiple screens
| Feature  | Status |
| :------------ | :-------------: |
| Input screen  | ✅  |
| Multiple input screen  | coming soon  |
| Confirm screen(optional)  | ✅ [^1] |
| Complete screen(optional)  | ✅ [^1] |

[^1]: Currently, force enabled confirm and complete screen. In future, it will be optional.

### Validation
| Feature  | Status |
| :------------ | :-------------: |
| Frontend validation | ✅ [^2] |
| Frontend realtime validation | coming soon |
| Backend validation | ✅ [^2b] |
| Validation rule - required | ✅ |
| Validation rule - email | ✅ |
| Validation rule - url | ✅ |
| Validation rule - exist email address | coming soon [^3] |
| Validation rule - exist url address | coming soon [^3] |
| Validation rule - min value and max value | ✅ [^4] |
| Validation rule - minlength and maxlength | ✅ |
| Validation rule - regex pattern | ✅ |
| Validation rule - match values between two inputs | coming soon |
| Preset regex pattern - Japanese postal code | coming soon |
| Preset regex pattern - Japanese telephone number | coming soon |
| Preset regex pattern - Katakana, Hiragana, Zenkaku, Hankaku | coming soon |

[^2]: Currently, HTML standard validate function is implemented.
[^2b]: Currently, partialy support.
[^3]: Backend only.
[^4]: input type number only.

### Sending email
| Feature  | Status |
| :------------ | :-------------: |
| Use PHP built-in mail function(not recommended) | coming soon |
| Use SMTP(recommended) | ✅ |
| Selective mail function or SMTP | coming soon |
| Multiple recipient | coming 0.4.0 [^5] |
| Set recipient name | ✅ |
| Set mail title | WIP |
| Auto reply to input user | coming 0.3.0 |
| HTML mail | WIP [^6] |

[^5]: Currently, single user can recieve a message.
[^6]: Function is already implemented, but template is WIP.

### Templating
| Feature  | Status |
| :------------ | :-------------: |
| User editable input screen | WIP [^7] |
| User editable confirm screen | WIP [^7] |
| User editable complete screen | WIP [^7] |
| User editable email template | WIP [^7] |
| User editable autoreply template | WIP [^7] |
| Simple HTML and CSS markup | ✅ |
| Bootstrap 5 | coming soon |

[^7]: Editable template function is already implemented. It will be updated to overridable default template. Always user can back to default template.

### Conditions
| Feature  | Status |
| :------------ | :-------------: |
| Conditional multiple recipient | Under consideration |
| Conditional form fileds | Under consideration |

### Security and internal behabior
| Feature  | Status |
| :------------ | :-------------: |
| CSRF token | ✅ |
| Avoid duplicate submit | WIP |
| Store sensitive data to .env | ✅ |
| Limit upload filesize | coming soon |
| Limit file count | coming soon |
| Periodically remove temporary file [^8] | coming soon |

[^8]: cron is required.

### UX and EFO
| Feature  | Status |
| :------------ | :-------------: |
| Required and optional label | coming soon |
| Step navigation | ✅ [^9] |
| Descriptionn field (optional) | coming soon |
| Assist messages (optional) | coming 0.3.0 |
| Error messages (optional) | coming 0.3.0 |
| Valid symbol (optional) | coming soon |
| Text length counter (optional) | coming soon |
| Text length meter (optional) [^10] | coming soon |
| Progress meter (optional) | Under consideration |
| Auto-complete Japanese address from postal code (optional) | coming soon |
| Auto-complete company info from Japanese corporate number (optional) | Under consideration |
| Prevent submit when unexpected hit enter key | Under consideration |
| Realtime store input contents to localstorage | Under consideration |

[^9]: When multiple screen is enabled only.
[^10]: This maybe good for textarea.

### Integrate other products
| Type  | Status |
| :------------ | :-------------: |
| WordPress plugin  | Under consideration  |
| Send message to Slack  | Under consideration  |
| Send message to Chatwork  | Under consideration  |

## Available Form elements

### HTML standard
| Type  | Status |
| :------------ | :-------------: |
| input type text  | ✅  |
| input type number  | ✅  |
| input type email  | ✅  |
| input type url  | ✅  |
| input type password  | ✅  |
| input type tel  | ✅  |
| input type checkbox  | coming 0.3.0  |
| input type radio  | ✅  |
| select | ✅ [^11]  |
| textarea | ✅  |
| input hidden | ✅  |
| input type datetime-local | coming 0.4.0  |
| input time | coming 0.4.0  |
| input week | coming 0.4.0  |
| input month | coming 0.4.0  |
| input color | coming 0.3.0  |
| input file | coming 0.4.0  |
| input range | coming 0.3.0  |
| fieldset | coming 0.3.0  |

[^11]: Partial support currently. option selected attribute, optgroup, multiple attribute is not implmented yet.

### Customized element
| Type  | Status |
| :------------ | :-------------: |
| Japanese Address Pattern  | coming 0.4.0  |
| Term of use and agreement  | coming 0.4.0  |
| Embed term of use and agreement  | coming 0.4.0  |
| Drag and Drop file uploader  | Under consideration |
| WYSIWYG editor  | Under consideration |


## Why PHP?
In Japanese web production field, still often use shared servers. Almost typically these servers are installed PHP. In Japan, PHP is commonly middleware for web servers.

## Dependencies

### PHP
* Routing - bramus/router
* View - twig/twig
* Enviroment variable - vlucas/phpdotenv
* SMTP - phpmailer/phpmailer
* Validation - vlucas/valitron

### JavaScript
* Two-way binding - Alpine.js

## Installation
WIP

## License
MIT License
