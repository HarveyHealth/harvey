import chai from 'chai';
import sinon from 'sinon';
import sinonChai from 'sinon-chai';

chai.use(sinonChai);

global.expect = chai.expect;
global.sinon = sinon;

// console errors
const oldError = console.error;
before(() => { console.error = (message) => { throw new Error(message); }; });
after(() => { console.error = oldError; });

const __karmaWebpackManifest__ = [];
const inManifest = path => __karmaWebpackManifest__.includes(path);

// require all `tests/**/*.specs.js`
const testsContext = require.context('./', true, /\.specs\.js$/);

// only run tests that have changed after the first pass.
const testsToRun = testsContext.keys().filter(inManifest)
;(testsToRun.length ? testsToRun : testsContext.keys()).forEach(testsContext);
