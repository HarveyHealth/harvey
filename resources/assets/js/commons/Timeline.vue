<template>
    <div>
        <!-- Loading State -->
        <div v-if="loading">
            <ClipLoader :color="'#82BEF2'" :loading="loading" />
        </div>

        <!-- Loaded -->
        <div v-if="!loading">
            <!-- Timeline Items -->

            <slot></slot>

            <div class="timeline-item" v-for="(item, key) in items" :name="'key-' + key">
              <Grid :flexAt="'l'" :columns="[{ s:2 }, { s:10 }]">

                <!-- Dot -->
                <div :slot="1" class="self-center">
                  <div @click="item.onClick" :class="key === index ? 'on' : ''" class="timeline-selection">
                      <div :class="key === index ? 'subOn' : ''" />
                  </div>
                </div>

                <!-- Info -->
                <div :slot="2" class="self-center">
                  <div @click="item.onClick" class="timeline-info" :class="key === index ? 'info-on' : ''">
                      <Grid :flexAt="'l'" :columns="[{ s:9 }, { s:3 }]">
                        <div :slot="1" class="self-center">
                            <span class="db">{{ item.type }}</span>
                            <span class="db">{{ item.date }}</span>
                            <span class="db">{{ item.doctor }}</span>
                        </div>
                        <div :slot="2" class="timeline-icon self-center">
                            <i :class="`fa fa-${
                                item.type === 'SOAP Note' ? 'pencil-square-o ' :
                                item.type === 'Prescription' ? 'file-excel-o' :
                                attachmentRegex.test(item.type) ? 'paperclip' :
                                item.type === 'Intake' ? 'clipboard' :
                                resultsRegex.test(item.type) ? 'flask' :
                                'book'
                            }`"></i>
                        </div>
                      </Grid>
                  </div>
                </div>
              </Grid>
            </div>

            <!-- Empty Message -->
            <div class="full-width" v-if="items.length === 0">
                <p class="timeline-empty-message">{{ emptyMessage }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import { Grid, Spacer } from 'layout';
import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
export default {
    components: {
        ClipLoader,
        Grid,
        Spacer
    },
    data() {
        return {
            resultsRegex: /(^|\W)Result($|\W)/,
            attachmentRegex: /(^|\W)Attachment($|\W)/
        };
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
    .timeline-item {
      padding-bottom: .7em;
      position: relative;

      &:before {
        background: #d8d8d8;
        content: "";
        height: 100%;
        left: 9px;
        position: absolute;
        width: 2px;
      }

      &:first-of-type {
        &:before {
          top: 50%;
        }
      }
      &:last-of-type {
        padding-bottom: 0;

        &:before {
          height: 50%;
        }
      }
    }
    .on {
        background-color: #a1bbd0 !important;
    }
    .subOn {
        height: 8px;
        width: 8px;
        background-color: white;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -4px;
        margin-top: -4px;
    }
    .timeline-selection {
        background-color: #d8d8d8;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        cursor: pointer;
        position: relative;
        z-index: 1;
    }
    .timeline-icon {
      font-size: 20px;
      text-align: center;
    }
    .timeline-info {
        border-radius: 8px;
        background-color: #fcfcfc;
        border: solid 1px #ebebeb;
        padding: 5px 10px;
        cursor: pointer;

        &.info-on {
          border: solid 1px #a1bbd0;
          position: relative;

          &:before {
            content: "";
            left: -8px;
            position: absolute;
            top: 50%;
            margin-top: -8px;
            width: 0;
            height: 0;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-right:8px solid #a1bbd0;
          }
        }
    }

    .timeline-empty-message {
        text-align: center;
        font-size: 1.2rem;
        color: #EDA1A6;
    }
</style>
