'use strict';

(function() {
    angular.extend( angular, {
        toParam: toParam
    });

    function toParam( object, prefix ) {
        var stack = [];
        var value;
        var key;

        for( key in object ) {
            value = object[ key ];
            key = prefix ? prefix + '[' + key + ']' : key;

            if ( value === null ) {
                value = encodeURIComponent( key ) + '=';
            } else if ( typeof( value ) !== 'object' ) {
                value = encodeURIComponent( key ) + '=' + encodeURIComponent( value );
            } else {
                value = toParam( value, key );
            }

            stack.push( value );
        }

        return stack.join( '&' );
    }
}());

var App = angular.module("App", []);

App.config(function( $httpProvider ) {
    $httpProvider.defaults.headers.post[ 'Content-Type' ] = 'application/x-www-form-urlencoded;charset=utf-8';
    $httpProvider.defaults.transformRequest = function( data ) {
        return angular.isObject( data ) && String( data ) !== '[object File]' ? angular.toParam( data ) : data;
    };
});

function LodashFactory($window) {
    return $window._;
}

// Define dependencies
LodashFactory.$inject = ['$window'];

// Register factory
App.factory('_', LodashFactory);
