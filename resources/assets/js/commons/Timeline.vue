<template>
    <div>
        <div v-for="item in items" style="height: 80px;">
            <div @click="item.onClick" style="background-color: #d8d8d8; border-radius: 50%; width: 25px; height: 25px; float: left; margin: 5% 15px 5% 0;" />
            <div @click="item.onClick" style="width: 85%; border-radius: 8px; height: 70px; background-color: #fcfcfc; border: solid 1px #ebebeb; float: left; padding: 5px 10px;">
                <div style="width: 70%; float:left;">
                    <h7 style="width: 100%; float:left;">{{ item.type }}</h7>
                    <span style="width: 100%; float:left;">{{ item.date }}</span>
                    <span style="width: 100%; float:left;">{{ item.doctor }}</span>
                </div>
            </div>
        </div>
        <div :style="`height: 90%; width: 30%; border-left: solid 2px #d8d8d8;`" />
    </div>
</template>

<script>
export default {
  props: {
      items: {
          type: Array,
          required: true
      }
  }
}
</script>
