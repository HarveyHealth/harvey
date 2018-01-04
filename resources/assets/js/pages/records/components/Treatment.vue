<template>
    <div>
        <Card>
            <CardContent>
                <Heading2>Treatment Plan</Heading2>
            </CardContent>
        </Card>
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
import { Heading2 } from 'typography';
export default {
    props: {
        patient: Object
    },
    components: {
      Card,
      CardContent,
      Grid,
      PageHeader,
      Heading2
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
