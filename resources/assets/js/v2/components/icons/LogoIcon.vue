<template>
    <span class="container">
        <svg v-if="justIcon" viewBox="0 0 92 123" :style="styles">
            <path :stroke="iconFill" :fill="iconFill" fill-rule="evenodd" d="M76.488 95.98c-3.153 1.193-7.722 1.798-13.58 1.798-5.35 0-10.54-.52-13.495-.88C53.433 91.928 64.263 79.2 72 76.272c2.808-1.064 5.39-1.603 7.673-1.603 4.552 0 7.58 2.124 8.53 5.986C89.69 86.71 85.09 92.724 76.487 95.98M45.378 3.735l.3-.006c5.2 0 8.76 5.613 9.074 14.3.285 7.914-6.147 21.122-8.98 26.535-2.613-5.282-8.442-17.883-8.726-25.797C36.726 9.854 40 3.953 45.38 3.734M29.49 97.778c-5.857 0-10.425-.605-13.578-1.797-8.604-3.256-13.204-9.27-11.716-15.324.95-3.862 3.98-5.987 8.532-5.987 2.28 0 4.863.538 7.67 1.602 7.742 2.93 18.57 15.655 22.59 20.626-2.957.36-8.142.88-13.5.88m-10.2-37.316c-6.944-4.92-9.668-11.366-6.938-16.42 1.31-2.428 3.467-3.71 6.237-3.71 2.714 0 5.905 1.245 9.23 3.6 6.203 4.397 12.9 18.144 15.415 23.673-5.357-.733-17.866-2.836-23.944-7.143m44.68-16.53c3.323-2.355 6.514-3.6 9.228-3.6 2.772 0 4.927 1.282 6.24 3.71 2.727 5.054.003 11.5-6.94 16.42-6.078 4.307-18.588 6.41-23.945 7.143 2.52-5.528 9.212-19.274 15.42-23.673m27.068 36.024c-1.263-5.144-5.513-8.216-11.365-8.216-2.638 0-5.537.602-8.675 1.792-8.352 3.16-19.205 15.925-23.586 21.25V70.718c4.38-.492 19.378-2.606 26.797-7.863 8.23-5.832 11.284-13.764 7.81-20.205-1.83-3.385-4.96-5.248-8.815-5.248-3.32 0-7.072 1.432-10.89 4.138-5.812 4.12-11.982 15.004-14.903 21.718v-15.45c1.46-3.447 10.645-19.94 10.29-29.886C57.245 5.282 50.94.8 45.693.8c-.14 0-.286.002-.426.008-5.426.22-11.573 5.084-11.107 18.064.387 10.75 10.33 28.464 10.33 29.498v14.89c-2.92-6.713-9.147-17.6-14.96-21.72-3.82-2.706-7.607-4.138-10.928-4.138-3.853 0-6.987 1.863-8.813 5.246-3.48 6.44-.385 14.372 7.845 20.206 7.42 5.257 22.475 7.372 26.856 7.863V94.03c-4.38-5.69-15.05-17.48-23.03-20.498-3.137-1.19-6.082-1.792-8.716-1.792-5.854 0-10.108 3.072-11.376 8.216-1.863 7.585 3.44 14.952 13.517 18.765 3.483 1.32 8.425 1.987 14.636 1.987 6.302 0 12.05-.678 14.97-1.038v20.7c0 .81.654 1.465 1.46 1.465.81 0 1.462-.655 1.462-1.465V99.585c1.46.324 8.694 1.122 15.526 1.122 6.212 0 11.112-.668 14.595-1.986 10.077-3.812 15.37-11.18 13.505-18.764" />
        </svg>
        <svg v-else viewBox="0 0 250 50" class="logo" :style="styles" >
            <g fill="none" fill-rule="evenodd">
                <path :fill="iconFill" d="M39.68 49.97a20.85 20.85 0 0 1-7 .93 60.32 60.32 0 0 1-7-.46c2.09-2.58 7.71-9.2 11.72-10.72a11.4 11.4 0 0 1 4-.83c2.36 0 3.94 1.1 4.43 3.11.77 3.15-1.61 6.27-6.08 8M23.5 2.03h.16c2.7 0 4.55 2.92 4.71 7.43.15 4.11-3.19 11-4.66 13.79-1.36-2.75-4.38-9.3-4.53-13.41-.17-4.63 1.53-7.7 4.33-7.81m-8.22 48.88a20.85 20.85 0 0 1-7-.93c-4.47-1.69-6.85-4.82-6.08-8 .49-2 2.07-3.11 4.43-3.11a11.41 11.41 0 0 1 4 .83c4 1.52 9.64 8.14 11.73 10.72a60.31 60.31 0 0 1-7 .46M9.99 31.5c-3.6-2.56-5-5.91-3.6-8.53a3.49 3.49 0 0 1 3.24-1.93 8.7 8.7 0 0 1 4.79 1.87c3.22 2.29 6.7 9.43 8 12.3-2.78-.38-9.27-1.47-12.43-3.71m23.19-8.59a8.7 8.7 0 0 1 4.79-1.87 3.49 3.49 0 0 1 3.24 1.93c1.42 2.63 0 6-3.6 8.53-3.16 2.24-9.65 3.33-12.43 3.71 1.31-2.87 4.78-10 8-12.3m14.06 18.73c-.66-2.67-2.86-4.27-5.9-4.27a12.81 12.81 0 0 0-4.5.93c-4.34 1.64-10 8.28-12.24 11V36.84c2.26-.25 10.05-1.34 13.9-4.09 4.27-3 5.86-7.15 4.05-10.5a5 5 0 0 0-4.57-2.75 10.12 10.12 0 0 0-5.65 2.15c-3 2.14-6.22 7.8-7.74 11.29v-8c.76-1.79 5.53-10.36 5.34-15.53C29.7 2.83 26.42.5 23.7.5h-.2c-2.82.11-6 2.64-5.77 9.39.2 5.59 5.36 14.79 5.36 15.33v7.74c-1.52-3.49-4.75-9.15-7.77-11.29a10.18 10.18 0 0 0-5.68-2.17 5 5 0 0 0-4.57 2.73c-1.81 3.35-.2 7.47 4.07 10.5 3.85 2.77 11.66 3.86 13.93 4.11v12.12c-2.27-3-7.81-9.09-12-10.65a12.92 12.92 0 0 0-4.52-.93c-3 0-5.25 1.6-5.9 4.27-1 3.94 1.79 7.77 7 9.75a22.33 22.33 0 0 0 7.6 1 63.81 63.81 0 0 0 7.77-.54v10.79a.76.76 0 1 0 1.52 0v-10.8a55.16 55.16 0 0 0 8.06.58 22.2 22.2 0 0 0 7.58-1c5.23-2 8-5.81 7-9.75"/>
                <path :class="textClass" :fill="textFill" d="M69.3 30.5h15.61V16.31h5.46V52.5h-5.46V35.6H69.3v16.9h-5.46V16.31h5.46zM120.78 43.71h-15.5l-4 8.79h-5.91l17.91-38.46 17.27 38.46h-6zm-2.23-5.13l-5.37-12.3-5.62 12.3zM147.81 37.08l11.2 15.42h-6.68L142 37.69h-1V52.5h-5.46V16.31h6.4q7.17 0 10.36 2.7a9.9 9.9 0 0 1 3.52 7.92 10.28 10.28 0 0 1-2.2 6.61 9.82 9.82 0 0 1-5.81 3.54zm-6.8-4.15h1.73q7.76 0 7.76-5.93 0-5.55-7.55-5.55H141zM165.5 16.31l10 25.08 10.15-25.08h6l-16.29 38.91-15.86-38.91zM216.55 21.45h-14.51v8.7h14.09v5.13h-14.09v12.09h14.51v5.13h-20V16.31h20zM231.5 36.87l-11.79-20.56h6.28l8.27 14.46 8.3-14.46h6.28l-11.86 20.56V52.5h-5.48z"/>
            </g>
        </svg>
    </span>
</template>

<script>
export default {
    props: {
        alwaysShowText: Boolean,
        hasDarkIcon: Boolean,
        hasDarkText: Boolean,
        hasWhiteIcon: Boolean,
        height: String,
        justIcon: Boolean,
        revealText: Boolean,
        width: String
    },
    computed: {
        defaultStyles() {
            return {
                height: this.justIcon ? '110px' : '60px',
                width: this.justIcon ? '80px' : '150px'
            };
        },
        iconFill() {
            if (this.hasDarkIcon) return '#5f7278';
            if (this.hasWhiteIcon) return '#ffffff';
            return '#b4e7a0';
        },
        styles() {
            return {
                height: this.height || this.defaultStyles.height,
                width: this.width || this.defaultStyles.width
            };
        },
        textClass() {
            return {
                'logo-text': true,
                'always-text': this.alwaysShowText,
                'reveal-text': this.revealText
            };
        },
        textFill() {
            if (this.hasDarkText) return '#5f7278';
            return '#ffffff';
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    svg {
        color: white;
    }

    .container {
        display: inline-block;
    }

    .logo-text {
        display: none;
    }

    .always-text {
        display: inline-block;
    }

    .reveal-text {
        @include query(md) {
            display: inline-block;
        }
    }


</style>
