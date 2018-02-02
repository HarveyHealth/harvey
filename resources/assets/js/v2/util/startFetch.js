export default function(resource) {
    Store.isLoading[resource] = true;
    Store.hasRequested[resource] = true;
}