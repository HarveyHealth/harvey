@include('_layouts.includes.header')

<div id="app">
    <app :guest="guest" :user="user">
        @yield('content')
    </app>
</div>

@include('_layouts.includes.footer')