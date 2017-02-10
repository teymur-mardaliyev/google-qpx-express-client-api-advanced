var lineCoordinates = [], line;

function animateCircle() {
    var count = 0;
    window.setInterval(function () {
        count = (count + 1) % 200;

        var icons = line.get('icons');
        icons[0].offset = (count / 2) + '%';
        line.set('icons', icons);
    }, 100);
}

/**
 *    Animated map, plane flight from one airport to another.
 * */

function initialize() {

    window.map = new google.maps.Map(document.getElementById('map-canvas'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    });

    var infowindow = new google.maps.InfoWindow();

    var bounds = new google.maps.LatLngBounds();

    var image = {
        url: '/public/assets/images/airplane/airport.png',
        // This marker is 20 pixels wide by 32 pixels tall.
        size: new google.maps.Size(24, 24)
    };

    var shape = {
        coords: [1, 1, 1, 20, 18, 20, 18, 1],
        type: 'poly'
    };


    for (i = 0; i < locations.length; i++) {
        var lat = locations[i].airport_position.lat,
            lng = locations[i].airport_position.lng;

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: map,
            icon: image,
            shape: shape
        });

        bounds.extend(marker.position);

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i].departure_city + ', ' + locations[i].departure_airport);
                infowindow.open(map, marker);
            }
        })(marker, i));

        lineCoordinates.push(new google.maps.LatLng(lat, lng));
    }

    /**
     *  Airplane icon
     */


    var lineSymbol = {
        path: 'M3.2,21.2h0.2l15.1,5c0,0,1.1-2.9-0.4-3.9c-1.5-1-4.5-3-4.5-3l-4.5-3l-5.9-4H3.3L3,3C3,3,3,0,0.8,0c-2.2,0-2.4,2.6-2.4,2.6l-0.4,10l-5.1,3.6l-4.5,3.1c0,0-2.6,1.5-4.1,2.9c-1.5,1.4-0.4,4.4-0.4,4.4l14.2-5.3l1.3,12.9c0,0.3,0,0.6,0,0.9l-4.6,3.2c0,0-1.2,1.7,0,1.7c1.2,0,10.8,0,11.8,0c1,0,0-1.5,0-1.5l-5.1-3.3c0-0.2,0-0.3,0-0.5L3.2,21.2z',
        fillColor: 'gold',
        fillOpacity: 1,
        scale: 0.9,
        strokeOpacity: 1,
        strokeColor: '#f2ad00'
    };

    // Create the polyline and add the symbol to it via the 'icons' property.

    line = new google.maps.Polyline({
        geodesic: true,
        path: lineCoordinates,
        icons: [{
            icon: lineSymbol,
            offset: '100%'
        }],
        strokeColor: "#cb5194",
        map: map
    });

    map.fitBounds(bounds);

    var listener = google.maps.event.addListener(map, "idle", function () {
        map.setZoom(map.getZoom());
        google.maps.event.removeListener(listener);
    });

    animateCircle();
}


function loadScript() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' + 'callback=initialize';
    document.body.appendChild(script);
}

if (runGoogleMap == true) {
    window.onload = loadScript;
}

function isset(value) {
    if (value === 'undefined') {
        return false;
    }
}


