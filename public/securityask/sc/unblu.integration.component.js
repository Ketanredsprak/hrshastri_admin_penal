(function(e, a) { for(var i in a) e[i] = a[i]; if(a.__esModule) Object.defineProperty(e, "__esModule", { value: true }); }(window,
/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 408:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {


var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.unbluIntegrationComponent = void 0;
var index_1 = __webpack_require__(274);
var conversation_type_1 = __webpack_require__(350);
var defaultApiKey = "MZsy5sFESYqU7MawXZgR_w";
var installationProvidedApiKey = "@@unblu.api.key@@";
var defaultUnbluEntryPath = undefined;
var installationProvidedUnbluEntryPath = "/ap/ga/ub";
var defaultUnbluServerUrl = undefined;
var installationProvidedUnbluServerUrl = "@@unblu.server.url@@";
var defaultBaseScriptName = "unblu.integration.component";
var installationProvidedBaseScriptName = "@@unblu.integration.component.basename@@";
var customScripts = ["/sc/unblu.interceptor.min.js"];
var videoExtensionScriptUrl = "/sc/unblu.video.ext.js";
var defaultVideoExtensionScriptUrl = "unblu.video.ext.js";
var defaultLogLevel = "";
var installationProvidedLogLevel = "@@unblu.uic.loglevel@@";
var conversationIsStarting = false;
var conversationStarting = null;
var log = function (message) {
    if (defaultLogLevel === "debug" || installationProvidedLogLevel === "debug" || window.unbluIntegrationComponentLogLevel || document.location.href.indexOf("debugUIC") !== -1) {
        console.log("UIC: " + message);
    }
};
/**
 * Check whether an Unblu session of any kind is currently active
 *
 * @returns {Promise<boolean>} `true`, if an Unblu session is active, `false` otherwise
 */
function isSessionActive() {
    return __awaiter(this, void 0, void 0, function () {
        var hasActiveConversation, checkForActiveSessionAfterPageLoad, isActive;
        var _this = this;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("isSessionActive called");
                    hasActiveConversation = function () { return __awaiter(_this, void 0, void 0, function () {
                        var _api, isActive;
                        return __generator(this, function (_a) {
                            switch (_a.label) {
                                case 0:
                                    configApi();
                                    return [4 /*yield*/, index_1.api.initialize()];
                                case 1:
                                    _api = _a.sent();
                                    return [4 /*yield*/, _api.getActiveConversation()];
                                case 2:
                                    isActive = ((_a.sent()) !== null);
                                    return [2 /*return*/, isActive];
                            }
                        });
                    }); };
                    checkForActiveSessionAfterPageLoad = function () { return __awaiter(_this, void 0, void 0, function () {
                        return __generator(this, function (_a) {
                            return [2 /*return*/, new Promise((function (resolve, reject) {
                                    if (document.readyState === "complete") {
                                        if (index_1.api.getApiState() === "INITIAL") {
                                            resolve(false);
                                            return;
                                        }
                                        resolve(hasActiveConversation());
                                    }
                                    else {
                                        window.addEventListener("load", function (event) {
                                            if (index_1.api.getApiState() === "INITIAL") {
                                                resolve(false);
                                                return;
                                            }
                                            resolve(hasActiveConversation());
                                        });
                                    }
                                }))];
                        });
                    }); };
                    return [4 /*yield*/, checkForActiveSessionAfterPageLoad()];
                case 1:
                    isActive = _a.sent();
                    log("isSessionActive result: " + isActive);
                    return [2 /*return*/, isActive];
            }
        });
    });
}
/**
 * Open the Unblu individual UI (which will show different content depending on the visitors situation)
 * Usually used after `isSessionActive()` calls with result `true` in order to just show
 * the individual UI (again) instead of starting a new chat or co-browsing session
 */
function openIndividualUi() {
    return __awaiter(this, void 0, void 0, function () {
        var _api;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("openIndividualUi called");
                    configApi();
                    return [4 /*yield*/, index_1.api.initialize()];
                case 1:
                    _api = _a.sent();
                    return [4 /*yield*/, _api.ui.openIndividualUi()];
                case 2:
                    _a.sent();
                    return [2 /*return*/];
            }
        });
    });
}
/**
 * Checks for the given visitor location in the browser (e.g. on video landing page) whether
 * an agent would be available when the visitor would start a chat at the moment of the check.
 */
function isAgentAvailable() {
    return __awaiter(this, void 0, void 0, function () {
        var _api, isAgentAvailable;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("isAgentAvailable called");
                    configApi();
                    return [4 /*yield*/, index_1.api.initialize()];
                case 1:
                    _api = _a.sent();
                    return [4 /*yield*/, _api.isAgentAvailable()];
                case 2:
                    isAgentAvailable = _a.sent();
                    log("isAgentAvailable result: " + isAgentAvailable);
                    return [2 /*return*/, isAgentAvailable];
            }
        });
    });
}
/**
 * Start a session with a given PIN (e.g. Live Support / Co-Browsing)
 *
 * @param pin The PIN code the visitor received from an agent to share the browser window with
 * @returns {Promise<string>} session ID (Unblu internally called "conversation id")
 */
function startWithPin(pin) {
    return __awaiter(this, void 0, void 0, function () {
        var conversationId, _api, conversation;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("startWithPin");
                    if (conversationIsStarting) {
                        log("startWithPin: aborted as another conversation is already starting");
                        return [2 /*return*/, Promise.reject("Another conversation is already starting")];
                    }
                    conversationIsStarting = true;
                    conversationId = null;
                    _a.label = 1;
                case 1:
                    _a.trys.push([1, , 7, 8]);
                    configApi();
                    return [4 /*yield*/, index_1.api.initialize()];
                case 2:
                    _api = _a.sent();
                    return [4 /*yield*/, _api.getActiveConversation()];
                case 3:
                    conversation = _a.sent();
                    if (!(conversation === null)) return [3 /*break*/, 5];
                    return [4 /*yield*/, _api.joinConversation(pin)];
                case 4:
                    conversation = _a.sent();
                    log("startWithPin result: " + conversation.getConversationId());
                    return [3 /*break*/, 6];
                case 5:
                    log("startWithPin result: No action (session already active)");
                    _a.label = 6;
                case 6:
                    conversationId = conversation.getConversationId();
                    return [3 /*break*/, 8];
                case 7:
                    conversationIsStarting = false;
                    return [7 /*endfinally*/];
                case 8: return [2 /*return*/, conversationId];
            }
        });
    });
}
/**
 * Start a chat session
 *
 * @returns {Promise<string>} session ID (Unblu internally called "conversation id")
 */
function startChat() {
    return __awaiter(this, void 0, void 0, function () {
        var conversationId, _api, conversation, availabilitystate;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("startChat called");
                    if (conversationIsStarting) {
                        log("startChat: aborted as another conversation is already starting");
                        return [2 /*return*/, Promise.reject("Another conversation is already starting")];
                    }
                    conversationIsStarting = true;
                    conversationId = null;
                    _a.label = 1;
                case 1:
                    _a.trys.push([1, , 12, 13]);
                    configApi();
                    return [4 /*yield*/, index_1.api.initialize()];
                case 2:
                    _api = _a.sent();
                    return [4 /*yield*/, _api.getActiveConversation()];
                case 3:
                    conversation = _a.sent();
                    if (!(conversation === null)) return [3 /*break*/, 9];
                    return [4 /*yield*/, _api.isAgentAvailable()];
                case 4:
                    availabilitystate = _a.sent();
                    if (!availabilitystate) return [3 /*break*/, 6];
                    return [4 /*yield*/, _api.startConversation(conversation_type_1.ConversationType.CHAT_REQUEST)];
                case 5:
                    conversation = _a.sent();
                    log("startOnlineChat result: " + conversation.getConversationId());
                    return [3 /*break*/, 8];
                case 6: return [4 /*yield*/, _api.startConversation(conversation_type_1.ConversationType.OFFLINE_CHAT_REQUEST)];
                case 7:
                    conversation = _a.sent();
                    log("startOfflineChat result: " + conversation.getConversationId());
                    _a.label = 8;
                case 8: return [3 /*break*/, 10];
                case 9:
                    log("startChat result: no action (session already active)");
                    _a.label = 10;
                case 10: return [4 /*yield*/, _api.ui.openIndividualUi()];
                case 11:
                    _a.sent();
                    conversationId = conversation.getConversationId();
                    return [3 /*break*/, 13];
                case 12:
                    conversationIsStarting = false;
                    return [7 /*endfinally*/];
                case 13: return [2 /*return*/, conversationId];
            }
        });
    });
}
/**
 * Start a video session
 *
 * @returns {Promise<string>} session ID (Unblu internally called "conversation id")
 */
function startVideo(showMyself) {
    return __awaiter(this, void 0, void 0, function () {
        var conversationId, _api, conversation;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("startVideo called");
                    if (conversationIsStarting) {
                        log("startVideo: aborted as another conversation is already starting");
                        return [2 /*return*/, Promise.reject("Another conversation is already starting")];
                    }
                    conversationIsStarting = true;
                    conversationId = null;
                    _a.label = 1;
                case 1:
                    _a.trys.push([1, , 8, 9]);
                    configApi();
                    return [4 /*yield*/, index_1.api.initialize()];
                case 2:
                    _api = _a.sent();
                    return [4 /*yield*/, _api.getActiveConversation()];
                case 3:
                    conversation = _a.sent();
                    if (!(conversation === null)) return [3 /*break*/, 5];
                    return [4 /*yield*/, _api.startConversation(conversation_type_1.ConversationType.VIDEO_REQUEST)];
                case 4:
                    conversation = _a.sent();
                    log("startVideo result: " + conversation.getConversationId());
                    return [3 /*break*/, 6];
                case 5:
                    log("startVideo result: no action (session already active)");
                    _a.label = 6;
                case 6: return [4 /*yield*/, _api.ui.openIndividualUi()];
                case 7:
                    _a.sent();
                    conversationId = conversation.getConversationId();
                    return [3 /*break*/, 9];
                case 8:
                    conversationIsStarting = false;
                    return [7 /*endfinally*/];
                case 9: return [2 /*return*/, conversationId];
            }
        });
    });
}
/**
 * Check for video capabilities (Camera, Microphone, Loudspeaker etc) of the device the browser runs on
 *
 * @returns {Promise<Result>} result of the check
 */
function checkVideo(qualityObserver, options) {
    return __awaiter(this, void 0, void 0, function () {
        var url, integrationScriptUrl;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("checkVideo called");
                    if (!!window.unbluVideoExt) return [3 /*break*/, 2];
                    url = void 0;
                    if (!videoExtensionScriptUrl.startsWith("@")) {
                        url = videoExtensionScriptUrl;
                    }
                    else {
                        integrationScriptUrl = getIntegrationScriptUrl();
                        if (integrationScriptUrl) {
                            if (integrationScriptUrl.indexOf("#") > -1) {
                                integrationScriptUrl = integrationScriptUrl.substring(0, integrationScriptUrl.indexOf("#"));
                            }
                            if (integrationScriptUrl.indexOf("?") > -1) {
                                integrationScriptUrl = integrationScriptUrl.substring(0, integrationScriptUrl.indexOf("?"));
                            }
                            if (integrationScriptUrl.indexOf("/") > -1) {
                                integrationScriptUrl = integrationScriptUrl.substring(0, integrationScriptUrl.lastIndexOf("/") + 1);
                            }
                            else {
                                integrationScriptUrl = "";
                            }
                            url = integrationScriptUrl + defaultVideoExtensionScriptUrl;
                        }
                        else {
                            url = defaultVideoExtensionScriptUrl;
                        }
                    }
                    return [4 /*yield*/, loadScript(url)];
                case 1:
                    _a.sent();
                    _a.label = 2;
                case 2: return [2 /*return*/, window.unbluVideoExt.checkVideoFunctionality(qualityObserver, options)];
            }
        });
    });
}
/**
 * Setup a "new conversation" event interceptor
 * This method is "lazy" in the sense that it checks the Unblu API whether it's already initialized. If it is,
 * the interceptor is forwarded straight away. If it's not, it is buffered until the API initializes.
 *
 * @returns {Promise<void>} resolves (without result), once the interceptor has been set.
 */
