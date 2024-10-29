<?php
    $company_login_id = session()->get('company_login_id');
    $groups = getClusters();

    $activeGeneratorIcon = [];
    $activeACSolarIcon   = [];
    $activeGridIcon      = [];
    $activeDCSolarIcon   = [];
    $activeUnitIcon      = [];
    $activeViewDetails   = [];
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeGeneratorIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Generator';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeACSolarIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'AC Solar';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeGridIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Grid';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeDCSolarIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'DC Solar';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeUnitIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Unit';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeViewDetails = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'PowerBank Details';
        }));
    }

    $activeGeneratorIcon = isset($activeGeneratorIcon[0]) ? $activeGeneratorIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/charging.svg');
    $activeACSolarIcon   = isset($activeACSolarIcon[0]) ? $activeACSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/acsolar.svg');
    $activeGridIcon      = isset($activeGridIcon[0]) ? $activeGridIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/tower.svg');
    $activeDCSolarIcon   = isset($activeDCSolarIcon[0]) ? $activeDCSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/dcsolar.svg');
    $activeUnitIcon      = isset($activeUnitIcon[0]) ? $activeUnitIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/unit.svg');
    $activeViewDetails   = isset($activeViewDetails[0]) ? $activeViewDetails[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/powerbank-details.svg');
?>
@if ($device_list)
    <?php $ctn = 0; ?>
    @foreach ($device_list as $device)
        <div class="col-md-6 device_found">
            <div class="card {{ $device->is_verified == 1 ? 'card-border-black' : 'card-border-black'}}">
                <div class="card-header {{ $device->is_verified == 1 ? 'bg-color-black' : 'bg-color-black'}}">
                    <a href="javascript:void(0);" class="card-options-collapse text-white" data-bs-toggle="card-collapse">
                        <h4 class="card-title-overview-page text-white"> {{$device->name ?? ''}} </h4>
                    </a>

                    <div class="card-options">
                        <h3 class="card-title text-white pt-1"> Verified : {{ $device->is_verified == 1 ? 'YES' : 'NO'}} </h3>
                        <a href="javascript:void(0);" class="card-options-collapse text-white" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="card card-border-warning card-border-radius device-card justify-content-center">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                            <img src="{{ $activeACSolarIcon }}" alt="img" class="list-acsolar-img">
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-4">
                                            <h5 class="card-kw-number-1 custom-color-warning mb-0">AC Solar</h5>
                                            <?php
                                            if (isset($device->system_calculated['data']) && $device->system_calculated['data']['data']) {
                                                $AC_Solar_Tot_Energy = $device->system_calculated['data']['data']['AC_Solar_Tot_Energy(Wh)'] ?? 0;
                                                $Gen_Tot_Pow = $device->system_calculated['data']['data']['Gen_Tot_Pow(W)'] ?? 0;
                                            }
                                            ?>
                                            <h3 class="card-kw-number-1 mb-0">  <?= (isset($AC_Solar_Tot_Energy) && !empty($AC_Solar_Tot_Energy)) ? unitConverter($AC_Solar_Tot_Energy) : "0 " ?>Wh</h3>
                                            <h3 class="card-kw-number-2 mb-0">  <?= (isset($Gen_Tot_Pow) && !empty($Gen_Tot_Pow)) ? unitConverter($Gen_Tot_Pow) : "0 " ?>W</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="card card-border-black card-border-radius device-card justify-content-center">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                            <img src="{{ $activeGridIcon }}" alt="img" class="list-grid-img">
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-4">
                                            <h5 class="card-kw-number-1 custom-color-black mb-0">Grid</h5>
                                            <?php
                                            $Grid_Tot_Energy = 0;
                                            if (isset($device->system_calculated['data']) && $device->system_calculated['data']['data']) {
                                                $Grid_Tot_Energy =  (isset($device->system_calculated['data']['data']['Grid_Tot_Energy(Wh)']) && $device->system_calculated['data']['data']['Grid_Tot_Energy(Wh)'] ? $device->system_calculated['data']['data']['Grid_Tot_Energy(Wh)'] : '');
                                                if($Grid_Tot_Energy)
                                                {
                                                    $Grid_Tot_Energy = $Grid_Tot_Energy;
                                                }
                                                $Gen_Tot_Pow = $device->system_calculated['data']['data']['Gen_Tot_Pow(W)'] ?? 0;
                                            }
                                            if (isset($device->sub_system_calculated['data']) && $device->sub_system_calculated['data']['data']) {
                                                $Grid_Tot_Energy = (isset($device->sub_system_calculated['data']['data']['Grid_Tot_Energy(Wh)']) && $device->sub_system_calculated['data']['data']['Grid_Tot_Energy(Wh)'] ? $device->sub_system_calculated['data']['data']['Grid_Tot_Energy(Wh)'] : '');
                                                if($Grid_Tot_Energy)
                                                {
                                                    $Grid_Tot_Energy = $Grid_Tot_Energy;
                                                }
                                                $Grid_Tot_Pow = $device->sub_system_calculated['data']['data']['Grid_Tot_Pow(W)'] ?? 0;
                                            }
                                            ?>
                                            <h3 class="card-kw-number-1 mb-0"> <?= (isset($Grid_Tot_Energy) && !empty($Grid_Tot_Energy)) ? unitConverter($Grid_Tot_Energy) : "0 " ?>Wh</h3>
                                            <h3 class="card-kw-number-2 mb-0"> <?= (isset($Grid_Tot_Pow) && !empty($Grid_Tot_Pow)) ? unitConverter($Grid_Tot_Pow) : "0 " ?>W</h3>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row mt-4">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="card card-border-danger card-border-radius device-card justify-content-center">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                            <img src="{{ $activeDCSolarIcon }}" alt="img" class="list-dcsolar-img">
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-4">
                                            <h5 class="card-kw-number-1 custom-color-danger  mb-0">DC Solar</h5>
                                            <?php
                                                if (isset($device->system_calculated['data']) && $device->system_calculated['data']['data']) {
                                                    $DC_Solar_Energy    = $device->system_calculated['data']['data']['DC_Solar_Energy(Wh)'] ?? 0;
                                                    $DC_Solar_Power     = $device->system_calculated['data']['data']['DC_Solar_Power(W)'] ?? 0;
                                                }
                                            ?>
                                            <h3 class="card-kw-number-1 mb-0"> <?= (isset($DC_Solar_Energy) && !empty($DC_Solar_Energy)) ? unitConverter($DC_Solar_Energy) : "0" ?>Wh</h3>
                                            <h5 class="card-kw-number-2 mb-0"> <?= (isset($DC_Solar_Power) && !empty($DC_Solar_Power)) ? unitConverter($DC_Solar_Power) : "0" ?>W</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="card card-border-primary card-border-radius device-card justify-content-center">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                            {{-- <img src="{{ url('theme-asset/images/charging.png') }}" alt="img" class="card-img-absolute"> --}}
                                            <img src="{{ $activeGeneratorIcon }}" alt="img" class="list-generator-img">
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-4">
                                            <h5 class="card-kw-number-1 custom-color-primary mb-0">Generator</h5>
                                            <?php
                                                if (isset($device->system_calculated['data']) && $device->system_calculated['data']['data']) {
                                                    $Gen_Tot_Energy    = $device->system_calculated['data']['data']['Gen_Tot_Energy(Wh)'] ?? 0;
                                                    $Gen_Tot_Pow     = $device->system_calculated['data']['data']['Gen_Tot_Pow(W)'] ?? 0;
                                                }
                                            ?>
                                            <h3 class="card-kw-number-1 mb-0"> <?= (isset($Gen_Tot_Energy) && !empty($Gen_Tot_Energy)) ? unitConverter($Gen_Tot_Energy) : "0 " ?>Wh</h3>
                                            <h5 class="card-kw-number-2 mb-0"> <?= (isset($Gen_Tot_Pow) && !empty($Gen_Tot_Pow)) ? unitConverter($Gen_Tot_Pow) : "0 " ?>W</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row row mt-4">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="card card-border-unit card-border-radius device-card justify-content-center">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="circle-icon text-center align-self-center box-primary-shadow">
                                            <img src="{{ $activeUnitIcon }}" alt="img" class="list-unit-img">
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-4">
                                            <h5 class="card-kw-number-1 custom-color-unit mb-0">Unit</h5>
                                            <?php
                                                if (isset($device->battery_data['data']) && $device->battery_data['data']['data']) {
                                                    $SOC    = $device->battery_data['data']['data']['Status']['SOC(%)'] ?? 0;
                                                    $SOH    = $device->battery_data['data']['data']['Status']['SOH(%)'] ?? 0;
                                                }
                                            ?>
                                             <h3 class="card-kw-number-1 mb-0"> <?= (isset($SOC) && !empty($SOC)) ? unitConverter($SOC) : "0 " ?>(%) SOC </h3>
                                             <h5 class="card-kw-number-2 mb-0"> <?= (isset($SOH) && !empty($SOH)) ? unitConverter($SOH) : "0 " ?>(%) SOH </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="card card-border-success card-border-radius device-card justify-content-center">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="circle-icon text-center align-self-center box-primary-shadow">
                                            <img src="{{ $activeViewDetails }}" alt="img" class="card-img-absolute list-view-details-img">
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-4">
                                            <h5 class="card-kw-number-1 custom-color-success mb-0">POWRBANK Details</h5>
                                            <?php if ($company_login_id) : ?>
                                            <a href="{{ url('/company/'.$company_login_id.'/device_details/'.$device->id) }}" class="btn btn-primary mt-2 bg-color-black">View More <i class="fa fa-angle-double-right"></i></a>
                                            <?php else : ?>
                                            <a href="{{ url('/device_details/'.$device->id) }}" class="btn btn-primary mt-2 bg-color-black">View More <i class="fa fa-angle-double-right"></i></a>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-3 mt-2 text-xl-end">
                            <label for="assign_to_group">Assign To:</label>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-9 p-0">
                            <div class="form-group">
                                <select class="form-select select2 form-control assign_to_group" name="assign_to_group" data-device-id="{{$device->id ?? ''}}">
                                    <option value="">Select Group</option>
                                    <?php
                                        if($groups && count($groups) > 0) :
                                        foreach ($groups as $key => $grp) :
                                    ?>
                                    <option value="{{$grp->id ?? ''}}">{{$grp->name ?? ''}}</option>
                                    <?php endforeach; endif;  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            if(isset($card_colors[$ctn]) && $card_colors[$ctn])
            {
                $ctn++;
            } else {
                $ctn = 0;
            }
        ?>
    @endforeach
    @endif
