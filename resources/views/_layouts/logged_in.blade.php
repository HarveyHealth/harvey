@include('_layouts.includes.header')

<div id="app">
{{--     <ul>
        <router-link tag="li" to="/dashboard">
            <a>Dashboard</a>
        </router-link>
    </ul> --}}
    <router-view></router-view>
</div>

@include('_layouts.includes.footer')
