export default class Errors {
    constructor() {
        this.errors = {};
    }

    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    any() {
        return Object.keys(this.errors).length > 0;
    }

    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    record(errors) {
        this.errors = errors;
    }

    clear(field) {
        if (field) {
            delete this.errors[field];
            return;
        }

        this.errors = {};
    }
}

export default class Form {
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    data() {
        let data = Object.assign({}, this);

        delete data.originalData;
        delete data.errors;

        return data;
    }

    reset() {
        for (let field in this.originalData) {
            this[field] = '';
        }
    }

    submit(requestType, url, successCallback) {
        axios[requestType]( url, this.data() )
            .then( this.onSuccess.bind(this, successCallback) )
            .catch( this.onFail.bind(this) );
    }

    onSuccess(successCallback) {
        this.errors.clear();
        
        if (successCallback) {
            successCallback();
        }
    }

    onFail(error) {
        if (error.response.data) {
            this.errors.record(error.response.data);
        }
    }
}