<template>
  <div>
    <PageHeader class="mb3" :heading="$parent.news ? 'New Lab Result' : 'Lab Result'" />
    <Grid v-if="$parent.news" :flexAt="'l'" :columns="[{ s:'2of3' }, { s:'1of3' }]" :gutters="{ s:2, m:3 }">

      <!-- News -->
      <Card :slot="1" :heading="'Lab Results'">
        <CardContent>
          <div class="">
            <div class="">
              <Paragraph>
                You are about upload a new lab test for client {{ patient.search_name }}
                (date of birth {{ patient.date_of_birth }}).
                Please verify the name of the lab and the type of lab test before
                uploading the results, so we can match the result with a lab test.
                The only file format accepted is a PDF.
              </Paragraph>
              <Spacer isBottom :size="5" />
            </div>
            <Card>
              <CardContent>
                <Grid :flexAt="'l'" :columns="[{ s:'1of3' }, { s:'1of3' }, { s:'1of3' }]" :gutters="{ s:2, m:3 }">
                  <div :slot="1">
                    <Heading3>Lab Name</Heading3>
                    <Spacer isBottom :size="2" />
                    <span class="custom-select">
                      <select @change="updateLabType($event)">
                        <option v-for="lab in labNameList" :data-id="lab">{{ lab }}</option>
                      </select>
                    </span>
                  </div>
                  <div :slot="2">
                    <Heading3>Lab Test</Heading3>
                    <Spacer isBottom :size="2" />
                    <span class="custom-select">
                      <select @change="updateLab($event)">
                        <option v-for="lab in labTestList" :data-id="lab.id">{{ lab.included.attributes.name }}</option>
                      </select>
                    </span>
                  </div>
                  <div :slot="3">
                    <Heading3>Upload</Heading3>
                    <Spacer isBottom :size="2" />
                    <label for="file-select-prescription" :class="{'disabled--cursor': !selectedLabName || !selectedLabType}" class="button button--grey block">
                      <i class="fa fa-book"></i>
                      <Paragraph class="ml1 mb0 dib">Lab Result (PDF)</Paragraph>
                    </label>
                    <input :class="{'disabled--cursor': !selectedLabName || !selectedLabType}" :disabled="!selectedLabName || !selectedLabType" @change="upload" type="file" id="file-select-prescription" accept=".pdf" hidden />
                  </div>
                  <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
                </Grid>
              </CardContent>
            </Card>
          </div>
        </CardContent>
      </Card>

      <Card :slot="2" :heading="'Quick Notes'">
        <CardContent>
          <div class="">
            <quill-editor
            output="html"
            :options="simpleEditor"
            v-model="notes"
            class="simple-editor"
            />
          </div>
        </CardContent>
      </Card>

    </Grid>

    <div v-if="!$parent.news">
      <Grid :flexAt="'l'" :columns="[{ s:'2of3' }, { s:'1of3' }]" :gutters="{ s:2, m:3 }">
        <!-- Not News -->
        <Card :slot="1" :heading="'Lab Results'">
          <CardContent>
            <div class="" >
              <iframe class="w-100" :style="'height:60vh'" :src="resultUrl" />
            </div>
          </CardContent>
        </Card>

        <Card :slot="2" :heading="'Quick Notes'">
          <CardContent>
            <div class="">
              <quill-editor
              output="html"
              :options="simpleEditor"
              v-model="notes"
              class="simple-editor"
              />
            </div>
          </CardContent>
        </Card>
      </Grid>

      <Grid v-if="$root.$data.permissions !== 'patient'" :flexAt="'l'" :columns="[{ s:'1of1' }]" :gutters="{ s:2, m:3 }">
        <Card :slot="1">
          <CardContent>
            <div class="inline-centered">
              <button @click="deleteModal()" class="button bg-danger">Archive Result</button>
            </div>

            <Modal
            :active="deleteModalActive"
            :onClose="modalClose"
            class="modal-wrapper"
            >
              <div class="card-content-wrap">
                <div class="inline-centered">
                  <h1 class="header-xlarge">
                    <span class="text">Archive Lab Result</span>
                  </h1>
                  <p>Are you sure you want to archive this lab result?</p>
                  <div class="button-wrapper">
                    <button class="button button--cancel" @click="modalClose">Cancel</button>
                    <button class="button" @click="deleteItem">Yes, Confirm</button>
                  </div>
                </div>
              </div>
            </Modal>
          </CardContent>
        </Card>

      </Grid>
    </div>

  </div>
