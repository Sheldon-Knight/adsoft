<div>

    @if ($getState())
        @forelse($getState() as $key => $value)
            {{ $key }} : {{ $value }} <br>
        @empty
            <p>No Attributes</p>
        @endforelse
    @else

    <small>No Data...</small>

    @endif
</div>
