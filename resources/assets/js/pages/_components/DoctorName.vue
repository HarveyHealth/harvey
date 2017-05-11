<template>
  <div :class="classNames">
    <label class="input__label" for="doctor-name">
      <div>doctor</div>
    </label>
    <span v-if="isEditable" class="custom-select">
      <select @change="selectDoctor($event)">
        <option
          v-for="doc in doctorlist"
          :selected="doc.name === doctorname" >{{ doc.name }}</option>
      </select>
    </span>
    <span v-else class="input__item">{{ chosenDoctor.name }}</span>
    <slot></slot>
  </div>
</template>

<script>
// TO-DO: doctorlist needs to come from the user?type=practitioner call
export default {
  props: ['classes', 'doctorid', 'doctorlist', 'doctorname', 'type', 'usertype'],
  data() {
    return {
      classNames: { 'input__container': true },
      selected: {}
    }
  },
  computed: {
    chosenDoctor() {
      return { name: this.doctorname, id: this.doctorid };
    },
    isEditable() {
      return ( this.usertype === 'admin' || this.usertype === 'patient' ) && this.type === 'new';
    }
  },
  methods: {
    selectDoctor(e) {
      this.selected = this.doctorlist[e.target.selectedIndex];
      axios.get(`api/v1/practitioners/${this.selected.id}?include=availability`).then(response => {
        console.log(response.data);
      })
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
}
</script>
