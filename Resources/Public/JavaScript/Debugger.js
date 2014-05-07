/**
 * Dumps debug output to the console.
 *
 * The debug output is read from the "data-debug" attribute of the clicked DOM element.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 */
var DisplaycontrollerDebugger = {
	dumpDebugData: function() {
		// Read the debug data
		var debugData = event.target.getAttribute('data-debug');
		// Read the log method
		var debugMethod = event.target.getAttribute('data-method');
		// Parse and output the debug data
		if (debugData) {
			// The debug data is urlencode'd on the server side
			var debugObject = JSON.parse(decodeURIComponent(debugData.replace(/\+/g, ' ')));
			switch (debugMethod) {
				case 'error':
					console.error(debugObject);
					break;
				case 'warn':
					console.warn(debugObject);
					break;
				default:
					console.log(debugObject);
			}
		}
	}
};