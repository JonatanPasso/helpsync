import 'jquery-slimscroll/jquery.slimscroll.min'

export default {
    bind: function (el, binding, vnode) {
        (function (_el, _binding) {

            if (_el.ignorar) return;

            setTimeout(function () {
                $(_el).slimscroll(_binding.value);

            }, 100);

            _el.ignorar = true;
            _el.autoscroll = _binding.value.autoscroll;
        }(el, binding));
    },
    update: function (el) {

        //relar automaticamente para baixo
        if (el.autoscroll) {
            setTimeout(function () {
                $(el).scrollTop($(el).get(0).scrollHeight);
            }, 1000);
        }

    }

}
