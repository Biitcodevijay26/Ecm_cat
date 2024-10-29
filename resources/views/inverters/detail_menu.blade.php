<div class="col-md-12">
    <ul class="nav">
        <li class="nav-item">
          <a class="nav-link text-info active" href="{{url('admin/inverter-detail') . '/' . $inverter->_id }}">Inverter Analysis</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-info" href="{{url('admin/inverter-detail') . '/' . $inverter->_id . '?type=alarmHistory' }}">Alarm History</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-info" href="{{url('admin/inverter-detail') . '/' . $inverter->_id . '?type=batteryAnalysis' }}">Battery Analysis</a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link text-info" href="#">Inverter Data</a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link text-info" href="{{url('admin/inverter-detail') . '/' . $inverter->_id . '?type=statisticReport' }}">Statistic Report</a>
        </li>
    </ul>
</div>