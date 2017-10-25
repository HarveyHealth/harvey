<template>
    <div class="symptoms-selector-wrapper columns is-desktop is-multiline">
        <div class="control column is-half-desktop"
             v-for="stat in stats"
        >
            <div class="control-label">
                <label class="label">{{stat.label}}</label>
                <label class="symptoms-selector-description">{{stat.description}}</label>
            </div>
            <div class="control">
                <vue-slider
                    v-model="stat.value"
                    :min="min"
                    :max="max"
                    :speed="speed"
                    :piecewise="piecewise"
                    @callback="onChange"
                ></vue-slider>
            </div>
        </div>
    </div>
</template>

<script>
    import vueSlider from 'vue-slider-component';

    export default {
        props: ['stats'],
        data() {
            return {
                min: 0,
                max: 5,
                speed: .2,
                piecewise: true,
                dataReady: false
            }
        },
        components: {
            vueSlider,
            sliderLegend: {
                template: `
                    <div class="control-legend control is-horizontal column is-half-desktop">
                        <div class="control-label"></div>
                        <div class="control">
                            <div class="is-clearfix">
                                <label class="label is-pulled-left">Less severe</label>
                                <label class="label is-pulled-right">More severe</label>
                            </div>
                        </div>
                    </div>
                `
            }
        },
        methods: {
            onChange(e) {
                if (this.dataReady) {
                    this.$emit('changed');
                    this.$children.forEach((child) => {
                        child.$off('callback');
                    });
                }
            }
        },
        mounted() {
            this.$nextTick(() => {
                this.dataReady =  true;
            });
        }
    }
</script>