function setNewConversationInterceptor(interceptor) {
    return __awaiter(this, void 0, void 0, function () {
        var _api;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("setNewConversationInterceptor called");
                    configApi();
                    return [4 /*yield*/, index_1.api.initialize()];
                case 1:
                    _api = _a.sent();
                    return [2 /*return*/, _api.setNewConversationInterceptor(interceptor)];
            }
        });
    });
}
/**
 * Load an external javascript and return promise. Resolves when javascript is loaded, rejects when an error occurred.
 *
 * @param scriptUrl URL of the javascript to load (must work in src attribute in context of this script)
 */
function loadScript(scriptUrl) {
    return __awaiter(this, void 0, void 0, function () {
        return __generator(this, function (_a) {
            log("loadScript called for " + scriptUrl);
            return [2 /*return*/, new Promise(function (resolve, reject) {
                    var s = document.createElement("script");
                    s.setAttribute("src", scriptUrl);
                    s.setAttribute("defer", "defer");
                    s.onload = function (event) { return resolve(); };
                    s.onerror = function (event) { return reject(event.message); };
                    document.head.appendChild(s);
                })];
        });
    });
}
/**
 * Load all scripts listed in the customScript array not starting with @ (@ is the placeholder marker for
 * the Postfinance deployment job)
 */
function loadCustomScripts() {
    return __awaiter(this, void 0, void 0, function () {
        var loadingScripts;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("loadCustomScripts called");
                    loadingScripts = [];
                    customScripts.forEach(function (customScript) {
                        if (!customScript.startsWith("@")) {
                            loadingScripts.push(loadScript(customScript));
                        }
                    });
                    return [4 /*yield*/, Promise.all(loadingScripts)];
                case 1:
                    _a.sent();
                    return [2 /*return*/];
            }
        });
    });
}
/**
 * Check if the current browser is smaller than a mobile device would be (i.e. it _is_ a mobile device or the browser window is very small)
 * If it is, in the event of an active conversation, make sure the Unblu individual UI is collapsed by default.
 */
function customMobileHandling() {
    return __awaiter(this, void 0, void 0, function () {
        var _api;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    log("customMobileHandling called");
                    if (!window.matchMedia("screen and (max-width:544px)").matches) return [3 /*break*/, 4];
                    return [4 /*yield*/, isSessionActive()];
                case 1:
                    if (!_a.sent()) return [3 /*break*/, 4];
                    log("customMobileHandling detected active conversation and mobile device: collapsing UI");
                    configApi();
                    return [4 /*yield*/, index_1.api.initialize()];
                case 2:
                    _api = _a.sent();
                    return [4 /*yield*/, _api.ui.collapseIndividualUi()];
                case 3:
                    _a.sent();
                    _a.label = 4;
                case 4: return [2 /*return*/];
            }
        });
    });
}
function getIntegrationScriptBaseName() {
    var integrationScriptBaseName = defaultBaseScriptName;
    if (!installationProvidedBaseScriptName.startsWith("@")) {
        integrationScriptBaseName = installationProvidedBaseScriptName;
        log("Using integrationScriptBaseName: " + integrationScriptBaseName);
    }
    return integrationScriptBaseName;
}
function getIntegrationScriptUrl() {
    var url = null;
    var ourScript = document.querySelector("script[src*='" + getIntegrationScriptBaseName() + "']");
    if (ourScript && ourScript.getAttribute("src")) {
        url = ourScript.getAttribute("src");
    }
    return url;
}
/**
 * Configure the Unblu JS API and in particular manage the api key by using a number of fallbacks:
 * * Search script tag with unblu-api-key as part of the src attribute, extract api key from there and use it. If not present:
 * * Check installationProvidedApiKey attribute, whether it contains a replaced (from deployment) value. If not present:
 * * Use the defaultApiKey
 */
function configApi() {
    if (!index_1.api.isConfigurationNeeded()) {
        return;
    }
    var integrationScriptBaseName = getIntegrationScriptBaseName();
    log("Using integrationScriptBaseName: " + integrationScriptBaseName);
    // last fallback
    var apiKey = defaultApiKey;
    var unbluEntryPath = defaultUnbluEntryPath;
    var unbluServerUrl = defaultUnbluServerUrl;
    // override fallback with api key provided - if provided
    if (!installationProvidedApiKey.startsWith("@")) {
        apiKey = installationProvidedApiKey;
        log("Overriding apiKey with installation param: " + apiKey);
    }
    if (!installationProvidedUnbluEntryPath.startsWith("@")) {
        unbluEntryPath = installationProvidedUnbluEntryPath;
        log("Overriding unbluEntryPath with installation param: " + unbluEntryPath);
    }
    if (!installationProvidedUnbluServerUrl.startsWith("@")) {
        unbluServerUrl = installationProvidedUnbluServerUrl;
        log("Overriding unbluServerUrl with installation param: " + unbluServerUrl);
    }
    // override with script request hash - if provided
    var url = getIntegrationScriptUrl();
    if (url) {
        var match = url.match(/x-unblu-apikey=([^&]+)/);
        if (match && match.length === 2 && match[1]) {
            apiKey = decodeURIComponent(match[1]);
            log("Overriding apiKey with script param: " + apiKey);
        }
        match = url.match(/x-unblu-entrypath=([^&]+)/);
        if (match && match.length === 2 && match[1]) {
            unbluEntryPath = decodeURIComponent(match[1]);
            log("Overriding unbluEntryPath with script param: " + unbluEntryPath);
        }
        match = url.match(/x-unblu-serverurl=([^&]+)/);
        if (match && match.length === 2 && match[1]) {
            unbluServerUrl = decodeURIComponent(match[1]);
            log("Overriding unbluServerUrl with script param: " + unbluServerUrl);
        }
    }
    log("Configuring Unblu API with apiKey: " + apiKey + ", entryPath: " + unbluEntryPath + ", serverUrl: " + unbluServerUrl);
    var config = {
        apiKey: apiKey
    };
    if (unbluEntryPath) {
        config.entryPath = unbluEntryPath;
    }
    if (unbluServerUrl) {
        config.serverUrl = unbluServerUrl;
    }
    index_1.api.configure(config);
}
function fireUnbluIntegrationComponentReadyEvent() {
    log("fireUnbluIntegrationComponentReadyEvent called");
    var event = new Event("unbluIntegrationComponentReady");
    window.dispatchEvent(event);
}
exports.unbluIntegrationComponent = {
    isSessionActive: isSessionActive,
    openIndividualUi: openIndividualUi,
    isAgentAvailable: isAgentAvailable,
    startChat: startChat,
    startWithPin: startWithPin,
    checkVideo: checkVideo,
    startVideo: startVideo,
    setNewConversationInterceptor: setNewConversationInterceptor
};
/**
 * Special function trying to handle a shortcoming that after logout, Unblu keeps some state
 * in the window.name
 */
function hackForLogout() {
    if (/[?&]logout/.test(location.href)) {
        // WORKAROUND: reset window.name to make sure Unblu starts properly - see UNBLUDESK-4265 for details
        window.name = "";
    }
}
// init when this script is loaded
(function () {
    return __awaiter(this, void 0, void 0, function () {
        var error_1;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    _a.trys.push([0, 4, , 5]);
                    return [4 /*yield*/, loadCustomScripts()];
                case 1:
                    _a.sent();
                    return [4 /*yield*/, customMobileHandling()];
                case 2:
                    _a.sent();
                    hackForLogout();
                    // always initialize the api
                    configApi();
                    return [4 /*yield*/, index_1.api.initialize()];
                case 3:
                    _a.sent();
                    fireUnbluIntegrationComponentReadyEvent();
                    return [3 /*break*/, 5];
                case 4:
                    error_1 = _a.sent();
                    console.error("Error during init of Unblu Integration Component", error_1);
                    return [3 /*break*/, 5];
                case 5: return [2 /*return*/];
            }
        });
    });
})();


/***/ }),

/***/ 274:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "Conversation": function() { return /* reexport */ Conversation; },
  "UnbluApi": function() { return /* reexport */ UnbluApi; },
  "api": function() { return /* binding */ api; }
});

