<template>
    <div>
        <div>
            <button @click="product = 'consultations'">Consultations</button>
            <button @click="product = 'lab_tests'">Lab Tests</button>
            <button @click="product = 'supplements'">Supplements</button>
        </div>
        <svg xmlns:cc="http://creativecommons.org/ns#"
            xmlns:dc="http://purl.org/dc/elements/1.1/"
            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
            xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
            xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
            xmlns:svg="http://www.w3.org/2000/svg"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            version="1.1"
            preserveAspectRatio="xMinYMin meet"
            x="0px" y="0px" width="959px" height="593px"
            viewBox="174 100 959 593"
            enable-background="new 174 100 959 593"
            xml:space="preserve"
            style="width: 100%; height: auto;"
        >
            <g id="g5">
                <path v-for="state in states" :id="state.id" :d="state.d" :fill="getFill(state.id)" />

                <g id="DC">
                    <path id="path58" fill="#D3D3D3" d="M975.8,353.8l-1.1-1.6l-1-0.8l1.1-1.6l2.2,1.5L975.8,353.8z"/>
                    <circle id="circle60" fill="#D3D3D3" stroke="#FFFFFF" stroke-width="1.5" cx="975.3" cy="351.8" r="5" />
                </g>
            </g>
            <path id="path67" fill="none" stroke="#A9A9A9" stroke-width="2" d="M385,593v55l36,45 M174,525h144l67,68h86l53,54v46" />
        </svg>
    </div>
</template>

<script>
import states from './stateData';

export default {
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
        serviceable() {
            return this.productAvailability[this.product].serviceable;
        }
    },
    methods: {
        getFill(state) {
            return this.serviceable.indexOf(state) > -1
                ? App.Config.misc.colors.green
                : App.Config.misc.colors['gray-5'];
        }
    },
    beforeMount() {
        App.setNavInversion(true);
    }
};
</script>
