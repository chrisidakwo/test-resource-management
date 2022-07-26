/**
 * Redirect to an external URL.
 *
 * @param {string} link
 */
export const externalRedirect = (link) => {
    window.location = link;
}

/**
 * Copy the given text to clipboard.
 *
 * @param text
 * @returns {Promise<void>}
 */
export const copyToClipboard = (text) => {
    return navigator.clipboard.writeText(text);
}

/**
 * Convert a string to title case.
 *
 * @param {string} text
 * @returns {string}
 */
export const toTitleCase = (text) => {
    return text.toLowerCase().split(' ').map((word) => {
        return toSentenceCase(word);
    }).join(' ');
}

/**
 * Return a given text as sentence case.
 *
 * @param {string} text
 * @returns {string}
 */
export const toSentenceCase = (text) => {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

export const getImgURL = (url, callback) => {
    const xhr = new XMLHttpRequest();

    xhr.onload = function() {
        callback(xhr.response);
    };

    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}