// CONCATENATED MODULE: ./src/lib-esm/unblu-api-error.js
var __extends = (undefined && undefined.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
/**
 * Type of an unblu error. This can be used to check what kind of error occurred.
 */
var UnbluErrorType;
(function (UnbluErrorType) {
    /**
     * Thrown if the browser is not supported by unblu.
     */
    UnbluErrorType["UNSUPPORTED_BROWSER"] = "UNSUPPORTED_BROWSER";
    /**
     * Thrown if the initialization of the unblu API failed due to a timeout.
     */
    UnbluErrorType["INITIALIZATION_TIMEOUT"] = "INITIALIZATION_TIMEOUT";
    /**
     * Thrown if the initialization is called with no existing snippet and no configuration.
     */
    UnbluErrorType["CONFIGURATION_MISSING"] = "CONFIGURATION_MISSING";
    /**
     * Thrown during initialization if the snippet can't be loaded or unblu can't be initialized from the snippet.
     */
    UnbluErrorType["ERROR_LOADING_UNBLU"] = "ERROR_LOADING_UNBLU";
    /**
     * Thrown if the unblu JS API is not compatible with the unblu collaboration server.
     */
    UnbluErrorType["INCOMPATIBLE_UNBLU_VERSION"] = "INCOMPATIBLE_UNBLU_VERSION";
    /**
     * Thrown if a function call was invalid.
     * This is usually do to an incompatibility between the unblu JS API and the unblu collaboration server.
     */
    UnbluErrorType["INVALID_FUNCTION_CALL"] = "INVALID_FUNCTION_CALL";
    /**
     * Thrown if the arguments passed to a function where invalid.
     */
    UnbluErrorType["INVALID_FUNCTION_ARGUMENTS"] = "INVALID_FUNCTION_ARGUMENTS";
    /**
     * Thrown if a called action is not permitted for the local person.
     * The details message usually has more information about the required permissions.
     */
    UnbluErrorType["ACTION_NOT_GRANTED"] = "ACTION_NOT_GRANTED";
    /**
     * Thrown if an unexpected exception occurrs during a function execution.
     */
    UnbluErrorType["EXECUTION_EXCEPTION"] = "EXECUTION_EXCEPTION";
    /**
     * Thrown if a method is called in an invalid context. E.g. if the Object called upon was already destroyed.
     */
    UnbluErrorType["ILLEGAL_STATE"] = "ILLEGAL_STATE";
})(UnbluErrorType || (UnbluErrorType = {}));
/**
 * General unblu JS API error class that will be thrown whenever something goes wrong.
 *
 * - Use the [[UnbluApiError.type]] to check what kind of error occurred.
 * - Use the [[UnbluApiError.detail]] for human readable details.
 *
 * Check the documentation of [[UnbluErrorType]] for more details on the different error types.
 *
 * Example:
 * ```ts
 * unblu.api.initialize().then(api => {
 *      // use the api
 * }).catch(e => {
 *     if(e.type === 'INITIALIZATION_TIMEOUT') {
 *          //retry
 *     } else if(e.type === 'UNSUPPORTED_BROWSER') {
 *          // display unsupported browser dialog
 *     } else {
 *          // show generic error message
 *     }
 * });
 * ```
 *
 * or using async / await:
 *
 * ```ts
 * try {
 *     const api = await unblu.api.initialize();
 *     // use the api
 * } catch(e) {
 *     if(e.type === 'INITIALIZATION_TIMEOUT') {
 *          //retry
 *     } else if(e.type === 'UNSUPPORTED_BROWSER') {
 *          // display unsupported browser dialog
 *     } else {
 *          // show generic error message
 *     }
 * }
 * ```
 *
 *
 * The error types may either be checked via their constant string values or via the UnbluErrorType enum:
 *
 * ```ts
 * // using string constant
 * function isTimeout(e: UnbluApiError) {
 *  return e.type === 'INITIALIZATION_TIMEOUT';
 * }
 * ```
 * ```ts
 * // using the enum
 * function isTimeout(e: UnbluApiError) {
 *  return e.type === window.unblu.UnbluErrorType.INITIALIZATION_TIMEOUT;
 * }
 * ```
 *
 */
var UnbluApiError = /** @class */ (function (_super) {
    __extends(UnbluApiError, _super);
    function UnbluApiError(type, detail) {
        var _this = _super.call(this, "type: " + type + ", detail: " + detail) || this;
        _this.type = type;
        _this.detail = detail;
        _this.name = 'UnbluApiError';
        return _this;
    }
    return UnbluApiError;
}(Error));

//# sourceMappingURL=unblu-api-error.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/util/event-emitter.js
var EventEmitter = /** @class */ (function () {
    function EventEmitter() {
        this.listeners = {};
    }
    /**
     * Resets the emitter by removing all registered listeners.
     */
    EventEmitter.prototype.reset = function () {
        this.listeners = {};
    };
    /**
     * Adds an event listeners
     * @param event the event to listen to
     * @param listener the listener to be called.
     */
    EventEmitter.prototype.on = function (event, listener) {
        this.listeners[event] = this.listeners[event] || [];
        if (this.listeners[event].indexOf(listener) === -1)
            this.listeners[event].push(listener);
    };
    /**
     * removes a previously registered listener
     * @param event the event
     * @param listener the listener that should be removed.
     * @return `true` if the listener was removed, `false` otherwise.
     */
    EventEmitter.prototype.off = function (event, listener) {
        var listeners = this.listeners[event] || [];
        var index = listeners.indexOf(listener);
        if (index > -1) {
            listeners.splice(index, 1);
            return true;
        }
        return false;
    };
    /**
     * removes all listeners for the given event.
     * @param event the event for which all listeners will be removed.
     */
    EventEmitter.prototype.offAll = function (event) {
        delete this.listeners[event];
    };
    /**
     * Checks weather at least one listener exists for a given event.
     * @param event the event to check for
     * @return weather or not any listeners for the given event are registered.
     */
    EventEmitter.prototype.hasListeners = function (event) {
        return this.listeners[event] != null && this.listeners[event].length > 0;
    };
    /**
     * Returns all events that have at least one listeners registered to them.
     * @return An array containing all events that have at least one listener.
     * If no listeners are registered at all, an empty array will be returned.
     */
    EventEmitter.prototype.getEventsWithListeners = function () {
        var events = [];
        for (var event_1 in this.listeners) {
            if (this.listeners[event_1].length)
                events.push(event_1);
        }
        return events;
    };
    /**
     * Emits an event dispatching it to all listeners registered for it.
     * @param event the event name.
     * @param data the event data.
     */
    EventEmitter.prototype.emit = function (event, data) {
        var listeners = this.listeners[event];
        if (listeners) {
            listeners.forEach(function (l) {
                try {
                    l(data);
                }
                catch (e) {
                    console.warn('Error dispatching event:', event, 'in listener:', l, e);
                }
            });
        }
    };
    return EventEmitter;
}());

//# sourceMappingURL=event-emitter.js.map
// CONCATENATED MODULE: ./src/lib-esm/conversation.js
var __awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (undefined && undefined.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};


/**
 * #### This class gives API access to the currently active conversation.
 *
 * As long as a conversation is active one can register and receive the events provided by this class and call the methods.
 * Once the conversation is closed this API object will be destroyed and no more event callbacks will be called.
 * Any subsequent calls will fail.
 *
 * Use the [[CLOSE]] event to de-init any code connected to this conversation.
 */
var Conversation = /** @class */ (function () {
    /**
     * @hidden
     */
    function Conversation(internalApi, conversationId) {
        var _this = this;
        this.internalApi = internalApi;
        this.conversationId = conversationId;
        this.eventEmitter = new EventEmitter();
        this.internalListeners = {};
        this.destroyed = false;
        // clean up all listeners when the conversation disconnects.
        this.on(Conversation.CLOSE, function () { return _this.destroy(); });
    }
    /**
     * Adds a listener.
     * @param event The event to register.
     * @param listener The listener to register.
     */
    Conversation.prototype.on = function (event, listener) {
        this.checkNotDestroyed();
        var needsInternalSubscription = !this.eventEmitter.hasListeners(event);
        this.eventEmitter.on(event, listener);
        if (needsInternalSubscription)
            this.onInternal(event);
    };
    /**
     * Removes a previously registered listener.
     * @param event The event to unregister from.
     * @param listener The listener to remove.
     */
    Conversation.prototype.off = function (event, listener) {
        this.checkNotDestroyed();
        var removed = this.eventEmitter.off(event, listener);
        if (!this.eventEmitter.hasListeners(event))
            this.offInternal(event);
        return removed;
    };
    Conversation.prototype.onInternal = function (eventName) {
        var _this = this;
        var internalListener;
        switch (eventName) {
            case Conversation.CONNECTION_STATE_CHANGE:
                internalListener = function (event) {
                    if (event.data.conversationId == _this.conversationId)
                        _this.eventEmitter.emit(event.name, event.data.connectionState);
                };
                break;
            case Conversation.CONVERSATION_STATE_CHANGE:
                internalListener = function (event) {
                    if (event.data.conversationId == _this.conversationId)
                        _this.eventEmitter.emit(event.name, event.data.conversationState);
                };
                break;
            case Conversation.CALL_STATE_CHANGE:
                internalListener = function (event) {
                    if (event.data.conversationId == _this.conversationId)
                        _this.eventEmitter.emit(event.name, event.data.callState);
                };
                break;
            case Conversation.INVITATIONS_CHANGE:
                internalListener = function (event) {
                    if (event.data.conversationId == _this.conversationId)
                        _this.eventEmitter.emit(event.name, event.data.invitations);
                };
                break;
            default:
                internalListener = function (event) {
                    if (event.data == _this.conversationId) {
                        _this.eventEmitter.emit(event.name);
                    }
                };
                break;
        }
        this.internalListeners[eventName] = internalListener;
        this.internalApi.conversation.on(eventName, internalListener).catch(function (e) { return console.warn('Error registering internal listener for event:', eventName, 'error:' + e, e); });
    };
    Conversation.prototype.offInternal = function (eventName) {
        var listener = this.internalListeners[eventName];
        if (listener == null)
            return;
        delete this.internalListeners[eventName];
        this.internalApi.conversation.off(eventName, listener).catch(function (e) { return console.warn('Error removing internal listener for event:', eventName, 'error:' + e, e); });
    };
    /**
     * Returns the ID of this conversation.
     */
    Conversation.prototype.getConversationId = function () {
        return this.conversationId;
    };
    /**
     * Returns the current connection state the conversation is in.
     *
     * If the connection is lost, the conversation will automatically try to reconnect using an exponential back-off strategy.
     * If a fatal error is detected, the state will change to [[ConnectionState.ERROR]].
     *
     * If this happens, the conversation is in it's terminal state. A dialog or other UI will be displayed to the user with details on the failure.
     * The conversation is not automatically closed in this case.
     * It may either be closed through a manual action by the visitor (confirming the error) or via the API.
     *
     * @see [[CONNECTION_STATE_CHANGE]] If you need to listen to changes.
     * @return A promise that resolves to the current connection state of the conversation
     * or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.getConnectionState = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.getConnectionState(this.conversationId)];
            });
        });
    };
    /**
     * Returns the current state the conversation is in.
     * @return A promise that resolves to the current state of the conversation
     * or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.getConversationState = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.getConversationState(this.conversationId)];
            });
        });
    };
    /**
     * @see [[CALL_STATE_CHANGE]] If you need to listen to changes.
     * @return A promise that resolves to the current call state of the local user
     * or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.getCallState = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.getCallState(this.conversationId)];
            });
        });
    };
    /**
     * Creates a new PIN invitation for this conversation.
     *
     * - If the local person doesn't have the right to invite a visitor,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.ACTION_NOT_GRANTED]].
     * @see [[INVITATIONS_CHANGE]] If you need to listen to changes for new invitations on this conversation.
     * @return A promise that resolves to a new [[Invitation]] object with all relevant metadata.
     * or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.createAnonymousPinInvitation = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.createAnonymousPinInvitation(this.conversationId)];
            });
        });
    };
    /**
     * Creates a new EMail invitation for this conversation.
     *
     * - If the local person doesn't have the right to invite a visitor,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.ACTION_NOT_GRANTED]].
     * - If a non valid email address is provided,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.INVALID_FUNCTION_ARGUMENTS]].
     * @param email The email which the invitation should be send. It must be a valid email address.
     * @see [[INVITATIONS_CHANGE]] If you need to listen to changes for new invitations on this conversation.
     * @return A promise that resolves to a new [[Invitation]] object with all relevant metadata.
     * or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.createAnonymousEmailInvitation = function (email) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.createAnonymousEmailInvitation(this.conversationId, email)];
            });
        });
    };
    /**
     * Revoke an invitation.
     *
     * - If the local person doesn't have the right to invite a visitor,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.ACTION_NOT_GRANTED]].
     * @param invitationId The invitation id.
     * @return A Promise that resolves to null or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.revokeInvitation = function (invitationId) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.revokeInvitation(this.conversationId, invitationId)];
            });
        });
    };
    /**
     * Renews an invitation PIN if the invitation is expired.
     *
     * - If the local person doesn't have the right to invite a visitor,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.ACTION_NOT_GRANTED]].
     * @param invitationId The invitation id.
     * @return A promise that resolves to a new [[Invitation]] object with all relevant metadata.
     */
    Conversation.prototype.renewInvitationPin = function (invitationId) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.renewInvitationPin(this.conversationId, invitationId)];
            });
        });
    };
    /**
     * Get all visitor invitations created by the local person for this conversation.
     * @return A promise that resolves to a new [[Invitation]] array with all relevant metadata.
     * or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.getInvitations = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.getInvitations(this.conversationId)];
            });
        });
    };
    /**
     * Starts a voice call in this conversation.
     *
     * - If a call is already active, this call will be ignored.
     * - If the local person doesn't have the right to start a voice call,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.ACTION_NOT_GRANTED]].
     * @see [[CALL_STATE_CHANGE]] If you need to listen to changes.
     * @return A Promise that resolves to null or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.startAudioCall = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.startAudioCall(this.conversationId)];
            });
        });
    };
    /**
     * Starts a video call in this conversation.
     *
     * - If a call is already active, this call will be ignored.
     * - If the local person doesn't have the right to start a video call,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.ACTION_NOT_GRANTED]].
     * @see [[CALL_STATE_CHANGE]] If you need to listen to changes.
     * @return A Promise that resolves to null or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.startVideoCall = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.startVideoCall(this.conversationId)];
            });
        });
    };
    /**
     * Ends and closes this conversation.
     *
     * If the local person doesn't have the right to end the conversation,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.ACTION_NOT_GRANTED]].
     * @see [[END]] fired after this call.
     * @see [[closeConversation]] for details on closing a conversation.
     * @return A Promise that resolves to null or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.endConversation = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.endConversation(this.conversationId)];
            });
        });
    };
    /**
     * Leaves and closes this conversation.
     *
     * By leaving, the visitor is removed from the active participant list of the conversation.
     * Once a conversation is left, the visitor can not re-open it. It will not be visible in the conversation history either.
     *
     * If the local person doesn't have the right to leave the conversation,
     * the returned promise will be rejected with the unblu error type [[UnbluErrorType.ACTION_NOT_GRANTED]].
     * @see [[CLOSE]] fired after this call.
     * @see [[closeConversation]] for details on closing a conversation without leaving.
     * @return A Promise that resolves to null or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.leaveConversation = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.leaveConversation(this.conversationId)];
            });
        });
    };
    /**
     * Closes this conversation locally.
     *
     * When called, the connection to this conversation is closed and the overview is displayed.
     *
     * **Note that:**
     * - Closing does NOT end the conversation.
     * - The person does NOT leave the conversation.
     * - All Conversation api instances for this conversation will be destroyed.
     *
     * The conversation can be joined again either via the UI or using [[UnbluApi.openConversation]].
     * @see [[CLOSE]] fired after this call.
     * @see [[endConversation]] for details on ending a conversation.
     * @see [[leavingConversation]] for details on leaving a conversation.
     * @see [[destroy]] for details on destroying a conversation.
     * @return A Promise that resolves to null or is rejected with a [[UnbluApiError]] if the call fails.
     */
    Conversation.prototype.closeConversation = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                this.checkNotDestroyed();
                return [2 /*return*/, this.internalApi.conversation.closeConversation(this.conversationId)];
            });
        });
    };
    Conversation.prototype.checkNotDestroyed = function () {
        if (this.destroyed)
            throw new UnbluApiError(UnbluErrorType.ILLEGAL_STATE, 'Error: trying to execute method on destroyed conversation object.');
    };
    /**
     * Returns weather this conversation is destroyed or not.
     *
     * Conversations are either destroyed if [[destroy]] is called or the conversation is closed.
     * This usually happens when the user navigates back to an overview or into an other conversation.
     * @see [[destroy]]
     * @see [[CLOSE]]
     * @return Weather this conversation is destroyed or not.
     */
    Conversation.prototype.isDestroyed = function () {
        return this.destroyed;
    };
    /**
     * Destroys this conversation API instance.
     *
     * Calling destroy will unregister all event listeners and prohibit any further calls to this object.
     * Once the conversation is destroyed, any subsequent calls will reject the returned promise with [[UnbluErrorType.ILLEGAL_STATE]] as reason.
     *
     * **Note that:**
     * - Destroying does NOT close the conversation .
     * - Destroying does NOT end the conversation.
     * - Destroying does NOT leave the conversation.
     * - Other instances of the same Conversation will NOT be destroyed.
     *
     * This call simply destroys this local API instance to the conversation.
     *
     * A destroyed but still open conversation can be accessed again using [[UnbluApi.getActiveConversation]].
     *
     * @see [[isDestroyed]]
     * @see [[closeConversation]] for details on how to close a conversation
     * @see [[endConversation]] for details on how to end a conversation
     */
    Conversation.prototype.destroy = function () {
        if (this.destroyed)
            return;
        this.destroyed = true;
        this.eventEmitter.reset();
        for (var event_1 in this.internalListeners) {
            this.offInternal(event_1);
        }
    };
    /**
     * Event emitted when the [[ConnectionState]] of this conversation changes.
     * @event connectionStateChange
     * @see [[on]] for listener registration
     */
    Conversation.CONNECTION_STATE_CHANGE = 'connectionStateChange';
    /**
     * Event emitted when the [[ConversationState]] of this conversation changes.
     * @event conversationStateChange
     * @see [[on]] for listener registration
     */
    Conversation.CONVERSATION_STATE_CHANGE = 'conversationStateChange';
    /**
     * Event emitted when the [[CallState]] of this conversation changes.
     * @event callStateChange
     * @see [[on]] for listener registration
     */
    Conversation.CALL_STATE_CHANGE = 'callStateChange';
    /**
     * Event emitted when the conversation ends.
     * @deprecated The end event is not always available, depending on the configuration. Use instead [[CONVERSATION_STATE_CHANGE]] event.
     * @event end
     * @see [[on]] for listener registration
     */
    Conversation.END = 'end';
    /**
     * Event emitted when the conversation is closed.
     *
     * This may happen due to a UI-navigation or an API-call.
     *
     * @event close
     * @see [[on]] for listener registration
     */
    Conversation.CLOSE = 'close';
    /**
     * Event emitted when an [[Invitation]] is added to or removed from this conversation or if an existing one changes.
     * The event emits an array of all visitor invitations created by the local person, all other invitations will not be present.
     * @event invitationsChange
     * @see [[on]] for listener registration
     */
    Conversation.INVITATIONS_CHANGE = 'invitationsChange';
    return Conversation;
}());

