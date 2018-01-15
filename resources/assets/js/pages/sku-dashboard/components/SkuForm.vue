<template>
    <div>
        <NotificationPopup
                :as-error="notificationError"
                :active="notificationActive"
                :comes-from="notificationDirection"
                :symbol="notificationSymbol"
                :text="notificationMessage"
        />
        <form v-on:submit.prevent="submitSkuForm" id="skuForm">

            <div class="input__container input-wrap">
                <label class="input__label" for="name">Partner</label>
                <input class="form-input form-input_text input-styles" type="text" name="lab_name" v-model="formSku.attributes.lab_test_information.lab_name"/>
            </div>

            <div class="input__container input-wrap">
                <label class="input__label" for="name">Test Name</label>
                <input class="form-input form-input_text input-styles" type="text" name="name" v-model="formSku.attributes.name"/>
            </div>

            <div class="input__container">
                <label class="input__label" for="sample">Sample</label>
                <span class="custom-select">
                    <select name="sample" id="sample" v-model="formSku.attributes.lab_test_information.sample">
                        <option value="Blood draw">Blood Draw</option>
                        <option value="Saliva">Saliva</option>
                        <option value="Stool">Stool</option>
                        <option value="Urine">Urine</option>
                    </select>
                </span>
            </div>

            <div class="input__container input-wrap">
                <label class="input__label" for="description">Description</label>
                <span class="charcount" style="top: 0px">{{ countDescription }} / {{ descriptionMaxLength }}</span>
                <textarea class="form-input form-input_textarea input-styles" rows="10" cols="40" :maxlength="descriptionMaxLength" name="description" v-model="formSku.attributes.lab_test_information.description"></textarea>
            </div>

            <div class="input__container input-wrap">
                <label class="input__label" for="quote">Quote</label>
                <span class="charcount" style="top: 0px">{{ countQuote }} / {{ quoteMaxLength }}</span>
                <textarea class="form-input form-input_textarea input-styles" rows="3" cols="40" :maxlength="quoteMaxLength" name="quote" v-model="formSku.attributes.lab_test_information.quote"></textarea>
            </div>

            <div class="input__container input-wrap">
                <label class="input__label" for="image">Image (PNG)</label>
                <input class="form-input form-input_text input-styles" type="text" name="image" v-model="formSku.attributes.lab_test_information.image"/>
            </div>

            <div class="input__container input-wrap">
                <label class="input__label" for="image">Sample (PDF)</label>
                <input class="form-input form-input_text input-styles" type="text" name="image" v-model="formSku.attributes.lab_test_information.example"/>
            </div>

            <div class="input__container input-wrap">
                <label class="input__label" for="price">Price</label>
                <input class="form-input form-input_text input-styles" type="number" step="0.01" name="price" v-model="formSku.attributes.price"/>
            </div>

            <div class="input__container input-wrap">
                <label class="input__label" for="cost">Cost</label>
                <input class="form-input form-input_text input-styles" type="number" step="0.01" name="cost" v-model="formSku.attributes.cost"/>
            </div>

            <div class="input__container">
                <label class="input__label" for="visibility">Visible to</label>
                <span class="custom-select">
                    <select name="visibility" id="visibility" v-model="formSku.attributes.lab_test_information.visibility">
                        <option value="public">All</option>
                        <option value="patients">Patient, Practitioners and Admins</option>
                        <option value="practitioners">Practitioners and Admins</option>
                        <option value="admins">Only Admins</option>
                    </select>
                </span>
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
    </div>
</template>

<script>
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
    import NotificationPopup from '../../../commons/NotificationPopup.vue';

    export default {
    data() {
        return {
            blankSku: {
                attributes: {
                    name: null,
                    price: 0,
                    cost: 0,
                    lab_test_information: {
                        lab_name: null,
                        sample: null,
                        description: null,
                        quote: null,
                        image: null,
                        visibility: 'patients'
                    }
                }
            },
            submitting: false,
            notificationSymbol: '&#10003;',
            notificationActive: false,
            notificationDirection: 'top-right',
            notificationError: false,
            notificationMessage: '',
            descriptionMaxLength: 2048,
            quoteMaxLength: 1024
        };
    },
    components: {
        ClipLoader,
        NotificationPopup
    },
    methods: {
        putSkuForm() {
            const sku = this.sku;
            axios.put(`${this.$root.$data.apiUrl}/skus/${sku.id}`, {
                name: sku.attributes.name,
                price: sku.attributes.price,
                cost: sku.attributes.cost,
                description: sku.attributes.lab_test_information.description,
                image: sku.attributes.lab_test_information.image,
                example: sku.attributes.lab_test_information.example,
                sample: sku.attributes.lab_test_information.sample,
                quote: sku.attributes.lab_test_information.quote,
                lab_name: sku.attributes.lab_test_information.lab_name,
                visibility: sku.attributes.lab_test_information.visibility
            })
            .then(() => {
                this.submitting = false;
                this.notificationActive = true;
                this.notificationMessage = "Lab Test Updated";
                setTimeout(() => this.notificationActive = false, 3000);
                this.$emit('saved');
            })
            .catch(e => {
                console.log(e);
                this.submitting = false;
            });
        },
        postSkuForm() {
            const sku = this.blankSku;
            axios.post(`${this.$root.$data.apiUrl}/skus`, {
                name: sku.attributes.name,
                price: sku.attributes.price,
                cost: sku.attributes.cost,
                description: sku.attributes.lab_test_information.description,
                image: sku.attributes.lab_test_information.image,
                example: sku.attributes.lab_test_information.example,
                sample: sku.attributes.lab_test_information.sample,
                quote: sku.attributes.lab_test_information.quote,
                lab_name: sku.attributes.lab_test_information.lab_name,
                visibility: sku.attributes.lab_test_information.visibility
            })
                .then(response => {
                    this.submitting = false;
                    this.notificationActive = true;
                    this.$emit('append', response.data.data);
                    this.notificationMessage = "Lab Test Created";
                    setTimeout(() => this.notificationActive = false, 3000);
                    this.resetSkuForm();
                    this.$emit('saved');
                })
                .catch(e => console.log(e));
        },
        submitSkuForm() {
            this.submitting = true;
            this.sku ? this.putSkuForm() : this.postSkuForm();
        },
        resetSkuForm() {
            this.blankSku = {
                attributes: {
                    name: null,
                    price: 0,
                    cost: 0,
                    lab_test_information: {
                        lab_name: null,
                        sample: null,
                        description: null,
                        quote: null,
                        image: null,
                        visibility: 'patients'
                    }
                }
            };
        }
    },
    computed: {
        formSku() {
            return this.sku || this.blankSku;
        },
        countQuote() {
          let model = this.sku ? this.sku : this.blankSku;
          let field = model.attributes.lab_test_information.quote;
          return this.quoteMaxLength - (field ? field.length : 0);
        },
        countDescription() {
          let model = this.sku ? this.sku : this.blankSku;
          let field = model.attributes.lab_test_information.description;
          return this.descriptionMaxLength - (field ? field.length : 0);
        }
    },
    props: {
        sku: {
            type: Object
        },
        appendSkuList: {
            type: Function
        }
    }
};
</script>

<style lang="scss"></style>
