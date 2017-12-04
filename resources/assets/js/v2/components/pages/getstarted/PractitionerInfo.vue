<template>
    <div v-if="practitioner">
        <div class="bg" :style="practitionerBackground"></div>
        <img class="avatar" :src="practitionerAvatar" />
        <Spacer isBottom :size="3" />
        <div class="pa3">
            <Heading2 v-if="practitioner.attributes.name">{{ practitioner.attributes.name }}, ND</Heading2>
            <Spacer isBottom :size="3" />
            <Paragraph v-if="practitioner.attributes.license_number">
                License {{ practitioner.attributes.license_number }}
            </Paragraph>
            <div class="tl">
                <Paragraph v-if="practitioner.attributes.description" :weight="'thin'">
                    {{ practitioner.attributes.description }}
                </Paragraph>
                <hr class="divider mv3" />
                <ul class="info">
                    <li v-if="practitioner.attributes.graduated_at">
                        <span>Graduated:</span> {{ practitioner.attributes.graduated_at.date | year }}
                    </li>
                    <li v-if="practitioner.attributes.school">
                        <span>Degree:</span> {{ practitioner.attributes.school }}
                    </li>
                    <li v-if="practitioner.attributes.specialty">
                        <span>Specialties:</span> {{ practitioner.attributes.specialty | specialty }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import { Spacer } from 'layout';
import { Heading2, Paragraph } from 'typography';

export default {
    props: {
        practitioner: Object
    },
    components: {
        Heading2,
        Paragraph,
        Spacer
    },
    computed: {
        practitionerAvatar() {
            return this.determineImage(this.practitioner.attributes.picture_url, 'user');
        },
        practitionerBackground() {
            return {
                backgroundImage: `url(${this.determineImage(this.practitioner.attributes.background_picture_url, 'background')})`
            }
        }
    },
    filters: {
        specialty(list) {
            return list.join(', ');
        },
        year(value) {
            return moment(value).format('YYYY');
        }
    },
    methods: {
        determineImage(image, type) {
            return image ? image : `https://d35oe889gdmcln.cloudfront.net/assets/images/default_${type}_image.png`;
        },
    }
}
</script>

<style lang="scss" scoped>
    @import '~sass';

    .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 2px solid white;
        position: absolute;
        top: 22px;
        left: 50%;
        transform: translate(-50%, 0);
    }

    .bg {
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        height: 80px;
        opacity: 0.5;
        width: 100%;
    }

    .divider {
        border: none;
        border-bottom: 2px solid $color-gray-5;
        width: 85%;
    }

    .info {
        list-style: none;
        padding: 0;

        span {
            color: $color-accent-dark;
        }
    }
</style>
