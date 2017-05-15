<!--<h3>Upcoming Appointments</h3>
@if (count($upcoming_appointments) > 0)
    <table class="table">
        <th>Date</th>
        <th>Time</th>
        <th>Patient</th>
        <th>Practitioner</th>
        @foreach ($upcoming_appointments as $appointment)
            <tr>
                <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('F, j') }}</td>
                <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('H:i a') }}</td>
                <td>{{ $appointment->patient->user->fullName() }}</td>
                <td>{{ $appointment->practitioner->user->fullName() }}</td>
            </tr>
        @endforeach
    </table>
@else
    No upcoming appointments.
@endif



<h3>Recent Appointments</h3>
@if (count($upcoming_appointments) > 0)
    <table class="table">
        <th>Date</th>
        <th>Time</th>
        <th>Patient</th>
        <th>Practitioner</th>
        @foreach ($recent_appointments as $appointment)
            <tr>
                <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('F, j') }}</td>
                <td>{{ Carbon\Carbon::parse($appointment->created_at)->format('H:i a') }}</td>
                <td>{{ $appointment->patient->user->fullName() }}</td>
                <td>{{ $appointment->practitioner->user->fullName() }}</td>
            </tr>
        @endforeach
    </table>
@else
    No upcoming appointments.
@endif



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
            <td>{{ $test->patient->user->fullName() }}</td>
            <td>{{ $test->practitioner->user->fullName() }}</td>
            <td>
                <a href="/upload/test/{{ $test->id }}">Upload Results</a>
            </td>
        </tr>
    @endforeach
</table>
@endif-->