$(document).ready(function (e) {
    if ($("ul.result-list").length > 0) {
        $("ul.result-list").quickPagination({pagerLocation: "after"});
    }

    $(".submitSearch").on({
        click: function () {
            var form = $(".active-form");
            var valid = form.validate({
                rules: {
                    "data[departureAirport]": {
                        required: true,
                        minlength: 3
                    },
                    "data[arrivalAirport]": {
                        required: true,
                        minlength: 3
                    },
                    "data[departureDate]": {
                        required: true
                    },
                    "data[arrivalDate]": {
                        required: true
                    }
                },
                ignore: [],
                errorPlacement: function (error, element) {
                    return true;
                }
            });

            if (valid) {
                form.submit();
            }
        }
    });

    $(".ticketTabs li a").on({
        click: function () {
            var ticketsTab = $(this).parent().parent();
            ticketsTab.find("li.active").removeClass("active");
            $(this).parent().addClass("active");

            searchForm($(this).data("type"));
            return false;
        }
    });

    $(".ticketTabs-select").on({
        change: function () {
            searchForm($(this).val());
        }
    });

    function searchForm(type) {

        var arrivalDate = $(".arrivalDate #arrivalDate, .arrivalDate .arrivalDateSendler");
        $(".search-tickets-form").removeClass("active-form");
        switch (type) {
            case 'oneway':
                arrivalDate.attr({disabled: "disabled"});
                arrivalDate.removeAttr("value");
                $(".search-tickets-form.single").addClass("active-form");
                break;
            case 'twoway':
                arrivalDate.removeAttr("disabled");
                $(".search-tickets-form.single").addClass("active-form");
                break;
            case 'multiple':
                $(".search-tickets-form.multiple").addClass("active-form");
                break;
        }
    }

    $(".arrivalDate").on({
        click: function () {
            $(".ticketTabs li a[data-type=twoway]").trigger("click");
            $(".ticketTabs-select").find("option[value=twoway]").attr("selected", "selected").trigger('change');
            $(this).find("#arrivalDate").focus();
            return false;
        }
    });

    var itemSel = 1, timer;

    $(".search-airport").on({
        click: function (event) {
            if ($(this).val().length > 2) {
                suggestes(event, $(this));
            } else {
                reSmlrAirports();
            }
        },
        keydown: function (event) {
            var similarAir = $(this).parent().parent().find(".similarAirports"),
                similar_li = similarAir.find("li"),
                code = (event.keyCode) ? event.keyCode : event.which,
                liLenght = similar_li.length;

            switch (code) {
                case 38:
                case 40:
                    if (liLenght > 0) {
                        similarAir.find('li:nth-child(' + itemSel + ')').removeClass('hover');
                        itemSel = itemSel + (code == 38 ? -1 : 1);
                        itemSel = itemSel > liLenght ? 1 : itemSel == 0 ? liLenght : itemSel;
                        similarAir.find('li:nth-child(' + itemSel + ')').addClass('hover');
                    }
                    break;
                case 13:
                case 9:
                    selected(event, $(this));
                    event.stopPropagation();
                    break;
                default:
                    break;
            }

        },
        keyup: function (event) {
            clearTimeout(timer);
            suggestes(event, $(this));
        }
    });

    function suggestes(event, $this) {
        var name = $this.val();
        var code = (event.keyCode) ? event.keyCode : event.which;

        if (code != 40 && code != 38) {
            if (id_save == $this.attr("id")) {
                itemSel = 1;
            } else {
                //reSmlrAirports();
            }
            var id_save = $this.attr("id");
            if (name.length > 2) {
                timer = setTimeout(function () {
                    reSmlrAirports();
                    $.ajax({
                        url: "/ajax/airportnames",
                        type: "post",
                        dataType: "json",
                        data: {name: name},
                        context: $this,
                        success: function (data) {
                            if (data.length > 0) {
                                $this.parent().after("<div class='similarAirports'><ul></ul></div>");
                                $.each(data, function (k, v) {
                                    $(".similarAirports ul").append("<li data-name='" + v.name + "'><span id='airport'>" + v.city + " - " + v.name + "<span class='bglight'></span></span><span class='code'>" + v.code + "</span></li>");
                                });
                                itemSel = 1;
                                $(".similarAirports").find('li:nth-child(' + itemSel + ')').addClass('hover');
                            }

                        },
                        error: function (data) {

                        }
                    });
                }, 500);
            }
        }
    }

    $(document).on('mouseover', '.similarAirports li', function (event) {
        event.preventDefault();
        $(this).parent().children().not($(this)).removeClass('hover');
        $(this).addClass('hover');
        itemSel = $(this).index()
    }).on('mouseout', 'li', function (event) {
        event.preventDefault();
        $(this).removeClass('hover');
        itemSel = 0;
    });

    $(document).on("click", ".similarAirports ul li", function (e) {
        var parent_elem = $(this).parent().parent();
        parent_elem.prev().find("input.search-airport").val($(this).find("#airport").text());
        addSelectedCode(parent_elem, $(this));
        e.preventDefault();
        return false;
    });

    $(document).on({
        click: function () {
            reSmlrAirports();
        }
    });

    function reSmlrAirports() {
        $(document).find(".similarAirports").remove();
    }

    function selected(event, $this) {
        var content = $this.parent().parent();
        if (content.find('li:nth-child(' + itemSel + ')').length > 0) {
            var selected = content.find('li:nth-child(' + itemSel + ')');
            $this.attr("value", selected.find("#airport").text());
            addSelectedCode(selected.parent().parent(), selected);
        }
    }

    function addSelectedCode(element, $this) {
        var add_to = element.prev();
        //console.log(element);
        var hidden_elem = add_to.find('.hidden-input-code');
        if (hidden_elem) {
            hidden_elem.val($this.find(".code").text());
            reSmlrAirports();
        }
    }

    function searchTickets() {
        $.ajax({
            url: "/ajax/tickets/search",
            type: "post",
            dataType: "html",
            data: {request: searchRequest},
            success: function (data) {
                window.location = '/search/' + searchRequest + '/' + data;
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    if (searchStart == true) {
        searchTickets();
    }

    $(".show-details").on({
        click: function (e) {
            var first_element = $(this).parent().parent().parent();
            var details = first_element.find(".ticket-details");
            var $this = $(this);
            if ($(this).hasClass("details")) {
                first_element.removeClass("active");
                details.stop().animate({
                    height: '0px'
                }, {
                    complete: function () {
                        $this.removeClass("details");
                    }
                });
            } else {
                first_element.addClass("active");
                details.stop().animate({
                    height: 'auto'
                }, {
                    complete: function () {
                        $this.addClass("details");
                    }
                });
            }
            return false;
        }
    });


    $('#sortable-list').mixItUp({
        load: {
            sort: 'price:asc'
        },
        animation: {
            effects: 'fade rotateZ(-180deg)',
            duration: 500
        },
        selectors: {
            target: '.mix-target',
            sort: '.sort-btn'
        },
        layout: {
            display: 'table',
            containerClass: '',
            containerClassFail: 'fail'
        },
        controls: {
            /*                enable: true,
             live: false,
             toggleFilterButtons: false,*/
            toggleFilterButtons: true,
            /*toggleLogic: 'or',*/
            activeClass: 'active'
        },
        callbacks: {
            onMixEnd: function (state) {
                $("ul.result-list").quickPagination({pagerLocation: "after"});
            }
        }
    });

    $(".sub-btn").on({
        click: function () {
            subscribe($("#subscribers"));
        }
    });

    $("#subscribers").on({
        keypress: function (e) {
            var code = e.keyCode ? e.keyCode : e.which;
            if (code == 13) {
                subscribe($(this));
            }
        }
    });

    $(".sifaris").on({
        click: function () {
            var $div = $("<div>", {class: "sifaris-modal"});
            $div.html("<h3>Bileti sifariş etmək üçün *2000 nömərsinə zəng edin.</h3><p>*Saytda göstərilən qiymətə xidmət haqqı əlavə olunmayıb.</p>");
            $(".modal-body").html($div);
            $('#subscribe-modal').modal('show');
            return false;
        }
    });


    function subscribe(email) {
        var $div = $("<div>", {class: "alert"});

        if (!validateEmail(email.val())) {
            $div.addClass("alert-danger");
            $div.html("E-mail ünvanınız düzgün deyil, bir daha yoxlayın və yenidən göndərməyə çalışın.");
        } else {
            $.ajax({
                url: "/ajax/subscribe/email",
                dataType: "json",
                data: {email: email.val()},
                type: "post",
                context: email,
                success: function (data) {
                    if (data.error == 0) {
                        $div.addClass("alert-success");
                        $(this).val("");
                    } else {
                        $div.addClass("alert-danger");
                    }
                    $div.html(data.message);
                },
                error: function (xhr, data) {
                    console.log(xhr);
                    console.log('------');
                    console.log(data);
                }
            });
        }
        $(".modal-body").html($div);
        $('#subscribe-modal').modal('show');
    }


    var picker = new Pikaday({
        field: document.getElementById('departureDate'),
        numberOfMonths: 2,
        minDate: new Date(new Date().getTime()),
        format: 'D MMMM YYYY',
        firstDay: 1,
        container: document.getElementById('departureDateCalendar'),
        onSelect: function () {
            picker2.setMinDate(this.getDate());
            $(".departureDateSendler").val(this.getMoment().format('YYYY-MM-DD'));

        }
    });


    var picker2 = new Pikaday({
        field: document.getElementById('arrivalDate'),
        numberOfMonths: 2,
        container: document.getElementById('arrivalDateCalendar'),
        format: 'D MMMM YYYY',
        firstDay: 1,
        onSelect: function () {
            picker.setMaxDate(this.getDate());
            $(".arrivalDateSendler").val(this.getMoment().format('YYYY-MM-DD'));
        }
    });
    var $multiDepartureDate = $(".search-tickets-form.multiple .multiDepartureDate");

    var multiPicker = {};

    $.each($multiDepartureDate, function (k, v) {
        var multiCal = k + 1;

        multiPicker[k] = new Pikaday({
            field: document.getElementById($(this).attr("id")),
            numberOfMonths: 2,
            minDate: new Date(new Date().getTime()),
            format: 'D MMMM YYYY',
            firstDay: 1,
            container: document.getElementById('multiDepartureDateCalendar' + multiCal),
            onSelect: function (e) {
                multiPicker[k].setMaxDate(this.getDate());
                multiPicker[multiCal].setMinDate(this.getDate());
                $(v).parent().find(".multiDeparture").val(this.getMoment().format('YYYY-MM-DD'));
            }
        });
    });

});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test($email);
}

