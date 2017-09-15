export default function(attributes) {
  if (attributes.first_name && attributes.last_name) {
    return `${attributes.first_name} ${attributes.last_name}`;
  } else {
    return null;
  }
}
