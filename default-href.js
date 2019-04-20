// Needs ParentNode, etc. polyfill
/*
FUTURE TODOS:
1) May need to change to dynamically insert web intent elements intead if the following
    goes through: http://lists.whatwg.org/htdig.cgi/whatwg-whatwg.org/2012-February/034881.html
2) Change "menuitemName" variable if contextmenu and <command> supported later in other browsers
3) Change "itemprop" from "menuitem" if different one considered better
4) Note somewhere that Chrome will register itself even for IE-clicked links, so IE CAN in a sense
    work with these links
*/

// TESTED BASICALLY IN THE FOLLOWING
// CHROME 22.0.1229.79 m:   WORKS
// FF 15.0.1:               WORKS
// IE8                      FALLS BACK CORRECTLY (REGISTRATION NOT SUPPORTED)
// SAFARI 5.1.7 (7534.57.2) FALLS BACK CORRECTLY (REGISTRATION NOT SUPPORTED)
// OPERA 12.02              FAILS: GIVING DOM SECURITY ERR

// PRIVATE STATIC VARS
let _menuCtr = 0;
// Should never need duplicates of scheme handlers within an application
const _supportMap = {};

function _rightClicked (e) {
    return e.button === 2;
}

function _cloneJSON (obj) {
    return JSON.parse(JSON.stringify(obj)); // Deep Clone
}

/**
* Checks whether any protocol handler exists (at least assuming it loads within 3500 ms);
*   uses a timeout set to check whether an iframe with the protocol has loaded
* @param {String} testProtocol
* @param {Function} cb Callback upon success (with the first argument
                        set to true), and if no errBack is present, it will
                        instead be called with false as its single argument
* @param {Function} errBack Optional error callback (will be called with false as single argument)
*/
function isAnyProtocolHandlerRegistered (testProtocol, cb, errBack, timeout) {
    const iframe = document.createElement('iframe');
    let success = false;
    errBack = errBack || cb;
    iframe.style.display = 'none';
    iframe.src = testProtocol +
        // Should have no side effects if following REST principles
        (testProtocol.includes(':') ? '' : ':test');
    iframe.onload = () => { // DOMContentLoaded does not work in Firefox or Chrome (and not IE)
        success = true; // We could also safely ignore instead of clearTimeout
        clearTimeout(timeout);
        cb(true);
    };
    timeout = setTimeout(() => {
        if (!success) {
            errBack(false);
        }
    }, timeout || 3500);
    document.body.append(iframe);
}

class DefaultHref {
    /**
    * Allows omission of 'new' keyword, sets configuration defaults, sets up initial event handlers, may end up
    prompting for registration of a scheme depending on configuration (though Chrome might not support without user event?)
    * @class Handles data-default-href attribute and fallback contextmenus (meta itemprop=menuitem element link children)
    * @param {Object} protocolConfig The initial scheme-specific configuration
    * @param {Object} dhc The cross-scheme default-href config object
    */
    constructor (protocolConfig, dhc) {
        this.schemeMap = {};
        protocolConfig = this.protocolConfig = protocolConfig || {};
        dhc = dhc || {};

        // Object-wide preference defaulting
        this.fallbacks = dhc.fallbacks || {};
        this.addDefaultHrefHandler =
            typeof dhc.addDefaultHrefHandler === 'undefined'
                ? true
                : dhc.addDefaultHrefHandler;
        // Might take "command" if supported later in other browsers
        this.menuitemName = dhc.menuitem || 'menuitem';

        if (protocolConfig.scheme) {
            this.setProtocolHandler(protocolConfig);
        }
        else if (protocolConfig) {
            this.setProtocolHandlers(protocolConfig);
        }
        if (!dhc.delayInitEventHandlers) {
            this.initEventHandlers();
        }
    }

    /**
    * Adds a registerProtocolHandler call where supported, and if not,
    *   default to other behaviors
    */
    addRegisterListener (node, type, handler, capturing) {
        if (!handler || typeof handler === 'object') {
            var defaultHrefConfig = handler ||
                (this.protocolConfig.scheme ? // If single scheme config supplied, we can use it for convenience
                    this.schemeMap[this.protocolConfig.scheme] :
                    false);

            handler = function (e) {
                e.preventDefault();
                if (this.registerProtocolHandlerSupported(defaultHrefConfig.scheme)) { // We could just check for navigator.registerProtocolHandler but we want a chance for the handlers to run
                    this.register(
                        defaultHrefConfig.scheme,
                        defaultHrefConfig.handler_url,
                        defaultHrefConfig.name
                    );
                }
            };
        }

        node.addEventListener(type, (e) => {
            handler.call(this, e);
        }, capturing);
    }

