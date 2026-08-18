var locale = {"format": "DD/MM/YYYY",
    "separator": " - ",
    "applyLabel": lang.Apply,
    "cancelLabel": lang.Cancel,
    "fromLabel": lang.From,
    "toLabel": lang.To,
    "customRangeLabel": lang.Custom,
    "weekLabel": lang.WeekShort,
    "daysOfWeek": [
        lang.daysShort[6],
        lang.daysShort[0],
        lang.daysShort[1],
        lang.daysShort[2],
        lang.daysShort[3],
        lang.daysShort[4],
        lang.daysShort[5]
    ],
    "monthNames": lang.months,
    "firstDay": 1
};
function single_date(idr) {
    if ($(idr + ' #range-single').is(":checked")) {
        $(idr + ' #date-range').attr('id', 'date-range-single');
        $('#date-range-single').daterangepicker({
            "locale": locale,
            "singleDatePicker": true,
            "minDate": $('#date-range-single').data('min'),
        });
    } else {
        $(idr + ' #date-range-single').attr('id', 'date-range');
        $('#date-range').daterangepicker({
            "locale": locale,
            "startDate": $('#date-range-single').data('start'),
            "endDate": $('#date-range-single').data('end'),
            "minDate": $('#date-range-single').data('min'),
        });
    }


}
single_date('#date-range-row');