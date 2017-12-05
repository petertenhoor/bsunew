<?php

/**
 * Template for shortcode Zipcode Checker
 */

?>

<div class="postcodeChecker col span_12">
    <p><strong>Beschikbaarheidscheck voor supersnel internet.</strong></p>
    <p>Glasvezel powered by KPN</p>
    <form id="postcodechecker">
        <label for="postcode">Postcode*</label>
        <input type="text" id="postcode" name="postcode" placeholder="1234AB">
        <label for="huisnummer">Huisnummer*</label>
        <input type="number" id="huisnummer" name="huisnummer">
        <label for="huisnummer">Toevoeging</label>
        <input type="text" id="toevoeging" name="toevoeging">
        <input type="submit" value="Check nu!" class="button">
        <div class="postcodeCheckerLoader"><i class="fa fa-spinner icon-spin"></i></div>
        <div class="errormsg"><i class="fa fa-exclamation-circle"></i><span class="errortext"></span></div>
        <div class="postcodeCheckerResultContainer">
            <hr>
            <h5>Postcode-check</h5>
            <p>Uw postcode kan gebruik maken van het volgende pakket:</p>
            <strong><p class="postcodeCheckerResult"></p></strong>
            <a id="checkurl" href="#">Ga naar formulier</a>
        </div>
    </form>
</div>

<script>
    (function ($) {
        $(document).ready(function () {
            //on submit get values
            $('#postcodechecker').submit(function (f) {
                var zipCode      = $('#postcode').val();
                zipCode          = zipCode.toUpperCase();
                zipCode          = zipCode.replace(" ", "");
                zipCode          = validateZipCode(zipCode);
                var streetNumber = $('#huisnummer').val();
                var streetNumberAddition = $('#toevoeging').val();
                //validate if fields are not empty
                if (zipCode !== '' && streetNumber !== '') {
                    //validate if zipcode is valid
                    if (zipCode !== false) {
                        $('.errormsg').hide();
                        $('.postcodeCheckerResultContainer').hide();
                        $('.postcodeCheckerLoader').show();
                        //create request url
                        var url = 'https://ws.deal-it.com/postcodecheckzg/v2/?postcode=' + zipCode + '&huisnummer=' + streetNumber + '&toevoeging=' + streetNumberAddition + '&responseformat=json';
                        // Get data
                        getText(url, function (e) {
                            e = JSON.parse(e);
                            if (e.Response !== undefined) {
                                var result = e.Response.result.typeglasgebiednaam;
                                $('.postcodeCheckerResultContainer').show();
                                $('.postcodeCheckerResult').text(result);
                                $('.postcodeCheckerLoader').hide();
                                var checkurl = '<?php get_template_directory_uri(); ?>' + '/postcode-check?postcode=' + zipCode + '&huisnummer=' + streetNumber + '&toevoeging=' + streetNumberAddition + '&result=' + result;
                                $('#checkurl').attr('href', checkurl);
                            }
                            //show error message when API returns error
                            else {
                                $('.errormsg .errortext').text('De ingevoerde combinatie van postcode en huisnummer is bij ons niet bekend.');
                                $('.postcodeCheckerResultContainer').hide();
                                $('.postcodeCheckerLoader').hide();
                                $('.errormsg').show();
                            }
                        });
                    }
                    //show error message when zipcode is invalid
                    else {
                        $('.errormsg .errortext').text('De ingevoerde postcode lijkt ongeldig te zijn.');
                        $('.postcodeCheckerResultContainer').hide();
                        $('.errormsg').show();
                    }
                }
                //show error message when fields are empty
                else {
                    $('.errormsg .errortext').text('Niet alle velden zijn ingevoerd.');
                    $('.postcodeCheckerResultContainer').hide();
                    $('.errormsg').show();
                }
                //don't execute form action
                f.preventDefault();
            });
        });

        /**
         * Does a XMLHTTP request with given URL
         * @param url
         * @param callback
         */
        function getText(url, callback) {
            var request                = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    callback(request.responseText);
                }
            };
            request.open('GET', url);
            request.send();
        }

        /**
         * Validate Dutch zipcode
         * @param zipcode
         * @returns {*}
         */
        function validateZipCode(zipcode) {

            var regex = /^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i;

            if (regex.test(zipcode)) {
                return zipcode;
            } else {
                return false;
            }
        }

    })(jQuery);
</script>