</template>

<script>
import axios from 'axios';
import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
import {capitalize} from 'lodash';
import moment from 'moment';
import Modal from '../../../commons/Modal.vue';
import simpleEditor from '../util/quillSimple';
import { Card, CardContent, Grid, PageHeader, Spacer } from 'layout';
import { Paragraph, Heading3 } from 'typography';
export default {
    props: {
        patient: Object
    },
    components: {
        Modal,
        ClipLoader,
        Card,
        CardContent,
        Grid,
        PageHeader,
        Paragraph,
        Heading3,
        Spacer
    },
    data() {
        return {
            selectedLabName: null,
            selectedLabType: null,
            deleteModalActive: false,
            loading: false,
            simpleEditor: simpleEditor,
            notes: ''
        };
    },
    methods: {
        deleteModal() {
            this.deleteModalActive = true;
        },
        modalClose() {
            this.deleteModalActive = false;
        },
        deleteItem() {
            axios.delete(`${this.$root.$data.apiUrl}/lab/tests/results/${this.$parent.propData.id}`)
                .then(() => {
                    this.deleteModalActive = false;
                    this.$parent.page = 0;
                    delete this.$parent.lab_test_results[this.$parent.propData.id];
                    this.$parent.timeline.splice(this.$parent.index, 1);
                    this.$parent.index = null;
                    this.$parent.propData = null;
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        updateLabType(e) {
            this.selectedLabName = e.target.children[e.target.selectedIndex].dataset.id;
        },
        updateLab(e) {
            this.selectedLabType = e.target.children[e.target.selectedIndex].dataset.id;
        },
        upload(file) {
            this.loading = true;
            let formData = new FormData();
            formData.append('file', file.target.files[0]);
            formData.append('notes', this.notes);
            axios.post(`${this.$root.$data.apiUrl}/lab/tests/${this.selectedLabType}/results`, formData)
            .then((response) => {
                let results = response.data.included.map(e => {
                    let object = {};
                    let returns = e;
                    this.$parent.lab_test_results[returns.id] = returns;
                    object.data = returns;
                    object.id = returns.id;
                    object.date = moment(returns.attributes.created_at.date).format('dddd, MMM Do YYYY');
                    object.original_date = returns.attributes.created_at.date;
                    object.doctor = returns.attributes.doctor_name || "No Doctor";
                    object.type = returns.type.split('_').map(e => capitalize(e)).join(' ');
                    return object;
                });
                this.$parent.timeline = results.concat(this.$parent.timeline);
                this.loading = false;
                this.$parent.news = false;
                this.$parent.setIndex(0);
                this.$parent.propData = results[0];
                this.$parent.notificationMessage = "Successfully added!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
            });
        }
    },
    computed: {
        labNameList() {
            let labNames = Object.keys(this.$root.$data.labTypes);
            return [''].concat(labNames);
        },
        labTestList() {
            let labTests = Object.values(this.$parent.lab_tests);
            return labTests.length ? [{included: {attributes: {name: ''}}, id: 0}].concat(labTests) : [{included: {attributes: {name: 'No Lab Tests'}}, id: 0}];
        },
        resultUrl() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
        },
        quickNotes() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.notes ? prop.attributes.notes : '';
        }
    },
    watch: {
        resultUrl(val) {
            if (!val) {
                const prop = this.$parent.propData;
                return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
            }
        },
        labNameList(val) {
            if (!val) {
                let labNames = Object.keys(this.$root.$data.labTypes);
                return [''].concat(labNames);
            }
        },
        labTestList(val) {
            if (!val) {
                let labTests = Object.values(this.$parent.lab_tests);
                return labTests.length ? [{included: {attributes: {name: ''}}, id: 0}].concat(labTests) : [{included: {attributes: {name: 'No Lab Tests'}}, id: 0}];
            }
        },
        quickNotes(val) {
            if (!val) {
                const prop = this.$parent.propData;
                return prop && prop.attributes && prop.attributes.notes ? prop.attributes.notes : '';
            }
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .quill-editor {
      border: none;
      border-bottom: 1px solid #ccc;
      border-radius: 0;
      height: 150px;
      overflow: hidden;
      padding: 0;
    }
    .simple-editor {
      border-bottom: none;
      height: 250px;
    }
    .disabled--cursor {
      cursor: not-allowed;
      opacity: 0.5;
    }
</style>
