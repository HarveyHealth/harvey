<template>
    <div ref="output" id="grid-css"></div>
</template>

<script>

export default {
    data() {
        return {
            COLUMN_BASE: 12,
            breakpoints: Object.keys(this.State('misc.grid')),
            spacing: this.Config.misc.spacing
        };
    },

    methods: {
        compileCSS(config) {
            let styles = '';

            for (let bp in config) {
                const bpWidth = config[bp].width;
                const classes = config[bp].classes.weedDuplicates();
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

            return `${this.styleColumnWidth(width, gutter)}${this.styleColumnMargin(gutter)}`;
        },

        styleColumnMargin(gutter) {
            if (gutter !== null) return `margin:0 ${gutter / 2}rem ${gutter}rem;`;

            return '';
        },

        styleColumnWidth(width, gutter) {
            if (width && gutter !== null) return `flex-basis:calc(${width} - ${gutter}rem);`;
            if (width) return `flex-basis:${width};`;

            return '';
        },

        styleContainerSpacing(cls) {
            const gutter = cls.split('-')[1];

            return `margin:0 -${gutter / 2}rem;`;
        },

        styleContentHeight(cls) {
            return this.selector(`.${cls} > div > *`, 'min-height:100%;');
        },

        styleGridContainer(cls) {
            const flexAt = this.selector(`.${cls}`, 'display:flex;flex:wrap;overflow:hidden;');
            const minHeight = this.styleContentHeight(cls);

            return `${flexAt}${minHeight}`;
        },

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
        }
    },

    mounted() {
        // const container = this.State('misc.grid.container.classes').flattenLists().weedDuplicates();
        // const classes = this.columnClasses.concat(container);
        this.$refs.output.innerHTML = this.compileCSS(this.State('misc.grid'));
    }
};
</script>
