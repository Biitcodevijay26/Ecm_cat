@if($notifications)
    @foreach ($notifications as $notif)
    {{-- <a href="{{ url('/user-edit/'.$notif->user_id)}}" class="list-group-item list-group-item-action flex-column align-items-start mb-4  notification_is_read_{{$notif->id}}" @if ($notif->is_read == 0) style="background:lightgray;" @endif">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-2"><b> New User Register </b></h5>
            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
        </div>
        <p class="mb-2">{{$notif->user->full_name ?? ''}}</p>
    </a> --}}
    <div class="card notification_is_read_{{$notif->id}}" @if ($notif->is_read == 0) style="background:lightgray;" @endif>
        <div class="card-status card-status-left bg-primary br-bl-7 br-tl-7"></div>
        <div class="card-body">
            <div class="example">
                <a href="{{ url('/user-edit/'.$notif->user_id)}}">
                <div class="media media-lg mt-0">
                    <img class="avatar avatar-xl brround me-3 mb-4" src="{{ url('theme-asset/images/notification.png') }}" alt="notification bell">
                    <div class="media-body">
                        <h4 class="mt-0">A new user has registered with the name {{$notif->user->full_name ?? ''}}</h4>
                        <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>

    @endforeach
@endif
