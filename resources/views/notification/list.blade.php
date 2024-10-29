@extends('front.layout_admin.app')
@section('content')
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
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>

            </div>
            <!-- PAGE-HEADER END -->
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    {{-- <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List of Notifications</h3>
                        </div>
                        <div class="card-body">
                            <div class="example">
                                <div class="list-group notification-list">

                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="notification-list">
                    </div>

                </div><!-- COL END -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('page_level_js')
<script>
    $(document).ready(function($) {
        var offset = 0;
        var processing;
        var type = 'All';
        render_notifications();
        $(document).scroll(function(e){
            if (processing)
                return false;

            if ($(window).scrollTop() >= $(document).height() - $(window).height() - 850){
                render_notifications();
            }
        });

        function render_notifications() {

            processing = true;
            $.ajax({
                url: '{{url("get-notification-list")}}',
                type: 'POST',
                data: {offset:offset,type:type},
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                success: function(resp) {
                    if(resp){

                        try{
                            resp = JSON.parse(resp)
                        } catch(e){}
                        offset = resp.offset;
                        $('.notification-list').append(resp.html);
                        updateReadStatus(resp.notificationIds);
                        if(resp.is_data) {
                            processing = false; //resets the ajax flag once the callback concludes
                        }
                        $('.unreadNotifCount').text(resp.unread_count);
                    }
                }
            });
        }

        function updateReadStatus(notificationIds){
            if(notificationIds){
                $.ajax({
                    url: '{{url("mark-notifications-as-read")}}',
                    type: 'POST',
                    data: {notificationIds:notificationIds},
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    success: function(resp) {
                        if(resp){
                            try{
                                resp = JSON.parse(resp)
                            } catch(e){}
                            if(resp.success == 1)
                            {
                                $.each(notificationIds, function (key, val) {
                                    console.log(val);
                                    setTimeout(() => {
                                        $('.notification_is_read_'+val).css('background','#ffffff');
                                    }, 1000);
                                });
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endsection

