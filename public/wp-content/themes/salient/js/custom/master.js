/**
 * Cookie Notification
 * @constructor
 */
function CookieNotification() {
    this.COOKIE_ACCEPTED_NAME = 'bsu_cookies_accepted';
    this.init();
}

/**
 * Initialize CookieNotification
 */
CookieNotification.prototype.init = function () {
    //create localStorage value if it does not exist
    if (localStorage.getItem(this.COOKIE_ACCEPTED_NAME) === null) {
        localStorage.setItem(this.COOKIE_ACCEPTED_NAME, '0')
    }

    //init cookie notification if user has not accepted cookies yet
    if (!this.hasAcceptedCookies()) {
        var xhttp = new XMLHttpRequest();
        var obj = this;

        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.body.insertAdjacentHTML('beforeend', JSON.parse(xhttp.response).html);
                obj.bindClickListener();

                setTimeout(function() {
                    obj.show();
                }, 2000);
            }
        };
        xhttp.open("GET", window.happy.paths.ajaxurl + '?action=fetchCookieNotificationHTML', true);
        xhttp.send();
    }

    this.onAcceptCookies = this.onAcceptCookies.bind(this);
};

/**
 * Check in localStorage if user has accepted cookies yet
 *
 * @returns {boolean}
 */
CookieNotification.prototype.hasAcceptedCookies = function () {
    return localStorage.getItem(this.COOKIE_ACCEPTED_NAME) === '1';
};

/**
 * Bind click listener to button
 */
CookieNotification.prototype.bindClickListener = function () {
    if (document.querySelector('.cookie-notification__agree') !== null) {
        document.querySelector('.cookie-notification__agree').addEventListener('click', this.onAcceptCookies);
    }

    if (document.querySelector('.cookie-notification__message .statement_expand') !== null) {
        document.querySelector('.cookie-notification__message .statement_expand').addEventListener('click', this.showStatement);
    }

    if (document.querySelector('.cookie-notification__close') !== null) {
        document.querySelector('.cookie-notification__close').addEventListener('click', this.hideStatement);
    }
};

/**
 * Update localStorage and hide notification if cookies are accepted
 */
CookieNotification.prototype.onAcceptCookies = function (event) {
    event.preventDefault();
    localStorage.setItem(this.COOKIE_ACCEPTED_NAME, '1');
    this.hide()
};

/**
 * Show cookie notification
 */
CookieNotification.prototype.show = function () {
    if (document.querySelector('.cookie-notification') !== null) {
        document.querySelector('.cookie-notification').classList.add('js--active');
    }
};

/**
 * Hide cookie notification
 */
CookieNotification.prototype.hide = function () {
    if (document.querySelector('.cookie-notification') !== null) {
        document.querySelector('.cookie-notification').classList.remove('js--active');
    }
};

/**
 * Show the statement
 */
CookieNotification.prototype.showStatement = function (e) {
    e.preventDefault();
    if (document.querySelector('.cookie-notification__statement') !== null) {
        document.querySelector('.cookie-notification__statement').classList.add('active');
    }
};

/**
 * Hide the statement
 */
CookieNotification.prototype.hideStatement = function (e) {
    e.preventDefault();
    if (document.querySelector('.cookie-notification__statement') !== null) {
        document.querySelector('.cookie-notification__statement').classList.remove('active');
    }
};

var cookieNotification = new CookieNotification();