<template>
    <div class="typeform-container">
        <a href="/dashboard" class="close-link">
            <i class="fa fa-close" />
        </a>
        <div
            class="typeform-widget"
            :data-url="typeformUrl"
            data-transparency=20
            data-hide-headers=false
            data-hide-footer=false
            style="width: 100%; height: 100vh;"
        ></div>
    </div>
</template>

<script>
export default {
    name: 'Intake',
    data() {
        return {
            typeformUrl: `https://goharvey.typeform.com/to/XGnCna?harvey_id=${Laravel.user.id}&intake_validation_token=${Laravel.user.intake_validation_token}`
        };
    },
    mounted() {
        // add Typeform script
        /* eslint-disable */
        (function() {
            var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm", b="https://embed.typeform.com/";
            if(!gi.call(d,id)) { js=ce.call(d,"script");
            js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q); }
        })();
        /* eslint-enable */
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .close-link {
        color: $color-copy;
        display: inline-block;
        font-size: 1.2rem;
        padding: 0.8rem 1.2rem;
        position: fixed;
        z-index: 1000;
        right: 0;
    }
</style>
