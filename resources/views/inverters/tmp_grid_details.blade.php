<div class="card top_counter">
    <div class="body">
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6">
                <div class="body text-center pb-0 pt-0">
                    <img src="{{ asset('/app-assets/images/icon/Group38.svg') }}" >
                    <h5 class="mt-0 grid_total_power" style="color: #B364D3;"> {{$data['data']['grid_total_power']['value'] ?? ''}} {{$data['data']['grid_total_power']['unit'] ?? ''}}</h5>
                    <p>Total Grid Power</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 align-self-center"">
                <div class="body text-center pb-0 pt-0">
                    <div class="content">
                        <h5 class="number grid_run_time">{{number_format($data['data']['grid_run_time']['value'], 2, '.', ',') ?? ''}} {{$data['data']['grid_run_time']['unit'] ?? ''}}</h5>
                        <div class="text">Run Time</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-6">
        <div class="card">
            <div class="body text-center pb-0 pt-0">
                <p class="pt-3">Energy Sell</p>
                <hr style="border-top: 2px solid rgb(253 251 251 / 10%);">
                <h5 class="mt-0"> {{$data['data']['grid_energy_sell_today']['value'] ?? ''}} {{$data['data']['grid_energy_sell_today']['unit'] ?? ''}}</h5>
                <p>Today Sell</p>

                <h5 class="mt-0"> {{$data['data']['grid_energy_sell_total']['value'] ?? ''}} {{$data['data']['grid_energy_sell_total']['unit'] ?? ''}}</h5>
                <p>Total Sell</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="body text-center pb-0 pt-0">
                <p class="pt-3">Energy Buy</p>
                <hr style="border-top: 2px solid rgb(253 251 251 / 10%);">
                <h5 class="mt-0"> {{$data['data']['grid_energy_buy_today']['value'] ?? ''}} {{$data['data']['grid_energy_buy_today']['unit'] ?? ''}}</h5>
                <p>Today Buy</p>

                <h5 class="mt-0"> {{$data['data']['grid_energy_byu_total']['value'] ?? ''}} {{$data['data']['grid_energy_byu_total']['unit'] ?? ''}}</h5>
                <p>Total Buy</p>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Grid Details</h2>
            </div>
            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Phases</th>
                            <th>Voltage</th>
                            <th>Power</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Phase 1</th>
                            <td>{{$data['data']['grid_phase1_voltage']['value'] ?? ''}} {{$data['data']['grid_phase1_voltage']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['grid_phase1_power']['value'] ?? ''}} {{$data['data']['grid_phase1_power']['unit'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Phase 2</th>
                            <td>{{$data['data']['grid_phase2_voltage']['value'] ?? ''}} {{$data['data']['grid_phase2_voltage']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['grid_phase2_power']['value'] ?? ''}} {{$data['data']['grid_phase2_power']['unit'] ?? ''}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Phase 3</th>
                            <td>{{$data['data']['grid_phase3_voltage']['value'] ?? ''}} {{$data['data']['grid_phase3_voltage']['unit'] ?? ''}}</td>
                            <td>{{$data['data']['grid_phase3_power']['value'] ?? ''}} {{$data['data']['grid_phase3_power']['unit'] ?? ''}}</td>
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