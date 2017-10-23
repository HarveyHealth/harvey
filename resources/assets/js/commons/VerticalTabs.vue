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
                            :data-url="tabData.url"
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
                currentUrl: null,
                previousTab: null
            };
        },
        props: {
            loadWithId: String
        },
        methods: {
            updateTab(tabData) {
                this.tabList = _.assign({}, this.tabList, {
                    [tabData.id]: tabData
                });
            },
            setActiveTab(tabData) {
                this.activeTab = tabData.id;
                this.currentUrl = tabData.url;

                if (this.currentUrl) {
                  window.history.pushState({ tab: this.previousTab }, null, this.currentUrl);
                }

                // remember the previous tab
                this.previousTab = tabData;
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
                    const firstTab = Object.keys(this.tabList)[0];
                    this.setActiveTab(this.tabList[firstTab]);
                }
            });

            window.onpopstate = (e) => {
              const firstTab = Object.keys(this.tabList)[0];
              const historyTab = e.state.tab || this.tabList[firstTab];

              this.setActiveTab(historyTab);
            };
        }
    };
</script>
