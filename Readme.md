# Zip Code

This module add two auto suggestion :
* for CITY, based on zipcode + country 
* for ZIPCODE, based on city + country 

(country is optionnal, by default the module will search in France)

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ZipCode.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/zip-code-module:~2.0
```

## Usage

To use this module you need to create an account here : http://www.geonames.org/login
then go to ZipCode module configuration and set your geonames username and that's all, 
if you have don't touch to the thelia hooks and inputs id (for zipcode and city) the module will now suggest zipcode and city
in register and address creation form.
Else if you have modify that you just have to puts all hooks describe below and add the ids ```zipcode``` and ```city``` to correponding inputs. 

## Hook

```main.javascript-initialization``` is used to add the javascript

```main.stylesheet``` is used to add the stylesheet

```main.after-javascript-include``` is used to add variable for javascript

