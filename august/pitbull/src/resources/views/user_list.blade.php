@extends(config('pitbull.templates.layout'))

@section(config('pitbull.templates.content'))

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <div class="container">
        @if (count($users) > 0)

            <table class="table">
                    <tr>
                        @foreach ($fields as $field)
                            <th>{{ title_case(str_replace('_', ' ', $field)) }}</th>
                        @endforeach
                        <th></th>
                    </tr>


                @foreach ($users as $user)
                    <tr>
                        @foreach ($fields as $field)
                            <td>{{ $user->$field }}</td>
                        @endforeach
                        <td><a href="{{ config('app.url') }}/pitbull/user/{{ $user->id}}" class="btn btn-default" role="button">Details</a></td>
                    </tr>
                @endforeach
            </table>
        @else
            Sorry, you don't have any users yet.
        @endif
    </div>
@endsection
