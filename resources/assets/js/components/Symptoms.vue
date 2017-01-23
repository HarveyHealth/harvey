<template>
    <div>
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
                        label: 'Hormonal issues',
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
                        label: 'Back/neck pain',
                        value: 5
                    },
                    {
                        label: 'Stress/anxiety',
                        value: 5
                    },
                    {
                        label: 'Sinus problems',
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
    .vue-slider-wrap {
        flex: 1;
    }
</style>

