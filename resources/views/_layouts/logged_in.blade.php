@include('_layouts.includes.header')

<div id="app">
    <ul>
        <router-link tag="li" to="/home">
            <a>go to home</a>
        </router-link>
    </ul>
    <router-view></router-view>
</div>

{{-- following content needs to be rewritten in vue components --}}
<div id="content" class="container">
    @yield('content')
</div>
{{-- end --}}

<script src="/js/app.js"></script>
@include('_layouts.includes.footer')
