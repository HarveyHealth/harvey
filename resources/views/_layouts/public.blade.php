@include('_layouts.includes.header')

<div id="app">
    <app :guest={{Auth::guest()}}>
        <div slot="content" class="page-content">@yield('content')</div>
    </app>
</div>

@include('_layouts.includes.footer')