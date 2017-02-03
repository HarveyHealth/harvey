import {assign} from 'lodash';
import Errors from './Errors.js';

export default class Form {
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    data() {
        let data = _.assign({}, this);

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

        if (successCallback && typeof successCallback === 'function') {
            successCallback();
        }
    }

    onFail(error) {
        if (error.response && error.response.data) {
            this.errors.record(error.response.data);
        }
    }
}