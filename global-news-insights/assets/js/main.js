// Main JS for interactive bits
(function(){
    document.addEventListener('DOMContentLoaded', function(){
        // Basic accessibility: toggle mobile menu (if implemented)
        var ticker = document.querySelector('.ticker');
        if (ticker) {
            // Duplicate list to create infinite scroll illusion
            ticker.innerHTML = ticker.innerHTML + ticker.innerHTML;
        }
    });
})();
