<template>
  <div :class="classNames" v-show="isVisible">
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
    },
    isVisible() {
      return this.usertype === 'admin' || this.usertype === 'patient';
    }
  },
  methods: {
    selectDoctor(selection) {
      this.selected = this.doctorlist[selection];
      this.$eventHub.$emit('availabilityResponse', 'refresh');
      this.getAvailability(this.selected.id, (response) => {
        const availabilityStatus = response.meta.availability[0].length + response.meta.availability[1].length;
        this.$eventHub.$emit('updateDoctor', this.selected.id);
        this.$eventHub.$emit('returnAvailability', response);
        this.$eventHub.$emit('availabilityResponse', availabilityStatus);
      });
    },
    getAvailability(id, cb) {
      axios.get(`/api/v1/practitioners/${id}?include=availability`)
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
        this.getAvailability(id, (response) => {
          const availabilityStatus = response.meta.availability[0].length + response.meta.availability[1].length;
          this.dataCollected = true;
          this.availability = response;
          this.$eventHub.$emit('returnAvailability', response);
          this.$eventHub.$emit('availabilityResponse', availabilityStatus);
        });
      } else if (this.usertype === 'practitioner' && this.dataCollected) {
        const availabilityStatus = this.meta.availability[0].length + this.meta.availability[1].length;
        this.$eventHub.$emit('returnAvailability', this.availability);
        this.$eventHub.$emit('availabilityResponse', availabilityStatus);
      } else {
        this.getAvailability(id || this.doctorid, (response) => {
          const availabilityStatus = response.meta.availability[0].length + response.meta.availability[1].length;
          this.$eventHub.$emit('returnAvailability', response);
          this.$eventHub.$emit('availabilityResponse', availabilityStatus);
        });
      }
    });
  },
  destroyed() {
    this.$eventHub.$off('getDoctorAvailability');
  }
}
</script>
