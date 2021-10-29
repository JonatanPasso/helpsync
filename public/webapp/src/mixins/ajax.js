import Swal from 'sweetalert2';

export default {

    data() {
        return {

            validationResponse: {},
            ajaxStatus: false,
            operacao: null,

        }
    },

    methods: {
        doPost: function (acao, dados, autocath = true) {
            return this.doAjax(acao, dados, 'POST', autocath);
        },

        doGet: function (acao, dados, autocath = true) {
            return this.doAjax(acao, dados, 'GET', autocath);
        },

        doAjax: function (acao, dados, method, autocath = true) {


            var aux = {};

            /**
             * Remover campos null
             */
            if (_.isObject(dados)) {
                for (var i in dados) {
                    if (dados[i] !== null) {
                        aux[i] = dados[i];
                    }
                }
            }

            var self = this;

            self.ajaxStatus = true;

            self.validationResponse = {};

            var token = '&authorization=' + TOKEN;


            var ajaxCall = $.ajax({
                type: method,
                url: HOST_API + '/api/' + acao + '?cliente_id=' + _.get(this.$root, 'CLIENTE.id', null) + token,
                data: method == 'POST' ? JSON.stringify(aux) : aux,
                // success: success,
                contentType: "application/json; charset=utf-8",
                dataType: 'json',
                context: self
            });


            ajaxCall.always(function () {
                self.ajaxStatus = false;
                self.operacao = null;
            }).fail(function (error) {


                if (autocath) {
                    if (error.status == 400) {
                        self.validationResponse = error.responseJSON;
                    } else if (error.responseJSON && error.responseJSON.erro) {
                        self.alertErro(error.responseJSON.erro);
                    } else {
                        self.alertErro(error.responseText ? error.responseText : 'Erro inesperado.');
                    }
                }
            });

            ajaxCall.dthen = function (arg, dot_path) {


                return this.then(function (result) {

                    /**
                     * if is dot notation
                     */
                    if (!_.isUndefined(dot_path) &&
                        _.isString(dot_path)) {
                        var aux = _.get(result, dot_path);

                        this[arg] = aux;

                    } else {
                        this[arg] = result;
                    }
                });
            }

            return ajaxCall;

        },

        doUpload: function _fileUpload(file, success, error) {

            var token = '&authorization=' + TOKEN;

            var formData = new FormData;

            if (!file.length) {
                file = [file];
            }

            for (var i in file) {
                formData.append('files[]', file[i]);
            }

            return $.ajax({

                url: HOST_API + '/api/geral/file-storage/upload?' + token,

                data: formData,
                type: 'POST',
                processData: false,
                contentType: false,

                // success: function (data) {
                //     success && success(data);
                // },
                //
                // beforeSend: function () {
                //
                // },
                // error: function (e) {
                //     error && error(e);
                // },
                // complete: function () {
                //
                // },

                xhr: function () {

                    var xhr = $.ajaxSettings.xhr();
                    xhr.onprogress = function e() {
                        // For downloads
                        if (e.lengthComputable) {
                            console.log(e.loaded / e.total);
                        }
                    };
                    xhr.upload.onprogress = function (e) {
                        // For uploads
                        if (e.lengthComputable) {
                            //  console.log((e.loaded / e.total) * 100);
                        }
                    };
                    return xhr;
                }
            });
        },

        alertSucesso: function (msg = 'Operacao realizada com sucesso.', titulo = 'ILLUMINARTI') {
            return Swal.fire({
                icon: 'success',
                title: titulo,
                text: msg,
            });
        },

        alertErro: function (msg = 'Ocorreu um erro inesperado.', titulo = 'ILLUMINARTI') {
            return Swal.fire({
                icon: 'error',
                title: titulo,
                text: msg,
            });
        },

        confirmar: function (cb, texto = 'Confirmar operação?', titulo = 'ILLUMINARTI') {

            var self = this;

            return Swal.fire({
                title: titulo,
                text: texto,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'NÃO, sair!',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK, Prosseguir!'
            }).then(function (result) {
                cb & cb.apply(self, [result.value]);
            });

        },

        isValid: function (campo) {
            if (this.validationResponse[campo] != undefined) {
                return 'is-invalid '
            }
        }
    }

}
