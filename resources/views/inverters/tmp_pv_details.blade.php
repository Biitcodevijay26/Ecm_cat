<div class="card top_counter">
    <div class="body">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6">
                <div class="body text-center pb-0 pt-0">
                    {{-- <i class="wi wi-day-sunny h1 text-warning"></i> --}}
                    <img src="{{ asset('/app-assets/images/icon/Group125.svg') }}" >
                    <h5 class="mt-0 total_pv_power text-warning"> {{$data['data']['total_pv_power']['value'] ?? ''}} {{$data['data']['total_pv_power']['unit'] ?? ''}}</h5>
                    <p>Total PV Power</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center"">
                <div class="body text-center pb-0 pt-0">
                    <div class="content">
                        <h5 class="number pv_energy_today">{{ number_format($data['data']['pv_energy_today']['value'], 2, '.', ',') ?? ''}} {{$data['data']['pv_energy_today']['unit'] ?? ''}}</h5>
                        <div class="text">Today Energy</div>
                    </div>
                    <div class="content pt-2">
                        <h5 class="number pv_energy_total">{{ number_format($data['data']['pv_energy_total']['value'], 2, '.', ',') ?? ''}} {{$data['data']['pv_energy_total']['unit'] ?? ''}}</h5>
                        <div class="text">Total Energy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>PV Details</h2>
            </div>
            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Inverter</th>
                            <th>Voltage</th>
                            <th>Current</th>
                            <th>Power</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">PV1</th>
                            <td>{{number_format($data['data']['pv1_voltage']['value'], 2, '.', ',') ?? ''}} {{$data['data']['pv1_voltage']['unit'] ?? ''}}</td>
                            <td>{{number_format($data['data']['pv1_current']['value'], 2, '.', ',') ?? ''}} {{$data['data']['pv1_current']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['pv1_power']['value'] ?? ''}} {{$data['data']['pv1_power']['unit'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <th scope="row">PV2</th>
                            <td>{{number_format($data['data']['pv2_voltage']['value'], 2, '.', ',') ?? ''}} {{$data['data']['pv2_voltage']['unit'] ?? ''}}</td>
                            <td>{{number_format($data['data']['pv2_current']['value'], 2, '.', ',') ?? ''}} {{$data['data']['pv2_current']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['pv2_power']['value'] ?? ''}} {{$data['data']['pv2_power']['unit'] ?? ''}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-12 text-center">
        Created At : <span class="badge badge-success">{{ ($data && $data['created_at']) ? date("Y-m-d h:i A", strtotime($data['created_at'])) : ''}}</span>
    </div>
</div>