<template>
    <div  id="contact" :class="['modal', {'is-active animated fadeIn': show}]">
        <div class="modal-background" @click="show = false"></div>
        <div class="modal-content has-text-centered">
            <h2 class="title is-1"><strong>Contact Us</strong></h2>

            <ul>
                <li v-for="(value, type) in types">
                    <h3 class="title is-4">{{ capitalize(type) }}</h3>
                    <p class="subtitle is-3">
                        <a :href="hyperlink(value, type)">{{value}}</a>
                    </p>
                </li>
            </ul>
        </div>
        <button class="modal-close" @click="show = false"></button>
    </div>
</template>

<script>
import {capitalize, hyperlink} from '../utils/filters/textformat.js';

export default {
    data() {
        return {
            show: false,
            types: {
                phone: '(310) 907-5302',
                email: 'support@goharvey.com'
            }
        };
    },
    methods: {
        capitalize,
        hyperlink
    },
    created() {
        this.$eventHub.$on('toggle-contact', () => {
            this.show = !this.show;
        });
    }
};
</script>
