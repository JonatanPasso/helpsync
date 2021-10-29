import moment from 'moment'
export default {
    dateTimeFormat(value, format) {
        return moment(value).format(format);
    }

}
