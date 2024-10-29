@component('mail::message')
# Hello {{ ucwords(trans($data->first_name .' '. $data->last_name)) }},

Welcome to {{ config('app.name') }}. <br>
We are happy to onboard you in our app and your verification code is <b> {{$data->email_otp ?? ''}} </b>. <br>


Thanks & Regards<br>
{{ config('app.name') }} Team.<br>
@endcomponent
