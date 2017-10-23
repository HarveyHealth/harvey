<template>
    <transition
        mode="out-in"
        enter-active-class="animated fadeIn"
        leave-active-class="animated fadeOut"
    >
        <div
            v-show="show"
            :class="['notification', 'is-' + alertData.type]"
        >
            <button class="delete"
                v-show="alertData.important"
                @click="show = false"
            ></button>
            {{ alertData.text }}
        </div>
    </transition>
</template>

<script>
import {assign} from 'lodash';

export default {
    name: 'alert',
    data() {
        return {
            show: false,
            alertData: {
                type: 'info',
                timeout: 2000,
                important: false
            }
        };
    },
    created() {
        this.$eventHub.$on('alert', (data) => {
            this.show = true;
            this.alertData = assign({}, this.alertData, data);

            if (!this.alertData.important) {
                setTimeout(
                    () => this.show = false,
                    this.alertData.timeout
                );
            }
        });
    }
};
</script>
