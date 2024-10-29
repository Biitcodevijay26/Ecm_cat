@extends('front.layout_admin.app')

@section('page_level_css')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 10px;
        }

        .appliances-grp .body {
            min-height: 90px;
        }

        .hideMe {
            display: none !important;
        }

        .nav-vertical .nav-left.nav-tabs {
            display: table;
            float: left;
            border-bottom: 0;
            border-radius: 0;
        }

        .nav-vertical .nav-left.nav-tabs li.nav-item {
            float: none;
            margin: 0 -1px 0 0;
        }

        .nav-vertical .nav-left.nav-tabs li.nav-item a.nav-link,
        .nav-vertical .nav-right.nav-tabs li.nav-item a.nav-link {
            /* min-width: 10%; */
        }

        .nav-vertical .nav-left~.tab-content {
            margin-left: 45px;
        }

        .nav-vertical .nav-left~.tab-content .tab-pane.active {
            display: block;
        }

        .nav-vertical .nav-left~.tab-content .tab-pane {
            display: none;
            overflow-y: auto;
            padding: 0 0 0.6rem 1rem;
        }

        .nav-item .fa {
            font-size: 25px;
            padding-right: 12px;
            color: #009847;
        }
    </style>
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-5 col-md-8 col-sm-12">
                        <h2><a href="javascript:void(0);" class="btn btn-link btn-toggle-fullwidth"><i
                                    class="fa fa-arrow-left"></i></a>{{ $title }}</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item">{{ $title }}</li>
                            <li class="breadcrumb-item active">{{ $title_sub }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-7 col-md-4 col-sm-12 text-right">

                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12">
                                    <h5>{{ $inverter->user->name ?? '' }}
                                        <small>&nbsp; | &nbsp; Control Card SN :
                                            <span class="badge badge-warning">{{ $inverter->control_card_no ?? '' }}</span>
                                        </small>
                                        <small>&nbsp; | &nbsp; Inverter SN :
                                            <span
                                                class="badge badge-warning">{{ $inverter->serial_no ?? '' }}</span></small>
                                        <small>&nbsp; | &nbsp; Site Name :
                                            <span class="badge badge-info">{{ $inverter->site_name ?? '' }}</span> </small>
                                    </h5>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="nav-vertical p-2">
                                                <ul class="nav nav-tabs nav-left nav-tabs-new222">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="baseVerticalLeft2-tab1"
                                                            data-toggle="tab" aria-controls="tabVerticalLeft21"
                                                            href="#tabVerticalLeft21" aria-expanded="true"><i
                                                                class="fa fa-power-off"></i> Inverter</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseVerticalLeft2-tab2" data-toggle="tab"
                                                            aria-controls="tabVerticalLeft22" href="#tabVerticalLeft22"
                                                            aria-expanded="false"><i class="fa fa-tachometer"></i> Meter</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseVerticalLeft2-tab3" data-toggle="tab"
                                                            aria-controls="tabVerticalLeft23" href="#tabVerticalLeft23"
                                                            aria-expanded="false"><i class="fa fa-road"></i> Grid</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseVerticalLeft2-tab4" data-toggle="tab"
                                                            aria-controls="tabVerticalLeft24" href="#tabVerticalLeft24"
                                                            aria-expanded="false"><i class="fa fa-battery-full"></i> Battery
                                                            Charger</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseVerticalLeft2-tab5" data-toggle="tab"
                                                            aria-controls="tabVerticalLeft25" href="#tabVerticalLeft25"
                                                            aria-expanded="false"><i class="fa fa-exchange"></i> Operating
                                                            Mode</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseVerticalLeft2-tab6" data-toggle="tab"
                                                            aria-controls="tabVerticalLeft26" href="#tabVerticalLeft26"
                                                            aria-expanded="false"><i class="fa fa-bars"></i> EPS System</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseVerticalLeft2-tab7" data-toggle="tab"
                                                            aria-controls="tabVerticalLeft27" href="#tabVerticalLeft27"
                                                            aria-expanded="false"><i class="fa fa-outdent"></i> Export
                                                            Control</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="baseVerticalLeft2-tab8" data-toggle="tab"
                                                            aria-controls="tabVerticalLeft28" href="#tabVerticalLeft28"
                                                            aria-expanded="false"><i class="fa fa-clock-o"></i> Date &
                                                            Time</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content px-1">
                                                    <div role="tabpanel" class="tab-pane active" id="tabVerticalLeft21"
                                                        aria-expanded="true" aria-labelledby="baseVerticalLeft2-tab1">
                                                        <div class="col-lg-12 col-md-12 text-center">
                                                            <div class="form-check pl-0">
                                                                <label for="inverterOnOff"
                                                                    class="form-check-label pr-4">ON / OFF</label>
                                                                <input id="inverterOnOff" class="form-check-input"
                                                                    type="checkbox" data-toggle="toggle"
                                                                    data-onstyle="success"
                                                                    {{ isset($setting['inverter_start_stop']) && $setting['inverter_start_stop'] == '1' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tabVerticalLeft22"
                                                        aria-labelledby="baseVerticalLeft2-tab2">
                                                        <div class="offset-lg-3 offset-md-3 col-lg-6 col-md-6 text-center">
                                                            <div class="blockquote blockquote-primary">
                                                                <div class="form-check pl-0 mb-3">
                                                                    <label for="meter_en_di"
                                                                        class="form-check-label pr-4">Meter
                                                                        Function</label>
                                                                    <input id="meter_en_di" class="form-check-input"
                                                                        type="checkbox" data-toggle="toggle"
                                                                        data-onstyle="success"
                                                                        {{ isset($setting['meter']['meter_en_di']) && $setting['meter']['meter_en_di'] == '1' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                            <div class="blockquote blockquote-primary text-left">
                                                                <p class="">Meter ID’s</p>
                                                                <hr>

                                                                <h6 class="">Meter 1ID</h6>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control"
                                                                        id="meter_1id" name="meter_1id" min="0"
                                                                        max="200" placeholder="Meter 1ID"
                                                                        value="{{ $setting['meter']['meter_1id'] ?? '0' }}"
                                                                        data-old="{{ $setting['meter']['meter_1id'] ?? '0' }}">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-success save_meter"
                                                                            type="button">Save</button>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">Meter 1ID must be 0 to 200</span>

                                                                <h6 class="mt-3">Meter 2ID</h6>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control"
                                                                        id="meter_2id" name="meter_2id" min="0"
                                                                        max="200" placeholder="Meter 2ID"
                                                                        value="{{ $setting['meter']['meter_2id'] ?? '0' }}"
                                                                        data-old="{{ $setting['meter']['meter_2id'] ?? '0' }}">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-success save_meter"
                                                                            type="button">Save</button>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">Meter 2ID must be 0 to 200</span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tabVerticalLeft23"
                                                        aria-labelledby="baseVerticalLeft2-tab3">

                                                        <div class="blockquote blockquote-primary text-left">
                                                            <p class="">VAC</p>
                                                            <hr>
                                                            <div class="row clearfix">
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <h6 class="">VAC Lower (MIN)</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="vac_min" name="vac_min"
                                                                            min="150" max="300"
                                                                            placeholder="VAC Lower (MIN)"
                                                                            value="{{ $setting['grid_vac']['vac_min']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_vac']['vac_min']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_vac"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">VAC Lower must be 150.0V to 300.0V</span>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <h6 class="">VAC Upper (MAX)</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="vac_max" name="vac_max"
                                                                            min="150" max="300"
                                                                            placeholder="VAC Upper (MAX)"
                                                                            value="{{ $setting['grid_vac']['vac_max']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_vac']['vac_max']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_vac"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">VAC Upper must be 150.0V to 300.0V</span>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                                                                    <h6 class="">VAC Lower (MIN) Slow</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="vac_min_slow" name="vac_min_slow"
                                                                            min="150" max="300"
                                                                            placeholder="VAC Lower (MIN) Slow"
                                                                            value="{{ $setting['grid_vac']['vac_min_slow']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_vac']['vac_min_slow']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_vac"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">VAC Lower Slow must be 150.0V to 300.0V</span>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                                                                    <h6 class="">VAC Upper (MAX) Slow</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="vac_max_slow" name="vac_max_slow"
                                                                            min="150" max="300"
                                                                            placeholder="VAC Upper (MAX) Slow"
                                                                            value="{{ $setting['grid_vac']['vac_max_slow']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_vac']['vac_max_slow']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_vac"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">VAC Upper Slow must be 150.0V to 300.0V</span>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="blockquote blockquote-primary text-left">
                                                            <p class="">FAC</p>
                                                            <hr>
                                                            <div class="row clearfix">
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <h6 class="">FAC Lower (MIN)</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="fac_min" name="fac_min"
                                                                            min="40" max="65"
                                                                            placeholder="FAC Lower (MIN)"
                                                                            value="{{ $setting['grid_fac']['fac_min']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_fac']['fac_min']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_fac"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">FAC Lower must be 40.00Hz to 65.00Hz</span>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <h6 class="">FAC Upper (MAX)</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="fac_max" name="fac_max"
                                                                            min="40" max="65"
                                                                            placeholder="FAC Upper (MAX)"
                                                                            value="{{ $setting['grid_fac']['fac_max']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_fac']['fac_max']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_fac"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">FAC Upper must be 40.00Hz to 65.00Hz</span>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                                                                    <h6 class="">FAC Lower (MIN) Slow</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="fac_min_slow" name="fac_min_slow"
                                                                            min="40" max="65"
                                                                            placeholder="FAC Lower (MIN) Slow"
                                                                            value="{{ $setting['grid_fac']['fac_min_slow']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_fac']['fac_min_slow']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_fac"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">FAC Lower Slow must be 40.00Hz to 65.00Hz</span>
                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                                                                    <h6 class="">FAC Upper (MAX) Slow</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="fac_max_slow" name="fac_max_slow"
                                                                            min="40" max="65"
                                                                            placeholder="FAC Upper (MAX) Slow"
                                                                            value="{{ $setting['grid_fac']['fac_max_slow']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_fac']['fac_max_slow']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_fac"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">FAC Upper Slow must be 40.00Hz to 65.00Hz</span>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="blockquote blockquote-primary text-left">
                                                            <div class="row clearfix">
                                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                                    <h6 class="">Grid 10 Min High</h6>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" step="0.1"
                                                                            id="grid_10min_high" name="grid_10min_high"
                                                                            min="150" max="300"
                                                                            placeholder="Grid 10 Min High"
                                                                            value="{{ $setting['grid_10min_high']['value'] ?? '0' }}"
                                                                            data-old="{{ $setting['grid_10min_high']['value'] ?? '0' }}">
                                                                        <div class="input-group-append">
                                                                            <button
                                                                                class="btn btn-outline-success save_grid_10min_high"
                                                                                type="button">Save</button>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block">Grid 10 Min High must be 150.0V to 300.0V</span>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="tab-pane" id="tabVerticalLeft24"
                                                        aria-labelledby="baseVerticalLeft2-tab4">
                                                        <div class="offset-lg-3 offset-md-3 col-lg-6 col-md-6 text-center">
                                                            
                                                            <div class="blockquote blockquote-primary text-left">
                                                                <p class="">Battery Setting</p>
                                                                <hr>

                                                                <h6 class="">Battery Min Capacity</h6>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control"
                                                                        id="battery_min_capcity" name="battery_min_capcity" min="0"
                                                                        max="100" placeholder="Battery Min Capacity"
                                                                        value="{{ $setting['battery_min_capcity']['value'] ?? '0' }}"
                                                                        data-old="{{ $setting['battery_min_capcity']['value'] ?? '0' }}">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-success save_battery_min_cap"
                                                                            type="button">Save</button>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">Battery Min Capacity must be 0% to 100%</span>

                                                                <h6 class="mt-3">Battery Charge Max Current</h6>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control"
                                                                        id="battery_charge_max_current" name="battery_charge_max_current" min="0"
                                                                        max="25" placeholder="Battery Charge Max Current"
                                                                        value="{{ $setting['battery_charge_max_current']['value'] ?? '0' }}"
                                                                        data-old="{{ $setting['battery_charge_max_current']['value'] ?? '0' }}">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-success save_battery_charge_dischrg_max"
                                                                            type="button">Save</button>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">Battery Charge Max Current must be 0.0A to 25.0A</span>

                                                                <h6 class="mt-3">Battery Discharge Max Current</h6>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control"
                                                                        id="battery_discharge_max_current" name="battery_discharge_max_current" min="0"
                                                                        max="25" placeholder="Battery Discharge Max Current"
                                                                        value="{{ $setting['battery_discharge_max_current']['value'] ?? '0' }}"
                                                                        data-old="{{ $setting['battery_discharge_max_current']['value'] ?? '0' }}">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-success save_battery_charge_dischrg_max"
                                                                            type="button">Save</button>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">Battery Discharge Max Current must be 0.0A to 25.0A</span>

                                                            </div>
                                                            <div class="blockquote blockquote-primary">
                                                                <div class="form-check pl-0 mb-3">
                                                                    <label for="grid_tie_limit_en_di"
                                                                        class="form-check-label pr-4">Grid Tied Min Limit</label>
                                                                    <input id="grid_tie_limit_en_di" class="form-check-input"
                                                                        type="checkbox" data-toggle="toggle"
                                                                        data-onstyle="success"
                                                                        {{ isset($setting['grid_tie_limit_en_di']) && $setting['grid_tie_limit_en_di'] == '1' ? 'checked' : '' }}>
                                                                </div>
                                                            </div>
                                                            <div class="blockquote blockquote-primary text-left {{ isset($setting['grid_tie_limit_en_di']) && $setting['grid_tie_limit_en_di'] == '1' ? '' : 'hideMe' }}">
                                                                <p class="">Grid Discharge Capacity</p>
                                                                <hr>

                                                                <h6 class="">Grid Discharge Capacity</h6>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control"
                                                                        id="discharge_min_capcity" name="discharge_min_capcity" min="10"
                                                                        max="100" placeholder="Battery Min Capacity"
                                                                        value="{{ $setting['discharge_min_capcity']['value'] ?? '0' }}"
                                                                        data-old="{{ $setting['discharge_min_capcity']['value'] ?? '0' }}">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-success save_discharge_min_cap"
                                                                            type="button">Save</button>
                                                                    </div>
                                                                </div>
                                                                <span class="help-block">Grid Discharge Capacity must be 10% to 100%</span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Set Pin --}}
    <div class="modal fade" id="largeModal2" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" style="z-index: 9999;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel2">Set 4 Digit PIN</h4>
                    <p>This 4 Digit PIN is required
                        to access your Inverter Settings. </p>
                </div>
                <div class="modal-body" id="largeModalBody2">
                    <form id="frm_add">
                        <div class="offset-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="pin1" class="control-label">Choose a PIN of your choice</label>
                                <input type="password" name="pin1" id="pin1" class="form-control">
                            </div>
                        </div>

                        <div class="offset-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="pin2" class="control-label">Reenter a PIN</label>
                                <input type="password" name="pin2" id="pin2" class="form-control">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn_save">Save</button>
                    {{-- <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Verify Pin --}}
    <div class="modal fade" id="largeModal1" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" style="z-index: 9999;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel1">Enter a 4 Digit PIN</h4>
                    <p>This Password will be used as security measure
                        to prevent unauthorized access. </p>
                </div>
                <div class="modal-body" id="largeModalBody1">
                    <form id="frm_verify">
                        <div class="offset-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="pin_verify" class="control-label">Enter PIN </label>
                                <input type="password" name="pin_verify" id="pin_verify" class="form-control">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn_verify">Verify</button>
                    {{-- <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_level_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    {{-- <script src="{{ url('app-assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js') }}"></script> 
    <script src="{{ url('app-assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script> --}}
    <script type="text/javascript">
        const control_card_no = "{{ $inverter->control_card_no ?? '' }}";
        const myPin = "{{ cache('myPin') }}";
        const userpin = "{{ $userpin }}";
        const user_id = "{{ $inverter->user_id_str ?? '' }}";
        const inverter_id = "{{ $inverter->id ?? '' }}";

        if (!userpin) {
            $('#largeModal2').modal('show');
        } else if (!myPin) {
            $('#largeModal1').modal('show');
        }

        $(document).ready(function() {
            // $('#pin1').mask('9999');
            // $('#pin2').mask('9999');
            $('#frm_add').validate({
                ignore: "",
                //errorElement: 'div',
                //errorClass: "invalid-feedback",
                rules: {
                    pin1: {
                        required: true,
                        number: true,
                        minlength: 4,
                        maxlength: 4
                    },
                    pin2: {
                        required: true,
                        number: true,
                        minlength: 4,
                        maxlength: 4,
                        equalTo: "#pin1"
                    }

                },
                messages: {

                },
                submitHandler: function(form) {
                    // return true;
                }

            });

            $('#frm_verify').validate({
                ignore: "",
                //errorElement: 'div',
                //errorClass: "invalid-feedback",
                rules: {
                    pin_verify: {
                        required: true,
                        number: true,
                        minlength: 4,
                        maxlength: 4
                    },

                },
                messages: {

                },
                submitHandler: function(form) {
                    // return true;
                }

            });


            $(document).on('click', '.btn_save', function() {
                $('label.errorFrm').remove();
                $('label.success_msg').remove();
                $('.alert-outline-success').remove();
                var _this = $(this);
                if ($("#frm_add").valid()) {
                    _this.prop('disabled', true).text('Processing...');
                    $.ajax({
                        url: '{{ url('admin/save-user-pin') }}',
                        type: "POST",
                        data: $('#frm_add').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            _this.prop('disabled', false).text('Save');
                            try {
                                data = JSON.parse(data);
                            } catch (e) {}
                            if (data.success) {
                                toastr.success("Succesfully saved.", "Success");
                                setTimeout(function() {
                                    location.reload();
                                }, 500);
                            } else if (!data.success) {
                                var errMsg = data.message;
                                toastr.error("", "Error");

                                $.each(errMsg, function(field_name, error) {
                                    toastr.error(error, "Error");
                                    //$(document).find('[name='+field_name+']').after('<label class="errorFrm">'+error+'</label>');

                                })
                            } else {
                                toastr.success("Cannot save.", "Error");
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.btn_verify', function() {
                $('label.errorFrm').remove();
                $('label.success_msg').remove();
                $('.alert-outline-success').remove();
                var _this = $(this);
                if ($("#frm_verify").valid()) {
                    _this.prop('disabled', true).text('Processing...');
                    $.ajax({
                        url: '{{ url('admin/verify-pin') }}',
                        type: "POST",
                        data: $('#frm_verify').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            _this.prop('disabled', false).text('Verify');
                            try {
                                data = JSON.parse(data);
                            } catch (e) {}
                            if (data.success == true) {
                                toastr.success(data.response_msg, "Success");
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else if (data.success == false) {
                                toastr.error(data.response_msg, "Warning");
                            } else if (!data.success) {
                                var errMsg = data.message;
                                toastr.error("", "Error");

                                $.each(errMsg, function(field_name, error) {
                                    toastr.error(error, "Error");
                                    //$(document).find('[name='+field_name+']').after('<label class="errorFrm">'+error+'</label>');

                                })
                            } else {
                                toastr.success("Cannot save.", "Error");
                            }
                        }
                    });
                }
            });

            // Inverter ON/OFF
            $('#inverterOnOff').change(function() {
                var data = {};
                data.inverter_start_stop = $(this).prop('checked') ? 1 : 0;
                data.setting_save_type = 'inverter_start_stop';
                saveSetting(data);
            });

            // Meter Function ON/OFF
            $('#meter_en_di').change(function() {
                var _t = $(this);
                var data = {};
                data.meter_en_di = _t.prop('checked') ? 1 : 0;
                data.setting_save_type = 'meter';
                if (data.meter_en_di == 1) {
                    var validateInput = validateMeterData();
                    if (validateInput) {
                        data.meter_1id = parseInt($('#meter_1id').val().trim());
                        data.meter_2id = parseInt($('#meter_2id').val().trim());
                        saveSetting(data);
                    } else {
                        data.meter_en_di == 1 ? _t.bootstrapToggle('off', true) : _t.bootstrapToggle('on',
                            true);
                    }
                } else {
                    var validateInput = validateMeterData();
                    if (validateInput) {
                        data.meter_1id = parseInt($('#meter_1id').val().trim());
                        data.meter_2id = parseInt($('#meter_2id').val().trim());
                    } else {
                        data.meter_1id = parseInt($('#meter_1id').attr('data-old').trim());
                        data.meter_2id = parseInt($('#meter_2id').attr('data-old').trim());
                    }
                    saveSetting(data);
                }
            });

            // meter input save
            $('.save_meter').click(function() {
                var _t = $(this);
                var data = {};
                data.meter_en_di = _t.prop('checked') ? 1 : 0;
                data.setting_save_type = 'meter';
                var validateInput = validateMeterData();
                if (validateInput) {
                    data.meter_1id = parseInt($('#meter_1id').val().trim());
                    data.meter_2id = parseInt($('#meter_2id').val().trim());
                    saveSetting(data);
                }
            });

            function validateMeterData() {
                var meterValid = true;
                var meter_1id = parseInt($('#meter_1id').val().trim());
                var meter_2id = parseInt($('#meter_2id').val().trim());
                if (!meter_1id || meter_1id < 0 || meter_1id > 200) {
                    toastr.error("Invalid Meter 1ID.", "Error");
                    return false;
                    meterValid = false;
                }
                if (!meter_2id || meter_2id < 0 || meter_2id > 200) {
                    toastr.error("Invalid Meter 2ID.", "Error");
                    return false;
                    meterValid = false;
                }
                return meterValid;
            }

            // VAC input save
            $('.save_vac').click(function() {
                var _t = $(this);
                var data = {};
                data.setting_save_type = 'grid';
                var validVac = validateGridVAC();
                var validFac = validateGridFAC();
                var validGrid10MinHigh = validateGrid10MinHigh();
                if (validVac) {
                    data.vac_min = parseFloat($('#vac_min').val().trim());
                    data.vac_max = parseFloat($('#vac_max').val().trim());
                    data.vac_min_slow = parseFloat($('#vac_min_slow').val().trim());
                    data.vac_max_slow = parseFloat($('#vac_max_slow').val().trim());
                } else {
                    return false;
                }

                if (validFac) {
                    data.fac_min = parseFloat($('#fac_min').val().trim());
                    data.fac_max = parseFloat($('#fac_max').val().trim());
                    data.fac_min_slow = parseFloat($('#fac_min_slow').val().trim());
                    data.fac_max_slow = parseFloat($('#fac_max_slow').val().trim());
                } else {
                    data.fac_min = parseFloat($('#fac_min').attr('data-old').trim());
                    data.fac_max = parseFloat($('#fac_max').attr('data-old').trim());
                    data.fac_min_slow = parseFloat($('#fac_min_slow').attr('data-old').trim());
                    data.fac_max_slow = parseFloat($('#fac_max_slow').attr('data-old').trim());
                }

                if (validGrid10MinHigh) {
                    data.grid_10min_high = parseFloat($('#grid_10min_high').val().trim());
                } else {
                    data.grid_10min_high = parseFloat($('#grid_10min_high').attr('data-old').trim());
                }

                saveSetting(data);
            });

            // FAC input save
            $('.save_fac').click(function() {
                var _t = $(this);
                var data = {};
                data.setting_save_type = 'grid';
                var validFac = validateGridFAC();
                var validVac = validateGridVAC();
                var validGrid10MinHigh = validateGrid10MinHigh();

                if (validFac) {
                    data.fac_min = parseFloat($('#fac_min').val().trim());
                    data.fac_max = parseFloat($('#fac_max').val().trim());
                    data.fac_min_slow = parseFloat($('#fac_min_slow').val().trim());
                    data.fac_max_slow = parseFloat($('#fac_max_slow').val().trim());
                } else {
                    return false;
                }

                if (validVac) {
                    data.vac_min = parseFloat($('#vac_min').val().trim());
                    data.vac_max = parseFloat($('#vac_max').val().trim());
                    data.vac_min_slow = parseFloat($('#vac_min_slow').val().trim());
                    data.vac_max_slow = parseFloat($('#vac_max_slow').val().trim());
                } else {
                    data.vac_min = parseFloat($('#vac_min').attr('data-old').trim());
                    data.vac_max = parseFloat($('#vac_max').attr('data-old').trim());
                    data.vac_min_slow = parseFloat($('#vac_min_slow').attr('data-old').trim());
                    data.vac_max_slow = parseFloat($('#vac_max_slow').attr('data-old').trim());
                }

                if (validGrid10MinHigh) {
                    data.grid_10min_high = parseFloat($('#grid_10min_high').val().trim());
                } else {
                    data.grid_10min_high = parseFloat($('#grid_10min_high').attr('data-old').trim());
                }

                saveSetting(data);
            });

            // save_grid_10min_high input save
            $('.save_grid_10min_high').click(function() {
                var _t = $(this);
                var data = {};
                data.setting_save_type = 'grid';
                var validGrid10MinHigh = validateGrid10MinHigh();
                var validFac = validateGridFAC();
                var validVac = validateGridVAC();

                if (validGrid10MinHigh) {
                    data.grid_10min_high = parseFloat($('#grid_10min_high').val().trim());
                } else {
                    return false;
                }

                if (validFac) {
                    data.fac_min = parseFloat($('#fac_min').val().trim());
                    data.fac_max = parseFloat($('#fac_max').val().trim());
                    data.fac_min_slow = parseFloat($('#fac_min_slow').val().trim());
                    data.fac_max_slow = parseFloat($('#fac_max_slow').val().trim());
                } else {
                    data.fac_min = parseFloat($('#fac_min').attr('data-old').trim());
                    data.fac_max = parseFloat($('#fac_max').attr('data-old').trim());
                    data.fac_min_slow = parseFloat($('#fac_min_slow').attr('data-old').trim());
                    data.fac_max_slow = parseFloat($('#fac_max_slow').attr('data-old').trim());
                }

                if (validVac) {
                    data.vac_min = parseFloat($('#vac_min').val().trim());
                    data.vac_max = parseFloat($('#vac_max').val().trim());
                    data.vac_min_slow = parseFloat($('#vac_min_slow').val().trim());
                    data.vac_max_slow = parseFloat($('#vac_max_slow').val().trim());
                } else {
                    data.vac_min = parseFloat($('#vac_min').attr('data-old').trim());
                    data.vac_max = parseFloat($('#vac_max').attr('data-old').trim());
                    data.vac_min_slow = parseFloat($('#vac_min_slow').attr('data-old').trim());
                    data.vac_max_slow = parseFloat($('#vac_max_slow').attr('data-old').trim());
                }

                saveSetting(data);
            });

            function validateGridVAC() {
                var valid = true;
                var vac_min = parseFloat($('#vac_min').val().trim());
                var vac_max = parseFloat($('#vac_max').val().trim());
                var vac_min_slow = parseFloat($('#vac_min_slow').val().trim());
                var vac_max_slow = parseFloat($('#vac_max_slow').val().trim());

                if (!vac_min || vac_min < 150 || vac_min > 300) {
                    toastr.error("Invalid VAC Lower (MIN).", "Error");
                    return false;
                    valid = false;
                }
                if (!vac_max || vac_max < 150 || vac_max > 300) {
                    toastr.error("Invalid VAC Upper (MAX).", "Error");
                    return false;
                    valid = false;
                }
                if (!vac_min_slow || vac_min_slow < 150 || vac_min_slow > 300) {
                    toastr.error("Invalid VAC Lower (MIN) Slow.", "Error");
                    return false;
                    valid = false;
                }
                if (!vac_max_slow || vac_max_slow < 150 || vac_max_slow > 300) {
                    toastr.error("Invalid VAC Upper (MAX) Slow.", "Error");
                    return false;
                    valid = false;
                }
                return valid;
            }
            function validateGridFAC() {
                var valid = true;
                var fac_min = parseFloat($('#fac_min').val().trim());
                var fac_max = parseFloat($('#fac_max').val().trim());
                var fac_min_slow = parseFloat($('#fac_min_slow').val().trim());
                var fac_max_slow = parseFloat($('#fac_max_slow').val().trim());

                if (!fac_min || fac_min < 40 || fac_min > 65) {
                    toastr.error("Invalid FAC Lower (MIN).", "Error");
                    return false;
                    valid = false;
                }
                if (!fac_max || fac_max < 40 || fac_max > 65) {
                    toastr.error("Invalid FAC Upper (MAX).", "Error");
                    return false;
                    valid = false;
                }
                if (!fac_min_slow || fac_min_slow < 40 || fac_min_slow > 65) {
                    toastr.error("Invalid FAC Lower (MIN) Slow.", "Error");
                    return false;
                    valid = false;
                }
                if (!fac_max_slow || fac_max_slow < 40 || fac_max_slow > 65) {
                    toastr.error("Invalid FAC Upper (MAX) Slow.", "Error");
                    return false;
                    valid = false;
                }
                return valid;
            }
            function validateGrid10MinHigh() {
                var valid = true;
                var grid_10min_high = parseFloat($('#grid_10min_high').val().trim());
                if (!grid_10min_high || grid_10min_high < 150 || grid_10min_high > 300) {
                    toastr.error("Invalid Grid 10 Min High.", "Error");
                    return false;
                    valid = false;
                }
                return valid;
            }

            // Save Battery Min Capacity
            $('.save_battery_min_cap').click(function() {
                var _t = $(this);
                var data = {};
                data.setting_save_type = 'battery_min_capcity';

                var battery_min_capcity = parseInt($('#battery_min_capcity').val().trim());
                if (!battery_min_capcity || battery_min_capcity < 0 || battery_min_capcity > 100) {
                    toastr.error("Invalid Battery Min Capacity.", "Error");
                    return false;
                } else {
                    data.battery_min_capcity = battery_min_capcity;
                    saveSetting(data);
                }
            });

            // Save Battery Charge/Discharge Max Current
            $('.save_battery_charge_dischrg_max').click(function() {
                var _t = $(this);
                var data = {};
                data.setting_save_type = 'battery_charge_discharge_max';

                var battery_charge_max_current = parseFloat($('#battery_charge_max_current').val().trim());
                var battery_discharge_max_current = parseFloat($('#battery_discharge_max_current').val().trim());
                if (!battery_charge_max_current || battery_charge_max_current < 0 || battery_charge_max_current > 25) {
                    toastr.error("Invalid Battery Charge Max Current.", "Error");
                    return false;
                } else if (!battery_discharge_max_current || battery_discharge_max_current < 0 || battery_discharge_max_current > 25) {
                    toastr.error("Invalid Battery Discharge Max Current.", "Error");
                    return false;
                } else {
                    data.battery_charge_max_current = battery_charge_max_current;
                    data.battery_discharge_max_current = battery_discharge_max_current;
                    saveSetting(data);
                }
            });

            function saveSetting(data) {
                data.user_id = user_id;
                data.inverter_id = inverter_id;
                $.ajax({
                    url: '{{ url('admin/save-inverter-setting') }}',
                    type: "POST",
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        try {
                            data = JSON.parse(data);
                        } catch (e) {}
                        if (data.status == true) {
                            toastr.success("Succesfully saved.", "Success");
                        } else if (data.status == false) {
                            toastr.error(data.message, "Error");
                        } else if (!data.success) {
                            var errMsg = data.message;
                            toastr.error("", "Error");

                            $.each(errMsg, function(field_name, error) {
                                toastr.error(error, "Error");
                                //$(document).find('[name='+field_name+']').after('<label class="errorFrm">'+error+'</label>');

                            })
                        } else {
                            toastr.success("Cannot save.", "Error");
                        }
                    }
                });
            }

        });
    </script>
@endsection
