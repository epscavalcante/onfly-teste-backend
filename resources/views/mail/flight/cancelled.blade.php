<x-mail::message>
# 👋 Hello, {{ $userName }}!

Your flight was **cancelled** at this time.

<x-mail::panel>
**Flight data:** <br>

- Destination: {{ $destination }}</li>
- Departune date: {{ date('d/m/Y H:i', strtotime($departuneDate)) }}</li>
- Return date: {{ date('d/m/Y H:i', strtotime($departuneDate)) }}</li>
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
