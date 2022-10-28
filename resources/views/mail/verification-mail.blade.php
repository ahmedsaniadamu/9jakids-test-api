@component('mail::message')
#  Welcome {{ $data['name'] }}

<h1> login details </h1>
<br>
<p>
    please copy the url below or alternatively click the button below to login directly {{ $data['url'] }}
</p>
<br>
@component('mail::button', ['url' => $data['url'] ])
 Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
