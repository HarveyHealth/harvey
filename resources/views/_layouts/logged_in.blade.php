@include('_layouts.includes.header')

@script(https://unpkg.com/vue/dist/vue.js)

<div id="content">
    @yield('content')
</div>

@include('_layouts.includes.footer')
