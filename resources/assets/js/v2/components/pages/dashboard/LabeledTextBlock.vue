<template>
    <div>
        <Heading4>{{ label }}</Heading4>
        <Paragraph :weight="'thin'"><slot></slot></Paragraph>
    </div>
</template>

<script>
import { Heading4, Paragraph } from 'typography';

export default {
    components: { Heading4, Paragraph },
    props: {
        label: String
    }
};
</script>
