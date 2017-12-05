import hljs from 'highlight.js';

const editorOption = {
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'header': 1 }, { 'header': 2 }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'indent': '-1' }, { 'indent': '+1' }],
            ['link', 'image']
        ],
        syntax: {
            highlight: text => hljs.highlightAuto(text).value
        }
    },
    theme: 'snow'
};

export default editorOption;