    /**
    * Set up initial or on page load event handlers
    */
    initEventHandlers () {
        if (this.addDefaultHrefHandler) {
            window.addEventListener('DOMContentLoaded', () => {
                // Could call as onclick to ensure available even before DOM load
                document.body.addEventListener('click', (e) => {
                    this.defaultHrefHandler(e);
                });
            });
        }
        if (this.fallbacks) {
            if (!this.fallbackLazyLoad) {
                window.addEventListener('DOMContentLoaded', () => {
                    this.fallbackMenuBuilder();
                });
            }
            // We run even if lazy load option is false to make available before page load; if lazy load is true, we
            //   will not do any work on page load, but first click of any element will be slower
            document.addEventListener('contextmenu', (e) => {
                // Pass target for convenience & compatibility with other approach below
                this.fallbackMenuEventHandler(e.target);
            });
        }
    }

    /**
    * Handler for data-default-href link attribute, preventing
    * default (though delegater might utilize href depending
    * on configuration); ignores other clicks.
    * @param {Event} e Click event object
    */
    defaultHrefHandler (e) {
        if (_rightClicked(e)) {
            return;
        }

        const a = e.target,
            data_default_href = a.getAttribute('data-default-href'), //a.dataset.defaultHref
            backupURL = a.href;
        if (a.nodeName.toLowerCase() !== 'a' || !data_default_href) {
            return;
        }
        // We prevent default and handle ourselves after timeout within successful_protocol_check if necessary
        e.preventDefault();

        this.delegateLocation(data_default_href, backupURL);
    }

    /**
    * General check of whether there is a registerProtocolHandler support or not, and if not, handlers may be run
    */
    registerProtocolHandlerSupported (data_default_href, backupURL) {
        var scheme = data_default_href.match(/^([^:]+?)(:.+)?$/)[1], // Different regex than in other method as possible to be scheme without being URL
            sma = this.schemeMap['*'] || {},
            sm = this.schemeMap[scheme] || sma, // Allow for generic scheme configuration (but do not register handlers!)
            name = sm.name,
            handler_url = sm.handler_url,
            test_handler_url = sm.test_handler_url;

        // Note: it is possible that registration does not exist but the protocol will work anyways
        if (!navigator.registerProtocolHandler && (sm.redirectIfNotSupported || sma.redirectIfNotSupported)) { // No custom protocol registration support and browser support redirect enabled
            if (sm.handleBrowserRedirect) {
                sm.handleBrowserRedirect(sm.redirectForBrowserSupport, scheme, name, handler_url, test_handler_url);
            }
            else if (sma.handleBrowserRedirect) {
                sma.handleBrowserRedirect(sma.redirectForBrowserSupport, scheme, name, handler_url, test_handler_url);
            }
            else if ((sm.confirms && confirm(sm.not_supported_message_redirect)) ||
                        (sma.confirms && confirm(sma.not_supported_message_redirect))) {
                // Confirmations enabled and confirms ok to redirect (otherwise, will stay on page doing nothing)
                window.location = sm.redirectForBrowserSupport || sma.redirectForBrowserSupport;
            }
            // else {} // The user cancelled confirmation
            return false;
        }
        // Browser does not support (but no redirect enabled)
        if (!navigator.registerProtocolHandler) {
            if (sm.handleHref) {
                sm.handleHref(backupURL);
            } else if (sma.handleHref) {
                sma.handleHref(backupURL);
            } else if (sm.useProtocolWithoutRegisterSupport) { // Meant to add another condition here?
                if (data_default_href.indexOf(':') > -1) {
                    window.location = data_default_href;
                }
            } else if (
                // confirmations disabled or the user opts to go on to fallback href rather than stay on the page
                (!sm.confirms || confirm(sm.not_supported_message)) ||
                (!sma.confirms || confirm(sma.not_supported_message))
            ) {
                if (backupURL) {
                    window.location = backupURL;
                }
            }
            // else {} // The user cancelled confirmation
            return false;
        }
        return true;
    }

    /**
    * Delgate for a specific URL to which to redirect (default or fallback)
    */
    delegateLocation (data_default_href, backupURL) {
        if (!this.registerProtocolHandlerSupported(data_default_href, backupURL)) {
            return;
        }

        var scheme = data_default_href.match(/^(.+?):/)[1],
            sma = this.schemeMap['*'] || {},
            sm = this.schemeMap[scheme] || sma, // Allow for generic scheme configuration (but do not register handlers!)
            name = sm.name,
            handler_url = sm.handler_url,
            test_handler_url = sm.test_handler_url;

        if ( // Specific (same-domain) protocol handler URL supplied for checking and browser supports custom protocol registration, but not registered
            handler_url &&
            navigator.isProtocolHandlerRegistered &&
            !navigator.isProtocolHandlerRegistered(scheme, handler_url)
        ) {
            // We at first don't go through simulated check of whether any protocol handler is registered,
            //  but eventually do in case registered through some other site; this function check may be
            //  visited again if the simulated check also doesn't find support
            if (!(sm.giveHandlersLowerPriority || sma.giveHandlersLowerPriority) && this.handleNotEnabled(sm, sma, scheme, name, handler_url, test_handler_url, backupURL, (sm.avoidSimulatedProtocolCheck || sma.sm.avoidSimulatedProtocolCheck))) {
                return;
            }
        }
        // Allow "fall-through" (for true handleNotEnabled()) without needing to re-specify this function call
        this.successful_protocol_check(data_default_href, sm, sma, scheme, name, handler_url, test_handler_url, backupURL, false);
    }

