/**
 * Fetches data from the given API URL.
 *
 * @param {string} url The URL to fetch data from.
 * @returns {Promise<Object>} A promise that resolves to the JSON response.
 * @throws {Error} Throws an error if the HTTP response is not ok.
 */
async function fetchFromApi(url) {
    const response = await fetch(url);
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    return await response.json();
}