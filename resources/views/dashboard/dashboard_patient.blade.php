<h3>Upcoming Appointments</h3>
@if (count($upcoming_appointments) > 0)
    <table class="table">
        <th>Date</th>
        <th>Time</th>
        <th>Patient</th>
        @foreach ($upcoming_appointments as $appointment)
            <tr>
                <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('F, j') }}</td>
                <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('H:i a') }}</td>
                <td>{{ $appointment->patient->fullName() }}</td>
            </tr>
        @endforeach
    </table>
@else
    No upcoming appointments.
@endif



@if (count($recent_appointments) > 0)
    <h3>Recent Appointments</h3>
    <table class="table">
        <th>Date</th>
        <th>Time</th>
        <th>Patient</th>
        @foreach ($recent_appointments as $appointment)
            <tr>
                <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('F, j') }}</td>
                <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('H:i a') }}</td>
                <td>{{ $appointment->patient->fullName() }}</td>
            </tr>
        @endforeach
    </table>
@endif



@if (count($recent_tests) > 0)
<h3>Recent Tests</h3>
<table class="table">
    <tr>
        <th>Test</th>
        <th>Practitioner</th>
        <th>Results</th>
    </tr>
    @foreach ($recent_tests as $test)
        <tr>
            <td>{{ $test->name }}</td>
            <td>{{ $test->practitioner->fullName() }}</td>
            <td>
                @if($test->results_key)
                    $test->tempResultsURL()
                @else
                    Pending
                @endif
            </td>
        </tr>
    @endforeach
</table>
@endif
