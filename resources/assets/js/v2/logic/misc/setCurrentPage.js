// Sets the current page so the UserNav can update accordingly
export default function(page) {
  const State = this.$root.$data.State;

  State.misc.currentPage = page;
}
