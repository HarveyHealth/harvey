<template>
    <div v-cloak :class="{ 'container': true, 'is-visible': isVisible }">
        <div title="Add to Calendar" class="addeventatc">
            Add to Calendar
            <span class="start">{{ config.start }}</span>
            <span class="end">{{ config.end }}</span>
            <span class="timezone">{{ config.timezone }}</span>
            <span class="title">{{ config.title }}</span>
            <span class="description">{{ config.description }}</span>
            <span class="location">{{ config.location }}</span>
            <span class="organizer">Harvey</span>
            <span class="organizer_email">support@goharvey.com</span>
            <span class="all_day_event">false</span>
            <span class="date_format">MM/DD/YYYY</span>
            <span class="client">ajiwVmWorzcyJqbpmmXE27705</span>
            <span class="addeventatc_icon" style="height: 0 !important; background: none !important;"></span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        config: Object,
        required: true,
        validator(config) {
            return (
                config.start && typeof config.start === 'string' &&
                config.end && typeof config.end === 'string' &&
                config.timezone && typeof config.timezone === 'string' &&
                config.title && typeof config.title === 'string' &&
                config.description && typeof config.description === 'string' &&
                config.location && typeof config.location === 'string'
            )
        }
    },
    data() {
        return {
            isVisible: false
        }
    },
    mounted() {
        // From https://www.addevent.com/buttons/add-to-calendar
        // Has to be added on component mount because it needs to be able to find
        // the corresponding button in the DOM.
        const d = document, s = d.createElement('script'), g = 'getElementsByTagName';
        s.type = 'text/javascript';
        s.charset = 'UTF-8';
        s.async = true;
        s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addevent.com/libs/atc/1.6.1/atc.min.js';

        const h = d[g]('body')[0];
        h.appendChild(s);
        s.onload = () => setTimeout(() => this.isVisible = true, 500);
    }
}
</script>

<style lang="scss" scoped>
    div.container {
        opacity: 0 !important;
    }

    div.is-visible {
        opacity: 1 !important;
    }

    [v-cloak] {
        opacity: 0 !important;
    }
</style>
