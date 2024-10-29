@extends('front.layout_admin.app')
@section('page_level_css')
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>

@endsection

@section('content')


     <!-- logs for today --> 
   <!-- <pre style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; max-height: 600px; overflow-y: scroll;">
           
        </pre>   -->
        <div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Application Log Files</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Log File</li>
                    </ol>
                </div>
</div>
<div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card chart-card-height">
                        <div class="card-header">
                            <div class="col-sm-9">
                                <h3 class="card-title text-dark number-font text-uppercase">Log Data<i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="Total Energy" data-bs-original-title="Total Energy"></i></h3>
                            </div>
                           

                        </div>
                        <div class="card-body pb-0" >
                            
                        <pre style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; max-height: 600px; overflow-y: scroll;">
                        {{$logs}}
           </pre>     
                      
                      
                    </div>
                </div>
                <!-- COL END -->

            </div>

@endsection