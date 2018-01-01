<template>
  <div>
        <Card>
          <CardContent>
            <Heading2>{{ $parent.news ? 'New Prescription' : 'Prescription' }}</Heading2>
            </CardContent>
        </Card>
    <Spacer isBottom :size="3" />
    <!-- New -->
    <div v-if="$parent.news" class="">
      <Grid :flexAt="'l'" :columns="[{ xxl:'2of3' }, { xxl:'1of3' }]" :gutters="{ s:2, m:3 }">
        <Card :slot="1" :heading="'Prescription'">
          <CardContent>
            <div class="">
              <Paragraph>
                  You are about upload a prescription for client {{ patient.search_name }}, born {{ patient.date_of_birth }}. Please verify the name of the pharmacy before uploading, so we keep things organized. Anything you upload will be viewable to your patient. The only file format accepted is PDF.
              </Paragraph>
              <Spacer isBottom :size="5" />
            </div>
            <Card>
              <CardContent>
                <Grid :flexAt="'l'" :columns="[{ m:'1of2' }, { m:'1of2' }]" :gutters="{ m:3 }">
                  <div :slot="1">
                      <Heading3>Pharmacy</Heading3>
                      <Spacer isBottom :size="2" />
                      <span class="custom-select">
                          <select @change="updateName($event)">
                              <option v-for="script in prescriptionList" :data-id="script">{{ script.name }}</option>
                          </select>
                      </span>
                  </div>

                  <div :slot="2">
                      <Heading3>Upload</Heading3>
                      <Spacer isBottom :size="2" />
                      <label for="file-select-prescription" :class="{'disabled--cursor': !selected}" class="button button--grey block">
                          <i class="fa fa-book"></i>
                          <Paragraph class="ml1 mb0 dib">Prescription (PDF)</Paragraph>
                      </label>
                      <input :class="{'disabled--cursor': !selected}" :disabled="!selected" @change="upload" type="file" id="file-select-prescription" accept=".pdf" hidden />

                      <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
                  </div>
                </Grid>
              </CardContent>
            </Card>
          </CardContent>
        </Card>

        <!-- Quick Notes -->
        <Card :slot="2" :heading="'Quick Notes'">
          <CardContent>

            <!-- Editor -->
            <div class="">
              <quill-editor
              output="html"
              :options="simpleEditor"
              v-model="quickNotes"
              class="simple-editor"
              />
            </div>

          </CardContent>
        </Card>

      </Grid>
    </div>

    <!-- Existing -->
    <div class="" v-if="!$parent.news">
      <Grid :flexAt="'l'" :columns="[{ xxl:'2of3' }, { xxl:'1of3' }]" :gutters="{ s:2, m:3 }">
        <!-- Main Card -->
        <Card :class="{'f-100': $root.$data.permissions === 'patient'}" :slot="1" :heading="'Prescription'">
          <CardContent>
            <iframe :style="'height:60vh'" class="w-100" :src="prescriptionUrl" />
          </CardContent>
        </Card>

        <!-- Quick Notes -->

        <Card v-if="$root.$data.permissions !== 'patient'" :slot="2" :heading="'Quick Notes'">
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
                <button @click="updateQuickNotes" class="button margin15">Save Changes</button>
                <button @click="deleteModal()" class="button bg-danger">Archive Prescription</button>
            </div>
          </CardContent>
        </Card>
      </Grid>

      <Modal
        :active="deleteModalActive"
        :onClose="modalClose"
        class="modal-wrapper"
      >
          <div class="card-content-wrap">
              <div class="inline-centered">
                  <h1 class="header-xlarge">
                      <span class="text">Archive Prescription</span>
                  </h1>
                  <p>Are you sure you want to archive this prescription?</p>
                  <div class="button-wrapper">
                      <button class="button button--cancel" @click="modalClose">Cancel</button>
                      <button class="button" @click="deleteItem">Yes, Confirm</button>
                  </div>
              </div>
          </div>
      </Modal>
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
import { Heading1, Heading2, Paragraph, Heading3 } from 'typography';
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
            selected: null,
            deleteModalActive: false,
            loading: false,
            notes: '',
            simpleEditor: simpleEditor
        };
    },
    methods: {
        updateQuickNotes() {
            axios.patch(`${this.$root.$data.apiUrl}/prescriptions/${this.$parent.propData.id}`, { notes:  this.notes })
                .then((response) => {
                    let data = response.data.data;
                    this.$parent.propData = data;
                    this.$parent.prescriptions[data.id] = data;
                    let regex = new RegExp('Prescription', 'ig');
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
            axios.delete(`${this.$root.$data.apiUrl}/prescriptions/${this.$parent.propData.id}`)
                .then(() => {
                    this.deleteModalActive = false;
                    this.$parent.page = 0;
                    delete this.$parent.prescriptions[this.$parent.propData.id];
                    this.$parent.timeline.splice(this.$parent.index, 1);
                    this.$parent.index = null;
                    this.$parent.propData = null;
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        upload(file) {
            this.loading = true;
            let formData = new FormData();
            formData.append('file', file.target.files[0]);
            formData.append('name', this.selected);
            if (this.notes && this.notes !== '') {
                formData.append('notes', this.notes);
            }
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/prescriptions`, formData)
            .then((response) => {
                let object = {};
                let returns = response.data.data;
                this.$parent.prescriptions[returns.id] = returns;
                object.data = returns;
                object.id = returns.id;
                object.date = moment.tz(returns.attributes.created_at.date, returns.attributes.created_at.timezone).tz(this.$root.$data.timezone).format('dddd, MMM Do YYYY');
                object.original_date = returns.attributes.created_at.date;
                object.doctor = returns.attributes.doctor_name || "No Doctor";
                object.type = returns.type.split('_').map(e => capitalize(e)).join(' ');
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
        updateName(e) {
            this.selected = e.target.children[e.target.selectedIndex].dataset.id;
        }
    },
    computed: {
        prescriptionList() {
            return [{name: ''}].concat([{name: 'Fullscript'}]);
        },
        prescriptionUrl() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
        },
        quickNotes() {
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
    watch : {
        prescriptionUrl(val) {
            if (!val) {
                const prop = this.$parent.propData;
                return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
            }
        },
        quickNotes(val) {
            if (!val) {
                 const prop = this.$parent.propData;
                if (prop && prop.attributes && prop.attributes.notes) {
                    let notes = !prop.attributes.notes ? '' : prop.attributes.notes;
                    this.$parent.news ? this.setNotes('') : this.setNotes(notes);
                } else {
                    this.setNotes('');
                }
                return this.$parent.news ? '' : prop && prop.attributes && prop.attributes.notes ? prop.attributes.notes : '';
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
