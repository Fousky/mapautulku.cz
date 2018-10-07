$(document).ready(function () {
    var gpsCheck = $('[data-toggle="gps-button"]');
    if (gpsCheck.length) {
        gpsCheck.click(function (event) {
            event.preventDefault();

            var address = $('[data-toggle="gps-address"]');
            var latitude = $('[data-toggle="gps-latitude"]');
            var longitude = $('[data-toggle="gps-longitude"]');

            if (address.length === 0 || latitude.length === 0 || longitude.length === 0) {
                return alert('Ve formuláři chybí jedno z polí: adresa, zem. šířka, zem. délka');
            }

            var addressValue = $.trim(address.val());

            if (addressValue === '') {
                return alert('Chybí adresa.');
            }

            var request = $.ajax({
                url: 'https://maps.google.com/maps/api/geocode/json?address='
                    + encodeURIComponent(addressValue)
                    + '&key='
                    + window.GOOGLE_MAPS_KEY,
                method: 'get',
            });

            request.done(function (response) {
                if (response.status === 'OK') {
                    var geo = response.results[0].geometry.location;
                    latitude.val(geo.lat);
                    longitude.val(geo.lng);
                } else {
                    alert(response.error_message);
                }
            });
            request.fail(function (response) {
                alert('Neočekávaná chyba, zkuste to znovu.');
            });
        });
    }
});
