import Contact from '../_includes/Contact.vue';

export default {
    components: {
        Contact
    },
    methods: {
        toggleContact() {
            this.$eventHub.$emit('toggle-contact');
        }
    }
}
