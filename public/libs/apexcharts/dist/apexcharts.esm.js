/*!
 * ApexCharts v3.6.10
 * (c) 2018-2019 Juned Chhipa
 * Released under the MIT License.
 */
function _typeof(obj) {
  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    _typeof = function (obj) {
      return typeof obj;
    };
  } else {
    _typeof = function (obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };
  }

  return _typeof(obj);
}

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
}

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

function _objectSpread(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};
    var ownKeys = Object.keys(source);

    if (typeof Object.getOwnPropertySymbols === 'function') {
      ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {
        return Object.getOwnPropertyDescriptor(source, sym).enumerable;
      }));
    }

    ownKeys.forEach(function (key) {
      _defineProperty(target, key, source[key]);
    });
  }

  return target;
}

function _inherits(subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function");
  }

  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      writable: true,
      configurable: true
    }
  });
  if (superClass) _setPrototypeOf(subClass, superClass);
}

function _getPrototypeOf(o) {
  _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
    return o.__proto__ || Object.getPrototypeOf(o);
  };
  return _getPrototypeOf(o);
}

function _setPrototypeOf(o, p) {
  _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };

  return _setPrototypeOf(o, p);
}

function _assertThisInitialized(self) {
  if (self === void 0) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return self;
}

function _possibleConstructorReturn(self, call) {
  if (call && (typeof call === "object" || typeof call === "function")) {
    return call;
  }

  return _assertThisInitialized(self);
}

function _toConsumableArray(arr) {
  return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread();
}

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) {
    for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) arr2[i] = arr[i];

    return arr2;
  }
}

function _iterableToArray(iter) {
  if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter);
}

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance");
}

/*
 ** Generic functions which are not dependent on ApexCharts
 */
