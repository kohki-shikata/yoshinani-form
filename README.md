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

* All configuration is set up by one single JSON file.
* Automatically generated frontend and backend by JSON file.
* Multiple screens
  * Input - default
  * Confirm - optional
  * Complete - optional
* Various validation patterns - frontend and backend
  * Required field
  * Email field, valid email address or not.
  * Regex pattern and presets
    * Japanese telephone number regex
    * Japanese postal code regex
    * Hiragana and Katakana regex
    * alphanumeric regex
  * minLength and maxLength
  * min value and max value for number field
  * compare two field and match both values
* Various common required form field
  * Japanese 47 prefecture select
  * Japanese 47 prefectures select, grouped by 8 regions as optgroup.
* UX enhancement
  * Required label and optional label
  * Assist messages - optional
  * Error messages - optional
  * Text length counter - optional
  * Text length meter - optional
  * Auto-complete address from Japanese postal code - optional
  * Step navigation - optional
  * Embed term of use - optional
  * Avoid duplicate submit
  * Drag and drop File uploader

## Why PHP?
In Japanese web production field, still often use shared servers. Almost typically these servers are installed PHP. In Japan, PHP is commonly middleware for web servers.

## Dependancies

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