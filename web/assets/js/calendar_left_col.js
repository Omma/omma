$(window).load(function()
        {
            $('input#calendar-left-col').glDatePicker(
{
    showAlways: true,
    selectedDate: new Date(2013, 0, 5),
    specialDates: [
        {
            date: new Date(2013, 0, 13),
            data: { message: 'Meeting every day 8 of the month' },
            repeatMonth: true
        },
        {
            date: new Date(0, 0, 1),
            data: { message: 'Happy New Year!' },
            repeatYear: true
        },
    ],
    onClick: function(target, cell, date, data) {
        target.val(date.getFullYear() + ' - ' +
                    date.getMonth() + ' - ' +
                    date.getDate());

        if(data != null) {
            //alert(data.message + '\n' + date);
        }
    }
});
        });