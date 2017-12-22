<template>
    <div id="intake">
      <PageHeader class="mb3" :heading="'Intake Information'" />
      <Grid :flexAt="'l'" :columns="[{ s:'1of1' }]" :gutters="{ s:2, m:3 }">
        <Card :slot="1">
          <CardContent>
            <div>
                <ul>
                    <li v-for="intake in questionsList">
                        <div>
                            <Paragraph :size="'large'" :weight="'bold'" v-html="intake.question"></Paragraph>
                            <Paragraph class="error-color">{{ intake.answer }}</Paragraph>
                            <Spacer isBottom :size="4" />
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
import { Paragraph } from 'typography';
import { Card, CardContent, Grid, PageHeader, Spacer } from 'layout';
export default {
    props: {
        patient: Object
    },
    components: {
        Card,
        Paragraph,
        CardContent,
        Grid,
        PageHeader,
        Spacer
    },
    data() {
        return {
            currentPatient: this.$root.$data.global.patientLookUp[this.$props.patient.id]
        };
    },
    computed: {
        questionsList() {
            let quest = {};
            // Have to go up two parents because of PageContainer
            let props = this.$parent.$parent.propData;
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

</style>
