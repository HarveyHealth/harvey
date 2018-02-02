<template>
    <div :class="gridClasses">
        <div v-for="(col, i) in columns" :class="columnClasses[i]">
            <slot :name="(i + 1)"></slot>
        </div>
    </div>
</template>

<script>
import { flattenDeep, uniq } from 'lodash';

export default {
    props: {
        // Array of column configuration objects.
        // Each column object takes Tachyons breakpoints as keys.
        // Values are column width based on a 12-point grid system.
        // Example: [{ s: 6, m: 3 }, { s: 6, m: 3 }]
        columns: {
            type: Array,
            required: true
        },
        // Object with Tachyons breakpoints as keys.
        // Each value is a Tachyons spacing number. This will apply that amount
        // of space between columns and below columns at the specified breakpoint
        // and all subsequent widths.
        // Example: { s: 2, m: 3 }
        gutters: {
            type: Object,
            default: function() {
                return {};
            }
        }
    },

    data() {
        return {
            // Breakpoints and spacing are based on Tachyons values
            breakpoints: Object.keys(this.State.misc.grid),
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
                    // Map gutters and assign gutters.
                    // We multiply by 1 here so that the value is not a reference to the prop
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

                // We'll loop through configuration again, this time applying widths retroactively.
                // This is because column widths are based on flex-basis with a calc() value.
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

        // Returns a list of all column necessary column classes
        columnClasses() {
            return this.gridConfig.map(obj => {
                let classes = [];
                for (let bp in obj) {
                    classes.push(this.createColumnClass(bp, obj[bp]));
                }
                return classes;
            });
        },

        // Returns classes to determine when the grid starts and what
        // spacing to apply to the container.
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
            return uniq(classes);
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
        // We update global state here which will be used by GridStyles to generate CSS
        this.gridClasses.map(cls => {
            const parts = cls.split('-');
            const bp = parts.length > 2 ? parts[2] : parts[1];
            this.State.misc.grid[bp].classes.push(cls);
        });
        flattenDeep(this.columnClasses).map(cls => {
            const bp = cls.split('-')[3];
            this.State.misc.grid[bp].classes.push(cls);
        });
    }
};
</script>
