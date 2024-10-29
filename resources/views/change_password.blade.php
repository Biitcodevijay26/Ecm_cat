@extends('front.layout_admin.app')

@section('page_level_css')
@endsection

@section('content')
<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-12">
                    <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i
                                class="fa fa-arrow-left"></i></a> {{ $heading }}</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                        <li class="breadcrumb-item active">{{ $heading }}</li>
                    </ul>
                </div>
                {{-- <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                    <div class="inlineblock text-center m-r-15 m-l-15 hidden-sm">
                        <div class="sparkline text-left" data-type="line" data-width="8em" data-height="20px"
                            data-line-Width="1" data-line-Color="#00c5dc" data-fill-Color="transparent">
                            3,5,1,6,5,4,8,3</div>
                        <span>Visitors</span>
                    </div>
                    <div class="inlineblock text-center m-r-15 m-l-15 hidden-sm">
                        <div class="sparkline text-left" data-type="line" data-width="8em" data-height="20px"
                            data-line-Width="1" data-line-Color="#f4516c" data-fill-Color="transparent">
                            4,6,3,2,5,6,5,4</div>
                        <span>Visits</span>
                    </div>
                </div> --}}
            </div>
        </div>

        <div class="row clearfix justify-content-center">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="header">
                        <h2> {{ $heading }} </h2>
                    </div>
                    <div class="body">
                        <form class="form" method="post" id="change-password-form" action="{{ url('admin/save-password')}}">
                            @csrf
                            <div class="form-group">
                                <label for="current_password">Current Password <span class="text-danger"> * </span> </label>
                                <input type="password" id="current_password" class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Current Password" name="current_password" value="{{ old('current_password') }}">
                                @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password <span class="text-danger"> * </span> </label>
                                <input type="password" id="new_password" class="form-control @error('new_password') is-invalid @enderror"
                                    placeholder="New Password" name="new_password" value="{{old('new_password') ?? ''}}">
                                @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="confrim_password">Confrim New Password <span class="text-danger"> * </span> </label>
                                <input type="password" id="confrim_password" class="form-control @error('confrim_password') is-invalid @enderror"
                                    placeholder="Confrim New Password" name="confrim_password">
                                @error('confrim_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-actions center">
                                <button type="reset" class="btn btn-danger mr-1">
                                    <i class="ft-x"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ft-check-square"></i> Save
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
