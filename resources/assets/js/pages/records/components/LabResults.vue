<template>
  <div>
    <Card>
        <CardContent>
            <Heading2>{{ $parent.news ? 'New Lab Result' : 'Lab Result' }}</Heading2>
        </CardContent>
    </Card>
    <Spacer isBottom :size="3" />
    <Grid v-if="$parent.news" :flexAt="'l'" :columns="[{ xxl:'2of3' }, { xxl:'1of3' }]" :gutters="{ s:2, m:3 }">

      <!-- News -->
      <Card :slot="1" :heading="'Lab Results'">
        <CardContent>
          <div class="">
            <div class="">
              <Paragraph>
                You are about upload a new lab test for client {{ patient.search_name }}, born {{ patient.date_of_birth }}. Please verify the name of the lab and the type of lab test before uploading the results, so we can match the result with a lab test. The only file format accepted is PDF.
              </Paragraph>
              <Spacer isBottom :size="5" />
            </div>
            <Card>
              <CardContent>
                <Grid :flexAt="'l'" :columns="[{ m:'1of3' }, { m:'1of3' }, { m:'1of3' }]" :gutters="{ m:3 }">
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
                        <option v-for="lab in userLabTestList" :data-id="lab.id">{{ lab.attributes.sku_name }} {{ lab.id ? `(#${lab.id})` : '' }}</option>
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
      <Grid :flexAt="'l'" :columns="[{ xxl:'2of3' }, { xxl:'1of3' }]" :gutters="{ s:2, m:3 }">
        <!-- Not News -->
        <Card :class="{'f-100': $root.$data.permissions === 'patient'}" :slot="1" :heading="$root.$data.labTests[$parent.lab_tests[$parent.propData.attributes.lab_test_id].attributes.sku_id].attributes.name + ' Results'">
          <CardContent>
              <iframe class="w-100" :style="'height:60vh'" :src="resultUrl" />
          </CardContent>
        </Card>

        <Card v-if="$root.$data.permissions !== 'patient'" :slot="2" :heading="'Quick Notes'">
          <CardContent>
            <div>
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
              <button @click="updateQuickNotes" class="button margin15">Save Changes</button>
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
import moment from 'moment';
import Modal from '../../../commons/Modal.vue';
import simpleEditor from '../util/quillSimple';
import { Card, CardContent, Grid, PageHeader, Spacer } from 'layout';
import { Paragraph, Heading1, Heading2, Heading3 } from 'typography';
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
        Heading1,
        Heading2,
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
        updateQuickNotes() {
            axios.patch(`${this.$root.$data.apiUrl}/lab/tests/results/${this.$parent.propData.id}`, { notes:  this.notes })
                .then((response) => {
                    let data = response.data.data;
                    this.$parent.propData = data;
                    this.$parent.lab_test_results[data.id] = data;
                    let regex = new RegExp('Result', 'ig');
                    this.$parent.timeline.map(e => {
                        if (regex.test(e.type) && data.id == e.id) {
                            e.data = data;
                        }
                        return e;
                    });
                    this.$parent.notificationMessage = "Successfully updated!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        setNotes(data) {
            this.notes = data;
        },
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
            if (this.notes && this.notes !== '') {
                formData.append('notes', this.notes);
            }
            axios.post(`${this.$root.$data.apiUrl}/lab/tests/${this.selectedLabType}/results`, formData)
            .then((response) => {
                let object = {};
                let returns = response.data.data;
                this.$parent.lab_test_results[returns.id] = returns;
                object.data = returns;
                object.id = returns.id;
                object.date = moment.tz(returns.attributes.created_at.date, returns.attributes.created_at.timezone).tz(this.$root.$data.timezone).format('dddd, MMM Do YYYY');
                object.original_date = returns.attributes.created_at.date;
                object.doctor = returns.attributes.doctor_name || "No Doctor";
                object.type = 'Lab Test Result';
                this.$parent.timeline = [object].concat(this.$parent.timeline);
                this.loading = false;
                this.$parent.news = false;
                this.$parent.setIndex(0);
                this.$parent.propData = returns;
                this.$parent.notificationMessage = "Successfully added!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
            });
        },
        findLabList() {
            let labNames = Object.keys(this.$root.$data.labTypes);
            return [''].concat(labNames);
        },
        findUserLabList() {
            let userLabTests = Object.values(this.$parent.lab_tests).map(e => {
                e.attributes.sku_name = Object.values(this.$root.$data.labTests).filter(e1 => e1.id == e.attributes.sku_id).pop().attributes.name;
                return e;
            });
            return userLabTests.length ? [{attributes: {name: ''}, id: 0}].concat(userLabTests) : [{attributes: {name: 'No Lab Tests'}, id: 0}];
        },
        findUrl() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
        },
        findQuickNotes() {
            const prop = this.$parent.propData;
            if (prop && prop.attributes && prop.attributes.notes) {
                let notes = !prop.attributes.notes ? '' : prop.attributes.notes;
                this.$parent.news ? this.setNotes('') : this.setNotes(notes);
            } else {
                this.setNotes('');
            }
            return this.$parent.news ? '' : prop && prop.attributes && prop.attributes.notes ? prop.attributes.notes : '';
        }
    },
    computed: {
        labNameList() {
            return this.findLabList();
        },
        userLabTestList() {
            return this.findUserLabList();
        },
        resultUrl() {
            return this.findUrl();
        },
        quickNotes() {
             return this.findQuickNotes();
        }
    },
    watch: {
        resultUrl(val) {
            if (!val) {
                return this.findUrl();
            }
        },
        labNameList(val) {
            if (!val) {
                return this.findLabList();
            }
        },
        userLabTestList(val) {
            if (!val) {
                return this.findUserLabList();
            }
        },
        quickNotes(val) {
            if (!val) {
                return this.findQuickNotes();
            }
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';
    .quill-editor {
      border: none;
      border-radius: 0;
      overflow: hidden;
      padding: 0;
    }
    .simple-editor {
      border-bottom: none;
      height: 268px;
    }
    .disabled--cursor {
      cursor: not-allowed;
      opacity: 0.5;
    }
    .ql-toolbar.ql-snow {
      height: 130px;
      border: 1px solid #eee;
    }
    .w-60 {
        width: 60%;
    }
    .w-30 {
        width: 30%;
    }
    .f-100 {
        flex-basis: calc(100% - 1.3rem) !important;
    }
</style>
