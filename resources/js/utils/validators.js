const minLength = min => {
    return (input, name) => input.length < min
        ? `${name} must be at least ${min} characters`
        : null;
};

const isRequired = () => {
    return (input, name) => input.length === 0 ? `${name} is required` : null;
}

const isValidUrl = (urlString) => {
    const urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
        '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator

    return !!urlPattern.test(urlString);
}

export { minLength, isRequired, isValidUrl };
