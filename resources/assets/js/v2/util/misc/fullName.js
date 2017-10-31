// Getting really tied of concatenating first and last name together
// from user objects, so this just does it for you when given the attributes.
export default function(attributes) {
  if (attributes.first_name && attributes.last_name) {
    return `${attributes.first_name} ${attributes.last_name}`;
  } else {
    return null;
  }
}
