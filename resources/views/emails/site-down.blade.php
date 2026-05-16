@component('mail::message')
# Site Down Alert

Your monitored site is currently **DOWN**.

**URL:** {{ $monitor->url }}
**Status:** Down
**Time:** {{ now()->toDateTimeString() }}

@component('mail::button', ['url' => $monitor->url])
Visit Site
@endcomponent

Thanks,
Uptime Monitor
@endcomponent