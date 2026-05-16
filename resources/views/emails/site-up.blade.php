@component('mail::message')
# Site Recovered

Your monitored site is back **UP**.

**URL:** {{ $monitor->url }}
**Status:** Up
**Time:** {{ now()->toDateTimeString() }}

@component('mail::button', ['url' => $monitor->url])
Visit Site
@endcomponent

Thanks,
Uptime Monitor
@endcomponent