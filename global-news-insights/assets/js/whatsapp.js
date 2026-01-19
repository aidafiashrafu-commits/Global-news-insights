// Floating WhatsApp chat behaviour
(function(){
    document.addEventListener('DOMContentLoaded', function(){
        var btn = document.createElement('a');
        btn.href = 'https://wa.me/255717007449';
        btn.target = '_blank';
        btn.className = 'gni-whatsapp-float';
        btn.innerHTML = '<span aria-hidden="true">💬</span>';
        btn.setAttribute('aria-label','Chat on WhatsApp');
        document.body.appendChild(btn);
    });
})();
