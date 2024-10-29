@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
@endsection
@section('content')
<?php
 $company_login_id = session()->get('company_login_id');
 $ids = $data->id ?? '';
?>
<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">{{ $heading }}</h1>
                    <ol class="breadcrumb">
                        <?php if ($company_login_id) : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/system-overview') }}">{{ $module }}</a></li>
                        <?php if($ids): ?>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/device_details/'.$ids) }}">POWRBANK Details</a></li>
                        <?php endif; ?>
                        <?php else : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/system-overview') }}">{{$module}}</a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                <div class="ms-auto pageheader-btn">
                    {{-- <a href="javascript:void(0);" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Add Company
                    </a> --}}
                </div>
            </div>
            <!-- PAGE-HEADER END -->

             <!-- ROW-1 OPEN -->
             <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $heading }} Info</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" id="frm_add">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $data->id ?? ''}}">
                                <input type="hidden" name="lat" id="lat" value="{{$data->location['lat'] ?? ''}}">
                                <input type="hidden" name="long" id="long" value="{{$data->location['long'] ?? ''}}">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="device_name">POWRBANK Name <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="device_name" id="device_name" placeholder="Enter POWRBANK name" value="{{ $data->name ?? ''}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="macid">MACID <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="macid" id="macid" placeholder="Enter MACID" value="{{ $data->macid ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="serial_no">Serial Number </label>
                                            <input type="text" class="form-control" name="serial_no" id="serial_no" placeholder="Enter serial number" value="{{ $data->serial_no ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="install_code">Agent Install Code </label>
                                            <?php
                                                $is_read_only = '';
                                                if(isset($data->install_code) && $data->install_code){
                                                    //$is_read_only = 'readonly';
                                                }
                                            ?>
                                            <input type="text" class="form-control" name="install_code" id="install_code" placeholder="Enter install code" value="{{ $data->install_code ?? ''}}" {{$is_read_only}}>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="channel_id">Channel ID </label>
                                            <input type="text" class="form-control" name="channel_id" id="channel_id" placeholder="Enter channel id" value="{{ $data->channel_id ?? ''}}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="hardware">Hardware </label>
                                            <input type="text" class="form-control" name="hardware" id="hardware" placeholder="Enter hardware" value="{{ $data->hardware ?? ''}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="description">Description </label>
                                            <textarea class="form-control" name="description" id="description" placeholder="Enter description" rows="2">{{ $data->description ?? ''}}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group form-group-register error-country_id">
                                            <label for="cluster_id">Group</label>
                                            <select class="form-select select2 form-control" name="cluster_id" id="cluster_id">
                                                <option value="">Select</option>
                                                @if ($clusters)
                                                @foreach ($clusters as $cluster)
                                                <?php
                                                    $selected = "";
                                                    if(isset($data->cluster_id) && $cluster->id == $data->cluster_id){
                                                        $selected = "selected";
                                                    }
                                                ?>
                                                <option <?= $selected ?> value="{{$cluster->id ?? ''}}">{{$cluster->name ?? ''}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="search_address">Address </label>
                                            <input type="text" class="form-control" name="search_address" id="search_address" placeholder="Search address" value="{{$data->location['address'] ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="is_active" class="form-label">Status</label>
                                            <?php
                                            $status = 0;
                                            if(isset($data->status) && $data->status){
                                                $status = 1;
                                            } else if( !isset($data->status) ){
                                                $status = 0;
                                            }
                                            ?>
                                            <label class="custom-switch">
                                                <input type="checkbox" name="status" id="status" value="1" class="custom-switch-input chk-status" {{ $status == 1 ? 'checked' : ''}}>
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- <div class="ht-300" id="leaflet1" style="height:300px"></div> --}}
                                    <div class="ht-300" id="map" style="height:300px"></div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-end">
                            <a href="javascript:void(0);" class="btn btn-danger w-sm btn_cancle">Cancel</a>
                            <a href="javascript:void(0);" class="btn btn-primary w-sm btn_save">Save</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-1 CLOSED -->

        </div>
         <!-- CONTAINER END -->
    </div>
</div>
@endsection
@section('page_level_js')
<!-- Custom Jquery Validation -->
<script src=" {{ url('theme-asset/jquery-validation/jquery.validate.min.js') }}"></script>
<!-- INTERNAL leaflet js -->
{{-- <script src="{{ url('theme-asset/plugins/leaflet/leaflet.js') }}"></script> --}}

<script>
    var geocoder = '';
    var latituted  = 51.1657;
    var longituted = 10.4515;
    var zoom = 5;
    if($('#lat').val())
    {
        latituted =  Number($('#lat').val());
    }
    if($('#long').val())
    {
        longituted = Number($('#long').val());
        zoom = 17;
    }
    function initMap() {
        // const myLatLng = { lat: 51.1657, lng: 10.4515 };
        const myLatLng = { lat: latituted, lng: longituted };
        geocoder = new google.maps.Geocoder();
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: zoom,
            center: myLatLng,
        });
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            draggable: true
        });
        var input = document.getElementById('search_address');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(marker, "dragend", function(event) {
            $('input[name="long"]').val(this.getPosition().lng());
            $('input[name="lat"]').val(this.getPosition().lat());
            geocodePosition(marker.getPosition());
        });
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                $.growl.error({
                    message: "No details available for input: '" + place.name + "'"
                });
                return;
            }
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17); // Why 17? Because it looks good.
            }
            marker.setPosition(place.geometry.location);
            $('#lat').val(place.geometry['location'].lat());
            $('#long').val(place.geometry['location'].lng());
            // document.getElementById("search_address").value = place.name;
        });

    }
    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                $('#search_address').val('');
                $('#search_address').val(responses[0].formatted_address);
            //   updateMarkerAddress(responses[0].formatted_address);
            } else {
                $.growl.error({
                    message: "Cannot determine address at this location."
                });
            }
        });
    }
    window.initMap = initMap;
