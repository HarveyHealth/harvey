<template>
    <PageContainer>
        <PageHeader :heading="Store.isAdmin ? 'Admin Dashboard' : 'Dashboard'" />
        <div class="mw8 pa2 pa3-m">
            <NotificationPopup
                :active='isNotificationActive'
                comes-from='top-right'
                symbol='&#10003;'
                :text='notificationText'
            />

            <LoadingSpinner v-if="!isDoneLoading" class="mt3" />

            <SlideIn v-else>
                <SlideIn :to="'right'" v-if="shouldShowIntakeAlert">
                    <Card isAlert class="alert mb2 mb3-m">
                        <CardContent class="pt4">
                            <Grid :columns="[{m:8}, {m:4}]" :gutters="{s:3}">
                                <Paragraph :slot="1" class="tc tl-m white">Please note: you must finish your patient intake form before your first appointment</Paragraph>
                                <div :slot="2" class="tc tr-m">
                                    <InputButton :href="'/intake'" :mode="'inverse'" :text="'Intake Form'" />
                                </div>
                            </Grid>
                        </CardContent>
                    </Card>
                </SlideIn>

                <Grid :columns="topRowColumnConfig" :gutters="{ s:2, m:3 }">
                    <Card :slot="1" :heading="'Appointments'">
                        <div v-if="Store.upcomingAppointments.length">
                            <AppointmentCardInfo
                                v-for="(appt, i) in Store.upcomingAppointments"
                                :appointment="appt"
                                :hasBorder="i < Store.upcomingAppointments.length - 1"
                                :key="appt.id"
                            />
                        </div>
                        <CardContent v-else>
                            <Paragraph :weight="'thin'">No upcoming appointments scheduled</Paragraph>
                        </CardContent>
                    </Card>
                    <Card :slot="2" :heading="'Practitioner'" v-if="shouldShowDoctorInfo">
                        <AvatarCardHeading :heading="userDoctor.attributes.name" />
                        <CardContent>
                            <Paragraph :weight="'thin'">{{ userDoctor.attributes.description }}</Paragraph>
                        </CardContent>
                    </Card>
                </Grid>

                <Grid :columns="[{ l:6 }, { l:6 }]" :gutters="{ s:2, m:3 }">
                    <Card :slot="1" :heading="'Contact Info'">
                        <AvatarCardHeading :heading="Util.getFullName(Store.user)" />
                        <CardContent>
                            <LabeledTextBlock :label="'Email'">
                                <a :href="'tel:'+Store.user.email">{{ Store.user.email }}</a>
                            </LabeledTextBlock>
                            <Spacer isBottom :size="3" />
                            <LabeledTextBlock :label="'Phone'">
                                <a :href="'tel:'+Store.user.phone">{{ Store.user.phone | formatPhone }}</a>
                            </LabeledTextBlock>
                            <Spacer isBottom :size="3" />
                            <LabeledTextBlock :label="'Location'">
                                {{ Store.user.city }}, {{ Store.user.state }}
                            </LabeledTextBlock>
                        </CardContent>
                    </Card>
                    <Card :slot="2" :heading="'Support'">
                        <AvatarCardHeading :heading="Store.SUPPORT.name" />
                        <CardContent>
                            <LabeledTextBlock :label="'Support'">
                                <a :href="'mailto:'+Store.SUPPORT.email">{{ Store.SUPPORT.email }}</a>
                            </LabeledTextBlock>
                            <Spacer isBottom :size="3" />
                            <LabeledTextBlock :label="'Phone'">
                                <a :href="'tel:'+Store.SUPPORT.phone">{{ Store.SUPPORT.phone }}</a>
                            </LabeledTextBlock>
                            <Spacer isBottom :size="3" />
                            <LabeledTextBlock :label="'Available'">
                                {{ Store.SUPPORT.available }}
                            </LabeledTextBlock>
                        </CardContent>
                    </Card>
                </Grid>
            </SlideIn>
        </div>
    </PageContainer>
</template>

<script>
import { InputButton } from 'inputs';
import { LoadingSpinner } from 'feedback';
import { Card, CardContent, Grid, PageHeader, PageContainer, SlideIn, Spacer } from 'layout';
import { Paragraph } from 'typography';

import AppointmentCardInfo from './AppointmentCardInfo.vue';
import AvatarCardHeading from './AvatarCardHeading.vue';
import LabeledTextBlock from './LabeledTextBlock.vue';
import NotificationPopup from '../../../../commons/NotificationPopup.vue';

export default {
    name: 'dashboard',
    components: {
        AppointmentCardInfo,
        AvatarCardHeading,
        Card,
        CardContent,
        Grid,
        InputButton,
        LabeledTextBlock,
        LoadingSpinner,
        NotificationPopup,
        PageContainer,
        PageHeader,
        Paragraph,
        SlideIn,
        Spacer
    },
    data() {
        return {
            isNotificationActive: false,
            notificationText: ''
        };
    },
    computed: {
        isDoneLoading() {
            return Store.practitioners.all.length &&
                Store.hasRequested.upcomingAppointments &&
                !Store.isLoading.upcomingAppointments;
        },
        shouldShowDoctorInfo() {
            return Store.isPatient && this.userDoctor;
        },
        shouldShowIntakeAlert() {
            return Store.isPatient &&
                Store.hasRequested.userIntake &&
                !Store.isLoading.userIntake &&
                !Store.users.intake.self;
        },
        topRowColumnConfig() {
            return this.shouldShowDoctorInfo 
                ? [{ l:6 }, { l:6 }]
                : [{ l:12 }];
        },
        userDoctor() {
            let output = null;
            Store.practitioners.licensed.some(dr => {
                if (dr.attributes.name === Store.user.doctor_name) {
                    output = dr;
                    return true;
                }
            });
            return output;
        }
    },
    mounted() {
        Store.currentPage = 'dashboard';

        if (!Store.hasRequested.userIntake && Store.isNotAdmin) {
            _App.users.getPatientIntake(Store.user.id);
        }

        if (!Store.hasRequested.upcomingAppointments) {
            _App.appointments.getUpcoming();
        }

        if (!Store.hasRequested.userPractitioner && Store.isPatient) {
            _App.practitioners.getAll();
        }

        if (Laravel.app.message) {
            this.notificationText = Laravel.app.message;
            this.isNotificationActive = true;
            setTimeout(() => this.isNotificationActive = false, 8000);
            Laravel.app.message = undefined;
        }
  }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .alert {
        border-left: 7px solid;
    }
</style>
