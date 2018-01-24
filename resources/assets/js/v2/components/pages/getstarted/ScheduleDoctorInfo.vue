<template>
    <SlideIn :to="'left'" class="tc">
        <img alt=""
            class="circle"
            style="height: 120px; width: 120px;"
            :src="Logic.practitioners.determineImage(doctorInfo.picture_url, 'user')"
        />
        <Spacer isBottom :size="3" />
        <Heading2>Dr. {{ doctorInfo.name }}, ND</Heading2>
        <Spacer isBottom :size="3" />
        <Paragraph v-if="doctorInfo.licenses.length">
            Licensed: {{ doctorInfo.licenses.map(l => l.state).join(', ') }}
        </Paragraph>
    </SlideIn>
</template>

<script>
import { Heading2, Paragraph } from 'typography';
import { SlideIn, Spacer } from 'layout';

export default {
    props: {
        doctorInfo: Object
    },
    components: {
        Heading2,
        Paragraph,
        SlideIn,
        Spacer
    }
};
</script>
