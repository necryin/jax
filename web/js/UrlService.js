'use strict';

App.service("UrlService", function(){
    return {
        create: function(path, args) {
            args = args || {};
            var url = "/index.php?r=";
            url += path;
            for (var key in args) {
                url += "&"+key+"="+args[key];
            }
            return url;
        }
    };
});
