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
