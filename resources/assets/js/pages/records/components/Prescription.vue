<template>
  <div>
    <PageHeader class="mb3" :heading="$parent.news ? 'New Prescription' : 'Prescription'" />

    <!-- New -->
    <div v-if="$parent.news" class="">
      <Grid :flexAt="'l'" :columns="[{ s:'2of3' }, { s:'1of3' }]" :gutters="{ s:2, m:3 }">
        <Card :slot="1" :heading="'Prescription'">
          <CardContent>
            <div class="">
              <p>
                  You are about upload a prescription for client {{ patient.search_name }},
                  with adate of birth {{ patient.date_of_birth }}.
                  Please verify the name of the pharmacy before
                  uploading, so we can keep things organized.
                  Anything you upload will be viewable to your patient.
                  The only file format accepted is a PDF.
              </p>
            </div>
            <div class="card-heading-container">
                <div class="">
                    <label class="input__label">pharmacy</label>
                    <span class="custom-select">
                        <select @change="updateName($event)">
                            <option v-for="script in prescriptionList" :data-id="script">{{ script.name }}</option>
                        </select>
                    </span>
                </div>
                <div class="">
                    <label class="input__label">upload</label>
                    <label for="file-select-prescription" :class="{'disabled--cursor': !selected}">
                        <div class="">
                            <div class="">
                                <i class="fa fa-book"></i>
                                <p class="">Prescription (PDF)</p>
                            </div>
                        </div>
                    </label>
                    <input :class="{'disabled--cursor': !selected}" :disabled="!selected" @change="upload" type="file" id="file-select-prescription" accept=".pdf" hidden />
                </div>
                <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
            </div>
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
      <Grid :flexAt="'l'" :columns="[{ s:'2of3' }, { s:'1of3' }]" :gutters="{ s:2, m:3 }">
        <!-- Main Card -->
        <Card :slot="1" :heading="'Prescription'">
          <CardContent>
            <iframe :style="{height: $root.$data.permissions === 'patient' ? '80vh' : '70vh'}" class="w-100" :src="prescriptionUrl" />
          </CardContent>
        </Card>

        <!-- Quick Notes -->

        <Card :slot="2" :heading="'Quick Notes'">
          <CardContent>
            <div v-if="$root.$data.permissions !== 'patient'" class="">
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

      <Grid :flexAt="'l'" :columns="[{ s:'1of1' }]" :gutters="{ s:2, m:3 }">
        <Card :slot="1">
          <CardContent>
            <div v-if="$root.$data.permissions !== 'patient'" class="inline-centered">
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
import { Card, CardContent, Grid, PageHeader } from 'layout';
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
        PageHeader
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
            formData.append('notes', this.notes);
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/prescriptions`, formData)
            .then((response) => {
                let object = {};
                let returns = response.data.data;
                this.$parent.prescriptions[returns.id] = returns;
                object.data = returns;
                object.id = returns.id;
                object.date = moment(returns.attributes.created_at.date).format('dddd, MMM Do YYYY');
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
            return prop && prop.attributes && prop.attributes.notes ? prop.attributes.notes : '';
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
  }
</style>
