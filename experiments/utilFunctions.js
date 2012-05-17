/* Testing deveolp branch
 * Functions to implement in Maqinato classes
 */


/**
 * Load Javascript files selectively (Carlos Benítez: http://www.etnassoft.com/2011/05/18/incluyendo-ficheros-javascript-bajo-demanda-includes/)
 * INPUTS
 *  url: of the javascript file
 *  callback: to execute when the file is available
 * OUTPUTS
 **/
function loadScript(url, callback) {
    var script = document.createElement('script');
    if (script.readyState) { // IE
        script.onreadystatechange = function () {
            if (script.readyState === 'loaded' || script.readyState === 'complete') {
                script.onreadystatechange = null;
                callback();
            }
        };
    } else { // Others
        script.onload = function() {
            callback();
        };
    }
    script.src = url;
    document.getElementsByTagName('head')[0].appendChild(script);
}