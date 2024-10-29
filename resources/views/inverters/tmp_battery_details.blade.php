<div class="card top_counter">
    <div class="body">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6">
                <div class="body text-center pb-0 pt-0">
                    <img src="{{ asset('/app-assets/images/icon/Group145.svg') }}" >
                    <h5 class="mt-0 bat_power text-info"> {{$data['data']['bat_power']['value'] ?? ''}} {{$data['data']['bat_power']['unit'] ?? ''}}</h5>
                    <p>Today Power</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center"">
                <div class="body text-center pb-0 pt-0">
                    <div class="content">
                        <h5 class="number bat_energy_total">{{$data['data']['bat_energy_total']['value'] ?? ''}} {{$data['data']['bat_energy_total']['unit'] ?? ''}}</h5>
                        <div class="text">Total Energy</div>
                    </div>
                    <div class="content pt-2">
                        <input type="text" class="bat_socChart" value="{{$data['data']['bat_soc']['value'] ?? '0'}}" data-width="50"
                                                data-height="50" data-thickness="0.2" data-fgColor="#55BBEA" readonly>
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
                <h2>Battery Details</h2>
            </div>
            <div class="body table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">Voltage</th>
                            <td>{{number_format($data['data']['bat_Voltage']['value'], 2, '.', ',') ?? ''}} {{$data['data']['bat_Voltage']['unit'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Current</th>
                            <td>{{$data['data']['bat_current']['value'] ?? ''}} {{$data['data']['bat_current']['unit'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Power</th>
                            <td>{{$data['data']['bat_power']['value'] ?? ''}} {{$data['data']['bat_power']['unit'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <th scope="row">SoC</th>
                            <td>{{$data['data']['bat_soc']['value'] ?? ''}} {{$data['data']['bat_soc']['unit'] ?? ''}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-12 text-center">
        Created At : <span class="badge badge-success">{{($data && $data['created_at']) ? date("Y-m-d h:i A", strtotime($data['created_at'])) : ''}}</span>
    </div>
</div>