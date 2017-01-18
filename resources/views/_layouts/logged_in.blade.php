@include('_layouts.includes.header')

<div id="app">
    <app :guest=false>
        <template slot="nav">
            <router-link tag="a" to="/dashboard" class="nav-item">Dashboard</router-link>
            <a href="/" class="nav-item">New Appointment</a>
            <router-link tag="a" to="/profile" class="nav-item">Edit Profile</router-link>
        </template>
        <div slot="content" class="page-content">
            <router-view></router-view>
        </div>
    </app>
</div>

@include('_layouts.includes.footer')
