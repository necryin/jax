'use strict';

App.controller("CommentsController", [
    "$scope",
    "$http",
    "$log",
    "CommentsService",
    "_",
    function($scope, $http, $log, CommentsService, _) {
        $scope.NO_COMMENT = 'Комментарий удален';
        $scope.comments = [];

        var buildTree = function(comments) {
            var idToNodeMap = [];
            var result = [];

            comments.forEach(function(el) {
                el.nodes = [];
                idToNodeMap[el.id] = el;
            });

            idToNodeMap.forEach(function(el, index) {
                if (typeof idToNodeMap[el.parent_id] != 'undefined') {
                    el.drop = true;
                    idToNodeMap[el.parent_id].nodes.push(el);
                }
            });

            idToNodeMap.forEach(function(el) {
                if(!el.hasOwnProperty('drop')) {
                    result.push(el);
                }
            });

            return result;
        };

        $scope.getTree = function(node) {
            return CommentsService.getTree(node.id)
                .success(function (comments) {
                    $log.info("Загрузка завершена");

                    node.nodes = buildTree(JSON.parse(comments));
                })
                .error(function(data, status) {
                    $log.error("Невозможно загрузить комментарии");
                    $log.error({status: status, data: data});
                });
        };

        $scope.update = function(text, node) {
            if(!text || text == node.text) {
                return;
            }
            return CommentsService.updateComment(text, node.id)
                .success(function () {
                    node.text = text;
                })
                .error(function(data, status) {
                    $log.error("Невозможно обновить комментарий");
                    $log.error({status: status, data: data});
                });
        };

        $scope.delete = function(node) {
            return CommentsService.deleteComment(node.id)
                .success(function () {
                    node.deleted = 1;
                    node.text = $scope.NO_COMMENT;
                })
                .error(function(data, status) {
                    $log.error("Невозможно удалить комментарий");
                    $log.error({status: status, data: data});
                });
        };

        $scope.insert = function(text, parent) {
            if(!text) {
                return;
            }
            parent = parent ? parent.id : null;
            return CommentsService.createComment(text, parent)
                .success(function (comment) {
                    var parsed = JSON.parse(comment);
                    if(parent == null) {
                        $scope.comments.unshift(parsed);
                    } else {
                        var root = _.findIndex($scope.comments, ['id', parsed.root_id]);
                        $scope.getTree($scope.comments[root]);
                    }
                })
                .error(function(data, status) {
                    $log.error("Невозможно создать комментарий");
                    $log.error({status: status, data: data});
                });
        };

        $scope.init = function() {
            CommentsService.getRoots()
                .success(function (comments) {
                    $log.info("Загрузка завершена");

                    $scope.comments = JSON.parse(comments);
                })
                .error(function(data, status) {
                    $log.error("Невозможно загрузить комментарии");
                    $log.error({status: status, data: data});
                });
        };

        $scope.init();
    }
]);
