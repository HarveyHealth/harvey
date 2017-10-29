<template>
  <CardContent withBorder>
    <Heading2 doesExpand class="avatar-heading">
      <SvgIcon :id="'small-person'" class="w2 h2" />
      <Space isRight :size="4" />
      <Space isRight :size="1" />
      {{ heading }}
    </Heading2>
  </CardContent>
</template>

<script>
import { CardContent, Space } from 'layout';
import { Heading2 } from 'typography';
import { SvgIcon } from 'icons';

export default {
  components: { CardContent, Heading2, Space, SvgIcon },
  props: {
    heading: String
  }
}
</script>

<style lang="scss" scoped>
  .avatar-heading {
    position: relative;

    svg {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
    }
  }
</style>
