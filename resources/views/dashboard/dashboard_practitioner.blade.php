<h3>Upcoming Appointments</h3>
@if (count($upcoming_appointments) > 0)
    <table class="table">
        @foreach ($upcoming_appointments as $appointment)
            <tr>
                <td>{{ $appointment->appointment }}</td>
            </tr>
        @endforeach
    </table>
@else
    You have no upcoming appointments. Schedule a consultation now.
@endif


<h3>Recent Appointments</h3>
@if (count($recent_appointments) > 0)
    <table class="table">
        @foreach ($recent_appointments as $appointment)
            <tr>
                <td>{{ $appointment->appointment }}</td>
            </tr>
        @endforeach
    </table>
@else
    You have no upcoming appointments. Schedule a consultation now.
@endif



@if (count($recent_tests) > 0)
<h3>Recent Tests</h3>
<table class="table">
    @foreach ($recent_tests as $test)
        <tr>
            <td>{{ $test->name }}</td>
        </tr>
    @endforeach
</table>
@endif
