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
    align: String,
    at: String,
    columns: Array,
    gutter: Number,
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
        case 'not-small': return `@media screen and (min-width: 420px) {${content}}`
        case 'medium': return `@media screen and (min-width: 640px) {${content}}`
        case 'large': return `@media screen and (min-width: 780px) {${content}}`
        case 'xlarge': return `@media screen and (min-width: 960px) {${content}}`
      }
    },
    column(config, i) {
      let css = this.defaultCol(i);
      for (var breakpoint in config) {
        const parts = config[breakpoint].split('of');
        const width = `${(parts[0] / parts[1]) * 100}%`;
        const style = this.selectorCol(i, `flex-basis:${width};`)
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
    if (this.at) css = this.atQuery(this.at, css);
    const colCss = this.columns.map((config, i) => this.column(config, i + 1))
      .join('');
    this.$refs.css.innerHTML = this.styles(`${css}${colCss}`);
    const columnNodes = this.$refs.row.childNodes;
    for (var i = 0; i < columnNodes.length; i++) {
      columnNodes[i].className+= ` gridCol-${i + 1}`;
    }
  }
}
</script>
