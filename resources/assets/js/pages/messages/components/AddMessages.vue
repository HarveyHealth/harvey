<template>
    <div>
        <aside :class="{ flyout: true, isactive: true }">
            <button class="button--close flyout-close" @click="close()">
                <svg><use xlink:href="#close" /></svg>
            </button>
            <h2 class="title">Create Messages</h2>
            <div class="input__container">
                <label class="input__label" for="patient_name">doctor</label>
                <span class="custom-select">
                    <select @change="updateDoctor($event)" name="doctor_name">
                        <option  v-for="doctor in doctorList" :data-id="doctor.id">{{ doctor.name }}</option>
                    </select>
                </span>
            </div>
            <div class="input__container">
                <label class="input__label" for="patient_name">subject</label>
                <input  class="input--text" type="text">
            </div>
            <div class="input__container">
                <label class="input__label" for="patient_name">message</label>
                <textarea class="input--textarea"></textarea>
            </div>
            <div>
                <div class="inline-centered">
                    <button class="button">Create Message</button>
                </div>
            </div>
        </aside>
    </div>
</template>

<script>
    export default {
        props: [],
        name: 'Preview',
        components: {

        },
        data() {
            return {
                close: this.$parent.close,
                selected: ''
            }
        },
        methods: {
            handleOverlayClick(e) {
                if (/.*appointment-modal.*/.test(e.target.className)) {
                    this.isActive = false;
                }
            },
            handleCancelClick() {
                this.isActive = false;
            },
            handleAffirmClick() {
                this.isActive = false;
                this.$eventHub.$emit(this.affirmEvent);
            },
            updateDoctor(e) {
                this.selected = e.target.children[e.target.selectedIndex].dataset.id;
            }
        },
        computed: {
            doctorList() {
                return [''].concat(this.$root.$data.global.practitioners);
            }
        }
    }
</script>

<style>

</style>