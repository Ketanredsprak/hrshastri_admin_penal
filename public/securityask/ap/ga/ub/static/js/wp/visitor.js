(function() {
function loadScript(uri) {
const timeoutTime = 12000;
const script = document.createElement('script');
script.setAttribute('charset', 'UTF-8');
script.setAttribute('type', 'text/javascript');
script.setAttribute('async', true);
script.setAttribute('timeout', timeoutTime);
script.src = uri;
const timeoutHandle = setTimeout(function() {onError('Timeout');}, timeoutTime);
script.onerror = onError;
function onComplete() {
    clearTimeout(timeoutHandle);
}
function onError(result) {
    onComplete();
    console.error('Failed to load script! Uri:', uri, 'Result:', result);
}
script.onload = function () {
    onComplete();
};
const head = document.getElementsByTagName('HEAD')[0];
head.appendChild(script);
}
if(window.unblu && window.unblu.APIKEY) {console.warn('Existing unblu detected while loading unblu. Please check site for double injection!'); return;}
if (!window['x-unblu-tmp-window-name']) window['x-unblu-tmp-window-name']=window.name;
window.unblu = window.unblu || {};
window.unblu.APIKEY = 'MZsy5sFESYqU7MawXZgR_w';
window.unblu.entryPoint = 'SiteIntegrationLazyMain';
window.unblu.t = '_unblu_572F594F_21AA_4D30_8081_40F2793592AF';

function extractServerUrl(prefix) {
	var VISITOR_JS_PART = prefix + '/visitor.js';
	var script = document.querySelector('script[src*="' + VISITOR_JS_PART + '"]');
	if (!script) {
		console.warn('Could not find unblu script tag. Assuming relative path.');
       return '';
	}
	var regex = new RegExp('(.*)' + VISITOR_JS_PART.replace('/', '\/').replace('.', '\.'));
	var match = regex.exec(script.src);
	if (match == null || match.length < 1)
		console.error('Error loading unblu, can\'t extract unblu server from visitor.js script tag');
	else
		return match[1];
}
if(!window.unblu.SERVER) window.unblu.SERVER = extractServerUrl('/ap/ga/ub');
window['_unblu_572F594F_21AA_4D30_8081_40F2793592AF'] = {"o":{"isDeploymentModeProduction":true,"t":"_unblu_572F594F_21AA_4D30_8081_40F2793592AF","y7e":"x-unblu","g7e":false,"$":"","Hmt":true,"Umt":true,"multiAccount":true,"Wmt":false,"L":null,"O":12000,"H":1661327368471,"T":1661427603608,"agentAvailabilityVersion":1661406532000,"j":null,"F":"fr-FR","G":null,"M":null,"u":"","defaultOriginPublic":"","Ier":"ZTnGrx1pTH-x7xY4RSKLXQ"}};
window['_unblu_572F594F_21AA_4D30_8081_40F2793592AF']['o']['l'] = window['x-unblu-tmp-entrypath'] || '/ap/ga/ub';
loadScript(window.unblu.SERVER + '/ap/ga/ub' + '/static/js/wp/xmd1661327368471/Initializer.js');
})();