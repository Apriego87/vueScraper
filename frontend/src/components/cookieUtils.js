// Function to get the token from the cookie
/* const getTokenFromCookie = () => {
    
};

export default getTokenFromCookie */

export function getTokenFromCookie() {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.startsWith('token=')) {
            return cookie.substring(6);
        }
    }
    return null;
}