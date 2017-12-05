<template>
    <div>
      <PageHeader class="mb3" :heading="'Treatment Plan'" />
      <Grid :flexAt="'l'" :columns="[{ s:'1of1' }]" :gutters="{ s:2, m:3 }">
        <Card :slot="1">
          <CardContent>
            <div
                disabled
                v-html="plan"
             />
           </CardContent>
         </Card>
      </Grid>
    </div>
</template>

<script>
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
    computed: {
        plan() {
            return this.$parent.propData.attributes.plan;
        }
    },
    watch: {
        plan(val) {
            if (!val) {
                return this.$parent.propData.attributes.plan;
            }
        }
    }
};
</script>
