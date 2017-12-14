<template>
    <div :class="gridClasses">
        <div v-for="(col, i) in columns" :class="columnClasses[i]">
            <slot :name="(i + 1)"></slot>
        </div>
    </div>
</template>

<script>
// need separate container margin classes
// need separate container class for default styles
export default {
    props: {
        columns: {
            type: Array,
            required: true
        },
        gutters: {
            type: Object,
            default: function() {
                return {};
            }
        }
    },

    data() {
        return {
            breakpoints: Object.keys(this.State('misc.grid')),
            spacing: this.Config.misc.spacing
        };
    },

    computed: {
        // Grid config is a compilation of column and gutter data.
        // It allows us to map over both sets and combine width and gutters styles efficiently
        gridConfig() {
            let config = [];

            // Map column data to compile data
            this.columns.map(obj => {
                let columnConfig = {};
                this.breakpoints.map(bp => {
                    columnConfig[bp] = { gutter: 'x', width: 'x' };
                });

                // Map column breakpoints and assign widths
                Object.keys(obj).map(bp => { columnConfig[bp].width = obj[bp]; });

                if (this.gutters) {
                    // Map gutters and assign gutters
                    Object.keys(this.gutters).map(bp => { columnConfig[bp].gutter = this.gutters[bp] * 1; });

                    // Now loop through columnConfig to retroactively apply gutters if needed.
                    // This is based on the principle that a gutter from a previous breakpoint
                    // will apply to larger screen widths unless told otherwise.
                    let previous = null;
                    for (let key in columnConfig) {
                        if (!obj[key] && !this.gutters[key]) {
                            delete columnConfig[key];
                        } else {
                            // Assign previous gutter to current gutter if no current gutter exists
                            if (previous && !this.hasGutter(columnConfig[key])) {
                                columnConfig[key].gutter = columnConfig[previous].gutter;
                            }
                            previous = key;
                        }
                    }
                }

                let previous = null;
                for (let key in columnConfig) {
                    if (previous && !this.hasWidth(columnConfig[key])) {
                        columnConfig[key].width = columnConfig[previous].width;
                    }
                    previous = key;
                }

                config.push(columnConfig);
            });

            return config;
        },

        columnClasses() {
            return this.gridConfig.map(obj => {
                let classes = [];
                for (let bp in obj) {
                    classes.push(this.createColumnClass(bp, obj[bp]));
                }
                return classes;
            });
        },

        gridClasses() {
            let classes = [];

            this.gridConfig.map(obj => {
                let flexAtIsFound = false;

                for (let bp in obj) {
                    const config = obj[bp];

                    if (this.hasWidth(config) && !flexAtIsFound) {
                        classes.push(`g-${bp}`);
                        flexAtIsFound = true;
                    }

                    if (this.hasGutter(config) && flexAtIsFound) {
                        classes.push(this.createGridClass(bp, config));
                    }
                }
            });
            return classes.weedDuplicates();
        }
    },

    methods: {
        createGridClass(bp, config) {
            return `s-${config.gutter}-${bp}`;
        },

        createColumnClass(bp, config) {
            return `c-${config.width}-${config.gutter}-${bp}`;
        },

        hasGutter(config) {
            return config.gutter !== 'x';
        },

        hasWidth(config) {
            return config.width !== 'x';
        }
    },

    beforeMount() {
        this.gridClasses.map(cls => {
            const parts = cls.split('-');
            const bp = parts.length > 2 ? parts[2] : parts[1];
            this.State(`misc.grid.${bp}.classes`).push(cls);
        });
        this.columnClasses.flattenLists().map(cls => {
            const bp = cls.split('-')[3];
            this.State(`misc.grid.${bp}.classes`).push(cls);
        });
    }
};
</script>
