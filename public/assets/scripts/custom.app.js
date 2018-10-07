$(document).ready(function () {
    var regionSelect = $('[data-toggle="region"]');
    var districtSelect = $('[data-toggle="district"]');

    if (regionSelect.length) {
        regionSelect.change(function () {
            configureDistrictElement($(this));
        });

        configureDistrictElement(regionSelect);
    }

    function configureDistrictElement(element) {
        var region = element.val();
        if (region) {
            $('[data-toggle="district"] option').prop('disabled', true);
            $('[data-toggle="district"] option[data-region="'+region+'"]').removeAttr('disabled');
            $('[data-toggle="district"] option[value=""]').removeAttr('disabled');
        } else {
            $('[data-toggle="district"] option').removeAttr('disabled');
        }

        districtSelect.trigger("chosen:updated");
    }
});
