<template>
  <div :class="classNames">
    <label v-if="showLabel" class="input__label" for="doctor-name">
      <div>doctor</div>
    </label>
    <span v-if="isEditable" class="custom-select">
      <select @change="selectDoctor($event.target.selectedIndex)">
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
      availability: {},
      dataCollected: false,
      classNames: { 'input__container': true },
      selected: {},
      showLabel: this.usertype !== 'practitioner' || (this.usertype === 'practitioner' && !this.isEditable)
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
    selectDoctor(selection) {
      this.selected = this.doctorlist[selection];
      this.getAvailability(this.selected.id, (response) => {
        this.$eventHub.$emit('returnAvailability', response);
      });
    },
    getAvailability(id, cb) {
      axios.get(`api/v1/practitioners/${id}?include=availability`)
        .then(response => cb(response.data))
    }
  },
  created() {
    return this.classes
      ? this.classes.forEach(cls => this.classNames[cls] = true)
      : this.classes;
  },
  mounted() {
    // Not the most elegant thing but this logic exists to limit api calls
    // when the user is a practitioner (since they don't need the option to
    // to select different doctors when updating or creating appointments)
    this.$eventHub.$on('getDoctorAvailability',(id) => {
      if (this.usertype === 'practitioner' && !this.dataCollected) {
        this.getAvailability(this.doctorid, (response) => {
          this.dataCollected = true;
          this.availability = response;
          this.$eventHub.$emit('returnAvailability', response);
        });
      } else if (this.usertype === 'practitioner' && this.dataCollected) {
        this.$eventHub.$emit('returnAvailability', this.availability);
      } else {
        this.getAvailability(id || this.doctorid, (response) => {
          this.$eventHub.$emit('returnAvailability', response);
        });
      }
    });
  }
}
</script>