//# sourceMappingURL=conversation.js.map
// CONCATENATED MODULE: ./src/lib-esm/unblu-api-ui.js
var unblu_api_ui_awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var unblu_api_ui_generator = (undefined && undefined.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};


/**
 * This class allows controlling of the UI state and the Unblu individual UI.
 */
var UnbluUiApi = /** @class */ (function () {
    /**
     * @hidden
     */
    function UnbluUiApi(internalApi) {
        var _this = this;
        this.internalApi = internalApi;
        this.internalListeners = {};
        this.eventEmitter = new EventEmitter();
        internalApi.meta.on('upgraded', function () { return _this.onUpgraded(); });
    }
    UnbluUiApi.prototype.on = function (event, listener) {
        var needsInternalSubscription = !this.eventEmitter.hasListeners(event);
        this.eventEmitter.on(event, listener);
        if (needsInternalSubscription)
            this.onInternal(event).catch(function (e) { return console.warn('Error registering internal listener for event:', event, 'error:' + e, e); });
    };
    /**
     * Removes a previously registered listener
     * @param event The event to unregister from.
     * @param listener The listener to remove.
     */
    UnbluUiApi.prototype.off = function (event, listener) {
        var removed = this.eventEmitter.off(event, listener);
        if (!this.eventEmitter.hasListeners(event))
            this.offInternal(event).catch(function (e) { return console.warn('Error removing internal listener for event:', event, 'error:' + e, e); });
        return removed;
    };
    UnbluUiApi.prototype.onInternal = function (eventName) {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            var internalListener, e_1;
            var _this = this;
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        switch (eventName) {
                            case UnbluUiApi.UI_STATE_CHANGE:
                                internalListener = function (event) {
                                    _this.eventEmitter.emit(event.name, event.data);
                                };
                                break;
                            default:
                                throw new UnbluApiError(UnbluErrorType.INVALID_FUNCTION_ARGUMENTS, 'Registration to unknown event:' + eventName);
                        }
                        return [4 /*yield*/, this.internalApi.meta.isUpgraded()];
                    case 1:
                        if (!_a.sent()) return [3 /*break*/, 5];
                        this.internalListeners[eventName] = internalListener;
                        _a.label = 2;
                    case 2:
                        _a.trys.push([2, 4, , 5]);
                        return [4 /*yield*/, this.internalApi.general.on(eventName, internalListener)];
                    case 3:
                        _a.sent();
                        return [3 /*break*/, 5];
                    case 4:
                        e_1 = _a.sent();
                        delete this.internalListeners[eventName];
                        throw e_1;
                    case 5: return [2 /*return*/];
                }
            });
        });
    };
    UnbluUiApi.prototype.offInternal = function (eventName) {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            var listener;
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        listener = this.internalListeners[eventName];
                        if (listener == null) {
                            return [2 /*return*/];
                        }
                        delete this.internalListeners[eventName];
                        return [4 /*yield*/, this.internalApi.general.off(eventName, listener)];
                    case 1:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Opens the individual UI if it is collapsed and collapses it if it is open.
     */
    UnbluUiApi.prototype.toggleIndividualUi = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.toggleIndividualUi()];
                    case 2:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Navigates the individual UI to the PIN entry UI.
     *
     * **NOTE:** calling this method will NOT automatically open the Unblu UI if it is collapsed. Use [[openIndividualUi]] if this is needed.
     */
    UnbluUiApi.prototype.openPinEntryUi = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.openPinEntryUi()];
                    case 2:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Pop-out the individual UI into a separate window.
     *
     * **NOTE:** this has to be called in a click-event in order to be able to open the pop-up window without being blocked by the browser!
     */
    UnbluUiApi.prototype.popoutIndividualUi = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.popoutIndividualUi()];
                    case 2:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Pop-in the individual UI when it is in [POPPED_OUT]{@link IndividualUiState.POPPED_OUT} state.
     *
     * The pop-out window will automatically close and the individual UI will be displayed in the original window again.
     */
    UnbluUiApi.prototype.popinIndividualUi = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.popinIndividualUi()];
                    case 2:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Maximize the individual UI - Does nothing if it is already maximized or popped out.
     */
    UnbluUiApi.prototype.maximizeIndividualUi = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.maximizeIndividualUi()];
                    case 2:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Minimize the individual UI - Does nothing if it is already minimized.
     */
    UnbluUiApi.prototype.minimizeIndividualUi = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.minimizeIndividualUi()];
                    case 2:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Opens the individual UI if it was collapsed. - Does nothing if it was already open.
     */
    UnbluUiApi.prototype.openIndividualUi = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.openIndividualUi()];
                    case 2:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Collapses the individual UI if it was open. - Does nothing if it was already collapsed.
     */
    UnbluUiApi.prototype.collapseIndividualUi = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.collapseIndividualUi()];
                    case 2:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Get the state of the individual UI.
     * @return A promise that resolves to the [[IndividualUiState]] of the individual UI.
     */
    UnbluUiApi.prototype.getIndividualUiState = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.getIndividualUiState()];
                    case 2: return [2 /*return*/, _a.sent()];
                }
            });
        });
    };
    UnbluUiApi.prototype.requireUpgrade = function () {
        return unblu_api_ui_awaiter(this, void 0, void 0, function () {
            return unblu_api_ui_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.internalApi.meta.upgrade(false)];
                    case 1:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    UnbluUiApi.prototype.onUpgraded = function () {
        for (var _i = 0, _a = this.eventEmitter.getEventsWithListeners(); _i < _a.length; _i++) {
            var event_1 = _a[_i];
            // register internal listeners for all events that need upgrade.
            if (!this.internalListeners[event_1])
                this.onInternal(event_1);
        }
    };
    /**
     * Event emitted every time the state of the individual UI is changed.
     *
     * @event uiStateChange
     * @see [[on]] for listener registration
     * @see [[UiStateChangeListener]]
     */
    UnbluUiApi.UI_STATE_CHANGE = 'uiStateChange';
    return UnbluUiApi;
}());

//# sourceMappingURL=unblu-api-ui.js.map
// CONCATENATED MODULE: ./src/lib-esm/unblu-api.js
var unblu_api_awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var unblu_api_generator = (undefined && undefined.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};




/**
 * #### This class represents the initialized Unblu Visitor JS API.
 *
 * There is only ever one instance of this api which can be retrieved via `unblu.api.initialize()`,
 * see [[UnbluStaticApi]] for more details on configuring and initializing the UnbluApi.
 *
 * The API connects to the integrated version of Unblu. All actions performed via the UnbluApi are executed in
 * the name of and with the rights of current visitor and may have direct effect on the displayed Unblu UI.
 *
 * For example if a conversation is started from the UnbluApi, the Unblu UI will navigate to it.
 * If a conversation is closed via the API, it will also be closed on the Unblu UI of the visitor.
 * For more information on UI side effects please check the documentation for each method call.
 *
 * For programmatic administrator access and configuration of Unblu please use the Unblu WebAPI.
 */
