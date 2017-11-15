<template>
  <div>
    <div :class="rowId.replace('Row','Styles')" v-html="css"></div>
    <div :class="rowId" ref="row">
      <slot :name="(i + 1)" v-for="(col, i) in columns"></slot>
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
    // For alligning column content
    // 'bottom', 'middle', or 'top'
    alignTo: String,

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
    gutters: Object,

    // This is a string of a breakpoint key that defines when the
    // grid engages as display: flex
    // Possible: s, ns, m, l, xl
    flexAt: String
  },
  data() {
    return {
      css: '',
      // Namspacing the Grid instance
      rowId: `gridRow-${this.Config.misc.gridRowId++}`,
    }
  },
  methods:{
    // Outputs alignment styling
    alignment() {
      switch (this.align) {
        case 'middle': return `.${this.rowId}{ align-items:center; }`
        case 'top': return `.${this.rowId}{ align-items:flex-start; }`
        case 'bottom': return `.${this.rowId}{ align-items:flex-end; }`
      }
    },

    // Wraps given styles in associated media query
    atQuery(query, content) {
      switch (query) {
        case 's': return content;
        case 'ns': return `@media screen and (min-width: 420px) {${content}}`
        case 'm': return `@media screen and (min-width: 640px) {${content}}`
        case 'l': return `@media screen and (min-width: 780px) {${content}}`
        case 'xl': return `@media screen and (min-width: 960px) {${content}}`
      }
    },

    // If a gutter is specified, column width must be calculated to compensate
    calcWidth(content, gutter) {
      return `calc(${content} - ${this.gutterValue(gutter)}rem);`
    },

    // Loops column configuration and builds column styling
    // Determines flex-basis based on breakpoints and/or gutters
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

    // Outputs default column styles
    defaultCol(index) {
      return this.selectorCol(index, 'flex-basis:100%;');
    },

    // Outputs default row styles
    defaultRow() {
      return this.selectorRow('display:flex; flex-wrap:wrap; margin:0 auto; overflow:hidden;');
    },

    // Loops config and ouputs gutters styles
    gutter(config) {
      let css = '';
      for (var breakpoint in config) {
        css += this.gutterStyle(breakpoint, config[breakpoint])
      }
      return css;
    },

    // Builds relevant gutter styles and wraps them in given breakpoint
    // Determins row and column margins
    gutterStyle(breakpoint, value) {
      const space = this.gutterValue(value);
      const halfSpace = space / 2;
      const row = `margin-left: -${halfSpace}rem; margin-right: -${halfSpace}rem;`;
      const col = `margin-bottom: ${space}rem; margin-left: ${halfSpace}rem; margin-right: ${halfSpace}rem;`;
      const rowStyles = this.selectorRow(row);
      const colStyles = `.${this.rowId} > * { ${col} }`
      const styles = rowStyles + colStyles;

      switch (breakpoint) {
        case 's': return styles
        case 'ns': return this.atQuery('ns', styles)
        case 'm': return this.atQuery('m', styles)
        case 'l': return this.atQuery('l', styles)
        case 'xl': return this.atQuery('xl', styles)
      }
    },

    // Takes in Tachyons value and outputs associated Tachyons rem value
    gutterValue(value) {
      switch (value) {
        case 0: return 0;
        case 1: return .25;
        case 2: return .7;
        case 3: return 1.3;
        case 4: return 2;
        case 5: return 4;
        case 6: return 8;
        case 7: return 16;
      }
    },

    // Wraps given styles in a namespaced column selector
    selectorCol(index, content) {
      return `.${this.rowId} > .gridCol-${index}{ ${content} }`;
    },

    // Wraps given styles in a namespaced row selector
    selectorRow(content) {
      return `.${this.rowId}{ ${content} }`;
    },

    // Wraps styles in style blocks
    styles(content) {
      return `<style>${content}</style>`;
    }
  },
  mounted() {
    // Start with basic default row styling
    let css = this.defaultRow();

    // Add alignment styles if applicable
    if (this.align) css += this.alignment();

    // Wrap those default styles in a media query if flexAt defined
    if (this.flexAt) css += this.atQuery(this.flexAt, css);

    // Add gutter styles if applicable
    if (this.gutters) css += this.gutter(this.gutters);

    // Loop column config and generate column styles
    const colCss = this.columns
      .map((config, i) => this.column(config, i + 1))
      .join('');

    // Combine all styles and assign to css
    this.css = this.styles(`${css}${colCss}`);

    // <slot> elements do not retain attributes when they are replaced by what
    // fills them in. Because of this, we have to manually apply namespaced
    // column classes once they have been added after mount.
    const columnNodes = this.$refs.row.childNodes;
    for (var i = 0; i < columnNodes.length; i++) {
      columnNodes[i].className+= ` gridCol-${i + 1}`;
    }
  }
}
</script>
