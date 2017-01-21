@include('_layouts.includes.header')

<div id="app">
    <app :guest="guest" :user="user">
        <router-view :user="user"></router-view>
    </app>
</div>

@include('_layouts.includes.footer')