var UnbluApi = /** @class */ (function () {
    /**
     * @hidden
     */
    function UnbluApi(internalApi) {
        var _this = this;
        this.internalApi = internalApi;
        this.internalListeners = {};
        this.eventEmitter = new EventEmitter();
        internalApi.meta.on('upgraded', function () { return _this.onUpgraded(); });
        // All UI functionality is provided with the ui namespace
        this.ui = new UnbluUiApi(internalApi);
    }
    UnbluApi.prototype.on = function (event, listener) {
        var needsInternalSubscription = !this.eventEmitter.hasListeners(event);
        this.eventEmitter.on(event, listener);
        if (needsInternalSubscription)
            this.onInternal(event).catch(function (e) { return console.warn('Error registering internal listener for event:', event, 'error:' + e, e); });
    };
    /**
     * Removes a previously registered listener
     * @param event The event to unregister from.
     * @param listener The listener to remove.
     */
    UnbluApi.prototype.off = function (event, listener) {
        var removed = this.eventEmitter.off(event, listener);
        if (!this.eventEmitter.hasListeners(event))
            this.offInternal(event).catch(function (e) { return console.warn('Error removing internal listener for event:', event, 'error:' + e, e); });
        return removed;
    };
    UnbluApi.prototype.onInternal = function (eventName) {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            var internalListener, internalModule, needsUpgrade, _a, e_1;
            var _this = this;
            return unblu_api_generator(this, function (_b) {
                switch (_b.label) {
                    case 0:
                        switch (eventName) {
                            case UnbluApi.AGENT_AVAILABILITY_CHANGE:
                                internalListener = function (event) {
                                    _this.eventEmitter.emit(event.name, event.data);
                                };
                                internalModule = this.internalApi.agentAvailability;
                                needsUpgrade = false;
                                break;
                            case UnbluApi.ACTIVE_CONVERSATION_CHANGE:
                                internalListener = function (event) {
                                    _this.eventEmitter.emit(event.name, event.data ? new Conversation(_this.internalApi, event.data) : null);
                                };
                                internalModule = this.internalApi.general;
                                needsUpgrade = true;
                                break;
                            case UnbluApi.NOTIFICATION_COUNT_CHANGE:
                            case UnbluApi.PERSON_CHANGE:
                                internalListener = function (event) {
                                    _this.eventEmitter.emit(event.name, event.data);
                                };
                                internalModule = this.internalApi.general;
                                needsUpgrade = true;
                                break;
                            default:
                                throw new UnbluApiError(UnbluErrorType.INVALID_FUNCTION_ARGUMENTS, 'Registration to unknown event:' + eventName);
                        }
                        _a = !needsUpgrade;
                        if (_a) return [3 /*break*/, 2];
                        return [4 /*yield*/, this.internalApi.meta.isUpgraded()];
                    case 1:
                        _a = (_b.sent());
                        _b.label = 2;
                    case 2:
                        if (!_a) return [3 /*break*/, 6];
                        this.internalListeners[eventName] = internalListener;
                        _b.label = 3;
                    case 3:
                        _b.trys.push([3, 5, , 6]);
                        return [4 /*yield*/, internalModule.on(eventName, internalListener)];
                    case 4:
                        _b.sent();
                        return [3 /*break*/, 6];
                    case 5:
                        e_1 = _b.sent();
                        delete this.internalListeners[eventName];
                        throw e_1;
                    case 6: return [2 /*return*/];
                }
            });
        });
    };
    UnbluApi.prototype.offInternal = function (eventName) {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            var listener, internalModule;
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        listener = this.internalListeners[eventName];
                        if (listener == null) {
                            return [2 /*return*/];
                        }
                        delete this.internalListeners[eventName];
                        switch (eventName) {
                            case UnbluApi.AGENT_AVAILABILITY_CHANGE:
                                internalModule = this.internalApi.agentAvailability;
                                break;
                            default:
                                internalModule = this.internalApi.general;
                                break;
                        }
                        return [4 /*yield*/, internalModule.off(eventName, listener)];
                    case 1:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    // General
    /**
     * Returns information about the visitor.
     * @return A promise that resolves to the current visitors person info.
     */
    UnbluApi.prototype.getPersonInfo = function () {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [2 /*return*/, this.internalApi.general.getPersonInfo()];
                }
            });
        });
    };
    /**
     * Set the nickname of the current visitor.
     * This could be set before or during a conversation.
     * @return A empty promise when operation is done.
     */
    UnbluApi.prototype.setPersonNickname = function (nickname) {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [2 /*return*/, this.internalApi.general.setPersonNickname(nickname)];
                }
            });
        });
    };
    /**
     * Returns the number of unread messages.
     * @return A promise that resolves to the current number of unread messages.
     */
    UnbluApi.prototype.getNotificationCount = function () {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.internalApi.meta.isUpgraded()];
                    case 1:
                        if (_a.sent()) {
                            return [2 /*return*/, this.internalApi.general.getNotificationCount()];
                        }
                        else {
                            return [2 /*return*/, this.internalApi.generalLazy.getNotificationCount()];
                        }
                        return [2 /*return*/];
                }
            });
        });
    };
    // Conversation
    /**
     * Starts a new Conversation and places it into the inbound conversation queue.
     *
     * **NOTE:** calling this method will NOT automatically open the Unblu UI if it is collapsed. Use [ui.openIndividualUi()]{@link UnbluUiApi.openIndividualUi} if this is needed.
     *
     * @param type The conversation type that shall be started.
     * @param visitorName The name the local visitor should have. This is only taken into account if the visitor is not already authenticated.
     * @param visitorData Custom data for the visitor in any format. **NOTE:** The data which is passed here could be used in [NewConversationCallback]{@link NewConversationInterceptor}
     * @return A promise that resolves to the conversation object giving API access to the started conversation.
     */
    UnbluApi.prototype.startConversation = function (type, visitorName, visitorData) {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            var conversationId;
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.startConversation(type, visitorName, visitorData)];
                    case 2:
                        conversationId = _a.sent();
                        return [2 /*return*/, new Conversation(this.internalApi, conversationId)];
                }
            });
        });
    };
    /**
     * Set a custom interceptor which will be triggered when a new conversation is started (initiated from UI or JavaScript).
     * @param callback The interceptor which is called before a new conversation is started. The Callback is of type [NewConversationCallback]{@link NewConversationInterceptor}
     * @return A promise that resolves when the interceptor is successfully applied and active.
     */
    UnbluApi.prototype.setNewConversationInterceptor = function (callback) {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.internalApi.meta.isUpgraded()];
                    case 1:
                        if (!!(_a.sent())) return [3 /*break*/, 3];
                        return [4 /*yield*/, this.internalApi.generalLazy.setNewConversationInterceptor(callback)];
                    case 2: return [2 /*return*/, _a.sent()];
                    case 3: return [4 /*yield*/, this.internalApi.general.setNewConversationInterceptor(callback)];
                    case 4: return [2 /*return*/, _a.sent()];
                }
            });
        });
    };
    /**
     * Joins an existing conversation with a given PIN.
     *
     * **NOTE:** calling this method will NOT automatically open the Unblu UI if it is collapsed. Use [ui.openIndividualUi()]{@link UnbluUiApi.openIndividualUi} if this is needed.
     *
     * @param pin The PIN retrieved from the Unblu Agent Desk.
     * @param visitorName The name the local visitor should have. This is only taken into account if the visitor is not already authenticated.
     * @return A promise that resolves to the conversation object giving API access to the joined conversation.
     */
    UnbluApi.prototype.joinConversation = function (pin, visitorName) {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            var conversationId;
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.joinConversation(pin, visitorName)];
                    case 2:
                        conversationId = _a.sent();
                        return [2 /*return*/, new Conversation(this.internalApi, conversationId)];
                }
            });
        });
    };
    /**
     * Opens the existing conversation with the given conversation ID.
     *
     * **NOTE:** calling this method will NOT automatically open the Unblu UI if it is collapsed. Use [ui.openIndividualUi()]{@link UnbluUiApi.openIndividualUi} if this is needed.
     *
     * @param conversationId The PIN retrieved from tha Unblu Agent Desk.
     * @return A promise that resolves to the conversation object giving API access to the opened conversation.
     */
    UnbluApi.prototype.openConversation = function (conversationId) {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.requireUpgrade()];
                    case 1:
                        _a.sent();
                        return [4 /*yield*/, this.internalApi.general.openConversation(conversationId)];
                    case 2:
                        _a.sent();
                        return [2 /*return*/, new Conversation(this.internalApi, conversationId)];
                }
            });
        });
    };
    /**
     * Returns the currently active conversation or `null` if no conversation is active.
     *
     * **NOTE:** calling this method twice while the same conversation is active, will result in two individual conversation API instances being returned.
     * destroying one of them will not cause the other one to also be destroyed. If however the active conversation is closed, all returned Conversation instances will be destroyed.
     *
     * @return A promise that either resolves to the currently active conversation or `null` if no conversation is open.
     * @see [[ACTIVE_CONVERSATION_CHANGE]]
     */
    UnbluApi.prototype.getActiveConversation = function () {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            var conversationId;
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.internalApi.meta.isUpgraded()];
                    case 1:
                        if (!_a.sent()) return [3 /*break*/, 3];
                        return [4 /*yield*/, this.internalApi.general.getActiveConversation()];
                    case 2:
                        conversationId = _a.sent();
                        return [2 /*return*/, conversationId != null ? new Conversation(this.internalApi, conversationId) : null];
                    case 3: return [2 /*return*/, null];
                }
            });
        });
    };
    /**
     * Checks if an agent is available for the current named area and language.
     *
     * @return Promise that resolves to `true` if the availability state is [AVAILABLE]{@link AgentAvailabilityState.AVAILABLE} or [BUSY]{@link AgentAvailabilityState.BUSY}, `false` otherwise.
     * @see [[getAgentAvailabilityState]] for a more detailed check.
     */
    UnbluApi.prototype.isAgentAvailable = function () {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            return unblu_api_generator(this, function (_a) {
                return [2 /*return*/, this.internalApi.agentAvailability.isAgentAvailable()];
            });
        });
    };
    /**
     * Returns the current availability state for the current named area and language.
     * @return Promise that resolves to the current availability state.
     * @see [[isAgentAvailable]] for a simpler check.
     */
    UnbluApi.prototype.getAgentAvailabilityState = function () {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            return unblu_api_generator(this, function (_a) {
                return [2 /*return*/, this.internalApi.agentAvailability.getAgentAvailabilityState()];
            });
        });
    };
    UnbluApi.prototype.requireUpgrade = function () {
        return unblu_api_awaiter(this, void 0, void 0, function () {
            return unblu_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0: return [4 /*yield*/, this.internalApi.meta.upgrade(false)];
                    case 1:
                        _a.sent();
                        return [2 /*return*/];
                }
            });
        });
    };
    UnbluApi.prototype.onUpgraded = function () {
        for (var _i = 0, _a = this.eventEmitter.getEventsWithListeners(); _i < _a.length; _i++) {
            var event_1 = _a[_i];
            // register internal listeners for all events that need upgrade.
            if (!this.internalListeners[event_1])
                this.onInternal(event_1);
        }
    };
    /**
     * Event emitted every time the active conversation changed.
     *
     * This may happen due to a UI-navigation or an API-call.
     *
     * @event activeConversationChange
     * @see [[on]] for listener registration
     * @see [[ConversationChangeListener]]
     */
    UnbluApi.ACTIVE_CONVERSATION_CHANGE = 'activeConversationChange';
    /**
     * Event emitted every time the notification count (unread messages) changes.
     *
     * @event notificationCountChange
     * @see [[on]] for listener registration
     * @see [[NotificationCountChangeListener]]
     */
    UnbluApi.NOTIFICATION_COUNT_CHANGE = 'notificationCountChange';
    /**
     * Event emitted every time the local person changes. This may be i.e. due to the person setting its name.
     *
     * @event personChange
     * @see [[on]] for listener registration
     * @see [[PersonChangeListener]]
     */
    UnbluApi.PERSON_CHANGE = 'personChange';
    /**
     * Event emitted every time the agent availability changes for the current named area and locale.
     *
     * @event availabilityChange
     * @see [[on]] for listener registration
     * @see [[AgentAvailabilityChangeListener]]
     */
    UnbluApi.AGENT_AVAILABILITY_CHANGE = 'availabilityChange';
    return UnbluApi;
}());

