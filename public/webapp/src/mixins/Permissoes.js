export default {

    methods:{

        acesso(acesso){

            if(this.$root.USER.is_root == 'Y') return true;

            for(var i in this.$root.USER_PERMISSIONS){
                if(_.get(this.$root,`USER_PERMISSIONS[${i}].recurso.uid`) == acesso){
                    return _.get(this.$root,`USER_PERMISSIONS[${i}].acesso`) == 'Y';
                }
            }

            return false;
        }


    }

}
