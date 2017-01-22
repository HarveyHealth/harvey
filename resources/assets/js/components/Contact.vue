<template>
    <div :class="['modal', {'is-active animated fadeIn': show}]">
        <div class="modal-background" @click="show = false"></div>
        <div class="modal-content has-text-centered">
            <h2 class="title is-1"><strong>Contact Us</strong></h2>

            <ul>
                <li v-for="(value, type) in types">
                    <h3 class="title is-4">{{type}}</h3>
                    <p class="subtitle is-3">
                        <a :href="hyperlink(type, value)">{{value}}</a>
                    </p>
                </li>
            </ul>
        </div>
        <button class="modal-close" @click="show = false"></button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                show: false,
                types: {
                    phone: '(310) 907-5302',
                    email: 'support@goharvey.co'
                }
            }
        },
        methods: {
            hyperlink(type, value) {
                let ret = '';

                switch(type) {
                    case 'phone':
                        ret = 'tel:' + value;
                        break;
                    case 'email':
                        ret = 'mailto:' + value;
                        break;
                }

                return ret;
            }
        },
        created() {
            this.$eventHub.$on('toggle-contact', () => {
                this.show = !this.show;
            });
        }
    }
</script>

<style lang="sass" scoped>
    .modal-content {
        overflow-y: hidden;
    }

    .title.is-1 {
        color: white;
        margin-bottom: 3rem;
    }
    
    li {
        margin-bottom: 1.5rem;
        .title {
            color: hsl(0, 0%, 48%);
        }
        .subtitle a {
            color: white;
        }
    }
</style>