//# sourceMappingURL=unblu-api.js.map
// CONCATENATED MODULE: ./src/lib-esm/unblu-util.js
// Typedef Helpers
var unblu_util_awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var unblu_util_generator = (undefined && undefined.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
/**
 * @hidden
 */
var UnbluUtil = /** @class */ (function () {
    function UnbluUtil() {
    }
    UnbluUtil.loadScript = function (uri, timeout) {
        return unblu_util_awaiter(this, void 0, void 0, function () {
            var timeoutTime, script;
            return unblu_util_generator(this, function (_a) {
                timeoutTime = timeout || 30000;
                script = document.createElement('script');
                script.setAttribute('charset', 'UTF-8');
                script.setAttribute('type', 'text/javascript');
                script.setAttribute('async', 'true');
                script.setAttribute('timeout', timeoutTime.toString());
                script.src = uri;
                return [2 /*return*/, new Promise(function (resolve, reject) {
                        var timeoutId;
                        var cleanup = function () {
                            // avoid mem leaks in IE.
                            script.onerror = script.onload = null;
                            window.clearTimeout(timeoutId);
                        };
                        var onError = function (error) {
                            cleanup();
                            console.error('Failed to load script! Uri:', uri, 'Error:', error);
                            reject(error);
                        };
                        script.onload = function () {
                            cleanup();
                            resolve();
                        };
                        script.onerror = onError;
                        timeoutId = window.setTimeout(function () { return onError('Timeout'); }, timeoutTime);
                        var head = document.getElementsByTagName('HEAD')[0];
                        head.appendChild(script);
                    })];
            });
        });
    };
    UnbluUtil.setNamedArea = function (namedArea) {
        var metaTag = window.document.querySelector('meta[name="unblu:named-area"]') || window.document.createElement('meta');
        metaTag.setAttribute('name', 'unblu:named-area');
        metaTag.setAttribute('content', namedArea);
        if (!metaTag.parentElement) {
            window.document.head.appendChild(metaTag);
        }
    };
    UnbluUtil.setLocale = function (locale) {
        // unblu.l will be read in user-locale-util.js
        UnbluUtil.getUnbluObject().l = locale;
    };
    UnbluUtil.isUnbluLoaded = function () {
        return UnbluUtil.getUnbluObject() && !!UnbluUtil.getUnbluObject().APIKEY;
    };
    UnbluUtil.getUnbluObject = function () {
        return window.unblu;
    };
    UnbluUtil.createUnbluObject = function () {
        return window.unblu = {};
    };
    return UnbluUtil;
}());

//# sourceMappingURL=unblu-util.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/java-error-codes.js
var CATEGORY_INVALID_FUNCTION_CALL = 1;
var CATEGORY_INVALID_FUNCTION_ARGUMENTS = 2;
var CATEGORY_EXECUTION_EXCEPTION = 3;
var CATEGORY_ACTION_NOT_GRANTED = 4;
//# sourceMappingURL=java-error-codes.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/api-bridge.js
var api_bridge_awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var api_bridge_generator = (undefined && undefined.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};


var SUPPORTED_MAJOR_VERSION = 2;
var ApiBridge = /** @class */ (function () {
    /**
     * instantiates the bridge that links the UNBLU internal API provided by the collaboration server with the UNBLU JS-API
     * @param mountPoint the global unblu object under which the internal API is registered.
     */
    function ApiBridge(mountPoint) {
        this.mountPoint = mountPoint;
    }
    ApiBridge.prototype.waitUntilLoaded = function (timeout, promise) {
        return api_bridge_awaiter(this, void 0, void 0, function () {
            var timeoutTimestamp;
            var _this = this;
            return api_bridge_generator(this, function (_a) {
                timeoutTimestamp = Date.now() + timeout;
                return [2 /*return*/, new Promise(function (resolve, reject) {
                        var waitForLoaded = function () {
                            if (timeoutTimestamp - Date.now() > 0) {
                                if (_this.checkLoaded())
                                    return resolve();
                                setTimeout(waitForLoaded, 50);
                            }
                            else {
                                reject(new UnbluApiError(UnbluErrorType.INITIALIZATION_TIMEOUT, 'Timeout while waiting for collaboration server API to be loaded.'));
                            }
                        };
                        waitForLoaded();
                    })];
            });
        });
    };
    ApiBridge.prototype.checkLoaded = function () {
        this.internal = this.mountPoint['internal'];
        return this.internal != null;
    };
    ApiBridge.prototype.checkCompatibility = function () {
        if (!this.internal) {
            throw new UnbluApiError(UnbluErrorType.INCOMPATIBLE_UNBLU_VERSION, 'Incompatible unblu collaboration server, no API bridge provided.');
        }
        else if (typeof this.internal.getApiVersion !== 'function') {
            throw new UnbluApiError(UnbluErrorType.INCOMPATIBLE_UNBLU_VERSION, 'Incompatible unblu collaboration server, incompatible API bridge.');
        }
        var version = this.internal.getApiVersion();
        if (+version[0] != SUPPORTED_MAJOR_VERSION) {
            throw new UnbluApiError(UnbluErrorType.INCOMPATIBLE_UNBLU_VERSION, "Incompatible collaboration server version. \n                Supported API version: " + SUPPORTED_MAJOR_VERSION + ".x.x.\n                collaboration server API version: " + version + ".");
        }
    };
    /**
     * Calls an API function of the internal unblu collaboration server API.
     * @param moduleName The module to which the function belongs.
     * @param functionName The function to call.
     * @param args The arguments to pass to the function.
     */
    ApiBridge.prototype.callApiFunction = function (moduleName, functionName, args) {
        return api_bridge_awaiter(this, void 0, void 0, function () {
            var e_1;
            return api_bridge_generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        _a.trys.push([0, 2, , 3]);
                        return [4 /*yield*/, this.internal.execute(moduleName, functionName, args)];
                    case 1: return [2 /*return*/, _a.sent()];
                    case 2:
                        e_1 = _a.sent();
                        switch (e_1.type) {
                            case CATEGORY_INVALID_FUNCTION_CALL:
                                throw new UnbluApiError(UnbluErrorType.INVALID_FUNCTION_CALL, e_1.message);
                            case CATEGORY_INVALID_FUNCTION_ARGUMENTS:
                                throw new UnbluApiError(UnbluErrorType.INVALID_FUNCTION_ARGUMENTS, e_1.message);
                            case CATEGORY_ACTION_NOT_GRANTED:
                                throw new UnbluApiError(UnbluErrorType.ACTION_NOT_GRANTED, e_1.message);
                            case CATEGORY_EXECUTION_EXCEPTION:
                                throw new UnbluApiError(UnbluErrorType.EXECUTION_EXCEPTION, e_1.message);
                            default:
                                throw new UnbluApiError(UnbluErrorType.EXECUTION_EXCEPTION, '' + e_1);
                        }
                        return [3 /*break*/, 3];
                    case 3: return [2 /*return*/];
                }
            });
        });
    };
    /**
     * Registers a callback for an event emitted by the internal unblu collaboration server API.
     * @param module The module that emits the event.
     * @param event The event name.
     * @param callback The callback which will be called every time the event is emitted.
     */
    ApiBridge.prototype.on = function (module, event, callback) {
        return this.internal.registerEventListener(module, event, callback);
    };
    /**
     * Unregisters a callback for an event emitted by the internal unblu collaboration server API.
     * @param module The module that emits the event.
     * @param event The event name.
     * @param callback Optionally callback which will be removed, if none is provided all listeners of the event will be removed.
     */
    ApiBridge.prototype.off = function (module, event, callback) {
        return this.internal.removeEventListener(module, event, callback);
    };
    return ApiBridge;
}());

//# sourceMappingURL=api-bridge.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/module/module.js
var InternalModule = /** @class */ (function () {
    function InternalModule(bridge, moduleName) {
        this.bridge = bridge;
        this.moduleName = moduleName;
    }
    InternalModule.prototype.callApiFunction = function (functionName, args) {
        return this.bridge.callApiFunction(this.moduleName, functionName, args);
    };
    InternalModule.prototype.on = function (eventName, listener) {
        return this.bridge.on(this.moduleName, eventName, listener);
    };
    InternalModule.prototype.off = function (eventName, listener) {
        return this.bridge.off(this.moduleName, eventName, listener);
    };
    return InternalModule;
}());

