<template>
  <div>
    <h1 class="heading-1">Conditions</h1>
    <div v-for="condition in conditions">
      <img :src="condition.image_url" style="width:80px; height:80px" />
      <a class="heading-2" :href="'/conditions/' + condition.slug">{{ condition.name }}</a>
      <p>{{ condition.description }}</p>
      <div v-for="q in JSON.parse(condition.questions)">
        <h4 class="heading-4">{{ q.question }}</h4>
        <ul>
          <li v-for="a in q.answers">{{ a }}</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'conditions',
  computed: {
    conditions() {
      const _conditions = this.$root.$data.State.conditions;
      return _conditions.all = _conditions.selectedIndex !== null && _conditions.selectedIndex > -1
        ? _conditions.all.filter((c, i) => {
          return i === _conditions.selectedIndex;
        })
        : _conditions.all;
    }
  }
}
</script>
