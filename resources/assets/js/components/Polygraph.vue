<template>
    <g>
        <polygon :points="points"></polygon>
        <circle cx="100" cy="100" r="80"></circle>
        <axis-label
            v-for="(stat, index) in stats"
            :stat="stat"
            :index="index"
        >
        </axis-label>
    </g>
</template>

<script>
    export default {
        name: 'polygraph',
        props: ['stats', 'max'],
        components: {
            'axis-label': {
                template: `<text :x="point.x" :y="point.y">{{stat.label}}</text>`,
                props: ['stat', 'index'],
                computed: {
                    point() {
                        return this.$parent.valueToPoint(
                            this.$parent.max,
                            this.index,
                            this.$parent.stats.length
                        )
                    }
                }
            }
        },
        methods: {
            valueToPoint(value, index, total) {
                let x = 0,
                    y = -value * 3.2 * this.max,
                    angle = Math.PI * 2 / total * index,
                    cos = Math.cos(angle),
                    sin = Math.sin(angle),
                    tx = x * cos - y * sin + 100,
                    ty = x * sin + y * cos + 100;

                return {
                    x: tx,
                    y: ty
                }
            }
        },
        computed: {
            points() {
                let total = this.stats.length;

                return this.stats.map(function (stat, i) {
                    let point = this.valueToPoint(stat.value, i, total)
                    return point.x + ',' + point.y
                }.bind(this)).join(' ')
            }
        }
    }
</script>

<style>
    body {
    font-family: Helvetica Neue, Arial, sans-serif;
}

polygon {
    fill: #42b983;
    opacity: .75;
}

circle {
    fill: transparent;
    stroke: #999;
}

text {
    font-family: Helvetica Neue, Arial, sans-serif;
    font-size: 10px;
    fill: #666;
}

label {
    display: inline-block;
    margin-left: 10px;
    width: 20px;
}

#raw {
    position: absolute;
    top: 0;
    left: 300px;
}
</style>