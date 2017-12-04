<template>
  <div id="SNScroller" class="" style="overflow: scroll;">
    <PageHeader class="mb3" :heading="$parent.news ? 'New Soap Note' : 'Soap Note'" />
    <Grid :flexAt="'l'" :columns="[{ s:'2of3' }, { s:'1of3' }]" :gutters="{ s:2, m:3 }">
      <!-- Main Card -->
      <Card :slot="1" :heading="'SOAP Note'">
        <CardContent>

          <div class="">
            <div class="">
              <label name="Subject" class="card-header">Subjective</label>
              <quill-editor
              :style="{'min-height': selected === 'subject' ? '50vh' : selected === null ? '125px': '50px'}"
              v-model="subjectiveTA"
              @click="setSelected('subject')"
              output="html"
              :options="editorOption"
              class="input--textarea"
              />
            </div>

            <div class="">
              <label name="Objective" class="card-header">Objective</label>
              <quill-editor
              :style="{'min-height': selected === 'objective' ? '50vh' : selected === null ? '125px': '50px'}"
              v-model="objectiveTA"
              id="objective"
              @click="setSelected('objective')"
              output="html"
              :options="editorOption"
              class="input--textarea"
              />
            </div>

            <div class="">
              <label name="Assessment" class="card-header">Assessment</label>
              <quill-editor
              :style="{'min-height': selected === 'assessment' ? '50vh' : selected === null ? '125px': '50px'}"
              v-model="assessmentTA"
              placeholder="Enter your text..."
              @click="setSelected('assessment')"
              output="html"
              :options="editorOption"
              class="input--textarea"
              />
            </div>

            <div class="">
              - - - - - - - FIELDS BELOW THIS LINE VISIBLE TO PATIENT  - - - - - - -
            </div>

            <div class="">
              <label name="Treatment"
              class="card-header">Plan/Treatment</label>
              <quill-editor
              :style="{'min-height': selected === 'treatment' ? '50vh' : selected === null ? '125px': '50px'}"
              v-model="planTA"
              @click="setSelected('treatment')"
              output="html"
              :options="editorOption"
              class="input--textarea"
              />
            </div>
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
            :options="editorOption"
            v-model="quickNotes"
            />
          </div>

        </CardContent>
      </Card>
    </Grid>

    <Grid :flexAt="'l'" :columns="[{ s:'1of1' }]" :gutters="{ s:2, m:3 }">
      <Card :slot="1">
        <CardContent>
          <!-- Save -->
          <div class="inline-centered">
            <button @click="submit()" :disabled="!subjectiveTA || !objectiveTA || !assessmentTA || !planTA" class="button">Save Changes</button>
            <button v-if="!$parent.news" @click="deleteModal()" class="button bg-danger">Archive Note</button>
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
            <span class="text">Archive SOAP Note</span>
          </h1>
          <p>Are you sure you want to archive this soap note?</p>
          <div class="button-wrapper">
            <button class="button button--cancel" @click="modalClose">Cancel</button>
            <button class="button" @click="deleteNote">Yes, Confirm</button>
          </div>
        </div>
      </div>
    </Modal>

  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import Modal from '../../../commons/Modal.vue';
import editorOption from '../util/quillEditorObject';
import { Card, CardContent, Grid, PageHeader } from 'layout';
export default {
    props: {
        patient: Object
    },
    components: {
        Modal,
        Card,
        CardContent,
        Grid,
        PageHeader
    },
    data() {
        return {
            subjectiveTA: null,
            objectiveTA: null,
            assessmentTA: null,
            planTA: null,
            deleteModalActive: false,
            selected: null,
            editorOption: editorOption
        };
    },
    methods: {
        setSubjectiveTA(data) {
            this.subjectiveTA = data;
        },
        setObjectiveTA(data) {
            this.objectiveTA = data;
        },
        setSelected(data) {
            this.selected = data;
            setTimeout(() => {
            let container = this.$el;
            container.scrollTop =
                data === 'subject' ? 0
                : data === 'objective' ? 100
                : data === 'assessment' ? 200
                : data === 'treatment' ? 200
                : 0;
            }, 300);
        },
        deleteModal() {
            this.deleteModalActive = true;
        },
        modalClose() {
            this.deleteModalActive = false;
        },
        setAssessmentTA(data) {
            this.assessmentTA = data;
        },
        setPlanTA(data) {
            this.planTA = data;
        },
        deleteNote() {
            axios.delete(`${this.$root.$data.apiUrl}/soap_notes/${this.$parent.propData.id}`)
                .then(() => {
                    this.deleteModalActive = false;
                    this.$parent.page = 0;
                    delete this.$parent.soap_notes[this.$parent.propData.id];
                    this.$parent.timeline.splice(this.$parent.index, 1);
                    this.$parent.index = null;
                    this.$parent.propData = null;
                    this.$parent.notificationMessage = "Successfully deleted!";
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
        },
        createSoapNote() {
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/soap_notes`, {
                subjective: this.subjectiveTA,
                objective: this.objectiveTA,
                assessment: this.assessmentTA,
                plan: this.planTA
            })
            .then(response => {
                let object = {};
                let returns = response.data.data;
                this.$parent.soap_notes[returns.id] = returns;
                object.data = returns;
                object.id = returns.id;
                object.date = moment(returns.attributes.created_at.date).format('dddd, MMM Do YYYY');
                object.original_date = returns.attributes.created_at.date;
                object.doctor = returns.attributes.doctor_name || "No Doctor";
                object.type = 'SOAP Note';
                this.$parent.timeline = [object].concat(this.$parent.timeline);
                this.$parent.news = false;
                this.$parent.setIndex(0);
                this.$parent.propData = returns;
                this.$parent.notificationMessage = "Successfully added!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
            });
        },
        editSoapNote() {
            axios.patch(`${this.$root.$data.apiUrl}/soap_notes/${this.$parent.propData.id}`, {
                subjective: this.subjectiveTA,
                objective: this.objectiveTA,
                assessment: this.assessmentTA,
                plan: this.planTA
            })
            .then(response => {
                this.$parent.soap_notes[response.data.data.id] = response.data.data;
                this.$parent.notificationMessage = "Successfully updated!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
            });
        },
        submit() {
            return this.$parent.news ? this.createSoapNote() : this.editSoapNote();
        }
    },
    computed: {
        subjective() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.subjective;
            this.setSubjectiveTA(data);
            return data;
        },
        objective() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.objective;
            this.setObjectiveTA(data);
            return data;
        },
        assessment() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.assessment;
            this.setAssessmentTA(data);
            return data;
        },
        plan() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.plan;
            this.setPlanTA(data);
            return data;
        },
        quickNotes() {
            const prop = this.$parent.propData;
            return prop && prop.attributes && prop.attributes.notes ? prop.attributes.notes : '';
        }
    },
    watch: {
        subjective(val) {
            if (!val) {
                let data = this.$parent.news ? '' : this.$parent.propData.attributes.subjective;
                this.setSubjectiveTA(data);
                return data;
            }
        },
        objective(val) {
            if (!val) {
                let data = this.$parent.news ? '' : this.$parent.propData.attributes.objective;
                this.setObjectiveTA(data);
                return data;
            }
        },
        assessment(val) {
            if (!val) {
                let data = this.$parent.news ? '' : this.$parent.propData.attributes.assessment;
                this.setAssessmentTA(data);
                return data;
            }
        },
        plan(val) {
            if (!val) {
                let data = this.$parent.news ? '' : this.$parent.propData.attributes.plan;
                this.setPlanTA(data);
                return data;
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
    .ql-editor {
        height: 200px !important;
    }
</style>
