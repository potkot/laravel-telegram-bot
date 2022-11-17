@foreach($commands as $command)
<b>{{ $command['name'] }}</b>
{{ $command['description'] }}

@endforeach
