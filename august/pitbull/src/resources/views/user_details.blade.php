@extends(config('pitbull.templates.layout'))

@section(config('pitbull.templates.content'))

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <div class="container" id="pitbull">
        <div class="row">

            <div class="col-md-3">
                <h3>User</h3>
                <table class="table">
                    @foreach($fields as $field)
                        <tr>
                            <td>
                                {{ title_case(str_replace('_', ' ', $field)) }}
                            </td>
                            <td>
                                {{ $user->$field }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="col-md-3">
                <h3>Roles</h3>
                <ul>
                    <li v-for="item in roles">@{{ item }}</li>
                </ul>
            </div>

            <div class="col-md-3">
                <h3>Extra Permissions</h3>
                <ul>
                    <li v-for="item in permissions">@{{ item }}</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>

        var app = new Vue({
            el: '#pitbull',
            data: {
                'user_id' : {{ $user->id }},
                'roles' : ['banana'],
                'permissions' : []
            },
            mounted() {
                var self = this;

                axios.get('/pitbull/rolesforuser/' + this.user_id).then(function(response) {
                    self.roles = response.data;
                });

                axios.get('/pitbull/permissionsforuser/' + this.user_id).then(function(response) {
                    self.permissions = response.data;
                });
            }
        })
    </script>

@endsection
