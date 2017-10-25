export default function(names) {
  return names.sort((a, b) => {
    const nameA = a.name.replace(/,.+/g, '').toUpperCase();
    const nameB = b.name.replace(/,.+/g, '').toUpperCase();
    if (nameA < nameB) {
      return -1;
    } else if (nameA > nameB) {
      return 1;
    } else {
      return 0;
    }
  });
}
