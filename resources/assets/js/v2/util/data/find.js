import propDeep from './propDeep';

// find an object in an array of objects where map resolves to resolution
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
      const _path = pair.path.split('.');
      if (propDeep(_path, obj, ifEmpty) !== pair.resolve) {
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
