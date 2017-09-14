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
  })

  return output;
}
