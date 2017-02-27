<template>
    <div class="symptoms-selector-wrapper columns is-desktop is-multiline">
        <div class="control is-horizontal column is-half-desktop"
             v-for="stat in stats"
        >
            <div class="control-label">
                <label class="label">{{stat.label}}</label>
                <small class="symptoms-selector-description">{{stat.description}}</small>
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
                    this.$children.forEach( (child) => {
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

<style lang="sass">
    .control-legend {
        margin-bottom: 0;
        padding-top: 0;
        padding-bottom: 0;
        &:nth-of-type(2) {
            @media screen and (max-width: 999px) {
                display: none;
            }
        }
    }
    .symptoms-selector-wrapper {
        margin-top: 0.75rem;
    }
    .label {
        font-weight: 500;
        &:not(:last-child) {
            margin-bottom: 0.25em;
        }
    }
    .control-label {
        text-align: left;
        margin-bottom: 2em;
    }
    .control:not(:last-child) {
        margin-bottom: 1.5rem;
        @media screen and (min-width: 1192px) {
            margin-bottom: 1rem;
        }
    }
    .symptoms-selector-description {
        display: block;
        line-height: 1.5;
    }
    @media screen and (min-width: 768px) {
        .control.is-horizontal > .control {
            display: block;
            flex-grow: 1.5;
            padding-top: 0.5em;
        }
        .control-label {
            margin-right: 0;
            margin-left: 1em;
        }
    }
    @media screen and (min-width: 1192px) {
        .control.is-horizontal > .control {
            flex-grow: 1.25;
        }
    }
</style>

