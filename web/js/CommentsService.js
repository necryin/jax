'use strict';

App.service("CommentsService", [
    "$http",
    "UrlService",
    function($http, UrlService) {
        return {
            getRoots: function() {
                return $http.get(UrlService.create("api/roots"));
            },
            getTree: function(root_id) {
                return $http.get(UrlService.create("api/tree", {root_id: root_id}));
            },
            createComment: function(text, parent_id) {
                return $http.post(UrlService.create("api/insert"), {text: text, parent_id: parent_id});
            },
            updateComment: function(text, id) {
                return $http.post(UrlService.create("api/update"), {text: text, id: id});
            },
            deleteComment: function(id) {
                return $http.post(UrlService.create("api/delete"), {id: id});
            }
        };
    }
]);
