<template>
    <div ref="output" id="grid-css"></div>
</template>

<script>
import { uniq } from 'lodash';

export default {
    data() {
        return {
            COLUMN_BASE: 12,
            breakpoints: Object.keys(this.State('misc.grid')),
            spacing: this.Config.misc.spacing
        };
    },

    // This computed property consumes the breakpoint data from state and generates
    // the CSS needed for each Grid instance currently mounted. This will be injected
    // into the node above on mount as well as when CSS update (via watch)
    computed: {
        CSS() {
            return this.compileCSS(this.State('misc.grid'));
        }
    },

    methods: {
        // Generates CSS per media queries
        compileCSS(config) {
            let styles = '';

            for (let bp in config) {
                const bpWidth = config[bp].width;
                const classes = uniq(config[bp].classes);
                if (classes.length) {
                    const css = classes.map(this.writeClassStyles).join('');
                    styles += this.queryWrap(bpWidth, css);
                }
            }

            return `<style>${styles}</style>`;
        },

        queryWrap(width, content) {
            return width
                ? `@media screen and (min-width:${width}px){${content}}`
                : content;
        },

        selector(sel, content) {
            return `${sel}{${content}}`;
        },

        styleColumn(cls) {
            const parts = cls.split('-');
            const width = parts[1] === 'x' ? null : `${(parts[1] / this.COLUMN_BASE) * 100}%`;
            const gutter = parts[2] === 'x' ? null : this.spacing[parts[2]];

            return `${this.styleColumnWidth(width, gutter)}${this.styleColumnMargin(width, gutter)}`;
        },

        // For column margin bottom
        styleColumnMargin(width, gutter) {
            if (width && gutter !== null) return `margin:0 ${gutter / 2}rem ${gutter}rem;`;
            if (gutter !== null) return `margin:0 0 ${gutter}rem;`;

            return '';
        },

        // For column flex-basis.
        // Uses calc to determine gutters.
        styleColumnWidth(width, gutter) {
            if (width && gutter !== null) return `flex-basis:calc(${width} - ${gutter}rem);`;
            if (width) return `flex-basis:${width};`;

            return '';
        },

        // Applies negative margins to container
        styleContainerSpacing(cls) {
            const gutter = this.spacing[cls.split('-')[1]];

            return `margin:0 -${gutter / 2}rem;`;
        },

        // This ensures the passed in components extend the full height of the row.
        // (Does not work in Safari)
        styleContentHeight(cls) {
            return this.selector(`.${cls} > div > *`, 'min-height:100%;');
        },

        // Default styles for grid container to be applied at the first breakpoint listed.
        styleGridContainer(cls) {
            const flexAt = this.selector(`.${cls}`, 'display:flex;flex-wrap:wrap;overflow:hidden;');
            const minHeight = this.styleContentHeight(cls);

            return `${flexAt}${minHeight}`;
        },

        // Parses given class and generates styles accordingly
        writeClassStyles(cls) {
            const type = cls.split('-')[0];
            switch(type) {
                case 'c':
                    return this.selector(`.${cls}`, this.styleColumn(cls));
                case 'g':
                    return this.styleGridContainer(cls);
                case 's':
                    return this.selector(`.${cls}`, this.styleContainerSpacing(cls));
                default:
                    return '';
            }
        },

        // For some reason, if we attach the generated styles to a node via v-html, IE will not
        // register the CSS until the window is resized.
        writeCSS(css) {
            this.$refs.output.innerHTML = css;
        }
    },

    watch: {
        // Watches for any updates to the styling and reinserts CSS
        CSS(styles) {
            this.writeCSS(styles);
        }
    },

    mounted() {
        this.writeCSS(this.CSS);
    }
};
</script>
