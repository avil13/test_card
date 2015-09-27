APP.factory('API', ['$http', 'swal',
    function($http, swal) {
        return {
            url: '',

            err: function(data, status) {
                console.log(status, data);
                var msg = data && data.error && data.error.message ? data.error.message : '';
                if (status === 403 || status === 401) {
                    msg = data;
                }
                swal('Возникли проблемы...', msg, 'error');
            },

            post: function(url, data, callback) {
                if (callback === undefined && data instanceof Function) {
                    callback = data;
                    data = {};
                }

                $http.post(this.url + url, data)
                    .success(function(data) {
                        if (data.message)
                            swal('', data.message);

                        if (callback)
                            callback(data);
                    })
                    .error(this.err);
            },

            // REST
            get: function(model, callback) {
                return $http({
                    method: 'GET',
                    url: this.url + model
                }).success(function(data) {
                    if (callback)
                        callback(data);
                }).error(this.err);
            },


            put: function(url, data, callback) {
                if (callback === undefined && data instanceof Function) {
                    callback = data;
                    data = {};
                }

                $http.put(this.url + url, data)
                    .success(function(data) {
                        if (data.message)
                            swal('', data.message);

                        if (callback)
                            callback(data);
                    })
                    .error(this.err);
            },

            show: function(model, id, callback) {
                return $http({
                    method: 'GET',
                    url: this.url + model + '/' + id,
                }).success(function(data) {
                    if (callback)
                        callback(data);
                }).error(this.err);
            },

            store: function(model, data, callback) {
                return $http({
                    method: 'POST',
                    url: this.url + model,
                    data: data
                }).success(function(data) {
                    if (callback)
                        callback(data);
                }).error(this.err);
            },

            update: function(model, id, data, callback) {
                return $http({
                    method: 'PUT',
                    url: this.url + model + '/' + id,
                    data: data
                }).success(function(data) {
                    if (callback)
                        callback(data);
                }).error(this.err);
            },

            edit: function(model, id, callback) {
                return $http({
                    method: 'GET',
                    url: this.url + model + '/' + id + '/edit',
                }).success(function(data) {
                    if (callback)
                        callback(data);
                }).error(this.err);
            },

            destroy: function(model, id, callback) {
                return $http.delete(this.url + model + '/' + id)
                    .success(function(data) {
                        if (callback)
                            callback(data);
                    }).error(this.err);
            },
        };
    }
]);