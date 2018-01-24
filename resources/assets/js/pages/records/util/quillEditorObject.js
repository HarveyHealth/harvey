import hljs from 'highlight.js';

const editorOption = {
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'header': 1 }, { 'header': 2 }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            ['link']
        ],
        syntax: {
            highlight: text => hljs.highlightAuto(text).value
        }
    },
    theme: 'snow'
};

export default editorOption;
