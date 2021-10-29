import StringMask from 'string-mask';

/**
 * 0    Any numbers
 * 9    Any numbers (Optional)
 * #    Any numbers (recursive)
 * A    Any alphanumeric character
 * a    Any alphanumeric character (Optional) Not implemented yet
 * S    Any letter
 * U    Any letter (All lower case character will be mapped to uppercase)
 * L    Any letter (All upper case character will be mapped to lowercase)
 * $    Escape character, used to escape any of the special formatting characters.
 */


import Vue from "vue";
import {template} from "underscore";

export default {
    mask(value, mask, options) {


        let maskObj = new StringMask(mask, options); //optionsObject is optional
        let maskedValue = maskObj.apply(value);
        return maskedValue;
    }

}
