/**
 * ==================================================================================
 * Response handler for success/error responses of Laravel.
 * 
 * Required libraries:
 * - Toastr
 * 
 * ==================================================================================
 **/
import toastr from 'toastr';
import css from '../../../node_modules/toastr/build/toastr.css';

export default {

    data: function() {
        return {
            errors: {},
        };
    },

    methods: {

        /**
         * Get field error
         *
         * @return string
         **/
        getError: function(field) {
            let error = '';

            /* Check if field exists */
            if(error = this.errors[field]) {

                if(typeof error === 'string') {
                    return error;
                }

                if(Array.isArray(error) && error.length > 0) {
                    return error[0];
                }
            }

            return error;
        },

        /**
         * Reset error variable
         *
         * IMPORTANT: Weird instance where the error var won't get updated immediately
         * causing the hasError(<field-name>) method to not show the error
         */
        resetErrors: function() {
            this.errors = {};
        },

        /**
         * Remove specified error
         * 
         * @param string field
         */
        removeError: function(field) {
            delete this.errors[field];
        },

        /**
         * Parses Laravel error responses
         *
         * @param mixed error
         * @param string title
         * @param boolean fade
         **/
        parseError: function(error, title = null, fade = false) {
            this.parseResponse(error, 0, title, { fade: fade });
        },

        /**
         * Parses Laravel warning responses
         *
         * @param mixed warning
         * @param string title
         * @param boolean fade
         **/
        parseWarning: function(warning, title = null, fade = false) {
            this.parseResponse(warning, 1, title, { fade: fade });
        },        

        /**
         * Parses Laravel success responses
         *
         * @param mixed success
         * @param string title
         * @param boolean fade
         **/
        parseSuccess: function(success, title = null, fade = true) {
            this.parseResponse(success, 2, title, { fade: fade });
        },

        /**
         * Parses Laravel info responses
         *
         * @param mixed info
         * @param string title
         * @param boolean fade
         **/
        parseInfo: function(info, title = null, fade = true) {
            this.parseResponse(info, 3, title, { fade: fade });
        },        

        /**
         * Parse response array/object
         * 
         * @param  mixed result
         * @param  boolean type
         * @param  string title
         * @return string
         */
        parseResponse: function(result, type, title = null, option = {}) {
            /* Set needed variables */
            let message = '';
            let hasResponse = false, hasData = false, hasMultiError = false;


            if(typeof result === 'string') {
                message = result;
            }

            if(typeof result !== 'undefined') {
                /* Fetch and add in message */
                if(result.hasOwnProperty('message')) {
                    message = result.message;
                }
            }

            if(typeof result.data !== 'undefined') { //alert(result.response.status);
                if(result.data.hasOwnProperty('message') && result.data.message.length > 0) {
                    message = result.data.message;
                }
            }

            if(typeof result.response !== 'undefined') { //alert(result.response.status);
                /* Set needed checker vars; */
                hasData = result.response.hasOwnProperty('data');

                /* Fetch and add in response error message */
                if(hasData) {
                    if(result.response.data.hasOwnProperty('message') && result.response.data.message.length > 0) {
                        message = result.response.data.message;
                    }
                }

                /* Setup generic response messages only for error & warning response */
                if(type == 0 || type == 1) {
                    switch(result.response.status) {
                        case 404: message = 'Invalid or missing route';
                            break;
                        case 405: message = 'Method not allowed on route';
                            break;
                        case 422:

                            /* Check for errors field */
                            if(hasData) {
                                if(result.response.data.hasOwnProperty('errors')) { 
                                    let fieldsArray = result.response.data.errors; //console.log(fieldsArray);

                                    /* Set multi-line error trigger */
                                    hasMultiError = true;
                                    /* Set error var for hasError() */
                                    this.errors = fieldsArray;

                                    /* Fetch each error per item */
                                    for(let field in fieldsArray) { //console.log(field);
                                        for(let subfield in fieldsArray[field]) { //console.log(fieldsArray[field][subfield]);
                                            message += '<li>' + fieldsArray[field][subfield] + '</li>';
                                        }
                                    } //console.log(errorsMsg);
                                }
                            }

                            break;
                        case 500: message = 'Server error';
                            break;
                    }
                }
            }


            /* Display error message */
            if(!option.fade) {
                this.removeFadeTimer();
            } else {
                this.addFadeTimer();
            }

            /* Build options */
            let toastrOption = {
                allowHtml: hasMultiError,
                toastClass: 'toastr',
                positionClass: "toast-top-center mt-2",
                progressBar: true,
                closeButton: true,
                preventDuplicates: true,
            };

            /* Display notifications */
            switch(type) {
                // Error
                case 0: return toastr.error(message, title, toastrOption);
                // Success
                case 1: return toastr.warning(message, title, toastrOption);
                // Success
                case 2: return toastr.success(message, title, toastrOption);
                // Info
                case 3: return toastr.info(message, title, toastrOption);
            }
        },

        /**
         * Add in fade timer
         * 
         * @return integer
         */
        addFadeTimer: function(timer = 5000) {
            toastr.options.timeOut = timer;
        },

        /**
         * Remove in fade timer
         * 
         * @return integer
         */
        removeFadeTimer: function() {
            toastr.options.timeOut = 0;
        },        


        /**
         * ==================================================================================
         * @Checker
         * ==================================================================================
         **/

        /**
         * Check if field has error
         *
         * @return boolean
         **/
        hasError: function(field) {
            return field in this.errors;
        },

        /**
         * Check if errors is empty
         *
         * @return boolean
         **/
        hasEmptyError:function() {
            return this.isEmpty(this.errors);
        },
    }
}