# Zip Code

This module add two auto suggestion :
* for CITY, based on zipcode + country 
* for ZIPCODE, based on city + country 

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ZipCode.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/zip-code-module:~1.0
```

## Usage

To use this module you need to create an account here : http://www.geonames.org/login
then go to ZipCode module configuration and set your geonames username and that's all, 
if you have don't touch to the thelia hooks the module will now suggest zipcode and city
in register and address creation form.

## Hook

```main.javascript-initialization``` is used to add the javascript

```main.stylesheet``` is used to add the stylesheet

```main.after-javascript-include``` is used to add variable for javascript

