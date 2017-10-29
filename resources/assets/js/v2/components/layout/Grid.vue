<template>
  <div>
    <div ref="css" :class="rowId.replace('Row','Styles')"></div>
    <div :class="rowId" ref="row">
      <slot :name="(i + 1)" v-for="(col, i) in columns" :ref="'gridCol-'+ (i + 1)"></slot>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    alignTo: String,
    columns: Array,
    gutters: Object,
    flexAt: String
  },
  data() {
    return {
      rowId: `gridRow-${this.Config.misc.gridRowId++}`
    }
  },
  methods:{
    alignment() {
      switch (this.align) {
        case 'middle': return `.${this.rowId}{ align-items:center; }`
        case 'top': return `.${this.rowId}{ align-items:flex-start; }`
        case 'bottom': return `.${this.rowId}{ align-items:flex-end; }`
      }
    },
    atQuery(query, content) {
      switch (query) {
        case 's': return content;
        case 'ns': return `@media screen and (min-width: 420px) {${content}}`
        case 'm': return `@media screen and (min-width: 640px) {${content}}`
        case 'l': return `@media screen and (min-width: 780px) {${content}}`
        case 'xl': return `@media screen and (min-width: 960px) {${content}}`
      }
    },
    calcWidth(content, gutter) {
      return `calc(${content} - ${this.gutterValue(gutter)}rem);`
    },
    column(config, i) {
      let css = this.defaultCol(i);
      for (var breakpoint in config) {
        const parts = config[breakpoint].split('of');
        let width = `${(parts[0] / parts[1]) * 100}%`;
        let style = this.selectorCol(i, `flex-basis:${width};`)
        if (this.gutters) {
          for (var bp in this.gutters) {
            style += this.selectorCol(i, `flex-basis:${this.calcWidth(width, this.gutters[bp])};`);
          }
        }
        css += this.atQuery(breakpoint, style);
      }
      return css;
    },
    defaultCol(index) {
      return this.selectorCol(index, 'flex-basis:100%;');
    },
    defaultRow() {
      return this.selectorRow('display:flex; flex-wrap:wrap; margin:0 auto; overflow:hidden;');
    },
    gutter(config) {
      let css = '';
      for (var breakpoint in config) {
        css += this.gutterStyle(breakpoint, config[breakpoint])
      }
      return css;
    },
    gutterStyle(breakpoint, value) {
      const space = this.gutterValue(value);
      const halfSpace = space / 2;
      const row = `margin-left:-${halfSpace}rem;margin-right:-${halfSpace}rem;`;
      const col = `margin-bottom:${space}rem;margin-left:${halfSpace}rem;margin-right:${halfSpace}rem;`;
      const rowStyles = this.selectorRow(row);
      const colStyles = `.${this.rowId} > * {${col}}`
      const styles = rowStyles + colStyles;

      switch (breakpoint) {
        case 's': return styles
        case 'ns': return this.atQuery('ns', styles)
        case 'm': return this.atQuery('m', styles)
        case 'l': return this.atQuery('l', styles)
        case 'xl': return this.atQuery('xl', styles)
      }
    },
    gutterValue(value) {
      switch (value) {
        case 0: return 0;
        case 1: return .25;
        case 2: return .5;
        case 3: return 1;
        case 4: return 2;
        case 5: return 4;
        case 6: return 8;
        case 7: return 16;
      }
    },
    selectorCol(index, content) {
      return `.${this.rowId} > .gridCol-${index}{${content}}`;
    },
    selectorRow(content) {
      return `.${this.rowId}{${content}}`;
    },
    styles(content) {
      return `<style>${content}</style>`;
    }
  },
  mounted() {
    let css = this.defaultRow();
    if (this.align) css += this.alignment();
    if (this.at) css += this.atQuery(this.at, css);
    if (this.gutters) css += this.gutter(this.gutters);
    const colCss = this.columns.map((config, i) => {
      return this.column(config, i + 1);
    }).join('');
    this.$refs.css.innerHTML = this.styles(`${css}${colCss}`);
    const columnNodes = this.$refs.row.childNodes;
    for (var i = 0; i < columnNodes.length; i++) {
      columnNodes[i].className+= ` gridCol-${i + 1}`;
    }
  }
}
</script>
