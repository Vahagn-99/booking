$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  // $('#property-images-slider .gallery-thumbs .swiper-slide iframe').height($('#property-images-slider .gallery-thumbs .swiper-slide img').height());
  // $(window).resize(function(){
  //     $('#property-images-slider .gallery-thumbs .swiper-slide iframe').height($('#property-images-slider .gallery-thumbs .swiper-slide img').height());
  // });
  // Check scroll
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
    $("#home-menu").removeClass("navbar-dark bg-transparent").addClass("navbar-light bg-white fixed-top");
    $("header").removeClass("fixed-top");
  } else {
    $(window).scroll(function () {
      if ($(window).scrollTop() > 20) {
        $("#home-menu").removeClass("navbar-dark bg-transparent").addClass("navbar-light bg-white");
      } else {
        $("#home-menu").removeClass("navbar-light bg-white").addClass("navbar-dark bg-transparent");
      }
    });
  }

  $('.modal-body').find('.is-invalid').each(function (i, el) {
    if (i == 0) {
      var modal = $(el).closest('.modal-body');
      $('#' + modal.attr('id')).modal('show');
    }
  });
  $('.tab-pane').find('.is-invalid').each(function (i, el) {
    if (i == 0) {
      var tab = $(el).closest('.tab-pane');
      $('.nav-tabs a[href="#' + tab.attr('id') + '"]').tab('show');
    }
  });

  // On change latitude manually
  $("#place_lat").on('input', function () {
    let currentLat = $(this).val();
    initMap(parseFloat(currentLat), parseFloat($("#place_lng").val()));
  });
  // On change longitude manually
  $("#place_lng").on('input', function () {
    let currentLng = $(this).val();
    initMap(parseFloat($("#place_lat").val()), parseFloat(currentLng));
  });
  // Set up filters on locations page
  $("#filter-locations").on('change', 'input, select', function () {
    let locationFiltersData = [];
    $("#filter-locations input, #filter-locations select").each(function () {
      locationFiltersData.push($(this).val());
    });
    MakeUrl(locationFiltersData);
  });
  var page = 1, lastScrollTop = 0;
  $('.filtered-properties').scroll(function () {
    var st = $(this).scrollTop();
    if (st > lastScrollTop && ($(this).scrollTop() + $(this).height() >= $(document).height() - 250)) {
      page++;
      loadProperties(page);
    }
    lastScrollTop = st;
  });
  $('.search-form').keydown(function (event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
  $('.btn-search').click(function () {
    var form = $(this).closest('form');
    form.find('.bg-white').removeClass('invalid');
    if ($('input[name=city]').val() == '' && $('input[name=city]').hasClass('required')) {
      $('input[name=city]').closest('.bg-white').addClass('invalid')
    }
    if ($('input[name=checkin]').val() != '' && $('input[name=checkin]').val() != '' && ($('input[name=guests]').val() == undefined || $('input[name=bedrooms]').val() == undefined)) {
      form.find('.required').each(function () {
        if ($(this).val() == '' || $(this).val() == undefined) {
          $(this).closest('.bg-white').addClass('invalid')
        }
      })
    }
    if ($('.bg-white.invalid').length == 0) {
      if ($(this).hasClass('filter')) {
        loadProperties(1);
      } else {
        form.submit()
      }
    }
  })

  var url = location.href.replace(/\/$/, "");
  if (location.hash) {
    const hash = url.split("#");
    $('.nav-tabs a[href="#' + hash[1] + '"]').tab("show");
    $('input[name=hash]').each(function () {
      $(this).val('#' + hash[1]);
    });
    url = location.href.replace(/\/#/, "#");
    history.replaceState(null, null, url);
    setTimeout(() => {
      $(window).scrollTop(0);
    }, 100);
  }
  $('.nav-tabs a').on('shown.bs.tab', function (event) {
    var tab = $(event.target).attr('href');
    $('input[name=hash]').each(function () {
      $(this).val(tab);
    })
  });

  $('.open-modal').click(function (e) {
    e.preventDefault();
    var property = $(this).data('property'),
      modal = $(this).data('modal'),
      model_name = $(this).data('model_name'),
      model_id = $(this).data('model_id'),
      bed_id = $(this).data('bed_id'),
      title = $(this).data('title'),
      hash = $('input[name=hash]').first().val();
    $.post('/admin/properties/open-modal', {
      property: property,
      modal: modal,
      model_name: model_name,
      title: title,
      model_id: model_id,
      bed_id: bed_id,
      hash: hash
    }, function (datajson) {
      $('.modal-container').html(datajson.view);
      $('.modal-container').find('.modal').modal('show');
    });
  });

  $('#search_button').click(function () {
    $('.card-body input.d-none').trigger('click');
  })
  $('#typeRegistration').change(function () {
    var t = $(this).val();
    if (t != '') {
      $('.form-main').removeClass('d-none');
      $('.btn-register').prop('disabled', false);
      if (t == 'owner') {
        $('.full-name').removeClass('d-none');
        $('.agency-name').addClass('d-none');
      } else {
        $('.full-name').addClass('d-none');
        $('.agency-name').removeClass('d-none');
      }
    } else {
      $('.form-main').addClass('d-none');
      $('.btn-register').prop('disabled', true);
    }
  })

  $('.switch-show').change(function () {
    var s = $(this).data('value'), id = $(this).data('id');
    $('#switch_id').val(id); $('#switch_val').val(s);
    $('#switch_form').submit();
  })

  $('#work_schedule_type').change(function () {
    var w = $(this).val();
    if (w == 'same') {
      $('.different-block').addClass('d-none');
    } else {
      $('.different-block').removeClass('d-none');
    }
  })

  $('#header_back_type').change(function () {
    var w = $(this).val();
    if (w == 'video') {
      $('.form-video').removeClass('d-none');
      $('.form-photo').addClass('d-none');
      $('.form-slide').addClass('d-none');
    } else if (w == 'slide') {
      $('.form-video').addClass('d-none');
      $('.form-photo').addClass('d-none');
      $('.form-slide').removeClass('d-none');
    } else {
      $('.form-video').addClass('d-none');
      $('.form-photo').removeClass('d-none');
      $('.form-slide').addClass('d-none');
    }
  })

  $('.link_type_check input').change(function () {
    var l = $('.link_type_check input:checked').val(),
      link = $(this).closest('form').find('#link');
    if (l == 'custom') {
      link.prop('disabled', false);
    } else {
      link.prop('disabled', true).val('');
    }
  })

  $('.stop-agency').click(function () {
    var id = $(this).data('id'), t = $(this).text();
    if ($(this).hasClass('stop-all')) {
      $('#stopAgency').find('#prop_id').val(id);
    } else {
      $('#stopAgency').find('#data_id').val(id);
    }
    $('#stopAgency .modal-title').text(t);
    $('#stopAgency').modal('show');
  })

  $('.admin-delete-el').click(function () {
    var id = $(this).data('id');
    $('#removeEl').find('#data_id').val(id);
    $('#removeEl').modal('show');
  })
  $('.photo-delete-el').click(function () {
    var id = $(this).data('id');
    $('#removeSlide').find('#dataId').val(id);
    $('#removeSlide').modal('show');
  })
  $('.admin-add-el').click(function () {
    var n = $(this).data('name');
    $('#addMenuEl').find('input[name=name]').val(n);
    $('#addMenuEl').modal('show');
  })
  // Timepicker for default check-in or check-out time
  if ($('#default_check_in').length != 0) {
    $('#default_check_in').timepicker({
      timeFormat: 'h:mm p',
      interval: 30,
      minTime: '12:00am',
      maxTime: '11:00pm',
      defaultTime: '3:00pm',
      startTime: '12:00am',
      dynamic: false,
      dropdown: true,
      scrollbar: true
    }).prop('readonly', true);
  }
  if ($('#default_check_out').length != 0) {
    $('#default_check_out').timepicker({
      timeFormat: 'h:mm p',
      interval: 30,
      minTime: '12:00am',
      maxTime: '11:00pm',
      defaultTime: '11:00am',
      startTime: '12:00am',
      dynamic: false,
      dropdown: true,
      scrollbar: true
    }).prop('readonly', true);
  }

  if ($('.start_time').length != 0) {
    $('.start_time').timepicker({
      timeFormat: 'h:mm p',
      interval: 30,
      dynamic: false,
      dropdown: true,
      scrollbar: true,
      change: function (time) {
        var st = $(this).val(), et = $(this).closest('.form-group').find('.end_time').val(),
          c = $(this).closest('.form-group').find('input[type=hidden]');
        if (st == '' || et == '') {
          c.val('');
        } else {
          c.val(st + ' - ' + et);
        }
      }
    });
  }
  if ($('.end_time').length != 0) {
    $('.end_time').timepicker({
      timeFormat: 'h:mm p',
      interval: 30,
      dynamic: false,
      dropdown: true,
      scrollbar: true,
      change: function (time) {
        var et = $(this).val(), st = $(this).closest('.form-group').find('.start_time').val(),
          c = $(this).closest('.form-group').find('input[type=hidden]');
        if (st == '' || et == '') {
          c.val('');
        } else {
          c.val(st + ' - ' + et);
        }
      }
    });
  }
  $('.control-sidebar').keydown(function (event) {
    if (event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });

  // Show keys details in account
  $(document).on('click', ".account-show-key", function () {
    $(this).parent().find('input').attr('type', 'text');
    $(this).removeClass('account-show-key').addClass('account-hide-key');
  });
  // Hide keys details in account
  $(document).on('click', ".account-hide-key", function () {
    $(this).parent().find('input').attr('type', 'password');
    $(this).removeClass('account-hide-key').addClass('account-show-key');
  });

  // Make reservation for each bedroom
  $(document).on('click', '.tb-main-content-book-button, .tb-main-content-book button', function () {
    showBookingForm(this);
  });
  // Check maximum people on the property page
  $("#property-adults").on('change', function () {
    if (parseInt($("#property-adults").attr('data-maxpeople')) - parseInt($("#property-adults").val()) <= 0) {
      $("#property-children").attr('disabled', true);
      $("#property-children").empty();
      $("#property-children").append('<option selected value="0">0</option>');
    } else {
      $("#property-children").attr('disabled', false);
      $("#property-children").empty();
      for (let x = 0; x <= (parseInt($("#property-adults").attr('data-maxpeople')) - parseInt($("#property-adults").val())); x++) {
        if (x == 0) {
          $("#property-children").append('<option selected value="' + x + '">' + x + '</option>');
        } else {
          $("#property-children").append('<option value="' + x + '">' + x + '</option>');
        }
      }
    }
  });
  // Check date field empty or not
  $('#property-check-in, #property-check-out').on('input change', function () {
    if ($('#property-check-in').val() == "" || $('#property-check-out').val() == "") {
      $("#book-property-button").attr("disabled", true);
    } else if ($('#property-check-in').val() != "" && $('#property-check-out').val() != "") {
      $("#book-property-button").attr("disabled", false);
    }
  });
  // Hide booking details and show first form
  $("#book-property-button-change").on('click', function () {
    $("#book-property-data, #book-property-price").hide('fade');
    $("#book-property").show('fade');
  });

  $("#pay-button").on('click', function () {
    var a = document.forms["payformname"]["contact_first_name"].value;
    var b = document.forms["payformname"]["contact_email"].value;
    var c = document.forms["payformname"]["contact_last_name"].value;
    const message = document.getElementById("p-error");
    if (a != '' && b != '' && c != '') {
      $("#pay-form").hide('fade');
      $("#pay-method").removeClass('d-none').show('fade');
    } else {
      message.innerHTML = "Fill all required fields";
    }
  });
  $('.payment-form').submit(function () {
    $(this).find('button').prop('disabled', true);
  })
  if ($("#property-images-slider").length) {
    var galleryThumbs = new Swiper('#property-images-slider .gallery-thumbs', {
      spaceBetween: 10,
      slidesPerView: 4,
      loop: true,
      freeMode: true,
      loopedSlides: 5, //looped slides should be the same
      watchSlidesVisibility: true,
      watchSlidesProgress: true,
      preloadImages: false,
      lazy: true,
    });
    var galleryTop = new Swiper('#property-images-slider .gallery-top', {
      spaceBetween: 10,
      preloadImages: false,
      lazy: true,
      loop: true,
      loopedSlides: 5, //looped slides should be the same
      navigation: {
        nextEl: '#property-images-slider .swiper-button-next',
        prevEl: '#property-images-slider .swiper-button-prev',
      },
      thumbs: {
        swiper: galleryThumbs,
      },
    });
  }
  if ($("#head-slider .swiper-container").length > 0) {
    var myswiper = new Swiper('#head-slider .swiper-container', {
      autoHeight: true,
      disableOnInteraction: true,
      slidesPerView: 1,
      spaceBetween: 0,
      freeMode: true,
      speed: 1000,
      autoplay: true,
      loop: true,
      navigation: false,
    });
  }
  // Event for button on property page for rates
  $("#property-rates-availability").on('click', function () {
    if (window.navigator.userAgent.indexOf("iPad") !== -1) {
      jQuery('body').animate({ scrollTop: jQuery("#tb-main-template").offset().top - 200 }, 0);
    } else {
      jQuery('html, body').animate({ scrollTop: jQuery("#tb-main-template").offset().top - 200 }, 0);
    }
  });

  if ($("#map").length) {
    // Admin Google Autocomplete
    // City, address, country, state elements
    var city = document.getElementById('city');
    var country = document.getElementById('country');
    var state = document.getElementById('state');
    var address = document.getElementById('address');

    if ($("#place_lat").val() != "" && $("#place_lng").val() != "") {
      initMap(parseFloat($("#place_lat").val()), parseFloat($("#place_lng").val()));
    } else if ($(city).val() != "" || $(country).val() != "" || $(state).val() != "" || $(address).val() != "") {
      showMap();
    }
    // Open fields
    $(country).on('change input', function () {
      if ($(country).val() != "") {
        $(state).attr("disabled", false);
        showMap();
      }
    });
    $(state).on('change input', function () {
      if ($(state).val() != "") {
        $(city).attr("disabled", false);
        showMap();
      }
    });
    $(city).on('change input', function () {
      if ($(city).val() != "") {
        $(address).attr("disabled", false);
        showMap();
      }
    });
    $(address).on('change input', function () {
      showMap();
    });
    if ($('#country').length > 0) {
      // Their autocomplete names
      var autocompleteCountry = new google.maps.places.Autocomplete(country, { types: ['(regions)'] });
      // Autocomplete country
      google.maps.event.addListener(autocompleteCountry, 'place_changed', function () {
        var place = autocompleteCountry.getPlace();
        for (let i = 0; i < place['address_components'].length; i++) {
          for (let x = 0; x < place['address_components'][i]['types'].length; x++) {
            if (place['address_components'][i]['types'][x] == "country") {
              $(country).val(place['address_components'][i]['long_name']);
              // Autocomplete names
              var autocompleteState = new google.maps.places.Autocomplete(state, {
                types: ['(regions)'],
                componentRestrictions: { country: place['address_components'][i]['short_name'].toLowerCase() }
              });
              var autocompleteCity = new google.maps.places.Autocomplete(city, {
                types: ['(cities)'],
                componentRestrictions: { country: place['address_components'][i]['short_name'].toLowerCase() }
              });
              var autocompleteAddress = new google.maps.places.Autocomplete(address, {
                types: ['address'],
                componentRestrictions: { country: place['address_components'][i]['short_name'].toLowerCase() }
              });
              // Autocomplete state
              google.maps.event.addListener(autocompleteState, 'place_changed', function () {
                var placeState = autocompleteState.getPlace();
                for (let y = 0; y < placeState['address_components'].length; y++) {
                  for (let z = 0; z < placeState['address_components'][y]['types'].length; z++) {
                    if (placeState['address_components'][y]['types'][z] == "administrative_area_level_1") {
                      $(state).val(placeState['address_components'][y]['long_name']);
                      break;
                    }
                  }
                }
              });
              // Autocomplete city
              google.maps.event.addListener(autocompleteCity, 'place_changed', function () {
                var placeCity = autocompleteCity.getPlace();
                for (let y = 0; y < placeCity['address_components'].length; y++) {
                  for (let z = 0; z < placeCity['address_components'][y]['types'].length; z++) {
                    if (placeCity['address_components'][y]['types'][z] == "locality") {
                      $(city).val(placeCity['address_components'][y]['long_name']);
                      var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(placeCity.geometry.location.lat(), placeCity.geometry.location.lng()), new google.maps.LatLng(placeCity.geometry.location.lat(), placeCity.geometry.location.lng()));
                      var autocompleteAddress = new google.maps.places.Autocomplete(address, {
                        bounds: defaultBounds,
                        types: ['address'],
                        componentRestrictions: { country: place['address_components'][i]['short_name'].toLowerCase() }
                      });
                      google.maps.event.addListener(autocompleteAddress, 'place_changed', function () {
                        var placeAddress = autocompleteAddress.getPlace();
                        showMap();
                        for (let pa = 0; pa < placeAddress['address_components'].length; pa++) {
                          for (let paa = 0; paa < placeAddress['address_components'][pa]['types'].length; paa++) {
                            if (placeAddress['address_components'][pa]['types'][paa] == "route") {
                              $(address).val(placeAddress['address_components'][pa]['long_name']);
                            }
                          }
                        }
                      });
                      break;
                    }
                  }
                }
              });
              break;
            }
          }
        }
      });
    }
  }
  if ($('#place_autocomplete').length > 0) {
    const input = document.getElementById("place_autocomplete");
    const options = {
      types: ['(cities)'],
    };
    const autocomplete = new google.maps.places.Autocomplete(input, options);
  }
  // Add map on locations page
  if ($("#map-locations").length) {
    locationsMap();
  }
  if ($('.editor').length > 0) {
    // $('.editor').summernote({
    //     height: 500,
    //     codemirror: { // codemirror options
    //         theme: 'monokai'
    //     }
    // });
    CKEDITOR.replace('content');
    CKEDITOR.replace('content_fr');
  }
  // On hover property on location page show map property
  $(document).on('mouseover', '.apartments-list', function () {
    let temporaryMapsStorage = [];
    $("map[id^=gmimap]").each(function () {
      temporaryMapsStorage.push($(this).attr('id'));
    });
    let countMapsStorageUsed = 0;
    $(".apartments-list").each(function () {
      $(this).attr('data-map', temporaryMapsStorage[countMapsStorageUsed]);
      countMapsStorageUsed++;
    });
    $("#" + $(this).attr('data-map') + " area").trigger('click');
  });
})
function calcTotal(el) {
  var e = $(el);
  if ($(el.target).hasClass('datepicker')) {
    e = $(el.target);
  }
  var f = e.closest('form'),
    p_id = f.find('input[name=property]').val(),
    currency = f.find('select[name=reservation_currency] :selected').val(),
    discount = f.find('input[name=discount_amount]').val(),
    room_id = f.find('select[name=reservation_room_type]').length > 0 ? f.find('select[name=reservation_room_type] :selected').val() : '',
    sub_total_price = f.find('input[name=reservation_rental_price]'),
    tax_fee = f.find('input[name=tax_fee]'),
    payment_status = f.find('select[name=payment_status] :selected').val(),
    total_price = f.find('input[name=total_price]'),
    paid = f.find('input[name=paid]'),
    check_in = f.find('input[name=reservation_check_in]').val(),
    check_out = f.find('input[name=reservation_check_out]');
  // if (total_price.length > 0 && p_id != '') {
  $.post('/admin/calc-total', {
    p_id: p_id,
    payment_status: payment_status,
    currency: currency,
    discount: discount,
    room_id: room_id,
    check_in: check_in,
    check_out: check_out.val()
  }, function (datajson) {
    sub_total_price.val(datajson.subtotal);
    tax_fee.val(datajson.tax_fee);
    total_price.val(datajson.total);
    paid.val(datajson.paid);
    f.find('.total').html(datajson.currency + '' + datajson.total);
    if (check_out.attr('id') != 'reservation_check_out_close') {
      check_out.datepicker('setStartDate', datajson.startCheckOut);
    } else {
      check_out.datepicker('setStartDate', check_in);
    }
  });
  // }
}
function getCheckOut(el) {
  var e = $(el.target);
  var f = e.closest('form'),
    p_id = f.find('input[name=property]').val(),
    room_id = f.find('select[name=reservation_room_type]').length > 0 ? f.find('select[name=reservation_room_type] :selected').val() : '',
    check_in = f.find('input[name=reservation_check_in]').val(),
    check_out = f.find('input[name=reservation_check_out]');
  $.post('/admin/get-checkout', {
    p_id: p_id,
    room_id: room_id,
    check_in: check_in,
  }, function (datajson) {
    check_out.datepicker('setStartDate', datajson.startCheckOut);
  });
}
function reservationCalc(el) {
  var e = $(el);
  var f = $('#book-property'),
    p_id = f.find('input[name=property]').val(),
    room_id = f.find('input[name=room_id]').val(),
    sub_total_price = $('#sub_total_price'),
    tax_fee = $('#tax_fee'),
    total_price = $('#total_price'),
    check_in = f.find('input[name=reservation_check_in]').val(),
    check_out = f.find('input[name=reservation_check_out]');
  $.post('/admin/calc-total', {
    p_id: p_id,
    room_id: room_id,
    check_in: check_in,
    check_out: check_out.val()
  }, function (datajson) {
    $("#book-property-data, #book-property-price").show('fade');
    $("#book-property").hide('fade');
    let reservationDays = Math.round((new Date(check_out.val().split(".")[2], check_out.val().split(".")[1] - 1, check_out.val().split(".")[0]) - new Date(check_in.split(".")[2], check_in.split(".")[1] - 1, check_in.split(".")[0])) / (1000 * 60 * 60 * 24));

    $('#reservation_rental_price').val(datajson.subtotal);
    $('#tax_fee_val').val(datajson.tax_fee);
    $('#total_price_val').val(datajson.total);
    sub_total_price.html(datajson.subtotal);
    tax_fee.html(datajson.tax_fee);
    total_price.html(datajson.total);
    $("#book-property-data-check-in span").text(check_in);
    $("#book-property-data-check-out span").text(check_out.val());
    $("#book-property-data-adults span").text($("#property-adults").val());
    $("#book-property-data-children span").text($("#property-children").val());
    $("#book-property-fields-nights").text(reservationDays);
    $('#reservation_nights').val(reservationDays);
  });
}
function validateDates(el) {
  var check_in = $(el).closest('form').find('input[name=reservation_check_in]').val(),
    check_out = $(el).closest('form').find('input[name=reservation_check_out]').val();
  if (check_in != '' && check_out != '') {
    $('.error-message').html('');
    $(el).closest('form').submit();
  } else {
    $('.control-sidebar').animate({ scrollTop: $('#err').position().top }, 1000);
    $('.error-message').html('The check in and check out fields are required.');
  }
}
function goCalendar(el) {
  location.href = $(el).find(":selected").data('link');
}
function locationsMap() {
  let propertiesLat = [];
  let propertiesLng = [];
  let propertiesImg = [];
  let propertiesName = [];
  let propertiesType = [];
  let propertiesBed = [];
  let propertiesPeople = [];
  let propertiesPrice = [];
  let propertiesProperty = [];
  let propertiesCurrency = $("#map-locations-currency").val();
  // Get all properties lat
  $("#apartments-list-wrapper .map-locations-lat").each(function () {
    propertiesLat.push($(this).val());
  });
  // Get all properties lng
  $("#apartments-list-wrapper .map-locations-lng").each(function () {
    propertiesLng.push($(this).val());
  });
  // Get all images
  $("#apartments-list-wrapper .map-locations-img").each(function () {
    propertiesImg.push($(this).val());
  });
  // Get all names
  $("#apartments-list-wrapper .map-locations-name").each(function () {
    propertiesName.push($(this).val());
  });
  // Get all types
  $("#apartments-list-wrapper .map-locations-type").each(function () {
    propertiesType.push($(this).val());
  });
  // Get all beds
  $("#apartments-list-wrapper .map-locations-bed").each(function () {
    propertiesBed.push($(this).val());
  });
  // Get all people
  $("#apartments-list-wrapper .map-locations-people").each(function () {
    propertiesPeople.push($(this).val());
  });
  // Get all prices
  $("#apartments-list-wrapper .map-locations-price").each(function () {
    propertiesPrice.push($(this).val());
  });
  // Get all properties
  $("#apartments-list-wrapper .map-locations-property").each(function () {
    propertiesProperty.push($(this).val());
  });
  var propertyPlace = { lat: parseFloat(propertiesLat[0]), lng: parseFloat(propertiesLng[0]) };
  var mapProperty = new google.maps.Map(document.getElementById('map-locations'), { zoom: 12, center: propertyPlace });
  var infowindow = new google.maps.InfoWindow();
  for (var i = 0; i < propertiesLat.length; i++) {
    let currentContent = '<div class="card border-0"><a href="property/' + propertiesProperty[i] + '"><img class="card-img" src="' + propertiesImg[i] + '" alt="Card image"></a><div class="card-img-overlay location-marker-info p-0"><div class="location-marker-info-title-wrapper pb-2"><p class="card-text clearfix location-marker-info-title mb-0 px-2"><a href="property/' + propertiesProperty[i] + '"><span class="float-left font-weight-bold text-white">' + propertiesName[i] + '</span><span class="float-right font-weight-bold">' + propertiesPrice[i] + '</span></a></p><div class="home-gallery-overlay"></div></div><div class="location-marker-info-data-wrapper bg-white py-3"><p class="card-text">' + propertiesType[i] + ' / <i class="fas fa-bed pr-2"></i>' + propertiesBed[i] + '<i class="fas fa-user pl-3 pr-2"></i>' + propertiesPeople[i] + '</p></div></div></div>';
    let currentLat = parseFloat(propertiesLat[i]);
    let currentLng = parseFloat(propertiesLng[i]);
    var marker = new google.maps.Marker({
      position: { lat: currentLat, lng: currentLng },
      map: mapProperty
    });
    google.maps.event.addListener(marker, 'click', function () {
      infowindow.close();
      infowindow.setContent(currentContent);
      infowindow.open(mapProperty, this);
    });
  }
}
// Show map on property creation page
function initMap(latVal, lngVal) {
  if (!isNaN(latVal) && !isNaN(lngVal)) {
    var place = { lat: latVal, lng: lngVal };
    var map = new google.maps.Map(
      document.getElementById('map'), { zoom: 17, center: place });
    var marker = new google.maps.Marker({ position: place, map: map, draggable: true });
    google.maps.event.addListener(marker, 'dragend', function (evt) {
      $("#place_lat").val(marker.position.lat());
      $("#place_lng").val(marker.position.lng());
    });
  }
}
function showMap() {
  let currentAddress = jQuery("#address").val().split(" ").join("+");
  let currentCity = jQuery("#city").val().split(" ").join("+");
  let currentState = jQuery("#state").val().split(" ").join("+");
  let currentCountry = jQuery("#country").val().split(" ").join("+");
  var jsonLtdLng = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + currentAddress + ',+' + currentCity + ',+' + currentState + ',+' + currentCountry + '&key=AIzaSyCTFcG_V1xd0aVrQM4MohUz_CuQE2Dctew';
  $.getJSON(jsonLtdLng, function (data) {
    initMap(data['results'][0]['geometry']['location']['lat'], data['results'][0]['geometry']['location']['lng']);
    $("#place_lat").val(data['results'][0]['geometry']['location']['lat']);
    $("#place_lng").val(data['results'][0]['geometry']['location']['lng']);
  });
}
function loadProperties(page) {
  let locationFiltersData = [];
  $("#filter-locations input, #filter-locations select").each(function () {
    locationFiltersData.push($(this).val());
  });
  MakeUrl(locationFiltersData);

  $('.loader').show();
  $.get("/locations", {
    page: page,
    locationFiltersData: locationFiltersData
  }, function (datajson) {
    if (page == 1) {
      $('#apartments-list-inner-wrapper').html(datajson.view);
    } else {
      $("#apartments-list-inner-wrapper").append(datajson.view);
    }
    locationsMap();
    $('.loader').hide();
  });
}
function MakeUrl(data) {
  window.history.pushState({ "html": '', "pageTitle": '' }, "", "/locations?city=" + data[0] + "&checkin=" + data[1] + "&checkout=" + data[2] + "&guests=" + data[4] + "&bedrooms=" + data[5]);
}
function showBookingForm(el, isSofaBed = null) {
  if (isSofaBed === null) {
    $("#book-wrapper").removeClass('d-none').addClass('d-block');
    $('#property-check-in').focus();
  }
  
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
    document.getElementById("property-check-in").scrollIntoView();
  }
  $("#reservation-type").val($(el).attr('data-pbedroom'));
  var room_id = $(el).attr('data-room_id');
  $('#book-property input[name=room_id]').val(room_id);
  let bookingFormName = $(el).attr('data-pbedroom');
  $("#book-property-title").text("Book the " + $("#property-wrapper h1").text() + " " + bookingFormName);
  $("#book-property-data-title").text("Check price and availability for the " + $("#property-wrapper h1").text() + " " + bookingFormName);
}
