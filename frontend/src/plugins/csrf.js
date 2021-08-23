const crypto = require('crypto');

export default function ({app}) {
    app.$cookies.set('XSRF-TOKEN', crypto.randomBytes(16).toString('hex'), {
        path: '/',
        maxAge: 60 * 60 * 24 * 365
    });
}
