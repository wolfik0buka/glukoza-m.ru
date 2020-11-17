module.exports = {
    bind(el, binding) {
        //unbind
        document.documentElement.removeEventListener('click', el["_vue_clickaway_handler"], false);
        delete el["_vue_clickaway_handler"];

        var callback = binding.value
        if (typeof callback !== 'function') {
            if ('development' !== 'production') {
                Vue.util.warn('v-' + binding.name + '="' + binding.expression + '" expects a function value, ' + 'got ' + callback);
            }
            return;
        }
        var initialMacrotaskEnded = false
        setTimeout(function() { initialMacrotaskEnded = true }, 0);

        el["_vue_clickaway_handler"] = function(ev) {
            if (initialMacrotaskEnded && !el.contains(ev.target)) { return callback(ev); }
        };

        document.documentElement.addEventListener('click', el["_vue_clickaway_handler"], false);
    },
    update: function(el, binding) {
        if (binding.value === binding.oldValue) return;

        //unbind
        document.documentElement.removeEventListener('click', el["_vue_clickaway_handler"], false);
        delete el["_vue_clickaway_handler"];

        //bind
        var callback = binding.value
        if (typeof callback !== 'function') {
            if ('development' !== 'production') {
                Vue.util.warn('v-' + binding.name + '="' + binding.expression + '" expects a function value, ' + 'got ' + callback);
            }
            return;
        }
        var initialMacrotaskEnded = false
        setTimeout(function() { initialMacrotaskEnded = true }, 0);

        el["_vue_clickaway_handler"] = function(ev) {
            if (initialMacrotaskEnded && !el.contains(ev.target)) { return callback(ev); }
        };

        document.documentElement.addEventListener('click', el["_vue_clickaway_handler"], false);
    },
    unbind(el) {
        document.documentElement.removeEventListener('click', el["_vue_clickaway_handler"], false);
        delete el["_vue_clickaway_handler"];
    },
}