var Utils =
/*#__PURE__*/
function () {
  function Utils() {
    _classCallCheck(this, Utils);
  }

  _createClass(Utils, [{
    key: "shadeRGBColor",
    value: function shadeRGBColor(percent, color) {
      var f = color.split(','),
          t = percent < 0 ? 0 : 255,
          p = percent < 0 ? percent * -1 : percent,
          R = parseInt(f[0].slice(4)),
          G = parseInt(f[1]),
          B = parseInt(f[2]);
      return 'rgb(' + (Math.round((t - R) * p) + R) + ',' + (Math.round((t - G) * p) + G) + ',' + (Math.round((t - B) * p) + B) + ')';
    }
  }, {
    key: "shadeHexColor",
    value: function shadeHexColor(percent, color) {
      var f = parseInt(color.slice(1), 16),
          t = percent < 0 ? 0 : 255,
          p = percent < 0 ? percent * -1 : percent,
          R = f >> 16,
          G = f >> 8 & 0x00ff,
          B = f & 0x0000ff;
      return '#' + (0x1000000 + (Math.round((t - R) * p) + R) * 0x10000 + (Math.round((t - G) * p) + G) * 0x100 + (Math.round((t - B) * p) + B)).toString(16).slice(1);
    } // beautiful color shading blending code
    // http://stackoverflow.com/questions/5560248/programmatically-lighten-or-darken-a-hex-color-or-rgb-and-blend-colors

  }, {
    key: "shadeColor",
    value: function shadeColor(p, color) {
      if (color.length > 7) return this.shadeRGBColor(p, color);else return this.shadeHexColor(p, color);
    }
  }], [{
    key: "bind",
    value: function bind(fn, me) {
      return function () {
        return fn.apply(me, arguments);
      };
    }
  }, {
    key: "isObject",
    value: function isObject(item) {
      return item && _typeof(item) === 'object' && !Array.isArray(item) && item != null;
    }
  }, {
    key: "listToArray",
    value: function listToArray(list) {
      var i,
          array = [];

      for (i = 0; i < list.length; i++) {
        array[i] = list[i];
      }

      return array;
    } // to extend defaults with user options
    // credit: http://stackoverflow.com/questions/27936772/deep-object-merging-in-es6-es7#answer-34749873

  }, {
    key: "extend",
    value: function extend(target, source) {
      var _this = this;

      if (typeof Object.assign !== 'function') {

        (function () {
          Object.assign = function (target) {

            if (target === undefined || target === null) {
              throw new TypeError('Cannot convert undefined or null to object');
            }

            var output = Object(target);

            for (var index = 1; index < arguments.length; index++) {
              var _source = arguments[index];

              if (_source !== undefined && _source !== null) {
                for (var nextKey in _source) {
                  if (_source.hasOwnProperty(nextKey)) {
                    output[nextKey] = _source[nextKey];
                  }
                }
              }
            }

            return output;
          };
        })();
      }

      var output = Object.assign({}, target);

      if (this.isObject(target) && this.isObject(source)) {
        Object.keys(source).forEach(function (key) {
          if (_this.isObject(source[key])) {
            if (!(key in target)) {
              Object.assign(output, _defineProperty({}, key, source[key]));
            } else {
              output[key] = _this.extend(target[key], source[key]);
            }
          } else {
            Object.assign(output, _defineProperty({}, key, source[key]));
          }
        });
      }

      return output;
    }
  }, {
    key: "extendArray",
    value: function extendArray(arrToExtend, resultArr) {
      var extendedArr = [];
      arrToExtend.map(function (item) {
        extendedArr.push(Utils.extend(resultArr, item));
      });
      arrToExtend = extendedArr;
      return arrToExtend;
    } // If month counter exceeds 12, it starts again from 1

  }, {
    key: "monthMod",
    value: function monthMod(month) {
      return month % 12;
    }
  }, {
    key: "addProps",
    value: function addProps(obj, arr, val) {
      if (typeof arr === 'string') {
        arr = arr.split('.');
      }

      obj[arr[0]] = obj[arr[0]] || {};
      var tmpObj = obj[arr[0]];

      if (arr.length > 1) {
        arr.shift();
        this.addProps(tmpObj, arr, val);
      } else {
        obj[arr[0]] = val;
      }

      return obj;
    }
  }, {
    key: "clone",
    value: function clone(source) {
      if (Object.prototype.toString.call(source) === '[object Array]') {
        var cloneResult = [];

        for (var i = 0; i < source.length; i++) {
          cloneResult[i] = this.clone(source[i]);
        }

        return cloneResult;
      } else if (_typeof(source) === 'object') {
        var _cloneResult = {};

        for (var prop in source) {
          if (source.hasOwnProperty(prop)) {
            _cloneResult[prop] = this.clone(source[prop]);
          }
        }

        return _cloneResult;
      } else {
        return source;
      }
    }
  }, {
    key: "log10",
    value: function log10(x) {
      return Math.log(x) / Math.LN10;
    }
  }, {
    key: "roundToBase10",
    value: function roundToBase10(x) {
      return Math.pow(10, Math.floor(Math.log10(x)));
    }
  }, {
    key: "roundToBase",
    value: function roundToBase(x, base) {
      return Math.pow(base, Math.floor(Math.log(x) / Math.log(base)));
    }
  }, {
    key: "parseNumber",
    value: function parseNumber(val) {
      if (val === null) return val;
      return parseFloat(val);
    }
  }, {
    key: "noExponents",
    value: function noExponents(val) {
      var data = String(val).split(/[eE]/);
      if (data.length == 1) return data[0];
      var z = '',
          sign = val < 0 ? '-' : '',
          str = data[0].replace('.', ''),
          mag = Number(data[1]) + 1;

      if (mag < 0) {
        z = sign + '0.';

        while (mag++) {
          z += '0';
        }

        return z + str.replace(/^\-/, '');
      }

      mag -= str.length;

      while (mag--) {
        z += '0';
      }

      return str + z;
    }
  }, {
    key: "getDimensions",
    value: function getDimensions(el) {
      var computedStyle = getComputedStyle(el);
      var ret = [];
      var elementHeight = el.clientHeight;
      var elementWidth = el.clientWidth;
      elementHeight -= parseFloat(computedStyle.paddingTop) + parseFloat(computedStyle.paddingBottom);
      elementWidth -= parseFloat(computedStyle.paddingLeft) + parseFloat(computedStyle.paddingRight);
      ret.push(elementWidth);
      ret.push(elementHeight);
      return ret;
    }
  }, {
    key: "getBoundingClientRect",
    value: function getBoundingClientRect(element) {
      var rect = element.getBoundingClientRect();
      return {
        top: rect.top,
        right: rect.right,
        bottom: rect.bottom,
        left: rect.left,
        width: rect.width,
        height: rect.height,
        x: rect.x,
        y: rect.y
      };
    } // http://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb#answer-12342275

  }, {
    key: "hexToRgba",
    value: function hexToRgba() {
      var hex = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '#999999';
      var opacity = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0.6;

      if (hex.substring(0, 1) !== '#') {
        hex = '#999999';
      }

      var h = hex.replace('#', '');
      h = h.match(new RegExp('(.{' + h.length / 3 + '})', 'g'));

      for (var i = 0; i < h.length; i++) {
        h[i] = parseInt(h[i].length === 1 ? h[i] + h[i] : h[i], 16);
      }

      if (typeof opacity !== 'undefined') h.push(opacity);
      return 'rgba(' + h.join(',') + ')';
    }
  }, {
    key: "getOpacityFromRGBA",
    value: function getOpacityFromRGBA(rgba) {
      rgba = rgba.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
      return rgba[3];
    }
  }, {
    key: "rgb2hex",
    value: function rgb2hex(rgb) {
      rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
      return rgb && rgb.length === 4 ? '#' + ('0' + parseInt(rgb[1], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[2], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
    }
  }, {
    key: "isColorHex",
    value: function isColorHex(color) {
      return /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(color);
    }
  }, {
    key: "polarToCartesian",
    value: function polarToCartesian(centerX, centerY, radius, angleInDegrees) {
      var angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;
      return {
        x: centerX + radius * Math.cos(angleInRadians),
        y: centerY + radius * Math.sin(angleInRadians)
      };
    }
  }, {
    key: "escapeString",
    value: function escapeString(str) {
      var escapeWith = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'x';
      var newStr = str.toString().slice();
      newStr = newStr.replace(/[` ~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, escapeWith);
      return newStr;
    }
  }, {
    key: "negToZero",
    value: function negToZero(val) {
      return val < 0 ? 0 : val;
    }
  }, {
    key: "moveIndexInArray",
    value: function moveIndexInArray(arr, old_index, new_index) {
      if (new_index >= arr.length) {
        var k = new_index - arr.length + 1;

        while (k--) {
          arr.push(undefined);
        }
      }

      arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
      return arr;
    }
  }, {
    key: "extractNumber",
    value: function extractNumber(s) {
      return parseFloat(s.replace(/[^\d\.]*/g, ''));
    }
  }, {
    key: "randomString",
    value: function randomString(len) {
      var text = '';
      var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

      for (var i = 0; i < len; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
      }

      return text;
    }
  }, {
    key: "findAncestor",
    value: function findAncestor(el, cls) {
      while ((el = el.parentElement) && !el.classList.contains(cls)) {
      }

      return el;
    }
  }, {
    key: "setELstyles",
    value: function setELstyles(el, styles) {
      for (var key in styles) {
        if (styles.hasOwnProperty(key)) {
          el.style.key = styles[key];
        }
      }
    }
  }, {
    key: "isNumber",
    value: function isNumber(value) {
      return !isNaN(value) && parseFloat(Number(value)) === value && !isNaN(parseInt(value, 10));
    }
  }, {
    key: "isFloat",
    value: function isFloat(n) {
      return Number(n) === n && n % 1 !== 0;
    }
  }, {
    key: "isSafari",
    value: function isSafari() {
      return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    }
  }, {
    key: "isFirefox",
    value: function isFirefox() {
      return navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
    }
  }, {
    key: "isIE11",
    value: function isIE11() {
      if (window.navigator.userAgent.indexOf('MSIE') !== -1 || window.navigator.appVersion.indexOf('Trident/') > -1) {
        return true;
      }
    }
  }, {
    key: "isIE",
    value: function isIE() {
      var ua = window.navigator.userAgent;
      var msie = ua.indexOf('MSIE ');

      if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
      }

      var trident = ua.indexOf('Trident/');

      if (trident > 0) {
        // IE 11 => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
      }

      var edge = ua.indexOf('Edge/');

      if (edge > 0) {
        // Edge (IE 12+) => return version number
        return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
      } // other browser


      return false;
    }
  }]);

  return Utils;
}();

/**
 * ApexCharts Filters Class for setting hover/active states on the paths.
 *
 * @module Formatters
 **/

var Filters =
/*#__PURE__*/
function () {
  function Filters(ctx) {
    _classCallCheck(this, Filters);

    this.ctx = ctx;
    this.w = ctx.w;
  } // create a re-usable filter which can be appended other filter effects and applied to multiple elements


  _createClass(Filters, [{
    key: "getDefaultFilter",
    value: function getDefaultFilter(el, i) {
      var w = this.w;
      el.unfilter(true);
      var filter = new window.SVG.Filter();
      filter.size('120%', '180%', '-5%', '-40%');

      if (w.config.states.normal.filter !== 'none') {
        this.applyFilter(el, i, w.config.states.normal.filter.type, w.config.states.normal.filter.value);
      } else {
        if (w.config.chart.dropShadow.enabled) {
          this.dropShadow(el, w.config.chart.dropShadow, i);
        }
      }
    }
  }, {
    key: "addNormalFilter",
    value: function addNormalFilter(el, i) {
      var w = this.w;

      if (w.config.chart.dropShadow.enabled) {
        this.dropShadow(el, w.config.chart.dropShadow, i);
      }
    } // appends dropShadow to the filter object which can be chained with other filter effects

  }, {
    key: "addLightenFilter",
    value: function addLightenFilter(el, i, attrs) {
      var _this = this;

      var w = this.w;
      var intensity = attrs.intensity;

      if (Utils.isFirefox()) {
        return;
      }

      el.unfilter(true);
      var filter = new window.SVG.Filter();
      filter.size('120%', '180%', '-5%', '-40%');
      el.filter(function (add) {
        var shadowAttr = w.config.chart.dropShadow;

        if (shadowAttr.enabled) {
          filter = _this.addShadow(add, i, shadowAttr);
        } else {
          filter = add;
        }

        filter.componentTransfer({
          rgb: {
            type: 'linear',
            slope: 1.5,
            intercept: intensity
          }
        });
      });
      el.filterer.node.setAttribute('filterUnits', 'userSpaceOnUse');
    } // appends dropShadow to the filter object which can be chained with other filter effects

  }, {
    key: "addDarkenFilter",
    value: function addDarkenFilter(el, i, attrs) {
      var _this2 = this;

      var w = this.w;
      var intensity = attrs.intensity;

      if (Utils.isFirefox()) {
        return;
      }

      el.unfilter(true);
      var filter = new window.SVG.Filter();
      filter.size('120%', '180%', '-5%', '-40%');
      el.filter(function (add) {
        var shadowAttr = w.config.chart.dropShadow;

        if (shadowAttr.enabled) {
          filter = _this2.addShadow(add, i, shadowAttr);
        } else {
          filter = add;
        }

        filter.componentTransfer({
          rgb: {
            type: 'linear',
            slope: intensity
          }
        });
      });
      el.filterer.node.setAttribute('filterUnits', 'userSpaceOnUse');
    }
  }, {
    key: "applyFilter",
    value: function applyFilter(el, i, filter) {
      var intensity = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0.5;

      switch (filter) {
        case 'none':
          {
            this.addNormalFilter(el, i);
            break;
          }

        case 'lighten':
          {
            this.addLightenFilter(el, i, {
              intensity: intensity
            });
            break;
          }

        case 'darken':
          {
            this.addDarkenFilter(el, i, {
              intensity: intensity
            });
            break;
          }

        default:
          // do nothing
          break;
      }
    } // appends dropShadow to the filter object which can be chained with other filter effects

  }, {
    key: "addShadow",
    value: function addShadow(add, i, attrs) {
      var blur = attrs.blur,
          top = attrs.top,
          left = attrs.left,
          color = attrs.color,
          opacity = attrs.opacity;
      var shadowBlur = add.flood(Array.isArray(color) ? color[i] : color, opacity).composite(add.sourceAlpha, 'in').offset(left, top).gaussianBlur(blur).merge(add.source);
      return add.blend(add.source, shadowBlur);
    } // directly adds dropShadow to the element and returns the same element.
    // the only way it is different from the addShadow() function is that addShadow is chainable to other filters, while this function discards all filters and add dropShadow

  }, {
    key: "dropShadow",
    value: function dropShadow(el, attrs) {
      var i = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;
      var top = attrs.top,
          left = attrs.left,
          blur = attrs.blur,
          color = attrs.color,
          opacity = attrs.opacity,
          noUserSpaceOnUse = attrs.noUserSpaceOnUse;
      el.unfilter(true);
      color = Array.isArray(color) ? color[i] : color;
      var filter = new window.SVG.Filter();
      filter.size('120%', '180%', '-5%', '-40%');
      el.filter(function (add) {
        var shadowBlur = null;

        if (Utils.isSafari() || Utils.isFirefox() || Utils.isIE()) {
          // safari/firefox has some alternative way to use this filter
          shadowBlur = add.flood(color, opacity).composite(add.sourceAlpha, 'in').offset(left, top).gaussianBlur(blur);
        } else {
          shadowBlur = add.flood(color, opacity).composite(add.sourceAlpha, 'in').offset(left, top).gaussianBlur(blur).merge(add.source);
        }

        add.blend(add.source, shadowBlur);
      });

      if (!noUserSpaceOnUse) {
        el.filterer.node.setAttribute('filterUnits', 'userSpaceOnUse');
      }

      return el;
    }
  }, {
    key: "setSelectionFilter",
    value: function setSelectionFilter(el, realIndex, dataPointIndex) {
      var w = this.w;

      if (typeof w.globals.selectedDataPoints[realIndex] !== 'undefined') {
        if (w.globals.selectedDataPoints[realIndex].indexOf(dataPointIndex) > -1) {
          el.node.setAttribute('selected', true);
          var activeFilter = w.config.states.active.filter;

          if (activeFilter !== 'none') {
            this.applyFilter(el, realIndex, activeFilter.type, activeFilter.value);
          }
        }
      }
    }
  }]);

  return Filters;
}();

/**
 * ApexCharts Animation Class.
 *
 * @module Animations
 **/

var Animations =
/*#__PURE__*/
function () {
  function Animations(ctx) {
    _classCallCheck(this, Animations);

    this.ctx = ctx;
    this.w = ctx.w;
    this.setEasingFunctions();
  }

  _createClass(Animations, [{
    key: "setEasingFunctions",
    value: function setEasingFunctions() {
      var easing;
      var userDefinedEasing = this.w.config.chart.animations.easing;

      switch (userDefinedEasing) {
        case 'linear':
          {
            easing = '-';
            break;
          }

        case 'easein':
          {
            easing = '<';
            break;
          }

        case 'easeout':
          {
            easing = '>';
            break;
          }

        case 'easeinout':
          {
            easing = '<>';
            break;
          }

        case 'swing':
          {
            easing = function easing(pos) {
              var s = 1.70158;
              return (pos -= 1) * pos * ((s + 1) * pos + s) + 1;
            };

            break;
          }

        case 'bounce':
          {
            easing = function easing(pos) {
              if (pos < 1 / 2.75) {
                return 7.5625 * pos * pos;
              } else if (pos < 2 / 2.75) {
                return 7.5625 * (pos -= 1.5 / 2.75) * pos + 0.75;
              } else if (pos < 2.5 / 2.75) {
                return 7.5625 * (pos -= 2.25 / 2.75) * pos + 0.9375;
              } else {
                return 7.5625 * (pos -= 2.625 / 2.75) * pos + 0.984375;
              }
            };

            break;
          }

        case 'elastic':
          {
            easing = function easing(pos) {
              if (pos === !!pos) return pos;
              return Math.pow(2, -10 * pos) * Math.sin((pos - 0.075) * (2 * Math.PI) / 0.3) + 1;
            };

            break;
          }

        default:
          {
            easing = '<>';
          }
      }

      this.w.globals.easing = easing;
    }
  }, {
    key: "animateLine",
    value: function animateLine(el, from, to, speed) {
      el.attr(from).animate(speed).attr(to);
    }
    /*
     ** Animate radius of a circle element
     */

  }, {
    key: "animateCircleRadius",
    value: function animateCircleRadius(el, from, to, speed, easing) {
      if (!from) from = 0;
      el.attr({
        r: from
      }).animate(speed, easing).attr({
        r: to
      });
    }
    /*
     ** Animate radius and position of a circle element
     */

  }, {
    key: "animateCircle",
    value: function animateCircle(el, from, to, speed, easing) {
      el.attr({
        r: from.r,
        cx: from.cx,
        cy: from.cy
      }).animate(speed, easing).attr({
        r: to.r,
        cx: to.cx,
        cy: to.cy
      });
    }
    /*
     ** Animate rect properties
     */

  }, {
    key: "animateRect",
    value: function animateRect(el, from, to, speed, fn) {
      el.attr(from).animate(speed).attr(to).afterAll(function () {
        fn();
      });
    }
  }, {
    key: "animatePathsGradually",
    value: function animatePathsGradually(params) {
      var el = params.el,
          j = params.j,
          pathFrom = params.pathFrom,
          pathTo = params.pathTo,
          speed = params.speed,
          delay = params.delay,
          strokeWidth = params.strokeWidth;
      var me = this;
      var w = this.w;
      var delayFactor = 0;

      if (w.config.chart.animations.animateGradually.enabled) {
        delayFactor = w.config.chart.animations.animateGradually.delay;
      }

      if (w.config.chart.animations.dynamicAnimation.enabled && w.globals.dataChanged) {
        delayFactor = 0;
      }

      me.morphSVG(el, j, pathFrom, pathTo, speed, strokeWidth, delay * delayFactor);
    }
  }, {
    key: "showDelayedElements",
    value: function showDelayedElements() {
      this.w.globals.delayedElements.forEach(function (d) {
        var ele = d.el;
        ele.classList.remove('hidden');
      });
    } // SVG.js animation for morphing one path to another

  }, {
    key: "morphSVG",
    value: function morphSVG(el, j, pathFrom, pathTo, speed, strokeWidth, delay) {
      var _this = this;

      var w = this.w;

      if (!pathFrom) {
        pathFrom = el.attr('pathFrom');
      }

      if (!pathTo) {
        pathTo = el.attr('pathTo');
      }

      if (!pathFrom || pathFrom.indexOf('undefined') > -1 || pathFrom.indexOf('NaN') > -1) {
        pathFrom = "M 0 ".concat(w.globals.gridHeight);
        speed = 1;
      }

      if (pathTo.indexOf('undefined') > -1 || pathTo.indexOf('NaN') > -1) {
        pathTo = "M 0 ".concat(w.globals.gridHeight);
        speed = 1;
      }

      if (!w.globals.shouldAnimate) {
        speed = 1;
      }

      el.plot(pathFrom).animate(1, w.globals.easing, delay).plot(pathFrom).animate(speed, w.globals.easing, delay).plot(pathTo).afterAll(function () {
        // a flag to indicate that the original mount function can return true now as animation finished here
        if (Utils.isNumber(j)) {
          if (j === w.globals.series[w.globals.maxValsInArrayIndex].length - 2 && w.globals.shouldAnimate) {
            w.globals.animationEnded = true;
          }
        } else if (w.globals.shouldAnimate) {
          w.globals.animationEnded = true;

          if (typeof w.config.chart.events.animationEnd === 'function') {
            w.config.chart.events.animationEnd(_this.ctx, w);
          }
        }

        _this.showDelayedElements();
      });
    }
  }]);

  return Animations;
}();

/**
 * ApexCharts Graphics Class for all drawing operations.
 *
 * @module Graphics
 **/

var Graphics =
/*#__PURE__*/
function () {
  function Graphics(ctx) {
    _classCallCheck(this, Graphics);

    this.ctx = ctx;
    this.w = ctx.w;
  }

  _createClass(Graphics, [{
    key: "drawLine",
    value: function drawLine(x1, y1, x2, y2) {
      var lineColor = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : '#a8a8a8';
      var dashArray = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : 0;
      var strokeWidth = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : null;
      var w = this.w;
      var line = w.globals.dom.Paper.line().attr({
        x1: x1,
        y1: y1,
        x2: x2,
        y2: y2,
        stroke: lineColor,
        'stroke-dasharray': dashArray,
        'stroke-width': strokeWidth
      });
      return line;
    }
  }, {
    key: "drawRect",
    value: function drawRect() {
      var x1 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;
      var y1 = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
      var x2 = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;
      var y2 = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0;
      var radius = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 0;
      var color = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : '#fefefe';
      var opacity = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : 1;
      var strokeWidth = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : null;
      var strokeColor = arguments.length > 8 && arguments[8] !== undefined ? arguments[8] : null;
      var strokeDashArray = arguments.length > 9 && arguments[9] !== undefined ? arguments[9] : 0;
      var w = this.w;
      var rect = w.globals.dom.Paper.rect();
      rect.attr({
        x: x1,
        y: y1,
        width: x2 > 0 ? x2 : 0,
        height: y2 > 0 ? y2 : 0,
        rx: radius,
        ry: radius,
        fill: color,
        opacity: opacity,
        'stroke-width': strokeWidth !== null ? strokeWidth : 0,
        stroke: strokeColor !== null ? strokeColor : 'none',
        'stroke-dasharray': strokeDashArray
      });
      return rect;
    }
  }, {
    key: "drawPolygon",
    value: function drawPolygon(polygonString) {
      var stroke = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '#e1e1e1';
      var fill = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'none';
      var w = this.w;
      var polygon = w.globals.dom.Paper.polygon(polygonString).attr({
        fill: fill,
        stroke: stroke
      });
      return polygon;
    }
  }, {
    key: "drawCircle",
    value: function drawCircle(radius) {
      var attrs = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      var w = this.w;
      var c = w.globals.dom.Paper.circle(radius * 2);

      if (attrs !== null) {
        c.attr(attrs);
      }

      return c;
    }
  }, {
    key: "drawPath",
    value: function drawPath(_ref) {
      var _ref$d = _ref.d,
          d = _ref$d === void 0 ? '' : _ref$d,
          _ref$stroke = _ref.stroke,
          stroke = _ref$stroke === void 0 ? '#a8a8a8' : _ref$stroke,
          _ref$strokeWidth = _ref.strokeWidth,
          strokeWidth = _ref$strokeWidth === void 0 ? 1 : _ref$strokeWidth,
          fill = _ref.fill,
          _ref$fillOpacity = _ref.fillOpacity,
          fillOpacity = _ref$fillOpacity === void 0 ? 1 : _ref$fillOpacity,
          _ref$strokeOpacity = _ref.strokeOpacity,
          strokeOpacity = _ref$strokeOpacity === void 0 ? 1 : _ref$strokeOpacity,
          classes = _ref.classes,
          _ref$strokeLinecap = _ref.strokeLinecap,
          strokeLinecap = _ref$strokeLinecap === void 0 ? null : _ref$strokeLinecap,
          _ref$strokeDashArray = _ref.strokeDashArray,
          strokeDashArray = _ref$strokeDashArray === void 0 ? 0 : _ref$strokeDashArray;
      var w = this.w;

      if (strokeLinecap === null) {
        strokeLinecap = w.config.stroke.lineCap;
      }

      if (d.indexOf('undefined') > -1 || d.indexOf('NaN') > -1) {
        d = "M 0 ".concat(w.globals.gridHeight);
      }

      var p = w.globals.dom.Paper.path(d).attr({
        fill: fill,
        'fill-opacity': fillOpacity,
        stroke: stroke,
        'stroke-opacity': strokeOpacity,
        'stroke-linecap': strokeLinecap,
        'stroke-width': strokeWidth,
        'stroke-dasharray': strokeDashArray,
        class: classes
      });
      return p;
    }
  }, {
    key: "group",
    value: function group() {
      var attrs = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var w = this.w;
      var g = w.globals.dom.Paper.group();

      if (attrs !== null) {
        g.attr(attrs);
      }

      return g;
    }
  }, {
    key: "move",
    value: function move(x, y) {
      var move = ['M', x, y].join(' ');
      return move;
    }
  }, {
    key: "line",
    value: function line(x, y) {
      var hORv = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
      var line = null;

      if (hORv === null) {
        line = ['L', x, y].join(' ');
      } else if (hORv === 'H') {
        line = ['H', x].join(' ');
      } else if (hORv === 'V') {
        line = ['V', y].join(' ');
      }

      return line;
    }
  }, {
    key: "curve",
    value: function curve(x1, y1, x2, y2, x, y) {
      var curve = ['C', x1, y1, x2, y2, x, y].join(' ');
      return curve;
    }
  }, {
    key: "quadraticCurve",
    value: function quadraticCurve(x1, y1, x, y) {
      var curve = ['Q', x1, y1, x, y].join(' ');
      return curve;
    }
  }, {
    key: "arc",
    value: function arc(rx, ry, axisRotation, largeArcFlag, sweepFlag, x, y) {
      var relative = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : false;
      var coord = 'A';
      if (relative) coord = 'a';
      var arc = [coord, rx, ry, axisRotation, largeArcFlag, sweepFlag, x, y].join(' ');
      return arc;
    }
    /**
     * @memberof Graphics
     * @param {object}
     *  i = series's index
     *  realIndex = realIndex is series's actual index when it was drawn time. After several redraws, the iterating "i" may change in loops, but realIndex doesn't
     *  pathFrom = existing pathFrom to animateTo
     *  pathTo = new Path to which d attr will be animated from pathFrom to pathTo
     *  stroke = line Color
     *  strokeWidth = width of path Line
     *  fill = it can be gradient, single color, pattern or image
     *  animationDelay = how much to delay when starting animation (in milliseconds)
     *  dataChangeSpeed = for dynamic animations, when data changes
     *  className = class attribute to add
     * @return {object} svg.js path object
     **/

  }, {
    key: "renderPaths",
    value: function renderPaths(_ref2) {
      var i = _ref2.i,
          j = _ref2.j,
          realIndex = _ref2.realIndex,
          pathFrom = _ref2.pathFrom,
          pathTo = _ref2.pathTo,
          stroke = _ref2.stroke,
          strokeWidth = _ref2.strokeWidth,
          strokeLinecap = _ref2.strokeLinecap,
          fill = _ref2.fill,
          animationDelay = _ref2.animationDelay,
          initialSpeed = _ref2.initialSpeed,
          dataChangeSpeed = _ref2.dataChangeSpeed,
          className = _ref2.className,
          id = _ref2.id,
          _ref2$shouldClipToGri = _ref2.shouldClipToGrid,
          shouldClipToGrid = _ref2$shouldClipToGri === void 0 ? true : _ref2$shouldClipToGri,
          _ref2$bindEventsOnPat = _ref2.bindEventsOnPaths,
          bindEventsOnPaths = _ref2$bindEventsOnPat === void 0 ? true : _ref2$bindEventsOnPat,
          _ref2$drawShadow = _ref2.drawShadow,
          drawShadow = _ref2$drawShadow === void 0 ? true : _ref2$drawShadow;
      var w = this.w;
      var filters = new Filters(this.ctx);
      var anim = new Animations(this.ctx);
      var initialAnim = this.w.config.chart.animations.enabled;
      var dynamicAnim = initialAnim && this.w.config.chart.animations.dynamicAnimation.enabled;
      var d;
      var shouldAnimate = !!(initialAnim && !w.globals.resized || dynamicAnim && w.globals.dataChanged && w.globals.shouldAnimate);

      if (shouldAnimate) {
        d = pathFrom;
      } else {
        d = pathTo;
        this.w.globals.animationEnded = true;
      }

      var strokeDashArrayOpt = w.config.stroke.dashArray;
      var strokeDashArray = 0;

      if (Array.isArray(strokeDashArrayOpt)) {
        strokeDashArray = strokeDashArrayOpt[realIndex];
      } else {
        strokeDashArray = w.config.stroke.dashArray;
      }

      var el = this.drawPath({
        d: d,
        stroke: stroke,
        strokeWidth: strokeWidth,
        fill: fill,
        fillOpacity: 1,
        classes: className,
        strokeLinecap: strokeLinecap,
        strokeDashArray: strokeDashArray
      });
      el.attr('id', "".concat(id, "-").concat(i));
      el.attr('index', realIndex);

      if (shouldClipToGrid) {
        el.attr({
          'clip-path': "url(#gridRectMask".concat(w.globals.cuid, ")")
        });
      } // const defaultFilter = el.filterer


      if (w.config.states.normal.filter.type !== 'none') {
        filters.getDefaultFilter(el, realIndex);
      } else {
        if (w.config.chart.dropShadow.enabled && drawShadow) {
          if (!w.config.chart.dropShadow.enabledSeries || w.config.chart.dropShadow.enabledSeries && w.config.chart.dropShadow.enabledSeries.indexOf(realIndex) !== -1) {
            var shadow = w.config.chart.dropShadow;
            filters.dropShadow(el, shadow, realIndex);
          }
        }
      }

      if (bindEventsOnPaths) {
        el.node.addEventListener('mouseenter', this.pathMouseEnter.bind(this, el));
        el.node.addEventListener('mouseleave', this.pathMouseLeave.bind(this, el));
        el.node.addEventListener('mousedown', this.pathMouseDown.bind(this, el));
      }

      el.attr({
        pathTo: pathTo,
        pathFrom: pathFrom
      });
      var defaultAnimateOpts = {
        el: el,
        j: j,
        pathFrom: pathFrom,
        pathTo: pathTo,
        strokeWidth: strokeWidth
      };

      if (initialAnim && !w.globals.resized && !w.globals.dataChanged) {
        anim.animatePathsGradually(_objectSpread({}, defaultAnimateOpts, {
          speed: initialSpeed,
          delay: animationDelay
        }));
      } else {
        if (w.globals.resized || !w.globals.dataChanged) {
          anim.showDelayedElements();
        }
      }

      if (w.globals.dataChanged && dynamicAnim && shouldAnimate) {
        anim.animatePathsGradually(_objectSpread({}, defaultAnimateOpts, {
          speed: dataChangeSpeed
        }));
      }

      return el;
    }
  }, {
    key: "drawPattern",
    value: function drawPattern(style, width, height) {
      var stroke = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '#a8a8a8';
      var strokeWidth = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 0;
      var w = this.w;
      var p = w.globals.dom.Paper.pattern(width, height, function (add) {
        if (style === 'horizontalLines') {
          add.line(0, 0, height, 0).stroke({
            color: stroke,
            width: strokeWidth + 1
          });
        } else if (style === 'verticalLines') {
          add.line(0, 0, 0, width).stroke({
            color: stroke,
            width: strokeWidth + 1
          });
        } else if (style === 'slantedLines') {
          add.line(0, 0, width, height).stroke({
            color: stroke,
            width: strokeWidth
          });
        } else if (style === 'squares') {
          add.rect(width, height).fill('none').stroke({
            color: stroke,
            width: strokeWidth
          });
        } else if (style === 'circles') {
          add.circle(width).fill('none').stroke({
            color: stroke,
            width: strokeWidth
          });
        }
      });
      return p;
    }
  }, {
    key: "drawGradient",
    value: function drawGradient(style, gfrom, gto, opacityFrom, opacityTo) {
      var size = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : null;
      var stops = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : null;
      var colorStops = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : null;
      var i = arguments.length > 8 && arguments[8] !== undefined ? arguments[8] : 0;
      var w = this.w;
      var g;
      gfrom = Utils.hexToRgba(gfrom, opacityFrom);
      gto = Utils.hexToRgba(gto, opacityTo);
      var stop1 = 0;
      var stop2 = 1;
      var stop3 = 1;
      var stop4 = null;

      if (stops !== null) {
        stop1 = typeof stops[0] !== 'undefined' ? stops[0] / 100 : 0;
        stop2 = typeof stops[1] !== 'undefined' ? stops[1] / 100 : 1;
        stop3 = typeof stops[2] !== 'undefined' ? stops[2] / 100 : 1;
        stop4 = typeof stops[3] !== 'undefined' ? stops[3] / 100 : null;
      }

      var radial = !!(w.config.chart.type === 'donut' || w.config.chart.type === 'pie' || w.config.chart.type === 'bubble');

      if (colorStops === null || colorStops.length === 0) {
        g = w.globals.dom.Paper.gradient(radial ? 'radial' : 'linear', function (stop) {
          stop.at(stop1, gfrom, opacityFrom);
          stop.at(stop2, gto, opacityTo);
          stop.at(stop3, gto, opacityTo);

          if (stop4 !== null) {
            stop.at(stop4, gfrom, opacityFrom);
          }
        });
      } else {
        g = w.globals.dom.Paper.gradient(radial ? 'radial' : 'linear', function (stop) {
          var stops = Array.isArray(colorStops[i]) ? colorStops[i] : colorStops;
          stops.forEach(function (s) {
            stop.at(s.offset / 100, s.color, s.opacity);
          });
        });
      }

      if (!radial) {
        if (style === 'vertical') {
          g.from(0, 0).to(0, 1);
        } else if (style === 'diagonal') {
          g.from(0, 0).to(1, 1);
        } else if (style === 'horizontal') {
          g.from(0, 1).to(1, 1);
        } else if (style === 'diagonal2') {
          g.from(0, 1).to(2, 2);
        }
      } else {
        var offx = w.globals.gridWidth / 2;
        var offy = w.globals.gridHeight / 2;

        if (w.config.chart.type !== 'bubble') {
          g.attr({
            gradientUnits: 'userSpaceOnUse',
            cx: offx,
            cy: offy,
            r: size
          });
        } else {
          g.attr({
            cx: 0.5,
            cy: 0.5,
            r: 0.8,
            fx: 0.2,
            fy: 0.2
          });
        }
      }

      return g;
    }
  }, {
    key: "drawText",
    value: function drawText(opts) {
      var w = this.w;
      var x = opts.x,
          y = opts.y,
          text = opts.text,
          textAnchor = opts.textAnchor,
          fontSize = opts.fontSize,
          fontFamily = opts.fontFamily,
          foreColor = opts.foreColor,
          opacity = opts.opacity;
      if (typeof text === 'undefined') text = '';

      if (!textAnchor) {
        textAnchor = 'start';
      }

      if (!foreColor) {
        foreColor = w.config.chart.foreColor;
      }

      fontFamily = fontFamily || w.config.chart.fontFamily;
      var elText;

      if (Array.isArray(text)) {
        elText = w.globals.dom.Paper.text(function (add) {
          for (var i = 0; i < text.length; i++) {
            add.tspan(text[i]);
          }
        });
      } else {
        elText = w.globals.dom.Paper.plain(text);
      }

      elText.attr({
        x: x,
        y: y,
        'text-anchor': textAnchor,
        'dominant-baseline': 'auto',
        'font-size': fontSize,
        'font-family': fontFamily,
        fill: foreColor,
        class: 'apexcharts-text ' + opts.cssClass ? opts.cssClass : ''
      });
      elText.node.style.fontFamily = fontFamily;
      elText.node.style.opacity = opacity;
      return elText;
    }
  }, {
    key: "addTspan",
    value: function addTspan(textEl, text, fontFamily) {
      var tspan = textEl.tspan(text);

      if (!fontFamily) {
        fontFamily = this.w.config.chart.fontFamily;
      }

      tspan.node.style.fontFamily = fontFamily;
    }
  }, {
    key: "drawMarker",
    value: function drawMarker(x, y, opts) {
      x = x || 0;
      var size = opts.pSize || 0;
      var elPoint = null;

      if (opts.shape === 'square') {
        var radius = opts.pRadius === undefined ? size / 2 : opts.pRadius;

        if (y === null) {
          size = 0;
          radius = 0;
        }

        var nSize = size * 1.2 + radius;
        var p = this.drawRect(nSize, nSize, nSize, nSize, radius);
        p.attr({
          x: x - nSize / 2,
          y: y - nSize / 2,
          cx: x,
          cy: y,
          class: opts.class ? opts.class : '',
          fill: opts.pointFillColor,
          'fill-opacity': opts.pointFillOpacity ? opts.pointFillOpacity : 1,
          stroke: opts.pointStrokeColor,
          'stroke-width': opts.pWidth ? opts.pWidth : 0,
          'stroke-opacity': opts.pointStrokeOpacity ? opts.pointStrokeOpacity : 1
        });
        elPoint = p;
      } else if (opts.shape === 'circle') {
        if (!Utils.isNumber(y)) {
          size = 0;
          y = 0;
        } // let nSize = size - opts.pRadius / 2 < 0 ? 0 : size - opts.pRadius / 2


        elPoint = this.drawCircle(size, {
          cx: x,
          cy: y,
          class: opts.class ? opts.class : '',
          stroke: opts.pointStrokeColor,
          fill: opts.pointFillColor,
          'fill-opacity': opts.pointFillOpacity ? opts.pointFillOpacity : 1,
          'stroke-width': opts.pWidth ? opts.pWidth : 0,
          'stroke-opacity': opts.pointStrokeOpacity ? opts.pointStrokeOpacity : 1
        });
      }

      return elPoint;
    }
  }, {
    key: "pathMouseEnter",
    value: function pathMouseEnter(path, e) {
      var w = this.w;
      var filters = new Filters(this.ctx);
      var i = parseInt(path.node.getAttribute('index'));
      var j = parseInt(path.node.getAttribute('j'));

      if (typeof w.config.chart.events.dataPointMouseEnter === 'function') {
        w.config.chart.events.dataPointMouseEnter(e, this.ctx, {
          seriesIndex: i,
          dataPointIndex: j,
          w: w
        });
      }

      this.ctx.fireEvent('dataPointMouseEnter', [e, this.ctx, {
        seriesIndex: i,
        dataPointIndex: j,
        w: w
      }]);

      if (w.config.states.active.filter.type !== 'none') {
        if (path.node.getAttribute('selected') === 'true') {
          return;
        }
      }

      if (w.config.states.hover.filter.type !== 'none') {
        if (w.config.states.active.filter.type !== 'none' && !w.globals.isTouchDevice) {
          var hoverFilter = w.config.states.hover.filter;
          filters.applyFilter(path, i, hoverFilter.type, hoverFilter.value);
        }
      }
    }
  }, {
    key: "pathMouseLeave",
    value: function pathMouseLeave(path, e) {
      var w = this.w;
      var filters = new Filters(this.ctx);
      var i = parseInt(path.node.getAttribute('index'));
      var j = parseInt(path.node.getAttribute('j'));

      if (typeof w.config.chart.events.dataPointMouseLeave === 'function') {
        w.config.chart.events.dataPointMouseLeave(e, this.ctx, {
          seriesIndex: i,
          dataPointIndex: j,
          w: w
        });
      }

      this.ctx.fireEvent('dataPointMouseLeave', [e, this.ctx, {
        seriesIndex: i,
        dataPointIndex: j,
        w: w
      }]);

      if (w.config.states.active.filter.type !== 'none') {
        if (path.node.getAttribute('selected') === 'true') {
          return;
        }
      }

      if (w.config.states.hover.filter.type !== 'none') {
        filters.getDefaultFilter(path, i);
      }
    }
  }, {
    key: "pathMouseDown",
    value: function pathMouseDown(path, e) {
      var w = this.w;
      var filters = new Filters(this.ctx);
      var i = parseInt(path.node.getAttribute('index'));
      var j = parseInt(path.node.getAttribute('j'));
      var selected = 'false';

      if (path.node.getAttribute('selected') === 'true') {
        path.node.setAttribute('selected', 'false');

        if (w.globals.selectedDataPoints[i].indexOf(j) > -1) {
          var index = w.globals.selectedDataPoints[i].indexOf(j);
          w.globals.selectedDataPoints[i].splice(index, 1);
        }
      } else {
        if (!w.config.states.active.allowMultipleDataPointsSelection && w.globals.selectedDataPoints.length > 0) {
          w.globals.selectedDataPoints = [];
          var elPaths = w.globals.dom.Paper.select('.apexcharts-series path').members;
          var elCircles = w.globals.dom.Paper.select('.apexcharts-series circle, .apexcharts-series rect').members;
          elPaths.forEach(function (elPath) {
            elPath.node.setAttribute('selected', 'false');
            filters.getDefaultFilter(elPath, i);
          });
          elCircles.forEach(function (circle) {
            circle.node.setAttribute('selected', 'false');
            filters.getDefaultFilter(circle, i);
          });
        }

        path.node.setAttribute('selected', 'true');
        selected = 'true';

        if (typeof w.globals.selectedDataPoints[i] === 'undefined') {
          w.globals.selectedDataPoints[i] = [];
        }

        w.globals.selectedDataPoints[i].push(j);
      }

      if (selected === 'true') {
        var activeFilter = w.config.states.active.filter;

        if (activeFilter !== 'none') {
          filters.applyFilter(path, i, activeFilter.type, activeFilter.value);
        }
      } else {
        if (w.config.states.active.filter.type !== 'none') {
          filters.getDefaultFilter(path, i);
        }
      }

      if (typeof w.config.chart.events.dataPointSelection === 'function') {
        w.config.chart.events.dataPointSelection(e, this.ctx, {
          selectedDataPoints: w.globals.selectedDataPoints,
          seriesIndex: i,
          dataPointIndex: j,
          w: w
        });
      }

      this.ctx.fireEvent('dataPointSelection', [e, this.ctx, {
        selectedDataPoints: w.globals.selectedDataPoints,
        seriesIndex: i,
        dataPointIndex: j,
        w: w
      }]); // if (this.w.config.chart.selection.selectedPoints !== undefined) {
      //   this.w.config.chart.selection.selectedPoints(w.globals.selectedDataPoints)
      // }
    }
  }, {
    key: "rotateAroundCenter",
    value: function rotateAroundCenter(el) {
      var coord = el.getBBox();
      var x = coord.x + coord.width / 2;
      var y = coord.y + coord.height / 2;
      return {
        x: x,
        y: y
      };
    }
  }, {
    key: "getTextRects",
    value: function getTextRects(text, fontSize, fontFamily, transform) {
      var useBBox = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;
      var w = this.w;
      var virtualText = this.drawText({
        x: -200,
        y: -200,
        text: text,
        textAnchor: 'start',
        fontSize: fontSize,
        fontFamily: fontFamily,
        foreColor: '#fff',
        opacity: 0
      });

      if (transform) {
        virtualText.attr('transform', transform);
      }

      w.globals.dom.Paper.add(virtualText);
      var rect = virtualText.bbox();

      if (!useBBox) {
        rect = virtualText.node.getBoundingClientRect();
      }

      virtualText.remove();
      return {
        width: rect.width,
        height: rect.height
      };
    }
    /**
     * append ... to long text
     * http://stackoverflow.com/questions/9241315/trimming-text-to-a-given-pixel-width-in-svg
     * @memberof Graphics
     **/

  }, {
    key: "placeTextWithEllipsis",
    value: function placeTextWithEllipsis(textObj, textString, width) {
      textObj.textContent = textString;

      if (textString.length > 0) {
        // ellipsis is needed
        if (textObj.getSubStringLength(0, textString.length) >= width) {
          for (var x = textString.length - 3; x > 0; x -= 3) {
            if (textObj.getSubStringLength(0, x) <= width) {
              textObj.textContent = textString.substring(0, x) + '...';
              return;
            }
          }

          textObj.textContent = '...'; // can't place at all
        }
      }
    }
  }], [{
    key: "setAttrs",
    value: function setAttrs(el, attrs) {
      for (var key in attrs) {
        if (attrs.hasOwnProperty(key)) {
          el.setAttribute(key, attrs[key]);
        }
      }
    }
  }]);

  return Graphics;
}();

const name = "en";
const options = {
	months: [
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December"
	],
	shortMonths: [
		"Jan",
		"Feb",
		"Mar",
		"Apr",
		"May",
		"Jun",
		"Jul",
		"Aug",
		"Sep",
		"Oct",
		"Nov",
		"Dec"
	],
	days: [
		"Sunday",
		"Monday",
		"Tuesday",
		"Wednesday",
		"Thursday",
		"Friday",
		"Saturday"
	],
	shortDays: [
		"Sun",
		"Mon",
		"Tue",
		"Wed",
		"Thu",
		"Fri",
		"Sat"
	],
	toolbar: {
		exportToSVG: "Download SVG",
		exportToPNG: "Download PNG",
		menu: "Menu",
		selection: "Selection",
		selectionZoom: "Selection Zoom",
		zoomIn: "Zoom In",
		zoomOut: "Zoom Out",
		pan: "Panning",
		reset: "Reset Zoom"
	}
};
var en = {
	name: name,
	options: options
};

var Options =
/*#__PURE__*/
function () {
  function Options() {
    _classCallCheck(this, Options);

    this.yAxis = {
      show: true,
      showAlways: false,
      seriesName: undefined,
      opposite: false,
      reversed: false,
      logarithmic: false,
      tickAmount: undefined,
      forceNiceScale: false,
      max: undefined,
      min: undefined,
      floating: false,
      decimalsInFloat: undefined,
      labels: {
        show: true,
        minWidth: 0,
        maxWidth: 160,
        offsetX: 0,
        offsetY: 0,
        align: undefined,
        rotate: 0,
        padding: 20,
        style: {
          colors: [],
          fontSize: '11px',
          fontFamily: undefined,
          cssClass: ''
        },
        formatter: undefined
      },
      axisBorder: {
        show: false,
        color: '#78909C',
        offsetX: 0,
        offsetY: 0
      },
      axisTicks: {
        show: false,
        color: '#78909C',
        width: 6,
        offsetX: 0,
        offsetY: 0
      },
      title: {
        text: undefined,
        rotate: 90,
        offsetY: 0,
        offsetX: 0,
        style: {
          color: undefined,
          fontSize: '11px',
          fontFamily: undefined,
          cssClass: ''
        }
      },
      tooltip: {
        enabled: false,
        offsetX: 0
      },
      crosshairs: {
        show: true,
        position: 'front',
        stroke: {
          color: '#b6b6b6',
          width: 1,
          dashArray: 0
        }
      }
    };
    this.xAxisAnnotation = {
      x: 0,
      x2: null,
      strokeDashArray: 1,
      fillColor: '#c2c2c2',
      borderColor: '#c2c2c2',
      opacity: 0.3,
      offsetX: 0,
      offsetY: 0,
      label: {
        borderColor: '#c2c2c2',
        borderWidth: 1,
        text: undefined,
        textAnchor: 'middle',
        orientation: 'vertical',
        position: 'top',
        offsetX: 0,
        offsetY: 0,
        style: {
          background: '#fff',
          color: undefined,
          fontSize: '11px',
          fontFamily: undefined,
          cssClass: '',
          padding: {
            left: 5,
            right: 5,
            top: 2,
            bottom: 2
          }
        }
      }
    };
    this.yAxisAnnotation = {
      y: 0,
      y2: null,
      strokeDashArray: 1,
      fillColor: '#c2c2c2',
      borderColor: '#c2c2c2',
      opacity: 0.3,
      offsetX: 0,
      offsetY: 0,
      yAxisIndex: 0,
      label: {
        borderColor: '#c2c2c2',
        borderWidth: 1,
        text: undefined,
        textAnchor: 'end',
        position: 'right',
        offsetX: 0,
        offsetY: -3,
        style: {
          background: '#fff',
          color: undefined,
          fontSize: '11px',
          fontFamily: undefined,
          cssClass: '',
          padding: {
            left: 5,
            right: 5,
            top: 0,
            bottom: 2
          }
        }
      }
    };
    this.pointAnnotation = {
      x: 0,
      y: null,
      yAxisIndex: 0,
      seriesIndex: 0,
      marker: {
        size: 0,
        fillColor: '#fff',
        strokeWidth: 2,
        strokeColor: '#333',
        shape: 'circle',
        offsetX: 0,
        offsetY: 0,
        radius: 2,
        cssClass: ''
      },
      label: {
        borderColor: '#c2c2c2',
        borderWidth: 1,
        text: undefined,
        textAnchor: 'middle',
        offsetX: 0,
        offsetY: -15,
        style: {
          background: '#fff',
          color: undefined,
          fontSize: '11px',
          fontFamily: undefined,
          cssClass: '',
          padding: {
            left: 5,
            right: 5,
            top: 0,
            bottom: 2
          }
        }
      },
      customSVG: {
        SVG: undefined,
        cssClass: undefined,
        offsetX: 0,
        offsetY: 0
      }
    };
  }

  _createClass(Options, [{
    key: "init",
    value: function init() {
      return {
        annotations: {
          position: 'front',
          yaxis: [this.yAxisAnnotation],
          xaxis: [this.xAxisAnnotation],
          points: [this.pointAnnotation]
        },
        chart: {
          animations: {
            enabled: true,
            easing: 'easeinout',
            // linear, easeout, easein, easeinout, swing, bounce, elastic
            speed: 800,
            animateGradually: {
              delay: 150,
              enabled: true
            },
            dynamicAnimation: {
              enabled: true,
              speed: 350
            }
          },
          background: 'transparent',
          locales: [en],
          defaultLocale: 'en',
          dropShadow: {
            enabled: false,
            enabledSeries: undefined,
            top: 2,
            left: 2,
            blur: 4,
            color: '#000',
            opacity: 0.35
          },
          events: {
            animationEnd: undefined,
            beforeMount: undefined,
            mounted: undefined,
            updated: undefined,
            click: undefined,
            legendClick: undefined,
            markerClick: undefined,
            selection: undefined,
            dataPointSelection: undefined,
            dataPointMouseEnter: undefined,
            dataPointMouseLeave: undefined,
            beforeZoom: undefined,
            zoomed: undefined,
            scrolled: undefined
          },
          foreColor: '#373d3f',
          fontFamily: 'Helvetica, Arial, sans-serif',
          height: 'auto',
          parentHeightOffset: 15,
          id: undefined,
          group: undefined,
          offsetX: 0,
          offsetY: 0,
          selection: {
            enabled: false,
            type: 'x',
            // selectedPoints: undefined, // default datapoints that should be selected automatically
            fill: {
              color: '#24292e',
              opacity: 0.1
            },
            stroke: {
              width: 1,
              color: '#24292e',
              opacity: 0.4,
              dashArray: 3
            },
            xaxis: {
              min: undefined,
              max: undefined
            },
            yaxis: {
              min: undefined,
              max: undefined
            }
          },
          sparkline: {
            enabled: false
          },
          brush: {
            enabled: false,
            autoScaleYaxis: false,
            target: undefined
          },
          stacked: false,
          stackType: 'normal',
          toolbar: {
            show: true,
            tools: {
              download: true,
              selection: true,
              zoom: true,
              zoomin: true,
              zoomout: true,
              pan: true,
              reset: true,
              customIcons: []
            },
            autoSelected: 'zoom' // accepts -> zoom, pan, selection

          },
          type: 'line',
          width: '100%',
          zoom: {
            enabled: true,
            type: 'x',
            // autoScaleYaxis: false, // TODO: rewrite the autoScaleY function
            zoomedArea: {
              fill: {
                color: '#90CAF9',
                opacity: 0.4
              },
              stroke: {
                color: '#0D47A1',
                opacity: 0.4,
                width: 1
              }
            }
          }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '70%',
            // should be in percent 0 - 100
            barHeight: '70%',
            // should be in percent 0 - 100
            distributed: false,
            endingShape: 'flat',
            colors: {
              ranges: [],
              backgroundBarColors: [],
              backgroundBarOpacity: 1
            },
            dataLabels: {
              maxItems: 100,
              hideOverflowingLabels: true,
              position: 'top' // top, center, bottom
              // TODO: provide stackedLabels for stacked charts which gives additions of values

            }
          },
          candlestick: {
            colors: {
              upward: '#00B746',
              downward: '#EF403C'
            },
            wick: {
              useFillColor: true
            }
          },
          heatmap: {
            radius: 2,
            enableShades: true,
            shadeIntensity: 0.5,
            distributed: false,
            colorScale: {
              inverse: false,
              ranges: [],
              min: undefined,
              max: undefined
            }
          },
          radialBar: {
            size: undefined,
            inverseOrder: false,
            startAngle: 0,
            endAngle: 360,
            offsetX: 0,
            offsetY: 0,
            hollow: {
              margin: 5,
              size: '50%',
              background: 'transparent',
              image: undefined,
              imageWidth: 150,
              imageHeight: 150,
              imageOffsetX: 0,
              imageOffsetY: 0,
              imageClipped: true,
              position: 'front',
              dropShadow: {
                enabled: false,
                top: 0,
                left: 0,
                blur: 3,
                color: '#000',
                opacity: 0.5
              }
            },
            track: {
              show: true,
              startAngle: undefined,
              endAngle: undefined,
              background: '#f2f2f2',
              strokeWidth: '97%',
              opacity: 1,
              margin: 5,
              // margin is in pixels
              dropShadow: {
                enabled: false,
                top: 0,
                left: 0,
                blur: 3,
                color: '#000',
                opacity: 0.5
              }
            },
            dataLabels: {
              show: true,
              name: {
                show: true,
                fontSize: '16px',
                fontFamily: undefined,
                color: undefined,
                offsetY: 0
              },
              value: {
                show: true,
                fontSize: '14px',
                fontFamily: undefined,
                color: undefined,
                offsetY: 16,
                formatter: function formatter(val) {
                  return val + '%';
                }
              },
              total: {
                show: false,
                label: 'Total',
                color: undefined,
                formatter: function formatter(w) {
                  return w.globals.seriesTotals.reduce(function (a, b) {
                    return a + b;
                  }, 0) / w.globals.series.length + '%';
                }
              }
            }
          },
          rangeBar: {},
          pie: {
            size: undefined,
            customScale: 1,
            offsetX: 0,
            offsetY: 0,
            expandOnClick: true,
            dataLabels: {
              // These are the percentage values which are displayed on slice
              offset: 0,
              // offset by which labels will move outside
              minAngleToShowLabel: 10
            },
            donut: {
              size: '65%',
              background: 'transparent',
              labels: {
                // These are the inner labels appearing inside donut
                show: false,
                name: {
                  show: true,
                  fontSize: '16px',
                  fontFamily: undefined,
                  color: undefined,
                  offsetY: -10
                },
                value: {
                  show: true,
                  fontSize: '20px',
                  fontFamily: undefined,
                  color: undefined,
                  offsetY: 10,
                  formatter: function formatter(val) {
                    return val;
                  }
                },
                total: {
                  show: false,
                  label: 'Total',
                  color: undefined,
                  formatter: function formatter(w) {
                    return w.globals.seriesTotals.reduce(function (a, b) {
                      return a + b;
                    }, 0);
                  }
                }
              }
            }
          },
          radar: {
            size: undefined,
            offsetX: 0,
            offsetY: 0,
            polygons: {
              // strokeColor: '#e8e8e8', // should be deprecated in the minor version i.e 3.2
              strokeColors: '#e8e8e8',
              connectorColors: '#e8e8e8',
              fill: {
                colors: undefined
              }
            }
          }
        },
        colors: undefined,
        dataLabels: {
          enabled: true,
          enabledOnSeries: undefined,
          formatter: function formatter(val) {
            return val;
          },
          textAnchor: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            fontSize: '12px',
            fontFamily: undefined,
            colors: undefined
          },
          dropShadow: {
            enabled: false,
            top: 1,
            left: 1,
            blur: 1,
            color: '#000',
            opacity: 0.45
          }
        },
        fill: {
          type: 'solid',
          colors: undefined,
          // array of colors
          opacity: 0.85,
          gradient: {
            shade: 'dark',
            type: 'horizontal',
            shadeIntensity: 0.5,
            gradientToColors: undefined,
            inverseColors: true,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 100],
            colorStops: []
          },
          image: {
            src: [],
            width: undefined,
            // optional
            height: undefined // optional

          },
          pattern: {
            style: 'sqaures',
            // String | Array of Strings
            width: 6,
            height: 6,
            strokeWidth: 2
          }
        },
        grid: {
          show: true,
          borderColor: '#e0e0e0',
          strokeDashArray: 0,
          position: 'back',
          xaxis: {
            lines: {
              show: false,
              animate: false
            }
          },
          yaxis: {
            lines: {
              show: true,
              animate: false
            }
          },
          row: {
            colors: undefined,
            // takes as array which will be repeated on rows
            opacity: 0.5
          },
          column: {
            colors: undefined,
            // takes an array which will be repeated on columns
            opacity: 0.5
          },
          padding: {
            top: 0,
            right: 10,
            bottom: 0,
            left: 12
          }
        },
        labels: [],
        legend: {
          show: true,
          showForSingleSeries: false,
          showForNullSeries: true,
          showForZeroSeries: true,
          floating: false,
          position: 'bottom',
          // whether to position legends in 1 of 4
          // direction - top, bottom, left, right
          horizontalAlign: 'center',
          // when position top/bottom, you can specify whether to align legends left, right or center
          fontSize: '12px',
          fontFamily: undefined,
          width: undefined,
          height: undefined,
          formatter: undefined,
          offsetX: -20,
          offsetY: 0,
          labels: {
            colors: undefined,
            useSeriesColors: false
          },
          markers: {
            width: 12,
            height: 12,
            strokeWidth: 0,
            strokeColor: '#fff',
            radius: 12,
            customHTML: undefined,
            offsetX: 0,
            offsetY: 0,
            onClick: undefined
          },
          itemMargin: {
            horizontal: 0,
            vertical: 5
          },
          onItemClick: {
            toggleDataSeries: true
          },
          onItemHover: {
            highlightDataSeries: true
          }
        },
        markers: {
          discrete: [],
          size: 0,
          colors: undefined,
          //strokeColor: '#fff', // TODO: deprecate in major version 4.0
          strokeColors: '#fff',
          strokeWidth: 2,
          strokeOpacity: 0.9,
          fillOpacity: 1,
          shape: 'circle',
          radius: 2,
          offsetX: 0,
          offsetY: 0,
          hover: {
            size: undefined,
            sizeOffset: 3
          }
        },
        noData: {
          text: undefined,
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: undefined,
            fontSize: '14px',
            fontFamily: undefined
          }
        },
        responsive: [],
        // breakpoints should follow ascending order 400, then 700, then 1000
        series: undefined,
        states: {
          normal: {
            filter: {
              type: 'none',
              value: 0
            }
          },
          hover: {
            filter: {
              type: 'lighten',
              value: 0.15
            }
          },
          active: {
            allowMultipleDataPointsSelection: false,
            filter: {
              type: 'darken',
              value: 0.65
            }
          }
        },
        title: {
          text: undefined,
          align: 'left',
          margin: 10,
          offsetX: 0,
          offsetY: 0,
          floating: false,
          style: {
            fontSize: '14px',
            fontFamily: undefined,
            color: undefined
          }
        },
        subtitle: {
          text: undefined,
          align: 'left',
          margin: 10,
          offsetX: 0,
          offsetY: 30,
          floating: false,
          style: {
            fontSize: '12px',
            fontFamily: undefined,
            color: undefined
          }
        },
        stroke: {
          show: true,
          curve: 'smooth',
          // "smooth" / "straight" / "stepline"
          lineCap: 'butt',
          // round, butt , square
          width: 2,
          colors: undefined,
          // array of colors
          dashArray: 0 // single value or array of values

        },
        tooltip: {
          enabled: true,
          shared: true,
          followCursor: false,
          // when disabled, the tooltip will show on top of the series instead of mouse position
          intersect: false,
          // when enabled, tooltip will only show when user directly hovers over point
          inverseOrder: false,
          custom: undefined,
          fillSeriesColor: false,
          theme: 'light',
          style: {
            fontSize: '12px',
            fontFamily: undefined
          },
          onDatasetHover: {
            highlightDataSeries: false
          },
          x: {
            // x value
            show: true,
            format: 'dd MMM',
            // dd/MM, dd MMM yy, dd MMM yyyy
            formatter: undefined // a custom user supplied formatter function

          },
          y: {
            formatter: undefined,
            title: {
              formatter: function formatter(seriesName) {
                return seriesName;
              }
            }
          },
          z: {
            formatter: undefined,
            title: 'Size: '
          },
          marker: {
            show: true
          },
          items: {
            display: 'flex'
          },
          fixed: {
            enabled: false,
            position: 'topRight',
            // topRight, topLeft, bottomRight, bottomLeft
            offsetX: 0,
            offsetY: 0
          }
        },
        xaxis: {
          type: 'category',
          categories: [],
          offsetX: 0,
          offsetY: 0,
          labels: {
            show: true,
            rotate: -45,
            rotateAlways: false,
            hideOverlappingLabels: true,
            trim: true,
            minHeight: undefined,
            maxHeight: 120,
            showDuplicates: true,
            style: {
              colors: [],
              fontSize: '12px',
              fontFamily: undefined,
              cssClass: ''
            },
            offsetX: 0,
            offsetY: 0,
            format: undefined,
            formatter: undefined,
            // custom formatter function which will override format
            datetimeFormatter: {
              year: 'yyyy',
              month: "MMM 'yy",
              day: 'dd MMM',
              hour: 'HH:mm',
              minute: 'HH:mm:ss'
            }
          },
          axisBorder: {
            show: true,
            color: '#78909C',
            width: '100%',
            height: 1,
            offsetX: 0,
            offsetY: 0
          },
          axisTicks: {
            show: true,
            color: '#78909C',
            height: 6,
            offsetX: 0,
            offsetY: 0
          },
          tickAmount: undefined,
          tickPlacement: 'on',
          min: undefined,
          max: undefined,
          range: undefined,
          floating: false,
          position: 'bottom',
          title: {
            text: undefined,
            offsetX: 0,
            offsetY: 0,
            style: {
              color: undefined,
              fontSize: '12px',
              fontFamily: undefined,
              cssClass: ''
            }
          },
          crosshairs: {
            show: true,
            width: 1,
            // tickWidth/barWidth or an integer
            position: 'back',
            opacity: 0.9,
            stroke: {
              color: '#b6b6b6',
              width: 1,
              dashArray: 3
            },
            fill: {
              type: 'solid',
              // solid, gradient
              color: '#B1B9C4',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5
              }
            },
            dropShadow: {
              enabled: false,
              left: 0,
              top: 0,
              blur: 1,
              opacity: 0.4
            }
          },
          tooltip: {
            enabled: true,
            offsetY: 0,
            formatter: undefined,
            style: {
              fontSize: '12px',
              fontFamily: undefined
            }
          }
        },
        yaxis: this.yAxis,
        theme: {
          mode: 'light',
          palette: 'palette1',
          // If defined, it will overwrite globals.colors variable
          monochrome: {
            // monochrome allows you to select just 1 color and fill out the rest with light/dark shade (intensity can be selected)
            enabled: false,
            color: '#008FFB',
            shadeTo: 'light',
            shadeIntensity: 0.65
          }
        }
      };
    }
  }]);

  return Options;
}();

/**
 * ApexCharts Annotations Class for drawing lines/rects on both xaxis and yaxis.
 *
 * @module Annotations
 **/

var Annotations =
/*#__PURE__*/
function () {
  function Annotations(ctx) {
    _classCallCheck(this, Annotations);

    this.ctx = ctx;
    this.w = ctx.w;
    this.graphics = new Graphics(this.ctx);

    if (this.w.globals.isBarHorizontal) {
      this.invertAxis = true;
    }

    this.xDivision = this.w.globals.gridWidth / this.w.globals.dataPoints;
  }

  _createClass(Annotations, [{
    key: "drawAnnotations",
    value: function drawAnnotations() {
      var w = this.w;

      if (w.globals.axisCharts) {
        var yAnnotations = this.drawYAxisAnnotations();
        var xAnnotations = this.drawXAxisAnnotations();
        var pointAnnotations = this.drawPointAnnotations();
        var initialAnim = w.config.chart.animations.enabled;
        var annoArray = [yAnnotations, xAnnotations, pointAnnotations];
        var annoElArray = [xAnnotations.node, yAnnotations.node, pointAnnotations.node];

        for (var i = 0; i < 3; i++) {
          w.globals.dom.elGraphical.add(annoArray[i]);

          if (initialAnim && !w.globals.resized && !w.globals.dataChanged) {
            annoElArray[i].classList.add('hidden');
          }

          w.globals.delayedElements.push({
            el: annoElArray[i],
            index: 0
          });
        } // after placing the annotations on svg, set any vertically placed annotations


        this.setOrientations(w.config.annotations.xaxis); // background sizes needs to be calculated after text is drawn, so calling them last

        this.annotationsBackground();
      }
    }
  }, {
    key: "addXaxisAnnotation",
    value: function addXaxisAnnotation(anno, parent, index) {
      var w = this.w;
      var min = this.invertAxis ? w.globals.minY : w.globals.minX;
      var range = this.invertAxis ? w.globals.yRange[0] : w.globals.xRange;
      var x1 = (anno.x - min) / (range / w.globals.gridWidth);
      var text = anno.label.text;

      if (w.config.xaxis.type === 'category' || w.config.xaxis.convertedCatToNumeric) {
        var catIndex = w.globals.labels.indexOf(anno.x);
        var xLabel = w.globals.dom.baseEl.querySelector('.apexcharts-xaxis-texts-g text:nth-child(' + (catIndex + 1) + ')');

        if (xLabel) {
          x1 = parseFloat(xLabel.getAttribute('x'));
        }
      }

      var strokeDashArray = anno.strokeDashArray;
      if (x1 < 0 || x1 > w.globals.gridWidth) return;

      if (anno.x2 === null) {
        var line = this.graphics.drawLine(x1 + anno.offsetX, // x1
        0 + anno.offsetY, // y1
        x1 + anno.offsetX, // x2
        w.globals.gridHeight + anno.offsetY, // y2
        anno.borderColor, // lineColor
        strokeDashArray //dashArray
        );
        parent.appendChild(line.node);
      } else {
        var x2 = (anno.x2 - min) / (range / w.globals.gridWidth);

        if (x2 < x1) {
          var temp = x1;
          x1 = x2;
          x2 = temp;
        }

        if (text) {
          var rect = this.graphics.drawRect(x1 + anno.offsetX, // x1
          0 + anno.offsetY, // y1
          x2 - x1, // x2
          w.globals.gridHeight + anno.offsetY, // y2
          0, // radius
          anno.fillColor, // color
          anno.opacity, // opacity,
          1, // strokeWidth
          anno.borderColor, // strokeColor
          strokeDashArray // stokeDashArray
          );
          parent.appendChild(rect.node);
        }
      }

      var textY = anno.label.position === 'top' ? -3 : w.globals.gridHeight;
      var elText = this.graphics.drawText({
        x: x1 + anno.label.offsetX,
        y: textY + anno.label.offsetY,
        text: text,
        textAnchor: anno.label.textAnchor,
        fontSize: anno.label.style.fontSize,
        fontFamily: anno.label.style.fontFamily,
        foreColor: anno.label.style.color,
        cssClass: 'apexcharts-xaxis-annotation-label ' + anno.label.style.cssClass
      });
      elText.attr({
        rel: index
      });
      parent.appendChild(elText.node);
    }
  }, {
    key: "drawXAxisAnnotations",
    value: function drawXAxisAnnotations() {
      var _this = this;

      var w = this.w;
      var elg = this.graphics.group({
        class: 'apexcharts-xaxis-annotations'
      });
      w.config.annotations.xaxis.map(function (anno, index) {
        _this.addXaxisAnnotation(anno, elg.node, index);
      });
      return elg;
    }
  }, {
    key: "addYaxisAnnotation",
    value: function addYaxisAnnotation(anno, parent, index) {
      var w = this.w;
      var strokeDashArray = anno.strokeDashArray;
      var y1;
      var y2;

      if (this.invertAxis) {
        var catIndex = w.globals.labels.indexOf(anno.y);
        var xLabel = w.globals.dom.baseEl.querySelector('.apexcharts-yaxis-texts-g text:nth-child(' + (catIndex + 1) + ')');

        if (xLabel) {
          y1 = parseFloat(xLabel.getAttribute('y'));
        }
      } else {
        y1 = w.globals.gridHeight - (anno.y - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight);

        if (w.config.yaxis[anno.yAxisIndex] && w.config.yaxis[anno.yAxisIndex].reversed) {
          y1 = (anno.y - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight);
        }
      }

      var text = anno.label.text;

      if (anno.y2 === null) {
        var line = this.graphics.drawLine(0 + anno.offsetX, // x1
        y1 + anno.offsetY, // y1
        w.globals.gridWidth + anno.offsetX, // x2
        y1 + anno.offsetY, // y2
        anno.borderColor, // lineColor
        strokeDashArray // dashArray
        );
        parent.appendChild(line.node);
      } else {
        if (this.invertAxis) {
          var _catIndex = w.globals.labels.indexOf(anno.y2);

          var _xLabel = w.globals.dom.baseEl.querySelector('.apexcharts-yaxis-texts-g text:nth-child(' + (_catIndex + 1) + ')');

          if (_xLabel) {
            y2 = parseFloat(_xLabel.getAttribute('y'));
          }
        } else {
          y2 = w.globals.gridHeight - (anno.y2 - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight);

          if (w.config.yaxis[anno.yAxisIndex] && w.config.yaxis[anno.yAxisIndex].reversed) {
            y2 = (anno.y2 - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight);
          }
        }

        if (y2 > y1) {
          var temp = y1;
          y1 = y2;
          y2 = temp;
        }

        if (text) {
          var rect = this.graphics.drawRect(0 + anno.offsetX, // x1
          y2 + anno.offsetY, // y1
          w.globals.gridWidth + anno.offsetX, // x2
          y1 - y2, // y2
          0, // radius
          anno.fillColor, // color
          anno.opacity, // opacity,
          1, // strokeWidth
          anno.borderColor, // strokeColor
          strokeDashArray // stokeDashArray
          );
          parent.appendChild(rect.node);
        }
      }

      var textX = anno.label.position === 'right' ? w.globals.gridWidth : 0;
      var elText = this.graphics.drawText({
        x: textX + anno.label.offsetX,
        y: (y2 || y1) + anno.label.offsetY - 3,
        text: text,
        textAnchor: anno.label.textAnchor,
        fontSize: anno.label.style.fontSize,
        fontFamily: anno.label.style.fontFamily,
        foreColor: anno.label.style.color,
        cssClass: 'apexcharts-yaxis-annotation-label ' + anno.label.style.cssClass
      });
      elText.attr({
        rel: index
      });
      parent.appendChild(elText.node);
    }
  }, {
    key: "drawYAxisAnnotations",
    value: function drawYAxisAnnotations() {
      var _this2 = this;

      var w = this.w;
      var elg = this.graphics.group({
        class: 'apexcharts-yaxis-annotations'
      });
      w.config.annotations.yaxis.map(function (anno, index) {
        _this2.addYaxisAnnotation(anno, elg.node, index);
      });
      return elg;
    }
  }, {
    key: "clearAnnotations",
    value: function clearAnnotations(ctx) {
      var w = ctx.w;
      var annos = w.globals.dom.baseEl.querySelectorAll('.apexcharts-yaxis-annotations, .apexcharts-xaxis-annotations, .apexcharts-point-annotations');
      annos = Utils.listToArray(annos);
      annos.forEach(function (a) {
        while (a.firstChild) {
          a.removeChild(a.firstChild);
        }
      });
    }
  }, {
    key: "addPointAnnotation",
    value: function addPointAnnotation(anno, parent, index) {
      var w = this.w;
      var x = 0;
      var y = 0;
      var pointY = 0;

      if (this.invertAxis) {
        console.warn('Point annotation is not supported in horizontal bar charts.');
      }

      if (typeof anno.x === 'string') {
        var catIndex = w.globals.labels.indexOf(anno.x);
        var xLabel = w.globals.dom.baseEl.querySelector('.apexcharts-xaxis-texts-g text:nth-child(' + (catIndex + 1) + ')');
        var xPos = parseFloat(xLabel.getAttribute('x'));
        x = xPos;
        var annoY = anno.y;

        if (anno.y === null) {
          annoY = w.globals.series[anno.seriesIndex][catIndex];
        }

        y = w.globals.gridHeight - (annoY - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight) - parseInt(anno.label.style.fontSize) - anno.marker.size;
        pointY = w.globals.gridHeight - (annoY - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight);

        if (w.config.yaxis[anno.yAxisIndex] && w.config.yaxis[anno.yAxisIndex].reversed) {
          y = (annoY - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight) + parseInt(anno.label.style.fontSize) + anno.marker.size;
          pointY = (annoY - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight);
        }
      } else {
        x = (anno.x - w.globals.minX) / (w.globals.xRange / w.globals.gridWidth);
        y = w.globals.gridHeight - (parseFloat(anno.y) - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight) - parseInt(anno.label.style.fontSize) - anno.marker.size;
        pointY = w.globals.gridHeight - (anno.y - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight);

        if (w.config.yaxis[anno.yAxisIndex] && w.config.yaxis[anno.yAxisIndex].reversed) {
          y = (parseFloat(anno.y) - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight) - parseInt(anno.label.style.fontSize) - anno.marker.size;
          pointY = (anno.y - w.globals.minYArr[anno.yAxisIndex]) / (w.globals.yRange[anno.yAxisIndex] / w.globals.gridHeight);
        }
      }

      if (x < 0 || x > w.globals.gridWidth) return;
      var optsPoints = {
        pSize: anno.marker.size,
        pWidth: anno.marker.strokeWidth,
        pointFillColor: anno.marker.fillColor,
        pointStrokeColor: anno.marker.strokeColor,
        shape: anno.marker.shape,
        radius: anno.marker.radius,
        class: 'apexcharts-point-annotation-marker ' + anno.marker.cssClass
      };
      var point = this.graphics.drawMarker(x + anno.marker.offsetX, pointY + anno.marker.offsetY, optsPoints);
      parent.appendChild(point.node);
      var text = anno.label.text ? anno.label.text : '';
      var elText = this.graphics.drawText({
        x: x + anno.label.offsetX,
        y: y + anno.label.offsetY,
        text: text,
        textAnchor: anno.label.textAnchor,
        fontSize: anno.label.style.fontSize,
        fontFamily: anno.label.style.fontFamily,
        foreColor: anno.label.style.color,
        cssClass: 'apexcharts-point-annotation-label ' + anno.label.style.cssClass
      });
      elText.attr({
        rel: index
      });
      parent.appendChild(elText.node);

      if (anno.customSVG.SVG) {
        var g = this.graphics.group({
          class: 'apexcharts-point-annotations-custom-svg ' + anno.customSVG.cssClass
        });
        g.attr({
          transform: "translate(".concat(x + anno.customSVG.offsetX, ", ").concat(y + anno.customSVG.offsetY, ")")
        });
        g.node.innerHTML = anno.customSVG.SVG;
        parent.appendChild(g.node);
      }
    }
  }, {
    key: "drawPointAnnotations",
    value: function drawPointAnnotations() {
      var _this3 = this;

      var w = this.w;
      var elg = this.graphics.group({
        class: 'apexcharts-point-annotations'
      });
      w.config.annotations.points.map(function (anno, index) {
        _this3.addPointAnnotation(anno, elg.node, index);
      });
      return elg;
    }
  }, {
    key: "setOrientations",
    value: function setOrientations(annos) {
      var _this4 = this;

      var annoIndex = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      var w = this.w;
      annos.map(function (anno, index) {
        if (anno.label.orientation === 'vertical') {
          var i = annoIndex !== null ? annoIndex : index;
          var xAnno = w.globals.dom.baseEl.querySelector(".apexcharts-xaxis-annotations .apexcharts-xaxis-annotation-label[rel='".concat(i, "']"));

          if (xAnno !== null) {
            var xAnnoCoord = xAnno.getBoundingClientRect();
            xAnno.setAttribute('x', parseFloat(xAnno.getAttribute('x')) - xAnnoCoord.height + 4);

            if (anno.label.position === 'top') {
              xAnno.setAttribute('y', parseFloat(xAnno.getAttribute('y')) + xAnnoCoord.width);
            } else {
              xAnno.setAttribute('y', parseFloat(xAnno.getAttribute('y')) - xAnnoCoord.width);
            }

            var annoRotatingCenter = _this4.graphics.rotateAroundCenter(xAnno);

            var x = annoRotatingCenter.x;
            var y = annoRotatingCenter.y;
            xAnno.setAttribute('transform', "rotate(-90 ".concat(x, " ").concat(y, ")"));
          }
        }
      });
    }
  }, {
    key: "addBackgroundToAnno",
    value: function addBackgroundToAnno(annoEl, anno) {
      var w = this.w;
      if (!anno.label.text) return null;
      var elGridRect = w.globals.dom.baseEl.querySelector('.apexcharts-grid').getBoundingClientRect();
      var coords = annoEl.getBoundingClientRect();
      var pleft = anno.label.style.padding.left;
      var pright = anno.label.style.padding.right;
      var ptop = anno.label.style.padding.top;
      var pbottom = anno.label.style.padding.bottom;

      if (anno.label.orientation === 'vertical') {
        ptop = anno.label.style.padding.left;
        pbottom = anno.label.style.padding.right;
        pleft = anno.label.style.padding.top;
        pright = anno.label.style.padding.bottom;
      }

      var x1 = coords.left - elGridRect.left - pleft;
      var y1 = coords.top - elGridRect.top - ptop;
      var elRect = this.graphics.drawRect(x1, y1, coords.width + pleft + pright, coords.height + ptop + pbottom, 0, anno.label.style.background, 1, anno.label.borderWidth, anno.label.borderColor, 0);
      return elRect;
    }
  }, {
    key: "annotationsBackground",
    value: function annotationsBackground() {
      var _this5 = this;

      var w = this.w;

      var add = function add(anno, i, type) {
        var annoLabel = w.globals.dom.baseEl.querySelector(".apexcharts-".concat(type, "-annotations .apexcharts-").concat(type, "-annotation-label[rel='").concat(i, "']"));

        if (annoLabel) {
          var parent = annoLabel.parentNode;

          var elRect = _this5.addBackgroundToAnno(annoLabel, anno);

          if (elRect) {
            parent.insertBefore(elRect.node, annoLabel);
          }
        }
      };

      w.config.annotations.xaxis.map(function (anno, i) {
        add(anno, i, 'xaxis');
      });
      w.config.annotations.yaxis.map(function (anno, i) {
        add(anno, i, 'yaxis');
      });
      w.config.annotations.points.map(function (anno, i) {
        add(anno, i, 'point');
      });
    }
  }, {
    key: "addText",
    value: function addText(params, pushToMemory, context) {
      var x = params.x,
          y = params.y,
          text = params.text,
          textAnchor = params.textAnchor,
          _params$appendTo = params.appendTo,
          appendTo = _params$appendTo === void 0 ? '.apexcharts-inner' : _params$appendTo,
          foreColor = params.foreColor,
          fontSize = params.fontSize,
          fontFamily = params.fontFamily,
          cssClass = params.cssClass,
          backgroundColor = params.backgroundColor,
          borderWidth = params.borderWidth,
          strokeDashArray = params.strokeDashArray,
          radius = params.radius,
          borderColor = params.borderColor,
          _params$paddingLeft = params.paddingLeft,
          paddingLeft = _params$paddingLeft === void 0 ? 4 : _params$paddingLeft,
          _params$paddingRight = params.paddingRight,
          paddingRight = _params$paddingRight === void 0 ? 4 : _params$paddingRight,
          _params$paddingBottom = params.paddingBottom,
          paddingBottom = _params$paddingBottom === void 0 ? 2 : _params$paddingBottom,
          _params$paddingTop = params.paddingTop,
          paddingTop = _params$paddingTop === void 0 ? 2 : _params$paddingTop;
      var me = context;
      var w = me.w;
      var parentNode = w.globals.dom.baseEl.querySelector(appendTo);
      var elText = this.graphics.drawText({
        x: x,
        y: y,
        text: text,
        textAnchor: textAnchor || 'start',
        fontSize: fontSize || '12px',
        fontFamily: fontFamily || w.config.chart.fontFamily,
        foreColor: foreColor || w.config.chart.foreColor,
        cssClass: 'apexcharts-text ' + cssClass ? cssClass : ''
      });
      parentNode.appendChild(elText.node);
      var textRect = elText.bbox();

      if (text) {
        var elRect = this.graphics.drawRect(textRect.x - paddingLeft, textRect.y - paddingTop, textRect.width + paddingLeft + paddingRight, textRect.height + paddingBottom + paddingTop, radius, backgroundColor, 1, borderWidth, borderColor, strokeDashArray);
        elText.before(elRect);
      }

      if (pushToMemory) {
        w.globals.memory.methodsToExec.push({
          context: me,
          method: me.addText,
          params: {
            x: x,
            y: y,
            text: text,
            textAnchor: textAnchor,
            appendTo: appendTo,
            foreColor: foreColor,
            fontSize: fontSize,
            cssClass: cssClass,
            backgroundColor: backgroundColor,
            borderWidth: borderWidth,
            strokeDashArray: strokeDashArray,
            radius: radius,
            borderColor: borderColor,
            paddingLeft: paddingLeft,
            paddingRight: paddingRight,
            paddingBottom: paddingBottom,
            paddingTop: paddingTop
          }
        });
      }

      return context;
    }
  }, {
    key: "addPointAnnotationExternal",
    value: function addPointAnnotationExternal(params, pushToMemory, context) {
      this.addAnnotationExternal({
        params: params,
        pushToMemory: pushToMemory,
        context: context,
        type: 'point',
        contextMethod: context.addPointAnnotation
      });
      return context;
    }
  }, {
    key: "addYaxisAnnotationExternal",
    value: function addYaxisAnnotationExternal(params, pushToMemory, context) {
      this.addAnnotationExternal({
        params: params,
        pushToMemory: pushToMemory,
        context: context,
        type: 'yaxis',
        contextMethod: context.addYaxisAnnotation
      });
      return context;
    } // The addXaxisAnnotation method requires a parent class, and user calling this method externally on the chart instance may not specify parent, hence a different method

  }, {
    key: "addXaxisAnnotationExternal",
    value: function addXaxisAnnotationExternal(params, pushToMemory, context) {
      this.addAnnotationExternal({
        params: params,
        pushToMemory: pushToMemory,
        context: context,
        type: 'xaxis',
        contextMethod: context.addXaxisAnnotation
      });
      return context;
    }
  }, {
    key: "addAnnotationExternal",
    value: function addAnnotationExternal(_ref) {
      var params = _ref.params,
          pushToMemory = _ref.pushToMemory,
          context = _ref.context,
          type = _ref.type,
          contextMethod = _ref.contextMethod;
      var me = context;
      var w = me.w;
      var parent = w.globals.dom.baseEl.querySelector(".apexcharts-".concat(type, "-annotations"));
      var index = parent.childNodes.length + 1;
      var opt = new Options();
      var axesAnno = Object.assign({}, type === 'xaxis' ? opt.xAxisAnnotation : type === 'yaxis' ? opt.yAxisAnnotation : opt.pointAnnotation);
      var anno = Utils.extend(axesAnno, params);

      switch (type) {
        case 'xaxis':
          this.addXaxisAnnotation(anno, parent, index);
          break;

        case 'yaxis':
          this.addYaxisAnnotation(anno, parent, index);
          break;

        case 'point':
          this.addPointAnnotation(anno, parent, index);
          break;
      } // add background


      var axesAnnoLabel = w.globals.dom.baseEl.querySelector(".apexcharts-".concat(type, "-annotations .apexcharts-").concat(type, "-annotation-label[rel='").concat(index, "']"));
      var elRect = this.addBackgroundToAnno(axesAnnoLabel, anno);

      if (elRect) {
        parent.insertBefore(elRect.node, axesAnnoLabel);
      }

      if (pushToMemory) {
        w.globals.memory.methodsToExec.push({
          context: me,
          method: contextMethod,
          params: params
        });
      }

      return context;
    }
  }]);

  return Annotations;
}();

/**
 * DateTime Class to manipulate datetime values.
 *
 * @module DateTime
 **/

var DateTime =
/*#__PURE__*/
function () {
  function DateTime(ctx) {
    _classCallCheck(this, DateTime);

    this.ctx = ctx;
    this.w = ctx.w;
    this.months31 = [1, 3, 5, 7, 8, 10, 12];
    this.months30 = [2, 4, 6, 9, 11];
    this.daysCntOfYear = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
  }

  _createClass(DateTime, [{
    key: "isValidDate",
    value: function isValidDate(date) {
      return !isNaN(this.parseDate(date));
    }
  }, {
    key: "getUTCTimeStamp",
    value: function getUTCTimeStamp(dateStr) {
      if (!Date.parse(dateStr)) {
        return dateStr;
      }

      return new Date(new Date(dateStr).toISOString().substr(0, 25)).getTime();
    }
  }, {
    key: "parseDate",
    value: function parseDate(dateStr) {
      var parsed = Date.parse(dateStr);

      if (!isNaN(parsed)) {
        return this.getUTCTimeStamp(dateStr);
      }

      var output = Date.parse(dateStr.replace(/-/g, '/').replace(/[a-z]+/gi, ' '));
      output = this.getUTCTimeStamp(output);
      return output;
    } // https://stackoverflow.com/a/11252167/6495043

  }, {
    key: "treatAsUtc",
    value: function treatAsUtc(dateStr) {
      var result = new Date(dateStr);
      result.setMinutes(result.getMinutes() - result.getTimezoneOffset());
      return result;
    } // http://stackoverflow.com/questions/14638018/current-time-formatting-with-javascript#answer-14638191

  }, {
    key: "formatDate",
    value: function formatDate(date, format) {
      var utc = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
      var convertToUTC = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : true;
      var locale = this.w.globals.locale;
      var MMMM = ['\x00'].concat(_toConsumableArray(locale.months));
      var MMM = ['\x01'].concat(_toConsumableArray(locale.shortMonths));
      var dddd = ['\x02'].concat(_toConsumableArray(locale.days));
      var ddd = ['\x03'].concat(_toConsumableArray(locale.shortDays));

      function ii(i, len) {
        var s = i + '';
        len = len || 2;

        while (s.length < len) {
          s = '0' + s;
        }

        return s;
      }

      if (convertToUTC) {
        date = this.treatAsUtc(date);
      }

      var y = utc ? date.getUTCFullYear() : date.getFullYear();
      format = format.replace(/(^|[^\\])yyyy+/g, '$1' + y);
      format = format.replace(/(^|[^\\])yy/g, '$1' + y.toString().substr(2, 2));
      format = format.replace(/(^|[^\\])y/g, '$1' + y);
      var M = (utc ? date.getUTCMonth() : date.getMonth()) + 1;
      format = format.replace(/(^|[^\\])MMMM+/g, '$1' + MMMM[0]);
      format = format.replace(/(^|[^\\])MMM/g, '$1' + MMM[0]);
      format = format.replace(/(^|[^\\])MM/g, '$1' + ii(M));
      format = format.replace(/(^|[^\\])M/g, '$1' + M);
      var d = utc ? date.getUTCDate() : date.getDate();
      format = format.replace(/(^|[^\\])dddd+/g, '$1' + dddd[0]);
      format = format.replace(/(^|[^\\])ddd/g, '$1' + ddd[0]);
      format = format.replace(/(^|[^\\])dd/g, '$1' + ii(d));
      format = format.replace(/(^|[^\\])d/g, '$1' + d);
      var H = utc ? date.getUTCHours() : date.getHours();
      format = format.replace(/(^|[^\\])HH+/g, '$1' + ii(H));
      format = format.replace(/(^|[^\\])H/g, '$1' + H);
      var h = H > 12 ? H - 12 : H === 0 ? 12 : H;
      format = format.replace(/(^|[^\\])hh+/g, '$1' + ii(h));
      format = format.replace(/(^|[^\\])h/g, '$1' + h);
      var m = utc ? date.getUTCMinutes() : date.getMinutes();
      format = format.replace(/(^|[^\\])mm+/g, '$1' + ii(m));
      format = format.replace(/(^|[^\\])m/g, '$1' + m);
      var s = utc ? date.getUTCSeconds() : date.getSeconds();
      format = format.replace(/(^|[^\\])ss+/g, '$1' + ii(s));
      format = format.replace(/(^|[^\\])s/g, '$1' + s);
      var f = utc ? date.getUTCMilliseconds() : date.getMilliseconds();
      format = format.replace(/(^|[^\\])fff+/g, '$1' + ii(f, 3));
      f = Math.round(f / 10);
      format = format.replace(/(^|[^\\])ff/g, '$1' + ii(f));
      f = Math.round(f / 10);
      format = format.replace(/(^|[^\\])f/g, '$1' + f);
      var T = H < 12 ? 'AM' : 'PM';
      format = format.replace(/(^|[^\\])TT+/g, '$1' + T);
      format = format.replace(/(^|[^\\])T/g, '$1' + T.charAt(0));
      var t = T.toLowerCase();
      format = format.replace(/(^|[^\\])tt+/g, '$1' + t);
      format = format.replace(/(^|[^\\])t/g, '$1' + t.charAt(0));
      var tz = -date.getTimezoneOffset();
      var K = utc || !tz ? 'Z' : tz > 0 ? '+' : '-';

      if (!utc) {
        tz = Math.abs(tz);
        var tzHrs = Math.floor(tz / 60);
        var tzMin = tz % 60;
        K += ii(tzHrs) + ':' + ii(tzMin);
      }

      format = format.replace(/(^|[^\\])K/g, '$1' + K);
      var day = (utc ? date.getUTCDay() : date.getDay()) + 1;
      format = format.replace(new RegExp(dddd[0], 'g'), dddd[day]);
      format = format.replace(new RegExp(ddd[0], 'g'), ddd[day]);
      format = format.replace(new RegExp(MMMM[0], 'g'), MMMM[M]);
      format = format.replace(new RegExp(MMM[0], 'g'), MMM[M]);
      format = format.replace(/\\(.)/g, '$1');
      return format;
    }
  }, {
    key: "getTimeUnitsfromTimestamp",
    value: function getTimeUnitsfromTimestamp(minX, maxX) {
      var w = this.w;

      if (w.config.xaxis.min !== undefined) {
        minX = w.config.xaxis.min;
      }

      if (w.config.xaxis.max !== undefined) {
        maxX = w.config.xaxis.max;
      }

      var minYear = new Date(minX).getFullYear();
      var maxYear = new Date(maxX).getFullYear();
      var minMonth = new Date(minX).getMonth();
      var maxMonth = new Date(maxX).getMonth();
      var minDate = new Date(minX).getDate();
      var maxDate = new Date(maxX).getDate();
      var minHour = new Date(minX).getHours();
      var maxHour = new Date(maxX).getHours();
      var minMinute = new Date(minX).getMinutes();
      var maxMinute = new Date(maxX).getMinutes();
      return {
        minMinute: minMinute,
        maxMinute: maxMinute,
        minHour: minHour,
        maxHour: maxHour,
        minDate: minDate,
        maxDate: maxDate,
        minMonth: minMonth,
        maxMonth: maxMonth,
        minYear: minYear,
        maxYear: maxYear
      };
    }
  }, {
    key: "isLeapYear",
    value: function isLeapYear(year) {
      return year % 4 === 0 && year % 100 !== 0 || year % 400 === 0;
    }
  }, {
    key: "calculcateLastDaysOfMonth",
    value: function calculcateLastDaysOfMonth(month, year, subtract) {
      var days = this.determineDaysOfMonths(month, year); // whatever days we get, subtract the number of days asked

      return days - subtract;
    }
  }, {
    key: "determineDaysOfYear",
    value: function determineDaysOfYear(year) {
      var days = 365;

      if (this.isLeapYear(year)) {
        days = 366;
      }

      return days;
    }
  }, {
    key: "determineRemainingDaysOfYear",
    value: function determineRemainingDaysOfYear(year, month, date) {
      var dayOfYear = this.daysCntOfYear[month] + date;
      if (month > 1 && this.isLeapYear()) dayOfYear++;
      return dayOfYear;
    }
  }, {
    key: "determineDaysOfMonths",
    value: function determineDaysOfMonths(month, year) {
      var days = 30;
      month = Utils.monthMod(month);

      switch (true) {
        case this.months30.indexOf(month) > -1:
          if (month === 2) {
            if (this.isLeapYear(year)) {
              days = 29;
            } else {
              days = 28;
            }
          }

          break;

        case this.months31.indexOf(month) > -1:
          days = 31;
          break;

        default:
          days = 31;
          break;
      }

      return days;
    }
  }]);

  return DateTime;
}();

/**
 * ApexCharts Default Class for setting default options for all chart types.
 *
 * @module Defaults
 **/

var Defaults =
/*#__PURE__*/
function () {
  function Defaults(opts) {
    _classCallCheck(this, Defaults);

    this.opts = opts;
  }

  _createClass(Defaults, [{
    key: "line",
    value: function line() {
      return {
        chart: {
          animations: {
            easing: 'swing'
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: 5,
          curve: 'straight'
        },
        markers: {
          size: 0,
          hover: {
            sizeOffset: 6
          }
        },
        xaxis: {
          crosshairs: {
            width: 1
          }
        }
      };
    }
  }, {
    key: "sparkline",
    value: function sparkline(defaults) {
      this.opts.yaxis[0].labels.show = false;
      this.opts.yaxis[0].floating = true;
      var ret = {
        grid: {
          show: false,
          padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
          }
        },
        legend: {
          show: false
        },
        xaxis: {
          labels: {
            show: false
          },
          tooltip: {
            enabled: false
          },
          axisBorder: {
            show: false
          }
        },
        chart: {
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        }
      };
      return Utils.extend(defaults, ret);
    }
  }, {
    key: "bar",
    value: function bar() {
      return {
        chart: {
          stacked: false,
          animations: {
            easing: 'swing'
          }
        },
        plotOptions: {
          bar: {
            dataLabels: {
              position: 'center'
            }
          }
        },
        dataLabels: {
          style: {
            colors: ['#fff']
          }
        },
        stroke: {
          width: 0
        },
        fill: {
          opacity: 0.85
        },
        legend: {
          markers: {
            shape: 'square',
            radius: 2,
            size: 8
          }
        },
        tooltip: {
          shared: false
        },
        xaxis: {
          tooltip: {
            enabled: false
          },
          crosshairs: {
            width: 'barWidth',
            position: 'back',
            fill: {
              type: 'gradient'
            },
            dropShadow: {
              enabled: false
            },
            stroke: {
              width: 0
            }
          }
        }
      };
    }
  }, {
    key: "candlestick",
    value: function candlestick() {
      return {
        stroke: {
          width: 1,
          colors: ['#333']
        },
        dataLabels: {
          enabled: false
        },
        tooltip: {
          shared: true,
          custom: function custom(_ref) {
            var seriesIndex = _ref.seriesIndex,
                dataPointIndex = _ref.dataPointIndex,
                w = _ref.w;
            var o = w.globals.seriesCandleO[seriesIndex][dataPointIndex];
            var h = w.globals.seriesCandleH[seriesIndex][dataPointIndex];
            var l = w.globals.seriesCandleL[seriesIndex][dataPointIndex];
            var c = w.globals.seriesCandleC[seriesIndex][dataPointIndex];
            return '<div class="apexcharts-tooltip-candlestick">' + '<div>Open: <span class="value">' + o + '</span></div>' + '<div>High: <span class="value">' + h + '</span></div>' + '<div>Low: <span class="value">' + l + '</span></div>' + '<div>Close: <span class="value">' + c + '</span></div>' + '</div>';
          }
        },
        states: {
          active: {
            filter: {
              type: 'none'
            }
          }
        },
        xaxis: {
          crosshairs: {
            width: 1
          }
        }
      };
    }
  }, {
    key: "rangeBar",
    value: function rangeBar() {
      return {
        stroke: {
          width: 0
        },
        plotOptions: {
          bar: {
            dataLabels: {
              position: 'center'
            }
          }
        },
        dataLabels: {
          enabled: false,
          formatter: function formatter(val, _ref2) {
            var ctx = _ref2.ctx,
                seriesIndex = _ref2.seriesIndex,
                dataPointIndex = _ref2.dataPointIndex,
                w = _ref2.w;
            var start = w.globals.seriesRangeStart[seriesIndex][dataPointIndex];
            var end = w.globals.seriesRangeEnd[seriesIndex][dataPointIndex];
            return end - start;
          },
          style: {
            colors: ['#fff']
          }
        },
        tooltip: {
          shared: false,
          followCursor: true,
          custom: function custom(_ref3) {
            var ctx = _ref3.ctx,
                seriesIndex = _ref3.seriesIndex,
                dataPointIndex = _ref3.dataPointIndex,
                w = _ref3.w;
            var start = w.globals.seriesRangeStart[seriesIndex][dataPointIndex];
            var end = w.globals.seriesRangeEnd[seriesIndex][dataPointIndex];
            var startVal = '';
            var endVal = '';
            var color = w.globals.colors[seriesIndex];

            if (w.config.tooltip.x.formatter === undefined) {
              if (w.config.xaxis.type === 'datetime') {
                var datetimeObj = new DateTime(ctx);
                startVal = datetimeObj.formatDate(new Date(start), w.config.tooltip.x.format, true, true);
                endVal = datetimeObj.formatDate(new Date(end), w.config.tooltip.x.format, true, true);
              } else {
                startVal = start;
                endVal = end;
              }
            } else {
              startVal = w.config.tooltip.x.formatter(start);
              endVal = w.config.tooltip.x.formatter(end);
            }

            var ylabel = w.globals.labels[dataPointIndex];
            return '<div class="apexcharts-tooltip-rangebar">' + '<div> <span class="series-name" style="color: ' + color + '">' + (w.config.series[seriesIndex].name ? w.config.series[seriesIndex].name : '') + '</span></div>' + '<div> <span class="category">' + ylabel + ': </span> <span class="value start-value">' + startVal + '</span> <span class="separator">-</span> <span class="value end-value">' + endVal + '</span></div>' + '</div>';
          }
        },
        xaxis: {
          tooltip: {
            enabled: false
          },
          crosshairs: {
            stroke: {
              width: 0
            }
          }
        }
      };
    }
  }, {
    key: "area",
    value: function area() {
      return {
        stroke: {
          width: 4
        },
        fill: {
          type: 'gradient',
          gradient: {
            inverseColors: false,
            shade: 'light',
            type: 'vertical',
            opacityFrom: 0.65,
            opacityTo: 0.5,
            stops: [0, 100, 100]
          }
        },
        markers: {
          size: 0,
          hover: {
            sizeOffset: 6
          }
        },
        tooltip: {
          followCursor: false
        }
      };
    }
  }, {
    key: "brush",
    value: function brush(defaults) {
      var ret = {
        chart: {
          toolbar: {
            autoSelected: 'selection',
            show: false
          },
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: 1
        },
        tooltip: {
          enabled: false
        },
        xaxis: {
          tooltip: {
            enabled: false
          }
        }
      };
      return Utils.extend(defaults, ret);
    }
  }, {
    key: "stacked100",
    value: function stacked100() {
      var _this = this;

      this.opts.dataLabels = this.opts.dataLabels || {};
      this.opts.dataLabels.formatter = this.opts.dataLabels.formatter || undefined;
      var existingDataLabelFormatter = this.opts.dataLabels.formatter;
      this.opts.yaxis.forEach(function (yaxe, index) {
        _this.opts.yaxis[index].min = 0;
        _this.opts.yaxis[index].max = 100;
      });
      var isBar = this.opts.chart.type === 'bar';

      if (isBar) {
        this.opts.dataLabels.formatter = existingDataLabelFormatter || function (val) {
          if (typeof val === 'number') {
            return val ? val.toFixed(0) + '%' : val;
          }

          return val;
        };
      }
    } // This function removes the left and right spacing in chart for line/area/scatter if xaxis type = category for those charts by converting xaxis = numeric. Numeric/Datetime xaxis prevents the unnecessary spacing in the left/right of the chart area

  }, {
    key: "bubble",
    value: function bubble() {
      return {
        dataLabels: {
          style: {
            colors: ['#fff']
          }
        },
        tooltip: {
          shared: false,
          intersect: true
        },
        xaxis: {
          crosshairs: {
            width: 0
          }
        },
        fill: {
          type: 'solid',
          gradient: {
            shade: 'light',
            inverse: true,
            shadeIntensity: 0.55,
            opacityFrom: 0.4,
            opacityTo: 0.8
          }
        }
      };
    }
  }, {
    key: "scatter",
    value: function scatter() {
      return {
        dataLabels: {
          enabled: false
        },
        tooltip: {
          shared: false,
          intersect: true
        },
        markers: {
          size: 6,
          strokeWidth: 2,
          hover: {
            sizeOffset: 2
          }
        }
      };
    }
  }, {
    key: "heatmap",
    value: function heatmap() {
      return {
        chart: {
          stacked: false,
          zoom: {
            enabled: false
          }
        },
        fill: {
          opacity: 1
        },
        dataLabels: {
          style: {
            colors: ['#fff']
          }
        },
        stroke: {
          colors: ['#fff']
        },
        tooltip: {
          followCursor: true,
          marker: {
            show: false
          },
          x: {
            show: false
          }
        },
        legend: {
          position: 'top',
          markers: {
            shape: 'square',
            size: 10,
            offsetY: 2
          }
        },
        grid: {
          padding: {
            right: 20
          }
        }
      };
    }
  }, {
    key: "pie",
    value: function pie() {
      return {
        chart: {
          toolbar: {
            show: false
          }
        },
        plotOptions: {
          pie: {
            donut: {
              labels: {
                show: false
              }
            }
          }
        },
        dataLabels: {
          formatter: function formatter(val) {
            return val.toFixed(1) + '%';
          },
          style: {
            colors: ['#fff']
          },
          dropShadow: {
            enabled: true
          }
        },
        stroke: {
          colors: ['#fff']
        },
        fill: {
          opacity: 1,
          gradient: {
            shade: 'dark',
            shadeIntensity: 0.35,
            inverseColors: false,
            stops: [0, 100, 100]
          }
        },
        padding: {
          right: 0,
          left: 0
        },
        tooltip: {
          theme: 'dark',
          fillSeriesColor: true
        },
        legend: {
          position: 'right'
        }
      };
    }
  }, {
    key: "donut",
    value: function donut() {
      return {
        chart: {
          toolbar: {
            show: false
          }
        },
        dataLabels: {
          formatter: function formatter(val) {
            return val.toFixed(1) + '%';
          },
          style: {
            colors: ['#fff']
          },
          dropShadow: {
            enabled: true
          }
        },
        stroke: {
          colors: ['#fff']
        },
        fill: {
          opacity: 1,
          gradient: {
            shade: 'dark',
            shadeIntensity: 0.4,
            inverseColors: false,
            type: 'vertical',
            opacityFrom: 1,
            opacityTo: 1,
            stops: [70, 98, 100]
          }
        },
        padding: {
          right: 0,
          left: 0
        },
        tooltip: {
          theme: 'dark',
          fillSeriesColor: true
        },
        legend: {
          position: 'right'
        }
      };
    }
  }, {
    key: "radar",
    value: function radar() {
      this.opts.yaxis[0].labels.style.fontSize = '13px';
      this.opts.yaxis[0].labels.offsetY = 6;
      return {
        dataLabels: {
          enabled: true,
          style: {
            colors: ['#a8a8a8'],
            fontSize: '11px'
          }
        },
        stroke: {
          width: 2
        },
        markers: {
          size: 3,
          strokeWidth: 1,
          strokeOpacity: 1
        },
        fill: {
          opacity: 0.2
        },
        tooltip: {
          shared: false,
          intersect: true,
          followCursor: true
        },
        grid: {
          show: false
        },
        xaxis: {
          tooltip: {
            enabled: false
          },
          crosshairs: {
            show: false
          }
        }
      };
    }
  }, {
    key: "radialBar",
    value: function radialBar() {
      return {
        chart: {
          animations: {
            dynamicAnimation: {
              enabled: true,
              speed: 800
            }
          },
          toolbar: {
            show: false
          }
        },
        fill: {
          gradient: {
            shade: 'dark',
            shadeIntensity: 0.4,
            inverseColors: false,
            type: 'diagonal2',
            opacityFrom: 1,
            opacityTo: 1,
            stops: [70, 98, 100]
          }
        },
        padding: {
          right: 0,
          left: 0
        },
        legend: {
          show: false,
          position: 'right'
        },
        tooltip: {
          enabled: false,
          fillSeriesColor: true
        }
      };
    }
  }], [{
    key: "convertCatToNumeric",
    value: function convertCatToNumeric(opts) {
      opts.xaxis.type = 'numeric';
      opts.xaxis.convertedCatToNumeric = true;
      opts.xaxis.labels = opts.xaxis.labels || {};

      opts.xaxis.labels.formatter = opts.xaxis.labels.formatter || function (val) {
        return val;
      };

      opts.chart = opts.chart || {};
      opts.chart.zoom = opts.chart.zoom || window.Apex.chart && window.Apex.chart.zoom || {};
      var defaultFormatter = opts.xaxis.labels.formatter;
      var labels = opts.xaxis.categories && opts.xaxis.categories.length ? opts.xaxis.categories : opts.labels;

      if (labels && labels.length) {
        opts.xaxis.labels.formatter = function (val) {
          return defaultFormatter(labels[val - 1]);
        };
      }

      opts.xaxis.categories = [];
      opts.labels = [];
      opts.chart.zoom.enabled = opts.chart.zoom.enabled || false;
      return opts;
    }
  }]);

  return Defaults;
}();

/*
 ** Util functions which are dependent on ApexCharts instance
 */
var CoreUtils =
/*#__PURE__*/
function () {
  function CoreUtils(ctx) {
    _classCallCheck(this, CoreUtils);

    this.ctx = ctx;
    this.w = ctx.w;
  }

  _createClass(CoreUtils, [{
    key: "getStackedSeriesTotals",

    /**
     * @memberof CoreUtils
     * returns the sum of all individual values in a multiple stacked series
     * Eg. w.globals.series = [[32,33,43,12], [2,3,5,1]]
     *  @return [34,36,48,13]
     **/
    value: function getStackedSeriesTotals() {
      var w = this.w;
      var total = [];

      for (var i = 0; i < w.globals.series[w.globals.maxValsInArrayIndex].length; i++) {
        var t = 0;

        for (var j = 0; j < w.globals.series.length; j++) {
          t += w.globals.series[j][i];
        }

        total.push(t);
      }

      w.globals.stackedSeriesTotals = total;
      return total;
    } // get total of the all values inside all series

  }, {
    key: "getSeriesTotalByIndex",
    value: function getSeriesTotalByIndex() {
      var index = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

      if (index === null) {
        // non-plot chart types - pie / donut / circle
        return this.w.config.series.reduce(function (acc, cur) {
          return acc + cur;
        }, 0);
      } else {
        // axis charts - supporting multiple series
        return this.w.globals.series[index].reduce(function (acc, cur) {
          return acc + cur;
        }, 0);
      }
    }
  }, {
    key: "isSeriesNull",
    value: function isSeriesNull() {
      var index = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var r = [];

      if (index === null) {
        // non-plot chart types - pie / donut / circle
        r = this.w.config.series.filter(function (d) {
          return d !== null;
        });
      } else {
        // axis charts - supporting multiple series
        r = this.w.globals.series[index].filter(function (d) {
          return d !== null;
        });
      }

      return r.length === 0;
    }
  }, {
    key: "seriesHaveSameValues",
    value: function seriesHaveSameValues(index) {
      return this.w.globals.series[index].every(function (val, i, arr) {
        return val === arr[0];
      });
    } // maxValsInArrayIndex is the index of series[] which has the largest number of items

  }, {
    key: "getLargestSeries",
    value: function getLargestSeries() {
      var w = this.w;
      w.globals.maxValsInArrayIndex = w.globals.series.map(function (a) {
        return a.length;
      }).indexOf(Math.max.apply(Math, w.globals.series.map(function (a) {
        return a.length;
      })));
    }
  }, {
    key: "getLargestMarkerSize",
    value: function getLargestMarkerSize() {
      var w = this.w;
      var size = 0;
      w.globals.markers.size.forEach(function (m) {
        size = Math.max(size, m);
      });
      w.globals.markers.largestSize = size;
      return size;
    }
    /**
     * @memberof Core
     * returns the sum of all values in a series
     * Eg. w.globals.series = [[32,33,43,12], [2,3,5,1]]
     *  @return [120, 11]
     **/

  }, {
    key: "getSeriesTotals",
    value: function getSeriesTotals() {
      var w = this.w;
      w.globals.seriesTotals = w.globals.series.map(function (ser, index) {
        var total = 0;

        if (Array.isArray(ser)) {
          for (var j = 0; j < ser.length; j++) {
            total += ser[j];
          }
        } else {
          // for pie/donuts/gauges
          total += ser;
        }

        return total;
      });
    }
  }, {
    key: "getSeriesTotalsXRange",
    value: function getSeriesTotalsXRange(minX, maxX) {
      var w = this.w;
      var seriesTotalsXRange = w.globals.series.map(function (ser, index) {
        var total = 0;

        for (var j = 0; j < ser.length; j++) {
          if (w.globals.seriesX[index][j] > minX && w.globals.seriesX[index][j] < maxX) {
            total += ser[j];
          }
        }

        return total;
      });
      return seriesTotalsXRange;
    }
    /**
     * @memberof CoreUtils
     * returns the percentage value of all individual values which can be used in a 100% stacked series
     * Eg. w.globals.series = [[32, 33, 43, 12], [2, 3, 5, 1]]
     *  @return [[94.11, 91.66, 89.58, 92.30], [5.88, 8.33, 10.41, 7.7]]
     **/

  }, {
    key: "getPercentSeries",
    value: function getPercentSeries() {
      var w = this.w;
      w.globals.seriesPercent = w.globals.series.map(function (ser, index) {
        var seriesPercent = [];

        if (Array.isArray(ser)) {
          for (var j = 0; j < ser.length; j++) {
            var total = w.globals.stackedSeriesTotals[j];
            var percent = 100 * ser[j] / total;
            seriesPercent.push(percent);
          }
        } else {
          var _total = w.globals.seriesTotals.reduce(function (acc, val) {
            return acc + val;
          }, 0);

          var _percent = 100 * ser / _total;

          seriesPercent.push(_percent);
        }

        return seriesPercent;
      });
    }
  }, {
    key: "getCalculatedRatios",
    value: function getCalculatedRatios() {
      var gl = this.w.globals;
      var yRatio = [];
      var invertedYRatio = 0;
      var xRatio = 0;
      var initialXRatio = 0;
      var invertedXRatio = 0;
      var zRatio = 0;
      var baseLineY = [];
      var baseLineInvertedY = 0.1;
      var baseLineX = 0;
      gl.yRange = [];

      if (gl.isMultipleYAxis) {
        for (var i = 0; i < gl.minYArr.length; i++) {
          gl.yRange.push(Math.abs(gl.minYArr[i] - gl.maxYArr[i]));
          baseLineY.push(0);
        }
      } else {
        gl.yRange.push(Math.abs(gl.minY - gl.maxY));
      }

      gl.xRange = Math.abs(gl.maxX - gl.minX);
      gl.zRange = Math.abs(gl.maxZ - gl.minZ); // multiple y axis

      for (var _i = 0; _i < gl.yRange.length; _i++) {
        yRatio.push(gl.yRange[_i] / gl.gridHeight);
      }

      xRatio = gl.xRange / gl.gridWidth;
      initialXRatio = Math.abs(gl.initialmaxX - gl.initialminX) / gl.gridWidth;
      invertedYRatio = gl.yRange / gl.gridWidth;
      invertedXRatio = gl.xRange / gl.gridHeight;
      zRatio = gl.zRange / gl.gridHeight * 16;

      if (gl.minY !== Number.MIN_VALUE && Math.abs(gl.minY) !== 0) {
        // Negative numbers present in series
        gl.hasNegs = true;
      }

      if (gl.isMultipleYAxis) {
        baseLineY = []; // baseline variables is the 0 of the yaxis which will be needed when there are negatives

        for (var _i2 = 0; _i2 < yRatio.length; _i2++) {
          baseLineY.push(-gl.minYArr[_i2] / yRatio[_i2]);
        }
      } else {
        baseLineY.push(-gl.minY / yRatio[0]);

        if (gl.minY !== Number.MIN_VALUE && Math.abs(gl.minY) !== 0) {
          baseLineInvertedY = -gl.minY / invertedYRatio; // this is for bar chart

          baseLineX = gl.minX / xRatio;
        }
      }

      return {
        yRatio: yRatio,
        invertedYRatio: invertedYRatio,
        zRatio: zRatio,
        xRatio: xRatio,
        initialXRatio: initialXRatio,
        invertedXRatio: invertedXRatio,
        baseLineInvertedY: baseLineInvertedY,
        baseLineY: baseLineY,
        baseLineX: baseLineX
      };
    }
  }, {
    key: "getLogSeries",
    value: function getLogSeries(series) {
      var w = this.w;
      w.globals.seriesLog = series.map(function (s, i) {
        if (w.config.yaxis[i] && w.config.yaxis[i].logarithmic) {
          return s.map(function (d) {
            if (d === null) return null;
            var logVal = (Math.log(d) - Math.log(w.globals.minYArr[i])) / (Math.log(w.globals.maxYArr[i]) - Math.log(w.globals.minYArr[i]));
            return logVal;
          });
        } else {
          return s;
        }
      });
      return w.globals.seriesLog;
    }
  }, {
    key: "getLogYRatios",
    value: function getLogYRatios(yRatio) {
      var _this = this;

      var w = this.w;
      var gl = this.w.globals;
      gl.yLogRatio = yRatio.slice();
      gl.logYRange = gl.yRange.map(function (yRange, i) {
        if (w.config.yaxis[i] && _this.w.config.yaxis[i].logarithmic) {
          var maxY = -Number.MAX_VALUE;
          var minY = Number.MIN_VALUE;
          var range = 1;
          gl.seriesLog.forEach(function (s, si) {
            s.forEach(function (v) {
              if (w.config.yaxis[si] && w.config.yaxis[si].logarithmic) {
                maxY = Math.max(v, maxY);
                minY = Math.min(v, minY);
              }
            });
          });
          range = Math.pow(gl.yRange[i], Math.abs(minY - maxY) / gl.yRange[i]);
          gl.yLogRatio[i] = range / gl.gridHeight;
          return range;
        }
      });
      return gl.yLogRatio;
    } // Some config objects can be array - and we need to extend them correctly

  }], [{
    key: "checkComboSeries",
    value: function checkComboSeries(series) {
      var comboCharts = false;
      var comboChartsHasBars = false; // if user specified a type in series too, turn on comboCharts flag

      if (series.length && typeof series[0].type !== 'undefined') {
        comboCharts = true;
        series.forEach(function (s) {
          if (s.type === 'bar' || s.type === 'column') {
            comboChartsHasBars = true;
          }
        });
      }

      return {
        comboCharts: comboCharts,
        comboChartsHasBars: comboChartsHasBars
      };
    }
  }, {
    key: "extendArrayProps",
    value: function extendArrayProps(configInstance, options) {
      if (options.yaxis) {
        options = configInstance.extendYAxis(options);
      }

      if (options.annotations) {
        if (options.annotations.yaxis) {
          options = configInstance.extendYAxisAnnotations(options);
        }

        if (options.annotations.xaxis) {
          options = configInstance.extendXAxisAnnotations(options);
        }

        if (options.annotations.points) {
          options = configInstance.extendPointAnnotations(options);
        }
      }

      return options;
    }
  }]);

  return CoreUtils;
}();

/**
 * ApexCharts Config Class for extending user options with pre-defined ApexCharts config.
 *
 * @module Config
 **/

var Config =
/*#__PURE__*/
function () {
  function Config(opts) {
    _classCallCheck(this, Config);

    this.opts = opts;
  }

  _createClass(Config, [{
    key: "init",
    value: function init() {
      var opts = this.opts;
      var options = new Options();
      var defaults = new Defaults(opts);
      this.chartType = opts.chart.type;

      if (this.chartType === 'histogram') {
        // technically, a histogram can be drawn by a column chart with no spaces in between
        opts.chart.type = 'bar';
        opts = Utils.extend({
          plotOptions: {
            bar: {
              columnWidth: '99.99%'
            }
          }
        }, opts);
      }

      opts.series = this.checkEmptySeries(opts.series);
      opts = this.extendYAxis(opts);
      opts = this.extendAnnotations(opts);
      var config = options.init();
      var newDefaults = {};

      if (opts && _typeof(opts) === 'object') {
        var chartDefaults = {};

        switch (this.chartType) {
          case 'line':
            chartDefaults = defaults.line();
            break;

          case 'area':
            chartDefaults = defaults.area();
            break;

          case 'bar':
            chartDefaults = defaults.bar();
            break;

          case 'candlestick':
            chartDefaults = defaults.candlestick();
            break;

          case 'rangeBar':
            chartDefaults = defaults.rangeBar();
            break;

          case 'histogram':
            chartDefaults = defaults.bar();
            break;

          case 'bubble':
            chartDefaults = defaults.bubble();
            break;

          case 'scatter':
            chartDefaults = defaults.scatter();
            break;

          case 'heatmap':
            chartDefaults = defaults.heatmap();
            break;

          case 'pie':
            chartDefaults = defaults.pie();
            break;

          case 'donut':
            chartDefaults = defaults.donut();
            break;

          case 'radar':
            chartDefaults = defaults.radar();
            break;

          case 'radialBar':
            chartDefaults = defaults.radialBar();
            break;

          default:
            chartDefaults = defaults.line();
        }

        if (opts.chart.brush && opts.chart.brush.enabled) {
          chartDefaults = defaults.brush(chartDefaults);
        }

        if (opts.chart.stacked && opts.chart.stackType === '100%') {
          defaults.stacked100();
        } // If user has specified a dark theme, make the tooltip dark too


        this.checkForDarkTheme(window.Apex); // check global window Apex options

        this.checkForDarkTheme(opts); // check locally passed options

        opts.xaxis = opts.xaxis || window.Apex.xaxis || {};
        var combo = CoreUtils.checkComboSeries(opts.series);

        if ((opts.chart.type === 'line' || opts.chart.type === 'area' || opts.chart.type === 'scatter') && !combo.comboChartsHasBars && opts.xaxis.type !== 'datetime' && opts.xaxis.type !== 'numeric' && opts.xaxis.tickPlacement !== 'between') {
          opts = Defaults.convertCatToNumeric(opts);
        }

        if (opts.chart.sparkline && opts.chart.sparkline.enabled || window.Apex.chart && window.Apex.chart.sparkline && window.Apex.chart.sparkline.enabled) {
          chartDefaults = defaults.sparkline(chartDefaults);
        }

        newDefaults = Utils.extend(config, chartDefaults);
      } // config should cascade in this fashion
      // default-config < global-apex-variable-config < user-defined-config
      // get GLOBALLY defined options and merge with the default config


      var mergedWithDefaultConfig = Utils.extend(newDefaults, window.Apex); // get the merged config and extend with user defined config

      config = Utils.extend(mergedWithDefaultConfig, opts); // some features are not supported. those mismatches should be handled

      config = this.handleUserInputErrors(config);
      return config;
    }
  }, {
    key: "extendYAxis",
    value: function extendYAxis(opts) {
      var options = new Options();

      if (typeof opts.yaxis === 'undefined') {
        opts.yaxis = {};
      } // extend global yaxis config (only if object is provided / not an array)


      if (opts.yaxis.constructor !== Array && window.Apex.yaxis && window.Apex.yaxis.constructor !== Array) {
        opts.yaxis = Utils.extend(opts.yaxis, window.Apex.yaxis);
      } // as we can't extend nested object's array with extend, we need to do it first
      // user can provide either an array or object in yaxis config


      if (opts.yaxis.constructor !== Array) {
        // convert the yaxis to array if user supplied object
        opts.yaxis = [Utils.extend(options.yAxis, opts.yaxis)];
      } else {
        opts.yaxis = Utils.extendArray(opts.yaxis, options.yAxis);
      }

      return opts;
    } // annotations also accepts array, so we need to extend them manually

  }, {
    key: "extendAnnotations",
    value: function extendAnnotations(opts) {
      if (typeof opts.annotations === 'undefined') {
        opts.annotations = {};
        opts.annotations.yaxis = [];
        opts.annotations.xaxis = [];
        opts.annotations.points = [];
      }

      opts = this.extendYAxisAnnotations(opts);
      opts = this.extendXAxisAnnotations(opts);
      opts = this.extendPointAnnotations(opts);
      return opts;
    }
  }, {
    key: "extendYAxisAnnotations",
    value: function extendYAxisAnnotations(opts) {
      var options = new Options();
      opts.annotations.yaxis = Utils.extendArray(typeof opts.annotations.yaxis !== 'undefined' ? opts.annotations.yaxis : [], options.yAxisAnnotation);
      return opts;
    }
  }, {
    key: "extendXAxisAnnotations",
    value: function extendXAxisAnnotations(opts) {
      var options = new Options();
      opts.annotations.xaxis = Utils.extendArray(typeof opts.annotations.xaxis !== 'undefined' ? opts.annotations.xaxis : [], options.xAxisAnnotation);
      return opts;
    }
  }, {
    key: "extendPointAnnotations",
    value: function extendPointAnnotations(opts) {
      var options = new Options();
      opts.annotations.points = Utils.extendArray(typeof opts.annotations.points !== 'undefined' ? opts.annotations.points : [], options.pointAnnotation);
      return opts;
    }
  }, {
    key: "checkForDarkTheme",
    value: function checkForDarkTheme(opts) {
      if (opts.theme && opts.theme.mode === 'dark') {
        if (!opts.tooltip) {
          opts.tooltip = {};
        }

        if (opts.tooltip.theme !== 'light') {
          opts.tooltip.theme = 'dark';
        }

        if (!opts.chart.foreColor) {
          opts.chart.foreColor = '#f6f7f8';
        }

        if (!opts.theme.palette) {
          opts.theme.palette = 'palette4';
        }
      }
    }
  }, {
    key: "checkEmptySeries",
    value: function checkEmptySeries(ser) {
      if (ser.length === 0) {
        return [{
          data: []
        }];
      }

      return ser;
    }
  }, {
    key: "handleUserInputErrors",
    value: function handleUserInputErrors(opts) {
      var config = opts; // conflicting tooltip option. intersect makes sure to focus on 1 point at a time. Shared cannot be used along with it

      if (config.tooltip.shared && config.tooltip.intersect) {
        throw new Error('tooltip.shared cannot be enabled when tooltip.intersect is true. Turn off any other option by setting it to false.');
      }

      if (config.chart.scroller) {
        console.warn('Scroller has been deprecated since v2.0.0. Please remove the configuration for chart.scroller');
      }

      if ((config.chart.type === 'bar' || config.chart.type === 'rangeBar') && config.plotOptions.bar.horizontal) {
        // No multiple yaxis for bars
        if (config.yaxis.length > 1) {
          throw new Error('Multiple Y Axis for bars are not supported. Switch to column chart by setting plotOptions.bar.horizontal=false');
        } // if yaxis is reversed in horizontal bar chart, you should draw the y-axis on right side


        if (config.yaxis[0].reversed) {
          config.yaxis[0].opposite = true;
        }

        config.xaxis.tooltip.enabled = false; // no xaxis tooltip for horizontal bar

        config.yaxis[0].tooltip.enabled = false; // no xaxis tooltip for horizontal bar

        config.chart.zoom.enabled = false; // no zooming for horz bars
      }

      if (config.chart.type === 'bar' || config.chart.type === 'rangeBar') {
        if (config.tooltip.shared) {
          if (config.xaxis.crosshairs.width === 'barWidth' && config.series.length > 1) {
            console.warn('crosshairs.width = "barWidth" is only supported in single series, not in a multi-series barChart.');
            config.xaxis.crosshairs.width = 'tickWidth';
          }

          if (config.plotOptions.bar.horizontal) {
            config.states.hover.type = 'none';
            config.tooltip.shared = false;
          }

          if (!config.tooltip.followCursor) {
            console.warn('followCursor option in shared columns cannot be turned off. Please set %ctooltip.followCursor: true', 'color: blue;');
            config.tooltip.followCursor = true;
          }
        }
      }

      if (config.chart.type === 'candlestick') {
        if (config.yaxis[0].reversed) {
          console.warn('Reversed y-axis in candlestick chart is not supported.');
          config.yaxis[0].reversed = false;
        }
      }

      if (config.chart.group && config.yaxis[0].labels.minWidth === 0) {
        console.warn('It looks like you have multiple charts in synchronization. You must provide yaxis.labels.minWidth which must be EQUAL for all grouped charts to prevent incorrect behaviour.');
      } // if user supplied array for stroke width, it will only be applicable to line/area charts, for any other charts, revert back to Number


      if (Array.isArray(config.stroke.width)) {
        if (config.chart.type !== 'line' && config.chart.type !== 'area') {
          console.warn('stroke.width option accepts array only for line and area charts. Reverted back to Number');
          config.stroke.width = config.stroke.width[0];
        }
      }

      return config;
    }
  }]);

  return Config;
}();

var Globals =
/*#__PURE__*/
function () {
  function Globals() {
    _classCallCheck(this, Globals);
  }

  _createClass(Globals, [{
    key: "globalVars",
    value: function globalVars(config) {
      return {
        chartID: null,
        // chart ID - apexcharts-cuid
        cuid: null,
        // chart ID - random numbers excluding "apexcharts" part
        events: {
          beforeMount: [],
          mounted: [],
          updated: [],
          clicked: [],
          selection: [],
          dataPointSelection: [],
          zoomed: [],
          scrolled: []
        },
        colors: [],
        clientX: null,
        clientY: null,
        fill: {
          colors: []
        },
        stroke: {
          colors: []
        },
        dataLabels: {
          style: {
            colors: []
          }
        },
        radarPolygons: {
          fill: {
            colors: []
          }
        },
        markers: {
          colors: [],
          size: config.markers.size,
          largestSize: 0
        },
        animationEnded: false,
        isTouchDevice: 'ontouchstart' in window || navigator.msMaxTouchPoints,
        isDirty: false,
        // chart has been updated after the initial render. This is different than dataChanged property. isDirty means user manually called some method to update
        initialConfig: null,
        // we will store the first config user has set to go back when user finishes interactions like zooming and come out of it
        lastXAxis: [],
        lastYAxis: [],
        series: [],
        // the MAIN series array (y values)
        seriesRangeStart: [],
        // the clone of series becomes the start in range
        seriesRangeEnd: [],
        // the end values in range chart
        seriesPercent: [],
        // the percentage values of the given series
        seriesTotals: [],
        stackedSeriesTotals: [],
        seriesX: [],
        // store the numeric x values in this array (x values)
        seriesZ: [],
        // The 3rd "Z" dimension for bubbles chart (z values)
        labels: [],
        // store the text to draw on x axis
        // Don't mutate the labels, many things including tooltips depends on it!
        timelineLabels: [],
        // store the timeline Labels in another variable
        invertedTimelineLabels: [],
        // for rangebar timeline
        seriesNames: [],
        // same as labels, used in non axis charts
        noLabelsProvided: false,
        // if user didn't provide any categories/labels or x values, fallback to 1,2,3,4...
        allSeriesCollapsed: false,
        collapsedSeries: [],
        // when user collapses a series, it goes into this array
        collapsedSeriesIndices: [],
        // this stores the index of the collapsedSeries instead of whole object for quick access
        ancillaryCollapsedSeries: [],
        // when user collapses an "alwaysVisible" series, it goes into this array
        ancillaryCollapsedSeriesIndices: [],
        // this stores the index of the ancillaryCollapsedSeries whose y-axis is always visible
        risingSeries: [],
        // when user re-opens a collapsed series, it goes here
        dataFormatXNumeric: false,
        // boolean value to indicate user has passed numeric x values
        selectedDataPoints: [],
        ignoreYAxisIndexes: [],
        // when series are being collapsed in multiple y axes, ignore certain index
        padHorizontal: 0,
        maxValsInArrayIndex: 0,
        zoomEnabled: config.chart.toolbar.autoSelected === 'zoom' && config.chart.toolbar.tools.zoom && config.chart.zoom.enabled,
        panEnabled: config.chart.toolbar.autoSelected === 'pan' && config.chart.toolbar.tools.pan,
        selectionEnabled: config.chart.toolbar.autoSelected === 'selection' && config.chart.toolbar.tools.selection,
        yaxis: null,
        minY: Number.MIN_VALUE,
        //  is 5e-324, i.e. the smallest positive number
        // NOTE: If there are multiple y axis, the first yaxis array element will be considered for all y values calculations. Rest all will be calculated based on that
        maxY: -Number.MAX_VALUE,
        // is -1.7976931348623157e+308
        // NOTE: The above note for minY applies here as well
        minYArr: [],
        maxYArr: [],
        maxX: -Number.MAX_VALUE,
        // is -1.7976931348623157e+308
        initialmaxX: -Number.MAX_VALUE,
        minX: Number.MIN_VALUE,
        //  is 5e-324, i.e. the smallest positive number
        initialminX: Number.MIN_VALUE,
        minZ: Number.MIN_VALUE,
        // Max Z value in charts with Z axis
        maxZ: -Number.MAX_VALUE,
        // Max Z value in charts with Z axis
        minXDiff: Number.MAX_VALUE,
        mousedown: false,
        lastClientPosition: {},
        // don't reset this variable this the chart is destroyed. It is used to detect right or left mousemove in panning
        visibleXRange: undefined,
        yRange: [],
        // this property is the absolute sum of positive and negative values [eg (-100 + 200 = 300)] - yAxis
        zRange: 0,
        // zAxis Range (for bubble charts)
        xRange: 0,
        // xAxis range
        yValueDecimal: 0,
        // are there floating numbers in the series. If yes, this represent the len of the decimals
        total: 0,
        SVGNS: 'http://www.w3.org/2000/svg',
        // svg namespace
        svgWidth: 0,
        // the whole svg width
        svgHeight: 0,
        // the whole svg height
        noData: false,
        // whether there is any data to display or not
        locale: {},
        // the current locale values will be preserved here for global access
        dom: {},
        // for storing all dom nodes in this particular property
        // elWrap: null, // the element that wraps everything
        // elGraphical: null, // this contains lines/areas/bars/pies
        // elGridRect: null, // paths going outside this area will be clipped
        // elGridRectMask: null, // clipping will happen with this mask
        // elGridRectMarkerMask: null, // clipping will happen with this mask
        // elLegendWrap: null, // the whole legend area
        // elDefs: null, // [defs] element
        memory: {
          methodsToExec: []
        },
        shouldAnimate: true,
        skipLastTimelinelabel: false,
        // when last label is cropped, skip drawing it
        delayedElements: [],
        // element which appear after animation has finished
        axisCharts: true,
        // chart type = line or area or bar
        // (refer them also as plot charts in the code)
        isXNumeric: false,
        // bool: data was provided in a {[x,y], [x,y]} pattern
        isDataXYZ: false,
        // bool: data was provided in a {[x,y,z]} pattern
        resized: false,
        // bool: user has resized
        resizeTimer: null,
        // timeout function to make a small delay before
        // drawing when user resized
        comboCharts: false,
        // bool: whether it's a combination of line/column
        comboChartsHasBars: false,
        // bool: whether it's a combination of line/column
        dataChanged: false,
        // bool: has data changed dynamically
        previousPaths: [],
        // array: when data is changed, it will animate from
        // previous paths
        seriesXvalues: [],
        // we will need this in tooltip (it's x position)
        // when we will have unequal x values, we will need
        // some way to get x value depending on mouse pointer
        seriesYvalues: [],
        // we will need this when deciding which series
        // user hovered on
        seriesCandleO: [],
        // candle stick open values
        seriesCandleH: [],
        // candle stick high values
        seriesCandleL: [],
        // candle stick low values
        seriesCandleC: [],
        // candle stick close values
        allSeriesHasEqualX: true,
        dataPoints: 0,
        // the longest series length
        pointsArray: [],
        // store the points positions here to draw later on hover
        // format is - [[x,y],[x,y]... [x,y]]
        dataLabelsRects: [],
        // store the positions of datalabels to prevent collision
        lastDrawnDataLabelsIndexes: [],
        hasNullValues: false,
        // bool: whether series contains null values
        easing: null,
        // function: animation effect to apply
        zoomed: false,
        // whether user has zoomed or not
        gridWidth: 0,
        // drawable width of actual graphs (series paths)
        gridHeight: 0,
        // drawable height of actual graphs (series paths)
        yAxisScale: [],
        xAxisScale: null,
        xAxisTicksPositions: [],
        timescaleTicks: [],
        rotateXLabels: false,
        defaultLabels: false,
        xLabelFormatter: undefined,
        // formatter for x axis labels
        yLabelFormatters: [],
        xaxisTooltipFormatter: undefined,
        // formatter for x axis tooltip
        ttKeyFormatter: undefined,
        ttVal: undefined,
        ttZFormatter: undefined,
        LINE_HEIGHT_RATIO: 1.618,
        xAxisLabelsHeight: 0,
        yAxisLabelsWidth: 0,
        scaleX: 1,
        scaleY: 1,
        translateX: 0,
        translateY: 0,
        translateYAxisX: [],
        yLabelsCoords: [],
        yTitleCoords: [],
        yAxisWidths: [],
        translateXAxisY: 0,
        translateXAxisX: 0,
        tooltip: null,
        tooltipOpts: null
      };
    }
  }, {
    key: "init",
    value: function init(config) {
      var globals = this.globalVars(config);
      globals.initialConfig = Utils.extend({}, config);
      globals.initialSeries = JSON.parse(JSON.stringify(globals.initialConfig.series));
      globals.lastXAxis = JSON.parse(JSON.stringify(globals.initialConfig.xaxis));
      globals.lastYAxis = JSON.parse(JSON.stringify(globals.initialConfig.yaxis));
      return globals;
    }
  }]);

  return Globals;
}();

/**
 * ApexCharts Base Class for extending user options with pre-defined ApexCharts config.
 *
 * @module Base
 **/

var Base =
/*#__PURE__*/
function () {
  function Base(opts) {
    _classCallCheck(this, Base);

    this.opts = opts;
  }

  _createClass(Base, [{
    key: "init",
    value: function init() {
      var config = new Config(this.opts).init();
      var globals = new Globals().init(config);
      var w = {
        config: config,
        globals: globals
      };
      return w;
    }
  }]);

  return Base;
}();

/**
 * ApexCharts Fill Class for setting fill options of the paths.
 *
 * @module Fill
 **/

var Fill =
/*#__PURE__*/
function () {
  function Fill(ctx) {
    _classCallCheck(this, Fill);

    this.ctx = ctx;
    this.w = ctx.w;
    this.opts = null;
    this.seriesIndex = 0;
  }

  _createClass(Fill, [{
    key: "clippedImgArea",
    value: function clippedImgArea(params) {
      var w = this.w;
      var cnf = w.config;
      var svgW = parseInt(w.globals.gridWidth);
      var svgH = parseInt(w.globals.gridHeight);
      var size = svgW > svgH ? svgW : svgH;
      var fillImg = params.image;
      var imgWidth = 0;
      var imgHeight = 0;

      if (typeof params.width === 'undefined' && typeof params.height === 'undefined') {
        if (cnf.fill.image.width !== undefined && cnf.fill.image.height !== undefined) {
          imgWidth = cnf.fill.image.width + 1;
          imgHeight = cnf.fill.image.height;
        } else {
          imgWidth = size + 1;
          imgHeight = size;
        }
      } else {
        imgWidth = params.width;
        imgHeight = params.height;
      }

      var elPattern = document.createElementNS(w.globals.SVGNS, 'pattern');
      Graphics.setAttrs(elPattern, {
        id: params.patternID,
        patternUnits: params.patternUnits ? params.patternUnits : 'userSpaceOnUse',
        width: imgWidth + 'px',
        height: imgHeight + 'px'
      });
      var elImage = document.createElementNS(w.globals.SVGNS, 'image');
      elPattern.appendChild(elImage);
      elImage.setAttributeNS('http://www.w3.org/1999/xlink', 'href', fillImg);
      Graphics.setAttrs(elImage, {
        x: 0,
        y: 0,
        preserveAspectRatio: 'none',
        width: imgWidth + 'px',
        height: imgHeight + 'px'
      });
      elImage.style.opacity = params.opacity;
      w.globals.dom.elDefs.node.appendChild(elPattern);
    }
  }, {
    key: "getSeriesIndex",
    value: function getSeriesIndex(opts) {
      var w = this.w;

      if (w.config.chart.type === 'bar' && w.config.plotOptions.bar.distributed || w.config.chart.type === 'heatmap') {
        this.seriesIndex = opts.seriesNumber;
      } else {
        this.seriesIndex = opts.seriesNumber % w.globals.series.length;
      }

      return this.seriesIndex;
    }
  }, {
    key: "fillPath",
    value: function fillPath(opts) {
      var w = this.w;
      this.opts = opts;
      var cnf = this.w.config;
      var pathFill;
      var patternFill, gradientFill;
      this.seriesIndex = this.getSeriesIndex(opts);
      var fillColors = this.getFillColors();
      var fillColor = fillColors[this.seriesIndex];

      if (typeof fillColor === 'function') {
        fillColor = fillColor({
          seriesIndex: this.seriesIndex,
          value: opts.value,
          w: w
        });
      }

      var fillType = this.getFillType(this.seriesIndex);
      var fillOpacity = Array.isArray(cnf.fill.opacity) ? cnf.fill.opacity[this.seriesIndex] : cnf.fill.opacity;
      var defaultColor = fillColor;

      if (opts.color) {
        fillColor = opts.color;
      }

      if (fillColor.indexOf('rgb') === -1) {
        defaultColor = Utils.hexToRgba(fillColor, fillOpacity);
      } else {
        if (fillColor.indexOf('rgba') > -1) {
          fillOpacity = 0 + '.' + Utils.getOpacityFromRGBA(fillColors[this.seriesIndex]);
        }
      }

      if (fillType === 'pattern') {
        patternFill = this.handlePatternFill(patternFill, fillColor, fillOpacity, defaultColor);
      }

      if (fillType === 'gradient') {
        gradientFill = this.handleGradientFill(gradientFill, fillColor, fillOpacity, this.seriesIndex);
      }

      if (cnf.fill.image.src.length > 0 && fillType === 'image') {
        if (opts.seriesNumber < cnf.fill.image.src.length) {
          this.clippedImgArea({
            opacity: fillOpacity,
            image: cnf.fill.image.src[opts.seriesNumber],
            patternUnits: opts.patternUnits,
            patternID: "pattern".concat(w.globals.cuid).concat(opts.seriesNumber + 1)
          });
          pathFill = "url(#pattern".concat(w.globals.cuid).concat(opts.seriesNumber + 1, ")");
        } else {
          pathFill = defaultColor;
        }
      } else if (fillType === 'gradient') {
        pathFill = gradientFill;
      } else if (fillType === 'pattern') {
        pathFill = patternFill;
      } else {
        pathFill = defaultColor;
      } // override pattern/gradient if opts.solid is true


      if (opts.solid) {
        pathFill = defaultColor;
      }

      return pathFill;
    }
  }, {
    key: "getFillType",
    value: function getFillType(seriesIndex) {
      var w = this.w;

      if (Array.isArray(w.config.fill.type)) {
        return w.config.fill.type[seriesIndex];
      } else {
        return w.config.fill.type;
      }
    }
  }, {
    key: "getFillColors",
    value: function getFillColors() {
      var w = this.w;
      var cnf = w.config;
      var opts = this.opts;
      var fillColors = [];

      if (w.globals.comboCharts) {
        if (w.config.series[this.seriesIndex].type === 'line') {
          if (w.globals.stroke.colors instanceof Array) {
            fillColors = w.globals.stroke.colors;
          } else {
            fillColors.push(w.globals.stroke.colors);
          }
        } else {
          if (w.globals.fill.colors instanceof Array) {
            fillColors = w.globals.fill.colors;
          } else {
            fillColors.push(w.globals.fill.colors);
          }
        }
      } else {
        if (cnf.chart.type === 'line') {
          if (w.globals.stroke.colors instanceof Array) {
            fillColors = w.globals.stroke.colors;
          } else {
            fillColors.push(w.globals.stroke.colors);
          }
        } else {
          if (w.globals.fill.colors instanceof Array) {
            fillColors = w.globals.fill.colors;
          } else {
            fillColors.push(w.globals.fill.colors);
          }
        }
      } // colors passed in arguments


      if (typeof opts.fillColors !== 'undefined') {
        fillColors = [];

        if (opts.fillColors instanceof Array) {
          fillColors = opts.fillColors.slice();
        } else {
          fillColors.push(opts.fillColors);
        }
      }

      return fillColors;
    }
  }, {
    key: "handlePatternFill",
    value: function handlePatternFill(patternFill, fillColor, fillOpacity, defaultColor) {
      var cnf = this.w.config;
      var opts = this.opts;
      var graphics = new Graphics(this.ctx);
      var patternStrokeWidth = cnf.fill.pattern.strokeWidth === undefined ? Array.isArray(cnf.stroke.width) ? cnf.stroke.width[this.seriesIndex] : cnf.stroke.width : Array.isArray(cnf.fill.pattern.strokeWidth) ? cnf.fill.pattern.strokeWidth[this.seriesIndex] : cnf.fill.pattern.strokeWidth;
      var patternLineColor = fillColor;

      if (cnf.fill.pattern.style instanceof Array) {
        if (typeof cnf.fill.pattern.style[opts.seriesNumber] !== 'undefined') {
          var pf = graphics.drawPattern(cnf.fill.pattern.style[opts.seriesNumber], cnf.fill.pattern.width, cnf.fill.pattern.height, patternLineColor, patternStrokeWidth, fillOpacity);
          patternFill = pf;
        } else {
          patternFill = defaultColor;
        }
      } else {
        patternFill = graphics.drawPattern(cnf.fill.pattern.style, cnf.fill.pattern.width, cnf.fill.pattern.height, patternLineColor, patternStrokeWidth, fillOpacity);
      }

      return patternFill;
    }
  }, {
    key: "handleGradientFill",
    value: function handleGradientFill(gradientFill, fillColor, fillOpacity, i) {
      var cnf = this.w.config;
      var opts = this.opts;
      var graphics = new Graphics(this.ctx);
      var utils = new Utils();
      var type = cnf.fill.gradient.type;
      var gradientFrom, gradientTo;
      var opacityFrom = cnf.fill.gradient.opacityFrom === undefined ? fillOpacity : Array.isArray(cnf.fill.gradient.opacityFrom) ? cnf.fill.gradient.opacityFrom[i] : cnf.fill.gradient.opacityFrom;
      var opacityTo = cnf.fill.gradient.opacityTo === undefined ? fillOpacity : Array.isArray(cnf.fill.gradient.opacityTo) ? cnf.fill.gradient.opacityTo[i] : cnf.fill.gradient.opacityTo;
      gradientFrom = fillColor;

      if (cnf.fill.gradient.gradientToColors === undefined || cnf.fill.gradient.gradientToColors.length === 0) {
        if (cnf.fill.gradient.shade === 'dark') {
          gradientTo = utils.shadeColor(parseFloat(cnf.fill.gradient.shadeIntensity) * -1, fillColor);
        } else {
          gradientTo = utils.shadeColor(parseFloat(cnf.fill.gradient.shadeIntensity), fillColor);
        }
      } else {
        gradientTo = cnf.fill.gradient.gradientToColors[opts.seriesNumber];
      }

      if (cnf.fill.gradient.inverseColors) {
        var t = gradientFrom;
        gradientFrom = gradientTo;
        gradientTo = t;
      }

      gradientFill = graphics.drawGradient(type, gradientFrom, gradientTo, opacityFrom, opacityTo, opts.size, cnf.fill.gradient.stops, cnf.fill.gradient.colorStops, i);
      return gradientFill;
    }
  }]);

  return Fill;
}();

/**
 * ApexCharts Markers Class for drawing points on y values in axes charts.
 *
 * @module Markers
 **/

var Markers =
/*#__PURE__*/
function () {
  function Markers(ctx, opts) {
    _classCallCheck(this, Markers);

    this.ctx = ctx;
    this.w = ctx.w;
  }

  _createClass(Markers, [{
    key: "setGlobalMarkerSize",
    value: function setGlobalMarkerSize() {
      var w = this.w;
      w.globals.markers.size = Array.isArray(w.config.markers.size) ? w.config.markers.size : [w.config.markers.size];

      if (w.globals.markers.size.length > 0) {
        if (w.globals.markers.size.length < w.globals.series.length + 1) {
          for (var i = 0; i <= w.globals.series.length; i++) {
            if (typeof w.globals.markers.size[i] === 'undefined') {
              w.globals.markers.size.push(w.globals.markers.size[0]);
            }
          }
        }
      } else {
        w.globals.markers.size = w.config.series.map(function (s) {
          return w.config.markers.size;
        });
      }
    }
  }, {
    key: "plotChartMarkers",
    value: function plotChartMarkers(pointsPos, seriesIndex, j) {
      var _this = this;

      var w = this.w;
      var i = seriesIndex;
      var p = pointsPos;
      var elPointsWrap = null;
      var graphics = new Graphics(this.ctx);
      var point;

      if (w.globals.markers.size[seriesIndex] > 0) {
        elPointsWrap = graphics.group({
          class: 'apexcharts-series-markers'
        });
        elPointsWrap.attr('clip-path', "url(#gridRectMarkerMask".concat(w.globals.cuid, ")"));
      }

      if (p.x instanceof Array) {
        var _loop = function _loop(q) {
          var dataPointIndex = j; // a small hack as we have 2 points for the first val to connect it

          if (j === 1 && q === 0) dataPointIndex = 0;
          if (j === 1 && q === 1) dataPointIndex = 1;
          var PointClasses = 'apexcharts-marker';

          if ((w.config.chart.type === 'line' || w.config.chart.type === 'area') && !w.globals.comboCharts && !w.config.tooltip.intersect) {
            PointClasses += ' no-pointer-events';
          }

          var shouldMarkerDraw = Array.isArray(w.config.markers.size) ? w.globals.markers.size[seriesIndex] > 0 : w.config.markers.size > 0;

          if (shouldMarkerDraw) {
            if (Utils.isNumber(p.y[q])) {
              PointClasses += " w".concat((Math.random() + 1).toString(36).substring(4));
            } else {
              PointClasses = 'apexcharts-nullpoint';
            }

            var opts = _this.getMarkerConfig(PointClasses, seriesIndex); // discrete markers is an option where user can specify a particular marker with different size and color


            w.config.markers.discrete.map(function (marker) {
              if (marker.seriesIndex === seriesIndex && marker.dataPointIndex === dataPointIndex) {
                opts.pointStrokeColor = marker.strokeColor;
                opts.pointFillColor = marker.fillColor;
                opts.pSize = marker.size;
              }
            });

            if (w.config.series[i].data[j]) {
              if (w.config.series[i].data[j].fillColor) {
                opts.pointFillColor = w.config.series[i].data[j].fillColor;
              }

              if (w.config.series[i].data[j].strokeColor) {
                opts.pointStrokeColor = w.config.series[i].data[j].strokeColor;
              }
            }

            point = graphics.drawMarker(p.x[q], p.y[q], opts);
            point.attr('rel', dataPointIndex);
            point.attr('j', dataPointIndex);
            point.attr('index', seriesIndex);
            point.node.setAttribute('default-marker-size', opts.pSize);
            var filters = new Filters(_this.ctx);
            filters.setSelectionFilter(point, seriesIndex, dataPointIndex);

            _this.addEvents(point);

            if (elPointsWrap) {
              elPointsWrap.add(point);
            }
          } else {
            // dynamic array creation - multidimensional
            if (typeof w.globals.pointsArray[seriesIndex] === 'undefined') w.globals.pointsArray[seriesIndex] = [];
            w.globals.pointsArray[seriesIndex].push([p.x[q], p.y[q]]);
          }
        };

        for (var q = 0; q < p.x.length; q++) {
          _loop(q);
        }
      }

      return elPointsWrap;
    }
  }, {
    key: "getMarkerConfig",
    value: function getMarkerConfig(cssClass, seriesIndex) {
      var w = this.w;
      var pStyle = this.getMarkerStyle(seriesIndex);
      var pSize = w.globals.markers.size[seriesIndex];
      return {
        pSize: pSize,
        pRadius: w.config.markers.radius,
        pWidth: w.config.markers.strokeWidth,
        pointStrokeColor: pStyle.pointStrokeColor,
        pointFillColor: pStyle.pointFillColor,
        shape: w.config.markers.shape instanceof Array ? w.config.markers.shape[seriesIndex] : w.config.markers.shape,
        class: cssClass,
        pointStrokeOpacity: w.config.markers.strokeOpacity,
        pointFillOpacity: w.config.markers.fillOpacity,
        seriesIndex: seriesIndex
      };
    }
  }, {
    key: "addEvents",
    value: function addEvents(circle) {
      var graphics = new Graphics(this.ctx);
      circle.node.addEventListener('mouseenter', graphics.pathMouseEnter.bind(this.ctx, circle));
      circle.node.addEventListener('mouseleave', graphics.pathMouseLeave.bind(this.ctx, circle));
      circle.node.addEventListener('mousedown', graphics.pathMouseDown.bind(this.ctx, circle));
      circle.node.addEventListener('touchstart', graphics.pathMouseDown.bind(this.ctx, circle), {
        passive: true
      });
    }
  }, {
    key: "getMarkerStyle",
    value: function getMarkerStyle(seriesIndex) {
      var w = this.w;
      var colors = w.globals.markers.colors;
      var strokeColors = w.config.markers.strokeColor || w.config.markers.strokeColors;
      var pointStrokeColor = strokeColors instanceof Array ? strokeColors[seriesIndex] : strokeColors;
      var pointFillColor = colors instanceof Array ? colors[seriesIndex] : colors;
      return {
        pointStrokeColor: pointStrokeColor,
        pointFillColor: pointFillColor
      };
    }
  }]);

  return Markers;
}();

/**
 * ApexCharts Scatter Class.
 * This Class also handles bubbles chart as currently there is no major difference in drawing them,
 * @module Scatter
 **/

var Scatter =
/*#__PURE__*/
function () {
  function Scatter(ctx) {
    _classCallCheck(this, Scatter);

    this.ctx = ctx;
    this.w = ctx.w;
    this.initialAnim = this.w.config.chart.animations.enabled;
    this.dynamicAnim = this.initialAnim && this.w.config.chart.animations.dynamicAnimation.enabled; // this array will help in centering the label in bubbles

    this.radiusSizes = [];
  }

  _createClass(Scatter, [{
    key: "draw",
    value: function draw(elSeries, j, opts) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var realIndex = opts.realIndex;
      var pointsPos = opts.pointsPos;
      var zRatio = opts.zRatio;
      var elPointsMain = opts.elParent;
      var elPointsWrap = graphics.group({
        class: "apexcharts-series-markers apexcharts-series-".concat(w.config.chart.type)
      });
      elPointsWrap.attr('clip-path', "url(#gridRectMarkerMask".concat(w.globals.cuid, ")"));

      if (pointsPos.x instanceof Array) {
        for (var q = 0; q < pointsPos.x.length; q++) {
          var dataPointIndex = j + 1;
          var shouldDraw = true; // a small hack as we have 2 points for the first val to connect it

          if (j === 0 && q === 0) dataPointIndex = 0;
          if (j === 0 && q === 1) dataPointIndex = 1;
          var radius = 0;
          var finishRadius = w.globals.markers.size[realIndex];

          if (zRatio !== Infinity) {
            // means we have a bubble
            finishRadius = w.globals.seriesZ[realIndex][dataPointIndex] / zRatio;

            if (typeof this.radiusSizes[realIndex] === 'undefined') {
              this.radiusSizes.push([]);
            }

            this.radiusSizes[realIndex].push(finishRadius);
          }

          if (!w.config.chart.animations.enabled) {
            radius = finishRadius;
          }

          var x = pointsPos.x[q];
          var y = pointsPos.y[q];
          radius = radius || 0;

          if (x === 0 && y === 0 || y === null || typeof w.globals.series[realIndex][dataPointIndex] === 'undefined') {
            shouldDraw = false;
          }

          if (shouldDraw) {
            var circle = this.drawPoint(x, y, radius, finishRadius, realIndex, dataPointIndex, j);
            elPointsWrap.add(circle);
          }

          elPointsMain.add(elPointsWrap);
        }
      }
    }
  }, {
    key: "drawPoint",
    value: function drawPoint(x, y, radius, finishRadius, realIndex, dataPointIndex, j) {
      var w = this.w;
      var i = realIndex;
      var anim = new Animations(this.ctx);
      var filters = new Filters(this.ctx);
      var fill = new Fill(this.ctx);
      var markers = new Markers(this.ctx);
      var graphics = new Graphics(this.ctx);
      var markerConfig = markers.getMarkerConfig('apexcharts-marker', i);
      var pathFillCircle = fill.fillPath({
        seriesNumber: realIndex,
        patternUnits: 'objectBoundingBox',
        value: w.globals.series[realIndex][j]
      });
      var circle = graphics.drawCircle(radius);

      if (w.config.series[i].data[dataPointIndex]) {
        if (w.config.series[i].data[dataPointIndex].fillColor) {
          pathFillCircle = w.config.series[i].data[dataPointIndex].fillColor;
        }
      }

      circle.attr({
        cx: x,
        cy: y,
        fill: pathFillCircle,
        stroke: markerConfig.pointStrokeColor,
        strokeWidth: markerConfig.pWidth
      });

      if (w.config.chart.dropShadow.enabled) {
        var dropShadow = w.config.chart.dropShadow;
        filters.dropShadow(circle, dropShadow, realIndex);
      }

      if (this.initialAnim && !w.globals.dataChanged) {
        var speed = 1;

        if (!w.globals.resized) {
          speed = w.config.chart.animations.speed;
        }

        anim.animateCircleRadius(circle, 0, finishRadius, speed, w.globals.easing);
      }

      if (w.globals.dataChanged) {
        if (this.dynamicAnim) {
          var _speed = w.config.chart.animations.dynamicAnimation.speed;
          var prevX, prevY, prevR;
          var prevPathJ = null;
          prevPathJ = w.globals.previousPaths[realIndex] && w.globals.previousPaths[realIndex][j];

          if (typeof prevPathJ !== 'undefined' && prevPathJ !== null) {
            // series containing less elements will ignore these values and revert to 0
            prevX = prevPathJ.x;
            prevY = prevPathJ.y;
            prevR = typeof prevPathJ.r !== 'undefined' ? prevPathJ.r : finishRadius;
          }

          for (var cs = 0; cs < w.globals.collapsedSeries.length; cs++) {
            if (w.globals.collapsedSeries[cs].index === realIndex) {
              _speed = 1;
              finishRadius = 0;
            }
          }

          if (x === 0 && y === 0) finishRadius = 0;
          anim.animateCircle(circle, {
            cx: prevX,
            cy: prevY,
            r: prevR
          }, {
            cx: x,
            cy: y,
            r: finishRadius
          }, _speed, w.globals.easing);
        } else {
          circle.attr({
            r: finishRadius
          });
        }
      }

      circle.attr({
        rel: dataPointIndex,
        j: dataPointIndex,
        index: realIndex,
        'default-marker-size': finishRadius
      });
      filters.setSelectionFilter(circle, realIndex, dataPointIndex);
      markers.addEvents(circle);
      circle.node.classList.add('apexcharts-marker');
      return circle;
    }
  }, {
    key: "centerTextInBubble",
    value: function centerTextInBubble(y) {
      var w = this.w;
      y = y + parseInt(w.config.dataLabels.style.fontSize) / 4;
      return {
        y: y
      };
    }
  }]);

  return Scatter;
}();

/**
 * ApexCharts DataLabels Class for drawing dataLabels on Axes based Charts.
 *
 * @module DataLabels
 **/

var DataLabels =
/*#__PURE__*/
function () {
  function DataLabels(ctx) {
    _classCallCheck(this, DataLabels);

    this.ctx = ctx;
    this.w = ctx.w;
  } // When there are many datalabels to be printed, and some of them overlaps each other in the same series, this method will take care of that
  // Also, when datalabels exceeds the drawable area and get clipped off, we need to adjust and move some pixels to make them visible again


  _createClass(DataLabels, [{
    key: "dataLabelsCorrection",
    value: function dataLabelsCorrection(x, y, val, i, dataPointIndex, alwaysDrawDataLabel, fontSize) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var drawnextLabel = false; //

      var textRects = graphics.getTextRects(val, fontSize);
      var width = textRects.width;
      var height = textRects.height; // first value in series, so push an empty array

      if (typeof w.globals.dataLabelsRects[i] === 'undefined') w.globals.dataLabelsRects[i] = []; // then start pushing actual rects in that sub-array

      w.globals.dataLabelsRects[i].push({
        x: x,
        y: y,
        width: width,
        height: height
      });
      var len = w.globals.dataLabelsRects[i].length - 2;
      var lastDrawnIndex = typeof w.globals.lastDrawnDataLabelsIndexes[i] !== 'undefined' ? w.globals.lastDrawnDataLabelsIndexes[i][w.globals.lastDrawnDataLabelsIndexes[i].length - 1] : 0;

      if (typeof w.globals.dataLabelsRects[i][len] !== 'undefined') {
        var lastDataLabelRect = w.globals.dataLabelsRects[i][lastDrawnIndex];

        if ( // next label forward and x not intersecting
        x > lastDataLabelRect.x + lastDataLabelRect.width + 2 || y > lastDataLabelRect.y + lastDataLabelRect.height + 2 || x + width < lastDataLabelRect.x // next label is going to be drawn backwards
        ) {
            // the 2 indexes don't override, so OK to draw next label
            drawnextLabel = true;
          }
      }

      if (dataPointIndex === 0 || alwaysDrawDataLabel) {
        drawnextLabel = true;
      }

      return {
        x: x,
        y: y,
        drawnextLabel: drawnextLabel
      };
    }
  }, {
    key: "drawDataLabel",
    value: function drawDataLabel(pos, i, j) {
      var align = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 'top';
      // this method handles line, area, bubble, scatter charts as those charts contains markers/points which have pre-defined x/y positions
      // all other charts like bars / heatmaps will define their own drawDataLabel routine
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var dataLabelsConfig = w.config.dataLabels;
      var x = 0;
      var y = 0;
      var dataPointIndex = j;
      var elDataLabelsWrap = null;

      if (!dataLabelsConfig.enabled || pos.x instanceof Array !== true) {
        return elDataLabelsWrap;
      }

      elDataLabelsWrap = graphics.group({
        class: 'apexcharts-data-labels'
      });
      elDataLabelsWrap.attr('clip-path', "url(#gridRectMarkerMask".concat(w.globals.cuid, ")"));

      for (var q = 0; q < pos.x.length; q++) {
        x = pos.x[q] + dataLabelsConfig.offsetX;
        y = pos.y[q] + dataLabelsConfig.offsetY - w.globals.markers.size[i] - 5;

        if (align === 'bottom') {
          y = y + w.globals.markers.size[i] * 2 + parseInt(dataLabelsConfig.style.fontSize) * 1.4;
        }

        if (!isNaN(x)) {
          // a small hack as we have 2 points for the first val to connect it
          if (j === 1 && q === 0) dataPointIndex = 0;
          if (j === 1 && q === 1) dataPointIndex = 1;
          var val = w.globals.series[i][dataPointIndex];
          var text = '';

          if (w.config.chart.type === 'bubble') {
            text = w.globals.seriesZ[i][dataPointIndex];
            y = pos.y[q] + w.config.dataLabels.offsetY;
            var scatter = new Scatter(this.ctx);
            var centerTextInBubbleCoords = scatter.centerTextInBubble(y, i, dataPointIndex);
            y = centerTextInBubbleCoords.y;
          } else {
            if (typeof val !== 'undefined' && val !== null) {
              text = w.config.dataLabels.formatter(val, {
                ctx: this.ctx,
                seriesIndex: i,
                dataPointIndex: dataPointIndex,
                w: w
              });
            }
          }

          this.plotDataLabelsText({
            x: x,
            y: y,
            text: text,
            i: i,
            j: dataPointIndex,
            parent: elDataLabelsWrap,
            offsetCorrection: true,
            dataLabelsConfig: w.config.dataLabels
          });
        }
      }

      return elDataLabelsWrap;
    }
  }, {
    key: "plotDataLabelsText",
    value: function plotDataLabelsText(opts) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var x = opts.x,
          y = opts.y,
          i = opts.i,
          j = opts.j,
          text = opts.text,
          textAnchor = opts.textAnchor,
          parent = opts.parent,
          dataLabelsConfig = opts.dataLabelsConfig,
          alwaysDrawDataLabel = opts.alwaysDrawDataLabel,
          offsetCorrection = opts.offsetCorrection;

      if (Array.isArray(w.config.dataLabels.enabledOnSeries)) {
        if (w.config.dataLabels.enabledOnSeries.indexOf(i) > -1) {
          return;
        }
      }

      var correctedLabels = {
        x: x,
        y: y,
        drawnextLabel: true
      };

      if (offsetCorrection) {
        correctedLabels = this.dataLabelsCorrection(x, y, text, i, j, alwaysDrawDataLabel, parseInt(dataLabelsConfig.style.fontSize));
      } // when zoomed, we don't need to correct labels offsets,
      // but if normally, labels get cropped, correct them


      if (!w.globals.zoomed) {
        x = correctedLabels.x;
        y = correctedLabels.y;
      }

      if (correctedLabels.drawnextLabel) {
        var dataLabelText = graphics.drawText({
          width: 100,
          height: parseInt(dataLabelsConfig.style.fontSize),
          x: x,
          y: y,
          foreColor: w.globals.dataLabels.style.colors[i],
          textAnchor: textAnchor || dataLabelsConfig.textAnchor,
          text: text,
          fontSize: dataLabelsConfig.style.fontSize,
          fontFamily: dataLabelsConfig.style.fontFamily
        });
        dataLabelText.attr({
          class: 'apexcharts-datalabel',
          cx: x,
          cy: y
        });

        if (dataLabelsConfig.dropShadow.enabled) {
          var textShadow = dataLabelsConfig.dropShadow;
          var filters = new Filters(this.ctx);
          filters.dropShadow(dataLabelText, textShadow);
        }

        parent.add(dataLabelText);

        if (typeof w.globals.lastDrawnDataLabelsIndexes[i] === 'undefined') {
          w.globals.lastDrawnDataLabelsIndexes[i] = [];
        }

        w.globals.lastDrawnDataLabelsIndexes[i].push(j);
      }
    }
  }]);

  return DataLabels;
}();

/**
 * ApexCharts Bar Class responsible for drawing both Columns and Bars.
 *
 * @module Bar
 **/

var Bar =
/*#__PURE__*/
function () {
  function Bar(ctx, xyRatios) {
    _classCallCheck(this, Bar);

    this.ctx = ctx;
    this.w = ctx.w;
    var w = this.w;
    this.barOptions = w.config.plotOptions.bar;
    this.isHorizontal = this.barOptions.horizontal;
    this.strokeWidth = w.config.stroke.width;
    this.isNullValue = false;
    this.xyRatios = xyRatios;

    if (this.xyRatios !== null) {
      this.xRatio = xyRatios.xRatio;
      this.yRatio = xyRatios.yRatio;
      this.invertedXRatio = xyRatios.invertedXRatio;
      this.invertedYRatio = xyRatios.invertedYRatio;
      this.baseLineY = xyRatios.baseLineY;
      this.baseLineInvertedY = xyRatios.baseLineInvertedY;
    }

    this.yaxisIndex = 0;
    this.seriesLen = 0;
  }
  /** primary draw method which is called on bar object
   * @memberof Bar
   * @param {array} series - user supplied series values
   * @param {int} seriesIndex - the index by which series will be drawn on the svg
   * @return {node} element which is supplied to parent chart draw method for appending
   **/


  _createClass(Bar, [{
    key: "draw",
    value: function draw(series, seriesIndex) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var coreUtils = new CoreUtils(this.ctx, w);
      series = coreUtils.getLogSeries(series);
      this.series = series;
      this.yRatio = coreUtils.getLogYRatios(this.yRatio);
      this.initVariables(series);
      var ret = graphics.group({
        class: 'apexcharts-bar-series apexcharts-plot-series'
      });

      if (w.config.dataLabels.enabled) {
        if (this.totalItems > w.config.plotOptions.bar.dataLabels.maxItems) {
          console.warn('WARNING: DataLabels are enabled but there are too many to display. This may cause performance issue when rendering.');
        }
      }

      for (var i = 0, bc = 0; i < series.length; i++, bc++) {
        var pathTo = void 0,
            pathFrom = void 0;
        var x = void 0,
            y = void 0,
            xDivision = void 0,
            // xDivision is the GRIDWIDTH divided by number of datapoints (columns)
        yDivision = void 0,
            // yDivision is the GRIDHEIGHT divided by number of datapoints (bars)
        zeroH = void 0,
            // zeroH is the baseline where 0 meets y axis
        zeroW = void 0; // zeroW is the baseline where 0 meets x axis

        var yArrj = []; // hold y values of current iterating series

        var xArrj = []; // hold x values of current iterating series

        var realIndex = w.globals.comboCharts ? seriesIndex[i] : i; // el to which series will be drawn

        var elSeries = graphics.group({
          class: "apexcharts-series ".concat(Utils.escapeString(w.globals.seriesNames[realIndex])),
          rel: i + 1,
          'data:realIndex': realIndex
        });
        this.ctx.series.addCollapsedClassToSeries(elSeries, realIndex);

        if (series[i].length > 0) {
          this.visibleI = this.visibleI + 1;
        }

        var strokeWidth = 0;
        var barHeight = 0;
        var barWidth = 0;

        if (this.yRatio.length > 1) {
          this.yaxisIndex = realIndex;
        }

        this.isReversed = w.config.yaxis[this.yaxisIndex] && w.config.yaxis[this.yaxisIndex].reversed;
        var initPositions = this.initialPositions();
        y = initPositions.y;
        barHeight = initPositions.barHeight;
        yDivision = initPositions.yDivision;
        zeroW = initPositions.zeroW;
        x = initPositions.x;
        barWidth = initPositions.barWidth;
        xDivision = initPositions.xDivision;
        zeroH = initPositions.zeroH;

        if (!this.horizontal) {
          xArrj.push(x + barWidth / 2);
        } // eldatalabels


        var elDataLabelsWrap = graphics.group({
          class: 'apexcharts-datalabels'
        });

        for (var j = 0, tj = w.globals.dataPoints; j < w.globals.dataPoints; j++, tj--) {
          if (typeof this.series[i][j] === 'undefined' || series[i][j] === null) {
            this.isNullValue = true;
          } else {
            this.isNullValue = false;
          }

          if (w.config.stroke.show) {
            if (this.isNullValue) {
              strokeWidth = 0;
            } else {
              strokeWidth = Array.isArray(this.strokeWidth) ? this.strokeWidth[realIndex] : this.strokeWidth;
            }
          }

          var paths = null;

          if (this.isHorizontal) {
            paths = this.drawBarPaths({
              indexes: {
                i: i,
                j: j,
                realIndex: realIndex,
                bc: bc
              },
              barHeight: barHeight,
              strokeWidth: strokeWidth,
              pathTo: pathTo,
              pathFrom: pathFrom,
              zeroW: zeroW,
              x: x,
              y: y,
              yDivision: yDivision,
              elSeries: elSeries
            });
            barWidth = this.series[i][j] / this.invertedYRatio;
          } else {
            paths = this.drawColumnPaths({
              indexes: {
                i: i,
                j: j,
                realIndex: realIndex,
                bc: bc
              },
              x: x,
              y: y,
              xDivision: xDivision,
              pathTo: pathTo,
              pathFrom: pathFrom,
              barWidth: barWidth,
              zeroH: zeroH,
              strokeWidth: strokeWidth,
              elSeries: elSeries
            });
            barHeight = this.series[i][j] / this.yRatio[this.yaxisIndex];
          }

          pathTo = paths.pathTo;
          pathFrom = paths.pathFrom;
          y = paths.y;
          x = paths.x; // push current X

          if (j > 0) {
            xArrj.push(x + barWidth / 2);
          }

          yArrj.push(y);
          var pathFill = this.getPathFillColor(series, i, j, realIndex);
          elSeries = this.renderSeries({
            realIndex: realIndex,
            pathFill: pathFill,
            j: j,
            i: i,
            pathFrom: pathFrom,
            pathTo: pathTo,
            strokeWidth: strokeWidth,
            elSeries: elSeries,
            x: x,
            y: y,
            series: series,
            barHeight: barHeight,
            barWidth: barWidth,
            elDataLabelsWrap: elDataLabelsWrap,
            visibleSeries: this.visibleI,
            type: 'bar'
          });
        } // push all x val arrays into main xArr


        w.globals.seriesXvalues[realIndex] = xArrj;
        w.globals.seriesYvalues[realIndex] = yArrj;
        ret.add(elSeries);
      }

      return ret;
    }
  }, {
    key: "getPathFillColor",
    value: function getPathFillColor(series, i, j, realIndex) {
      var w = this.w;
      var fill = new Fill(this.ctx);
      var fillColor = null;
      var seriesNumber = this.barOptions.distributed ? j : i;

      if (this.barOptions.colors.ranges.length > 0) {
        var colorRange = this.barOptions.colors.ranges;
        colorRange.map(function (range) {
          if (series[i][j] >= range.from && series[i][j] <= range.to) {
            fillColor = range.color;
          }
        });
      }

      if (w.config.series[i].data[j] && w.config.series[i].data[j].fillColor) {
        fillColor = w.config.series[i].data[j].fillColor;
      }

      var pathFill = fill.fillPath({
        seriesNumber: this.barOptions.distributed ? seriesNumber : realIndex,
        color: fillColor,
        value: series[i][j]
      });
      return pathFill;
    }
  }, {
    key: "renderSeries",
    value: function renderSeries(_ref) {
      var realIndex = _ref.realIndex,
          pathFill = _ref.pathFill,
          lineFill = _ref.lineFill,
          j = _ref.j,
          i = _ref.i,
          pathFrom = _ref.pathFrom,
          pathTo = _ref.pathTo,
          strokeWidth = _ref.strokeWidth,
          elSeries = _ref.elSeries,
          x = _ref.x,
          y = _ref.y,
          series = _ref.series,
          barHeight = _ref.barHeight,
          barWidth = _ref.barWidth,
          elDataLabelsWrap = _ref.elDataLabelsWrap,
          visibleSeries = _ref.visibleSeries,
          type = _ref.type;
      var w = this.w;
      var graphics = new Graphics(this.ctx);

      if (!lineFill) {
        /* fix apexcharts#341 */
        lineFill = this.barOptions.distributed ? w.globals.stroke.colors[j] : w.globals.stroke.colors[realIndex];
      }

      if (w.config.series[i].data[j] && w.config.series[i].data[j].strokeColor) {
        lineFill = w.config.series[i].data[j].strokeColor;
      }

      if (this.isNullValue) {
        pathFill = 'none';
      }

      var delay = j / w.config.chart.animations.animateGradually.delay * (w.config.chart.animations.speed / w.globals.dataPoints) / 2.4;
      var renderedPath = graphics.renderPaths({
        i: i,
        j: j,
        realIndex: realIndex,
        pathFrom: pathFrom,
        pathTo: pathTo,
        stroke: lineFill,
        strokeWidth: strokeWidth,
        strokeLineCap: w.config.stroke.lineCap,
        fill: pathFill,
        animationDelay: delay,
        initialSpeed: w.config.chart.animations.speed,
        dataChangeSpeed: w.config.chart.animations.dynamicAnimation.speed,
        className: "apexcharts-".concat(type, "-area"),
        id: "apexcharts-".concat(type, "-area")
      });
      renderedPath.attr('clip-path', "url(#gridRectMask".concat(w.globals.cuid, ")"));
      var filters = new Filters(this.ctx);
      filters.setSelectionFilter(renderedPath, realIndex, j);
      elSeries.add(renderedPath);
      var dataLabels = this.calculateDataLabelsPos({
        x: x,
        y: y,
        i: i,
        j: j,
        series: series,
        realIndex: realIndex,
        barHeight: barHeight,
        barWidth: barWidth,
        renderedPath: renderedPath,
        visibleSeries: visibleSeries
      });

      if (dataLabels !== null) {
        elDataLabelsWrap.add(dataLabels);
      }

      elSeries.add(elDataLabelsWrap);
      return elSeries;
    }
  }, {
    key: "initVariables",
    value: function initVariables(series) {
      var w = this.w;
      this.series = series;
      this.totalItems = 0;
      this.seriesLen = 0;
      this.visibleI = -1;
      this.visibleItems = 1; // number of visible bars after user zoomed in/out

      for (var sl = 0; sl < series.length; sl++) {
        if (series[sl].length > 0) {
          this.seriesLen = this.seriesLen + 1;
          this.totalItems += series[sl].length;
        }

        if (w.globals.isXNumeric) {
          // get max visible items
          for (var j = 0; j < series[sl].length; j++) {
            if (w.globals.seriesX[sl][j] > w.globals.minX && w.globals.seriesX[sl][j] < w.globals.maxX) {
              this.visibleItems++;
            }
          }
        } else {
          this.visibleItems = w.globals.dataPoints;
        }
      }

      if (this.seriesLen === 0) {
        // A small adjustment when combo charts are used
        this.seriesLen = 1;
      }
    }
  }, {
    key: "initialPositions",
    value: function initialPositions() {
      var w = this.w;
      var x, y, yDivision, xDivision, barHeight, barWidth, zeroH, zeroW;

      if (this.isHorizontal) {
        // height divided into equal parts
        yDivision = w.globals.gridHeight / w.globals.dataPoints;
        barHeight = yDivision / this.seriesLen;

        if (w.globals.isXNumeric) {
          yDivision = w.globals.gridHeight / this.totalItems;
          barHeight = yDivision / this.seriesLen;
        }

        barHeight = barHeight * parseInt(this.barOptions.barHeight) / 100;
        zeroW = this.baseLineInvertedY + w.globals.padHorizontal + (this.isReversed ? w.globals.gridWidth : 0) - (this.isReversed ? this.baseLineInvertedY * 2 : 0);
        y = (yDivision - barHeight * this.seriesLen) / 2;
      } else {
        // width divided into equal parts
        xDivision = w.globals.gridWidth / this.visibleItems;
        barWidth = xDivision / this.seriesLen * parseInt(this.barOptions.columnWidth) / 100;

        if (w.globals.isXNumeric) {
          // max barwidth should be equal to minXDiff to avoid overlap
          xDivision = w.globals.minXDiff / this.xRatio;
          barWidth = xDivision / this.seriesLen * parseInt(this.barOptions.columnWidth) / 100;
        }

        zeroH = w.globals.gridHeight - this.baseLineY[this.yaxisIndex] - (this.isReversed ? w.globals.gridHeight : 0) + (this.isReversed ? this.baseLineY[this.yaxisIndex] * 2 : 0);
        x = w.globals.padHorizontal + (xDivision - barWidth * this.seriesLen) / 2;
      }

      return {
        x: x,
        y: y,
        yDivision: yDivision,
        xDivision: xDivision,
        barHeight: barHeight,
        barWidth: barWidth,
        zeroH: zeroH,
        zeroW: zeroW
      };
    }
  }, {
    key: "drawBarPaths",
    value: function drawBarPaths(_ref2) {
      var indexes = _ref2.indexes,
          barHeight = _ref2.barHeight,
          strokeWidth = _ref2.strokeWidth,
          pathTo = _ref2.pathTo,
          pathFrom = _ref2.pathFrom,
          zeroW = _ref2.zeroW,
          x = _ref2.x,
          y = _ref2.y,
          yDivision = _ref2.yDivision,
          elSeries = _ref2.elSeries;
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var i = indexes.i;
      var j = indexes.j;
      var realIndex = indexes.realIndex;
      var bc = indexes.bc;

      if (w.globals.isXNumeric) {
        y = (w.globals.seriesX[i][j] - w.globals.minX) / this.invertedXRatio - barHeight;
      }

      var barYPosition = y + barHeight * this.visibleI;
      pathTo = graphics.move(zeroW, barYPosition);
      pathFrom = graphics.move(zeroW, barYPosition);

      if (w.globals.previousPaths.length > 0) {
        pathFrom = this.getPathFrom(realIndex, j);
      }

      if (typeof this.series[i][j] === 'undefined' || this.series[i][j] === null) {
        x = zeroW;
      } else {
        x = zeroW + this.series[i][j] / this.invertedYRatio - (this.isReversed ? this.series[i][j] / this.invertedYRatio : 0) * 2;
      }

      var endingShapeOpts = {
        barHeight: barHeight,
        strokeWidth: strokeWidth,
        barYPosition: barYPosition,
        x: x,
        zeroW: zeroW
      };
      var endingShape = this.barEndingShape(w, endingShapeOpts, this.series, i, j);
      pathTo = pathTo + graphics.line(endingShape.newX, barYPosition) + endingShape.path + graphics.line(zeroW, barYPosition + barHeight - strokeWidth) + graphics.line(zeroW, barYPosition);
      pathFrom = pathFrom + graphics.line(zeroW, barYPosition) + endingShape.ending_p_from + graphics.line(zeroW, barYPosition + barHeight - strokeWidth) + graphics.line(zeroW, barYPosition + barHeight - strokeWidth) + graphics.line(zeroW, barYPosition);

      if (!w.globals.isXNumeric) {
        y = y + yDivision;
      }

      if (this.barOptions.colors.backgroundBarColors.length > 0 && i === 0) {
        if (bc >= this.barOptions.colors.backgroundBarColors.length) {
          bc = 0;
        }

        var bcolor = this.barOptions.colors.backgroundBarColors[bc];
        var rect = graphics.drawRect(0, barYPosition - barHeight * this.visibleI, w.globals.gridWidth, barHeight * this.seriesLen, 0, bcolor, this.barOptions.colors.backgroundBarOpacity);
        elSeries.add(rect);
        rect.node.classList.add('apexcharts-backgroundBar');
      }

      return {
        pathTo: pathTo,
        pathFrom: pathFrom,
        x: x,
        y: y,
        barYPosition: barYPosition
      };
    }
  }, {
    key: "drawColumnPaths",
    value: function drawColumnPaths(_ref3) {
      var indexes = _ref3.indexes,
          x = _ref3.x,
          y = _ref3.y,
          xDivision = _ref3.xDivision,
          pathTo = _ref3.pathTo,
          pathFrom = _ref3.pathFrom,
          barWidth = _ref3.barWidth,
          zeroH = _ref3.zeroH,
          strokeWidth = _ref3.strokeWidth,
          elSeries = _ref3.elSeries;
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var i = indexes.i;
      var j = indexes.j;
      var realIndex = indexes.realIndex;
      var bc = indexes.bc;

      if (w.globals.isXNumeric) {
        x = (w.globals.seriesX[i][j] - w.globals.minX) / this.xRatio - barWidth / 2;
      }

      var barXPosition = x + barWidth * this.visibleI;
      pathTo = graphics.move(barXPosition, zeroH);
      pathFrom = graphics.move(barXPosition, zeroH);

      if (w.globals.previousPaths.length > 0) {
        pathFrom = this.getPathFrom(realIndex, j);
      }

      if (typeof this.series[i][j] === 'undefined' || this.series[i][j] === null) {
        y = zeroH;
      } else {
        y = zeroH - this.series[i][j] / this.yRatio[this.yaxisIndex] + (this.isReversed ? this.series[i][j] / this.yRatio[this.yaxisIndex] : 0) * 2;
      }

      var endingShapeOpts = {
        barWidth: barWidth,
        strokeWidth: strokeWidth,
        barXPosition: barXPosition,
        y: y,
        zeroH: zeroH
      };
      var endingShape = this.barEndingShape(w, endingShapeOpts, this.series, i, j);
      pathTo = pathTo + graphics.line(barXPosition, endingShape.newY) + endingShape.path + graphics.line(barXPosition + barWidth - strokeWidth, zeroH) + graphics.line(barXPosition - strokeWidth / 2, zeroH);
      pathFrom = pathFrom + graphics.line(barXPosition, zeroH) + endingShape.ending_p_from + graphics.line(barXPosition + barWidth - strokeWidth, zeroH) + graphics.line(barXPosition + barWidth - strokeWidth, zeroH) + graphics.line(barXPosition - strokeWidth / 2, zeroH);

      if (!w.globals.isXNumeric) {
        x = x + xDivision;
      }

      if (this.barOptions.colors.backgroundBarColors.length > 0 && i === 0) {
        if (bc >= this.barOptions.colors.backgroundBarColors.length) {
          bc = 0;
        }

        var bcolor = this.barOptions.colors.backgroundBarColors[bc];
        var rect = graphics.drawRect(barXPosition - barWidth * this.visibleI, 0, barWidth * this.seriesLen, w.globals.gridHeight, 0, bcolor, this.barOptions.colors.backgroundBarOpacity);
        elSeries.add(rect);
        rect.node.classList.add('apexcharts-backgroundBar');
      }

      return {
        pathTo: pathTo,
        pathFrom: pathFrom,
        x: x,
        y: y,
        barXPosition: barXPosition
      };
    }
    /** getPathFrom is a common function for bars/columns which is used to get previous paths when data changes.
     * @memberof Bar
     * @param {int} realIndex - current iterating i
     * @param {int} j - current iterating series's j index
     * @return {string} pathFrom is the string which will be appended in animations
     **/

  }, {
    key: "getPathFrom",
    value: function getPathFrom(realIndex, j) {
      var w = this.w;
      var pathFrom;

      for (var pp = 0; pp < w.globals.previousPaths.length; pp++) {
        var gpp = w.globals.previousPaths[pp];

        if (gpp.paths.length > 0 && parseInt(gpp.realIndex) === parseInt(realIndex)) {
          if (typeof w.globals.previousPaths[pp].paths[j] !== 'undefined') {
            pathFrom = w.globals.previousPaths[pp].paths[j].d;
          }
        }
      }

      return pathFrom;
    }
    /** calculateBarDataLabels is used to calculate the positions for the data-labels
     * It also sets the element's data attr for bars and calls drawCalculatedBarDataLabels()
     * @memberof Bar
     * @param {object} {barProps} most of the bar properties used throughout the bar
     * drawing function
     * @return {object} dataLabels node-element which you can append later
     **/

  }, {
    key: "calculateDataLabelsPos",
    value: function calculateDataLabelsPos(_ref4) {
      var x = _ref4.x,
          y = _ref4.y,
          i = _ref4.i,
          j = _ref4.j,
          realIndex = _ref4.realIndex,
          series = _ref4.series,
          barHeight = _ref4.barHeight,
          barWidth = _ref4.barWidth,
          visibleSeries = _ref4.visibleSeries,
          renderedPath = _ref4.renderedPath;
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var strokeWidth = Array.isArray(this.strokeWidth) ? this.strokeWidth[realIndex] : this.strokeWidth;
      var bcx = x + parseFloat(barWidth * visibleSeries);
      var bcy = y + parseFloat(barHeight * visibleSeries);

      if (w.globals.isXNumeric && !w.globals.isBarHorizontal) {
        bcx = x + parseFloat(barWidth * (visibleSeries + 1)) - strokeWidth;
        bcy = y + parseFloat(barHeight * (visibleSeries + 1)) - strokeWidth;
      }

      var dataLabels = null;
      var dataLabelsX = x;
      var dataLabelsY = y;
      var dataLabelsPos = {};
      var dataLabelsConfig = w.config.dataLabels;
      var barDataLabelsConfig = this.barOptions.dataLabels;
      var offX = dataLabelsConfig.offsetX;
      var offY = dataLabelsConfig.offsetY;
      var textRects = {
        width: 0,
        height: 0
      };

      if (w.config.dataLabels.enabled) {
        textRects = graphics.getTextRects(w.globals.yLabelFormatters[0](w.globals.maxY), parseInt(dataLabelsConfig.style.fontSize));
      }

      if (this.isHorizontal) {
        dataLabelsPos = this.calculateBarsDataLabelsPosition({
          x: x,
          y: y,
          i: i,
          j: j,
          renderedPath: renderedPath,
          bcy: bcy,
          barHeight: barHeight,
          barWidth: barWidth,
          textRects: textRects,
          strokeWidth: strokeWidth,
          dataLabelsX: dataLabelsX,
          dataLabelsY: dataLabelsY,
          barDataLabelsConfig: barDataLabelsConfig,
          offX: offX,
          offY: offY
        });
      } else {
        dataLabelsPos = this.calculateColumnsDataLabelsPosition({
          x: x,
          y: y,
          i: i,
          j: j,
          renderedPath: renderedPath,
          realIndex: realIndex,
          bcx: bcx,
          bcy: bcy,
          barHeight: barHeight,
          barWidth: barWidth,
          textRects: textRects,
          strokeWidth: strokeWidth,
          dataLabelsY: dataLabelsY,
          barDataLabelsConfig: barDataLabelsConfig,
          offX: offX,
          offY: offY
        });
      }

      renderedPath.attr({
        cy: dataLabelsPos.bcy,
        cx: dataLabelsPos.bcx,
        j: j,
        val: series[i][j],
        barHeight: barHeight,
        barWidth: barWidth
      });
      dataLabels = this.drawCalculatedDataLabels({
        x: dataLabelsPos.dataLabelsX,
        y: dataLabelsPos.dataLabelsY,
        val: series[i][j],
        i: realIndex,
        j: j,
        barWidth: barWidth,
        barHeight: barHeight,
        textRects: textRects,
        dataLabelsConfig: dataLabelsConfig
      });
      return dataLabels;
    }
  }, {
    key: "calculateColumnsDataLabelsPosition",
    value: function calculateColumnsDataLabelsPosition(opts) {
      var w = this.w;
      var i = opts.i,
          j = opts.j,
          y = opts.y,
          bcx = opts.bcx,
          barWidth = opts.barWidth,
          barHeight = opts.barHeight,
          textRects = opts.textRects,
          dataLabelsY = opts.dataLabelsY,
          barDataLabelsConfig = opts.barDataLabelsConfig,
          strokeWidth = opts.strokeWidth,
          offX = opts.offX,
          offY = opts.offY;
      var dataLabelsX;
      var dataPointsDividedWidth = w.globals.gridWidth / w.globals.dataPoints;
      bcx = bcx - strokeWidth / 2;

      if (w.globals.isXNumeric) {
        dataLabelsX = bcx - barWidth / 2 + offX;
      } else {
        dataLabelsX = bcx - dataPointsDividedWidth + barWidth / 2 + offX;
      }

      var valIsNegative = this.series[i][j] <= 0;

      if (this.isReversed) {
        y = y - barHeight;
      }

      switch (barDataLabelsConfig.position) {
        case 'center':
          if (valIsNegative) {
            dataLabelsY = y + barHeight / 2 + textRects.height / 2 + offY;
          } else {
            dataLabelsY = y + barHeight / 2 + textRects.height / 2 - offY;
          }

          break;

        case 'bottom':
          if (valIsNegative) {
            dataLabelsY = y + barHeight + textRects.height + strokeWidth + offY;
          } else {
            dataLabelsY = y + barHeight - textRects.height / 2 + strokeWidth - offY;
          }

          break;

        case 'top':
          if (valIsNegative) {
            dataLabelsY = y - textRects.height / 2 - offY;
          } else {
            dataLabelsY = y + textRects.height + offY;
          }

          break;
      }

      if (!w.config.chart.stacked) {
        if (dataLabelsY < 0) {
          dataLabelsY = 0 + strokeWidth;
        } else if (dataLabelsY + textRects.height / 3 > w.globals.gridHeight) {
          dataLabelsY = w.globals.gridHeight - strokeWidth;
        }
      }

      return {
        bcx: bcx,
        bcy: y,
        dataLabelsX: dataLabelsX,
        dataLabelsY: dataLabelsY
      };
    }
  }, {
    key: "calculateBarsDataLabelsPosition",
    value: function calculateBarsDataLabelsPosition(opts) {
      var w = this.w;
      var x = opts.x,
          i = opts.i,
          j = opts.j,
          bcy = opts.bcy,
          barHeight = opts.barHeight,
          barWidth = opts.barWidth,
          textRects = opts.textRects,
          dataLabelsX = opts.dataLabelsX,
          strokeWidth = opts.strokeWidth,
          barDataLabelsConfig = opts.barDataLabelsConfig,
          offX = opts.offX,
          offY = opts.offY;
      var dataPointsDividedHeight = w.globals.gridHeight / w.globals.dataPoints;
      var dataLabelsY = bcy - dataPointsDividedHeight + barHeight / 2 + textRects.height / 2 + offY - 3;
      var valIsNegative = this.series[i][j] <= 0;

      if (this.isReversed) {
        x = x + barWidth;
      }

      switch (barDataLabelsConfig.position) {
        case 'center':
          if (valIsNegative) {
            dataLabelsX = x - barWidth / 2 - offX;
          } else {
            dataLabelsX = x - barWidth / 2 + offX;
          }

          break;

        case 'bottom':
          if (valIsNegative) {
            dataLabelsX = x - barWidth - strokeWidth - Math.round(textRects.width / 2) - offX;
          } else {
            dataLabelsX = x - barWidth + strokeWidth + Math.round(textRects.width / 2) + offX;
          }

          break;

        case 'top':
          if (valIsNegative) {
            dataLabelsX = x - strokeWidth + Math.round(textRects.width / 2) - offX;
          } else {
            dataLabelsX = x - strokeWidth - Math.round(textRects.width / 2) + offX;
          }

          break;
      }

      if (!w.config.chart.stacked) {
        if (dataLabelsX < 0) {
          dataLabelsX = dataLabelsX + textRects.width + strokeWidth;
        } else if (dataLabelsX + textRects.width / 2 > w.globals.gridWidth) {
          dataLabelsX = w.globals.gridWidth - textRects.width - strokeWidth;
        }
      }

      return {
        bcx: x,
        bcy: bcy,
        dataLabelsX: dataLabelsX,
        dataLabelsY: dataLabelsY
      };
    }
  }, {
    key: "drawCalculatedDataLabels",
    value: function drawCalculatedDataLabels(_ref5) {
      var x = _ref5.x,
          y = _ref5.y,
          val = _ref5.val,
          i = _ref5.i,
          j = _ref5.j,
          textRects = _ref5.textRects,
          barHeight = _ref5.barHeight,
          barWidth = _ref5.barWidth,
          dataLabelsConfig = _ref5.dataLabelsConfig;
      var w = this.w;
      var dataLabels = new DataLabels(this.ctx);
      var graphics = new Graphics(this.ctx);
      var formatter = dataLabelsConfig.formatter;
      var elDataLabelsWrap = null;
      var isSeriesNotCollapsed = w.globals.collapsedSeriesIndices.indexOf(i) > -1;

      if (dataLabelsConfig.enabled && !isSeriesNotCollapsed) {
        elDataLabelsWrap = graphics.group({
          class: 'apexcharts-data-labels'
        });
        var text = '';

        if (typeof val !== 'undefined' && val !== null) {
          text = formatter(val, {
            seriesIndex: i,
            dataPointIndex: j,
            w: w
          });
        }

        if (val === 0 && w.config.chart.stacked) {
          // in a stacked bar/column chart, 0 value should be neglected as it will overlap on the next element
          text = '';
        }

        if (w.config.chart.stacked && this.barOptions.dataLabels.hideOverflowingLabels) {
          // if there is not enough space to draw the label in the bar/column rect, check hideOverflowingLabels property to prevent overflowing on wrong rect
          // Note: This issue is only seen in stacked charts
          if (this.isHorizontal) {
            barWidth = this.series[i][j] / this.yRatio[this.yaxisIndex];

            if (textRects.width / 1.6 > barWidth) {
              text = '';
            }
          } else {
            barHeight = this.series[i][j] / this.yRatio[this.yaxisIndex];

            if (textRects.height / 1.6 > barHeight) {
              text = '';
            }
          }
        }

        dataLabels.plotDataLabelsText({
          x: x,
          y: y,
          text: text,
          i: i,
          j: j,
          parent: elDataLabelsWrap,
          dataLabelsConfig: dataLabelsConfig,
          alwaysDrawDataLabel: true,
          offsetCorrection: true
        });
      }

      return elDataLabelsWrap;
    }
    /** barEndingShape draws the various shapes on top of bars/columns
     * @memberof Bar
     * @param {object} w - chart context
     * @param {object} opts - consists several properties like barHeight/barWidth
     * @param {array} series - global primary series
     * @param {int} i - current iterating series's index
     * @param {int} j - series's j of i
     * @return {object} path - ending shape whether round/arrow
     *         ending_p_from - similar to pathFrom
     *         newY - which is calculated from existing y and new shape's top
     **/

  }, {
    key: "barEndingShape",
    value: function barEndingShape(w, opts, series, i, j) {
      var graphics = new Graphics(this.ctx);

      if (this.isHorizontal) {
        var endingShape = null;
        var endingShapeFrom = '';
        var x = opts.x;

        if (typeof series[i][j] !== 'undefined' || series[i][j] !== null) {
          var inverse = series[i][j] < 0;
          var eX = opts.barHeight / 2 - opts.strokeWidth;
          if (inverse) eX = -opts.barHeight / 2 - opts.strokeWidth;

          if (!w.config.chart.stacked) {
            if (this.barOptions.endingShape === 'rounded') {
              x = opts.x - eX / 2;
            }
          }

          switch (this.barOptions.endingShape) {
            case 'flat':
              endingShape = graphics.line(x, opts.barYPosition + opts.barHeight - opts.strokeWidth);
              break;

            case 'rounded':
              endingShape = graphics.quadraticCurve(x + eX, opts.barYPosition + (opts.barHeight - opts.strokeWidth) / 2, x, opts.barYPosition + opts.barHeight - opts.strokeWidth);
              break;
          }
        }

        return {
          path: endingShape,
          ending_p_from: endingShapeFrom,
          newX: x
        };
      } else {
        var _endingShape = null;
        var _endingShapeFrom = '';
        var y = opts.y;

        if (typeof series[i][j] !== 'undefined' || series[i][j] !== null) {
          var _inverse = series[i][j] < 0;

          var eY = opts.barWidth / 2 - opts.strokeWidth;
          if (_inverse) eY = -opts.barWidth / 2 - opts.strokeWidth;

          if (!w.config.chart.stacked) {
            // the shape exceeds the chart height, hence reduce y
            if (this.barOptions.endingShape === 'rounded') {
              y = y + eY / 2;
            }
          }

          switch (this.barOptions.endingShape) {
            case 'flat':
              _endingShape = graphics.line(opts.barXPosition + opts.barWidth - opts.strokeWidth, y);
              break;

            case 'rounded':
              _endingShape = graphics.quadraticCurve(opts.barXPosition + (opts.barWidth - opts.strokeWidth) / 2, y - eY, opts.barXPosition + opts.barWidth - opts.strokeWidth, y);
              break;
          }
        }

        return {
          path: _endingShape,
          ending_p_from: _endingShapeFrom,
          newY: y
        };
      }
    }
  }]);

  return Bar;
}();

/**
 * ApexCharts BarStacked Class responsible for drawing both Stacked Columns and Bars.
 *
 * @module BarStacked
 * The whole calculation for stacked bar/column is different from normal bar/column,
 * hence it makes sense to derive a new class for it extending most of the props of Parent Bar
 **/

var BarStacked =
/*#__PURE__*/
function (_Bar) {
  _inherits(BarStacked, _Bar);

  function BarStacked() {
    _classCallCheck(this, BarStacked);

    return _possibleConstructorReturn(this, _getPrototypeOf(BarStacked).apply(this, arguments));
  }

  _createClass(BarStacked, [{
    key: "draw",
    value: function draw(series, seriesIndex) {
      var w = this.w;
      this.graphics = new Graphics(this.ctx);
      this.fill = new Fill(this.ctx);
      this.bar = new Bar(this.ctx, this.xyRatios);
      var coreUtils = new CoreUtils(this.ctx, w);
      series = coreUtils.getLogSeries(series);
      this.yRatio = coreUtils.getLogYRatios(this.yRatio);
      this.initVariables(series);

      if (w.config.chart.stackType === '100%') {
        series = w.globals.seriesPercent.slice();
      }

      this.series = series;
      this.totalItems = 0;
      this.prevY = []; // y position on chart

      this.prevX = []; // x position on chart

      this.prevYF = []; // y position including shapes on chart

      this.prevXF = []; // x position including shapes on chart

      this.prevYVal = []; // y values (series[i][j]) in columns

      this.prevXVal = []; // x values (series[i][j]) in bars

      this.xArrj = []; // xj indicates x position on graph in bars

      this.xArrjF = []; // xjF indicates bar's x position + endingshape's positions in bars

      this.xArrjVal = []; // x val means the actual series's y values in horizontal/bars

      this.yArrj = []; // yj indicates y position on graph in columns

      this.yArrjF = []; // yjF indicates bar's y position + endingshape's positions in columns

      this.yArrjVal = []; // y val means the actual series's y values in columns

      for (var sl = 0; sl < series.length; sl++) {
        if (series[sl].length > 0) {
          this.totalItems += series[sl].length;
        }
      }

      var ret = this.graphics.group({
        class: 'apexcharts-bar-series apexcharts-plot-series'
      });
      var x = 0;
      var y = 0;

      for (var i = 0, bc = 0; i < series.length; i++, bc++) {
        var pathTo = void 0,
            pathFrom = void 0;
        var xDivision = void 0; // xDivision is the GRIDWIDTH divided by number of datapoints (columns)

        var yDivision = void 0; // yDivision is the GRIDHEIGHT divided by number of datapoints (bars)

        var zeroH = void 0; // zeroH is the baseline where 0 meets y axis

        var zeroW = void 0; // zeroW is the baseline where 0 meets x axis

        var xArrValues = [];
        var yArrValues = [];
        var realIndex = w.globals.comboCharts ? seriesIndex[i] : i;

        if (this.yRatio.length > 1) {
          this.yaxisIndex = realIndex;
        }

        this.isReversed = w.config.yaxis[this.yaxisIndex] && w.config.yaxis[this.yaxisIndex].reversed; // el to which series will be drawn

        var elSeries = this.graphics.group({
          class: "apexcharts-series ".concat(Utils.escapeString(w.globals.seriesNames[realIndex])),
          rel: i + 1,
          'data:realIndex': realIndex
        }); // eldatalabels

        var elDataLabelsWrap = this.graphics.group({
          class: 'apexcharts-datalabels'
        });
        var strokeWidth = 0;
        var barHeight = 0;
        var barWidth = 0;
        var initPositions = this.initialPositions(x, y, xDivision, yDivision, zeroH, zeroW);
        y = initPositions.y;
        barHeight = initPositions.barHeight;
        yDivision = initPositions.yDivision;
        zeroW = initPositions.zeroW;
        x = initPositions.x;
        barWidth = initPositions.barWidth;
        xDivision = initPositions.xDivision;
        zeroH = initPositions.zeroH;
        this.yArrj = [];
        this.yArrjF = [];
        this.yArrjVal = [];
        this.xArrj = [];
        this.xArrjF = [];
        this.xArrjVal = []; // if (!this.horizontal) {
        // this.xArrj.push(x + barWidth / 2)
        // }

        for (var j = 0; j < w.globals.dataPoints; j++) {
          if (w.config.stroke.show) {
            if (this.isNullValue) {
              strokeWidth = 0;
            } else {
              strokeWidth = Array.isArray(this.strokeWidth) ? this.strokeWidth[realIndex] : this.strokeWidth;
            }
          }

          var paths = null;

          if (this.isHorizontal) {
            paths = this.drawBarPaths({
              indexes: {
                i: i,
                j: j,
                realIndex: realIndex,
                bc: bc
              },
              barHeight: barHeight,
              strokeWidth: strokeWidth,
              pathTo: pathTo,
              pathFrom: pathFrom,
              zeroW: zeroW,
              x: x,
              y: y,
              yDivision: yDivision,
              elSeries: elSeries
            });
            barWidth = this.series[i][j] / this.invertedYRatio;
          } else {
            paths = this.drawColumnPaths({
              indexes: {
                i: i,
                j: j,
                realIndex: realIndex,
                bc: bc
              },
              x: x,
              y: y,
              xDivision: xDivision,
              pathTo: pathTo,
              pathFrom: pathFrom,
              barWidth: barWidth,
              zeroH: zeroH,
              strokeWidth: strokeWidth,
              elSeries: elSeries
            });
            barHeight = this.series[i][j] / this.yRatio[this.yaxisIndex];
          }

          pathTo = paths.pathTo;
          pathFrom = paths.pathFrom;
          y = paths.y;
          x = paths.x;
          xArrValues.push(x);
          yArrValues.push(y);
          var pathFill = this.bar.getPathFillColor(series, i, j, realIndex);
          elSeries = this.renderSeries({
            realIndex: realIndex,
            pathFill: pathFill,
            j: j,
            i: i,
            pathFrom: pathFrom,
            pathTo: pathTo,
            strokeWidth: strokeWidth,
            elSeries: elSeries,
            x: x,
            y: y,
            series: series,
            barHeight: barHeight,
            barWidth: barWidth,
            elDataLabelsWrap: elDataLabelsWrap,
            type: 'bar',
            visibleSeries: 0
          });
        } // push all x val arrays into main xArr


        w.globals.seriesXvalues[realIndex] = xArrValues;
        w.globals.seriesYvalues[realIndex] = yArrValues; // push all current y values array to main PrevY Array

        this.prevY.push(this.yArrj);
        this.prevYF.push(this.yArrjF);
        this.prevYVal.push(this.yArrjVal);
        this.prevX.push(this.xArrj);
        this.prevXF.push(this.xArrjF);
        this.prevXVal.push(this.xArrjVal);
        ret.add(elSeries);
      }

      return ret;
    }
  }, {
    key: "initialPositions",
    value: function initialPositions(x, y, xDivision, yDivision, zeroH, zeroW) {
      var w = this.w;
      var barHeight, barWidth;

      if (this.isHorizontal) {
        // height divided into equal parts
        yDivision = w.globals.gridHeight / w.globals.dataPoints;
        barHeight = yDivision;
        barHeight = barHeight * parseInt(w.config.plotOptions.bar.barHeight) / 100;
        zeroW = this.baseLineInvertedY + w.globals.padHorizontal + (this.isReversed ? w.globals.gridWidth : 0) - (this.isReversed ? this.baseLineInvertedY * 2 : 0); // initial y position is half of barHeight * half of number of Bars

        y = (yDivision - barHeight) / 2;
      } else {
        // width divided into equal parts
        xDivision = w.globals.gridWidth / w.globals.dataPoints;
        barWidth = xDivision;

        if (w.globals.isXNumeric) {
          xDivision = w.globals.minXDiff / this.xRatio;
          barWidth = xDivision * parseInt(this.barOptions.columnWidth) / 100;
        } else {
          barWidth = barWidth * parseInt(w.config.plotOptions.bar.columnWidth) / 100;
        }

        zeroH = this.baseLineY[this.yaxisIndex] + (this.isReversed ? w.globals.gridHeight : 0) - (this.isReversed ? this.baseLineY[this.yaxisIndex] * 2 : 0); // initial x position is one third of barWidth

        x = w.globals.padHorizontal + (xDivision - barWidth) / 2;
      }

      return {
        x: x,
        y: y,
        yDivision: yDivision,
        xDivision: xDivision,
        barHeight: barHeight,
        barWidth: barWidth,
        zeroH: zeroH,
        zeroW: zeroW
      };
    }
  }, {
    key: "drawBarPaths",
    value: function drawBarPaths(_ref) {
      var indexes = _ref.indexes,
          barHeight = _ref.barHeight,
          strokeWidth = _ref.strokeWidth,
          pathTo = _ref.pathTo,
          pathFrom = _ref.pathFrom,
          zeroW = _ref.zeroW,
          x = _ref.x,
          y = _ref.y,
          yDivision = _ref.yDivision,
          elSeries = _ref.elSeries;
      var w = this.w;
      var barYPosition = y;
      var barXPosition;
      var i = indexes.i;
      var j = indexes.j;
      var realIndex = indexes.realIndex;
      var bc = indexes.bc;
      var prevBarW = 0;

      for (var k = 0; k < this.prevXF.length; k++) {
        prevBarW = prevBarW + this.prevXF[k][j];
      }

      if (i > 0) {
        var bXP = zeroW;

        if (this.prevXVal[i - 1][j] < 0) {
          if (this.series[i][j] >= 0) {
            bXP = this.prevX[i - 1][j] + prevBarW - (this.isReversed ? prevBarW : 0) * 2;
          } else {
            bXP = this.prevX[i - 1][j];
          }
        } else if (this.prevXVal[i - 1][j] >= 0) {
          if (this.series[i][j] >= 0) {
            bXP = this.prevX[i - 1][j];
          } else {
            bXP = this.prevX[i - 1][j] - prevBarW + (this.isReversed ? prevBarW : 0) * 2;
          }
        }

        barXPosition = bXP;
      } else {
        // the first series will not have prevX values
        barXPosition = zeroW;
      }

      if (this.series[i][j] === null) {
        x = barXPosition;
      } else {
        x = barXPosition + this.series[i][j] / this.invertedYRatio - (this.isReversed ? this.series[i][j] / this.invertedYRatio : 0) * 2;
      }

      var endingShapeOpts = {
        barHeight: barHeight,
        strokeWidth: strokeWidth,
        invertedYRatio: this.invertedYRatio,
        barYPosition: barYPosition,
        x: x
      };
      var endingShape = this.bar.barEndingShape(w, endingShapeOpts, this.series, i, j);

      if (this.series.length > 1 && i !== this.endingShapeOnSeriesNumber) {
        // revert back to flat shape if not last series
        endingShape.path = this.graphics.line(endingShape.newX, barYPosition + barHeight - strokeWidth);
      }

      this.xArrj.push(endingShape.newX);
      this.xArrjF.push(Math.abs(barXPosition - endingShape.newX));
      this.xArrjVal.push(this.series[i][j]);
      pathTo = this.graphics.move(barXPosition, barYPosition);
      pathFrom = this.graphics.move(barXPosition, barYPosition);

      if (w.globals.previousPaths.length > 0) {
        pathFrom = this.bar.getPathFrom(realIndex, j, false);
      }

      pathTo = pathTo + this.graphics.line(endingShape.newX, barYPosition) + endingShape.path + this.graphics.line(barXPosition, barYPosition + barHeight - strokeWidth) + this.graphics.line(barXPosition, barYPosition);
      pathFrom = pathFrom + this.graphics.line(barXPosition, barYPosition) + this.graphics.line(barXPosition, barYPosition + barHeight - strokeWidth) + this.graphics.line(barXPosition, barYPosition + barHeight - strokeWidth) + this.graphics.line(barXPosition, barYPosition + barHeight - strokeWidth) + this.graphics.line(barXPosition, barYPosition);

      if (w.config.plotOptions.bar.colors.backgroundBarColors.length > 0 && i === 0) {
        if (bc >= w.config.plotOptions.bar.colors.backgroundBarColors.length) {
          bc = 0;
        }

        var bcolor = w.config.plotOptions.bar.colors.backgroundBarColors[bc];
        var rect = this.graphics.drawRect(0, barYPosition, w.globals.gridWidth, barHeight, 0, bcolor, w.config.plotOptions.bar.colors.backgroundBarOpacity);
        elSeries.add(rect);
        rect.node.classList.add('apexcharts-backgroundBar');
      }

      y = y + yDivision;
      return {
        pathTo: pathTo,
        pathFrom: pathFrom,
        x: x,
        y: y
      };
    }
  }, {
    key: "drawColumnPaths",
    value: function drawColumnPaths(_ref2) {
      var indexes = _ref2.indexes,
          x = _ref2.x,
          y = _ref2.y,
          xDivision = _ref2.xDivision,
          pathTo = _ref2.pathTo,
          pathFrom = _ref2.pathFrom,
          barWidth = _ref2.barWidth,
          zeroH = _ref2.zeroH,
          strokeWidth = _ref2.strokeWidth,
          elSeries = _ref2.elSeries;
      var w = this.w;
      var i = indexes.i;
      var j = indexes.j;
      var realIndex = indexes.realIndex;
      var bc = indexes.bc;

      if (w.globals.isXNumeric) {
        var seriesVal = w.globals.seriesX[i][j];
        if (!seriesVal) seriesVal = 0;
        x = (seriesVal - w.globals.minX) / this.xRatio - barWidth / 2;
      }

      var barXPosition = x;
      var barYPosition;
      var prevBarH = 0;

      for (var k = 0; k < this.prevYF.length; k++) {
        prevBarH = prevBarH + this.prevYF[k][j];
      }

      if (i > 0 && !w.globals.isXNumeric || i > 0 && w.globals.isXNumeric && w.globals.seriesX[i - 1][j] === w.globals.seriesX[i][j]) {
        var bYP;
        var prevYValue = this.prevY[i - 1][j];

        if (this.prevYVal[i - 1][j] < 0) {
          if (this.series[i][j] >= 0) {
            bYP = prevYValue - prevBarH + (this.isReversed ? prevBarH : 0) * 2;
          } else {
            bYP = prevYValue;
          }
        } else {
          if (this.series[i][j] >= 0) {
            bYP = prevYValue;
          } else {
            bYP = prevYValue + prevBarH - (this.isReversed ? prevBarH : 0) * 2;
          }
        }

        barYPosition = bYP;
      } else {
        // the first series will not have prevY values, also if the prev index's series X doesn't matches the current index's series X, then start from zero
        barYPosition = w.globals.gridHeight - zeroH;
      }

      y = barYPosition - this.series[i][j] / this.yRatio[this.yaxisIndex] + (this.isReversed ? this.series[i][j] / this.yRatio[this.yaxisIndex] : 0) * 2;
      var endingShapeOpts = {
        barWidth: barWidth,
        strokeWidth: strokeWidth,
        yRatio: this.yRatio[this.yaxisIndex],
        barXPosition: barXPosition,
        y: y
      };
      var endingShape = this.bar.barEndingShape(w, endingShapeOpts, this.series, i, j);
      this.yArrj.push(endingShape.newY);
      this.yArrjF.push(Math.abs(barYPosition - endingShape.newY));
      this.yArrjVal.push(this.series[i][j]);
      pathTo = this.graphics.move(barXPosition, barYPosition);
      pathFrom = this.graphics.move(barXPosition, barYPosition);

      if (w.globals.previousPaths.length > 0) {
        pathFrom = this.bar.getPathFrom(realIndex, j, false);
      }

      pathTo = pathTo + this.graphics.line(barXPosition, endingShape.newY) + endingShape.path + this.graphics.line(barXPosition + barWidth - strokeWidth, barYPosition) + this.graphics.line(barXPosition - strokeWidth / 2, barYPosition);
      pathFrom = pathFrom + this.graphics.line(barXPosition, barYPosition) + this.graphics.line(barXPosition + barWidth - strokeWidth, barYPosition) + this.graphics.line(barXPosition + barWidth - strokeWidth, barYPosition) + this.graphics.line(barXPosition + barWidth - strokeWidth, barYPosition) + this.graphics.line(barXPosition - strokeWidth / 2, barYPosition);

      if (w.config.plotOptions.bar.colors.backgroundBarColors.length > 0 && i === 0) {
        if (bc >= w.config.plotOptions.bar.colors.backgroundBarColors.length) {
          bc = 0;
        }

        var bcolor = w.config.plotOptions.bar.colors.backgroundBarColors[bc];
        var rect = this.graphics.drawRect(barXPosition, 0, barWidth, w.globals.gridHeight, 0, bcolor, w.config.plotOptions.bar.colors.backgroundBarOpacity);
        elSeries.add(rect);
        rect.node.classList.add('apexcharts-backgroundBar');
      }

      x = x + xDivision;
      return {
        pathTo: pathTo,
        pathFrom: pathFrom,
        x: w.globals.isXNumeric ? x - xDivision : x,
        y: y
      };
    }
    /*
     * When user clicks on legends, the collapsed series will be filled with [0,0,0,...,0]
     * We need to make sure, that the last series is not [0,0,0,...,0]
     * as we need to draw shapes on the last series (for stacked bars/columns only)
     * Hence, we are collecting all inner arrays in series which has [0,0,0...,0]
     **/

  }, {
    key: "checkZeroSeries",
    value: function checkZeroSeries(_ref3) {
      var series = _ref3.series;
      var w = this.w;

      for (var zs = 0; zs < series.length; zs++) {
        var total = 0;

        for (var zsj = 0; zsj < series[w.globals.maxValsInArrayIndex].length; zsj++) {
          total += series[zs][zsj];
        }

        if (total === 0) {
          this.zeroSerieses.push(zs);
        }
      } // After getting all zeroserieses, we need to ensure whether endingshapeonSeries is not in that zeroseries array


      for (var s = series.length - 1; s >= 0; s--) {
        if (this.zeroSerieses.indexOf(s) > -1 && s === this.endingShapeOnSeriesNumber) {
          this.endingShapeOnSeriesNumber -= 1;
        }
      }
    }
  }]);

  return BarStacked;
}(Bar);

/**
 * ApexCharts CandleStick Class responsible for drawing both Stacked Columns and Bars.
 *
 * @module CandleStick
 **/

var CandleStick =
/*#__PURE__*/
function (_Bar) {
  _inherits(CandleStick, _Bar);

  function CandleStick() {
    _classCallCheck(this, CandleStick);

    return _possibleConstructorReturn(this, _getPrototypeOf(CandleStick).apply(this, arguments));
  }

  _createClass(CandleStick, [{
    key: "draw",
    value: function draw(series, seriesIndex) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var fill = new Fill(this.ctx);
      this.candlestickOptions = this.w.config.plotOptions.candlestick;
      var coreUtils = new CoreUtils(this.ctx, w);
      series = coreUtils.getLogSeries(series);
      this.series = series;
      this.yRatio = coreUtils.getLogYRatios(this.yRatio);
      this.initVariables(series);
      var ret = graphics.group({
        class: 'apexcharts-candlestick-series apexcharts-plot-series'
      });

      for (var i = 0, bc = 0; i < series.length; i++, bc++) {
        var pathTo = void 0,
            pathFrom = void 0;
        var x = void 0,
            y = void 0,
            xDivision = void 0,
            // xDivision is the GRIDWIDTH divided by number of datapoints (columns)
        zeroH = void 0; // zeroH is the baseline where 0 meets y axis

        var yArrj = []; // hold y values of current iterating series

        var xArrj = []; // hold x values of current iterating series

        var realIndex = w.globals.comboCharts ? seriesIndex[i] : i; // el to which series will be drawn

        var elSeries = graphics.group({
          class: "apexcharts-series ".concat(Utils.escapeString(w.globals.seriesNames[realIndex])),
          rel: i + 1,
          'data:realIndex': realIndex
        });

        if (series[i].length > 0) {
          this.visibleI = this.visibleI + 1;
        }

        var strokeWidth = 0;
        var barHeight = 0;
        var barWidth = 0;

        if (this.yRatio.length > 1) {
          this.yaxisIndex = realIndex;
        }

        var initPositions = this.initialPositions();
        y = initPositions.y;
        barHeight = initPositions.barHeight;
        x = initPositions.x;
        barWidth = initPositions.barWidth;
        xDivision = initPositions.xDivision;
        zeroH = initPositions.zeroH;
        xArrj.push(x + barWidth / 2); // eldatalabels

        var elDataLabelsWrap = graphics.group({
          class: 'apexcharts-datalabels'
        });

        for (var j = 0, tj = w.globals.dataPoints; j < w.globals.dataPoints; j++, tj--) {
          if (typeof this.series[i][j] === 'undefined' || series[i][j] === null) {
            this.isNullValue = true;
          } else {
            this.isNullValue = false;
          }

          if (w.config.stroke.show) {
            if (this.isNullValue) {
              strokeWidth = 0;
            } else {
              strokeWidth = Array.isArray(this.strokeWidth) ? this.strokeWidth[realIndex] : this.strokeWidth;
            }
          }

          var color = void 0;
          var paths = this.drawCandleStickPaths({
            indexes: {
              i: i,
              j: j,
              realIndex: realIndex,
              bc: bc
            },
            x: x,
            y: y,
            xDivision: xDivision,
            pathTo: pathTo,
            pathFrom: pathFrom,
            barWidth: barWidth,
            zeroH: zeroH,
            strokeWidth: strokeWidth,
            elSeries: elSeries
          });
          pathTo = paths.pathTo;
          pathFrom = paths.pathFrom;
          y = paths.y;
          x = paths.x;
          color = paths.color; // push current X

          if (j > 0) {
            xArrj.push(x + barWidth / 2);
          }

          yArrj.push(y);
          var pathFill = fill.fillPath({
            seriesNumber: realIndex,
            color: color,
            value: series[i][j]
          });
          var lineFill = this.candlestickOptions.wick.useFillColor ? color : undefined;
          elSeries = this.renderSeries({
            realIndex: realIndex,
            pathFill: pathFill,
            lineFill: lineFill,
            j: j,
            i: i,
            pathFrom: pathFrom,
            pathTo: pathTo,
            strokeWidth: strokeWidth,
            elSeries: elSeries,
            x: x,
            y: y,
            series: series,
            barHeight: barHeight,
            barWidth: barWidth,
            elDataLabelsWrap: elDataLabelsWrap,
            visibleSeries: this.visibleI,
            type: 'candlestick'
          });
        } // push all x val arrays into main xArr


        w.globals.seriesXvalues[realIndex] = xArrj;
        w.globals.seriesYvalues[realIndex] = yArrj;
        ret.add(elSeries);
      }

      return ret;
    }
  }, {
    key: "drawCandleStickPaths",
    value: function drawCandleStickPaths(_ref) {
      var indexes = _ref.indexes,
          x = _ref.x,
          y = _ref.y,
          xDivision = _ref.xDivision,
          pathTo = _ref.pathTo,
          pathFrom = _ref.pathFrom,
          barWidth = _ref.barWidth,
          zeroH = _ref.zeroH,
          strokeWidth = _ref.strokeWidth;
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var i = indexes.i;
      var j = indexes.j;
      var isPositive = true;
      var colorPos = w.config.plotOptions.candlestick.colors.upward;
      var colorNeg = w.config.plotOptions.candlestick.colors.downward;
      var yRatio = this.yRatio[this.yaxisIndex];
      var realIndex = indexes.realIndex;
      var ohlc = this.getOHLCValue(realIndex, j);
      var l1 = zeroH;
      var l2 = zeroH;

      if (ohlc.o > ohlc.c) {
        isPositive = false;
      }

      var y1 = Math.min(ohlc.o, ohlc.c);
      var y2 = Math.max(ohlc.o, ohlc.c);

      if (w.globals.isXNumeric) {
        x = (w.globals.seriesX[i][j] - w.globals.minX) / this.xRatio - barWidth / 2;
      }

      var barXPosition = x + barWidth * this.visibleI;

      if (typeof this.series[i][j] === 'undefined' || this.series[i][j] === null) {
        y1 = zeroH;
      } else {
        y1 = zeroH - y1 / yRatio;
        y2 = zeroH - y2 / yRatio;
        l1 = zeroH - ohlc.h / yRatio;
        l2 = zeroH - ohlc.l / yRatio;
      }

      pathTo = graphics.move(barXPosition, zeroH);
      pathFrom = graphics.move(barXPosition, y1);

      if (w.globals.previousPaths.length > 0) {
        pathFrom = this.getPathFrom(realIndex, j, true);
      }

      pathTo = graphics.move(barXPosition, y2) + graphics.line(barXPosition + barWidth / 2, y2) + graphics.line(barXPosition + barWidth / 2, l1) + graphics.line(barXPosition + barWidth / 2, y2) + graphics.line(barXPosition + barWidth, y2) + graphics.line(barXPosition + barWidth, y1) + graphics.line(barXPosition + barWidth / 2, y1) + graphics.line(barXPosition + barWidth / 2, l2) + graphics.line(barXPosition + barWidth / 2, y1) + graphics.line(barXPosition, y1) + graphics.line(barXPosition, y2 - strokeWidth / 2);
      pathFrom = pathFrom + graphics.move(barXPosition, y1);

      if (!w.globals.isXNumeric) {
        x = x + xDivision;
      }

      return {
        pathTo: pathTo,
        pathFrom: pathFrom,
        x: x,
        y: y2,
        barXPosition: barXPosition,
        color: isPositive ? colorPos : colorNeg
      };
    }
  }, {
    key: "getOHLCValue",
    value: function getOHLCValue(i, j) {
      var w = this.w;
      return {
        o: w.globals.seriesCandleO[i][j],
        h: w.globals.seriesCandleH[i][j],
        l: w.globals.seriesCandleL[i][j],
        c: w.globals.seriesCandleC[i][j]
      };
    }
  }]);

  return CandleStick;
}(Bar);

var Crosshairs =
/*#__PURE__*/
function () {
  function Crosshairs(ctx) {
    _classCallCheck(this, Crosshairs);

    this.ctx = ctx;
    this.w = ctx.w;
  }

  _createClass(Crosshairs, [{
    key: "drawXCrosshairs",
    value: function drawXCrosshairs() {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var filters = new Filters(this.ctx);
      var crosshairGradient = w.config.xaxis.crosshairs.fill.gradient;
      var crosshairShadow = w.config.xaxis.crosshairs.dropShadow;
      var fillType = w.config.xaxis.crosshairs.fill.type;
      var gradientFrom = crosshairGradient.colorFrom;
      var gradientTo = crosshairGradient.colorTo;
      var opacityFrom = crosshairGradient.opacityFrom;
      var opacityTo = crosshairGradient.opacityTo;
      var stops = crosshairGradient.stops;
      var shadow = 'none';
      var dropShadow = crosshairShadow.enabled;
      var shadowLeft = crosshairShadow.left;
      var shadowTop = crosshairShadow.top;
      var shadowBlur = crosshairShadow.blur;
      var shadowColor = crosshairShadow.color;
      var shadowOpacity = crosshairShadow.opacity;
      var xcrosshairsFill = w.config.xaxis.crosshairs.fill.color;

      if (w.config.xaxis.crosshairs.show) {
        if (fillType === 'gradient') {
          xcrosshairsFill = graphics.drawGradient('vertical', gradientFrom, gradientTo, opacityFrom, opacityTo, null, stops, null);
        }

        var xcrosshairs = graphics.drawRect();

        if (w.config.xaxis.crosshairs.width === 1) {
          // to prevent drawing 2 lines, convert rect to line
          xcrosshairs = graphics.drawLine();
        }

        xcrosshairs.attr({
          class: 'apexcharts-xcrosshairs',
          x: 0,
          y: 0,
          y2: w.globals.gridHeight,
          width: Utils.isNumber(w.config.xaxis.crosshairs.width) ? w.config.xaxis.crosshairs.width : 0,
          height: w.globals.gridHeight,
          fill: xcrosshairsFill,
          filter: shadow,
          'fill-opacity': w.config.xaxis.crosshairs.opacity,
          stroke: w.config.xaxis.crosshairs.stroke.color,
          'stroke-width': w.config.xaxis.crosshairs.stroke.width,
          'stroke-dasharray': w.config.xaxis.crosshairs.stroke.dashArray
        });

        if (dropShadow) {
          xcrosshairs = filters.dropShadow(xcrosshairs, {
            left: shadowLeft,
            top: shadowTop,
            blur: shadowBlur,
            color: shadowColor,
            opacity: shadowOpacity
          });
        }

        w.globals.dom.elGraphical.add(xcrosshairs);
      }
    }
  }, {
    key: "drawYCrosshairs",
    value: function drawYCrosshairs() {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var crosshair = w.config.yaxis[0].crosshairs;

      if (w.config.yaxis[0].crosshairs.show) {
        var ycrosshairs = graphics.drawLine(0, 0, w.globals.gridWidth, 0, crosshair.stroke.color, crosshair.stroke.dashArray, crosshair.stroke.width);
        ycrosshairs.attr({
          class: 'apexcharts-ycrosshairs'
        });
        w.globals.dom.elGraphical.add(ycrosshairs);
      } // draw an invisible crosshair to help in positioning the yaxis tooltip


      var ycrosshairsHidden = graphics.drawLine(0, 0, w.globals.gridWidth, 0, crosshair.stroke.color, 0, 0);
      ycrosshairsHidden.attr({
        class: 'apexcharts-ycrosshairs-hidden'
      });
      w.globals.dom.elGraphical.add(ycrosshairsHidden);
    }
  }]);

  return Crosshairs;
}();

/**
 * ApexCharts HeatMap Class.
 * @module HeatMap
 **/

var HeatMap =
/*#__PURE__*/
function () {
  function HeatMap(ctx, xyRatios) {
    _classCallCheck(this, HeatMap);

    this.ctx = ctx;
    this.w = ctx.w;
    this.xRatio = xyRatios.xRatio;
    this.yRatio = xyRatios.yRatio;
    this.negRange = false;
    this.dynamicAnim = this.w.config.chart.animations.dynamicAnimation;
    this.rectRadius = this.w.config.plotOptions.heatmap.radius;
    this.strokeWidth = this.w.config.stroke.width;
  }

  _createClass(HeatMap, [{
    key: "draw",
    value: function draw(series) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var ret = graphics.group({
        class: 'apexcharts-heatmap'
      });
      ret.attr('clip-path', "url(#gridRectMask".concat(w.globals.cuid, ")")); // width divided into equal parts

      var xDivision = w.globals.gridWidth / w.globals.dataPoints;
      var yDivision = w.globals.gridHeight / w.globals.series.length;
      var y1 = 0;
      var rev = false;
      this.checkColorRange();
      var heatSeries = series.slice();

      if (w.config.yaxis[0].reversed) {
        rev = true;
        heatSeries.reverse();
      }

      for (var i = rev ? 0 : heatSeries.length - 1; rev ? i < heatSeries.length : i >= 0; rev ? i++ : i--) {
        // el to which series will be drawn
        var elSeries = graphics.group({
          class: "apexcharts-series apexcharts-heatmap-series ".concat(Utils.escapeString(w.globals.seriesNames[i])),
          rel: i + 1,
          'data:realIndex': i
        });

        if (w.config.chart.dropShadow.enabled) {
          var shadow = w.config.chart.dropShadow;
          var filters = new Filters(this.ctx);
          filters.dropShadow(elSeries, shadow, i);
        }

        var x1 = 0;

        for (var j = 0; j < heatSeries[i].length; j++) {
          var colorShadePercent = 1;
          var heatColorProps = this.determineHeatColor(i, j);

          if (w.globals.hasNegs || this.negRange) {
            var shadeIntensity = w.config.plotOptions.heatmap.shadeIntensity;

            if (heatColorProps.percent < 0) {
              colorShadePercent = 1 - (1 + heatColorProps.percent / 100) * shadeIntensity;
            } else {
              colorShadePercent = (1 - heatColorProps.percent / 100) * shadeIntensity;
            }
          } else {
            colorShadePercent = 1 - heatColorProps.percent / 100;
          }

          var color = heatColorProps.color;

          if (w.config.plotOptions.heatmap.enableShades) {
            var utils = new Utils();
            color = Utils.hexToRgba(utils.shadeColor(colorShadePercent, heatColorProps.color), w.config.fill.opacity);
          }

          var radius = this.rectRadius;
          var rect = graphics.drawRect(x1, y1, xDivision, yDivision, radius);
          rect.attr({
            cx: x1,
            cy: y1
          });
          rect.node.classList.add('apexcharts-heatmap-rect');
          elSeries.add(rect);
          rect.attr({
            fill: color,
            i: i,
            index: i,
            j: j,
            val: heatSeries[i][j],
            'stroke-width': this.strokeWidth,
            stroke: w.globals.stroke.colors[0],
            color: color
          });
          rect.node.addEventListener('mouseenter', graphics.pathMouseEnter.bind(this, rect));
          rect.node.addEventListener('mouseleave', graphics.pathMouseLeave.bind(this, rect));
          rect.node.addEventListener('mousedown', graphics.pathMouseDown.bind(this, rect));

          if (w.config.chart.animations.enabled && !w.globals.dataChanged) {
            var speed = 1;

            if (!w.globals.resized) {
              speed = w.config.chart.animations.speed;
            }

            this.animateHeatMap(rect, x1, y1, xDivision, yDivision, speed);
          }

          if (w.globals.dataChanged) {
            var _speed = 1;

            if (this.dynamicAnim.enabled && w.globals.shouldAnimate) {
              _speed = this.dynamicAnim.speed;
              var colorFrom = w.globals.previousPaths[i] && w.globals.previousPaths[i][j] && w.globals.previousPaths[i][j].color;
              if (!colorFrom) colorFrom = 'rgba(255, 255, 255, 0)';
              this.animateHeatColor(rect, Utils.isColorHex(colorFrom) ? colorFrom : Utils.rgb2hex(colorFrom), Utils.isColorHex(color) ? color : Utils.rgb2hex(color), _speed);
            }
          }

          var dataLabels = this.calculateHeatmapDataLabels({
            x: x1,
            y: y1,
            i: i,
            j: j,
            series: heatSeries,
            rectHeight: yDivision,
            rectWidth: xDivision
          });

          if (dataLabels !== null) {
            elSeries.add(dataLabels);
          }

          x1 = x1 + xDivision;
        }

        y1 = y1 + yDivision;
        ret.add(elSeries);
      } // adjust yaxis labels for heatmap


      var yAxisScale = w.globals.yAxisScale[0].result.slice();

      if (w.config.yaxis[0].reversed) {
        yAxisScale.unshift('');
      } else {
        yAxisScale.push('');
      }

      w.globals.yAxisScale[0].result = yAxisScale;
      var divisor = w.globals.gridHeight / w.globals.series.length;
      w.config.yaxis[0].labels.offsetY = -(divisor / 2);
      return ret;
    }
  }, {
    key: "checkColorRange",
    value: function checkColorRange() {
      var _this = this;

      var w = this.w;
      var heatmap = w.config.plotOptions.heatmap;

      if (heatmap.colorScale.ranges.length > 0) {
        heatmap.colorScale.ranges.map(function (range, index) {
          if (range.from < 0) {
            _this.negRange = true;
          }
        });
      }
    }
  }, {
    key: "determineHeatColor",
    value: function determineHeatColor(i, j) {
      var w = this.w;
      var val = w.globals.series[i][j];
      var heatmap = w.config.plotOptions.heatmap;
      var seriesNumber = heatmap.colorScale.inverse ? j : i;
      var color = w.globals.colors[seriesNumber];
      var min = Math.min.apply(Math, _toConsumableArray(w.globals.series[i]));
      var max = Math.max.apply(Math, _toConsumableArray(w.globals.series[i]));

      if (!heatmap.distributed) {
        min = w.globals.minY;
        max = w.globals.maxY;
      }

      if (typeof heatmap.colorScale.min !== 'undefined') {
        min = heatmap.colorScale.min < w.globals.minY ? heatmap.colorScale.min : w.globals.minY;
        max = heatmap.colorScale.max > w.globals.maxY ? heatmap.colorScale.max : w.globals.maxY;
      }

      var total = Math.abs(max) + Math.abs(min);
      var percent = 100 * val / (total === 0 ? total - 0.000001 : total);

      if (heatmap.colorScale.ranges.length > 0) {
        var colorRange = heatmap.colorScale.ranges;
        colorRange.map(function (range, index) {
          if (val >= range.from && val <= range.to) {
            color = range.color;
            min = range.from;
            max = range.to;

            var _total = Math.abs(max) + Math.abs(min);

            percent = 100 * val / (_total === 0 ? _total - 0.000001 : _total);
          }
        });
      }

      return {
        color: color,
        percent: percent
      };
    }
  }, {
    key: "calculateHeatmapDataLabels",
    value: function calculateHeatmapDataLabels(_ref) {
      var x = _ref.x,
          y = _ref.y,
          i = _ref.i,
          j = _ref.j,
          series = _ref.series,
          rectHeight = _ref.rectHeight,
          rectWidth = _ref.rectWidth;
      var w = this.w; // let graphics = new Graphics(this.ctx)

      var dataLabelsConfig = w.config.dataLabels;
      var graphics = new Graphics(this.ctx);
      var dataLabels = new DataLabels(this.ctx);
      var formatter = dataLabelsConfig.formatter;
      var elDataLabelsWrap = null;

      if (dataLabelsConfig.enabled) {
        elDataLabelsWrap = graphics.group({
          class: 'apexcharts-data-labels'
        });
        var offX = dataLabelsConfig.offsetX;
        var offY = dataLabelsConfig.offsetY;
        var dataLabelsX = x + rectWidth / 2 + offX;
        var dataLabelsY = y + rectHeight / 2 + parseInt(dataLabelsConfig.style.fontSize) / 3 + offY;
        var text = formatter(w.globals.series[i][j], {
          seriesIndex: i,
          dataPointIndex: j,
          w: w
        });
        dataLabels.plotDataLabelsText({
          x: dataLabelsX,
          y: dataLabelsY,
          text: text,
          i: i,
          j: j,
          parent: elDataLabelsWrap,
          dataLabelsConfig: dataLabelsConfig
        });
      }

      return elDataLabelsWrap;
    }
  }, {
    key: "animateHeatMap",
    value: function animateHeatMap(el, x, y, width, height, speed) {
      var _this2 = this;

      var animations = new Animations(this.ctx);
      animations.animateRect(el, {
        x: x + width / 2,
        y: y + height / 2,
        width: 0,
        height: 0
      }, {
        x: x,
        y: y,
        width: width,
        height: height
      }, speed, function () {
        _this2.w.globals.animationEnded = true;
      });
    }
  }, {
    key: "animateHeatColor",
    value: function animateHeatColor(el, colorFrom, colorTo, speed) {
      el.attr({
        fill: colorFrom
      }).animate(speed).attr({
        fill: colorTo
      });
    }
  }]);

  return HeatMap;
}();

/**
 * ApexCharts Pie Class for drawing Pie / Donut Charts.
 * @module Pie
 **/

var Pie =
/*#__PURE__*/
function () {
  function Pie(ctx) {
    _classCallCheck(this, Pie);

    this.ctx = ctx;
    this.w = ctx.w;
    this.chartType = this.w.config.chart.type;
    this.initialAnim = this.w.config.chart.animations.enabled;
    this.dynamicAnim = this.initialAnim && this.w.config.chart.animations.dynamicAnimation.enabled;
    this.animBeginArr = [0];
    this.animDur = 0;
    this.donutDataLabels = this.w.config.plotOptions.pie.donut.labels;
    var w = this.w;
    this.lineColorArr = w.globals.stroke.colors !== undefined ? w.globals.stroke.colors : w.globals.colors;
    this.defaultSize = w.globals.svgHeight < w.globals.svgWidth ? w.globals.svgHeight - 35 : w.globals.gridWidth;
    this.centerY = this.defaultSize / 2;
    this.centerX = w.globals.gridWidth / 2;
    this.fullAngle = 360;
    this.size = 0;
    this.donutSize = 0;
    this.sliceLabels = [];
    this.prevSectorAngleArr = []; // for dynamic animations
  }

  _createClass(Pie, [{
    key: "draw",
    value: function draw(series) {
      var self = this;
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var ret = graphics.group({
        class: 'apexcharts-pie'
      });
      var total = 0;

      for (var k = 0; k < series.length; k++) {
        // CALCULATE THE TOTAL
        total += Utils.negToZero(series[k]);
      }

      var sectorAngleArr = []; // el to which series will be drawn

      var elSeries = graphics.group(); // prevent division by zero error if there is no data

      if (total === 0) {
        total = 0.00001;
      }

      for (var i = 0; i < series.length; i++) {
        // CALCULATE THE ANGLES
        var angle = this.fullAngle * Utils.negToZero(series[i]) / total;
        sectorAngleArr.push(angle);
      }

      if (w.globals.dataChanged) {
        var prevTotal = 0;

        for (var _k = 0; _k < w.globals.previousPaths.length; _k++) {
          // CALCULATE THE PREV TOTAL
          prevTotal += Utils.negToZero(w.globals.previousPaths[_k]);
        }

        var previousAngle;

        for (var _i = 0; _i < w.globals.previousPaths.length; _i++) {
          // CALCULATE THE PREVIOUS ANGLES
          previousAngle = this.fullAngle * Utils.negToZero(w.globals.previousPaths[_i]) / prevTotal;
          this.prevSectorAngleArr.push(previousAngle);
        }
      }

      this.size = this.defaultSize / 2.05 - w.config.stroke.width - w.config.chart.dropShadow.blur;

      if (w.config.plotOptions.pie.size !== undefined) {
        this.size = w.config.plotOptions.pie.size;
      }

      this.donutSize = this.size * parseInt(w.config.plotOptions.pie.donut.size) / 100;
      var scaleSize = w.config.plotOptions.pie.customScale;
      var halfW = w.globals.gridWidth / 2;
      var halfH = w.globals.gridHeight / 2;
      var translateX = halfW - w.globals.gridWidth / 2 * scaleSize;
      var translateY = halfH - w.globals.gridHeight / 2 * scaleSize;

      if (this.donutDataLabels.show) {
        var dataLabels = this.renderInnerDataLabels(this.donutDataLabels, {
          hollowSize: this.donutSize,
          centerX: this.centerX,
          centerY: this.centerY,
          opacity: this.donutDataLabels.show,
          translateX: translateX,
          translateY: translateY
        });
        ret.add(dataLabels);
      }

      if (w.config.chart.type === 'donut') {
        // draw the inner circle and add some text to it
        var circle = graphics.drawCircle(this.donutSize);
        circle.attr({
          cx: this.centerX,
          cy: this.centerY,
          fill: w.config.plotOptions.pie.donut.background
        });
        elSeries.add(circle);
      }

      var elG = self.drawArcs(sectorAngleArr, series); // add slice dataLabels at the end

      this.sliceLabels.forEach(function (s) {
        elG.add(s);
      });
      elSeries.attr({
        transform: "translate(".concat(translateX, ", ").concat(translateY - 5, ") scale(").concat(scaleSize, ")")
      });
      ret.attr({
        'data:innerTranslateX': translateX,
        'data:innerTranslateY': translateY - 25
      });
      elSeries.add(elG);
      ret.add(elSeries);
      return ret;
    } // core function for drawing pie arcs

  }, {
    key: "drawArcs",
    value: function drawArcs(sectorAngleArr, series) {
      var w = this.w;
      var filters = new Filters(this.ctx);
      var graphics = new Graphics(this.ctx);
      var fill = new Fill(this.ctx);
      var g = graphics.group();
      var startAngle = 0;
      var prevStartAngle = 0;
      var endAngle = 0;
      var prevEndAngle = 0;
      this.strokeWidth = w.config.stroke.show ? w.config.stroke.width : 0;

      for (var i = 0; i < sectorAngleArr.length; i++) {
        // if(sectorAngleArr[i]>0) {
        var elPieArc = graphics.group({
          class: "apexcharts-series apexcharts-pie-series ".concat(Utils.escapeString(w.globals.seriesNames[i])),
          id: 'apexcharts-series-' + i,
          rel: i + 1
        });
        g.add(elPieArc);
        startAngle = endAngle;
        prevStartAngle = prevEndAngle;
        endAngle = startAngle + sectorAngleArr[i];
        prevEndAngle = prevStartAngle + this.prevSectorAngleArr[i];
        var angle = endAngle - startAngle;
        var pathFill = fill.fillPath({
          seriesNumber: i,
          size: this.size,
          value: series[i]
        }); // additionaly, pass size for gradient drawing in the fillPath function

        var path = this.getChangedPath(prevStartAngle, prevEndAngle);
        var elPath = graphics.drawPath({
          d: path,
          stroke: this.lineColorArr instanceof Array ? this.lineColorArr[i] : this.lineColorArr,
          strokeWidth: this.strokeWidth,
          fill: pathFill,
          fillOpacity: w.config.fill.opacity,
          classes: 'apexcharts-pie-area'
        });
        elPath.attr({
          id: "apexcharts-".concat(w.config.chart.type, "-slice-").concat(i),
          index: 0,
          j: i
        });

        if (w.config.chart.dropShadow.enabled) {
          var shadow = w.config.chart.dropShadow;
          filters.dropShadow(elPath, shadow, i);
        }

        this.addListeners(elPath, this.donutDataLabels);
        Graphics.setAttrs(elPath.node, {
          'data:angle': angle,
          'data:startAngle': startAngle,
          'data:strokeWidth': this.strokeWidth,
          'data:value': series[i]
        });
        var labelPosition = {
          x: 0,
          y: 0
        };

        if (w.config.chart.type === 'pie') {
          labelPosition = Utils.polarToCartesian(this.centerX, this.centerY, this.size / 1.25 + w.config.plotOptions.pie.dataLabels.offset, startAngle + (endAngle - startAngle) / 2);
        } else if (w.config.chart.type === 'donut') {
          labelPosition = Utils.polarToCartesian(this.centerX, this.centerY, (this.size + this.donutSize) / 2 + w.config.plotOptions.pie.dataLabels.offset, startAngle + (endAngle - startAngle) / 2);
        }

        elPieArc.add(elPath); // Animation code starts

        var dur = 0;

        if (this.initialAnim && !w.globals.resized && !w.globals.dataChanged) {
          dur = (endAngle - startAngle) / this.fullAngle * w.config.chart.animations.speed;
          this.animDur = dur + this.animDur;
          this.animBeginArr.push(this.animDur);
        } else {
          this.animBeginArr.push(0);
        }

        if (this.dynamicAnim && w.globals.dataChanged) {
          this.animatePaths(elPath, {
            endAngle: endAngle,
            startAngle: startAngle,
            prevStartAngle: prevStartAngle,
            prevEndAngle: prevEndAngle,
            animateStartingPos: true,
            i: i,
            animBeginArr: this.animBeginArr,
            dur: w.config.chart.animations.dynamicAnimation.speed
          });
        } else {
          this.animatePaths(elPath, {
            endAngle: endAngle,
            startAngle: startAngle,
            i: i,
            totalItems: sectorAngleArr.length - 1,
            animBeginArr: this.animBeginArr,
            dur: dur
          });
        } // animation code ends


        if (w.config.plotOptions.pie.expandOnClick) {
          elPath.click(this.pieClicked.bind(this, i));
        }

        if (w.config.dataLabels.enabled) {
          var xPos = labelPosition.x;
          var yPos = labelPosition.y;
          var text = 100 * (endAngle - startAngle) / 360 + '%';

          if (angle !== 0 && w.config.plotOptions.pie.dataLabels.minAngleToShowLabel < sectorAngleArr[i]) {
            var formatter = w.config.dataLabels.formatter;

            if (formatter !== undefined) {
              text = formatter(w.globals.seriesPercent[i][0], {
                seriesIndex: i,
                w: w
              });
            }

            var foreColor = w.globals.dataLabels.style.colors[i];
            var elPieLabel = graphics.drawText({
              x: xPos,
              y: yPos,
              text: text,
              textAnchor: 'middle',
              fontSize: w.config.dataLabels.style.fontSize,
              fontFamily: w.config.dataLabels.style.fontFamily,
              foreColor: foreColor
            });

            if (w.config.dataLabels.dropShadow.enabled) {
              var textShadow = w.config.dataLabels.dropShadow;

              var _filters = new Filters(this.ctx);

              _filters.dropShadow(elPieLabel, textShadow);
            }

            elPieLabel.node.classList.add('apexcharts-pie-label');

            if (w.config.chart.animations.animate && w.globals.resized === false) {
              elPieLabel.node.classList.add('apexcharts-pie-label-delay');
              elPieLabel.node.style.animationDelay = w.config.chart.animations.speed / 940 + 's';
            }

            this.sliceLabels.push(elPieLabel);
          }
        } // }

      }

      return g;
    }
  }, {
    key: "addListeners",
    value: function addListeners(elPath, dataLabels) {
      var graphics = new Graphics(this.ctx); // append filters on mouseenter and mouseleave

      elPath.node.addEventListener('mouseenter', graphics.pathMouseEnter.bind(this, elPath));
      elPath.node.addEventListener('mouseenter', this.printDataLabelsInner.bind(this, elPath.node, dataLabels));
      elPath.node.addEventListener('mouseleave', graphics.pathMouseLeave.bind(this, elPath));
      elPath.node.addEventListener('mouseleave', this.revertDataLabelsInner.bind(this, elPath.node, dataLabels));
      elPath.node.addEventListener('mousedown', graphics.pathMouseDown.bind(this, elPath));
      elPath.node.addEventListener('mousedown', this.printDataLabelsInner.bind(this, elPath.node, dataLabels));
    } // This function can be used for other circle charts too

  }, {
    key: "animatePaths",
    value: function animatePaths(el, opts) {
      var w = this.w;
      var me = this;
      var angle = opts.endAngle - opts.startAngle;
      var prevAngle = angle;
      var fromStartAngle = opts.startAngle;
      var toStartAngle = opts.startAngle;

      if (opts.prevStartAngle !== undefined && opts.prevEndAngle !== undefined) {
        fromStartAngle = opts.prevEndAngle;
        prevAngle = opts.prevEndAngle - opts.prevStartAngle;
      }

      if (opts.i === w.config.series.length - 1) {
        // some adjustments for the last overlapping paths
        if (angle + toStartAngle > this.fullAngle) {
          opts.endAngle = opts.endAngle - (angle + toStartAngle);
        } else if (angle + toStartAngle < this.fullAngle) {
          opts.endAngle = opts.endAngle + (this.fullAngle - (angle + toStartAngle));
        }
      }

      if (angle === this.fullAngle) angle = this.fullAngle - 0.01;
      me.animateArc(el, fromStartAngle, toStartAngle, angle, prevAngle, opts);
    }
  }, {
    key: "animateArc",
    value: function animateArc(el, fromStartAngle, toStartAngle, angle, prevAngle, opts) {
      var me = this;
      var w = this.w;
      var size = me.size;

      if (!size) {
        size = opts.size;
      }

      var path;

      if (isNaN(fromStartAngle) || isNaN(prevAngle)) {
        fromStartAngle = toStartAngle;
        prevAngle = angle;
        opts.dur = 0;
      }

      var currAngle = angle;
      var startAngle = toStartAngle;
      var fromAngle = fromStartAngle - toStartAngle;

      if (w.globals.dataChanged && opts.shouldSetPrevPaths) {
        // to avoid flickering, set prev path first and then we will animate from there
        path = me.getPiePath({
          me: me,
          startAngle: startAngle,
          angle: prevAngle,
          size: size
        });
        el.attr({
          d: path
        });
      }

      if (opts.dur !== 0) {
        el.animate(opts.dur, w.globals.easing, opts.animBeginArr[opts.i]).afterAll(function () {
          if (w.config.chart.type === 'pie' || w.config.chart.type === 'donut') {
            this.animate(300).attr({
              'stroke-width': w.config.stroke.width
            });
          }

          w.globals.animationEnded = true;
        }).during(function (pos) {
          currAngle = fromAngle + (angle - fromAngle) * pos;

          if (opts.animateStartingPos) {
            currAngle = prevAngle + (angle - prevAngle) * pos;
            startAngle = fromStartAngle - prevAngle + (toStartAngle - (fromStartAngle - prevAngle)) * pos;
          }

          path = me.getPiePath({
            me: me,
            startAngle: startAngle,
            angle: currAngle,
            size: size
          });
          el.node.setAttribute('data:pathOrig', path);
          el.attr({
            d: path
          });
        });
      } else {
        path = me.getPiePath({
          me: me,
          startAngle: startAngle,
          angle: angle,
          size: size
        });

        if (!opts.isTrack) {
          w.globals.animationEnded = true;
        }

        el.node.setAttribute('data:pathOrig', path);
        el.attr({
          d: path
        });
      }
    }
  }, {
    key: "pieClicked",
    value: function pieClicked(i) {
      var w = this.w;
      var me = this;
      var path;
      var size = me.size + 4;
      var elPath = w.globals.dom.Paper.select("#apexcharts-".concat(w.config.chart.type.toLowerCase(), "-slice-").concat(i)).members[0];
      var pathFrom = elPath.attr('d');

      if (elPath.attr('data:pieClicked') === 'true') {
        elPath.attr({
          'data:pieClicked': 'false'
        });
        this.revertDataLabelsInner(elPath.node, this.donutDataLabels);
        var origPath = elPath.attr('data:pathOrig');
        elPath.attr({
          d: origPath
        });
        return;
      } else {
        // reset all elems
        var allEls = w.globals.dom.baseEl.querySelectorAll('.apexcharts-pie-area');
        Array.prototype.forEach.call(allEls, function (pieSlice) {
          pieSlice.setAttribute('data:pieClicked', 'false');
          var origPath = pieSlice.getAttribute('data:pathOrig');
          pieSlice.setAttribute('d', origPath);
        });
        elPath.attr('data:pieClicked', 'true');
      }

      var startAngle = parseInt(elPath.attr('data:startAngle'));
      var angle = parseInt(elPath.attr('data:angle'));
      path = me.getPiePath({
        me: me,
        startAngle: startAngle,
        angle: angle,
        size: size
      });
      if (angle === 360) return;
      elPath.plot(path).animate(1).plot(pathFrom).animate(100).plot(path);
    }
  }, {
    key: "getChangedPath",
    value: function getChangedPath(prevStartAngle, prevEndAngle) {
      var path = '';

      if (this.dynamicAnim && this.w.globals.dataChanged) {
        path = this.getPiePath({
          me: this,
          startAngle: prevStartAngle,
          angle: prevEndAngle - prevStartAngle,
          size: this.size
        });
      }

      return path;
    }
  }, {
    key: "getPiePath",
    value: function getPiePath(_ref) {
      var me = _ref.me,
          startAngle = _ref.startAngle,
          angle = _ref.angle,
          size = _ref.size;
      var w = this.w;
      var path;
      var startDeg = startAngle;
      var startRadians = Math.PI * (startDeg - 90) / 180;
      var endDeg = angle + startAngle;
      if (Math.ceil(endDeg) >= 360) endDeg = 359.99;
      var endRadians = Math.PI * (endDeg - 90) / 180;
      var x1 = me.centerX + size * Math.cos(startRadians);
      var y1 = me.centerY + size * Math.sin(startRadians);
      var x2 = me.centerX + size * Math.cos(endRadians);
      var y2 = me.centerY + size * Math.sin(endRadians);
      var startInner = Utils.polarToCartesian(me.centerX, me.centerY, me.donutSize, endDeg);
      var endInner = Utils.polarToCartesian(me.centerX, me.centerY, me.donutSize, startDeg);
      var largeArc = angle > 180 ? 1 : 0;

      if (w.config.chart.type === 'donut') {
        path = ['M', x1, y1, 'A', size, size, 0, largeArc, 1, x2, y2, 'L', startInner.x, startInner.y, 'A', me.donutSize, me.donutSize, 0, largeArc, 0, endInner.x, endInner.y, 'L', x1, y1, 'z'].join(' ');
      } else if (w.config.chart.type === 'pie') {
        path = ['M', x1, y1, 'A', size, size, 0, largeArc, 1, x2, y2, 'L', me.centerX, me.centerY, 'L', x1, y1].join(' ');
      } else {
        path = ['M', x1, y1, 'A', size, size, 0, largeArc, 1, x2, y2].join(' ');
      }

      return path;
    }
  }, {
    key: "renderInnerDataLabels",
    value: function renderInnerDataLabels(dataLabelsConfig, opts) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var g = graphics.group({
        class: 'apexcharts-datalabels-group',
        transform: "translate(".concat(opts.translateX ? opts.translateX : 0, ", ").concat(opts.translateY ? opts.translateY : 0, ")")
      });
      var showTotal = dataLabelsConfig.total.show;
      g.node.style.opacity = opts.opacity;
      var x = opts.centerX;
      var y = opts.centerY;
      var labelColor, valueColor;

      if (dataLabelsConfig.name.color === undefined) {
        labelColor = w.globals.colors[0];
      } else {
        labelColor = dataLabelsConfig.name.color;
      }

      if (dataLabelsConfig.value.color === undefined) {
        valueColor = w.config.chart.foreColor;
      } else {
        valueColor = dataLabelsConfig.value.color;
      }

      var lbFormatter = dataLabelsConfig.value.formatter;
      var val = '';
      var name = '';

      if (showTotal) {
        labelColor = dataLabelsConfig.total.color;
        name = dataLabelsConfig.total.label;
        val = dataLabelsConfig.total.formatter(w);
      } else {
        if (w.globals.series.length === 1) {
          val = lbFormatter(w.globals.series[0], w);
          name = w.globals.seriesNames[0];
        }
      }

      if (dataLabelsConfig.name.show) {
        var elLabel = graphics.drawText({
          x: x,
          y: y + parseInt(dataLabelsConfig.name.offsetY),
          text: name,
          textAnchor: 'middle',
          foreColor: labelColor,
          fontSize: dataLabelsConfig.name.fontSize,
          fontFamily: dataLabelsConfig.name.fontFamily
        });
        elLabel.node.classList.add('apexcharts-datalabel-label');
        g.add(elLabel);
      }

      if (dataLabelsConfig.value.show) {
        var valOffset = dataLabelsConfig.name.show ? parseInt(dataLabelsConfig.value.offsetY) + 16 : dataLabelsConfig.value.offsetY;
        var elValue = graphics.drawText({
          x: x,
          y: y + valOffset,
          text: val,
          textAnchor: 'middle',
          foreColor: valueColor,
          fontSize: dataLabelsConfig.value.fontSize,
          fontFamily: dataLabelsConfig.value.fontFamily
        });
        elValue.node.classList.add('apexcharts-datalabel-value');
        g.add(elValue);
      } // for a multi-series circle chart, we need to show total value instead of first series labels


      return g;
    }
    /**
     *
     * @param {string} name - The name of the series
     * @param {string} val - The value of that series
     * @param {object} el - Optional el (indicates which series was hovered/clicked). If this param is not present, means we need to show total
     */

  }, {
    key: "printInnerLabels",
    value: function printInnerLabels(labelsConfig, name, val, el) {
      var w = this.w;
      var labelColor;

      if (el) {
        if (labelsConfig.name.color === undefined) {
          labelColor = w.globals.colors[parseInt(el.parentNode.getAttribute('rel')) - 1];
        } else {
          labelColor = labelsConfig.name.color;
        }
      } else {
        if (w.globals.series.length > 1 && labelsConfig.total.show) {
          labelColor = labelsConfig.total.color;
        }
      }

      var elLabel = w.globals.dom.baseEl.querySelector('.apexcharts-datalabel-label');
      var elValue = w.globals.dom.baseEl.querySelector('.apexcharts-datalabel-value');
      var lbFormatter = labelsConfig.value.formatter;
      val = lbFormatter(val, w); // we need to show Total Val - so get the formatter of it

      if (!el && typeof labelsConfig.total.formatter === 'function') {
        val = labelsConfig.total.formatter(w);
      }

      if (elLabel !== null) {
        elLabel.textContent = name;
      }

      if (elValue !== null) {
        elValue.textContent = val;
      }

      if (elLabel !== null) {
        elLabel.style.fill = labelColor;
      }
    }
  }, {
    key: "printDataLabelsInner",
    value: function printDataLabelsInner(el, dataLabelsConfig) {
      var w = this.w;
      var val = el.getAttribute('data:value');
      var name = w.globals.seriesNames[parseInt(el.parentNode.getAttribute('rel')) - 1];

      if (w.globals.series.length > 1) {
        this.printInnerLabels(dataLabelsConfig, name, val, el);
      }

      var dataLabelsGroup = w.globals.dom.baseEl.querySelector('.apexcharts-datalabels-group');

      if (dataLabelsGroup !== null) {
        dataLabelsGroup.style.opacity = 1;
      }
    }
  }, {
    key: "revertDataLabelsInner",
    value: function revertDataLabelsInner(el, dataLabelsConfig, event) {
      var _this = this;

      var w = this.w;
      var dataLabelsGroup = w.globals.dom.baseEl.querySelector('.apexcharts-datalabels-group');

      if (dataLabelsConfig.total.show && w.globals.series.length > 1) {
        var pie = new Pie(this.ctx);
        pie.printInnerLabels(dataLabelsConfig, dataLabelsConfig.total.label, dataLabelsConfig.total.formatter(w));
      } else {
        var slices = document.querySelectorAll(".apexcharts-pie-area");
        var sliceOut = false;
        slices.forEach(function (s) {
          if (s.getAttribute('data:pieClicked') === 'true') {
            sliceOut = true;

            _this.printDataLabelsInner(s, dataLabelsConfig);
          }
        });

        if (!sliceOut) {
          if (w.globals.selectedDataPoints.length && w.globals.series.length > 1) {
            if (w.globals.selectedDataPoints[0].length > 0) {
              var index = w.globals.selectedDataPoints[0];

              var _el = w.globals.dom.baseEl.querySelector("#apexcharts-".concat(w.config.chart.type.toLowerCase(), "-slice-").concat(index));

              this.printDataLabelsInner(_el, dataLabelsConfig);
            } else if (dataLabelsGroup && w.globals.selectedDataPoints.length && w.globals.selectedDataPoints[0].length === 0) {
              dataLabelsGroup.style.opacity = 0;
            }
          } else {
            if (dataLabelsGroup && w.globals.series.length > 1) {
              dataLabelsGroup.style.opacity = 0;
            }
          }
        }
      }
    }
  }]);

  return Pie;
}();

/**
 * ApexCharts Radar Class for Spider/Radar Charts.
 * @module Radar
 **/

var Radar =
/*#__PURE__*/
function () {
  function Radar(ctx) {
    _classCallCheck(this, Radar);

    this.ctx = ctx;
    this.w = ctx.w;
    this.chartType = this.w.config.chart.type;
    this.initialAnim = this.w.config.chart.animations.enabled;
    this.dynamicAnim = this.initialAnim && this.w.config.chart.animations.dynamicAnimation.enabled;
    this.animDur = 0;
    var w = this.w;
    this.graphics = new Graphics(this.ctx);
    this.lineColorArr = w.globals.stroke.colors !== undefined ? w.globals.stroke.colors : w.globals.colors;
    this.defaultSize = w.globals.svgHeight < w.globals.svgWidth ? w.globals.svgHeight - 35 : w.globals.gridWidth;
    this.maxValue = this.w.globals.maxY;
    this.polygons = w.config.plotOptions.radar.polygons;
    this.maxLabelWidth = 20;
    var longestLabel = w.globals.labels.slice().sort(function (a, b) {
      return b.length - a.length;
    })[0];
    var labelWidth = this.graphics.getTextRects(longestLabel, w.config.dataLabels.style.fontSize);
    this.size = this.defaultSize / 2.1 - w.config.stroke.width - w.config.chart.dropShadow.blur - labelWidth.width / 1.75;

    if (w.config.plotOptions.radar.size !== undefined) {
      this.size = w.config.plotOptions.radar.size;
    }

    this.dataRadiusOfPercent = [];
    this.dataRadius = [];
    this.angleArr = [];
    this.yaxisLabelsTextsPos = [];
  }

  _createClass(Radar, [{
    key: "draw",
    value: function draw(series) {
      var _this = this;

      var w = this.w;
      var fill = new Fill(this.ctx);
      var allSeries = [];
      this.dataPointsLen = series[w.globals.maxValsInArrayIndex].length;
      this.disAngle = Math.PI * 2 / this.dataPointsLen;
      var halfW = w.globals.gridWidth / 2;
      var halfH = w.globals.gridHeight / 2;
      var translateX = halfW;
      var translateY = halfH;
      var ret = this.graphics.group({
        class: 'apexcharts-radar-series',
        'data:innerTranslateX': translateX,
        'data:innerTranslateY': translateY - 25,
        transform: "translate(".concat(translateX || 0, ", ").concat(translateY || 0, ")")
      });
      var dataPointsPos = [];
      var elPointsMain = null;
      this.yaxisLabels = this.graphics.group({
        class: 'apexcharts-yaxis'
      });
      series.forEach(function (s, i) {
        // el to which series will be drawn
        var elSeries = _this.graphics.group().attr({
          class: "apexcharts-series ".concat(Utils.escapeString(w.globals.seriesNames[i])),
          rel: i + 1,
          'data:realIndex': i
        });

        _this.dataRadiusOfPercent[i] = [];
        _this.dataRadius[i] = [];
        _this.angleArr[i] = [];
        s.forEach(function (dv, j) {
          _this.dataRadiusOfPercent[i][j] = dv / _this.maxValue;
          _this.dataRadius[i][j] = _this.dataRadiusOfPercent[i][j] * _this.size;
          _this.angleArr[i][j] = j * _this.disAngle;
        });
        dataPointsPos = _this.getDataPointsPos(_this.dataRadius[i], _this.angleArr[i]);

        var paths = _this.createPaths(dataPointsPos, {
          x: 0,
          y: 0
        }); // points


        elPointsMain = _this.graphics.group({
          class: 'apexcharts-series-markers-wrap hidden'
        });
        w.globals.delayedElements.push({
          el: elPointsMain.node,
          index: i
        });
        var defaultRenderedPathOptions = {
          i: i,
          realIndex: i,
          animationDelay: i,
          initialSpeed: w.config.chart.animations.speed,
          dataChangeSpeed: w.config.chart.animations.dynamicAnimation.speed,
          className: "apexcharts-radar",
          id: "apexcharts-radar",
          shouldClipToGrid: false,
          bindEventsOnPaths: false,
          stroke: w.globals.stroke.colors[i],
          strokeLineCap: w.config.stroke.lineCap
        };
        var pathFrom = null;

        if (w.globals.previousPaths.length > 0) {
          pathFrom = _this.getPathFrom(i);
        }

        for (var p = 0; p < paths.linePathsTo.length; p++) {
          var renderedLinePath = _this.graphics.renderPaths(_objectSpread({}, defaultRenderedPathOptions, {
            pathFrom: pathFrom === null ? paths.linePathsFrom[p] : pathFrom,
            pathTo: paths.linePathsTo[p],
            strokeWidth: Array.isArray(w.config.stroke.width) ? w.config.stroke.width[i] : w.config.stroke.width,
            fill: 'none',
            drawShadow: false
          }));

          elSeries.add(renderedLinePath);
          var pathFill = fill.fillPath({
            seriesNumber: i
          });

          var renderedAreaPath = _this.graphics.renderPaths(_objectSpread({}, defaultRenderedPathOptions, {
            pathFrom: pathFrom === null ? paths.areaPathsFrom[p] : pathFrom,
            pathTo: paths.areaPathsTo[p],
            strokeWidth: 0,
            fill: pathFill,
            drawShadow: false
          }));

          if (w.config.chart.dropShadow.enabled) {
            var filters = new Filters(_this.ctx);
            var shadow = w.config.chart.dropShadow;
            filters.dropShadow(renderedAreaPath, Object.assign({}, shadow, {
              noUserSpaceOnUse: true
            }), i);
          }

          elSeries.add(renderedAreaPath);
        }

        s.forEach(function (sj, j) {
          var markers = new Markers(_this.ctx);
          var opts = markers.getMarkerConfig('apexcharts-marker', i);

          var point = _this.graphics.drawMarker(dataPointsPos[j].x, dataPointsPos[j].y, opts);

          point.attr('rel', j);
          point.attr('j', j);
          point.attr('index', i);
          point.node.setAttribute('default-marker-size', opts.pSize);

          var elPointsWrap = _this.graphics.group({
            class: 'apexcharts-series-markers'
          });

          if (elPointsWrap) {
            elPointsWrap.add(point);
          }

          elPointsMain.add(elPointsWrap);
          elSeries.add(elPointsMain);
        });
        allSeries.push(elSeries);
      });
      this.drawPolygons({
        parent: ret
      });

      if (w.config.dataLabels.enabled) {
        var dataLabels = this.drawLabels();
        ret.add(dataLabels);
      }

      ret.add(this.yaxisLabels);
      allSeries.forEach(function (elS) {
        ret.add(elS);
      });
      return ret;
    }
  }, {
    key: "drawPolygons",
    value: function drawPolygons(opts) {
      var _this2 = this;

      var w = this.w;
      var parent = opts.parent;
      var yaxisTexts = w.globals.yAxisScale[0].result.reverse();
      var layers = yaxisTexts.length;
      var radiusSizes = [];
      var layerDis = this.size / (layers - 1);

      for (var i = 0; i < layers; i++) {
        radiusSizes[i] = layerDis * i;
      }

      radiusSizes.reverse();
      var polygonStrings = [];
      var lines = [];
      radiusSizes.forEach(function (radiusSize, r) {
        var polygon = _this2.getPolygonPos(radiusSize);

        var string = '';
        polygon.forEach(function (p, i) {
          if (r === 0) {
            var line = _this2.graphics.drawLine(p.x, p.y, 0, 0, Array.isArray(_this2.polygons.connectorColors) ? _this2.polygons.connectorColors[i] : _this2.polygons.connectorColors);

            lines.push(line);
          }

          if (i === 0) {
            _this2.yaxisLabelsTextsPos.push({
              x: p.x,
              y: p.y
            });
          }

          string += p.x + ',' + p.y + ' ';
        });
        polygonStrings.push(string);
      });
      polygonStrings.forEach(function (p, i) {
        var strokeColors = _this2.polygons.strokeColors;

        var polygon = _this2.graphics.drawPolygon(p, Array.isArray(strokeColors) ? strokeColors[i] : strokeColors, w.globals.radarPolygons.fill.colors[i]);

        parent.add(polygon);
      });
      lines.forEach(function (l) {
        parent.add(l);
      });

      if (w.config.yaxis[0].show) {
        this.yaxisLabelsTextsPos.forEach(function (p, i) {
          var yText = _this2.drawYAxisText(p.x, p.y, i, yaxisTexts[i]);

          _this2.yaxisLabels.add(yText);
        });
      }
    }
  }, {
    key: "drawYAxisText",
    value: function drawYAxisText(x, y, i, text) {
      var w = this.w;
      var yaxisConfig = w.config.yaxis[0];
      var formatter = w.globals.yLabelFormatters[0];
      var yaxisLabel = this.graphics.drawText({
        x: x + yaxisConfig.labels.offsetX,
        y: y + yaxisConfig.labels.offsetY,
        text: formatter(text, i),
        textAnchor: 'middle',
        fontSize: yaxisConfig.labels.style.fontSize,
        fontFamily: yaxisConfig.labels.style.fontFamily,
        foreColor: yaxisConfig.labels.style.color
      });
      return yaxisLabel;
    }
  }, {
    key: "drawLabels",
    value: function drawLabels() {
      var _this3 = this;

      var w = this.w;
      var limit = 10;
      var textAnchor = 'middle';
      var dataLabelsConfig = w.config.dataLabels;
      var elDataLabelsWrap = this.graphics.group({
        class: 'apexcharts-datalabels'
      });
      var polygonPos = this.getPolygonPos(this.size);
      var currPosX = 0;
      var currPosY = 0;
      w.globals.labels.forEach(function (label, i) {
        var formatter = dataLabelsConfig.formatter;
        var dataLabels = new DataLabels(_this3.ctx);

        if (polygonPos[i]) {
          currPosX = polygonPos[i].x;
          currPosY = polygonPos[i].y;

          if (Math.abs(polygonPos[i].x) >= limit) {
            if (polygonPos[i].x > 0) {
              textAnchor = 'start';
              currPosX += 10;
            } else if (polygonPos[i].x < 0) {
              textAnchor = 'end';
              currPosX -= 10;
            }
          } else {
            textAnchor = 'middle';
          }

          if (Math.abs(polygonPos[i].y) >= _this3.size - limit) {
            if (polygonPos[i].y < 0) {
              currPosY -= 10;
            } else if (polygonPos[i].y > 0) {
              currPosY += 10;
            }
          }

          var text = formatter(label, {
            seriesIndex: -1,
            dataPointIndex: i,
            w: w
          });
          dataLabels.plotDataLabelsText({
            x: currPosX,
            y: currPosY,
            text: text,
            textAnchor: textAnchor,
            i: i,
            j: i,
            parent: elDataLabelsWrap,
            dataLabelsConfig: dataLabelsConfig,
            offsetCorrection: false
          });
        }
      });
      return elDataLabelsWrap;
    }
  }, {
    key: "createPaths",
    value: function createPaths(pos, origin) {
      var _this4 = this;

      var linePathsTo = [];
      var linePathsFrom = [];
      var areaPathsTo = [];
      var areaPathsFrom = [];

      if (pos.length) {
        linePathsFrom = [this.graphics.move(origin.x, origin.y)];
        areaPathsFrom = [this.graphics.move(origin.x, origin.y)];
        var linePathTo = this.graphics.move(pos[0].x, pos[0].y);
        var areaPathTo = this.graphics.move(pos[0].x, pos[0].y);
        pos.forEach(function (p, i) {
          linePathTo += _this4.graphics.line(p.x, p.y);
          areaPathTo += _this4.graphics.line(p.x, p.y);

          if (i === pos.length - 1) {
            linePathTo += 'Z';
            areaPathTo += 'Z';
          }
        });
        linePathsTo.push(linePathTo);
        areaPathsTo.push(areaPathTo);
      }

      return {
        linePathsFrom: linePathsFrom,
        linePathsTo: linePathsTo,
        areaPathsFrom: areaPathsFrom,
        areaPathsTo: areaPathsTo
      };
    }
  }, {
    key: "getPathFrom",
    value: function getPathFrom(realIndex) {
      var w = this.w;
      var pathFrom = null;

      for (var pp = 0; pp < w.globals.previousPaths.length; pp++) {
        var gpp = w.globals.previousPaths[pp];

        if (gpp.paths.length > 0 && parseInt(gpp.realIndex) === parseInt(realIndex)) {
          if (typeof w.globals.previousPaths[pp].paths[0] !== 'undefined') {
            pathFrom = w.globals.previousPaths[pp].paths[0].d;
          }
        }
      }

      return pathFrom;
    }
  }, {
    key: "getDataPointsPos",
    value: function getDataPointsPos(dataRadiusArr, angleArr) {
      var dataPointsLen = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : this.dataPointsLen;
      dataRadiusArr = dataRadiusArr || [];
      angleArr = angleArr || [];
      var dataPointsPosArray = [];

      for (var j = 0; j < dataPointsLen; j++) {
        var curPointPos = {};
        curPointPos.x = dataRadiusArr[j] * Math.sin(angleArr[j]);
        curPointPos.y = -dataRadiusArr[j] * Math.cos(angleArr[j]);
        dataPointsPosArray.push(curPointPos);
      }

      return dataPointsPosArray;
    }
  }, {
    key: "getPolygonPos",
    value: function getPolygonPos(size) {
      var dotsArray = [];
      var angle = Math.PI * 2 / this.dataPointsLen;

      for (var i = 0; i < this.dataPointsLen; i++) {
        var curPos = {};
        curPos.x = size * Math.sin(i * angle);
        curPos.y = -size * Math.cos(i * angle);
        dotsArray.push(curPos);
      }

      return dotsArray;
    }
  }]);

  return Radar;
}();

/**
 * ApexCharts Radial Class for drawing Circle / Semi Circle Charts.
 * @module Radial
 **/

var Radial =
/*#__PURE__*/
function (_Pie) {
  _inherits(Radial, _Pie);

  function Radial(ctx) {
    var _this;

    _classCallCheck(this, Radial);

    _this = _possibleConstructorReturn(this, _getPrototypeOf(Radial).call(this, ctx));
    _this.ctx = ctx;
    _this.w = ctx.w;
    _this.animBeginArr = [0];
    _this.animDur = 0;
    var w = _this.w;
    _this.startAngle = w.config.plotOptions.radialBar.startAngle;
    _this.endAngle = w.config.plotOptions.radialBar.endAngle;
    _this.trackStartAngle = w.config.plotOptions.radialBar.track.startAngle;
    _this.trackEndAngle = w.config.plotOptions.radialBar.track.endAngle;
    _this.radialDataLabels = w.config.plotOptions.radialBar.dataLabels;
    if (!_this.trackStartAngle) _this.trackStartAngle = _this.startAngle;
    if (!_this.trackEndAngle) _this.trackEndAngle = _this.endAngle;
    if (_this.endAngle === 360) _this.endAngle = 359.99;
    _this.fullAngle = 360 - w.config.plotOptions.radialBar.endAngle - w.config.plotOptions.radialBar.startAngle;
    _this.margin = parseInt(w.config.plotOptions.radialBar.track.margin);
    return _this;
  }

  _createClass(Radial, [{
    key: "draw",
    value: function draw(series) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var ret = graphics.group({
        class: 'apexcharts-radialbar'
      });
      var elSeries = graphics.group();
      var centerY = this.defaultSize / 2;
      var centerX = w.globals.gridWidth / 2;
      var size = this.defaultSize / 2.05 - w.config.stroke.width - w.config.chart.dropShadow.blur;

      if (w.config.plotOptions.radialBar.size !== undefined) {
        size = w.config.plotOptions.radialBar.size;
      }

      var colorArr = w.globals.fill.colors;

      if (w.config.plotOptions.radialBar.track.show) {
        var elTracks = this.drawTracks({
          size: size,
          centerX: centerX,
          centerY: centerY,
          colorArr: colorArr,
          series: series
        });
        elSeries.add(elTracks);
      }

      var elG = this.drawArcs({
        size: size,
        centerX: centerX,
        centerY: centerY,
        colorArr: colorArr,
        series: series
      });
      elSeries.add(elG.g);

      if (w.config.plotOptions.radialBar.hollow.position === 'front') {
        elG.g.add(elG.elHollow);

        if (elG.dataLabels) {
          elG.g.add(elG.dataLabels);
        }
      }

      ret.add(elSeries);
      return ret;
    }
  }, {
    key: "drawTracks",
    value: function drawTracks(opts) {
      var w = this.w;
      var graphics = new Graphics(this.ctx);
      var g = graphics.group();
      var filters = new Filters(this.ctx);
      var fill = new Fill(this.ctx);
      var strokeWidth = this.getStrokeWidth(opts);
      opts.size = opts.size - strokeWidth / 2;

      for (var i = 0; i < opts.series.length; i++) {
        var elRadialBarTrack = graphics.group({
          class: 'apexcharts-radialbar-track apexcharts-track'
        });
        g.add(elRadialBarTrack);
        elRadialBarTrack.attr({
          id: 'apexcharts-tra