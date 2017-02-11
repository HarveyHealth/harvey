<template>
    <div class="columns is-gapless">
        <div class="column is-3 tabs-navigation">
            <aside class="menu">
                <ul class="menu-list">
                    <li v-for="tabData in tabList">
                        <a
                            :class="{'is-active': tabData.id === activeTab}"
                            @click="setActiveTab(tabData)"
                        >{{ tabData.label }}</a>
                    </li>
                </ul>
            </aside>
        </div>
        <div class="column tabs-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import {assign} from 'lodash';

    export default {
        data() {
            return {
                tabList: {},
                activeTab: null
            }
        },
        methods: {
            updateTab(tabData) {
                this.tabList = _.assign({}, this.tabList, {
                    [tabData.id]: tabData
                });
            },
            setActiveTab(tabData) {
                this.activeTab = tabData.id;
            },
            getTabIndex(id) {
                const idList = Object.keys(this.tabList);
                return idList.indexOf(id);
            }
        },
        computed: {
            activeTabNumber() {
                if (this.activeTab) {
                    return this.getTabIndex(this.activeTab);
                }
            }
        },
        mounted() {
            this.$nextTick(() => {
                if (Object.keys(this.tabList).length && !this.activeTab) {
                    let firstTab = Object.keys(this.tabList)[0];

                    this.setActiveTab(this.tabList[firstTab]);
                }
            })
        }
    }
</script>

<style lang="sass" scoped>
    $border: 1px solid hsl(0, 0%, 86%);

    .tabs-navigation {
        border: $border;
        @media screen and (max-width: 767px) {
            border-bottom: none;
        }
        @media screen and (min-width: 768px) {
            border-right: none;
        }
        li:not(:last-child) {
            border-bottom: $border;
        }
    }

    .tabs-content {
        border: $border;
        overflow: hidden;
        @media screen and (max-width: 767px) {
            border-top: none;
        }
        .columns.is-gapless > &.column {
            padding: 1.5rem 0.75rem;
            @media screen and (min-width: 768px) {
                padding: 2rem;
            }
        }
    }
</style>