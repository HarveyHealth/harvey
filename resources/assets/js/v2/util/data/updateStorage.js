import fromStorage from './fromStorage';
import killStorage from './killStorage';
import toStorage from './toStorage';

export default function(storageKey, property, value) {
  let storage = JSON.parse(fromStorage(storageKey));
  storage[property] = value;
  killStorage(storageKey);
  toStorage(storageKey, JSON.stringify(storage));
}
