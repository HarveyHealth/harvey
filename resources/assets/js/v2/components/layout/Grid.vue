<template>
    <div>
        <div ref="styles_here"></div>
        <div :class="rowId" ref="row">
            <div v-for="(col, i) in columns" :class="getColumnClass(i)">
                <slot :name="(i + 1)"></slot>
            </div>
        </div>
    </div>
</template>

<script>
// Grid
// -----------------------------------------------------------------------------
// The grid component takes configuration for columns and gutters for a given
// row. Configuration is parsed and a block of CSS is generated. Each instance
// of Grid recieves a unique id based on Config.misc.gridRowId which is used to
// namespace class names and styling. Grid uses flex box.
//
// The number of columns in the grid depends on how many config objects are
// included in the columns prop. The column array is mapped and each object
// creates a named slot in the template. Any element or component can be used as
// a column by attaching the column slot number, even another Grid component:
//   <Grid :columns="[{ m: '1of2' }, { m: '1of2' }]">
//     <Card slot="1">...</Card>
//     <Card slot="2">...</Card>
//   </Grid>
//
// Column width is defined by ratios in config strings. Format is '[num]of[num]'
// so '1of2' = 50% and '1of3' = 33.33333%

export default {
    props: {
        // Column configuration is an array of objects which
        // define the width of the column at specific breakpoints
        // [{ m: '1of2' }, { m: '1of2' }]
        // This says each each column will be 50% at the medium breakpoint
        // Breakpoint key names are the same used by Tachyons with 's' added for default
        // s, ns, m, l, xl
        columns: {
            type: Array,
            required: true
        },

        // Gutter configuration is an array of objects which
        // defines the width of column gutters at specific breakpoints
        // [{ m: 2 }, { l: 3 }]
        // This says at medium breakpoint, gutters are size 2
        // Sizing follows Tachyons spacing conventions
        gutters: {
            type: Object,
            default: function() {
                return {};
            }
        },

        // This is a string of a breakpoint key that defines when the
        // grid engages as display: flex
        // Possible: s, ns, m, l, xl
        flexAt: String,

        // When true, the first node in each column slot gets a class 'h100'
        // to ensure each column's height is the same per row
        matchColumnHeights: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            // Namspacing the Grid instance
            rowId: `gridRow-${this.Config.misc.gridRowId++}`,
            // Spacing sizes based on Tachyons (rems)
            spacing: [0, 0.25, 0.7, 1.3, 2, 4, 8, 16]
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
                Object.keys(App.Config.misc.breakpoints).map(bp => {
                    columnConfig[bp] = { gutter: null, width: null };
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
                            if (previous && !columnConfig[key].gutter) {
                                columnConfig[key].gutter = columnConfig[previous].gutter;
                            }
                            previous = key;
                        }
                    }
                }

                config.push(columnConfig);
            });

            return config;
        },

        gridCss() {
            if (!this.gridConfig) return;

            // First we set up default row container styles
            let styles = '';
            styles += `.${this.rowId} { display: flex; flex-wrap: wrap; overflow: hidden; }`;

            // If column heights should be matched, we add height 100 to slot nodes
            if (this.matchColumnHeights) {
                styles += `[class*="--Column"] > * { height: 100%; }`;
            }

            // If flexAt is specified, we wrap initial styles in media query
            if (this.flexAt) {
                styles = this.mediaQuery(this.flexAt, styles);
            }

            // Next we loop through column objects to handle:
            //  1. column widths
            //  2. column widths with gutter applied
            //  3. row margin offset for gutters
            //  4. column spacing for gutters
            //  5. IE-specific media-query to add calc() to column width
            this.gridConfig.map(columnConfig => {
                const colSelector = `.${this.getColumnClass(index)}`;

                Object.keys(columnConfig).map(bp => {
                    const ratio = columnConfig[bp].width;
                    const gutter = columnConfig[bp].gutter;

                    // If a width ratio exists, write default column width
                    if (ratio) {
                        styles += this.mediaQuery(bp, `${colSelector} { ${this.columnWidth(ratio)} }`);
                        // If a gutter and width exist, write out IE overwrites
                        if (gutter !== null) {
                            styles += this.mediaQuery(bp, `${colSelector} { ${this.columnWidth(ratio, gutter)} }`, true);
                        }
                    }

                    // Now apply container and column gutters if gutter exists
                    if (gutter !== null) {
                        const containerGutters = `.${this.rowId} { ${this.containerSpace(gutter)} }`;
                        const columnGutters = `${colSelector} { ${this.columnSpace(gutter)} }`;

                        styles += this.mediaQuery(bp, `${containerGutters} ${columnGutters}`);
                    }
                });
            });

            return `<style>${styles}</style>`;
        }
    },

    methods: {
        // Adds padding for gutters
        columnSpace(key) {
            return `padding-left: ${this.spacing[key]}rem; padding-bottom: ${this.spacing[key]}rem;`;
        },

        // Determines flex-basis. If breakpoint is passed, will return calc() version for IE
        // instructions = the ratio string ('1of2')
        columnWidth(instructions, gutter) {
            const width = this.convertRatio(instructions);
            const gutterSize = gutter ? this.spacing[gutter] : null;

            return gutterSize ? `flex-basis: calc(${width} - ${gutterSize}rem);` : `flex-basis: ${width};`;
        },

        // Adds margin-left for container gutter offset
        containerSpace(key) {
            return `margin-left: -${this.spacing[key]}rem;`;
        },

        // Converts ratio string ('1of2') to width ('50%')
        convertRatio(ratio) {
            const r = ratio.split('of');

            return `${(r[0] / r[1]) * 100}%`;
        },

        // Returns a column selector
        getColumnClass(index) {
            return `${this.rowId}--Column-${index}`;
        },

        // Wraps content in a specific media query.
        // Note: if addIe is true, the specified content will be wrapped in -ms specific media queries.
        // When calculating flex-basis, IE does not take into account element padding (like border-box).
        // Because of this, column flex-basis needs to use calc to subtract the given gutter size.
        // To isolate these styles, we wrap them in -ms specific media queries to ensure only IE10 and IE11
        // will parse and apply them.
        mediaQuery(bp, content, addIe) {
            if (bp === 's') {
                return addIe
                    ? `@media (-ms-high-contrast: none), (-ms-high-contrast: active) {${content}}`
                    : content;
            }
            let query = `(min-width: ${App.Config.misc.breakpoints[bp]}px)`;

            return addIe
                ? `@media ${query} and (-ms-high-contrast: none), ${query} and (-ms-high-contrast: active) {${content}}`
                : `@media screen and ${query} {${content}}`;
        }
    },

    // In order for IE to register the dynamic styles, they must be inserted into the DOM after
    // the component mounts (as opposed to using a computed property, for instance)
    mounted() {
        this.$refs.styles_here.innerHTML = this.gridCss;
        console.log(this.gridConfig);
    }
};
</script>
