@include('_layouts.includes.header')

<div id="app">
    <app>
        <transition
            mode="out-in"
            enter-active-class="animated animated-fast fadeIn"
            leave-active-class="animated animated-fast fadeOut"
        >
            <router-view :user="user"></router-view>
        </transition>
    </app>
</div>

@include('_layouts.includes.footer')