    /**
    * Attempts to detect whether the protocol is supported, and if so, will redirect the page to it, and if not,
    *   it will redirect to a backup URL ()
    */
    successful_protocol_check (data_default_href, sm, sma, scheme, name, handler_url, test_handler_url, backupURL, useBackupURL) {
        if (_supportMap[scheme]) {
            window.location = data_default_href;
            /*if (location != data_default_href) { // This way to check doesn't work on all browsers
                if (!this.handleNotEnabled(sm, sma, scheme, name, handler_url, test_handler_url, backupURL)) {
                    return;
                }
            }*/
            return;
        }
        if (_supportMap[scheme] === false) { // Note that if the user clicks ok to the browser to register a handler after success is set to false below, this line may wrongly execute
            _supportMap[scheme] = null; // If it fails once, give it a chance to be checked later without caching in
                                        // case user enables it (yes, it will be slower if user keeps clicking
                                        // without approving, but that is not the expected use case
            this.handleNotEnabled(sm, sma, scheme, name, handler_url, test_handler_url, backupURL, useBackupURL); // last arg was true
            return;
        }

        var args = arguments;
        isAnyProtocolHandlerRegistered(
            data_default_href,
            (success) => {
                _supportMap[scheme] = success;
                this.successful_protocol_check(...args);
            }
        );
    }

    /**
    * Handles case when already determined that there is a Protocol handler URL
    * supplied and browser supports custom protocol registration, but not registered
    * Autoactivates registration if so configured and if not, it calls handlers
    * if present (for case of protocol handlers not being enabled),
    * or, if not present, will optionally redirect the user, upon a confirm()
    * dialog to a designated URL (at which messages could be placed about how
    * to register for a protocol handler or find such handlers).
    */
    handleNotEnabled (sm, sma, scheme, name, handler_url, test_handler_url, backupURL, useBackupURL) {
        if (sm.handleNotEnabled) {
            sm.handleNotEnabled(sm.not_enabled_message, scheme, name, handler_url, test_handler_url, backupURL, useBackupURL);
            return true;
        }
        if (sma.handleNotEnabled) {
            sma.handleNotEnabled(sma.not_enabled_message, scheme, name, handler_url, test_handler_url, backupURL, useBackupURL);
            return true;
        }
        if ((sm.not_enabled_message && confirm(sm.not_enabled_message)) ||
            (sma.not_enabled_message && confirm(sma.not_enabled_message))) {
            window.location = test_handler_url;
            return true;
        }
        if (this.autoActivateRegisterTrigger('click', sm, scheme, handler_url, name)) {
            // Should now have been able and successful to trigger registration request so we don't continue to simulate protocol support check (or continue on) until the user approves and clicks again and we can't detect when the user may do this next
            return true;
        }
        // go on directly to href if present; otherwise will simulate protocol support check by returning false
        if (useBackupURL && backupURL) {
            window.location = backupURL;
            return true;
        }
        // No handlers of not enabled
        return false;
    }

    /**
    * @param {'click'|'start'} type Type of activation to run (note 'start' might not be supported in browsers besides Firefox)
    * @param {Object} sm
    * @param {String} scheme
    * @param {String} handler_url
    * @param {String} name
    */
    autoActivateRegisterTrigger (type, sm, scheme, handler_url, name) {
        var autoActivationEventTriggers = sm.autoActivationEventTriggers;
        if (autoActivationEventTriggers && autoActivationEventTriggers.indexOf(type) > -1) {
            return this.register(scheme, handler_url, name);
        }
        return false;
    }

