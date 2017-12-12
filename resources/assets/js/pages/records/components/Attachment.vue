<template>
  <div>
    <PageHeader class="mb3" :heading="$parent.news ? 'New Attachment' : 'Attachment'" />

    <!-- New -->
    <div v-if="$parent.news" class="">
      <Grid :flexAt="'l'" :columns="[{ s:'2of3' }, { s:'1of3' }]" :gutters="{ s:2, m:3 }">
        <Card :slot="1" :heading="'Attachment'">
          <CardContent>
            <div class="">
              <Paragraph>
                You are about upload an attachment for client {{ patient.search_name }}, born {{ patient.date_of_birth }}. Please verify the name of attachment before uploading, so we can keep things organized. Anything you upload will be viewable to your doctor and doctor's assisstants only. The only file format accepted is a PDF.
              </Paragraph>
              <Spacer isBottom :size="5" />
            </div>

            <Card>
              <CardContent>
                <Grid :flexAt="'l'" :columns="[{ s:'1of2' }, { s:'1of2' }]" :gutters="{ s:2, m:3 }">
                  <div :slot="1">
                    <Heading3>File Upload</Heading3>
                    <Spacer isBottom :size="2" />
                    <input class="input--text" v-model="fileName" placeholder="Enter file name">
                  </div>
                  <div :slot="1">
                    <Heading3>Upload</Heading3>
                    <Spacer isBottom :size="2" />
                    <label for="file-select-prescription" :class="{'disabled--cursor': fileName === ''}" class="button button--grey block">
                      <i class="fa fa-book"></i>
                      <Paragraph class="ml1 mb0 dib">Attachment (PDF)</Paragraph>
                    </label>
                    <input :class="{'disabled--cursor': fileName === ''}" :disabled="fileName === ''" @change="upload" type="file" id="file-select-prescription" accept=".pdf" hidden />
                  </div>
                  <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
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
      <Grid :flexAt="'l'" :columns="[{ s:'2of3' }, { s:'1of3' }]" :gutters="{ s:2, m:3 }">
        <!-- Main Card -->
        <Card :slot="1" :heading="startCase($parent.propData.attributes.name) +  ' Attachment'">
          <CardContent>

            <iframe :style="'height:60vh'" class="w-100" :src="attachmentUrl" />
          </CardContent>
        </Card>

        <Card :slot="1" :heading="'Quick Notes'">
          <CardContent>
            <div v-if="$root.$data.permissions !== 'patient'" class="">
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
                <button @click="deleteModal()" class="button bg-danger">Archive Attachment</button>
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
              <span class="text">Archive Attachment</span>
            </h1>
            <p>Are you sure you want to archive this attachment?</p>
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
import {mask} from 'vue-the-mask';
import ClipLoader from 'vue-spinner/src/ClipLoader.vue';
import {capitalize, startCase} from 'lodash';
import moment from 'moment';
import Modal from '../../../commons/Modal.vue';
import simpleEditor from '../util/quillSimple';
import axios from 'axios';
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
    directives: {
        mask
    },
    data() {
        return {
            fileName: '',
            deleteModalActive: false,
            loading: false,
            simpleEditor: simpleEditor,
            notes: ''
        };
    },
    methods: {
        startCase(data) {
            return startCase(data);
        },
        updateQuickNotes() {
            axios.patch(`${this.$root.$data.apiUrl}/attachments/${this.$parent.propData.id}`, { notes:  this.notes })
                .then((response) => {
                    let data = response.data.data;
                    this.$parent.propData = data;
                    this.$parent.attachments[data.id] = data;
                    this.$parent.timeline.map(e => {
                        if (e.type === 'Attachment' && data.id == e.data.id) {
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
            axios.delete(`${this.$root.$data.apiUrl}/attachments/${this.$parent.propData.id}`)
                .then(() => {
                    this.deleteModalActive = false;
                    this.$parent.page = 0;
                    delete this.$parent.attachments[this.$parent.propData.id];
                    this.$parent.timeline.splice(this.$parent.index, 1);
                    this.$parent.index = null;
                    this.$parent.propData = null;
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        upload(file) {
            let formData = new FormData();
            this.loading = true;
            formData.append('file', file.target.files[0]);
            if (this.notes && this.notes !== '') {
                formData.append('notes', this.notes);
            }
            formData.append('name', this.fileName);
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/attachments`, formData)
            .then((response) => {
                let object = {};
                let returns = response.data.data;
                this.$parent.attachments[returns.id] = returns;
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
        }
    },
    computed: {
        attachmentUrl() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
        },
        quickNotes() {
            const prop = this.$parent.propData;
            if (prop && prop.attributes && prop.attributes.notes) {
                this.$parent.news ? this.setNotes('') : this.setNotes(prop.attributes.notes);
            }
            return this.$parent.news ? '' : prop && prop.attributes && prop.attributes.notes ? prop.attributes.notes : '';
        }
    },
    watch: {
        attachmentUrl(val) {
            if (!val) {
                const prop = this.$parent.propData;
                if (prop && prop.attributes && prop.attributes.notes) {
                    this.setNotes(prop.attributes.notes);
                }
                return prop && prop.attributes && prop.attributes.url ? prop.attributes.url : '';
            }
        },
        quickNotes(val) {
            if (!val) {
               const prop = this.$parent.propData;
                if (prop && prop.attributes && prop.attributes.notes) {
                    this.$parent.news ? this.setNotes('') : this.setNotes(prop.attributes.notes);
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
</style>
