<template>
    <div class="symptoms-selector-wrapper">
        <div class="control is-horizontal"
             v-for="stat in stats"
        >
            <div class="control-label">
                <label class="label">{{stat.label}}</label>
            </div>
            <div class="control">
                <vue-slider
                    v-model="stat.value"
                    :min="min"
                    :max="max"
                    :speed="speed"
                    :piecewise="piecewise">
                </vue-slider>
            </div>
        </div>
    </div>
</template>

<script>
    import vueSlider from 'vue-slider-component';

    export default {
        data() {
            return {
                min: 0,
                max: 5,
                speed: .2,
                piecewise: true,
                stats: [
                    {
                        label: 'Fatigue',
                        value: 5
                    },
                    {
                        label: 'Aches / pains',
                        value: 5
                    },
                    {
                        label: 'Allergies',
                        value: 5
                    },
                    {
                        label: 'Depression',
                        value: 5
                    },
                    {
                        label: 'Digestion / stomach',
                        value: 5
                    },
                    {
                        label: 'Irritability / mood',
                        value: 5
                    },
                    {
                        label: 'Libido',
                        value: 5
                    },
                    {
                        label: 'Stress',
                        value: 5
                    },
                    {
                        label: 'Weight',
                        value: 5
                    },
                    {
                        label: 'Hair and skin',
                        value: 5
                    },
                    {
                        label: 'Other',
                        value: 5
                    }
                ]
            }
        },
        components: {
            vueSlider
        }
    }
</script>

<style lang="sass" scoped>
    .control-label {
        text-align: left;
    }
    .control:not(:last-child) {
        margin-bottom: 1.5rem;
        @media screen and (min-width: 1192px) {
            margin-bottom: 1rem;
        }
    }
    @media screen and (min-width: 769px) {
        .control.is-horizontal > .control {
            display: block;
            flex-grow: 4;
            padding-top: 0.5em;
        }
    }
</style>

