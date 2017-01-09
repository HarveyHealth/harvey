@if (count($pending_tests) > 0)
<h3>Recent Tests</h3>
<table class="table">
    <tr>
        <th>Test</th>
        <th>Patient</th>
        <th>Practitioner</th>
        <th>Results</th>
    </tr>
    @foreach ($pending_tests as $test)
        <tr>
            <td>{{ $test->name }}</td>
            <td>{{ $test->patient->fullName() }}</td>
            <td>{{ $test->practitioner->fullName() }}</td>
            <td>
                <a href="/upload/test/{{ $test->id }}">Upload Results</a>
            </td>
        </tr>
    @endforeach
</table>
@endif
