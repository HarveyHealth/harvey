<template>
    <div>
        <div class="mb3 mha mw5 mw6-m tc">
            <Grid :columns="[{m:4},{m:4},{m:4}]" :gutters="{s:3}">
                <div :slot="1" class="relative tl tc-m pl4 pa0-m">
                    <button class="btn-reset f5 dark-gray tl pa2" @click="product = 'consultations'" :style="isActive('consultations')">
                        <i class="dib pr2 f5 fa fa-desktop"></i>
                        Consultations
                    </button>
                </div>
                <div :slot="2" class="relative tl tc-m pl4 pa0-m">
                    <button class="btn-reset f5 dark-gray tl pa2" @click="product = 'lab_tests'" :style="isActive('lab_tests')">
                        <i class="dib pr2 f5 fa fa-flask"></i>
                        Lab Tests
                    </button>
                </div>
                <div :slot="3" class="relative tl tc-m pl4 pa0-m">
                    <button class="btn-reset f5 dark-gray tl pa2" @click="product = 'supplements'" :style="isActive('supplements')">
                        <i class="dib pr2 f5 fa fa-pagelines"></i>
                        Supplements
                    </button>
                </div>
            </Grid>
        </div>
        <svg viewBox="174 100 959 593" style="width: 100%; height: auto;">
            <g id="g5">
                <path v-for="state in states" :d="state.d" :fill="getFill(state.id)" class="state-path" />
            </g>
        </svg>
        <div class="pt5" style="height: 220px;">
            <div class="f6 f5-m i gray mb2" v-show="serviceable.length">
                <span class="blue fw6">Serviceable</span>: {{ serviceable.join(', ') }}
            </div>
            <div class="f6 f5-m i gray mb2" v-show="notServiceable.length">
                <span class="dark-gray fw6">Not Serviceable</span>: {{ notServiceable.join(', ') }}
            </div>
            <div class="f6 f5-m i gray mb2" v-show="requiresConsult.length">
                <span class="alt fw6">Requires Consultation</span>: {{ requiresConsult.join(', ') }}
            </div>
        </div>
    </div>
</template>

<script>
import states from './stateData';
import { Grid } from 'layout';

export default {
    components: { Grid },
    props: {
        productAvailability: Object
    },
    data() {
        return {
            states,
            product: 'consultations'
        };
    },
    computed: {
        notServiceable() {
            return this.productAvailability[this.product].not_serviceable || [];
        },
        requiresConsult() {
            return this.productAvailability[this.product].require_consultation || [];
        },
        serviceable() {
            return this.productAvailability[this.product].serviceable || [];
        }
    },
    methods: {
        isActive(product) {
            return this.product === product
                ? { color: App.Config.misc.colors.blue }
                : null;
        },
        getFill(state) {
            if (this.serviceable.indexOf(state) > -1) return App.Config.misc.colors.blue;
            if (this.requiresConsult.indexOf(state) > -1) return App.Config.misc.colors.alt;
            return App.Config.misc.colors['gray-5'];
        }
    },
    beforeMount() {
        App.setNavInversion(true);
    }
};
</script>

<style lang="scss" scoped>
    .btn-icon {
        left: 0;
        top: 4px;
    }

    .state-path {
        transition: fill 200ms ease-in-out;
    }
</style>
