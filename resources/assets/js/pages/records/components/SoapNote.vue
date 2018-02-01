<template>
  <div id="SNScroller" style="overflow: scroll;">
    <Card>
        <CardContent>
            <Heading2>{{ $parent.news ? 'New Soap Note' : 'Soap Note' }}</Heading2>
        </CardContent>
    </Card>
    <Spacer isBottom :size="3" />
    <Grid :flexAt="'l'" :columns="[{ xxl:8 }, { xxl:4 }]" :gutters="{ s:2, m:3 }">
      <!-- Main Card -->
      <Card :slot="1" :heading="'SOAP Note'">
        <CardContent>

          <div>
            <div>
              <Heading3>Subjective</Heading3>
              <Spacer isBottom :size="2" />
              <quill-editor
              v-model="subjectiveTA"
              @change="updateCharacterCount('subjective')"
              @click="setSelected('subject')"
              output="html"
              :options="editorOption"
              />
              <div class="float-right">Characters left: {{ counts.subjective || soapCharacterCount }}</div>
              <Spacer isBottom :size="4" />
            </div>

            <div >
              <Heading3>Objective</Heading3>
              <Spacer isBottom :size="2" />
              <quill-editor
              v-model="objectiveTA"
              @change="updateCharacterCount('objective')"
              id="objective"
              @click="setSelected('objective')"
              output="html"
              :options="editorOption"
              />
              <div class="float-right">Characters left: {{ counts.objective || soapCharacterCount }}</div>
              <Spacer isBottom :size="4" />
            </div>

            <div>
              <Heading3>Assessment</Heading3>
              <Spacer isBottom :size="2" />
              <quill-editor
              v-model="assessmentTA"
              placeholder="Enter your text..."
              @click="setSelected('assessment')"
              @change="updateCharacterCount('assessment')"
              output="html"
              :options="editorOption"
              />
              <div class="float-right">Characters left: {{ counts.assessment || soapCharacterCount }}</div>
              <Spacer isBottom :size="4" />
            </div>

            <div>
              <Paragraph class="patient-disclaimer ttu tc" :size="'small'">
                <span class="disclaimer-inside">Fields below this line visible to patient</span>
              </Paragraph>
              <Spacer isBottom :size="4" />
            </div>

            <div class="">
              <Heading3>Plan/Treatment</Heading3>
              <Spacer isBottom :size="2" />
              <quill-editor
              v-model="planTA"
              @click="setSelected('treatment')"
              @change="updateCharacterCount('plan')"
              output="html"
              :options="editorOption"
              />
              <div class="float-right">Characters left: {{ counts.plan || soapCharacterCount }}</div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Quick Notes -->
      <Card :slot="2" :heading="'Quick Notes'">
        <CardContent>

          <!-- Editor -->
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

    <Grid :flexAt="'l'" :columns="[{ s:12 }]" :gutters="{ s:2, m:3 }">
      <Card :slot="1">
        <CardContent>
          <!-- Save -->
          <div class="inline-centered">
            <button @click="submit" :disabled="!subjectiveTA || !objectiveTA || !assessmentTA || !planTA || limits" class="button margin15">Save Changes</button>
            <button v-if="!$parent.news" @click="deleteModal" class="button bg-danger">Archive Note</button>
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
import simpleEditor from '../util/quillSimple';
import { Card, CardContent, Grid, PageHeader, Spacer } from 'layout';
import { Heading1, Heading2, Heading3, Paragraph } from 'typography';
export default {
    props: {
        patient: Object
    },
    components: {
        Modal,
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
            subjectiveTA: null,
            objectiveTA: null,
            assessmentTA: null,
            planTA: null,
            deleteModalActive: false,
            selected: null,
            editorOption: editorOption,
            simpleEditor: simpleEditor,
            notes: '',
            counts: {},
            soapCharacterCount: 16777215,
        };
    },
    methods: {
        updateCharacterCount(name) {
            let string = name + 'TA';
            this.counts[name] = this.soapCharacterCount - this[string].length;
        },
        setNotes(data) {
            this.notes = data;
        },
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
        characterCount(data, name) {
            let count = this.soapCharacterCount - data.length;
            this.counts[name] = count;
        },
        limits() {
            for (let i in this.counts) {
                if (this.counts[i] < 0) {
                    return true;
                }
            }
            return false;
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
            let object = {
                subjective: this.subjectiveTA,
                objective: this.objectiveTA,
                assessment: this.assessmentTA,
                plan: this.planTA,
                notes: this.notes
            };
            for (let i in object) {
                if (object[i] === '') {
                    delete object[i];
                }
            }
            axios.post(`${this.$root.$data.apiUrl}/patients/${this.$props.patient.id}/soap_notes`, object)
            .then(response => {
                let object = {};
                let returns = response.data.data;
                this.$parent.soap_notes[returns.id] = returns;
                object.data = returns;
                object.id = returns.id;
                object.date = moment.tz(returns.attributes.created_at.date, returns.attributes.created_at.timezone).tz(this.$root.$data.timezone).format('dddd, MMM Do YYYY');
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
            })
            .catch(error => {
                if (error.response) {
                    console.error(error.response);
                }
            });
        },
        editSoapNote() {
            let object = {
                subjective: this.subjectiveTA,
                objective: this.objectiveTA,
                assessment: this.assessmentTA,
                plan: this.planTA,
                notes: this.notes
            };
            for (let i in object) {
                if (object[i] === '') {
                    delete object[i];
                }
            }
            axios.patch(`${this.$root.$data.apiUrl}/soap_notes/${this.$parent.propData.id}`, object)
            .then(response => {
                let data = response.data.data;
                this.$parent.propData = data;
                this.$parent.soap_notes[data.id] = data;
                this.$parent.timeline = this.$parent.timeline.map(e => {
                    if (e.type === 'SOAP Note' && data.id == e.id) {
                        e.data = data;
                    }
                    return e;
                });
                this.$parent.notificationMessage = "Successfully updated!";
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
            })
            .catch(error => {
                if (error.response) {
                    console.error(error.response);
                }
            });
        },
        submit() {
            return this.$parent.news ? this.createSoapNote() : this.editSoapNote();
        },
        getSubject() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.subjective;
            this.setSubjectiveTA(data);
            this.characterCount(data, 'subjective');
            return data;
        },
        setObject() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.objective;
            this.setObjectiveTA(data);
            this.characterCount(data, 'objective');
            return data;
        },
        setAssessment() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.assessment;
            this.setAssessmentTA(data);
            this.characterCount(data, 'assessment');
            return data;
        },
        setPlan() {
            let data = this.$parent.news ? '' : this.$parent.propData.attributes.plan;
            this.setPlanTA(data);
            this.characterCount(data, 'plan');
            return data;
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
        subjective() {
            return this.getSubject();
        },
        objective() {
            return this.setObject();
        },
        assessment() {
            return this.setAssessment();
        },
        plan() {
            return this.setPlan();
        },
        quickNotes() {
            return this.findQuickNotes();
        }
    },
    watch: {
        subjective(val) {
            if (!val) {
                return this.getSubject();
            }
        },
        objective(val) {
            if (!val) {
                return this.setObject();
            }
        },
        assessment(val) {
            if (!val) {
                return this.setAssessment();
            }
        },
        plan(val) {
            if (!val) {
                return this.setPlan();
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
    .float-right {
        float: right;
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
    .patient-disclaimer {
      color: $color-copy-alt;
      position: relative;
      &:before {
        border-top: 2px dashed $color-copy-alt;
        content: "";
        left: 0;
        margin-top: -1px;
        position: absolute;
        top: 50%;
        width: 100%;
      }
      .disclaimer-inside {
        background: $color-white;
        padding: 0 5px;
        position: relative;
        z-index: 1;
      }
      .w-60 {
          width: 60%;
      }
      .w-30 {
          width: 30%;
      }
    }
</style>
