<template>
    <div class="level is-fullwidth">
        <div class="level-item">
            <span class="is-circle test-marker test-marker--type-mn">MN</span>
        </div>
        <div class="level-item">
            <div>
                <p><strong>Micronutrient Blood Test</strong></p>
                <small class="color-greylight">Ordered on {{test.created_at | datetime('ddd, MMM Do, YYYY') }}</small>
            </div>
        </div>
        <div class="level-item">
            <transition
                mode="out-in"
                enter-active-class="animated animated-fast fadeIn"
                leave-active-class="animated animated-fast fadeOut"
            >   
                <template v-if="test.results_key">
                    <div>
                        <button class="button is-info" @click="toggleDisplayResult">View Results</button>
                        <div class="modal" :class="{'is-active': displayResult}">
                            <div class="modal-background" @click="toggleDisplayResult"></div>
                            <div class="modal-content">
                                <iframe :src="test.results_key" frameborder="0" width="100%" height="100%"></iframe>
                            </div>
                            <button class="modal-close" @click="toggleDisplayResult"></button>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <button v-if="userType == 'patient'" class="button is-disabled">Pending</button>
                    <FileUploader v-else
                        :action="'/tests/' + test.id +'/results'"
                        @uploaded="onUpload"
                    ></FileUploader>
                </template>
            </transition>
        </div>
    </div>
</template>

<script>
    import FileUploader from '../_includes/FileUploader.vue';

    export default {
        props: ['test', 'userType'],
        data() {
            return {
                displayResult: false
            }
        },
        components: {
            FileUploader
        },
        methods: {
            onUpload(data) {
                this.test.results_key = data.data.data.results_key;
            },
            toggleDisplayResult() {
                this.displayResult = !this.displayResult;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .test-marker {
        color: #fff;
    }
    .test-marker--type-mn {
        background-color: hsl(348, 100%, 61%);
    }
    .level-item {
        &:not(:last-child) {
            flex-grow: 0;
            margin-right: 1rem;
        }
        &:last-child {
            justify-content: flex-end;
        }
    }
    .modal-content {
        width: 90%;
        height: 100%;
    }
</style>