//# sourceMappingURL=module.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/module/meta-module.js
var meta_module_extends = (undefined && undefined.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();

var MetaModule = /** @class */ (function (_super) {
    meta_module_extends(MetaModule, _super);
    function MetaModule(bridge) {
        return _super.call(this, bridge, 'meta') || this;
    }
    MetaModule.prototype.isUpgraded = function () {
        return this.callApiFunction("isUpgraded" /* isUpgraded */, []);
    };
    MetaModule.prototype.upgrade = function (openUi) {
        return this.callApiFunction("upgrade" /* upgrade */, [openUi]);
    };
    MetaModule.prototype.waitUntilInitialized = function () {
        return this.callApiFunction("waitUntilInitialized" /* waitUntilInitialized */, []);
    };
    return MetaModule;
}(InternalModule));

//# sourceMappingURL=meta-module.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/module/general-module.js
var general_module_extends = (undefined && undefined.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();

var GeneralModule = /** @class */ (function (_super) {
    general_module_extends(GeneralModule, _super);
    function GeneralModule(bridge) {
        return _super.call(this, bridge, 'general') || this;
    }
    GeneralModule.prototype.startConversation = function (type, visitorName, visitorData) {
        return this.callApiFunction("startConversation" /* startConversation */, [type, visitorName, visitorData]);
    };
    GeneralModule.prototype.setNewConversationInterceptor = function (callback) {
        return this.callApiFunction("setNewConversationInterceptor" /* setNewConversationInterceptor */, [callback]);
    };
    GeneralModule.prototype.joinConversation = function (pin, visitorName) {
        return this.callApiFunction("joinConversation" /* joinConversation */, [pin, visitorName]);
    };
    GeneralModule.prototype.openConversation = function (conversationId) {
        return this.callApiFunction("openConversation" /* openConversation */, [conversationId]);
    };
    GeneralModule.prototype.toggleIndividualUi = function () {
        return this.callApiFunction("toggleIndividualUi" /* toggleIndividualUi */, []);
    };
    GeneralModule.prototype.openPinEntryUi = function () {
        return this.callApiFunction("openPinEntryUi" /* openPinEntryUi */, []);
    };
    GeneralModule.prototype.popoutIndividualUi = function () {
        return this.callApiFunction("popoutIndividualUi" /* popoutIndividualUi */, []);
    };
    GeneralModule.prototype.popinIndividualUi = function () {
        return this.callApiFunction("popinIndividualUi" /* popinIndividualUi */, []);
    };
    GeneralModule.prototype.maximizeIndividualUi = function () {
        return this.callApiFunction("maximizeIndividualUi" /* maximizeIndividualUi */, []);
    };
    GeneralModule.prototype.minimizeIndividualUi = function () {
        return this.callApiFunction("minimizeIndividualUi" /* minimizeIndividualUi */, []);
    };
    GeneralModule.prototype.openIndividualUi = function () {
        return this.callApiFunction("openIndividualUi" /* openIndividualUi */, []);
    };
    GeneralModule.prototype.collapseIndividualUi = function () {
        return this.callApiFunction("collapseIndividualUi" /* collapseIndividualUi */, []);
    };
    GeneralModule.prototype.getIndividualUiState = function () {
        return this.callApiFunction("getIndividualUiState" /* getIndividualUiState */, []);
    };
    GeneralModule.prototype.getActiveConversation = function () {
        return this.callApiFunction("getActiveConversation" /* getActiveConversation */, []);
    };
    GeneralModule.prototype.getNotificationCount = function () {
        return this.callApiFunction("getNotificationCount" /* getNotificationCount */, []);
    };
    GeneralModule.prototype.getPersonInfo = function () {
        return this.callApiFunction("getPersonInfo" /* getPersonInfo */, []);
    };
    GeneralModule.prototype.setPersonNickname = function (nickname) {
        return this.callApiFunction("setPersonNickname" /* setPersonNickname */, [nickname]);
    };
    return GeneralModule;
}(InternalModule));

//# sourceMappingURL=general-module.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/module/general-lazy-module.js
var general_lazy_module_extends = (undefined && undefined.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();

var GeneralLazyModule = /** @class */ (function (_super) {
    general_lazy_module_extends(GeneralLazyModule, _super);
    function GeneralLazyModule(bridge) {
        return _super.call(this, bridge, 'generallazy') || this;
    }
    GeneralLazyModule.prototype.getNotificationCount = function () {
        return this.callApiFunction("getNotificationCount" /* getNotificationCount */, []);
    };
    GeneralLazyModule.prototype.getPersonInfo = function () {
        return this.callApiFunction("getPersonInfo" /* getPersonInfo */, []);
    };
    GeneralLazyModule.prototype.setNewConversationInterceptor = function (callback) {
        return this.callApiFunction("setNewConversationInterceptor" /* setNewConversationInterceptor */, [callback]);
    };
    return GeneralLazyModule;
}(InternalModule));

//# sourceMappingURL=general-lazy-module.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/module/conversation-module.js
var conversation_module_extends = (undefined && undefined.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var conversation_module_awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var conversation_module_generator = (undefined && undefined.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};

var ConversationModule = /** @class */ (function (_super) {
    conversation_module_extends(ConversationModule, _super);
    function ConversationModule(bridge) {
        return _super.call(this, bridge, 'conversation') || this;
    }
    ConversationModule.prototype.getConnectionState = function (conversationId) {
        return this.callApiFunction("getConnectionState" /* getConnectionState */, [conversationId]);
    };
    ConversationModule.prototype.getConversationState = function (conversationId) {
        return this.callApiFunction("getConversationState" /* getConversationState */, [conversationId]);
    };
    ConversationModule.prototype.getCallState = function (conversationId) {
        return this.callApiFunction("getCallState" /* getCallState */, [conversationId]);
    };
    ConversationModule.prototype.startAudioCall = function (conversationId) {
        return this.callApiFunction("startAudioCall" /* startAudioCall */, [conversationId]);
    };
    ConversationModule.prototype.startVideoCall = function (conversationId) {
        return this.callApiFunction("startVideoCall" /* startVideoCall */, [conversationId]);
    };
    ConversationModule.prototype.endConversation = function (conversationId) {
        return this.callApiFunction("endConversation" /* endConversation */, [conversationId]);
    };
    ConversationModule.prototype.leaveConversation = function (conversationId) {
        return this.callApiFunction("leaveConversation" /* leaveConversation */, [conversationId]);
    };
    ConversationModule.prototype.closeConversation = function (conversationId) {
        return this.callApiFunction("closeConversation" /* closeConversation */, [conversationId]);
    };
    ConversationModule.prototype.createAnonymousPinInvitation = function (conversationId) {
        return this.callApiFunction("createAnonymousPinInvitation" /* createAnonymousPinInvitation */, [conversationId]);
    };
    ConversationModule.prototype.createAnonymousEmailInvitation = function (conversationId, email) {
        return this.callApiFunction("createAnonymousEmailInvitation" /* createAnonymousEmailInvitation */, [conversationId, email]);
    };
    ConversationModule.prototype.getInvitations = function (conversationId) {
        return conversation_module_awaiter(this, void 0, void 0, function () {
            return conversation_module_generator(this, function (_a) {
                return [2 /*return*/, this.callApiFunction("getInvitations" /* getInvitations */, [conversationId])];
            });
        });
    };
    ConversationModule.prototype.revokeInvitation = function (conversationId, invitationId) {
        return conversation_module_awaiter(this, void 0, void 0, function () {
            return conversation_module_generator(this, function (_a) {
                return [2 /*return*/, this.callApiFunction("revokeInvitation" /* revokeInvitation */, [conversationId, invitationId])];
            });
        });
    };
    ConversationModule.prototype.renewInvitationPin = function (conversationId, invitationId) {
        return conversation_module_awaiter(this, void 0, void 0, function () {
            return conversation_module_generator(this, function (_a) {
                return [2 /*return*/, this.callApiFunction("renewInvitationPin" /* renewInvitationPin */, [conversationId, invitationId])];
            });
        });
    };
    return ConversationModule;
}(InternalModule));

//# sourceMappingURL=conversation-module.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/module/agent-availability-module.js
var agent_availability_module_extends = (undefined && undefined.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();

var AgentAvailabilityModule = /** @class */ (function (_super) {
    agent_availability_module_extends(AgentAvailabilityModule, _super);
    function AgentAvailabilityModule(bridge) {
        return _super.call(this, bridge, 'agentavailability') || this;
    }
    AgentAvailabilityModule.prototype.isAgentAvailable = function () {
        return this.callApiFunction("isAgentAvailable" /* isAgentAvailable */, []);
    };
    AgentAvailabilityModule.prototype.getAgentAvailabilityState = function () {
        return this.callApiFunction("getAgentAvailabilityState" /* getAgentAvailabilityState */, []);
    };
    return AgentAvailabilityModule;
}(InternalModule));

//# sourceMappingURL=agent-availability-module.js.map
// CONCATENATED MODULE: ./src/lib-esm/internal/internal-api.js





var InternalApi = /** @class */ (function () {
    function InternalApi(bridge) {
        this.bridge = bridge;
        this.meta = new MetaModule(bridge);
        this.general = new GeneralModule(bridge);
        this.generalLazy = new GeneralLazyModule(bridge);
        this.conversation = new ConversationModule(bridge);
        this.agentAvailability = new AgentAvailabilityModule(bridge);
    }
    InternalApi.prototype.checkCompatibility = function () {
        this.bridge.checkCompatibility();
    };
    return InternalApi;
}());

//# sourceMappingURL=internal-api.js.map
// CONCATENATED MODULE: ./src/lib-esm/unblu-static-api.js
var unblu_static_api_awaiter = (undefined && undefined.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var unblu_static_api_generator = (undefined && undefined.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};






/**
 * #### The central entry point that allows to configure an initialize the Unblu Visitor JS API.
 * The static Unblu API works without actually loading the rest of Unblu.
 * It can do some general checks and load Unblu or connect the API to a loaded version of Unblu.
 * The JS API is an optional add-on to the Unblu visitor site integration.
 *
 * Depending on how Unblu is integrated into the local website the API has to be initialized differently.
 *
 * **a.) API-only integration**
 * If no unblu-snippet is loaded into the page, Unblu can be fully initialized with the API.
 * In this case, both the `configure` and the `initialize` methods have to be called.
 * Example:
 * ```ts
 *  const api = await unblu.api
 *      // configure the unblu server
 *      .configure({
 *          apiKey: "<your-api-key>",
 *          serverUrl: "<unblu-server-url>"
 *      })
 *      // initialize the api.
 *      .initialize();
 * ```
 * This implementation will load the Unblu snippet and initialize both Unblu and the JS API.
 *
 * **b.) Snippet and JS API integration**
 * If the Unblu snippet is already present in the local website, Unblu doesn't have to be loaded
 * and only the API has to be initialized.
 * Example:
 * ```ts
 * // directly initialize the api without configuring.
 * const api = await unblu.api.initialize();
 *
 * ```
 */
var UnbluStaticApi = /** @class */ (function () {
    /**
     * @hidden
     */
    function UnbluStaticApi() {
        //TODO: detect browser support;
        var _this = this;
        this.state = "INITIAL" /* INITIAL */;
        this.eventEmitter = new EventEmitter();
        // store the error
        this.eventEmitter.on(UnbluStaticApi.ERROR, function (e) { return _this.error = e; });
        // install globally
        var unblu = UnbluUtil.getUnbluObject();
        if (unblu.api) {
            this.handleError(new UnbluApiError(UnbluErrorType.ILLEGAL_STATE, 'Unblu API has already been loaded.'));
        }
        else {
            unblu.api = this;
        }
        if (UnbluUtil.isUnbluLoaded()) {
            // Auto init if snippet is already loaded.
            this.initializeApi().catch(function (e) { return console.warn('Error during auto initialization', e); });
        }
    }
    UnbluStaticApi.prototype.on = function (event, listener) {
        if (event == UnbluStaticApi.READY && this.state == "INITIALIZED" /* INITIALIZED */)
            listener(this.initializedApi);
        else if (event == UnbluStaticApi.ERROR && this.state == "ERROR" /* ERROR */)
            listener(this.error);
        else
            this.eventEmitter.on(event, listener);
    };
    /**
     * Removes a previously registered listener.
     * @param event The event unregister.
     * @param listener The listener to be removed.
     * @return `true` if the listener was removed, `false` otherwise.
     */
    UnbluStaticApi.prototype.off = function (event, listener) {
        return this.eventEmitter.off(event, listener);
    };
    /**
     * Checks whether the API has to be configured or not.
     *
     * - If no snippet is present and the API state is still [INITIAL]{@link ApiState.INITIAL} a configuration is necessary.
     * - If a snippet is present or the API is already loaded, configuration is not necessary.
     *
     * @return `true` if a configuration is needed to initialize the API, `false` otherwise.
     * @see [[configure]] to configure the API
     * @see [[initialize]] to initialize the API
     */
    UnbluStaticApi.prototype.isConfigurationNeeded = function () {
        return this.state === "INITIAL" /* INITIAL */ && !UnbluUtil.isUnbluLoaded();
    };
    /**
     * Returns the current state of the API
     * @return the current API state.
     * @see [[isInitialized]] for a simpler check
     */
    UnbluStaticApi.prototype.getApiState = function () {
        return this.state;
    };
    /**
     * Checks whether the API is initialized or not.
     * @return `true` if the API is initialized, `false` for any other state.
     * @see [[getApiState]] for the full state
     */
    UnbluStaticApi.prototype.isInitialized = function () {
        return this.state === "INITIALIZED" /* INITIALIZED */;
    };
    /**
     * Configures the way that Unblu should be initialized.
     *
     * The configuration of the Unblu API is needed when, and only when no Unblu snippet is already present in the website.
     *
     * **Note:**
     * - Calling this method when there already is an unblu-snippet will result in an [[UnbluApiError]].
     * - This method must be called BEFORE [[initialize]]. If it is called afterwards an [[UnbluApiError]] will be thrown.
     *
     * @param config The configuration to be set.
     * @return an instance of `this` allowing chaining like `unblu.api.configure({...}).initialize();`
     * @see [[isConfigurationNeeded]] to check if configuration is needed or not.
     */
    UnbluStaticApi.prototype.configure = function (config) {
        if (UnbluUtil.isUnbluLoaded()) {
            throw new UnbluApiError(UnbluErrorType.ILLEGAL_STATE, 'Configure called when Unblu was already loaded.');
        }
        else if (this.state !== "INITIAL" /* INITIAL */) {
            throw new UnbluApiError(UnbluErrorType.ILLEGAL_STATE, 'Error configure called after API was already initialized.');
        }
        this.configuration = config;
        return this;
    };
    /**
     * Initializes the API and resolves to the fully initialized API.
     *
     * If the API has already been initialized or is already in the initializing process, the existing API will be returned.
     * There is only ever one instance of the API which will be returned by any call of this method which makes it safe to call this multiple times.
     *
     * *The initialization may fail with a [[UnbluApiError]] for the following reasons*
     * - A configuration is needed but none was provided: [CONFIGURATION_MISSING]{@link UnbluErrorType.CONFIGURATION_MISSING}
     * - Loading Unblu encounters a problem: [ERROR_LOADING_UNBLU]{@link UnbluErrorType.ERROR_LOADING_UNBLU}
     * - The initialization timed out: [INITIALIZATION_TIMEOUT]{@link UnbluErrorType.INITIALIZATION_TIMEOUT}
     * - The Unblu API is incompatible with the Unblu server: [INCOMPATIBLE_UNBLU_VERSION]{@link UnbluErrorType.INCOMPATIBLE_UNBLU_VERSION}
     * - The browser is unsupported: [UNSUPPORTED_BROWSER]{@link UnbluErrorType.UNSUPPORTED_BROWSER}
     */
    UnbluStaticApi.prototype.initialize = function () {
        return unblu_static_api_awaiter(this, void 0, void 0, function () {
            var _this = this;
            return unblu_static_api_generator(this, function (_a) {
                if (this.state === "INITIALIZED" /* INITIALIZED */) {
                    return [2 /*return*/, this.initializedApi];
                }
                else if (this.state === "INITIALIZING" /* INITIALIZING */) {
                    return [2 /*return*/, new Promise(function (resolve, reject) {
                            _this.on(UnbluStaticApi.READY, resolve);
                            _this.on(UnbluStaticApi.ERROR, reject);
                        })];
                }
                else {
                    return [2 /*return*/, this.initializeApi()];
                }
                return [2 /*return*/];
            });
        });
    };
    UnbluStaticApi.prototype.initializeApi = function () {
        return unblu_static_api_awaiter(this, void 0, void 0, function () {
            var apiBridge, internalApi, e_1;
            return unblu_static_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        this.state = "INITIALIZING" /* INITIALIZING */;
                        _a.label = 1;
                    case 1:
                        _a.trys.push([1, 6, , 7]);
                        if (!!UnbluUtil.isUnbluLoaded()) return [3 /*break*/, 3];
                        if (!this.configuration) {
                            throw new UnbluApiError(UnbluErrorType.CONFIGURATION_MISSING, 'No Unblu snippet present and no configuration provided. Use configure if you want to initialize Unblu without having the Unblu snippet loaded.');
                        }
                        if (this.configuration.namedArea) {
                            UnbluUtil.setNamedArea(this.configuration.namedArea);
                        }
                        if (this.configuration.locale) {
                            UnbluUtil.setLocale(this.configuration.locale);
                        }
                        return [4 /*yield*/, this.injectUnblu(this.configuration)];
                    case 2:
                        _a.sent();
                        _a.label = 3;
                    case 3:
                        apiBridge = new ApiBridge(UnbluUtil.getUnbluObject());
                        return [4 /*yield*/, apiBridge.waitUntilLoaded(this.configuration ? this.configuration.initTimeout || 30000 : 30000)];
                    case 4:
                        _a.sent();
                        internalApi = new InternalApi(apiBridge);
                        internalApi.checkCompatibility();
                        // Check internalApi waitUntilInitialized
                        return [4 /*yield*/, internalApi.meta.waitUntilInitialized()];
                    case 5:
                        // Check internalApi waitUntilInitialized
                        _a.sent();
                        this.initializedApi = new UnbluApi(internalApi);
                        this.state = "INITIALIZED" /* INITIALIZED */;
                        return [3 /*break*/, 7];
                    case 6:
                        e_1 = _a.sent();
                        this.handleError(e_1);
                        return [3 /*break*/, 7];
                    case 7:
                        this.eventEmitter.emit(UnbluStaticApi.READY, this.initializedApi);
                        return [2 /*return*/, this.initializedApi];
                }
            });
        });
    };
    UnbluStaticApi.prototype.injectUnblu = function (config) {
        return unblu_static_api_awaiter(this, void 0, void 0, function () {
            var serverUrl, apiKey, unbluPath, unbluUrl, e_2;
            return unblu_static_api_generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        serverUrl = config.serverUrl || '';
                        apiKey = config.apiKey || '';
                        unbluPath = config.entryPath || '/unblu';
                        unbluUrl = "" + serverUrl + unbluPath + "/visitor.js?x-unblu-apikey=" + apiKey;
                        _a.label = 1;
                    case 1:
                        _a.trys.push([1, 3, , 4]);
                        return [4 /*yield*/, UnbluUtil.loadScript(unbluUrl, config.initTimeout)];
                    case 2:
                        _a.sent();
                        return [3 /*break*/, 4];
                    case 3:
                        e_2 = _a.sent();
                        throw new UnbluApiError(UnbluErrorType.ERROR_LOADING_UNBLU, 'Error loading unblu snippet: ' + e_2 + ' check the configuration: ' + config);
                    case 4: return [2 /*return*/];
                }
            });
        });
    };
    UnbluStaticApi.prototype.handleError = function (error) {
        this.state = "ERROR" /* ERROR */;
        this.eventEmitter.emit(UnbluStaticApi.ERROR, error);
        console.error(error);
        throw error;
    };
    /**
     * Event emitted as soon as the API is initialized.
     *
     * It usually makes sense to use this event if there is some general action that has to be triggered when the API is initialized,
     * but there are several places in the integration code that may trigger the initialization.
     *
     * In most cases however, it is better to use
     * ```ts
     * unblu.api.initialize().then(api => { //use api here });
     * ```
     * or
     * ```ts
     * let api = await unblu.api.initialize();
     * // use api here
     * ```
     *
     * @event ready
     * @see [[on]] for listener registration
     */
    UnbluStaticApi.READY = 'ready';
    /**
     * Event emitted if the API initialization fails.
     *
     * It usually makes sense to use this event if there is some general action that has to be triggered when the API initialization fails,
     * but there are several places in the integration code that may trigger the initialization.
     *
     * In most cases however, it is better to use
     * ```ts
     * unblu.api.initialize().catch(error=> { //handle error here });
     * ```
     * or
     * ```ts
     * try{
     *      let api = await unblu.api.initialize();
     * }catch(e){
     *     // handle error here
     * }
     *
     * ```
     *
     * @event error
     * @see [[on]] for listener registration
     */
    UnbluStaticApi.ERROR = 'error';
    return UnbluStaticApi;
}());

