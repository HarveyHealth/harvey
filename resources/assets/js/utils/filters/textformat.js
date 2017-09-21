function capitalize (value) {
    if (value) {
        return value[0].toUpperCase() + value.slice(1);
    }
    return value;
}

function phone (value) {
    if (value) {
        return value.replace(/[^0-9]/g, '')
            .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    }
    return value;
}

function hyperlink (value, type) {
    let ret = '';

    switch (type) {
    case 'phone':
        ret = 'tel:' + value;
        break;
    case 'email':
        ret = 'mailto:' + value;
        break;
    }

    return ret;
}

export { capitalize, phone, hyperlink };
