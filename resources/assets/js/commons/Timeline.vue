<template>
    <div>
        <div v-for="(item, key) in items" style="height: 80px;">
            <div @click="item.onClick" :class="key === index ? 'on' : ''" style="background-color: #d8d8d8; border-radius: 50%; width: 25px; height: 25px; float: left; margin: 5% 15px 5% 0; cursor: pointer;" />
            <div @click="item.onClick" style="width: 85%; border-radius: 8px; height: 70px; background-color: #fcfcfc; border: solid 1px #ebebeb; float: left; padding: 5px 10px; cursor: pointer;">
                <div style="width: 70%; float:left;">
                    <h7 style="width: 100%; float:left;">{{ item.type }}</h7>
                    <span style="width: 100%; float:left;">{{ item.date }}</span>
                    <span style="width: 100%; float:left;">{{ item.doctor }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  props: {
      items: {
          type: Array,
          required: true
      },
      index: {
          type: Number
      }
  }
}
</script>

<style lang="scss">
    .on {
        background-color: #a1bbd0 !important;
    }
</style>