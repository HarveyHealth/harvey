import { assign } from 'lodash';
import Errors from './Errors.js';

export default class Form {
    constructor (data) {
        this.originalData = data;

        for (const field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    data () {
        const data = assign({}, this);

        delete data.originalData;
        delete data.errors;

        return data;
    }

    reset () {
        for (const field in this.originalData) {
            this[field] = '';
        }
    }

    submit (requestType, url, successCallback, failCallback) {
        axios[requestType](url, this.data())
            .then(this.onSuccess.bind(this, successCallback))
            .catch(error => this.onFail(error, failCallback));
    }

    onSuccess (successCallback) {
        this.errors.clear();

        if (successCallback && typeof successCallback === 'function') {
            successCallback();
        }
    }

    onFail (error, failCallback) {
        if (error.response && error.response.data) {
            this.errors.record(error.response.data);
        }
        if (failCallback) failCallback();
    }
}
