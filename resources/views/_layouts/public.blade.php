@include('_layouts.includes.header')

<div id="app">
    <app :guest={{Auth::guest()}}>
        <template slot="content">
            @yield('content')
        </template>
    </app>
</div>

@include('_layouts.includes.footer')