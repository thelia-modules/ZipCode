let zipcodeInput = document.getElementById("zipcode");

if (null !== zipcodeInput) {
    let zipcodeSuggestion = document.createElement("ul");
    zipcodeSuggestion.id = "zipcode_suggestion";
    zipcodeSuggestion.className = "hidden zipcode-suggestion";
    zipcodeInput.parentNode.insertBefore(zipcodeSuggestion, zipcodeInput.nextSibling);
    zipcodeInput.addEventListener('change', function() {geonamesCall(zipcodeInput.value,'postalcode')});
    zipcodeInput.addEventListener('focus', function()
    {
        zipcodeSuggestion.classList.remove("hidden")
    });
    zipcodeInput.addEventListener('blur', function()
    {
        setTimeout(function() {
            zipcodeSuggestion.classList.add("hidden")
        }, 400)
    });

    let cityInput = document.getElementById("city");
    let citySuggestion = document.createElement("ul");
    citySuggestion.id = "city_suggestion";
    citySuggestion.className = "hidden zipcode-suggestion";
    cityInput.parentNode.insertBefore(citySuggestion, cityInput.nextSibling);
    cityInput.addEventListener('change', function() {geonamesCall(cityInput.value,'placename')});
    cityInput.addEventListener('focus', function()
    {

        citySuggestion.classList.remove("hidden")
    });

    cityInput.addEventListener('blur', function()
    {
        setTimeout(function() {
            citySuggestion.classList.add("hidden")
        }, 300)
    });

    let countrySelector = document.getElementById("country");
    let countryIsoAlpha2= 'fr';
    let countryId= '64';

    let loader = document.createElement("li");
    loader.className = "loader";
    loader.append(createCircle('left'));
    loader.append(createCircle('middle'));
    loader.append(createCircle('right'));

    function geonamesCall(search, searchType) {
        clearSuggestion();
        citySuggestion.append(loader.cloneNode(true));
        zipcodeSuggestion.append(loader.cloneNode(true));

        if (null !== countrySelector) {
            countryId = countrySelector.options[countrySelector.selectedIndex].value
        }

        getIsoAlpha2(countryId)
            .then(function () {
                    let baseUrl = 'https://secure.geonames.org/postalCodeSearchJSON?maxRows=5&username='+geonames_username+'&country=';
                    return fetch(baseUrl+countryIsoAlpha2+"&"+searchType+'='+search)
                        .then(response => {
                            return response.json();
                        }).then(response => {
                            showResults(response, searchType)
                        }).then(response => {
                            clearLoader()
                        });
                }
            )
    }

    function showResults(results, searchType) {
        if (searchType === "postalcode") {
            showCities(results);
        } else {
            showZipcodes(results);
        }
    }

    function showCities(results) {
        let postalCodeArray = results.postalCodes;
        postalCodeArray.forEach(postalcode => {
            let suggestion = createSuggestion('city');
            suggestion.append(postalcode.placeName);
            citySuggestion.append(suggestion);
        })
    }

    function showZipcodes(results) {
        let postalCodeArray = results.postalCodes;
        postalCodeArray.forEach(postalcode => {
            let suggestion = createSuggestion('zipcode');
            suggestion.append(postalcode.postalCode);
            zipcodeSuggestion.append(suggestion);
        })
    }

    function createSuggestion(suggestion) {
        let li = document.createElement("li");
        li.addEventListener('click', function() {
            window[suggestion+'Input'].value = this.innerHTML;
            window[suggestion+'Suggestion'].classList.add('hidden');
            window[suggestion+'Suggestion'].innerHTML = "";
        });
        return li;
    }

    function clearSuggestion() {
        zipcodeSuggestion.classList.add('hidden');
        zipcodeSuggestion.innerHTML = "";

        citySuggestion.classList.add('hidden');
        citySuggestion.innerHTML = "";
    }

    function createCircle(position) {
        let circle = document.createElement("div");
        circle.className = "zipcode-circle zipcode-"+position;
        return circle;
    }

    function clearLoader() {
        let loaders = document.getElementsByClassName('loader');

        while (loaders.length > 0) loaders[0].remove();
    }

    function getIsoAlpha2(id) {
        return fetch("/zipcode/isoalpha2/"+id).then(response => {
            return response.json();
        }).then(function(response) {
            countryIsoAlpha2 = response.isoalpha2;
        }).catch(function (response) {
            countryIsoAlpha2 = 'fr';
        });
    }
}