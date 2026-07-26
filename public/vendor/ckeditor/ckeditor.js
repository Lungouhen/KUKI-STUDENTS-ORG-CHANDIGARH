
window.ClassicEditor = {
    create: function(el) {
        return Promise.resolve({
            getData: function() { return el ? el.value : ''; },
            setData: function(val) { if (el) el.value = val; }
        });
    }
};
