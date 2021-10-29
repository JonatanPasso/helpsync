/**
 * chamdas de api padronizadas do modulo de estoque
 */

export default {


    /**
     * Listar de fabricantes
     * @param options
     * @returns {*}
     */
    getFabricantes(options = {paginacao: false}) {

        return this.doGet('estoque/fabricantes/buscar', options);
    },

    /**
     * Listar marcas
     * @param options
     * @returns {*}
     */
    getMarcas(options = {paginacao: false}) {

        return this.doGet('estoque/marcas/buscar', options);
    },
    /**
     * Listar modelos
     * @param options
     * @returns {*}
     */
    getModelos(options = {paginacao: false}) {

        return this.doGet('estoque/modelos/buscar', options);
    },


    /**
     * Listar linhas de produtos
     * @param options
     * @returns {*}
     */
    getLinhas(options = {}) {

        return this.doGet('estoque/linhas/buscar', options);
    },


    /**
     * Listar categorias
     * @param options
     * @returns {*}
     */
    getCategorias(options = {}) {

        return this.doGet('estoque/categorias/buscar', options);
    },


    /**
     * Listar Unidades de Medidas
     * @param options
     * @returns {*}
     */
    getUnidades(options = {}) {

        return this.doGet('estoque/unidades/buscar', options);
    },


    /**
     * Listar Ncm
     * @param options
     * @returns {*}
     */
    getNcm(options = {}) {

        return this.doGet('estoque/ncm/buscar', options);
    },


    /**
     * Listar Produtos
     * @param options
     * @returns {*}
     */
    getProdutos(options = {}) {

        return this.doGet('estoque/produtos/buscar', options);
    },
}
