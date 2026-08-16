/**
 * Decode the payload of a JWT without verifying the signature.
 * The frontend does not have the JWT secret, so it can only check
 * structural validity and expiry.
 */
export function decodeTokenPayload(token) {
	try {
		const base64 = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
		const json = Buffer.from(base64, 'base64').toString('utf-8');
		return JSON.parse(json);
	} catch {
		return null;
	}
}

export function isTokenValid(token) {
	const payload = decodeTokenPayload(token);
	if (!payload || !payload.exp) {
		return false;
	}
	return payload.exp * 1000 > Date.now();
}