    /*
        On context menus, see:
            http://thewebrocks.com/demos/context-menu/
            http://www.whatwg.org/specs/web-apps/current-work/multipage/interactive-elements.html#context-menus
            http://hacks.mozilla.org/2011/11/html5-context-menus-in-firefox-screencast-and-code/
    */
    fallbackMenuEventHandler (a) {
        if (a.hasAttribute('contextmenu') ||
            !(a.dataset.contextKey || a.getElementsByTagName('meta').length)) { // a.hasAttribute('data-context-key')
            return;
        }
        var key = a.dataset.contextKey || ''; // a.getAttribute('data-context-key');

        var contextMenuID = key + 'FallbackMenu' + (++_menuCtr);
        a.setAttribute('contextmenu', contextMenuID);

        var menu = document.createElement('menu');
        menu.setAttribute('type', 'context');
        menu.setAttribute('id', contextMenuID);

        // Allow fallbacks to be expressed declaratively in
        // HTML (e.g., as <meta itemprop="menuitem" content="Name1=URL1" />)

        this.fallbacks[''] = [];
        [...a.querySelectorAll('meta')].forEach((meta) => {
            if (meta.getAttribute('itemprop') !== 'menuitem') {
                return;
            }
            var content = meta.getAttribute('content');
            var nameValue = content.split('=');
            var obj = {};
            obj[decodeURIComponent(nameValue[0])] = decodeURIComponent(
                nameValue.splice(1).join('=')
            ); // Allow for multiple equal signs after first one
            this.fallbacks[''].push(obj);
        });

        this.fallbacks[key].concat(this.fallbacks['']).forEach((fbPair) => {
            const menuitem = document.createElement(this.menuitemName),
                key = Object.keys(fbPair)[0],
                value = fbPair[key];
            menuitem.setAttribute('label', key);
            menuitem.setAttribute('title', value);
            menuitem.addEventListener('click', () => {
                location.href = value;
            });
            menu.append(menuitem);
        });

        document.body.append(menu);
    }

    fallbackMenuBuilder () {
        [...document.querySelectorAll('a')].forEach((a) => {
            this.fallbackMenuEventHandler(a);
        });
    }

    setProtocolHandlers (protocolConfig) { // {'web+1': {}, 'web+2': {}, etc.}
        const schemes = protocolConfig.schemes || protocolConfig;

        const configObj = {};
        for (const scheme in schemes) {
            configObj[scheme] = _cloneJSON(schemes[scheme]);
            configObj[scheme].scheme = scheme;
            this.setProtocolHandler(schemes[scheme]);
        }
    }

    setProtocolHandler (protocolConfig) { // {scheme: 'web+1', otherConfig...}
        var {name, scheme, handler_url} = protocolConfig;
            // autoActivationEventTriggers = protocolConfig.autoActivationEventTriggers; // Not in use?

        this.schemeMap[scheme] = protocolConfig;

        var confirms = this.schemeMap[scheme].confirms =
            typeof this.schemeMap[scheme].confirms === 'undefined'
                ? true
                : this.schemeMap[scheme].confirms;

        // Protocol-specific defaults
        this.schemeMap[scheme].redirectIfNotSupported =
            typeof protocolConfig.redirectIfNotSupported === 'undefined'
                ? true
                : protocolConfig.redirectIfNotSupported;

        if (confirms) {
            this.schemeMap[scheme].redirectForBrowserSupport =
                protocolConfig.redirectForBrowserSupport || 'http://getfirefox.com/';
            this.schemeMap[scheme].not_supported_message =
                protocolConfig.not_supported_message ||
                    "Your browser does not support protocol registration, something which we would use to "+
                    "allow you to make your own choice about which website tool you would wish to use to visit "+
                    "specially-written links as used on this page. However, we will pass you on to a default URL "+
                    "unless you click cancel";
            this.schemeMap[scheme].not_supported_message_redirect =
                protocolConfig.not_supported_message_redirect ||
                    "Your browser does not support protocol registration, something which we would use to "+
                    "allow you to make your own choice about which website tool you would wish to use to visit "+
                    "specially-written links as used on this page. You will now be redirected to " +
                    this.schemeMap[scheme].redirectForBrowserSupport + ' unless you click cancel.';
            this.schemeMap[scheme].not_enabled_message =
                protocolConfig.not_enabled_message ||
                    "You have not yet opted to register a handler for the \"" + scheme + "\" "+
                    "protocol, a protocol which allows you to visit links which will direct you to "+
                    "the tool you prefer, so you will instead be sent directly to "+
                    "a default URL specified within this page unless you click cancel "+
                    "to stay on this page and choose the protocol suggested by this page.";
        }

        this.autoActivateRegisterTrigger('start', protocolConfig, scheme, handler_url, name);
    }

    /**
    * Register a protocol handler if supported
    * @param {String} scheme Scheme to register
    * @param {String} handler_url URL of the site's handler (with %s to replace query string)
    * @param {String} name Visible name user agent may show to user
    * @returns {Boolean} Whether navigator.registerProtocolHandler is available or not
    */
    register (scheme, handler_url, name) {
        if (!navigator.registerProtocolHandler) {
            return false;
        }
        navigator.registerProtocolHandler(scheme, handler_url, name);
        return true;
    }
}

export default DefaultHref;
