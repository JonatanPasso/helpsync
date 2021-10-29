var _ = require('lodash');
export default {
    wfirst(value) {
        return _.isString(value) ? _.first(value.split(" ")) : value;
    }

}
