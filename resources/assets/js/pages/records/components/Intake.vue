<template>
    <div id="intake">
      <PageHeader class="mb3" :heading="'Intake Information'" />
      <Grid :flexAt="'l'" :columns="[{ s:'1of1' }]" :gutters="{ s:2, m:3 }">
        <Card :slot="1" :heading="'Intake Information'">
          <CardContent>
            <div class="intake-padding">
                <ul>
                    <li v-for="intake in questionsList">
                        <div>
                            <span v-html="intake.question" />
                            <span class="error-color">{{ intake.answer }}</span>
                        </div>
                    </li>
                </ul>
            </div>
          </CardContent>
        </Card>
      </Grid>

    </div>
</template>

<script>
import _ from 'lodash';
import { Card, CardContent, Grid, PageHeader } from 'layout';
export default {
    props: {
        patient: Object
    },
    components: {
        Card,
        CardContent,
        Grid,
        PageHeader
    },
    data() {
        return {
            currentPatient: this.$root.$data.global.patientLookUp[this.$props.patient.id]
        };
    },
    computed: {
        questionsList() {
            let quest = {};
            let props = this.$parent.propData;
            props.attributes.questions.forEach(e => {
                quest[e.id] = {};
                quest[e.id].question = e.question;
            });
            props.attributes.responses.forEach(e => {
                _.each(e.answers, (answer, id) => {
                    quest[id].answer = answer;
                });
            });
            return Object.values(quest);
        }
    }
};
</script>

<style lang="scss">
    .no-margin {
        margin: 0 !important;
    }
    .intake-padding {
        padding: 10px 20px;
    }
</style>
