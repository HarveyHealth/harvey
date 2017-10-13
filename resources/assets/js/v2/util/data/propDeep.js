// Requests the value of a property within a nested object by passing in
// an array of keys indicating the path of the object tree to search.
// If the property is undefined, the function will return null by default,
// unless ifEmpty is passed, in which case it will return that as a fallback.
// Example:
//    const obj = { a: { b: 'c' } }
//    pathDeep(['a', 'b'], obj) yields 'c'
//    pathDeep(['a', 'foo'], obj) yields null
//    pathDeep(['a', 'foo'], obj, 'N/A') yields 'N/A'
export default function(path, object, ifEmpty) {
  let output = object;
  const last = path.length - 1;
  const empty = ifEmpty !== undefined ? ifEmpty : null;
  if (typeof object !== 'object') return empty;

  path.map((prop, i) => {
    const isObj = typeof output === 'object';
    const isLast = i === last;
    const notFalsy = output !== null && output !== false && output !== undefined
    const valid = isObj && notFalsy;

    if (valid && !isLast) {
      output = output.hasOwnProperty(prop) ? output[prop] : empty;
    } else if ((!valid && !isLast) || !valid) {
      output = empty;
    } else if (isLast) {
      output = output[prop] === undefined ? empty : output[prop];
    }
  });

  return output;
}
