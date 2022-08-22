var todayPropertyNew = new Date();
var currentYearPropertyNew = todayPropertyNew.getFullYear();
var currentMonthPropertyNew = todayPropertyNew.getMonth() + 1;
var currentDayNum = parseInt(todayPropertyNew.getDate());
var monthStoragePropertyNew = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

$("#calendarDateToday").val(todayPropertyNew.getDate() + " " + monthStoragePropertyNew[currentMonthPropertyNew - 1] + " " + currentYearPropertyNew);
var countWeekDayName = todayPropertyNew.getDay();
var datesStorage = [];
var propsStorage = $(".list-group-item").map(function() {
    return $(this).data('property_id');
}).get();
var propId = $('.properies-select :selected').val();

$(document).ready(function () {
    $('.wrapper').click(function(e) {
        if ( $('body').hasClass('control-sidebar-slide-open') && !$(e.target).closest(".control-sidebar").length
            && !$(e.target).hasClass('date-empty-wrapper') && !$(e.target).hasClass('date-wrapper-new') ) {
            closeSidebar()
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
    // Calendar appearance functionality
    if ($('.calendar-full').length > 0) {
        initCalendar(true);
        // Datepicker for choosing date in calendar
        $('#calendarDateToday').datepicker({
            endDate: "+10Y", format: "dd MM yyyy", autoclose: true
        }).on('changeDate', function(e) {
            $('.loader').show();
            dateText = e.format();
            let currentDateInfo = dateText.split(" ");
            currentDayNum = parseInt(currentDateInfo[0]);
            currentMonthPropertyNew = parseInt(monthStoragePropertyNew.indexOf(currentDateInfo[1])) + 1;
            currentYearPropertyNew = currentDateInfo[2];
            datesStorage = [];
            initCalendar(true);
        });
        $("#nextPeriod").on('click', function () {
            $('.loader').show();
            let daysInCurrentMonth = new Date(currentYearPropertyNew, currentMonthPropertyNew, 0).getDate();

            for (let x = 0; x < 14; x++) {
                if (currentDayNum <= daysInCurrentMonth) {

                } else {
                    currentDayNum = 1;
                    currentMonthPropertyNew++;
                    if (currentMonthPropertyNew > 12) {
                        currentMonthPropertyNew = 1;
                        currentYearPropertyNew++;
                    }
                }
                let currentMonthForCheck = currentMonthPropertyNew;
                if (currentMonthForCheck < 10) {
                    currentMonthForCheck = "0" + currentMonthForCheck;
                }
                let currentDayNumForCheck = currentDayNum;
                if (currentDayNumForCheck < 10) {
                    currentDayNumForCheck = "0" + currentDayNumForCheck;
                }
                datesStorage.push(currentYearPropertyNew + "-" + currentMonthForCheck + "-" + currentDayNumForCheck);
                currentDayNum++;
            }
            $("#calendarDateToday").val(currentDayNum + " " + monthStoragePropertyNew[currentMonthPropertyNew - 1] + " " + currentYearPropertyNew);
            initCalendar(true);
        });
        $("#prevPeriod").on('click', function () {
            $('.loader').show();
            let daysInCurrentMonth = new Date(currentYearPropertyNew, currentMonthPropertyNew, 0).getDate();

            for(let x = 0; x < 28; x++){
                if(currentDayNum >= 1){
                    currentDayNum--;
                }else{
                    currentMonthPropertyNew--;
                    if(currentMonthPropertyNew < 1){
                        currentYearPropertyNew--;
                        currentMonthPropertyNew = 12;
                        daysInCurrentMonth = new Date(currentYearPropertyNew, currentMonthPropertyNew, 0).getDate();
                    }else{
                        daysInCurrentMonth = new Date(currentYearPropertyNew, currentMonthPropertyNew, 0).getDate();
                    }
                    currentDayNum = daysInCurrentMonth;
                    currentDayNum--;
                }
            }

            for (let x = 0; x < 14; x++) {
                if (currentDayNum <= daysInCurrentMonth) {

                } else {
                    currentDayNum = 1;
                    currentMonthPropertyNew++;
                    if (currentMonthPropertyNew > 12) {
                        currentMonthPropertyNew = 1;
                        currentYearPropertyNew++;
                    }
                }
                let currentMonthForCheck = currentMonthPropertyNew;
                if (currentMonthForCheck < 10) {
                    currentMonthForCheck = "0" + currentMonthForCheck;
                }
                let currentDayNumForCheck = currentDayNum;
                if (currentDayNumForCheck < 10) {
                    currentDayNumForCheck = "0" + currentDayNumForCheck;
                }
                datesStorage.push(currentYearPropertyNew + "-" + currentMonthForCheck + "-" + currentDayNumForCheck);
                currentDayNum++;
            }
            $("#calendarDateToday").val(currentDayNum + " " + monthStoragePropertyNew[currentMonthPropertyNew - 1] + " " + currentYearPropertyNew);
            initCalendar(true);
        });
    }

    if ($('.calendar-property').length > 0) {
        $("#nextPeriod").on('click', function () {
            $('.loader').show();
            initCalendar(false,'next');
        });
        $("#prevPeriod").on('click', function () {
            $('.loader').show();
            initCalendar(false,'prev');
        });
    }
});
function closeSidebar() {
    $('.control-sidebar').ControlSidebar('toggle');
    $('.sidebar-mini').toggleClass('sidebar-collapse');
}
function initCalendar(full,dir=null) {
    if (full === true) {
        var startDate = $('#calendarDateToday').val();
        $.post('/admin/calendar-info-full', {
            propIds: propsStorage,
            startDate: startDate
        }, function (datajson) {
            $('#propCalendars').html(datajson.view);
            $('.loader').hide();
            $('[data-toggle="tooltip"]').tooltip();
        });
    } else {
        var startDate = $('#calendarMonthToday').val();
        $.post('/admin/calendar-info', {
            id: propId,
            startDate: startDate,
            dir: dir
        }, function (datajson) {
            $('#propCalendar').html(datajson.view);
            $('.loader').hide();
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
}
function openCalendarForm(el) {
    var property = $(el).data('property'),
        currentdate = $(el).data('currdate'),
        modal =  '_update-reservation',
        model_id =  $(el).data('reservation');
    if ($(el).hasClass('date-empty-wrapper') || $(el).hasClass('half-out')) {
        modal =  '_add-reservation';
    } else if ($(el).hasClass('date-remove')) {
        modal =  '_remove-blocks';
    }
    $.post('/admin/calendar/open-modal', {
        property: property,
        currentdate: currentdate,
        modal: modal,
        model_id: model_id,
    }, function (datajson) {
        $('.calendar-container').html(datajson.view);
        $('.sidebar-mini').toggleClass('sidebar-collapse');
        $('.control-sidebar').ControlSidebar('toggle');
        // $('.modal-container').find('.modal').first().modal('show');
    });
}
