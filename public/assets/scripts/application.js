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

            var actualDistrict = $('[data-toggle="district"] option:selected');
            if (actualDistrict) {
                var actualRegion = actualDistrict.data('region');
                if (typeof actualRegion !== 'undefined' && actualRegion !== region) {
                    $('[data-toggle="district"]').val('');
                }
            }
        } else {
            $('[data-toggle="district"] option').removeAttr('disabled');
        }

        districtSelect.trigger("chosen:updated");
    }
});
