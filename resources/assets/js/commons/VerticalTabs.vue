<template>
    <div id="vertical-tabs" class="columns is-gapless vertical-tabs">
        <div class="column is-3 tabs-navigation">
            <aside class="menu">
                <ul class="menu-list">
                    <li class="menu-label">Choose Test</li>
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
                activeTab: `tab-${this.loadWithId}` || null,
            }
        },
        props: {
            loadWithId: String,
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