//# sourceMappingURL=unblu-static-api.js.map
// CONCATENATED MODULE: ./src/lib-esm/model/call-state.js
var CallState;
(function (CallState) {
    /**
     * A some one is trying to call, the phone is ringing
     */
    CallState["INBOUND"] = "INBOUND";
    /**
     * You are trying to call someone
     */
    CallState["OUTBOUND"] = "OUTBOUND";
    /**
     * A call is active and the local tab is connected to the call.
     */
    CallState["ACTIVE_JOINED"] = "ACTIVE_JOINED";
    /**
     * A call is active but the local person has declined it.
     */
    CallState["ACTIVE_NOT_JOINED"] = "ACTIVE_NOT_JOINED";
    /**
     * A call is active and the local person has joined it on an other device.
     */
    CallState["ACTIVE_JOINED_ELSEWHERE"] = "ACTIVE_JOINED_ELSEWHERE";
    /**
     * No inbound, outgoing or active call.
     */
    CallState["NONE"] = "NONE";
})(CallState || (CallState = {}));
//# sourceMappingURL=call-state.js.map
// CONCATENATED MODULE: ./src/lib-esm/model/connection-state.js
var ConnectionState;
(function (ConnectionState) {
    /**
     * Initial state before connection establishment has started
     */
    ConnectionState["INITIAL"] = "INITIAL";
    /**
     * State during the first connection to the server
     */
    ConnectionState["CONNECTING"] = "CONNECTING";
    /**
     * The connection has been successfully established.
     */
    ConnectionState["CONNECTED"] = "CONNECTED";
    /**
     * The connection has been lost, reconnect is active.
     */
    ConnectionState["RECONNECTING"] = "RECONNECTING";
    /**
     * The connection has been successfully closed.
     *
     */
    ConnectionState["CLOSED"] = "CLOSED";
    /**
     * A fatal error has occurred. The connection has been permanently lost. No reconnect will be be possible.
     */
    ConnectionState["ERROR"] = "ERROR";
})(ConnectionState || (ConnectionState = {}));
//# sourceMappingURL=connection-state.js.map
// EXTERNAL MODULE: ./src/lib-esm/model/conversation-type.js
var conversation_type = __webpack_require__(350);
// CONCATENATED MODULE: ./src/lib-esm/model/agent-availability-state.js
var AgentAvailabilityState;
(function (AgentAvailabilityState) {
    /**
     * There is at least one agent available for the specified named area and language.
     */
    AgentAvailabilityState["AVAILABLE"] = "AVAILABLE";
    /**
     * There is at least one agent watching the inbound queue for the specified named area and language but the max capacity of parallel conversations is reached.
     *
     * It is very likely, that an agent will be available in a short time.
     */
    AgentAvailabilityState["BUSY"] = "BUSY";
    /**
     * There is no agent watching the inbound queue for the specified named area and language.
     *
     * It is unlikely, that an agent will be available in a short time.
     */
    AgentAvailabilityState["UNAVAILABLE"] = "UNAVAILABLE";
    /**
     * There is currently no agent handling any inbound queue items.
     *
     * It is very unlikely, that an agent will be available in a short time.
     */
    AgentAvailabilityState["OFFLINE"] = "OFFLINE";
})(AgentAvailabilityState || (AgentAvailabilityState = {}));
//# sourceMappingURL=agent-availability-state.js.map
// CONCATENATED MODULE: ./src/lib-esm/model/conversation-state.js
var ConversationState;
(function (ConversationState) {
    /**
     * Initial state of a conversation
     */
    ConversationState["CREATED"] = "CREATED";
    /**
     * Depending of the engagement type of the conversation, the conversation stays in the onboarding state as long as the person in the center of the conversation
     * is doing his onboarding
     */
    ConversationState["ONBOARDING"] = "ONBOARDING";
    /**
     * When a message is send to an unassigned conversation, the conversation first is put into the reboarding state as long as the person in the center of the
     * conversation is doing the reboarding.
     */
    ConversationState["REBOARDING"] = "REBOARDING";
    /**
     * Depending of the engagement type of the conversation, the conversation is put in the queued state when it is inside the queue.
     */
    ConversationState["QUEUED"] = "QUEUED";
    /**
     * Conversation is active
     */
    ConversationState["ACTIVE"] = "ACTIVE";
    /**
     * After there is no assignee of a conversation anymore, the conversation goes to unassigned state until a message from a visitor is written to start reboarding
     * again.
     */
    ConversationState["UNASSIGNED"] = "UNASSIGNED";
    /**
     * Conversation is being ended
     */
    ConversationState["OFFBOARDING"] = "OFFBOARDING";
    /**
     * Conversation is ended
     */
    ConversationState["ENDED"] = "ENDED";
})(ConversationState || (ConversationState = {}));
//# sourceMappingURL=conversation-state.js.map
// CONCATENATED MODULE: ./src/lib-esm/index.js










var unblu = (UnbluUtil.getUnbluObject() || UnbluUtil.createUnbluObject());
unblu.AgentAvailabilityState = AgentAvailabilityState;
unblu.CallState = CallState;
unblu.ConnectionState = ConnectionState;
unblu.ConversationState = ConversationState;
unblu.ConversationType = conversation_type.ConversationType;
unblu.UnbluErrorType = UnbluErrorType;
/**
 * The central access point to the unblu JS API.
 * This api object gives static access to configure and initialize the full unblu `UnbluApi`.
 * When loaded in the global space use `Unblu.Api` to access it.
 * @hidden
 */
var api = new UnbluStaticApi();
//# sourceMappingURL=index.js.map

/***/ }),

/***/ 350:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ConversationType": function() { return /* binding */ ConversationType; }
/* harmony export */ });
var ConversationType;
(function (ConversationType) {
    /**
     * (Visitor initiated, visitor centered) A visitor creates an incoming chat conversation.
     */
    ConversationType["CHAT_REQUEST"] = "CHAT_REQUEST";
    /**
     * (Visitor initiated, visitor centered) A visitor creates an incoming chat conversation while all agents are offline.
     */
    ConversationType["OFFLINE_CHAT_REQUEST"] = "OFFLINE_CHAT_REQUEST";
    /**
     * (Visitor initiated, visitor centered) A visitor creates an incoming video conversation.
     */
    ConversationType["VIDEO_REQUEST"] = "VIDEO_REQUEST";
    /**
     * (Visitor initiated, visitor centered) A visitor creates an incoming audio conversation.
     */
    ConversationType["AUDIO_REQUEST"] = "AUDIO_REQUEST";
    /**
     * (Visitor initiated, visitor centered) A visitor creates an incoming universal co-browsing conversation.
     */
    ConversationType["HEADLESS_BROWSER_REQUEST"] = "HEADLESS_BROWSER_REQUEST";
    /**
     * (Visitor initiated, visitor centered) A visitor creates an incoming embedded co-browsing conversation.
     */
    ConversationType["DOMCAP_BROWSER_REQUEST"] = "DOMCAP_BROWSER_REQUEST";
    /**
     * (Visitor initiated, visitor centered) A visitor creates an incoming mobile co-browsing conversation.
     */
    ConversationType["MOBILE_COBROWSING_REQUEST"] = "MOBILE_COBROWSING_REQUEST";
    /**
     * (Visitor initiated, visitor centered) A visitor creates an incoming screen sharing conversation.
     */
    ConversationType["SCREEN_SHARING_REQUEST"] = "SCREEN_SHARING_REQUEST";
    /**
     * (Api initiated, visitor centered) A visitor creates a conversation via API which is not added to the queue and where he can invite other visitor to do
     * embedded
     */
    ConversationType["VISITOR_COBROWSING"] = "VISITOR_COBROWSING";
})(ConversationType || (ConversationType = {}));
//# sourceMappingURL=conversation-type.js.map

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	// module exports must be returned from runtime so entry inlining is disabled
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(408);
/******/ })()

));
//# sourceMappingURL=unblu.integration.component.js.map