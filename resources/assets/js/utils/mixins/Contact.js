import Contact from '../../commons/Contact.vue';

export default {
    components: {
        Contact
    },
    methods: {
        toggleContact () {
            this.$eventHub.$emit('toggle-contact');
        }
    }
};
