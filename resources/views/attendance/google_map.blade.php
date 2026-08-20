<style type="text/css">
    html,
    body {
        height: 100%;
    }
</style>

@php

    $clockInDateTime = '';
    $clockOutDateTime = '';

    if (empty($data->clock_in_date_time) || $data->clock_in_date_time == '0000-00-00 00:00:00') {
        $clockInDateTime = $data->getRawOriginal('date') . ' ' . $data->clock_in;
    } else {
        $clockInDateTime = $data->clock_in_date_time;
    }

    if (empty($data->clock_out_date_time) || $data->clock_out_date_time == '0000-00-00 00:00:00') {
        $clockOutDateTime = $data->getRawOriginal('date') . ' ' . $data->clock_out;
    } else {
        $clockOutDateTime = $data->clock_out_date_time;
    }

    $clockInMetaData = [];
    $metaDataString = $data->clock_in_meta_data;
    $firstDecode = json_decode($metaDataString, true);
    if (is_string($firstDecode)) {
        $clockInMetaData = json_decode($firstDecode, true);
    } else {
        $clockInMetaData = $firstDecode;
    }

    $clockOutMetaData = [];
    $metaDataString = $data->clock_out_meta_data;
    $firstDecode = json_decode($metaDataString, true);
    if (is_string($firstDecode)) {
        $clockOutMetaData = json_decode($firstDecode, true);
    } else {
        $clockOutMetaData = $firstDecode;
    }

    $distance = '';
    $isAutoClockout = '';

    if (!empty($clockOutMetaData)) {
        try {
            if (array_key_exists('distance', $clockOutMetaData)) {
                $distance = $clockOutMetaData['distance'] ?? '';
            }

            if (array_key_exists('is_auto_clock_out', $clockOutMetaData)) {
                $isAutoClockout = $clockOutMetaData['is_auto_clock_out'] ?? false;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

@endphp

<div class="card-body">
    <div class="row">
        @if ($data->clock_in != '00:00:00')
            <div class="col-sm-8 mb-4">
                <h7>Clock In :
                    {{ Auth::user()->dateFormat($clockInDateTime) . ' ' . Auth::user()->timeFormat($clockInDateTime) }}
                </h7>
                <br>
                <label>Address: <span id="clock_in_address"></span></label>
            </div>

            <div class="col-sm-4 mb-4">
                @php
                    $imagePath = getFilePathPrefix() . 'images/clock_in_out/' . $data->employee_id . '/' . $data->image;
                @endphp
                <img src="{{ $imagePath }}" class="img-fluid rounded-circle showImage" alt="image"
                    style="width:115px;height:115px; cursor: pointer;" />
            </div>
        @endif

        @if ($data->clock_out != '00:00:00')
            <div class="col-sm-8 mb-4">
                <h7>Clock Out :
                    {{ Auth::user()->dateFormat($clockOutDateTime) . ' ' . Auth::user()->timeFormat($clockOutDateTime) }}
                </h7>
                <br>
                <label>Address: <span id="clock_out_address"></span></label>
            </div>

            @if ($data->clock_out_image != null && !empty($data->clock_out_image))
                <div class="col-sm-4 mb-4">
                    @php
                        $imagePath =
                            getFilePathPrefix() .
                            'images/clock_in_out/' .
                            $data->employee_id .
                            '/' .
                            $data->clock_out_image;
                    @endphp
                    <img src="{{ $imagePath }}" class="img-fluid rounded-circle showImage" alt="image"
                        style="width:115px;height:115px; cursor: pointer;" />
                </div>
            @endif

            @if (!empty($clockOutMetaData))
                <div class="col-sm-8 mb-4">
                    @if (!empty($distance))
                        <h7>Distance : {{ $distance }}</h7>
                    @endif

                    <br>
                    {{-- @if ($isAutoClockout != '') --}}
                    <h7>Is Auto Clockout : {{ $isAutoClockout == true ? 'Yes' : 'No' }}</h7>
                    {{-- @endif --}}
                </div>
            @endif
        @endif
    </div>
    <div class="row">

        <div class="col-sm-12">
            <div id="map" style="width: 700px; height: 400px"></div>
        </div>
    </div>


    <br />

    {{-- <div class="modal-footer pr-0">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{ __('Cancel') }}</button>
</div> --}}



    <script>
        // Add markers to Google Maps based on the lat/long.

        var map;

        var clock_in_latitude = "{{ $data->clock_in_latitude }}";
        var clock_in_longitude = "{{ $data->clock_in_longitude }}";

        var clock_out_latitude = "{{ $data->clock_out_latitude }}";
        var clock_out_longitude = "{{ $data->clock_out_longitude }}";

        var infowindow = null;

        clock_in_latitude = parseFloat(clock_in_latitude);
        clock_in_longitude = parseFloat(clock_in_longitude);

        clock_out_latitude = parseFloat(clock_out_latitude);
        clock_out_longitude = parseFloat(clock_out_longitude);

        // SHow Map with pins of clock in and out
        function initializeMap() {
            var mapOptions = {
                center: {
                    lat: clock_in_latitude,
                    lng: clock_in_longitude
                },
                zoom: 13,
                streetViewControl: false, // Disable Street View
                styles: [{
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{
                        visibility: 'off'
                    }],
                }, ],
            };

            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            var markers = [{
                    position: {
                        lat: clock_in_latitude,
                        lng: clock_in_longitude
                    },
                    text: 'Clock In - {{ Auth::user()->timeFormat($data->clock_in) }}',
                    color: 'green'
                },
                {
                    position: {
                        lat: clock_out_latitude,
                        lng: clock_out_longitude
                    },
                    text: 'Clock Out - {{ Auth::user()->timeFormat($data->clock_out) }}',
                    color: 'red'
                },
                // Add more markers as needed
            ];

            markers.forEach(function(markerData) {
                var marker = new google.maps.Marker({
                    position: markerData.position,
                    map: map,
                    title: markerData.text,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8, // Adjust the scale to make the circle bigger
                        fillColor: markerData.color,
                        fillOpacity: 1,
                        strokeWeight: 1,
                        strokeColor: 'white',
                    },
                });

                // Set the pin color
                marker.setIcon('http://maps.google.com/mapfiles/ms/icons/' + markerData.color + '-dot.png');

                var infowindow = new google.maps.InfoWindow({
                    content: markerData.text,
                });

                marker.addListener('mouseover', function() {
                    infowindow.open(map, marker);
                });

                marker.addListener('mouseout', function() {
                    infowindow.close();
                });

                // marker.addListener('click', function() {
                //      infowindow.open(map, marker);
                // });
            });
        }

        google.maps.event.addDomListener(window, 'load', initializeMap);
        // SHow Map with pins of clock in and out

        initializeMap();


        //  Get Google Map Address
        function getAddressFromLatLng(lat, lng, element) {
            // new 

            // Construct the URL for the Nominatim API request
            //   const url2 = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`;

            //   fetch(url2)
            //       .then(response => response.json())
            //       .then(data => {
            //           const address = data.display_name;

            //           // console.log("Address:", address);
            //           $("#" + element).html(address);
            //       })
            //       .catch(error => {
            //           // console.error("Error:", error);
            //           $("#" + element).html("");
            //       });
            // new 





            $.ajax({
                url: "{{ route('check_address_saved') }}",
                type: "POST",
                data: {
                    key: element,
                    attendance_id: '{{ $data->id }}',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == 1) {
                        if (response.data == '' || response.data == null) {
                            //  old
                            var geocoder = new google.maps.Geocoder();
                            var latlng = new google.maps.LatLng(lat, lng);

                            geocoder.geocode({
                                'latLng': latlng
                            }, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {

                                        var address = results[0].formatted_address;

                                        $("#" + element).html(address);
                                        $.ajax({
                                            url: "{{ route('save_address_from_lat_long') }}",
                                            type: "POST",
                                            data: {
                                                key: element,
                                                address: address,
                                                attendance_id: '{{ $data->id }}',
                                                _token: '{{ csrf_token() }}'
                                            },
                                            success: function(response) {

                                            },
                                            error: function(xhr, status, error) {

                                            }
                                        });
                                    } else {
                                        $("#" + element).html("");
                                    }
                                } else {
                                    $("#" + element).html("");
                                }
                            });
                            //  old
                        } else {
                            $("#" + element).html(response.data);
                        }
                    }
                },
                error: function(xhr, status, error) {

                }
            });

        }

        getAddressFromLatLng(clock_in_latitude, clock_in_longitude, "clock_in_address");
        getAddressFromLatLng(clock_out_latitude, clock_out_longitude, "clock_out_address");

        $(document).ready(function() {
            // Function to show image in full screen
            function showFullScreenImage(imageUrl) {
                // Create a full-screen overlay div
                let $overlay = $('<div>', {
                    id: 'image-overlay',
                    css: {
                        position: 'fixed',
                        top: 0,
                        left: 0,
                        width: '100%',
                        height: '100%',
                        backgroundColor: 'rgba(0, 0, 0, 0.9)', // Black overlay with some transparency
                        zIndex: 999999, // Very high z-index to appear above all elements
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center'
                    }
                });

                // Create the image element
                let $image = $('<img>', {
                    src: imageUrl,
                    css: {
                        maxWidth: '90%',
                        maxHeight: '90%',
                        boxShadow: '0px 0px 20px rgba(255, 255, 255, 0.7)',
                        cursor: 'pointer' // Hand cursor for the image
                    }
                });

                // Create a close button
                let $closeButton = $('<button>', {
                    text: 'Close',
                    css: {
                        position: 'fixed',
                        top: '20px',
                        right: '20px',
                        padding: '10px 20px',
                        backgroundColor: '#f44336',
                        color: '#fff',
                        border: 'none',
                        borderRadius: '5px',
                        cursor: 'pointer',
                        zIndex: 1000000 // Slightly higher than the overlay to ensure visibility
                    },
                    click: function() {
                        $overlay.remove(); // Remove the overlay when the close button is clicked
                    }
                });

                // Append the image and close button to the overlay
                $overlay.append($image).append($closeButton);

                // Append the overlay to the body
                $('body').append($overlay);

                // Close the overlay if clicking outside the image
                $overlay.click(function(e) {
                    if (e.target === this) {
                        $overlay.remove(); // Remove the overlay
                    }
                });
            }

            // Example usage: show the image in full screen on click
            $('.showImage').click(function() {
                let imageUrl = $(this).attr('src');
                showFullScreenImage(imageUrl);
            });
        });
    </script>
