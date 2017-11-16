import fromStorage from './fromStorage';
import killStorage from './killStorage';
import toStorage from './toStorage';

// storageKey { string | required } = name of the storage item to update
// updates { string, object | required } = key/value pairs to update
export default function(storageKey, updates) {
  let storage = JSON.parse(fromStorage(storageKey));

  for (var key in updates) storage[key] = updates[key];

  killStorage(storageKey);
  toStorage(storageKey, JSON.stringify(storage));
}
