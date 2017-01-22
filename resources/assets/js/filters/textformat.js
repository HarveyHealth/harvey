function capitalize(value) {
    if (value) {
        return value[0].toUpperCase() + value.slice(1);
    }

    return value;
}

export {capitalize}