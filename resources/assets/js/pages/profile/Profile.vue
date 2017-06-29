<template>
    <div class="main-container">
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="title header-xlarge">
                        <span class="text">Profile</span>
                    </h1>
                </div>
            </div>
            <div class="card card-info">
                <div class="card-heading-container">
                    <h2 class="card-header">Contact Info</h2>
                </div>
                <div class="card-content-container">
                    <div class="card-content-wrap">
                        <form action="#" method="POST" class="form" id="user_form">
                            <div class="formgroups">
                                <div class="formgroup">
                                    <label for="first_name">First Name</label>
                                    <input v-model="user.attributes.first_name" type="text" name="first_name"/>
                                    <br/>
                                    <label for="last_name">Last Name</label>
                                    <input v-model="user.attributes.last_name" type="text" name="last_name"/>
                                    <br/>
                                    <label for="email">Email</label>
                                    <input v-validate="'required|email'" v-model="user.attributes.email" type="text" name="email"/>
                                    <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                    <br/>
                                    <label for="phone">Phone Number</label>
                                    <input v-model="user.attributes.phone" type="number" name="phone"/>
                                    <br/>
                                    <label for="timezone">Timezone</label>
                                    <select v-model="user.attributes.timezone">
                                        <option v-for="timezone in timezones" >{{ timezone }}</option>
                                    </select>
                                    <!--<input v-model="user.attributes.timezone" type="text" name="timezone"/>-->
                                </div>
                                <div class="formgroup">
                                    <label for="address_1">Mailing Address</label>
                                    <input v-model="user.attributes.address_1" type="text" name="address_1"/>
                                    <br/>
                                    <label for="address_2">Apt/Unit #</label>
                                    <input v-model="user.attributes.address_2" type="text" name="address_2"/>
                                    <br/>
                                    <label for="city">City</label>
                                    <input v-model="user.attributes.city" type="text" name="city"/>
                                    <br/>
                                    <label for="state">State</label>
                                    <input v-model="user.attributes.state" type="text" name="state"/>
                                    <br/>
                                    <label for="zip">Zip Code</label>
                                    <input v-model="user.attributes.zip" type="text" name="zip"/>
                                </div>
                            </div>
                            <div class="submit">
                                <button v-on:click.prevent="submit" >Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import diff from 'object-diff';
    import _ from 'lodash';
    import timezones from '../../../../../public/timezones.json';

    export default {
        name: 'profile',
        data() {
            return {
                user: {
                    attributes: {
                        first_name: '',
                        last_name: '',
                        email: '',
                        phone: '',
                        timezone: '',
                        address_1: '',
                        address_2: '',
                        city: '',
                        state: '',
                        zip: '',
                    }
                },
                timezones: timezones
            }
        },
        methods: {
            submit() {
                if(_.isEmpty(this.updates))
                    return;

                axios.patch(`/api/v1/users/${this.user.id}`, this.updates)
                    .then(response => {
                        this.$root.$data.global.user = response.data.data;
                    })
                    .catch(err => {console.log(err);});
            },
            getUser() {
                axios.get(`/api/v1/users/${Laravel.user.id}`)
                    .then(response => {
                        this.user = response.data.data;
                    })
                    .catch(error => this.user = {});
            },
        },
        mounted() {
            this.$root.$data.global.currentPage = 'profile';
        },
        created() {
            if(this.$root.$data.global.user.id) {
                this.user =_.cloneDeep(this.$root.$data.global.user);
            } else {
                this.getUser();
            }
        },
        computed: {
            updates() {
                return diff(this.$root.$data.global.user.attributes, this.user.attributes);
            }
        }
    }
</script>

<style>
    input, label {
        display:block;
    }

    .formgroups {
        display: flex;
    }

    .formgroup {
        flex: 1;
    }

    .submit {
        padding-top: 20px;
        display: flex;
        justify-content: center;
    }

    .card-info {
        width: 75%;
    }
</style>
