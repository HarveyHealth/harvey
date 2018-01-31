<template>
    <div class="main-container profile-page">
        <div class="main-content">
            <NotificationPopup
                v-if="notificationError !== undefined && notificationActive !== undefined && notificationDirection !== undefined && notificationSymbol !== undefined && notificationMessage !== undefined"
                :as-error="notificationError"
                :active="notificationActive"
                :comes-from="notificationDirection"
                :symbol="notificationSymbol"
                :text="notificationMessage"
            />
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="heading-1">
                        <span class="text">Profile</span>
                    </h1>
                </div>
            </div>
            <div class="card card-info">
                <div class="card-heading-container">
                    <h2 class="heading-2">
                        Contact Info
                        <span v-if="user_id && !loading">for {{ user.attributes.first_name }} {{ user.attributes.last_name }} (#{{ user_id}})</span>
                        <span v-if="!user_id && !loading">(#{{ thisUserId }})</span>
                    </h2>
                </div>
                <div class="card-content-container topPadding">
                    <div class="card-content-wrap">
                        <!-- Using v-if here because we don't want the rest to register until user data is up -->
                        <ClipLoader :color="'#82BEF2'" :loading="loading" v-if="loading"></ClipLoader>
                        <form action="#" method="POST" class="form" id="user_form" v-else>
                            <div class="formgroups flex-respond-row">
                                <div class="formgroup">
                                    <div class="input__container input-wrap">
                                        <label class="input__label" for="first_name">First Name</label>
                                        <input class="form-input form-input_text input-styles" v-model="user.attributes.first_name" type="text" name="first_name"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="last_name">Last Name</label>
                                        <input class="form-input form-input_text input-styles" v-model="user.attributes.last_name" type="text" name="last_name"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="email">Email</label>
                                        <input class="form-input form-input_text input-styles" v-validate="'required|email'" v-model="user.attributes.email" type="text" name="email"/>
                                        <span v-show="errors.has('email')" class="error-text">{{ errors.first('email') }}</span>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="phone">Phone Number</label>
                                        <input class="form-input form-input_text input-styles" v-model="user.attributes.phone" type="number" name="phone"/>
                                        <a href="#" class="phone-link" @click.prevent="handleTextSend(true)" v-if="phoneNotVerified">Verify phone number</a>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="phone">Date of Birth</label>
                                        <input class="form-input form-input_text input-styles" v-model="user.included.attributes.birthdate.date" type="text" name="birthdate"/>
                                    </div>
                                    <div class="input__container">
                                        <label  class="input__label" for="gender">Gender</label>
                                        <div class="gender-options">
                                            <div class="gender-options__option">
                                                <input type="radio" name="gender" id="male" v-model="user.attributes.gender" v-bind:value="'male'">
                                                <label for="male">Male</label>
                                            </div>
                                            <div class="gender-options__option">
                                                <input type="radio" name="gender" id="female" v-model="user.attributes.gender" v-bind:value="'female'">
                                                <label for="female">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="formgroup">
                                    <div class="profile-img-container">
                                        <ImageUpload
                                                class="profile-img-container__button"
                                                v-on:uploading="uploadingProfileImage"
                                                v-on:uploaded="uploadedProfileImage"
                                                v-on:uploadError="uploadError"
                                                label="Picture"
                                                :route="`api/v1/users/${user.id}/image`"
                                                type="profile">
                                        </ImageUpload>
                                        <div v-show="!loadingProfileImage" class="profile-img-container__img">
                                            <img alt="" :src="user.attributes.image_url" />
                                        </div>
                                        <ClipLoader class="profile-img-container__img" :color="'#82BEF2'" :loading="loadingProfileImage"></ClipLoader>
                                    </div>
                                    <p class="copy-muted-2 font-italic font-sm font-thin" style="margin:-26px 0 22px;">Image must be square and max 300px.</p>
                                    <div class="input__container">
                                        <label class="input__label" for="address_1">Mailing Address</label>
                                        <input class="form-input form-input_text input-styles" v-model="user.attributes.address_1" type="text" name="address_1"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="address_2">Apt/Unit #</label>
                                        <input class="form-input form-input_text input-styles" v-model="user.attributes.address_2" type="text" name="address_2"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="city">City</label>
                                        <input class="form-input form-input_text input-styles" v-model="user.attributes.city" type="text" name="city"/>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="state">State</label>
                                        <span class="custom-select">
                                            <select name="state" v-model="user.attributes.state">
                                                <option v-for="(state, abbreviation) in states" v-bind:value="abbreviation">{{ state }}</option>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="input__container">
                                        <label class="input__label" for="zip">Zip Code</label>
                                        <input class="form-input form-input_text input-styles" v-model="user.attributes.zip" type="text" name="zip"/>
                                    </div>
                                </div>
                            </div>
                            <div class="error-text">
                                <p v-for="error in errorMessages">{{ error.detail }} </p>
                            </div>
                            <div class="submit inline-centered">
                                <button class="button" v-on:click.prevent="submit" :disabled="submitting" style="width: 160px">
                                  <div v-if="submitting" style="width: 12px; margin: 0 auto;">
                                    <ClipLoader :size="'12px'" :color="'#ffffff'" />
                                  </div>
                                  <span v-else>Save Changes</span>
                                </button><br/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <PractitionerProfile
                v-if="canEditPractitioners"
                :flashSuccess="callSuccessNotification"
                :practitionerIdEditing="practitioner"
            />
        </div>
        {{ _user }}
        <Modal :active="phoneModal" :on-close="() => phoneModal = false">
          <h2 class="text-centered">Enter Phone Verification Code</h2>
          <div style="text-align: center;">
            <!-- confirmation inputs -->
            <ConfirmInput ref="confirmInputs" :get-value="(val) => phoneConfirmation = val" />

            <!-- send text again button -->
            <button class="phone-process-button text-again" @click="handleTextResend">
              <i class="fa fa-repeat" aria-hidden="true"></i>
              Text Me Again
            </button>

            <!-- error messages -->
            <p class="error-text" v-show="isInvalidCode">Invalid code entered.</p>

            <!-- confirm code button -->
            <button class="button button--blue phone-confirm-button" style="width: 170px; margin-top: 22px"
                    :disabled="isPhoneConfirming" @click="handleCodeConfirmation">
              <span v-if="!isPhoneConfirming">Confirm Code</span>
              <div v-else style="width: 12px; margin: 0 auto;">
                <ClipLoader :size="'12px'" :color="'#ffffff'" />
              </div>
            </button>

          </div>
        </Modal>
    </div>
</template>

<script>
    import diff from 'object-diff';
    import _ from 'lodash';
    import timezones from '../../../../../public/timezones.json';
    import states from '../../../../../public/states.json';
    import NotificationPopup from '../../commons/NotificationPopup.vue';
    import ImageUpload from '../../commons/ImageUpload.vue';
    import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';
    import PractitionerProfile from './components/PractitionerProfile.vue';
    import Modal from '../../commons/Modal.vue';
    import ConfirmInput from '../../commons/ConfirmInput.vue';
    import moment from 'moment';

    export default {
        name: 'profile',
        components: {
          NotificationPopup,
          ImageUpload,
          ClipLoader,
          PractitionerProfile,
          Modal,
          ConfirmInput
        },
        data() {
            return {
                moment: moment,
                loadingProfileImage: false, // loading of the image on image upload
                previousProfileImage: '',
                user: {
                    attributes: {
                        first_name: '',
                        last_name: '',
                        email: '',
                        gender: '',
                        phone: '',
                        address_1: '',
                        address_2: '',
                        city: '',
                        state: '',
                        zip: '',
                    },
                    included: {
                        attributes: {
                            birthdate: {
                                date: '',
                            },
                        },
                    },
                },
                thisUserId: Laravel.user.id,
                practitioner: `${Laravel.user.practitionerId}` || null,
                user_data: null,
                user_id: this.$route.params.id,
                timezones: timezones,
                states: states,
                errorSymbol: '!',
                errorMessage: 'Error retrieving data',
                successSymbol: '&#10003;',
                successMessage: 'Changes Saved',
                notificationError: false,
                notificationSymbol: '&#10003;',
                notificationMessage: 'Changes Saved',
                notificationActive: false,
                notificationDirection: 'top-right',
                errorMessages: null,
                submitting: false,
                phoneModal: false,
                phoneConfirmation: '',
                phoneVerified: Laravel.user.phone_verified_at,
                currentUserId: Laravel.user.id,
                isInvalidCode: false,
                isPhoneConfirming: false
            };
        },
        methods: {
            flashNotification() {
                this.notificationActive = true;
                setTimeout(() => this.notificationActive = false, 3000);
            },
            resetErrorMessages() {
                this.errorMessages = null;
            },
            callErrorNotification(msg) {
                this.notificationError = true;
                this.notificationSymbol = this.errorSymbol;
                this.notificationMessage = msg || this.errorMessage;
                this.flashNotification();
            },
            callSuccessNotification() {
              this.notificationError = false;
              this.notificationSymbol = this.successSymbol;
              this.notificationMessage = this.successMessage;
              this.flashNotification();
            },
            handleVerifyClick() {
              this.phoneModal = true;
              this.handleTextSend(true);
            },
            handleTextSend(force) {
              // If admin is editing someone else's phone number, do not call confirmation modal
              if (this.$route.params.id) return;

              const currentPhone = Laravel.user.phone;
              const updatedPhone = this.updates.phone;
              const shouldPatch = updatedPhone && (updatedPhone !== currentPhone);

              // If force is true, send text verification no matter what
              // If it is not true, check to see if verification should be sent
              if (!force && !updatedPhone) {
                return;
              } else if (!force && updatedPhone) {
                this.phoneModal = true;
                this.isInvalidCode = false;
                return;
              }

              this.phoneModal = true;
              this.isInvalidCode = false;

              // If phone was changed, patch the user's account to trigger text send
              if (shouldPatch) {
                axios.patch(`${this.$root.$data.apiUrl}/users/${this.user_id || this.user.id}`, { phone: updatedPhone })
                  .then(() => {
                    // Update the Laravel object in case the user wants to update phone before refreshing
                    Laravel.user.phone = updatedPhone;
                  })
                  .catch(error => {
                    if (error.response) {
                      console.log(error.response);
                      this.callErrorNotification('Could not update user information');
                    }
                  });
              // If phone is the same, post to send verification text again
              } else if (!this.phoneVerified) {
                axios.post(`${this.$root.$data.apiUrl}/users/${this.user_id || this.user.id}/phone/sendverificationcode`)
                  .catch(error => {
                    if (error.response) {
                      console.log(error.response);
                      this.callErrorNotification('Error sending verification text message');
                    }
                  });
              }
            },
            handleTextResend() {
              this.isInvalidCode = false;
              this.resetConfirmInputs();
              this.handleTextSend(true);
            },
            handleCodeConfirmation() {
              // Simple validation to check if valid inputs
              const isCodeValid = (/\d{5}/).test(this.phoneConfirmation);
              if (!isCodeValid) {
                this.isInvalidCode = true;
                return;
              }

              this.isPhoneConfirming = true;

              axios.get(`${this.$root.$data.apiUrl}/users/${this.user_id || this.user.id}/phone/verify?code=${this.phoneConfirmation}`)
                .then(response => {
                  this.isPhoneConfirming = false;
                  // a successful return object is sent even if verification was unsuccessful
                  if (response.data.verified) {
                    this.phoneModal = false;
                    this.phoneVerified = true;
                    this.callSuccessNotification();
                  } else {
                    this.isInvalidCode = true;
                    this.resetConfirmInputs();
                  }
                })
                .catch(error => {
                  if (error.response) {
                    console.log(error.response);
                    this.phoneModal = false;
                    this.callErrorNotification('Verification could not be sent');
                  }
                });
            },
            resetConfirmInputs() {
              this.phoneConfirmation = '';
              Object.keys(this.$refs.confirmInputs.$refs).forEach(i => {
                this.$refs.confirmInputs.$refs[i].value = '';
              });
              this.$refs.confirmInputs.$refs[0].focus();
            },
            submit() {
                if(_.isEmpty(this.updates))
                    return;

                this.resetErrorMessages();

                this.submitting = true;

                axios.patch(`${this.$root.$data.apiUrl}/users/${this.user_id || this.user.id}`, this.updates)
                    .then(response => {
                      this.callSuccessNotification();
                      this.handleTextSend();
                      if (this.updates.address_1 || this.updates.address_2 || this.updates.city || this.updates.state || this.updates.zip) {
                          this.$root.getLabData();
                      }
                      if (this.canEditUsers) {
                          this.user_data = response.data.data;
                      } else {
                          this.$root.$data.global.user = response.data.data;
                      }
                      this.submitting = false;
                    })
                    .catch(err => {
                        this.errorMessages = err.response.data.errors;
                        this.submitting = false;
                    });
            },
            uploadingProfileImage() {
                this.previousProfileImage = this.user.attributes.image_url;
                this.loadingProfileImage = true;
            },
            uploadedProfileImage(response) {
                this.user.attributes.image_url = response.data.attributes.image_url;
                this.loadingProfileImage = false;
                this.callSuccessNotification();
            },
            uploadError(err) {
                this.user.attributes.image_url = this.previousProfileImage;
                this.loadingProfileImage = false;
                this.errorMessages = err.errors;
            },
            getData(userId) {
                this.$root.$data.global.loadingUser = true;
                axios.get(`${this.$root.$data.apiUrl}/users/${userId}?include=patient,practitioner`)
                    .then(response => {
                        // this.user_data persists the data retrieved from the server
                        // so we can diff against it on PATCH
                        this.$root.$data.global.loadingUser = false;
                        this.user_data = response.data.data;
                        this.user = _.cloneDeep(response.data.data);
                        this.practitioner = response.data.data.relationships.practitioner.data.id;
                        this.user.included.attributes.birthdate.date = moment(response.data.included.attributes.birthdate.date).format('MM/DD/YYYY');
                    })
                    .catch(error => {
                        if (error.response) {
                          if (error.response.status === 404) {
                            this.errorMessage = 'Not a valid user id';
                          }
                          this.$router.push('/profile');
                          this.user_id = null;
                          this.$root.$data.global.loadingUser = false;
                          this.callErrorNotification();
                        }
                    });
            },
            setUserId(id) {
                this.user_id = id;
            },
            setUser(data) {
                this.user = data;
            }
        },
        mounted() {
            // We need to bar non admins from hitting profile/:id
            if (this.canEditUsers) {
                this.user_id = this.$route.params.id;
            } else if (this._user_id) {
                this.$router.push('/profile');
            }
            this.$root.$data.global.currentPage = 'profile';
        },
        // If the user id parameter changes in the URL we want to trigger getData to populate fields
        // with new user data corresponding to the id
        watch: {
            _user_id(id) {
                if (id && this.canEditUsers) {
                    this.setUserId(id);
                    this.getData(this.user_id);
                } else {
                    this.$router.push('/profile');
                }
            }
        },
        computed: {
            canEditUsers() {
                return this._user_id && Laravel.user.user_type === 'admin';
            },
            canEditPractitioners() {
                return Laravel.user.practitionerId || (this.canEditUsers && 'practitioner' == this.user.attributes.user_type);
            },
            // loading is connected to global state since that's where the main user api call is made
            loading() {
                return this.$root.$data.global.loadingUser;
            },
            phoneNotVerified() {
              return !this.$route.params.id && !this.phoneVerified;
            },
            updates() {
                // We want to diff against the correct user attributes
                const oldUserAttributes = this.canEditUsers ? this.user_data.attributes : this.$root.$data.global.user.attributes;
                return _.omit(diff(oldUserAttributes, this.user.attributes), 'created_at', 'email_verified_at', 'phone_verified_at', 'doctor_name', 'image_url');
            },
            // This computed property is used solely to populate this.user once the api call
            // from app.js is finished running. Sort of like a watch for parent components.
            _user() {
                if (this.canEditUsers) {
                    this.getData(this.user_id);
                } else if (!this.$root.$data.global.loadingUser) {
                    let user = _.cloneDeep(this.$root.$data.global.user);
                    this.setUser(user);
                }
                return '';
            },
            // We set the user_id as a computed property so we can set a watch on it for when the url changes
            _user_id() {
                return this.$route.params.id;
            }
        }
    };
</script>

<style lang="scss">

    .card-info {
        width: 100%;
        max-width: 870px;
    }

    .profile-page .input__container {
        width: 80%;
    }

    .formgroups {
        display: flex;
    }

    .formgroup {
        flex: 1;
        padding: 0 20px;
    }

    .submit {
        padding-top: 20px;
        display: flex;
        justify-content: center;
    }

    .topPadding {
        padding: 20px;
    }

    .gender-options {
        display: flex;
        width: 100%;

        input {
            margin: 0 10px 10px 0;
            max-width: 18px;
            float: left;
        }

        label {
            max-width: 80px;
            float: left;
        }

        &__option {
            width: 38%;
        }
    }

    .profile-img-container {
        display: flex;
        height: 100px;
        justify-content: space-between;
        padding-bottom: 20px;

        &__wrapper {
            width: 100%;
        }

        &__button {
            flex: 1;
        }

        &__img {
            flex: 1;
            img {
                width: 80px;
                border-radius: 50%;
                margin-top: -7px;
            }
        }
        .button {
            padding: 10px 17px;
            font-size: 13px;
            background: #DDD;
            color: #444;
            border: 1px solid #CCC;
            margin-top: 5px;
            width: 130px;
        }
    }

    .warning {
        font-size: 12px;
        margin: -20px 0 20px;
        color: #DDD;
        font-style: italic;
    }

    .loading {
        margin-left: 20px;
        color: #AAA;
    }

    .error-text {
        width: 100%;
        text-align: center;
        .flyout & {
            text-align: left;
        }
    }
</style>
