@if (count($recent_tests) > 0)
<h3>Recent Tests</h3>
<table class="table">
    <tr>
        <th>Test</th>
        <th>Results</th>
    </tr>
    @foreach ($recent_tests as $test)
        <tr>
            <td>{{ $test->name }}</td>
            <td>
                @if($test->results_url)
                    $test->tempURL()
                @else
                    Pending
                @endif
            </td>
        </tr>
    @endforeach
</table>
@endif
