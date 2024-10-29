@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
@endsection
@section('content')
<?php
 $company_login_id = session()->get('company_login_id');
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
                                <input type="hidden" name="id" id="id" value="">
                                <input type="hidden" name="lat" id="lat" value="">
                                <input type="hidden" name="long" id="long" value="">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="">
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
                                                $status = 1;
                                            }
                                            ?>
                                            <label class="custom-switch">
                                                <input type="checkbox" name="status" id="status" value="1" class="custom-switch-input chk-status" {{ $status == 1 ? 'checked' : ''}}>
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="map_address">Address <span class="text-danger"> *</span> </label>
                                            <input type="text" class="form-control" id="map_address"  name="map_address" placeholder="Search address">
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <!-- <div class="ht-300" id="leaflet1" style="height:300px"></div> -->
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
<!-- <script src="{{ url('theme-asset/plugins/leaflet/leaflet.js') }}"></script> -->
{{-- <script src="{{ url('theme-asset/js/map-leafleft.js') }}"></script> --}}


<script>
    var geocoder = '';
    function initMap() {
        const myLatLng = { lat: 51.1657, lng: 10.4515 };
        geocoder = new google.maps.Geocoder();
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: myLatLng,
        });
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            draggable: true
        });
        var input = document.getElementById('map_address');
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
            // document.getElementById("map_address").value = place.name;
        });

    }
    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                $('#map_address').val('');
                $('#map_address').val(responses[0].formatted_address);
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
         // Validation Users
         $('#frm_add').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                name: {
                    required: true,
                },
                map_address: {
                    required: true,
                },
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
                    url: '{{url("save-cluster")}}',
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
                            // location = "{{url('/system-overview')}}";
                        } else {
                            $.growl.error({
                                message: "Cannot saved."
                            });
                        }
                    }
                });
            }
        });

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
    });
</script>
@endsection