</script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyAXgn1b-y0lC11QjFve-kP6oCFxKG2sHfo&callback=initMap&libraries=places" ></script>

<script>
    $(document).ready(function($) {
        var company_login_id = '{{ $company_login_id ?? ''}}';
        $('.select2').select2({});
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return /^[a-z0-9:]+$/.test(value);
        }, "Small Letters, Numbers, and Colon only please");

        // Validation Device
         $('#frm_add').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                device_name: {
                    required: true,
                },
                macid: {
                    required: true,
                    alphanumeric :true
                }
            },
            messages: {

            },
            // errorElement: "div",
            submitHandler: function (form) {
                // return true;
            }

        });

         // Save Record
         $(document).on('click', '.btn_save', function() {
            var _this = $(this);
            if($("#frm_add").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("save-device")}}',
                    type: "POST",
                    data:  $('#frm_add').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        _this.prop('disabled', false).html('Save');
                        try {
                            data = JSON.parse(data);
                        } catch(e){}
                        if(data.status == "true")
                        {
                            if($('#id').val())
                            {
                                var id = data.user_id;
                                if(id)
                                {
                                    $('#global-loader').show();
                                    setInterval(() => {
                                        var is_verified = checkIsDeviceVerified(id);
                                    }, 3000);
                                    setTimeout(() => {
                                        $.growl.notice({
                                            title: "Success",
                                            message: "Succesfully Saved."
                                        });
                                        if(company_login_id)
                                        {
                                            var url  = "/company/"+company_login_id+"/system-overview";
                                            location = url;
                                        } else {
                                            location = "{{url('/system-overview')}}";
                                        }

                                    }, 15000);
                                } else {

                                    // Update Response
                                    $.growl.notice({
                                        title: "Success",
                                        message: "Succesfully Saved."
                                    });
                                    if(company_login_id)
                                    {
                                        var url  = "/company/"+company_login_id+"/system-overview";
                                        location = url;
                                    } else {
                                        location = "{{url('/system-overview')}}";
                                    }
                                }

                            } else {
                                // Saved Response
                                $('#global-loader').show();
                                notif({
                                    type: "warning",
                                    msg: "<b> Please wait... We are verifying this POWRBANK with ECM. It may take 15-20 seconds </b>",
                                    position: "center",
                                    width: 800,
                                    autohide: true,
                                    timeout: 15000,
                                    opacity: 1
                                });
                                setInterval(() => {
                                    var id = data.user_id;
                                    if(id)
                                    {
                                        var is_verified = checkIsDeviceVerified(id);
                                    }
                                }, 3000);

                                setTimeout(() => {

                                    $.growl.error({
                                        title: "Error",
                                        message: "Sorry !!!  We could not verify your POWRBANK right now. please try again later. Make sure your ECM is ON and have proper internet connectivity.",
                                        size: 'large',
                                        duration: 8000,
                                    });

                                    setTimeout(() => {
                                        $('#global-loader').hide();
                                        if(company_login_id)
                                        {
                                            var url  = "/company/"+company_login_id+"/system-overview";
                                            location = url;
                                        } else {
                                            location = "{{url('/system-overview')}}";
                                        }
                                    }, 8000);

                                    // location = "{{url('/system-overview')}}";

                                }, 15000);
                            }


                        } else if(data.status == 'exits'){
                            $.growl.error({
                                message: data.response_msg
                            });
                        }
                        else {
                            $.growl.error({
                                message: "Cannot saved."
                            });
                        }
                    }
                });
            }
        });

        function checkIsDeviceVerified(id) {
            if(id)
            {
                $.ajax({
                    url: '{{url("check-device-verified")}}',
                    type: "POST",
                    data:  {'id' : id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        try {
                            data = JSON.parse(data);
                        } catch(e){}
                        if(data.status == 'true')
                        {
                            $.growl.notice({
                                title: "Success",
                                message: "POWRBANK verified successfully."
                            });

                            setTimeout(() => {
                                if(company_login_id)
                                {
                                    var url  = "/company/"+company_login_id+"/system-overview";
                                    location = url;
                                } else {
                                    location = "{{url('/system-overview')}}";
                                }
                            }, 2000);

                        } else {
                            return 'false';
                        }
                    }
                });
            }
        }

        $(document).on('click', '.btn_cancle', function() {
            $('label.errorFrm').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();
            $('#frm_add')[0].reset();
        });

        // Google Map
        // var map = L.map('leaflet1').setView([51.505, -0.09], 13);
        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        // }).addTo(map);
        // L.marker([51.5, -0.09]).addTo(map)
        //     .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
        //     .openPopup();



    }); // End Ready
</script>
@endsection

