
export function generateCodeVerifier(length = 64)
{
    const chars =     'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~'

    let verifier = ''
    const random = crypto.getRandomValues(new Uint8Array(length))

    for(let i = 0;i < length;i++)
    {
        verifier += chars[random[i] % chars.length]
    }

    return verifier
}
// export function generateCodeVerifier(length = 64)
// {
//     const array = new Uint8Array(length)
//     window.crypto.getRandomValues(array)
//     return btoa(String.fromCharCode(...array)).replace(/\+/g,'-').replace(/\//g,'_').replace(/=+$/,'')
// }

export async function generateCodeChallenge(codeVerifier : string)
{
    const encoder = new TextEncoder()
    const data = encoder.encode(codeVerifier)
    const hash = await crypto.subtle.digest('SHA-256',data)

    return btoa(String.fromCharCode(...new Uint8Array(hash))).replace(/\+/g,'-').replace(/\//g,'_').replace(/=+$/,'')
}