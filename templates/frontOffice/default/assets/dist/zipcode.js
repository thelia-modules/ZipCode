"use strict";

var zipcodeInput = document.getElementById("zipcode");

if (null !== zipcodeInput) {
    var geonamesCall = function geonamesCall(search, searchType) {
        clearSuggestion();
        citySuggestion.append(loader.cloneNode(true));
        zipcodeSuggestion.append(loader.cloneNode(true));

        if (null !== countrySelector) {
            countryId = countrySelector.options[countrySelector.selectedIndex].value;
        }

        getIsoAlpha2(countryId).then(function () {
            var baseUrl = 'https://secure.geonames.org/postalCodeSearchJSON?maxRows=5&username=' + geonames_username + '&country=';
            return fetch(baseUrl + countryIsoAlpha2 + "&" + searchType + '=' + search).then(function (response) {
                return response.json();
            }).then(function (response) {
                showResults(response, searchType);
            }).then(function (response) {
                clearLoader();
            });
        });
    };

    var showResults = function showResults(results, searchType) {
        if (searchType === "postalcode") {
            showCities(results);
        } else {
            showZipcodes(results);
        }
    };

    var showCities = function showCities(results) {
        var postalCodeArray = results.postalCodes;
        postalCodeArray.forEach(function (postalcode) {
            var suggestion = createSuggestion('city');
            suggestion.append(postalcode.placeName);
            citySuggestion.append(suggestion);
        });
    };

    var showZipcodes = function showZipcodes(results) {
        var postalCodeArray = results.postalCodes;
        postalCodeArray.forEach(function (postalcode) {
            var suggestion = createSuggestion('zipcode');
            suggestion.append(postalcode.postalCode);
            zipcodeSuggestion.append(suggestion);
        });
    };

    var createSuggestion = function createSuggestion(suggestion) {
        var li = document.createElement("li");
        li.addEventListener('click', function () {
            window[suggestion + 'Input'].value = this.innerHTML;
            window[suggestion + 'Suggestion'].classList.add('hidden');
            window[suggestion + 'Suggestion'].innerHTML = "";
        });
        return li;
    };

    var clearSuggestion = function clearSuggestion() {
        zipcodeSuggestion.classList.add('hidden');
        zipcodeSuggestion.innerHTML = "";

        citySuggestion.classList.add('hidden');
        citySuggestion.innerHTML = "";
    };

    var createCircle = function createCircle(position) {
        var circle = document.createElement("div");
        circle.className = "zipcode-circle zipcode-" + position;
        return circle;
    };

    var clearLoader = function clearLoader() {
        var loaders = document.getElementsByClassName('loader');

        while (loaders.length > 0) {
            loaders[0].remove();
        }
    };

    var getIsoAlpha2 = function getIsoAlpha2(id) {
        return fetch("/zipcode/isoalpha2/" + id, {
            credentials: 'include'
        }).then(function (response) {
            return response.json();
        }).then(function (response) {
            countryIsoAlpha2 = response.isoalpha2;
        }).catch(function (response) {
            countryIsoAlpha2 = 'fr';
        });
    };

    var zipcodeSuggestion = document.createElement("ul");
    zipcodeSuggestion.id = "zipcode_suggestion";
    zipcodeSuggestion.className = "hidden zipcode-suggestion";
    zipcodeInput.parentNode.insertBefore(zipcodeSuggestion, zipcodeInput.nextSibling);
    zipcodeInput.addEventListener('change', function () {
        geonamesCall(zipcodeInput.value, 'postalcode');
    });
    zipcodeInput.addEventListener('focus', function () {
        zipcodeSuggestion.classList.remove("hidden");
    });
    zipcodeInput.addEventListener('blur', function () {
        setTimeout(function () {
            zipcodeSuggestion.classList.add("hidden");
        }, 400);
    });

    var cityInput = document.getElementById("city");
    var citySuggestion = document.createElement("ul");
    citySuggestion.id = "city_suggestion";
    citySuggestion.className = "hidden zipcode-suggestion";
    cityInput.parentNode.insertBefore(citySuggestion, cityInput.nextSibling);
    cityInput.addEventListener('change', function () {
        geonamesCall(cityInput.value, 'placename');
    });
    cityInput.addEventListener('focus', function () {

        citySuggestion.classList.remove("hidden");
    });

    cityInput.addEventListener('blur', function () {
        setTimeout(function () {
            citySuggestion.classList.add("hidden");
        }, 300);
    });

    var countrySelector = document.getElementById("country");
    var countryIsoAlpha2 = 'fr';
    var countryId = '64';

    var loader = document.createElement("li");
    loader.className = "loader";
    loader.append(createCircle('left'));
    loader.append(createCircle('middle'));
    loader.append(createCircle('right'));
}