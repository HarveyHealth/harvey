<template>
    <div>
        <div v-if="!loading">
            <div v-for="(item, key) in items" class="timeline-height" :name="'key-' + key">
                <div @click="item.onClick" :class="key === index ? 'on' : ''" class="timeline-selection">
                    <div :class="key === index ? 'subOn' : ''" />
                </div>
                <div @click="item.onClick" class="timeline-info">
                    <div class="width-80 float-left">
                        <span class="full-width float-left">{{ item.type }}</span>
                        <span class="full-width float-left">{{ item.date }}</span>
                        <span class="full-width float-left">{{ item.doctor }}</span>
                    </div>
                    <div class="float-right width-20">
                        <i :class="`font-timeline fa fa-${
                            item.type === 'SOAP Note' ? 'pencil-square-o ' :
                            item.type === 'Prescription' ? 'file-excel-o' :
                            item.type === 'Attachment' ? 'paperclip' :
                            item.type === 'Intake' ? 'clipboard' :
                            item.type === 'Lab Test Result' ? 'flask' :
                            'book'
                        }`"></i>
                    </div>
                </div>
            </div>
            <div class="full-width" v-if="items.length === 0">
                <p class="timeline-empty-message">{{ emptyMessage }}</p>
            </div>
        </div>
        <div v-if="loading">
            <ClipLoader :color="'#82BEF2'" :loading="loading" />
        </div>
    </div>
</template>

<script>
import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
export default {
    components: {
        ClipLoader
    },
    props: {
        items: {
            type: Array,
            required: true
        },
        index: {
            type: Number
        },
        loading: {
            type: Boolean,
            required: true
        },
        emptyMessage: {
            type: String,
            required: true
        }
    }
};
</script>

<style lang="scss">
    .on {
        background-color: #a1bbd0 !important;
    }
    .subOn {
        height: 10px;
        width: 10px; 
        background-color: white; 
        border-radius: 50%;
        margin: 27.5%;
    }
    .timeline-selection {
        background-color: #d8d8d8; 
        border-radius: 50%; 
        width: 25px; 
        height: 25px; 
        float: left; 
        margin: 5% 15px 5% 0; 
        cursor: pointer;
    }
    .timeline-info {
        width: 85%;
        border-radius: 8px;
        height: 70px;
        background-color: #fcfcfc;
        border: solid 1px #ebebeb;
        float: left;
        padding: 5px 10px;
        cursor: pointer;
    }
    .full-width {
        width: 100%;
    }
    .width-80 {
        width: 80%;
    }
    .width-20 {
        width: 20%;
    }
    .float-left {
        float: left;
    }
    .float-right {
        float: right;
    }
    .font-timeline {
        font-size: 36px;
        display: flex;
        justify-content: center;
        padding: 10px 0;
    }
    .timeline-height {
        height: 80px;
    }
    .timeline-empty-message {
        text-align: center;
        font-size: 1.2rem !important;
        color: #EDA1A6 !important;
    }
</style>