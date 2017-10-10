import propDeep from './propDeep';

// Attempts to return a single object within an array of objects by checking
// if particular properties within that object resolve to the values given.
// collection = the array of objects
// instructions = array of objects set as { where: 'to.the.prop', is: 'value' }
// ifEmpty = the fall back value to return if no match is found
// Example:
//    const list = [
//      { a: { b: 'foo' } },
//      { a: { b: 'bar' } }
//    ]
//    const instructions = [{ where: 'a.b', is: 'bar' }];
//    find(list, instructions) yields { a: { b: 'bar' } }
export default function(collection, instructions, ifEmpty) {
  if (!collection || typeof collection !== 'object') {
    console.warn('collection is required and must be an object');
    return;
  }
  if (!instructions || typeof instructions !== 'object') {
    console.warn('instructions is required and must be an array');
    return;
  }

  let output;
  collection.some(obj => {
    let match = true;
    instructions.forEach(pair => {
      const _path = pair.where.split('.');
      if (propDeep(_path, obj, ifEmpty) !== pair.is) {
        match = false
      }
    })
    if (match) {
      output = obj;
      return true;
    }
  })

  return output;
}
