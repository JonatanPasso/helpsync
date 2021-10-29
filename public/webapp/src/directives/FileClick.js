export default {

    bind(el, binding, vnode) {

        el.inputFile = $('<input type="file" class="hidden"/>');

        el.inputFile.attr($(el).data());

        el.inputFile.insertAfter(el);
        el.inputFile.on('change', function () {
            if (this.files) {

                binding.value(this.files);
            }
        });
        $(el).on('click', function () {
            el.inputFile.val('');
            el.inputFile.trigger('click');
        });
        $(el).css({cursor: 'pointer'})
    }
}
