<template>
    <div v-if="pagination.previous || canShowNext" class="cf">
        <router-link :to="{ path: '/' + pagination.previous }" v-if="pagination.previous">
            <i class="f6 fa fa-long-arrow-left"></i>
            <span class="dib f6 mh1">{{ steps[pagination.previous] }}</span>
        </router-link>
        <router-link :to="{ path: '/' + pagination.next }" v-if="pagination.next && canShowNext" class="fr">
            <span class="dib f6 mh1">{{ steps[pagination.next] }}</span>
            <i class="f6 fa fa-long-arrow-right"></i>
        </router-link>
    </div>
</template>

<script>
export default {
    props: {
        step: String
    },

    data() {
        return {
            steps: {
                confirmation: 'Confirmation',
                payment: 'Payment',
                phone: 'Phone',
                practitioner: 'Practitioner',
                schedule: 'Schedule'
            }
        }
    },

    computed: {
        // We can show the link to the next step only if that step has been completed
        canShowNext() {
            return this.State(`getstarted.signup.stepsCompleted.${this.step}`);
        },
        // Pagination is based on the index of steps in getstarted.signup.steps
        pagination() {
            return {
                next: this.State('getstarted.signup.steps')[this.stepIndex + 1],
                previous: this.State('getstarted.signup.steps')[this.stepIndex - 1]
            }
        },
        stepIndex() {
            return this.State('getstarted.signup.steps').indexOf(this.step);
        }
    }
}
</script>

<style lang="scss" scoped>
    @import '~sass';

    a {
        color: $color-fade-light;
        transition: color 200ms ease-in-out;

        &:hover {
            color: $color-white;
        }
    }

    .fa-long-arrow-left,
    .fa-long-arrow-right {
        position: relative;
        top: 1px;
    }

    .fa-long-arrow-left {
        left: 3px;
        transition: left 200ms ease-in-out;

        a:hover & {
            left: 0;
        }
    }

    .fa-long-arrow-right {
        right: 3px;
        transition: right 200ms ease-in-out;

        a:hover & {
            right: 0;
        }
    }
</style>
