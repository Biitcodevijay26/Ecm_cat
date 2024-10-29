<div class="card top_counter">
    <div class="body">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6">
                <div class="body text-center pb-0 pt-0">
                    <img src="{{ asset('/app-assets/images/icon/Group198.svg') }}" >
                    <h5 class="mt-0 inv_total_power text-success"> {{$data['data']['inv_total_power']['value'] ?? ''}} {{$data['data']['inv_total_power']['unit'] ?? ''}}</h5>
                    <p>Total Inverter Power</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center"">
                <div class="body text-center pb-0 pt-0">
                    <div class="content">
                        <h5 class="number inv_energy_today">{{number_format($data['data']['inv_energy_today']['value'], 2, '.', ',') ?? ''}} {{$data['data']['inv_energy_today']['unit'] ?? ''}}</h5>
                        <div class="text">Today Energy</div>
                    </div>
                    <div class="content pt-2">
                        <h5 class="number inv_energy_total">{{number_format($data['data']['inv_energy_total']['value'], 2, '.', ',') ?? ''}} {{$data['data']['inv_energy_total']['unit'] ?? ''}}</h5>
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
                <h2>Inverter Details</h2>
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
                            <th scope="row">AC <span class="text-danger">R</span> </th>
                            <td>{{$data['data']['inv_phase1_voltage']['value'] ?? ''}} {{$data['data']['inv_phase1_voltage']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['inv_phase1_current']['value'] ?? ''}} {{$data['data']['inv_phase1_current']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['inv_phase1_power']['value'] ?? ''}} {{$data['data']['inv_phase1_power']['unit'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <th scope="row">AC <span class="text-warning">Y</span></th>
                            <td>{{$data['data']['inv_phase2_voltage']['value'] ?? ''}} {{$data['data']['inv_phase2_voltage']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['inv_phase2_current']['value'] ?? ''}} {{$data['data']['inv_phase2_current']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['inv_phase2_power']['value'] ?? ''}} {{$data['data']['inv_phase2_power']['unit'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <th scope="row">AC <span class="text-primary">B</span></th>
                            <td>{{$data['data']['inv_phase3_voltage']['value'] ?? ''}} {{$data['data']['inv_phase3_voltage']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['inv_phase3_current']['value'] ?? ''}} {{$data['data']['inv_phase3_current']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['inv_phase3_power']['value'] ?? ''}} {{$data['data']['inv_phase3_power']['unit'] ?? ''}}</td>
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