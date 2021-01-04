# bahai-writings-handler

[Custom protocol handler](https://developer.mozilla.org/en-US/docs/Web-based_protocol_handlers)
library ([browser support](https://caniuse.com/#search=custom%20protocol%20handling))
to facilitate adding abstract links in webpages leading to the Bahá'í Writings
in whatever web app or software registers the "web+bahaiwritings" handler.

Also has abstract, reusable `default-href.js` client-side code to facilitate
default fallback behavior when browser support is lacking.

See the [Demo](https://bahai-library.com/test-bahai-web-protocol/test-bahai-web-protocol.html).

# To-dos

1. Move the abstract code into a more general purpose library and add as a
    dependency.
2. Add a protocol redirector for https://bahai-browser.org (Chrome only)
