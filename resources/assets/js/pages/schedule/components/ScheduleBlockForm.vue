<template>
    <form v-on:submit.prevent="submitScheduleForm" id="skuForm">

        <div class="input__container">
            <label class="input__label" for="day_of_week">Day Of Week</label>
            <span class="custom-select">
                <select name="day_of_week" id="day_of_week" v-model="scheduleBlock.attributes.day_of_week" @change="$emit('inputChanged')">
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </span>
        </div>

        <div class="input__container">
            <label class="input__label" for="start_time">First Block</label>
            <span class="custom-select">
                <select name="start_time" id="start_time" v-model="scheduleBlock.attributes.start_time" @change="$emit('inputChanged')">
                    <option v-for="timeBlock in timeBlocks" :value="timeBlock.start_value">
                        {{timeBlock.title}} ({{timeBlock.subtitle}})
                    </option>
                </select>
            </span>
        </div>

        <div class="input__container">
            <label class="input__label" for="stop_time">Last Block</label>
            <span class="custom-select">
                <select name="stop_time" id="stop_time" v-model="scheduleBlock.attributes.stop_time" @change="$emit('inputChanged')">
                    <option v-for="timeBlock in timeBlocks" :value="timeBlock.stop_value">
                        {{timeBlock.title}} ({{timeBlock.subtitle}})
                    </option>
                </select>
            </span>
        </div>

        <div class="input__container input-wrap">
            <label class="input__label" for="notes">Notes</label>
            <input class="form-input form-input_text input-styles" type="text" name="notes" maxlength="191" v-model="scheduleBlock.attributes.notes"  @change="$emit('inputChanged')"/>
        </div>

        <div class="error-text">
            <p v-for="error in errorMessages">{{ error.detail }} </p>
        </div>

        <div class="submit inline-centered">
            <button class="button" :disabled="submitting" style="width: 160px">
                <div v-if="submitting" style="width: 12px; margin: 0 auto;">
                    <ClipLoader :size="'12px'" :color="'#ffffff'" ></ClipLoader>
                </div>
                <span v-else>Save Changes</span>
            </button><br/>
        </div>

    </form>
</template>

<script>
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

    export default {
        data() {
            return {

            };
        },
        components: {ClipLoader},
        methods: {
            submitScheduleForm() {
                this.scheduleBlock.id ? this.$emit('patchSchedule') : this.$emit('postSchedule');
            }
        },
        props: {
            scheduleBlock: {
                type: Object
            },
            submitting: {
                type: Boolean
            },
            timeBlocks: {
                type: Array
            },
            errorMessages: {
                type: Array
            }
        }
    };
</script>
