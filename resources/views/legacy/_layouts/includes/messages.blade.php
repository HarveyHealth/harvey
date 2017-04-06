{{-- Show success/error messages --}}
@if (session('success'))
    <div class="notification animated fadeOut">
        {{ session('success') }}
    </div>
@endif

@if (isset($errors))
    @if (count($errors) > 0)
        <div class="notification is-danger animated fadeIn">
            <button class="delete"></button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif
