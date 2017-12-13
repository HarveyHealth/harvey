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
        columns: Array,

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
            css: '',
            // Namspacing the Grid instance
            rowId: `gridRow-${this.Config.misc.gridRowId++}`,
            // Spacing sizes based on Tachyons (rems)
            spacing: [0, 0.25, 0.7, 1.3, 2, 4, 8, 16]
        };
    },

    computed: {
        gridCss() {
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
            this.columns.map((obj, index) => {
                const colBp = Object.keys(obj)[0];
                const ratio = obj[colBp];

                styles += this.mediaQuery(colBp, `.${this.getColumnClass(index)} { ${this.columnWidth(ratio)} }`);

                // At each column object we loop through the gutter options to set gutter-specific styles
                Object.keys(this.gutters).map(gutBp => {
                    const size = this.gutters[gutBp];
                    styles += this.mediaQuery(gutBp, `
                        .${this.getColumnClass(index)} { ${this.columnWidth(ratio)} }
                        .${this.rowId} { ${this.containerSpace(size)} }
                        .${this.getColumnClass(index)} { ${this.columnSpace(size)} }
                    `);
                    // And this is where we add IE-specific overwrites
                    styles += this.mediaQuery(gutBp, `.${this.getColumnClass(index)} { ${this.columnWidth(ratio, gutBp)} }`, true);
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
        columnWidth(instructions, bp) {
            const ratio = instructions.split('of');
            const width = `${(ratio[0] / ratio[1]) * 100}%`;
            const gutter = this.gutters[bp]
                ? this.spacing[this.gutters[bp]]
                : null;

            return gutter ? `flex-basis: calc(${width} - ${gutter}rem);` : `flex-basis: ${width};`;
        },

        // Adds margin-left for container gutter offset
        containerSpace(key) {
            return `margin-left: -${this.spacing[key]}rem;`;
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
                : `@media screen and ${query} {${content}}`
        }
    },

    // In order for IE to register the dynamic styles, they must be inserted into the DOM after
    // the component mounts (as opposed to using a computed property, for instance)
    mounted() {
        this.$refs.styles_here.innerHTML = this.gridCss;
    }
};
</script>
